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
        'schedule',
    ];

    protected $casts = [
        'schedule' => 'array',
    ];

    public function getScheduleAttribute($value)
    {
        return json_decode(json_decode($value));
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
