<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class SurveyQuestions extends Model
{
    use HasFactory;

    protected $fillable = [
        'survey_id',
        'questions_data',
    ];
}
