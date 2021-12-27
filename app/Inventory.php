<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $fillable = [
        'user_id', 'item_id'
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
