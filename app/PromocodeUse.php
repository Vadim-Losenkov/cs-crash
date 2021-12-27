<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PromocodeUse extends Model
{
    protected $fillable = [
        'user_id', 'promocode_id'
    ];

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function promocode()
    {
        return $this->belongsTo('App\Promocode', 'promocode_id', 'id');
    }
}
