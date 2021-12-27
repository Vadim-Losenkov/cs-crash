@extends('admin/layout')

@section('content')
    <main class='main-content bgc-grey-100'>
        <div id='mainContent'>
            <div class="row gap-20 masonry pos-r">
                <div class="masonry-sizer col-md-6"></div>
                <div class="masonry-item col-md-6">
                    <div class="bgc-white p-20 bd">
                        <h6 class="c-grey-900">Редактировать бота</h6>
                        <div class="mT-30">
                            <form action="/jhasdjashdas/bots/edit/{{ $user->id }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Никнейм</label>
                                    <input type="text" name="username" value="{{ $user->username }}" required
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
