<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProgrammePaymentMethod extends Model
{
    use HasFactory;

    protected $table = 'programme_payment_methods';

    protected $fillable = [
        'programme_id',
        'payment_method_id',
        'account_name',    // ✅ Override field
        'account_number',  // ✅ Override field
        'is_active',       // ✅ Override field
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function programme()
    {
        return $this->belongsTo(ParticipationProgramme::class, 'programme_id');
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function submissions()
    {
        return $this->hasMany(ParticipationSubmission::class, 'programme_payment_method_id');
    }

    // ✅ Accessors with override logic
    public function getDisplayBankAttribute()
    {
        return $this->paymentMethod->bank ?? '-';
    }

    public function getDisplayAccountNameAttribute()
    {
        return $this->account_name ?? $this->paymentMethod->account_name ?? '-';
    }

    public function getDisplayAccountNumberAttribute()
    {
        return $this->account_number ?? $this->paymentMethod->account_number ?? '-';
    }

    public function getIsOverrideAttribute()
    {
        return !is_null($this->account_name) || !is_null($this->account_number);
    }
}