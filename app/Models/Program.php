<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ParticipationProgramme;

class Program extends Model
{
    use HasFactory;
    
    protected $table = 'programs';

    protected $fillable = [
        'title',
        'participation_programme_id',
        'event_date',
        'event_time',
        'location',
        'theme',
        'introduction',
        'background',
        'objectives',
        'schedules',
        'vip_list',
        'participation_description',
        'participation_prices',
        'participation_form',
        'participation_form_type',
        'participation_additional_files',
        'sponsorship_description',
        'sponsorship_packages',
        'sponsorship_additional_files',
        'sponsorship_form',
        'sponsorship_form_type',
        'programme_images',
        'programme_name',
        'programme_description',
        'is_visible',
        'visible_sections',
    ];
    
    // CRITICAL FIX: Remove automatic JSON casting to prevent conflicts
    // We handle JSON encoding/decoding manually in the controller and accessors
    protected $casts = [
        'is_visible' => 'boolean',
        'event_date' => 'date',
    ];

    // ========== RELATIONSHIPS ==========
    
    public function programmeItems()
    {
        return $this->hasMany(ProgrammeItem::class)->orderBy('order');
    }

    public function photoItems()
    {
        return $this->hasMany(PhotoItem::class, 'program_id', 'id');
    }
/**
 * Link to Participation Programme (for participant list)
 */
public function participationProgramme()
{
    return $this->belongsTo(
        ParticipationProgramme::class,
        'participation_programme_id', // Foreign key in programs table
        'id'                          // Primary key in participation_programmes table
    );
}

    public function getProgrammeItemsCountAttribute()
    {
        return $this->programmeItems()->count();
    }

    // ========== CUSTOM ACCESSORS (Manual JSON Decoding) ==========
    
    public function getIntroductionAttribute($value)
    {
        if (empty($value)) return [];
        if (is_array($value)) return $value;
        $decoded = json_decode($value, true);
        return is_array($decoded) ? $decoded : [];
    }

    public function getBackgroundAttribute($value)
    {
        if (empty($value)) return [];
        if (is_array($value)) return $value;
        $decoded = json_decode($value, true);
        return is_array($decoded) ? $decoded : [];
    }

    public function getObjectivesAttribute($value)
    {
        if (empty($value)) return [];
        if (is_array($value)) return $value;
        $decoded = json_decode($value, true);
        return is_array($decoded) ? $decoded : [];
    }

    public function getSchedulesAttribute($value)
    {
        if (empty($value)) return [];
        if (is_array($value)) return $value;
        $decoded = json_decode($value, true);
        return is_array($decoded) ? $decoded : [];
    }

    public function getVipListAttribute($value)
    {
        if (empty($value)) return [];
        if (is_array($value)) return $value;
        $decoded = json_decode($value, true);
        return is_array($decoded) ? $decoded : [];
    }

    public function getParticipationDescriptionAttribute($value)
    {
        if (empty($value)) return [];
        if (is_array($value)) return $value;
        $decoded = json_decode($value, true);
        return is_array($decoded) ? $decoded : [];
    }

    public function getParticipationPricesAttribute($value)
    {
        if (empty($value)) return [];
        if (is_array($value)) return $value;
        $decoded = json_decode($value, true);
        return is_array($decoded) ? $decoded : [];
    }

    public function getSponsorshipDescriptionAttribute($value)
    {
        if (empty($value)) return [];
        if (is_array($value)) return $value;
        $decoded = json_decode($value, true);
        return is_array($decoded) ? $decoded : [];
    }

    public function getSponsorshipPackagesAttribute($value)
    {
        if (empty($value)) return [];
        if (is_array($value)) return $value;
        $decoded = json_decode($value, true);
        return is_array($decoded) ? $decoded : [];
    }

    public function getProgrammeImagesAttribute($value)
    {
        if (empty($value)) return [];
        if (is_array($value)) return $value;
        $decoded = json_decode($value, true);
        return is_array($decoded) ? $decoded : [];
    }

    public function getProgrammeDescriptionAttribute($value)
    {
        if (empty($value)) return [];
        if (is_array($value)) return $value;
        $decoded = json_decode($value, true);
        return is_array($decoded) ? $decoded : [];
    }

    public function getVisibleSectionsAttribute($value)
    {
        if (empty($value)) {
            return [
                'overview' => true,
                'tentative' => true,
                'vip' => true,
                'participation' => true,
                'sponsorship' => true,
                'programme' => true,
                'photo' => true,
                'link-participation' => true,
                
            ];
        }
        if (is_array($value)) return $value;
        $decoded = json_decode($value, true);
        return is_array($decoded) ? $decoded : [];
    }

    // ========== OTHER ACCESSORS ==========

    public function getFormattedEventDateAttribute()
    {
        if (!$this->event_date) return null;
        try {
            return \Carbon\Carbon::parse($this->event_date)->format('d/m/Y');
        } catch (\Exception $e) {
            return $this->event_date;
        }
    }

    public function getFormattedEventTimeAttribute()
    {
        if (!$this->event_time) return null;
        try {
            return \Carbon\Carbon::parse($this->event_time)->format('h:i A');
        } catch (\Exception $e) {
            return $this->event_time;
        }
    }

    public function getParticipationFormUrlAttribute()
    {
        if (!$this->participation_form) return null;
        if ($this->participation_form_type === 'link') {
            return $this->participation_form;
        }
        return asset('storage/' . $this->participation_form);
    }

    public function getSponsorshipFormUrlAttribute()
    {
        if (!$this->sponsorship_form) return null;
        if ($this->sponsorship_form_type === 'link') {
            return $this->sponsorship_form;
        }
        return asset('storage/' . $this->sponsorship_form);
    }

    public function getSponsorshipAdditionalFilesUrlAttribute()
    {
        if (!$this->sponsorship_additional_files) return null;
        return asset('storage/' . $this->sponsorship_additional_files);
    }

    public function getProgrammeImagesUrlsAttribute()
    {
        if (!$this->programme_images) return [];
        return array_map(function($image) {
            return asset('storage/' . $image);
        }, $this->programme_images);
    }

    public function getVipListWithUrlsAttribute()
    {
        if (!$this->vip_list) return [];
        return array_map(function($vip) {
            if (isset($vip['image'])) {
                $vip['image_url'] = asset('storage/' . $vip['image']);
            }
            return $vip;
        }, $this->vip_list);
    }

    // ========== SCOPES ==========

    public function scopeVisible($query)
    {
        return $query->where('is_visible', true);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('event_date', '>=', now()->toDateString())
                    ->orderBy('event_date', 'asc');
    }

    public function scopePast($query)
    {
        return $query->where('event_date', '<', now()->toDateString())
                    ->orderBy('event_date', 'desc');
    }

    // ========== COMPUTED ATTRIBUTES ==========

    public function getIsUpcomingAttribute()
    {
        if (!$this->event_date) return false;
        return $this->event_date >= now()->toDateString();
    }

    public function getIsPastAttribute()
    {
        if (!$this->event_date) return false;
        return $this->event_date < now()->toDateString();
    }

    public function getVipCountAttribute()
    {
        return $this->vip_list ? count($this->vip_list) : 0;
    }

    public function getScheduleCountAttribute()
    {
        return $this->schedules ? count($this->schedules) : 0;
    }

    public function getProgrammeImageCountAttribute()
    {
        return $this->programme_images ? count($this->programme_images) : 0;
    }

    public function getHasParticipationFormAttribute()
    {
        return !empty($this->participation_form);
    }

    public function getHasSponsorshipFormAttribute()
    {
        return !empty($this->sponsorship_form);
    }

    public function getStatusTextAttribute()
    {
        if (!$this->event_date) return 'Not Scheduled';
        if ($this->is_upcoming) return 'Upcoming';
        return 'Past Event';
    }

    public function getStatusColorAttribute()
    {
        if (!$this->event_date) return 'secondary';
        if ($this->is_upcoming) return 'success';
        return 'warning';
    }
    
}