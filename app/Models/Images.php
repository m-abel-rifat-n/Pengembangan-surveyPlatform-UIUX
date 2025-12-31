<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Images extends Model
{
    use HasFactory;

    protected $fillable = [
        'image',
        'link',
    ];

    public function image()
    {
        return Attribute::make(
            get: fn ($image) => asset('storage/image/' . $image),
        );
    }
}
