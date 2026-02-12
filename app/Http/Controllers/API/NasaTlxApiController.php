<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SurveyResponses;
use App\Models\NasaTlxScore;
use App\Models\Survey;
use Illuminate\Support\Facades\DB;

class NasaTlxApiController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'survey_id' => 'required|exists:surveys,id',
                'user_id' => 'required|exists:users,id',
                'first_name' => 'required|string',
                'surname' => 'required|string',
                'email' => 'required|email',
                'birth_date' => 'required|date',
                'gender' => 'required|string',
                'profession' => 'required|string',
                'educational_background' => 'required|string',
                'mental_demand' => 'required|integer|min:0|max:100',
                'physical_demand' => 'required|integer|min:0|max:100',
                'temporal_demand' => 'required|integer|min:0|max:100',
                'performance' => 'required|integer|min:0|max:100',
                'effort' => 'required|integer|min:0|max:100',
                'frustration' => 'required|integer|min:0|max:100',
            ]);

            // Start transaction
            DB::beginTransaction();

            // Create survey response
            $surveyResponse = SurveyResponses::create([
                'survey_id' => $validated['survey_id'],
                'user_id' => $validated['user_id'],
                'first_name' => $validated['first_name'],
                'surname' => $validated['surname'],
                'email' => $validated['email'],
                'birth_date' => $validated['birth_date'],
                'gender' => $validated['gender'],
                'profession' => $validated['profession'],
                'educational_background' => $validated['educational_background'],
                'response_data' => json_encode([
                    'nasa_tlx' => [
                        'mental_demand' => $validated['mental_demand'],
                        'physical_demand' => $validated['physical_demand'],
                        'temporal_demand' => $validated['temporal_demand'],
                        'performance' => $validated['performance'],
                        'effort' => $validated['effort'],
                        'frustration' => $validated['frustration'],
                    ]
                ]),
            ]);

            // Calculate final score (Mean of all dimensions)
            $finalScore = ($validated['mental_demand'] + 
                          $validated['physical_demand'] + 
                          $validated['temporal_demand'] + 
                          $validated['performance'] + 
                          $validated['effort'] + 
                          $validated['frustration']) / 6;

            // Create NASA-TLX score
            $nasaTlxScore = NasaTlxScore::create([
                'survey_id' => $validated['survey_id'],
                'user_id' => $validated['user_id'],
                'survey_response_id' => $surveyResponse->id,
                'mental_demand' => $validated['mental_demand'],
                'physical_demand' => $validated['physical_demand'],
                'temporal_demand' => $validated['temporal_demand'],
                'performance' => $validated['performance'],
                'effort' => $validated['effort'],
                'frustration' => $validated['frustration'],
                'final_score' => round($finalScore, 2),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'NASA-TLX response recorded successfully',
                'data' => $nasaTlxScore
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('NASA-TLX API Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to save response: ' . $e->getMessage()
            ], 500);
        }
    }
}
