<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'settings';

    protected $fillable = [
        'kia_enabled',
    ];

    protected $casts = [
        'kia_enabled' => 'boolean',
    ];

    /**
     * Get the singleton settings instance
     */
    public static function get()
    {
        return self::first() ?? self::create(['kia_enabled' => false]);
    }

    /**
     * Check if KIA (Kedah Innovation Award) is enabled
     */
    public static function isKiaEnabled()
    {
        return self::get()->kia_enabled;
    }

    /**
     * Toggle KIA enabled status
     */
    public static function toggleKia()
    {
        $settings = self::get();
        $settings->update(['kia_enabled' => !$settings->kia_enabled]);
        return $settings->kia_enabled;
    }
}
