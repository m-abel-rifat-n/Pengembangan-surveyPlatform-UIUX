<?php

namespace App\Http\Controllers\Account;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use App\Exports\ResponsesVisawiSExport;
use App\Models\SurveyResponses;
use App\Models\Survey;
use App\Models\SurveyQuestions;
use App\Models\VisawiSScore;
use App\Models\SurveyAiRecommendation;
use App\Services\GroqService;

class VisawiSController extends Controller
{
    protected $groqService;

    public function __construct(GroqService $groqService)
    {
        $this->groqService = $groqService;
    }

    public function index(Request $request)
    {
        $user = auth()->user();

        if (auth()->user()->hasPermissionTo('visawi_s.index.full')) {
            $surveyTitles = Survey::whereHas('methods', function ($query) {
                $query->where('method_id', 6);
            })
                ->get(['surveys.id', 'surveys.title']);
        } else {
            $surveyTitles = Survey::where('user_id', $user->id)
                ->whereHas('methods', function ($query) {
                    $query->where('method_id', 6);
                })
                ->get(['surveys.id', 'surveys.title']);

            if ($surveyTitles->isEmpty()) {
                return redirect()->route('account.surveys.create');
            }
        }

        $sortedSurveyTitles = $surveyTitles->sortBy('id');

        if ($sortedSurveyTitles->isEmpty()) {
            return redirect()->route('account.surveys.create');
        }

        $lowestTitleId = $sortedSurveyTitles->first()->id;

        return redirect()->route('account.visawi-s.id', ['id' => $lowestTitleId]);
    }

    public function show(Request $request, $id)
    {
        $userID = auth()->user()->id;
        $survey = Survey::find($id);
        $surveyName = $survey->title;
        $surveyTheme = $survey->theme;

        $cacheExpiredMinutes = 2 * 60;

        if (!auth()->user()->hasPermissionTo('visawi_s.index.full') && $survey->user_id != $userID) {
            return abort(403, 'Unauthorized');
        }

        if (auth()->user()->hasPermissionTo('visawi_s.index.full')) {
            $surveyTitles = Survey::whereHas('methods', function ($query) {
                $query->where('method_id', 6);
            })
                ->get(['surveys.id', 'surveys.title']);
        } else {
            $surveyTitles = Survey::where('user_id', $userID)
                ->whereHas('methods', function ($query) {
                    $query->where('method_id', 6);
                })
                ->get(['surveys.id', 'surveys.title']);
        }

        $visawiScores = Cache::remember('visawi-s-scores-' . $id, $cacheExpiredMinutes, function () use ($id) {
            return VisawiSScore::where('survey_id', $id)->get();
        });

        $respondentCount = $visawiScores->count();
        $demographicRespondents = $this->demographicRespondents($id);
        $averageVisawiS = $this->calculateAverageVisawiS($visawiScores);
        $visawiChartData = $this->getVisawiChartData($visawiScores);
        $visawiSurveyResults = $this->getVisawiResults($visawiScores);
        $getAverageDimension = $this->getAverageDimension($visawiScores);
        $getResumeDescription = $this->getResumeDescription($getAverageDimension, $surveyTheme);

        // Get AI recommendation if exists
        $aiRecommendation = $survey->getAiRecommendation('VisAWI-S');

        return inertia('Account/VisawiS/Index', [
            'surveyTitles' => $surveyTitles,
            'survey' => $survey,
            'resumeDescription' => $getResumeDescription,
            'averageDimension' => $getAverageDimension,
            'respondentCount' => $respondentCount,
            'demographicRespondents' => $demographicRespondents,
            'averageVisawiS' => $averageVisawiS,
            'visawiChartData' => $visawiChartData,
            'visawiSurveyResults' => $visawiSurveyResults,
            'aiRecommendation' => $aiRecommendation
        ])->with('currentSurveyTitle', $survey->title);
    }

    public function generateAiRecommendation(Request $request, $id)
    {
        try {
            $survey = Survey::findOrFail($id);

            // Check authorization
            if (!auth()->user()->hasPermissionTo('visawi_s.index.full') && $survey->user_id != auth()->id()) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            // Get survey data for analysis
            $visawiScores = VisawiSScore::where('survey_id', $id)->get();

            if ($visawiScores->isEmpty()) {
                return response()->json(['error' => 'Tidak ada data respons untuk dianalisis'], 400);
            }

            // Calculate analysis data
            $getAverageDimension = $this->getAverageDimension($visawiScores);
            $getResumeDescription = $this->getResumeDescription($getAverageDimension, $survey->theme);

            if (!$getResumeDescription) {
                return response()->json(['error' => 'Tidak dapat menghasilkan deskripsi resume'], 400);
            }

            // Generate AI recommendation
            $aiRecommendation = $this->groqService->generateSurveyRecommendation(
                'VisAWI-S',
                $getResumeDescription,
                $survey->theme
            );

            // Save or update AI recommendation
            SurveyAiRecommendation::updateOrCreate(
                [
                    'survey_id' => $id,
                    'method_type' => 'VisAWI-S'
                ],
                [
                    'resume_description' => $getResumeDescription,
                    'ai_recommendation' => $aiRecommendation,
                    'generated_at' => now()
                ]
            );

            return response()->json([
                'success' => true,
                'recommendation' => $aiRecommendation,
                'generated_at' => now()->toIso8601String()
            ]);

        } catch (\Exception $e) {
            \Log::error('VisAWI-S AI Recommendation Error: ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan saat menghasilkan rekomendasi'], 500);
        }
    }

    private function demographicRespondents($surveyId)
    {
        $responses = SurveyResponses::where('survey_id', $surveyId)
            ->whereHas('visawiSScores')
            ->get();

        if ($responses->isEmpty()) {
            return null;
        }

        $demographics = [
            'gender' => [],
            'profession' => [],
            'educational_background' => [],
            'age' => []
        ];

        foreach ($responses as $response) {
            foreach ($demographics as $key => $value) {
                if ($key === 'age') {
                    $age_category = $this->categorizeAge($response->birth_date);
                    if (isset($demographics[$key][$age_category])) {
                        $demographics[$key][$age_category]++;
                    } else {
                        $demographics[$key][$age_category] = 1;
                    }
                } else {
                    if (isset($demographics[$key][$response[$key]])) {
                        $demographics[$key][$response[$key]]++;
                    } else {
                        $demographics[$key][$response[$key]] = 1;
                    }
                }
            }
        }

        return $demographics;
    }

    private function categorizeAge($birth_date)
    {
        $birth_date = Carbon::parse($birth_date);
        $age = $birth_date->age;

        if ($age < 18) {
            return '0-17';
        } elseif ($age >= 18 && $age < 25) {
            return '18-24';
        } elseif ($age >= 25 && $age < 35) {
            return '25-34';
        } elseif ($age >= 35 && $age < 45) {
            return '35-44';
        } elseif ($age >= 45 && $age < 55) {
            return '45-54';
        } elseif ($age >= 55 && $age < 65) {
            return '55-64';
        } else {
            return '65+';
        }
    }

    private function calculateAverageVisawiS($visawiScores)
    {
        if ($visawiScores->isEmpty()) {
            return 0;
        }

        $totalScore = 0;
        foreach ($visawiScores as $score) {
            $totalScore += $score->final_score;
        }

        $averageScore = $totalScore / count($visawiScores);
        return number_format($averageScore, 2);
    }

    private function getVisawiChartData($visawiScores)
    {
        $chartData = [
            'simplicity' => [],
            'diversity' => [],
            'colorfulness' => [],
            'craftsmanship' => []
        ];

        foreach ($visawiScores as $score) {
            $chartData['simplicity'][] = $score->simplicity;
            $chartData['diversity'][] = $score->diversity;
            $chartData['colorfulness'][] = $score->colorfulness;
            $chartData['craftsmanship'][] = $score->craftsmanship;
        }

        return $chartData;
    }

    private function getVisawiResults($visawiScores)
    {
        $visawiSurveyResults = [];

        foreach ($visawiScores as $score) {
            $visawiSurveyResults[] = [
                'id' => $score->id,
                'respondentName' => $score->surveyResponse->first_name . " " . $score->surveyResponse->surname,
                'finalScore' => $score->final_score,
                'dimensions' => [
                    'simplicity' => $score->simplicity,
                    'diversity' => $score->diversity,
                    'colorfulness' => $score->colorfulness,
                    'craftsmanship' => $score->craftsmanship,
                ],
            ];
        }

        return $visawiSurveyResults;
    }

    private function getAverageDimension($visawiScores)
    {
        if ($visawiScores->isEmpty()) {
            return null;
        }

        $dimensions = [
            'simplicity' => 0,
            'diversity' => 0,
            'colorfulness' => 0,
            'craftsmanship' => 0
        ];

        foreach ($visawiScores as $score) {
            $dimensions['simplicity'] += $score->simplicity;
            $dimensions['diversity'] += $score->diversity;
            $dimensions['colorfulness'] += $score->colorfulness;
            $dimensions['craftsmanship'] += $score->craftsmanship;
        }

        $count = $visawiScores->count();
        foreach ($dimensions as $key => $value) {
            $dimensions[$key] = round($value / $count, 2);
        }

        return $dimensions;
    }

    private function getResumeDescription($dimensions, $surveyTheme)
    {
        if ($dimensions == null) {
            return null;
        }

        $descriptions = [];

        // Simplicity
        if ($dimensions['simplicity'] < 3.5) {
            $descriptions[] = "Tata letak (layout) $surveyTheme dianggap kompleks dan sulit dipahami pengguna.";
        } elseif ($dimensions['simplicity'] < 5.5) {
            $descriptions[] = "Tata letak $surveyTheme dianggap cukup sederhana dan mudah dipahami.";
        } else {
            $descriptions[] = "Tata letak $surveyTheme sangat sederhana dan jelas dipahami pengguna.";
        }

        // Diversity
        if ($dimensions['diversity'] < 3.5) {
            $descriptions[] = "Desain visual $surveyTheme kurang menampilkan keberagaman elemen.";
        } elseif ($dimensions['diversity'] < 5.5) {
            $descriptions[] = "Desain visual $surveyTheme menampilkan keberagaman elemen yang cukup baik.";
        } else {
            $descriptions[] = "Desain visual $surveyTheme menampilkan keberagaman elemen yang sangat baik.";
        }

        // Colorfulness
        if ($dimensions['colorfulness'] < 3.5) {
            $descriptions[] = "Penggunaan warna pada $surveyTheme dianggap kurang menarik.";
        } elseif ($dimensions['colorfulness'] < 5.5) {
            $descriptions[] = "Komposisi warna pada $surveyTheme cukup menarik dan harmonis.";
        } else {
            $descriptions[] = "Komposisi warna pada $surveyTheme sangat menarik dan memuaskan pengguna.";
        }

        // Craftsmanship
        if ($dimensions['craftsmanship'] < 3.5) {
            $descriptions[] = "Tata letak $surveyTheme dirancang dengan kurang profesional.";
        } elseif ($dimensions['craftsmanship'] < 5.5) {
            $descriptions[] = "Tata letak $surveyTheme dirancang dengan tingkat profesionalisme yang cukup.";
        } else {
            $descriptions[] = "Tata letak $surveyTheme dirancang dengan tingkat profesionalisme yang sangat tinggi.";
        }

        return implode(" ", $descriptions);
    }

    public function export(Request $request)
    {
        $surveyIds = $request->get('surveys');

        if (!$surveyIds) {
            return redirect()->back()->with('error', 'Tidak ada survei yang dipilih untuk diekspor');
        }

        // Convert comma-separated string to array
        $surveyIdsArray = explode(',', $surveyIds);

        // Validate survey IDs and check permissions
        $user = auth()->user();
        $validSurveyIds = [];

        foreach ($surveyIdsArray as $surveyId) {
            $survey = Survey::find($surveyId);

            if (!$survey) {
                continue;
            }

            // Check if survey has VisAWI-S method
            if (!$survey->methods()->where('method_id', 6)->exists()) {
                continue;
            }

            // Check permissions
            if (!$user->hasPermissionTo('visawi_s.index.full') && $survey->user_id != $user->id) {
                continue;
            }

            $validSurveyIds[] = $surveyId;
        }

        if (empty($validSurveyIds)) {
            return redirect()->back()->with('error', 'Tidak ada survei yang valid untuk diekspor');
        }

        // Generate filename
        $dateTime = now()->format('Y-m-d_H-i');
        $surveyCount = count($validSurveyIds);

        if ($surveyCount === 1) {
            $survey = Survey::find($validSurveyIds[0]);
            $fileName = $survey->title . '_' . $dateTime . '_VisAWI-S_export.xlsx';
        } else {
            $fileName = 'Multiple_VisAWI-S_Surveys_' . $dateTime . '_export.xlsx';
        }

        return Excel::download(new ResponsesVisawiSExport($validSurveyIds), $fileName);
    }
}
