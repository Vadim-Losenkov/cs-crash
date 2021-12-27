@extends('admin/layout')

@section('content')
    <main class="main-content bgc-grey-100">
        <div id="mainContent">
            <div class="row gap-20 masonry pos-r" style="position: relative; height: 2403px;">
                <div class="masonry-sizer col-md-6"></div>
                <div class="masonry-item w-100" style="position: absolute; left: 0%; top: 0px;">
                    <div class="row gap-20">
                        <div class="col-md-3">
                            <div class="layers bd bgc-white p-20">
                                <div class="layer w-100 mB-10"><h6 class="lh-1">Депозит сегодня</h6></div>
                                <div class="layer w-100">
                                    <div class="peers ai-sb fxw-nw">
                                        <div class="peer"><span
                                                class="d-ib lh-0 va-m fw-600 bdrs-10em pX-15 pY-15 bgc-green-50 c-green-500">
                                                {{ App\Payment::query()->where([['created_at', '>=', \Carbon\Carbon::today()], ['status', 1]])->sum('sum') }} $
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="layers bd bgc-white p-20">
                                <div class="layer w-100 mB-10"><h6 class="lh-1">Депозит за 7 дней</h6></div>
                                <div class="layer w-100">
                                    <div class="peers ai-sb fxw-nw">
                                        <div class="peer"><span
                                                class="d-ib lh-0 va-m fw-600 bdrs-10em pX-15 pY-15 bgc-green-50 c-green-500">
                                                {{ App\Payment::query()->where([['created_at', '>=', \Carbon\Carbon::today()->subDays(7)], ['status', 1]])->sum('sum') }} $
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="layers bd bgc-white p-20">
                                <div class="layer w-100 mB-10"><h6 class="lh-1">Депозит за месяц</h6></div>
                                <div class="layer w-100">
                                    <div class="peers ai-sb fxw-nw">
                                        <div class="peer"><span
                                                class="d-ib lh-0 va-m fw-600 bdrs-10em pX-15 pY-15 bgc-green-50 c-green-500">
                                                {{ App\Payment::query()->where([['created_at', '>=', \Carbon\Carbon::today()->subMonth()], ['status', 1]])->sum('sum') }} $
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="layers bd bgc-white p-20">
                                <div class="layer w-100 mB-10"><h6 class="lh-1">Депозит за все время</h6></div>
                                <div class="layer w-100">
                                    <div class="peers ai-sb fxw-nw">
                                        <div class="peer"><span
                                                class="d-ib lh-0 va-m fw-600 bdrs-10em pX-15 pY-15 bgc-green-50 c-green-500">
                                                {{ App\Payment::query()->where('status', 1)->sum('sum') }} $
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row gap-20">
                        <div class="col-md-3">
                            <div class="layers bd bgc-white p-20">
                                <div class="layer w-100 mB-10"><h6 class="lh-1">Выводов за сегодня</h6></div>
                                <div class="layer w-100">
                                    <div class="peers ai-sb fxw-nw">
                                        <div class="peer"><span
                                                class="d-ib lh-0 va-m fw-600 bdrs-10em pX-15 pY-15 bgc-green-50 c-green-500">
                                                {{ $withdrawToday }} $
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="layers bd bgc-white p-20">
                                <div class="layer w-100 mB-10"><h6 class="lh-1">Выводов за 7 дней</h6></div>
                                <div class="layer w-100">
                                    <div class="peers ai-sb fxw-nw">
                                        <div class="peer"><span
                                                class="d-ib lh-0 va-m fw-600 bdrs-10em pX-15 pY-15 bgc-green-50 c-green-500">
                                                {{ $withdrawWeek }} $
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="layers bd bgc-white p-20">
                                <div class="layer w-100 mB-10"><h6 class="lh-1">Выводов за месяц</h6></div>
                                <div class="layer w-100">
                                    <div class="peers ai-sb fxw-nw">
                                        <div class="peer"><span
                                                class="d-ib lh-0 va-m fw-600 bdrs-10em pX-15 pY-15 bgc-green-50 c-green-500">
                                                {{ $withdrawMonth }} $
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="layers bd bgc-white p-20">
                                <div class="layer w-100 mB-10"><h6 class="lh-1">Выводов за все время</h6></div>
                                <div class="layer w-100">
                                    <div class="peers ai-sb fxw-nw">
                                        <div class="peer"><span
                                                class="d-ib lh-0 va-m fw-600 bdrs-10em pX-15 pY-15 bgc-green-50 c-green-500">
                                                {{ $withdrawAll }} $
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row gap-20">
                        <div class="col-md-3">
                            <div class="layers bd bgc-white p-20">
                                <div class="layer w-100 mB-10"><h6 class="lh-1">Профит сайта</h6></div>
                                <div class="layer w-100">
                                    <div class="peers ai-sb fxw-nw">
                                        <div class="peer"><span
                                                class="d-ib lh-0 va-m fw-600 bdrs-10em pX-15 pY-15 bgc-green-50 c-green-500">
                                                {{ round(\App\Game::query()->where('status', 2)->sum('profit'), 2) }} $
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="layers bd bgc-white p-20">
                                <div class="layer w-100 mB-10"><h6 class="lh-1">Пользователей</h6></div>
                                <div class="layer w-100">
                                    <div class="peers ai-sb fxw-nw">
                                        <div class="peer"><span
                                                class="d-ib lh-0 va-m fw-600 bdrs-10em pX-15 pY-15 bgc-green-50 c-green-500">
                                                {{ \App\User::query()->where('is_fake', 0)->count('id') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
