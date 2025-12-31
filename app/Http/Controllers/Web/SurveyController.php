<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Survey;

class SurveyController extends Controller
{
    public function index()
    {
        $surveys = Survey::when(request()->q, function ($surveys) {
            $surveys = $surveys->where('title', 'like', '%' . request()->q . '%');
        })->latest()->where('status', 'Public')->paginate(20);
        
        return inertia('Web/Surveys', [
            'auth' => auth()->user(),
            'surveys' => $surveys
        ]);
    }
}
