<?php
namespace App\Models;

use Carbon\Carbon;

trait BaseModel
{

    public function getCreatedAtAttribute($value)
    {
        $date = Carbon::parse($value);
        return $date->format('Y-m-d H:i');
    }
    
    public function getUpdatedAtAttribute($value)
    {

        $date = Carbon::parse($value);
        return $date->format('Y-m-d H:i');
    }
}
