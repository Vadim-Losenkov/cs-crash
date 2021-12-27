@extends('admin/layout')

@section('content')
    <link href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css" rel="stylesheet">
    <main class='main-content bgc-grey-100'>
        <div id='mainContent'>
            <div class="container-fluid">
                <div class="bgc-white p-20 bd"><h6 class="c-grey-900">Настройки</h6>
                    <div class="mT-30">
                        <form action="/jhasdjashdas/settings" method="POST">
                            @csrf
                            <div class="form-row">
                                <div class="form-group col-md-3">
                                    <label>Title</label>
                                    <input name="title" type="text" class="form-control" value="{{ $settings->title }}">
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Description</label>
                                    <input name="description" type="text" class="form-control" value="{{ $settings->description }}">
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Keywords</label>
                                    <input name="keywords" type="text" class="form-control" value="{{ $settings->keywords }}">
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Название сайта</label>
                                    <input name="site_name" type="text" class="form-control" value="{{ $settings->site_name }}">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-3">
                                    <label>FreeKassa ID</label>
                                    <input name="freekassa_id" type="text" class="form-control" value="{{ $settings->freekassa_id }}">
                                </div>
                                <div class="form-group col-md-3">
                                    <label>FreeKassa Secret 1</label>
                                    <input name="freekassa_secret_1" type="text" class="form-control" value="{{ $settings->freekassa_secret_1 }}">
                                </div>
                                <div class="form-group col-md-3">
                                    <label>FreeKassa Secret 2</label>
                                    <input name="freekassa_secret_2" type="text" class="form-control" value="{{ $settings->freekassa_secret_2 }}">
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Минимальная сумма пополнения FreeKassa</label>
                                    <input name="freekassa_sum" type="text" class="form-control" value="{{ $settings->freekassa_sum }}">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-3">
                                    <label>Курс доллара</label>
                                    <input name="dollar" type="text" class="form-control" value="{{ $settings->dollar }}">
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Макс кол-во предметов в ставке</label>
                                    <input name="max_items" type="text" class="form-control" value="{{ $settings->max_items }}">
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Профит, ниже которого сайт не может упасть</label>
                                    <input name="profit" type="text" class="form-control" value="{{ $settings->profit }}">
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Множитель вывода</label>
                                    <input name="min_withdraw" type="text" class="form-control" value="{{ $settings->min_withdraw }}">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-3">
                                    <label>Ключ от маркета</label>
                                    <input name="market_api_key" type="text" class="form-control" value="{{ $settings->market_api_key }}">
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Коэффициент покупки</label>
                                    <input name="coef_price" type="text" class="form-control" value="{{ $settings->coef_price }}">
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Ссылка на группу ВК</label>
                                    <input name="vk_group" type="text" class="form-control" value="{{ $settings->vk_group }}">
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Процент за пополнение реферала</label>
                                    <input name="percent_referral" type="text" class="form-control" value="{{ $settings->percent_referral }}">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-3">
                                    <label>Ставок от ботов в игре</label>
                                    <input name="bots_bets" type="text" class="form-control" value="{{ $settings->bots_bets }}">
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Минимальная ставка бота</label>
                                    <input name="bots_min" type="text" class="form-control" value="{{ $settings->bots_min }}">
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Максимальная ставка бота</label>
                                    <input name="bots_max" type="text" class="form-control" value="{{ $settings->bots_max }}">
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Раз в N минут писать в чат</label>
                                    <input name="bots_chat" type="text" class="form-control" value="{{ $settings->bots_chat }}">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-3">
                                    <label>Блокировка чата (0/1)</label>
                                    <input name="ban_all_chat" type="text" class="form-control" value="{{ $settings->ban_all_chat }}">
                                </div>
                                <div class="form-group col-md-3">
                                    <label>Спрятать розыгрыши (0/1)</label>
                                    <input name="hide_giveaway" type="text" class="form-control" value="{{ $settings->hide_giveaway }}">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Сохранить</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
