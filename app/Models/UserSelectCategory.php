<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSelectCategory extends Model
{
    use HasFactory;

    protected $table = 'user_select_category';

    protected $fillable = [
        'category_id',
        'user_id',
    ];
}
