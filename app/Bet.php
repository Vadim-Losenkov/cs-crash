<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bet extends Model
{
    protected $fillable = [
        'user_id', 'game_id', 'items', 'skins', 'bank', 'multiplier', 'auto_withdraw', 'win', 'win_item_id', 'is_win', 'is_fake'
    ];

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function winItem()
    {
        return $this->belongsTo('App\AllItem', 'win_item_id', 'id');
    }
}
