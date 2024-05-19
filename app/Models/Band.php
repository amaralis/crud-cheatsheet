<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
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

    public function deleteRecursive(): bool
    {
        // Apagar recursivamente (evitando cascade, sobre a qual não se tem qualquer controlo)
        if ($this->cover_image !== 'default_band.jpg') {
            if (Storage::disk('images')->delete($this->cover_image)) {
                if ($this->albums()->exists()) {
                    $this->albums()->each(function ($album) {
                        if(!$album->deleteRecursive()){
                            return false;
                        }
                    });
                }
                return $this->delete();
            } else {
                return false; // Couldn't delete image
            }
        } else {
            if ($this->albums()->exists()) {
                $this->albums()->each(function ($album) {
                    if(!$album->deleteRecursive()){
                        return false;
                    }
                });
            }
            return $this->delete();
        }
    }
}
