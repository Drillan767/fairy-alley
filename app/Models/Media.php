<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Media extends Model
{
    use HasFactory;

    protected $table = 'medias';
    protected $fillable = ['url', 'title', 'illustrable_id', 'illustrable_type'];

    public function illustrable(): MorphTo
    {
        return $this->morphTo();
    }
}
