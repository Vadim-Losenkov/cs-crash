@extends('admin/layout')

@section('content')
    <link href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css"  rel="stylesheet">
    <main class='main-content bgc-grey-100'>
        <div id='mainContent'>
            <div class="container-fluid">
                <h4 class="c-grey-900 mT-10 mB-30">Предметы</h4>
                <div class="row">
                    <div class="col-md-12">
                        <div class="bgc-white bd bdrs-3 p-20 mB-20">
                            <h4 class="c-grey-900 mB-20">Все предметы</h4>
                            <button type="button" class="btn cur-p btn-primary"
                                    onclick="window.location.href = '{{ route('admin.items.create') }}'">
                                Создать предмет
                            </button>
                            <button type="button" class="btn cur-p btn-primary"
                                    onclick="window.location.href = '{{ route('admin.items.prices', ['appId' => 730]) }}'">
                                Обновить цены CS:GO
                            </button>
                            <button type="button" class="btn cur-p btn-primary"
                                    onclick="window.location.href = '{{ route('admin.items.prices', ['appId' => 570]) }}'">
                                Обновить цены Dota 2
                            </button>
                            <table id="dataTable" class="table table-striped table-bordered" cellspacing="0" width="100%">
                                <thead>
                                <tr>
                                    <th>Market name</th>
                                    <th>Exterior</th>
                                    <th>Rarity</th>
                                    <th>Color</th>
                                    <th>Is stattrak</th>
                                    <th>Price</th>
                                    <th>Действие</th>
                                </tr>
                                </thead>
                                <tfoot>
                                <tr>
                                    <th>Market name</th>
                                    <th>Exterior</th>
                                    <th>Rarity</th>
                                    <th>Color</th>
                                    <th>Is stattrak</th>
                                    <th>Price</th>
                                    <th>Действие</th>
                                </tr>
                                </tfoot>
                                <tbody>
                                @foreach(\App\AllItem::query()->get() as $item)
                                    <tr>
                                        <td>{{ $item->market_hash_name }}</td>
                                        <td>{{ $item->exterior }}</td>
                                        <td>{{ $item->rarity }}</td>
                                        <td>{{ $item->color }}</td>
                                        <td>{{ $item->is_stattrak }}</td>
                                        <td>{{ $item->price }}</td>
                                        <td>
                                            <a href="{{ route('admin.items.edit', ['id' => $item->id]) }}">Редактировать</a>
                                            /
                                            <a href="{{ route('admin.items.delete', ['id' => $item->id]) }}">Удалить</a>
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
