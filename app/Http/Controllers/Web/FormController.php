<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Survey;
use App\Models\SurveyResponses;
use App\Models\SurveyHasMethods;
use App\Models\SurveyQuestions;
use App\Models\NasaTlxScore;
use App\Models\VisawiSScore;
use Illuminate\Support\Facades\DB;

class FormController extends Controller
{
    public function show($id, $slug)
    {
        $user = auth()->user();
        $survey = Survey::where('id', $id)->where('slug', $slug)->firstOrFail();
        if ($survey->status == 'Private' && $survey->user_id !== $user->id) {
            abort(403, 'This survey is not available.');
        }

        $response = SurveyResponses::where('email', $user->email)->where('survey_id', $survey->id)->first();
        $surveyMethods = SurveyHasMethods::where('survey_id', $survey->id)->get();
        $surveyMethodIds = $surveyMethods->pluck('method_id')->toArray();
        $surveyQuestions = SurveyQuestions::where('survey_id', $survey->id)->get();

        if ($response) {
            abort(403, 'You have already submitted this survey and cannot participate again.');
        }

        return inertia('Web/Form', [
            'surveys' => $survey,
            'auth' => auth()->user(),
            'surveyMethods' => $surveyMethods,
            'surveyMethodIds' => $surveyMethodIds,
            'surveyQuestions' => $surveyQuestions
        ]);
    }



    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'user_id'               => 'required',
            'survey_id'             => 'required|exists:surveys,id',
            'first_name'             => 'required',
            'surname'              => 'required',
            'email'                 => 'required|email',
            'birth_date'                   => 'required|date',
            'gender'                => 'required',
            'profession'            => 'required',
            'educational_background' => 'required',
            'response_data'        => 'required|json',
        ]);
        $userId = auth()->user()->id;

        $survey = Survey::find($validatedData['survey_id']);
        $surveyUserId = $survey->user_id;

        if ($userId === $surveyUserId) {
            abort(403, 'Survey authors cannot submit their own surveys.');
        }

        $responseData = json_decode($validatedData['response_data'], true);

        DB::beginTransaction();
        try {
            $surveyResponse = SurveyResponses::create($validatedData);

            // Handle NASA-TLX data if present
            if (isset($responseData['nasa_tlx'])) {
                $nasaTlxData = $responseData['nasa_tlx'];
                $finalScore = ($nasaTlxData['mental_demand'] + 
                              $nasaTlxData['physical_demand'] + 
                              $nasaTlxData['temporal_demand'] + 
                              $nasaTlxData['performance'] + 
                              $nasaTlxData['effort'] + 
                              $nasaTlxData['frustration']) / 6;

                NasaTlxScore::create([
                    'survey_id' => $validatedData['survey_id'],
                    'user_id' => $userId,
                    'survey_response_id' => $surveyResponse->id,
                    'mental_demand' => $nasaTlxData['mental_demand'],
                    'physical_demand' => $nasaTlxData['physical_demand'],
                    'temporal_demand' => $nasaTlxData['temporal_demand'],
                    'performance' => $nasaTlxData['performance'],
                    'effort' => $nasaTlxData['effort'],
                    'frustration' => $nasaTlxData['frustration'],
                    'final_score' => round($finalScore, 2),
                ]);
            }

            // Handle VisAWI-S data if present
            if (isset($responseData['visawi_s'])) {
                $visawiSData = $responseData['visawi_s'];
                $finalScore = ($visawiSData['simplicity'] + 
                              $visawiSData['diversity'] + 
                              $visawiSData['colorfulness'] + 
                              $visawiSData['craftsmanship']) / 4;

                VisawiSScore::create([
                    'survey_id' => $validatedData['survey_id'],
                    'user_id' => $userId,
                    'survey_response_id' => $surveyResponse->id,
                    'simplicity' => $visawiSData['simplicity'],
                    'diversity' => $visawiSData['diversity'],
                    'colorfulness' => $visawiSData['colorfulness'],
                    'craftsmanship' => $visawiSData['craftsmanship'],
                    'final_score' => round($finalScore, 2),
                ]);
            }

            DB::commit();
            return redirect('/')->with('status', 'Pengisian Survey Berhasil!');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Form submission error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
        }
    }
}
