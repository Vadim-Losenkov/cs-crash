<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Withdraw extends Model
{
    protected $fillable = [
        'user_id', 'item_id', 'market_id', 'custom_id', 'appId', 'status'
    ];

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function item()
    {
        return $this->belongsTo('App\AllItem', 'item_id', 'id');
    }
}
