@extends('admin/layout')

@section('content')
    <link href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css"  rel="stylesheet">
    <main class='main-content bgc-grey-100'>
        <div id='mainContent'>
            <div class="container-fluid">
                <h4 class="c-grey-900 mT-10 mB-30">Пользователи</h4>
                <div class="row">
                    <div class="col-md-12">
                        <div class="bgc-white bd bdrs-3 p-20 mB-20">
                            <h4 class="c-grey-900 mB-20">Все пользователи</h4>
                            <table id="dataTable" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>Имя</th>
                                    <th>Steamid</th>
                                    <th>Баланс</th>
                                    <th>Пополнил</th>
                                    <th>Вывел</th>
                                    <th>Профит</th>
                                    <th>Действие</th>
                                </tr>
                                </thead>
                                <tfoot>
                                <tr>
                                    <th>Имя</th>
                                    <th>Steamid</th>
                                    <th>Баланс</th>
                                    <th>Пополнил</th>
                                    <th>Вывел</th>
                                    <th>Профит</th>
                                    <th>Действие</th>
                                </tr>
                                </tfoot>
                                <tbody>
                                @foreach(\App\User::query()->where('is_fake', 0)->get() as $user)
                                    <?php
                                        $withdraw = 0;
                                        $profit = 0;

                                        foreach (App\Withdraw::query()->with(['item'])->where([['user_id', $user->id], ['status', 1]])->get() as $win) {
                                            $withdraw += $win->item->price;
                                        }

                                        foreach (App\Bet::query()->where('user_id', $user->id)->orderBy('id', 'desc')->get() as $bet) {
                                            $profit += round($bet->win - $bet->bank, 2);
                                        }
                                    ?>
                                    <tr>
                                        <td>{{ $user->username }}</td>
                                        <td><a href="https://steamcommunity.com/profiles/{{ $user->steamid }}">Профиль</a></td>
                                        <td>{{ $user->balance }} $</td>
                                        <td>{{ App\Payment::query()->where([['user_id', $user->id], ['status', 1]])->sum('sum') }} $</td>
                                        <td>{{ round($withdraw, 2) }} $</td>
                                        <td>{{ $profit }} $</td>
                                        <td>
                                            <a href="{{ route('admin.users.edit', ['id' => $user->id]) }}">Редактировать</a>
                                            /
                                            <a href="{{ route('admin.users.delete', ['id' => $user->id]) }}">Удалить</a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
