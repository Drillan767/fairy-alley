<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lesson extends Model
{
    use HasFactory;

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('title');
        });
    }

    protected $fillable = [
        'title',
        'description',
        'year',
        'ref',
        'gender',
        'schedule',
        'type',
    ];

    protected $casts = [
        'schedule' => 'array',
        'gender' => 'array',
    ];

    public function trackMovement(int $user_id, string $action): Collection
    {
        return $this
            ->movements()
            ->where([
                ['lesson_id', $this->id],
                ['user_id', $user_id],
                ['action', $action]
            ])
            ->get();
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function movements(): HasMany
    {
        return $this->hasMany(Movement::class);
    }
}
