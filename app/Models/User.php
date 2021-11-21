<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany, HasOne};
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens,
        HasFactory,
        HasProfilePhoto,
        Notifiable,
        HasRoles,
        TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'gender',
        'firstname',
        'lastname',
        'address1',
        'zipcode',
        'city',
        'email',
        'birthday',
        'lesson_id',
        'phone',
        'pro',
        'password',
        'other_data',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'created_at' => 'datetime:d/m/Y',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'full_name',
        'role',
    ];

    public function yearDatas(): HasMany
    {
        return $this->hasMany(YearData::class);
    }

    public function currentYearData(): HasOne
    {
        return $this->hasOne(YearData::class)->latest();
    }

    public function subscription(): HasOne
    {
        return $this->hasOne(Subscription::class);
    }

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }

    public function files()
    {
        return $this->morphMany(Media::class, 'illustrable');
    }

    public function getFullNameAttribute(): string
    {
        return "$this->firstname $this->lastname";
    }

    public function getRoleAttribute()
    {
        return $this->getRoleNames()->first();
    }
}
