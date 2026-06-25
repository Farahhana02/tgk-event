<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ParticipationPackage extends Model
{
    use HasFactory;

    protected $table = 'participation_packages';

    protected $fillable = [
        'programme_id',
        'package_type',        // one_person | multi_person
        'name',
        'price',
        'people_per_package',  // only for multi_person
        'description',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function programme()
    {
        return $this->belongsTo(ParticipationProgramme::class, 'programme_id');
    }

    public function submissions()
    {
        return $this->hasMany(ParticipationSubmission::class, 'package_id');
    }
}
