<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ParticipationProgrammePackage extends Model
{
    use HasFactory;

    protected $table = 'participation_programme_packages';

    protected $fillable = [
        'programme_id',
        'package_id',
        'price',
        'people_per_package',
        'description',     // ✅ Override field
        'is_locked',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_locked' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function programme()
    {
        return $this->belongsTo(ParticipationProgramme::class, 'programme_id');
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function submissions()
    {
        return $this->hasMany(ParticipationSubmission::class, 'participation_programme_package_id');
    }

    // ✅ Accessors with override logic
    public function getNameAttribute()
    {
        return $this->package->name ?? '-';
    }

    public function getPackageTypeAttribute()
    {
        return $this->package->package_type ?? null;
    }

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