<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Giveaway extends Model
{
    protected $fillable = [
        'item_id', 'min_payment', 'end_time', 'winner_id', 'status'
    ];

    public function item()
    {
        return $this->belongsTo('App\AllItem', 'item_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'winner_id', 'id');
    }
}
