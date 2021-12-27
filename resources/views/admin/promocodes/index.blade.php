@extends('admin/layout')

@section('content')
    <link href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css"  rel="stylesheet">
    <main class='main-content bgc-grey-100'>
        <div id='mainContent'>
            <div class="container-fluid">
                <h4 class="c-grey-900 mT-10 mB-30">Промокоды</h4>
                <div class="row">
                    <div class="col-md-12">
                        <div class="bgc-white bd bdrs-3 p-20 mB-20">
                            <h4 class="c-grey-900 mB-20">Все промокоды</h4>
                            <button type="button" class="btn cur-p btn-primary" onclick="window.location.href = '{{ route('admin.promocodes.create') }}'">Создать промокод</button>
                            <table id="dataTable" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>Название</th>
                                    <th>Сумма</th>
                                    <th>Активаций всего</th>
                                    <th>Активаций осталось</th>
                                    <th>Действие</th>
                                </tr>
                                </thead>
                                <tfoot>
                                <tr>
                                    <th>Название</th>
                                    <th>Сумма</th>
                                    <th>Активаций всего</th>
                                    <th>Активаций осталось</th>
                                    <th>Действие</th>
                                </tr>
                                </tfoot>
                                <tbody>
                                @foreach(\App\Promocode::query()->get() as $promocode)
                                    <tr>
                                        <td>{{ $promocode->name }}</td>
                                        <td>{{ $promocode->sum }}</td>
                                        <td>{{ $promocode->activates }}</td>
                                        <td>{{ $promocode->activates - \App\PromocodeUse::query()->where('promocode_id', $promocode->id)->count('id') }}</td>
                                        <td>
                                            <a href="{{ route('admin.promocodes.edit', ['id' => $promocode->id]) }}">Редактировать</a>
                                            /
                                            <a href="{{ route('admin.promocodes.delete', ['id' => $promocode->id]) }}">Удалить</a>
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
