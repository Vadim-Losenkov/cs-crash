@extends('admin/layout')

@section('content')
    <link href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css" rel="stylesheet">
    <main class='main-content bgc-grey-100'>
        <div id='mainContent'>
            <div class="container-fluid">
                <h4 class="c-grey-900 mT-10 mB-30">Поддержка</h4>
                <div class="row">
                    <div class="col-md-12">
                        <div class="bgc-white bd bdrs-3 p-20 mB-20">
                            <h4 class="c-grey-900 mB-20">Все запросы</h4>
                            <table id="dataTable" class="table table-striped table-bordered" cellspacing="0"
                                   width="100%">
                                <thead>
                                <tr>
                                    <th>Пользователь</th>
                                    <th>Название тикета</th>
                                    <th>Последнее сообщение от админа (да/нет)</th>
                                    <th>Статус</th>
                                    <th>Действие</th>
                                </tr>
                                </thead>
                                <tfoot>
                                <tr>
                                    <th>Пользователь</th>
                                    <th>Название тикета</th>
                                    <th>Последнее сообщение от админа (да/нет)</th>
                                    <th>Статус</th>
                                    <th>Действие</th>
                                </tr>
                                </tfoot>
                                <tbody>
                                @foreach($supports as $support)
                                    <tr>
                                        <td><a href="https://steamcommunity.com/profiles/{{ $support->user->steamid }}">{{ $support->user->username }}</a></td>
                                        <td>{{ $support->title }}</td>
                                        @if ($support->last_admin)
                                            <td style="color: #90EE90">Да</td>
                                        @else
                                            <td style="color: #FF0000">Нет</td>
                                        @endif

                                        @if($support->status)
                                            <td style="color: red">
                                                Закрыт
                                            </td>
                                        @else
                                            <td>
                                                Открыт
                                            </td>
                                        @endif
                                        <td>
                                            <a href="{{ route('admin.support.chat', ['id' => $support->id]) }}">Открыть
                                                чат</a>
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
