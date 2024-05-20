<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    public function deleteRecursive(): bool
    {
        // Apagar recursivamente (evitando cascade, sobre a qual não se tem qualquer controlo)
        // Tem aqui algum controlo, mas precisa de mais verificação sobre as eliminações
        if ($this->cover_image !== 'default_album.jpg') {
            if (Storage::disk('images')->delete($this->cover_image)) {
                if ($this->songs()->exists()) {
                    $this->songs()->each(function ($song) {
                        if(!$song->delete()){ // Retornar falso se uma canção não puder ser eliminada
                            return false;
                        }
                    });
                }
                return $this->delete();
            } else {
                return false; // Couldn't delete image
            }
        } else {
            if ($this->songs()->exists()) {
                $this->songs()->each(function ($song) {
                    if(!$song->delete()) { // Retornar falso se uma canção não puder ser eliminada
                        return false;
                    }
                });
                return $this->delete();
            }
            return $this->delete();
        }
    }
}
