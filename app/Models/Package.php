<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Package extends Model
{
    use HasFactory;

    protected $table = 'packages';

    protected $fillable = [
        'name',
        'package_type',
        'default_price',
        'people_per_package',
        'description',
        'is_active',
    ];

    protected $casts = [
        'default_price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    // ✅ Relationship with old system (programme_packages)
    public function oldProgrammePackages()
    {
        return $this->hasMany(ProgrammePackage::class);
    }

    // ✅ Relationship with new system (participation_programme_packages)
    public function participationProgrammePackages()
    {
        return $this->hasMany(ParticipationProgrammePackage::class);
    }

    // ✅ Get all programme usages (both old and new)
    public function allProgrammeUsages()
    {
        $old = $this->oldProgrammePackages()->with('programme')->get();
        $new = $this->participationProgrammePackages()->with('programme')->get();
        
        return $old->merge($new);
    }

    // ✅ Check if package is used in any programme
    public function getIsUsedAttribute()
    {
        return $this->oldProgrammePackages()->exists() || 
               $this->participationProgrammePackages()->exists();
    }
}