<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $table = 'payment_methods';

    protected $fillable = [
        'bank',
        'account_number',
        'account_name',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function programmePaymentMethods()
    {
        return $this->hasMany(ProgrammePaymentMethod::class);
    }

    // ✅ Check if used in any programme
    public function getIsUsedAttribute()
    {
        return $this->programmePaymentMethods()->exists();
    }
}