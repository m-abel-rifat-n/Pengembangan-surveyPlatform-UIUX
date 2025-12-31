<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class SurveyResponses extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'surname',
        'email',
        'birth_date',
        'gender',
        'profession',
        'educational_background',
        'response_data',
        'survey_id',
        'user_id',
    ];

    protected $casts = [
        'responses_data' => 'json',
    ];

    public function survey()
    {
        return $this->belongsTo(Survey::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
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
