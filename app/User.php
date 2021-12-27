<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'steamid', 'username', 'avatar', 'balance', 'is_admin', 'is_moderator', 'is_ban_chat',
        'trade_link', 'referral_code', 'referral_use', 'referral_sum',
        'api_token', 'is_fake', 'is_ban', 'is_ban_withdraw'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'api_token'
    ];
}
