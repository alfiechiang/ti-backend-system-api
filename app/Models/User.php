<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'account',
        'password',
        'role_id',
        'supervisor',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];



    public  function getLevelAttribute()
    {

        $account = $this->account;
        $hier = Hierarchy::where("account", $account)->first();
        $level = $hier->level;
        return $level;
        # code...
    }


    public function online()
    {

        return $this->hasOne(OauthAccessToken::class, 'user_id', 'id')->select("name", "user_id");
    }
}
