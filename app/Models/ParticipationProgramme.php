<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ParticipationProgramme extends Model
{
    use HasFactory;

    protected $table = 'participation_programmes';

    protected $fillable = [
        'title',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'venue',
        'public_token',
        'qr_path',
        'receipt_max_mb',
        'is_active',
        'upload_form_path',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
        'is_active'  => 'boolean',
    ];

    // ✅ CORRECTED: Links to participation_programme_packages (snapshot layer)
    public function programmePackages()
    {
        return $this->hasMany(ParticipationProgrammePackage::class, 'programme_id')
            ->orderBy('sort_order');
    }

    // ✅ Links to programme_payment_methods
    public function programmePaymentMethods()
    {
        return $this->hasMany(ProgrammePaymentMethod::class, 'programme_id')
            ->where('is_active', true);
    }

    public function submissions()
    {
        return $this->hasMany(ParticipationSubmission::class, 'programme_id');
    }
}