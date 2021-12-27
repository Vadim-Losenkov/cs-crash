@extends('admin/layout')

@section('content')
<link href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css"  rel="stylesheet">
<main class='main-content bgc-grey-100'>
    <div id='mainContent'>
        <div class="container-fluid">
            <h4 class="c-grey-900 mT-10 mB-30">Розыгрыши</h4>
            <div class="row">
                <div class="col-md-12">
                    <div class="bgc-white bd bdrs-3 p-20 mB-20">
                        <h4 class="c-grey-900 mB-20">Все розыгрыши</h4>
                        <button type="button" class="btn cur-p btn-primary"
                                onclick="window.location.href = '{{ route('admin.giveaway.create') }}'">
                            Создать розыгрыш
                        </button>
                        <table id="dataTable" class="table table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Предмет</th>
                                <th>Мин. депозит</th>
                                <th>Конец</th>
                                <th>Победитель</th>
                                <th>Статус</th>
                                <th>Действие</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th>#</th>
                                <th>Предмет</th>
                                <th>Мин. депозит</th>
                                <th>Конец</th>
                                <th>Победитель</th>
                                <th>Статус</th>
                                <th>Действие</th>
                            </tr>
                            </tfoot>
                            <tbody>
                            @foreach(\App\Giveaway::query()->with(['user', 'item'])->get() as $giveaway)
                                <tr>
                                    <td>{{ $giveaway->id }}</td>
                                    <td>{{ $giveaway->item->market_hash_name }}</td>
                                    <td>{{ $giveaway->min_payment }}</td>
                                    <td>{{ $giveaway->end_time }}</td>
                                    @if($giveaway->winner_id)
                                        <td><a href="{{ route('admin.users.edit', ['id' => $giveaway->id]) }}">{{ $giveaway->user->username }}</a></td>
                                    @else
                                        <td>Не определен</td>
                                    @endif
                                    @if($giveaway->status === 0)
                                        <td style="color: green">Разыгрывается</td>
                                    @else
                                        <td style="color: red">Разыгран</td>
                                    @endif
                                    <td>
                                        <a href="{{ route('admin.giveaway.delete', ['id' => $giveaway->id]) }}">Удалить</a>
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
