<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    protected $fillable = [
        'title', 'description', 'keywords', 'dollar', 'max_items', 'profit', 'payment_to_withdraw', 'market_api_key', 'coef_price',
        'site_name', 'vk_group', 'freekassa_id', 'freekassa_secret_1', 'freekassa_secret_2', 'freekassa_sum', 'skinpay_sum', 'percent_referral',
        'bots_bets', 'bots_min', 'bots_max', 'bots_chat', 'min_withdraw',
        'site_name', 'vk_group', 'freekassa_id', 'freekassa_secret_1', 'freekassa_secret_2', 'freekassa_sum', 'skinpay_sum', 'percent_referral',
        'bot_steamid', 'bot_username', 'bot_password', 'bot_shared_secret', 'bot_identity_secret', 'bot_trade_url', 'ban_all_chat',
        'hide_giveaway'
    ];
}
