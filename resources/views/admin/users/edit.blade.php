@extends('admin/layout')

@section('content')
    <main class='main-content bgc-grey-100'>
        <div id='mainContent'>
            <div class="row gap-20 masonry pos-r">
                <div class="masonry-sizer col-md-6"></div>
                <div class="masonry-item col-md-6">
                    <div class="bgc-white p-20 bd">
                        <h6 class="c-grey-900">Редактировать пользователя</h6>
                        <div class="mT-30">
                            <form action="/jhasdjashdas/users/edit/{{ $user->id }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Никнейм</label>
                                    <input type="text" name="username" value="{{ $user->username }}" required
                                           class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp"
                                           placeholder="">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Баланс</label>
                                    <input type="text" name="balance" value="{{ $user->balance }}" required
                                           class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp"
                                           placeholder="">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Привилегии</label>
                                    <select name="type" class="form-control">
                                        <option @if(!$user->is_admin && $user->is_moderator) selected="selected" @endif value="user">Пользователь</option>
                                        <option @if($user->is_admin) selected="selected" @endif value="admin">Админ</option>
                                        <option @if($user->is_moderator) selected="selected" @endif value="moderator">Модератор</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Бан чата</label>
                                    <select name="is_ban_chat" class="form-control">
                                        <option @if(!$user->is_ban_chat) selected="selected" @endif value="0">Не заблокирован</option>
                                        <option @if($user->is_ban_chat) selected="selected" @endif value="1">Заблокирован</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Бан</label>
                                    <select name="is_ban" class="form-control">
                                        <option @if(!$user->is_ban) selected="selected" @endif value="0">Не заблокирован</option>
                                        <option @if($user->is_ban) selected="selected" @endif value="1">Заблокирован</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Бан вывода</label>
                                    <select name="is_ban_withdraw" class="form-control">
                                        <option @if(!$user->is_ban_withdraw) selected="selected" @endif value="0">Не заблокирован</option>
                                        <option @if($user->is_ban_withdraw) selected="selected" @endif value="1">Заблокирован</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Пополнено</label>
                                    <input type="text" disabled value="{{ App\Payment::query()->where([['user_id', $user->id], ['status', 1]])->sum('sum') }} $" required
                                           class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp"
                                           placeholder="">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Выведено</label>
                                    <input type="text" disabled value="{{ $withdraw }} $" required
                                           class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp"
                                           placeholder="">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Профит</label>
                                    <input type="text" disabled value="{{ $profit }} $" required
                                           class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp"
                                           placeholder="">
                                </div>

                                <button type="submit" class="btn btn-primary">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="bgc-white bd bdrs-3 p-20 mB-20">
                        <h4 class="c-grey-900 mB-20">Все ставки</h4>
                        <table id="dataTable" class="table table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th>Игра</th>
                                <th>Ставка</th>
                                <th>Выигрыш</th>
                                <th>Множитель</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th>Игра</th>
                                <th>Ставка</th>
                                <th>Выигрыш</th>
                                <th>Множитель</th>
                            </tr>
                            </tfoot>
                            <tbody>
                            @foreach($bets as $bet)
                                <tr>
                                    <td>#{{ $bet->id }}</td>
                                    <td>{{ round($bet->bank, 2) }} $</td>
                                    <td>{{ round($bet->win, 2) }} $</td>
                                    <td>{{ round($bet->multiplier, 2) }}x</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="bgc-white bd bdrs-3 p-20 mB-20">
                        <h4 class="c-grey-900 mB-20">Все выводы</h4>
                        <table id="dataTableWithdraw" class="table table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Название</th>
                                <th>Стоимость</th>
                                <th>Статус</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>Название</th>
                                <th>Стоимость</th>
                                <th>Статус</th>
                            </tr>
                            </tfoot>
                            <tbody>
                            @foreach($withdraws as $withdraw)
                                <tr>
                                    <td>{{ $withdraw->id }}</td>
                                    <td>{{ $withdraw->item->market_hash_name }}</td>
                                    <td>{{ round($withdraw->item->price, 2) }} $</td>
                                    @if($withdraw->status === 1)
                                        <td style="color: green">Выведен</td>
                                    @endif
                                    @if($withdraw->status === 2)
                                        <td style="color: red">Ошибка</td>
                                    @endif
                                    @if($withdraw->status === 0)
                                        <td>Ожидает</td>
                                    @endif
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
