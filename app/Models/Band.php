<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Band extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'cover_image',
        'uuid'
    ];

    public function albums(): HasMany
    {
        return $this->hasMany(Album::class);
    }

    public function songs(): HasManyThrough
    {
        return $this->hasManyThrough(Song::class, Album::class);
    }
}
