<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ParticipationSubmission extends Model
{
    use HasFactory;

    protected $table = 'participation_submissions';

    protected $fillable = [
        'programme_id',
        'company_name',
        'officer_name',
        'phone_number',
        
        // ✅ CORRECTED: Links to participation_programme_packages (snapshot)
        'participation_programme_package_id',
        'quantity',
        'unit_price',
        'expected_participants',
        'total_price',
        
        // ✅ Links to programme_payment_methods
        'programme_payment_method_id',
        
        'receipt_path',
        'receipt_original_name',
        'receipt_size',
        'receipt_mime',
        'status',
        'admin_note',

        // ✅ ADD THESE
    'supporting_document_path',
    'supporting_document_original',
    'supporting_document_size',
    'supporting_document_mime',

    'status',
    ];

    protected $casts = [
        'unit_price'   => 'decimal:2',
        'total_price'  => 'decimal:2',
        'receipt_size' => 'integer',
    ];

    public function programme()
    {
        return $this->belongsTo(ParticipationProgramme::class);
    }

    // ✅ CORRECTED: Links to participation_programme_packages (snapshot layer)
    public function programmePackage()
    {
        return $this->belongsTo(ParticipationProgrammePackage::class, 'participation_programme_package_id');
    }

    // ✅ Links to programme_payment_methods
    public function programmePaymentMethod()
    {
        return $this->belongsTo(ProgrammePaymentMethod::class);
    }

    public function participants()
    {
        return $this->hasMany(ParticipationParticipant::class, 'submission_id')
            ->orderBy('sort_order');
    }

    // ✅ Helper: Get package name (through snapshot)
    public function getPackageNameAttribute()
    {
        return optional($this->programmePackage)->name ?? '-';
    }

    // ✅ Helper: Get payment method details
    public function getPaymentDetailsAttribute()
    {
        $method = optional($this->programmePaymentMethod->paymentMethod);
        return [
            'bank'           => $method->bank ?? '-',
            'account_name'   => $method->account_name ?? '-',
            'account_number' => $method->account_number ?? '-',
        ];
    }
}