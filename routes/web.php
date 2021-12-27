<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['prefix' => 'jhasdjashdas', 'namespace' => 'Admin'], function () {
    Route::get('/login', 'LoginController@index');
    Route::get('/logout', 'LoginController@logout');
    Route::post('/login', 'LoginController@login');

    Route::group(['middleware' => 'admin'], function () {
        Route::get('/', 'IndexController@index')->name('admin.index');
        Route::get('/users', 'UsersController@index')->name('admin.users');
        Route::get('/admins', 'AdminController@index')->name('admin.admins');
        Route::get('/withdraws', 'WithdrawController@index')->name('admin.withdraws');
        Route::get('/support', 'SupportController@index')->name('admin.support');
        Route::get('/promocodes', 'PromocodeController@index')->name('admin.promocodes');
        Route::get('/items', 'ItemsController@index')->name('admin.items');
        Route::get('/bots', 'BotsController@index')->name('admin.bots');
        Route::get('/giveaways', 'GiveawayController@index')->name('admin.giveaways');

        Route::group(['prefix' => 'users'], function () {
            Route::get('/edit/{id}', 'UsersController@edit')->name('admin.users.edit');
            Route::post('/edit/{id}', 'UsersController@editPost');
            Route::get('/delete/{id}', 'UsersController@delete')->name('admin.users.delete');
        });

        Route::group(['prefix' => 'giveaway'], function () {
            Route::get('/delete/{id}', 'GiveawayController@delete')->name('admin.giveaway.delete');
            Route::get('/create', 'GiveawayController@create')->name('admin.giveaway.create');
            Route::post('/create', 'GiveawayController@createPost');
        });

        Route::group(['prefix' => 'bots'], function () {
            Route::get('/create', 'BotsController@create')->name('admin.bots.create');
            Route::post('/create', 'BotsController@createPost');
            Route::get('/edit/{id}', 'BotsController@edit')->name('admin.bots.edit');
            Route::post('/edit/{id}', 'BotsController@editPost');
            Route::get('/user/{id}', 'BotsController@user')->name('admin.bots.user');
            Route::get('/message/create', 'BotsController@messageCreate')->name('admin.bots.create_message');
            Route::post('/message/create', 'BotsController@messageCreatePost');
            Route::get('/message/edit/{id}', 'BotsController@messageEdit')->name('admin.bots.edit_message');
            Route::post('/message/edit/{id}', 'BotsController@messageEditPost');
            Route::get('/message/delete/{id}', 'BotsController@messageDelete')->name('admin.bots.delete_message');
            Route::post('/message/send', 'BotsController@sendMessage');
        });

        Route::group(['prefix' => 'settings'], function () {
            Route::get('/', 'SettingsController@index')->name('admin.settings');
            Route::post('/', 'SettingsController@save');
        });

        Route::group(['prefix' => 'support'], function () {
            Route::get('/chat/{id}', 'SupportController@chat')->name('admin.support.chat');
            Route::post('/sendMessage/{id}', 'SupportController@sendMessage');
            Route::get('/closeTicket/{id}', 'SupportController@closeTicket');
        });

        Route::group(['prefix' => 'admins'], function () {
            Route::get('/create', 'AdminController@create')->name('admin.admins.create');
            Route::post('/create', 'AdminController@createPost');
            Route::get('/edit/{id}', 'AdminController@edit')->name('admin.admins.edit');
            Route::post('/edit/{id}', 'AdminController@editPost');
            Route::get('/delete/{id}', 'AdminController@delete')->name('admin.admins.delete');
        });

        Route::group(['prefix' => 'promocodes'], function () {
            Route::get('/create', 'PromocodeController@create')->name('admin.promocodes.create');
            Route::post('/create', 'PromocodeController@createPost');
            Route::get('/edit/{id}', 'PromocodeController@edit')->name('admin.promocodes.edit');
            Route::post('/edit/{id}', 'PromocodeController@editPost');
            Route::get('/delete/{id}', 'PromocodeController@delete')->name('admin.promocodes.delete');
        });

        Route::group(['prefix' => 'items'], function () {
            Route::get('/create', 'ItemsController@create')->name('admin.items.create');
            Route::post('/create', 'ItemsController@createPost');
            Route::get('/edit/{id}', 'ItemsController@edit')->name('admin.items.edit');
            Route::post('/edit/{id}', 'ItemsController@editPost');
            Route::get('/delete/{id}', 'ItemsController@delete')->name('admin.items.delete');
            Route::get('/prices/{appId}', 'ItemsController@prices')->name('admin.items.prices');
        });
    });
});

Route::get('/{any}', 'IndexController@index')->where('any', '.*');
