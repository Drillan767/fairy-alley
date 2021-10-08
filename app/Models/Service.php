<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Service extends Model
{
    use HasFactory;

    public function file(): MorphOne
    {
        return $this->morphOne(Media::class, 'illustrable');
    }

    protected $fillable =  [
        'title',
        'description',
        'page_id',
    ];
}
