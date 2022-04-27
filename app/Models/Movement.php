<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Movement extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'lesson_id', 'action', 'lesson_time', 'by_admin',
    ];

    public function lesson(): HasOne
    {
        return $this->hasOne(Lesson::class, 'id', 'lesson_id');
    }

    public function user(): HasOne
    {
        return $this
            ->hasOne(User::class, 'id', 'user_id')
            ->select('id', 'firstname', 'lastname', 'email', 'phone', 'pro');
    }
}
