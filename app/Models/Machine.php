<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Redis;

class Machine extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'video_address',
        'link_address',
        'open',
        'machine_name',
        "machine_model",
        "image",
        "coin_name",
        "work"
    ];


    public  function getWorkAttribute()
    {

        $redis = Redis::connection('db1');
        $machine_model = $this->machine_model;
        $str =  $redis->get($machine_model);
        $work = true;
        if (is_null($str)) {
            $work = false;
        }
        # code...
        return $work;
    }
}
