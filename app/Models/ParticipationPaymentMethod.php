<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ParticipationPaymentMethod extends Model
{
    use HasFactory;

    protected $table = 'participation_payment_methods';

    protected $fillable = [
        'programme_id',
        'bank',
        'account_number',
        'account_name',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function programme()
    {
        return $this->belongsTo(ParticipationProgramme::class, 'programme_id');
    }

    public function submissions()
    {
        return $this->hasMany(ParticipationSubmission::class, 'payment_method_id');
    }
}
