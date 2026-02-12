<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SurveyResponses;
use App\Models\VisawiSScore;
use App\Models\Survey;
use Illuminate\Support\Facades\DB;

class VisawiSApiController extends Controller
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
                'simplicity' => 'required|integer|min:1|max:7',
                'diversity' => 'required|integer|min:1|max:7',
                'colorfulness' => 'required|integer|min:1|max:7',
                'craftsmanship' => 'required|integer|min:1|max:7',
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
                    'visawi_s' => [
                        'simplicity' => $validated['simplicity'],
                        'diversity' => $validated['diversity'],
                        'colorfulness' => $validated['colorfulness'],
                        'craftsmanship' => $validated['craftsmanship'],
                    ]
                ]),
            ]);

            // Calculate final score (Mean of all dimensions)
            $finalScore = ($validated['simplicity'] + 
                          $validated['diversity'] + 
                          $validated['colorfulness'] + 
                          $validated['craftsmanship']) / 4;

            // Create VisAWI-S score
            $visawiSScore = VisawiSScore::create([
                'survey_id' => $validated['survey_id'],
                'user_id' => $validated['user_id'],
                'survey_response_id' => $surveyResponse->id,
                'simplicity' => $validated['simplicity'],
                'diversity' => $validated['diversity'],
                'colorfulness' => $validated['colorfulness'],
                'craftsmanship' => $validated['craftsmanship'],
                'final_score' => round($finalScore, 2),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'VisAWI-S response recorded successfully',
                'data' => $visawiSScore
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
            \Log::error('VisAWI-S API Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to save response: ' . $e->getMessage()
            ], 500);
        }
    }
}
