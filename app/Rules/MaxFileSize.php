<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class MaxFileSize implements Rule
{
    protected $maxSizeMB;
    
    public function __construct($maxSizeMB = 15)
    {
        $this->maxSizeMB = $maxSizeMB;
    }
    
    public function passes($attribute, $value)
    {
        if (!$value) return true;
        
        $maxSizeBytes = $this->maxSizeMB * 1024 * 1024;
        return $value->getSize() <= $maxSizeBytes;
    }
    
    public function message()
    {
        return "The :attribute must not exceed {$this->maxSizeMB}MB.";
    }
}