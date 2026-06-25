<?php
// app/Models/ProgrammeItem.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgrammeItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'program_id',
        'title',
        'description',
        'images',
        'order'
    ];

    protected $casts = [
        'images' => 'array'
    ];

    /**
     * Get the program that owns the programme item
     */
    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    /**
     * Get full URLs for all images
     */
    public function getImageUrlsAttribute()
    {
        if (!$this->images) {
            return [];
        }

        return array_map(function($image) {
            return asset('storage/' . $image);
        }, $this->images);
    }

    /**
     * Get description as array
     */
    public function getDescriptionArrayAttribute()
    {
        if (is_string($this->description)) {
            $decoded = json_decode($this->description, true);
            return is_array($decoded) ? $decoded : [];
        }
        
        return is_array($this->description) ? $this->description : [];
    }
}