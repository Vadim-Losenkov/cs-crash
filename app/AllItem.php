<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AllItem extends Model
{
    protected $fillable = [
        'market_hash_name', 'image', 'exterior', 'rarity', 'color', 'is_stattrak', 'price', 'appId'
    ];
}
