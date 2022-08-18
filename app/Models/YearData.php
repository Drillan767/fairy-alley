<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class YearData extends Model
{
    use HasFactory;

    protected $casts = [
        'pre_registration_signature' => 'boolean',
        'payments' => 'array',
        'deposit_paid' => 'boolean',
    ];

    protected $fillable = ['total', 'payments'];

    public function file(): MorphOne
    {
        return $this->morphOne(Media::class, 'illustrable');
    }
}
