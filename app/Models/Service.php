<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Service extends Model
{
    use HasFactory;

    public function banner(): MorphOne
    {
        return $this->morphOne(Media::class, 'illustrable');
    }

    public function thumbnail(): MorphOne
    {
        return $this->morphOne(Media::class, 'illustrable');
    }

    public function files(): MorphMany
    {
        return $this->morphMany(Media::class, 'illustrable');
    }

    public function page(): HasOne
    {
        return $this->hasOne(Page::class, 'id', 'page_id');
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(ServiceSubscription::class);
    }

    protected $fillable = [
        'title',
        'description',
        'page_id',
        'order',
    ];

    protected $casts = [
        'homepage' => 'boolean',
    ];
}
