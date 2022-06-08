<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceSubscription extends Model
{

    public function user(): belongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function service(): belongsTo
    {
        return $this->belongsTo(Service::class);
    }

    protected $casts = [
        'accepted_at' => 'date:d/m/Y',
        'created_at' => 'date:d/m/Y',
    ];
}
