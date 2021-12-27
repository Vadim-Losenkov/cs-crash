@extends('admin/layout')

@section('content')
    <link href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css"  rel="stylesheet">
    <main class='main-content bgc-grey-100'>
        <div id='mainContent'>
            <div class="container-fluid">
                <h4 class="c-grey-900 mT-10 mB-30">Выводы</h4>
                <div class="row">
                    <div class="col-md-12">
                        <div class="bgc-white bd bdrs-3 p-20 mB-20">
                            <h4 class="c-grey-900 mB-20">Все выводы</h4>
                            <table id="dataTable" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Пользователь</th>
                                    <th>Название</th>
                                    <th>Стоимость</th>
                                    <th>Статус</th>
                                </tr>
                                </thead>
                                <tfoot>
                                <tr>
                                    <th>ID</th>
                                    <th>Пользователь</th>
                                    <th>Название</th>
                                    <th>Стоимость</th>
                                    <th>Статус</th>
                                </tr>
                                </tfoot>
                                <tbody>
                                @foreach(\App\Withdraw::query()->with(['user', 'item'])->get() as $withdraw)
                                    <tr>
                                        <td>{{ $withdraw->id }}</td>
                                        <td><a href="https://steamcommunity.com/profiles/{{ $withdraw->user->steamid }}">{{ $withdraw->user->username }}</a></td>
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
        </div>
    </main>
@endsection
