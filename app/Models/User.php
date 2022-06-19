<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
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

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($user) {
            $user->yearDatas()->delete();
            $user->files()->delete();
            $user->subscription()->delete();
            $user->roles()->detach();
            $user->movements()->delete();
            $user->firstContactData()->delete();
        });
    }

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
        'lesson_title',
        'available_replacements',
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
        return $this->hasOne(Subscription::class)->whereNot('status', Subscription::SUBSCRIPTION_OVER);
    }

    public function firstContactData(): HasOne
    {
        return $this->hasOne(FirstContact::class);
    }

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }

    public function files()
    {
        return $this->morphMany(Media::class, 'illustrable');
    }

    public function movements()
    {
        return $this->hasMany(Movement::class);
    }

    public function getFutureLessons()
    {
        return $this
            ->movements()
            ->with('lesson:id,title,description')
            ->where('lesson_time', '>', now())
            ->orderBy('lesson_time')
            ->get();
    }

    /* ATTRIBUTES */

    public function getFullNameAttribute(): string
    {
        return "$this->firstname $this->lastname";
    }

    public function getRoleAttribute()
    {
        return $this->getRoleNames()->first();
    }

    public function getLessonTitleAttribute(): string
    {
        return $this->lesson()->count() ? $this->lesson->title : 'Aucun';
    }

    public function getAvailableReplacementsAttribute(): int
    {
        return $this
                ->movements()
                ->where('action', 'leave')
                ->whereRelation('lesson', 'type', '=', 'lesson')
                ->count()
            -
            $this
                ->movements()
                ->where('action', 'join')
                ->whereRelation('lesson', 'type', '=', 'lesson')
                ->count();
    }
}
