<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\SurveyResponses;
use App\Models\Survey;
use App\Models\User;

class AboutController extends Controller
{
    public function index()
    {
        // $usersCount = intval(ceil(User::count() / 10) * 10);
        $usersCount = User::count();
        $surveysCount = Survey:: count();
        $responsesCount = SurveyResponses::count();

        return Inertia('Web/About', [
            'auth' => auth()->user(),
            'usersCount' => $usersCount,
            'surveysCount' => $surveysCount,
            'responsesCount' => $responsesCount
        ]);
    }
}
