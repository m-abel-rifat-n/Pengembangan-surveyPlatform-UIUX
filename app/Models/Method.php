<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Method extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function surveys()
    {
        return $this->belongsToMany(Survey::class, 'survey_has_methods', 'method_id', 'survey_id');
    }
}
