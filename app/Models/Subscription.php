<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'lesson_id', 'possibility_1', 'possibility_2', 'invites'];

    protected $casts = [
        'invites' => 'array',
    ];
}
