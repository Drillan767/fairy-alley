<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FirstContact extends Model
{
    use HasFactory;

    protected $casts = [
        'birthday' => 'datetime:d/m/Y',
        'created_at' => 'datetime:d/m/Y',
    ];

    protected $appends = [
        'full_name',
    ];

    public function getFullNameAttribute()
    {
        return "$this->firstname $this->lastname";
    }
}
