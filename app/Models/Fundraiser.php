<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Fundraiser extends Model
{
    protected $table = 'fundraisers';

    protected $fillable = [
        'programme_name',
        'start_date',
        'end_date',
        'target_amount',
        'progress',
        'image_path',
        'status',
        'description',
        'form_path'
    ];

    protected $dates = ['start_date', 'end_date'];

    /**
     * Get all donations for this fundraiser
     */
    public function donations()
    {
        return $this->hasMany(Donation::class, 'fundraiser_id');
    }

    /**
     * Get all donors for this fundraiser (alias for donations)
     */
    public function donors()
    {
        return $this->hasMany(Donation::class, 'fundraiser_id');
    }

    /**
     * Get total raised amount from APPROVED donations only
     */
    public function getTotalRaisedAttribute()
    {
        return $this->donations()
            ->where('status', 'approved')
            ->sum('amount_pledge');
    }

    /**
     * Get remaining amount to reach target
     */
    public function getRemainingAmountAttribute()
    {
        return max(0, $this->target_amount - $this->total_raised);
    }

    // Accessor for full image URL
    public function getImageUrlAttribute()
    {
        if ($this->image_path) {
            // Check if it's already a full URL
            if (filter_var($this->image_path, FILTER_VALIDATE_URL)) {
                return $this->image_path;
            }
            return asset('storage/' . $this->image_path);
        }
        return asset('assets/images/default-fundraiser.jpg'); // Default image
    }

    // Accessors to format dates for display
    public function getStartDateDisplayAttribute()
    {
        return $this->start_date ? Carbon::parse($this->start_date)->format('d/m/Y') : null;
    }

    public function getEndDateDisplayAttribute()
    {
        return $this->end_date ? Carbon::parse($this->end_date)->format('d/m/Y') : null;
    }

    // Mutators to handle date input
    public function setStartDateAttribute($value)
    {
        // If value is in dd/mm/yyyy format, convert to Y-m-d
        if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $value)) {
            $this->attributes['start_date'] = Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d');
        } else {
            $this->attributes['start_date'] = $value;
        }
    }

    public function setEndDateAttribute($value)
    {
        // If value is in dd/mm/yyyy format, convert to Y-m-d
        if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $value)) {
            $this->attributes['end_date'] = Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d');
        } else {
            $this->attributes['end_date'] = $value;
        }
    }
}