<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhotoItem extends Model
{
    use HasFactory;
    
    protected $table = 'photo_items';
    
    protected $fillable = [
        'program_id',
        'title',
        'image',
        'order'
    ];
    
    protected $casts = [
        'image' => 'string'
    ];
    
    public function program()
    {
        return $this->belongsTo(Program::class);
    }
}