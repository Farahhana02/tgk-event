<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    use HasFactory;

    protected $fillable = [
        'fundraiser_id',
        'donor_name',
        'email',
        'phone',
        'amount_pledge',
        'notes',
        'submitted_form_path',
        'receipt_file',
        'status',
        'donate_time',
    ];

    protected $casts = [
        'amount_pledge' => 'decimal:2',
        'donate_time' => 'datetime',
        'created_at' => 'datetime',
    ];

    /**
     * Accessor to map donor_name to name for blade compatibility
     */
    public function getNameAttribute()
    {
        return $this->donor_name;
    }

    /**
     * Accessor to map amount_pledge to amount for blade compatibility
     */
    public function getAmountAttribute()
    {
        return $this->amount_pledge;
    }

    /**
     * Accessor to map receipt_file to receipt_path for blade compatibility
     */
    public function getReceiptPathAttribute()
    {
        return $this->receipt_file;
    }

    /**
     * Get the fundraiser this donation belongs to
     */
    public function fundraiser()
    {
        return $this->belongsTo(Fundraiser::class);
    }
}