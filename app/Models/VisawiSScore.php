<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class VisawiSScore extends Model
{
    use HasFactory;

    protected $table = 'visawi_s_scores';

    protected $fillable = [
        'survey_id',
        'user_id',
        'survey_response_id',
        'simplicity',
        'diversity',
        'colorfulness',
        'craftsmanship',
        'final_score',
    ];

    protected $casts = [
        'final_score' => 'decimal:2',
    ];

    public function survey()
    {
        return $this->belongsTo(Survey::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function surveyResponse()
    {
        return $this->belongsTo(SurveyResponses::class, 'survey_response_id');
    }

    protected function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => \Carbon\Carbon::parse($value)->timezone('Asia/Jakarta')->translatedFormat('H:i \W\I\B d/m/Y'),
        );
    }

    protected function updatedAt(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => \Carbon\Carbon::parse($value)->timezone('Asia/Jakarta')->translatedFormat('H:i \W\I\B d/m/Y'),
        );
    }
}
