<?php

namespace App\Http\Controllers\Web;
use App\Models\Category;
use App\Models\SurveyHasCategories;
use App\Models\Survey;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::latest()->where('status', 'Public')->paginate(18);   

        return inertia('Web/Categories/Index', [
            'categories' => $categories,
            'auth' => auth()->user(),
        ]);
    }

    public function show($slug)
    {
        $user = auth()->user();
        $category = Category::where('slug', $slug)->firstOrFail();
        if ($category->status == 'Private' && !$user->hasPermissionTo('categories.index.full')) {
            abort(403, 'This category is not available.');
        }

        $categoryId = $category->id;
        $surveyIds = SurveyHasCategories::where('category_id', $categoryId)->pluck('survey_id');
        $surveys = Survey::whereIn('id', $surveyIds)->where('status', 'Public')->latest()->paginate(16);

        return inertia('Web/Categories/Show', [
            'category' => $category,
            'surveys' => $surveys,
            'auth' => auth()->user(),
        ]);
    }
}
