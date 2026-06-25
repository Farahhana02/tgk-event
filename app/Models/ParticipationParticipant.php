<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\ParticipationSubmission; // 👈 WAJIB

class ParticipationParticipant extends Model
{
    use HasFactory;

    protected $table = 'participation_participants';

    protected $fillable = [
        'submission_id',
        'name',
        'position',
        'table_number',
        'sort_order', 
    ];

    public function submission()
    {
        return $this->belongsTo(
            ParticipationSubmission::class,
            'submission_id'
        );
    }
}
