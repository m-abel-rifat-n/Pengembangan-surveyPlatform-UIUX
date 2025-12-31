<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyHasCategories extends Model
{
    use HasFactory;

    protected $fillable = [
        'survey_id',
        'category_id',
    ];
}
