<?php

namespace App\Models;

use App\Casts\Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends Model
{
    use HasFactory;

    public const PENDING = 1;
    public const VALIDATED = 2;
    public const NEEDS_INFOS = 3;
    public const AWAITING_PAYMENT = 4;
    public const SUBSCRIPTION_OVER = 5;

    protected $fillable = ['user_id', 'lesson_id', 'possibility_1', 'possibility_2', 'invites', 'status'];

    protected $casts = [
        'invites' => 'array',
        'status' => Status::class,
        'created_at' => 'datetime:d/m/Y',
        'updated_at' => 'datetime:d/m/Y',
    ];

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }
}
