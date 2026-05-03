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
use App\Exports\ResponsesNasaTlxExport;
use App\Models\SurveyResponses;
use App\Models\Survey;
use App\Models\SurveyQuestions;
use App\Models\NasaTlxScore;
use App\Models\SurveyAiRecommendation;
use App\Services\GroqService;

class NasaTlxController extends Controller
{
    protected $groqService;

    public function __construct(GroqService $groqService)
    {
        $this->groqService = $groqService;
    }

    public function index(Request $request)
    {
        $user = auth()->user();

        if (auth()->user()->hasPermissionTo('nasa_tlx.index.full')) {
            $surveyTitles = Survey::whereHas('methods', function ($query) {
                $query->where('method_id', 5);
            })
                ->get(['surveys.id', 'surveys.title']);
        } else {
            $surveyTitles = Survey::where('user_id', $user->id)
                ->whereHas('methods', function ($query) {
                    $query->where('method_id', 5);
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

        return redirect()->route('account.nasa-tlx.id', ['id' => $lowestTitleId]);
    }

    public function show(Request $request, $id)
    {
        $userID = auth()->user()->id;
        $survey = Survey::find($id);
        $surveyName = $survey->title;
        $surveyTheme = $survey->theme;

        $cacheExpiredMinutes = 2 * 60;

        if (!auth()->user()->hasPermissionTo('nasa_tlx.index.full') && $survey->user_id != $userID) {
            return abort(403, 'Unauthorized');
        }

        if (auth()->user()->hasPermissionTo('nasa_tlx.index.full')) {
            $surveyTitles = Survey::whereHas('methods', function ($query) {
                $query->where('method_id', 5);
            })
                ->get(['surveys.id', 'surveys.title']);
        } else {
            $surveyTitles = Survey::where('user_id', $userID)
                ->whereHas('methods', function ($query) {
                    $query->where('method_id', 5);
                })
                ->get(['surveys.id', 'surveys.title']);
        }

        $nasaTlxScores = Cache::remember('nasa-tlx-scores-' . $id, $cacheExpiredMinutes, function () use ($id) {
            return NasaTlxScore::where('survey_id', $id)->get();
        });

        $respondentCount = $nasaTlxScores->count();
        $demographicRespondents = $this->demographicRespondents($id);
        $averageNasaTlx = $this->calculateAverageNasaTlx($nasaTlxScores);
        $nasaTlxChartData = $this->getNasaTlxChartData($nasaTlxScores);
        $nasaTlxSurveyResults = $this->getNasaTlxResults($nasaTlxScores);
        $getAverageDimension = $this->getAverageDimension($nasaTlxScores);
        $getResumeDescription = $this->getResumeDescription($getAverageDimension, $surveyTheme);

        // Get AI recommendation if exists
        $aiRecommendation = $survey->getAiRecommendation('NASA-TLX');

        return inertia('Account/NasaTlx/Index', [
            'surveyTitles' => $surveyTitles,
            'survey' => $survey,
            'resumeDescription' => $getResumeDescription,
            'averageDimension' => $getAverageDimension,
            'respondentCount' => $respondentCount,
            'demographicRespondents' => $demographicRespondents,
            'averageNasaTlx' => $averageNasaTlx,
            'nasaTlxChartData' => $nasaTlxChartData,
            'nasaTlxSurveyResults' => $nasaTlxSurveyResults,
            'aiRecommendation' => $aiRecommendation
        ])->with('currentSurveyTitle', $survey->title);
    }

    public function generateAiRecommendation(Request $request, $id)
    {
        try {
            $survey = Survey::findOrFail($id);

            // Check authorization
            if (!auth()->user()->hasPermissionTo('nasa_tlx.index.full') && $survey->user_id != auth()->id()) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            // Get survey data for analysis
            $nasaTlxScores = NasaTlxScore::where('survey_id', $id)->get();

            if ($nasaTlxScores->isEmpty()) {
                return response()->json(['error' => 'Tidak ada data respons untuk dianalisis'], 400);
            }

            // Calculate analysis data
            $getAverageDimension = $this->getAverageDimension($nasaTlxScores);
            $getResumeDescription = $this->getResumeDescription($getAverageDimension, $survey->theme);

            if (!$getResumeDescription) {
                return response()->json(['error' => 'Tidak dapat menghasilkan deskripsi resume'], 400);
            }

            // Generate AI recommendation
            $aiRecommendation = $this->groqService->generateSurveyRecommendation(
                'NASA-TLX',
                $getResumeDescription,
                $survey->theme
            );

            // Save or update AI recommendation
            SurveyAiRecommendation::updateOrCreate(
                [
                    'survey_id' => $id,
                    'method_type' => 'NASA-TLX'
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
                'generated_at' => now()->format('d/m/Y H:i')
            ]);

        } catch (\Exception $e) {
            \Log::error('NASA-TLX AI Recommendation Error: ' . $e->getMessage());
            return response()->json(['error' => 'Terjadi kesalahan saat menghasilkan rekomendasi'], 500);
        }
    }

    private function demographicRespondents($surveyId)
    {
        $responses = SurveyResponses::where('survey_id', $surveyId)
            ->whereHas('nasaTlxScores')
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

    private function calculateAverageNasaTlx($nasaTlxScores)
    {
        if ($nasaTlxScores->isEmpty()) {
            return 0;
        }

        $totalScore = 0;
        foreach ($nasaTlxScores as $score) {
            $totalScore += $score->final_score;
        }

        $averageScore = $totalScore / count($nasaTlxScores);
        return number_format($averageScore, 2);
    }

    private function getNasaTlxChartData($nasaTlxScores)
    {
        $chartData = [
            'mental_demand' => [],
            'physical_demand' => [],
            'temporal_demand' => [],
            'performance' => [],
            'effort' => [],
            'frustration' => []
        ];

        foreach ($nasaTlxScores as $score) {
            $chartData['mental_demand'][] = $score->mental_demand;
            $chartData['physical_demand'][] = $score->physical_demand;
            $chartData['temporal_demand'][] = $score->temporal_demand;
            $chartData['performance'][] = $score->performance;
            $chartData['effort'][] = $score->effort;
            $chartData['frustration'][] = $score->frustration;
        }

        return response()->json($chartData);
    }

    private function getNasaTlxResults($nasaTlxScores)
    {
        $nasaTlxSurveyResults = [];

        foreach ($nasaTlxScores as $score) {
            $nasaTlxSurveyResults[] = [
                'id' => $score->id,
                'respondentName' => $score->surveyResponse->first_name . " " . $score->surveyResponse->surname,
                'finalScore' => $score->final_score,
                'dimensions' => [
                    'mental_demand' => $score->mental_demand,
                    'physical_demand' => $score->physical_demand,
                    'temporal_demand' => $score->temporal_demand,
                    'performance' => $score->performance,
                    'effort' => $score->effort,
                    'frustration' => $score->frustration,
                ],
            ];
        }

        return $nasaTlxSurveyResults;
    }

    private function getAverageDimension($nasaTlxScores)
    {
        if ($nasaTlxScores->isEmpty()) {
            return null;
        }

        $dimensions = [
            'mental_demand' => 0,
            'physical_demand' => 0,
            'temporal_demand' => 0,
            'performance' => 0,
            'effort' => 0,
            'frustration' => 0
        ];

        foreach ($nasaTlxScores as $score) {
            $dimensions['mental_demand'] += $score->mental_demand;
            $dimensions['physical_demand'] += $score->physical_demand;
            $dimensions['temporal_demand'] += $score->temporal_demand;
            $dimensions['performance'] += $score->performance;
            $dimensions['effort'] += $score->effort;
            $dimensions['frustration'] += $score->frustration;
        }

        $count = $nasaTlxScores->count();
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

        // Mental Demand
        if ($dimensions['mental_demand'] < 33) {
            $descriptions[] = "Beban mental $surveyTheme dianggap rendah oleh sebagian besar pengguna.";
        } elseif ($dimensions['mental_demand'] < 67) {
            $descriptions[] = "Beban mental $surveyTheme dianggap sedang oleh pengguna.";
        } else {
            $descriptions[] = "Beban mental $surveyTheme dianggap tinggi, pengguna memerlukan konsentrasi yang signifikan.";
        }

        // Physical Demand
        if ($dimensions['physical_demand'] < 33) {
            $descriptions[] = "Beban fisik yang dibutuhkan untuk menggunakan $surveyTheme sangat minimal.";
        } elseif ($dimensions['physical_demand'] < 67) {
            $descriptions[] = "Beban fisik untuk menggunakan $surveyTheme termasuk sedang.";
        } else {
            $descriptions[] = "Beban fisik yang diperlukan untuk menggunakan $surveyTheme cukup tinggi.";
        }

        // Temporal Demand
        if ($dimensions['temporal_demand'] < 33) {
            $descriptions[] = "Waktu yang dibutuhkan untuk menggunakan $surveyTheme dirasa tidak memburu.";
        } elseif ($dimensions['temporal_demand'] < 67) {
            $descriptions[] = "Waktu untuk menggunakan $surveyTheme dianggap cukup ketat.";
        } else {
            $descriptions[] = "Pengguna merasa terburu-buru saat menggunakan $surveyTheme.";
        }

        // Performance
        if ($dimensions['performance'] < 33) {
            $descriptions[] = "Sebagian besar pengguna merasa kurang berhasil mencapai tujuan mereka.";
        } elseif ($dimensions['performance'] < 67) {
            $descriptions[] = "Pengguna merasa cukup berhasil dalam mencapai target mereka.";
        } else {
            $descriptions[] = "Pengguna sangat berhasil mencapai tujuan mereka saat menggunakan $surveyTheme.";
        }

        // Effort
        if ($dimensions['effort'] < 33) {
            $descriptions[] = "Usaha yang diperlukan untuk menggunakan $surveyTheme sangat minim.";
        } elseif ($dimensions['effort'] < 67) {
            $descriptions[] = "Diperlukan usaha sedang untuk menggunakan $surveyTheme dengan baik.";
        } else {
            $descriptions[] = "Pengguna perlu mengeluarkan banyak usaha untuk menggunakan $surveyTheme secara efektif.";
        }

        // Frustration
        if ($dimensions['frustration'] < 33) {
            $descriptions[] = "Tingkat frustrasi pengguna terhadap $surveyTheme sangat rendah.";
        } elseif ($dimensions['frustration'] < 67) {
            $descriptions[] = "Pengguna mengalami tingkat frustrasi yang sedang saat menggunakan $surveyTheme.";
        } else {
            $descriptions[] = "Pengguna mengalami tingkat frustrasi yang tinggi terhadap $surveyTheme.";
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

            // Check if survey has NASA-TLX method
            if (!$survey->methods()->where('method_id', 5)->exists()) {
                continue;
            }

            // Check permissions
            if (!$user->hasPermissionTo('nasa_tlx.index.full') && $survey->user_id != $user->id) {
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
            $fileName = $survey->title . '_' . $dateTime . '_NASA-TLX_export.xlsx';
        } else {
            $fileName = 'Multiple_NASA-TLX_Surveys_' . $dateTime . '_export.xlsx';
        }

        return Excel::download(new ResponsesNasaTlxExport($validSurveyIds), $fileName);
    }
}
