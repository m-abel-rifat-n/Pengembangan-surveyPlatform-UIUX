<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'original_certificate',
        'certificate',
        'status',
        'description',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function categories()
    {
        return $this->belongsToMany(CertificateHasCategorys::class, 'certificate_has_category');
    }

    protected function certificate(): Attribute
    {
        return Attribute::make(
            get: fn ($certificate) => asset('storage/file/certificates/' . $certificate),
        );
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
