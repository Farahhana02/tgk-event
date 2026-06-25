<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProgrammePackage extends Model
{
    use HasFactory;

    protected $table = 'programme_packages';

    protected $fillable = [
        'programme_id',
        'package_id',
        'price',
        'people_per_package',
        'description',     // ✅ Override field
        'is_locked',
        'sort_order',
        'is_active',       // ✅ Override field
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_locked' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function programme()
    {
        return $this->belongsTo(Program::class, 'programme_id');
    }

    // ✅ Accessors for override logic
    public function getDisplayPriceAttribute()
    {
        return $this->price ?? $this->package->default_price ?? 0;
    }

    public function getDisplayDescriptionAttribute()
    {
        return $this->description ?? $this->package->description ?? null;
    }

    public function getDisplayPeoplePerPackageAttribute()
    {
        return $this->people_per_package ?? $this->package->people_per_package ?? null;
    }

    public function getIsOverrideAttribute()
    {
        return !is_null($this->price) || 
               !is_null($this->description) || 
               !is_null($this->people_per_package);
    }
}