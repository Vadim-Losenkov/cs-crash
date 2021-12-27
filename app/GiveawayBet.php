<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GiveawayBet extends Model
{
    protected $fillable = [
        'giveaway_id', 'user_id'
    ];
}
