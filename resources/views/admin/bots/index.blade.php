@extends('admin/layout')

@section('content')
    <link href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css"  rel="stylesheet">
    <main class='main-content bgc-grey-100'>
        <div id='mainContent'>
            <div class="container-fluid">
                <h4 class="c-grey-900 mT-10 mB-30">Боты</h4>
                <div class="row">
                    <div class="col-md-12">
                        <div class="bgc-white bd bdrs-3 p-20 mB-20">
                            <h4 class="c-grey-900 mB-20">Все боты</h4>
                            <button type="button" class="btn cur-p btn-primary" onclick="window.location.href = '{{ route('admin.bots.create') }}'">Создать бота</button>
                            <table id="dataTable" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>Имя</th>
                                    <th>Steamid</th>
                                    <th>Профит</th>
                                    <th>Действие</th>
                                </tr>
                                </thead>
                                <tfoot>
                                <tr>
                                    <th>Имя</th>
                                    <th>Steamid</th>
                                    <th>Профит</th>
                                    <th>Действие</th>
                                </tr>
                                </tfoot>
                                <tbody>
                                @foreach(\App\User::query()->where('is_fake', 1)->get() as $user)
                                    <?php
                                        $profit = 0;

                                        foreach (App\Bet::query()->where('user_id', $user->id)->orderBy('id', 'desc')->get() as $bet) {
                                            $profit += round($bet->win - $bet->bank, 2);
                                        }
                                    ?>
                                    <tr>
                                        <td>{{ $user->username }}</td>
                                        <td><a href="https://steamcommunity.com/profiles/{{ $user->steamid }}">Профиль</a></td>
                                        <td>{{ $profit }} $</td>
                                        <td>
                                            <a href="{{ route('admin.bots.edit', ['id' => $user->id]) }}">Редактировать</a>
                                            /
                                            <a href="{{ route('admin.bots.user', ['id' => $user->id]) }}">Сделать пользователем</a>
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

                <div class="row">
                    <div class="col-md-12">
                        <div class="bgc-white bd bdrs-3 p-20 mB-20">
                            <h4 class="c-grey-900 mB-20">Сообщения в чат</h4>
                            <button type="button" class="btn cur-p btn-primary" onclick="window.location.href = '{{ route('admin.bots.create_message') }}'">Создать сообщение</button>
                            <table id="dataTable" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>Текст</th>
                                    <th>Действие</th>
                                </tr>
                                </thead>
                                <tfoot>
                                <tr>
                                    <th>Текст</th>
                                    <th>Действие</th>
                                </tr>
                                </tfoot>
                                <tbody>
                                @foreach(\App\ChatFake::query()->get() as $chat)
                                    <tr>
                                        <td>{{ $chat->text }}</td>
                                        <td>
                                            <a href="{{ route('admin.bots.edit_message', ['id' => $chat->id]) }}">Редактировать</a>
                                            /
                                            <a href="{{ route('admin.bots.delete_message', ['id' => $chat->id]) }}">Удалить</a>
                                        </td>
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
                            <h4 class="c-grey-900 mB-20">Отправить сообщение в чат</h4>
                            <form action="/jhasdjashdas/bots/message/send" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Бот</label>
                                    <select name="user_id" class="form-control">
                                        @foreach(\App\User::query()->where('is_fake', 1)->get() as $user)
                                            <option value="{{ $user->id }}">{{ $user->username }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Сообщение</label>
                                    <input type="text" name="message" required
                                           class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp"
                                           placeholder="">
                                </div>

                                <button type="submit" class="btn btn-primary">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
