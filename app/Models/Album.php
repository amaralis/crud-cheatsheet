<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Album extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'band_id',
        'launch_date',
        'uuid',
        'cover_image'
    ];

    public function songs(): HasMany
    {
        return $this->hasMany(Song::class);
    }

    public function band(): BelongsTo
    {
        return $this->belongsTo(Band::class);
    }
}
