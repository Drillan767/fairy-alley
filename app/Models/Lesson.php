<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'year',
        'ref',
        'gender',
        'schedule',
    ];

    protected $casts = [
        'schedule' => 'array',
    ];

    public function trackMovement(int $user_id, string $action)
    {
//        dd($this->movements);
        return $this
            ->movements()
            ->where([
                ['lesson_id', $this->id],
                ['user_id', $user_id],
                ['action', $action]
            ])
            ->get()
            ;
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function queues(): HasMany
    {
        return $this->hasMany(Queue::class);
    }

    public function movements(): HasMany
    {
        return $this->hasMany(Movement::class);
    }
}
