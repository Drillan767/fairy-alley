<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class YearData extends Model
{
    use HasFactory;

    protected $casts = [
        'pre_registration_signature' => 'boolean',
        'deposit_paid' => 'boolean',
    ];

    public function file()
    {
        return $this->morphOne(Media::class, 'illustrable');
    }
}
