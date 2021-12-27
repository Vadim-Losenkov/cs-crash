<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => '/auth', 'namespace' => 'Auth'], function () {
    Route::group(['prefix' => '/steam'], function () {
        Route::get('/', 'SteamController@redirectToSteam');
        Route::get('/handle', 'SteamController@handle');
    });
});

Route::group(['prefix' => '/user'], function () {
    Route::post('/get', 'UserController@getInfo')->middleware('auth:api');
    Route::post('/getInventory', 'UserController@getInventory')->middleware('auth:api');
    Route::post('/buy', 'UserController@buy')->middleware('auth:api');
    Route::post('/getProfile', 'UserController@getProfile')->middleware('auth:api');
    Route::post('/getUser', 'UserController@getUser');
    Route::post('/saveUrl', 'UserController@saveUrl')->middleware('auth:api');
    Route::post('/usePromo', 'UserController@usePromo')->middleware('auth:api');
});

Route::group(['prefix' => '/crash'], function () {
    Route::post('/myBet', 'CrashController@myBet')->middleware('auth:api');
    Route::post('/getHistory', 'CrashController@getGameHistory');
    Route::post('/getGame', 'CrashController@getGameBets');
});

Route::group(['prefix' => '/chat'], function () {
    Route::post('/sendMessage', 'ChatController@sendMessage')->middleware('auth:api');
    Route::post('/ban', 'ChatController@ban')->middleware('auth:api');
    Route::post('/unBan', 'ChatController@unBan')->middleware('auth:api');
    Route::post('/delete', 'ChatController@delete')->middleware('auth:api');
    Route::post('/getMessages', 'ChatController@getMessages');
});

Route::group(['prefix' => 'tickets', 'middleware' => 'auth:api'], function () {
    Route::post('/create', 'TicketsController@create');
    Route::post('/get', 'TicketsController@get');
    Route::post('/getById', 'TicketsController@getById');
    Route::post('/close', 'TicketsController@close');
    Route::post('/sendMessage', 'TicketsController@sendMessage');
});

Route::group(['prefix' => 'withdraws', 'middleware' => 'auth:api'], function () {
    Route::post('/send', 'WithdrawController@send');
});

Route::group(['prefix' => '/all-items', 'middleware' => 'auth:api'], function () {
    Route::post('/getList', 'AllItemsController@getList');
    Route::get('/fill', 'AllItemsController@fill');
});

Route::group(['prefix' => '/giveaways'], function () {
    Route::post('/getActive', 'GiveawayController@getActiveGiveaways');
    Route::post('/myActive', 'GiveawayController@getMyActiveGiveaway')->middleware('auth:api');
    Route::post('/setMyActive', 'GiveawayController@setMyActive')->middleware('auth:api');
});

Route::post('/getConfig', 'IndexController@getConfig');

Route::group(['prefix' => 'payment'], function () {
    Route::group(['prefix' => 'sum'], function () {
        Route::post('/create', 'PaymentController@sumCreate')->middleware('auth:api');
        Route::post('/handle', 'PaymentController@sumHandle');
    });
    Route::group(['prefix' => 'skins'], function () {
        Route::post('/create', 'PaymentController@skinsCreate')->middleware('auth:api');
    });
});

Route::group(['prefix' => '/bot', 'middleware' => 'bot'], function () {
    Route::post('/newBet', 'CrashController@newBet');
    Route::post('/getGame', 'CrashController@getGame');
    Route::post('/setStatus', 'CrashController@setStatus');
    Route::post('/crashBets', 'CrashController@crashBets');
    Route::post('/take', 'CrashController@take');
    Route::post('/autoTake', 'CrashController@autoTake');
    Route::post('/getWithdraws', 'WithdrawController@getWithdraws');
    Route::post('/fakeBets', 'BotsController@fakeBets');
    Route::post('/getLoadFakeMessages', 'BotsController@getLoadFakeMessages');
    Route::post('/sendFakeMessage', 'BotsController@sendFakeMessage');
    Route::post('/getSettings', 'WithdrawController@getSettings');
    Route::post('/setStatusBot', 'WithdrawController@setStatusBot');
    Route::post('/getOffer', 'WithdrawController@getOffer');
    Route::post('/deposit', 'PaymentController@depositSkins');
    Route::post('/getRaffle', 'GiveawayController@getRaffle');
});
