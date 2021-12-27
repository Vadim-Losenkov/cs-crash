@extends('admin/layout')

@section('content')
    <main class='main-content bgc-grey-100'>
        <div id='mainContent'>
            <div class="row gap-20 masonry pos-r">
                <div class="masonry-sizer col-md-6"></div>
                <div class="masonry-item col-md-6">
                    <div class="bgc-white p-20 bd">
                        <h6 class="c-grey-900">Редактировать промокод</h6>
                        <div class="mT-30">
                            <form action="/jhasdjashdas/items/edit/{{ $item->id }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Market name</label>
                                    <input type="text" name="market_hash_name" value="{{ $item->market_hash_name }}" required class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Image</label>
                                    <input type="text" name="image" value="{{ $item->image }}" required class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Exterior</label>
                                    <input type="text" name="exterior" value="{{ $item->exterior }}" required class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Rarity</label>
                                    <input type="text" name="rarity" value="{{ $item->rarity }}" required class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Color</label>
                                    <input type="text" name="color" value="{{ $item->color }}" required class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Is stattrak</label>
                                    <input type="text" name="is_stattrak" value="{{ $item->is_stattrak }}" required class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Price</label>
                                    <input type="text" name="price" value="{{ $item->price }}" required class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="">
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
