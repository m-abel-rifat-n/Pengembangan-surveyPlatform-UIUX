<?php

namespace App\Http\Controllers\Account;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Survey;
use App\Models\SurveyHasMethods;
use App\Models\SurveyResponses;


class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $app_url = config('app.url');

        if ($user->hasPermissionTo('dashboard.index.full')) {
            $surveys = Survey::latest()->paginate(10);
        } else {
            $surveys = Survey::where('user_id', $user->id)->latest()->paginate(10);
        }

        $surveyData = [];

        foreach ($surveys as $survey) {
            $surveyMethods = SurveyHasMethods::where('survey_id', $survey->id)->get();
            $methodIds = $surveyMethods->pluck('method_id')->toArray();
            sort($methodIds);

            $totalResponses = SurveyResponses::where('survey_id', $survey->id)->get()->count();

            $surveyData[] = [
                'survey_id' => $survey->id,
                'title' => $survey->title,
                'status' => $survey->status,
                'response_count' => $totalResponses,
                'method_ids' => $methodIds,
            ];
        }

        return inertia('Account/Dashboard', [
            'app_url' => $app_url,
            'surveys' => $surveys,
            'surveyData' => $surveyData,
        ])->with('currentSurveyTitle');
    }
}
