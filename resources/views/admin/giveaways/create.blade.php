@extends('admin/layout')

@section('content')
    <main class='main-content bgc-grey-100'>
        <div id='mainContent'>
            <div class="row gap-20 masonry pos-r">
                <div class="masonry-sizer col-md-6"></div>
                <div class="masonry-item col-md-6">
                    <div class="bgc-white p-20 bd">
                        <h6 class="c-grey-900">Создать розыгрыш</h6>
                        <div class="mT-30">
                            <form action="/jhasdjashdas/giveaway/create" method="POST">
                                @csrf
                                <select name="item_id" class="form-control">
                                    <label for="exampleInputEmail1">Предмет</label>
                                    @foreach(\App\AllItem::query()->get() as $item)
                                        <option value="{{ $item->id }}">{{ $item->market_hash_name }}</option>
                                    @endforeach
                                </select>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Минимальная сумма пополнения</label>
                                    <input type="text" name="min_payment" required class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="0.00">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Дата окончания</label>
                                    <input type="text" name="end_time" required class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="{{ \Carbon\Carbon::now() }}">
                                </div>
                                <select name="winner_id" class="form-control">
                                    <label for="exampleInputEmail1">Дата окончания</label>
                                    <option selected value="NULL">Не определен</option>
                                    @foreach(\App\User::query()->get() as $user)
                                        <option value="{{ $user->id }}">{{ $user->username }}</option>
                                    @endforeach
                                </select>
                                <input type="text" name="status" value="0" hidden>

                                <button type="submit" class="btn btn-primary">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
