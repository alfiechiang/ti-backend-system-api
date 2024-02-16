<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Redis;

class Member extends Model
{
    use HasFactory, BaseModel;

    protected $fillable = [
        'agent_id',
        'account',
        'phone',
        'name',
        'freeze_status',
        'has_test_account',
        'slot_open',
        'lottery_open',
        'balance',
        'balance_limit',
        'balance_desc',
        'desc',
    ];

    protected $casts = [
        'balance' => 'decimal:2'
    ];

    public function games()
    {
        return $this->hasMany(MemberGameStatus::class);
    }

    public function user()
    {

        return $this->hasOne(User::class, 'account', 'account')->select(['account', 'id']);;
    }

    public function upLevel()
    {
        return $this->hasOne(Hierarchy::class, 'id', 'agent_id');
    }

    public  function getOnlineAttribute()
    {


        $machines = Machine::all();

        $infos = [];
        $redis = Redis::connection('db1');
        $member_name = '';
        foreach ($machines as $machine) {
            $k = $machine->machine_model;
            $json_str = $redis->get($k);
            $json = json_decode($json_str);
            $str = "member:login";
            if (isset($json->$str)) {
                $member_name = $json->$str;
            }
        }

        if (!empty($member_name)) {
            $info = [];
            $info["name"] = "slot";

            $user = User::where("account", $member_name)->first();
            $info["user_id"] = $user->id;
            $infos[] = $info;
        }

        return $infos;
    }
}
