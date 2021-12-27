<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    const STATUS_TIMER = 0;
    const STATUS_START = 1;
    const STATUS_END = 2;

    protected $fillable = [
        'multiplier', 'bank', 'members', 'skins', 'profit', 'status'
    ];

    public function users()
    {
        return \DB::table('games')
            ->join('bets', 'games.id', '=', 'bets.game_id')
            ->join('users', 'bets.user_id', '=', 'users.id')
            ->where('games.id', $this->id)
            ->groupBy('users.username')
            ->select('users.*')
            ->get();
    }
}
