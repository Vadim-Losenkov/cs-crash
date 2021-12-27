@extends('admin/layout')

@section('content')
    <main class='main-content bgc-grey-100'>
        <div id='mainContent'>
            <div class="container-fluid">
                <div class="layers">
                    <div class="layer w-100 p-20">
                        <h6 class="lh-1">Тикет "{{ $ticket->title }}"</h6>
                        @if($ticket->status === 0)<a href="/jhasdjashdas/support/closeTicket/{{ $ticket->id }}">Закрыть тикет</a>@endif
                    </div>
                    <div class="layer w-100">
                        <div id="scrollChat" class="bgc-grey-200 p-20" style="overflow-y: scroll;max-height: 700px;margin-top: -7.5px!important;margin-bottom: -7.5px!important;">
                            @foreach(json_decode($ticket->messages, true) as $msg)
                                @if ($msg['username'] !== 'Админ')
                                    <div class="peers fxw-nw">
                                        <div class="peer peer-greed">
                                            <div class="layers ai-fs gapY-5">
                                                <div class="layer">
                                                    <div class="peers fxw-nw ai-c pY-3 pX-10 bgc-white bdrs-2 lh-3/2">
                                                        <div class="peer mR-10"><small>{{ $msg['date'] }}</small></div>
                                                        <div class="peer-greed"><span>{{ $msg['message'] }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="peers fxw-nw ai-fe">
                                        <div class="peer peer-greed ord-0">
                                            <div class="layers ai-fe gapY-10">
                                                <div class="layer">
                                                    <div class="peers fxw-nw ai-c pY-3 pX-10 bgc-white bdrs-2 lh-3/2">
                                                        <div class="peer mL-10 ord-1"><small>{{ $msg['date'] }}</small></div>
                                                        <div class="peer-greed ord-0"><span>{{ $msg['message'] }}</span></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                        <div class="p-20 bdT bgc-white">
                            <form class="pos-r" action="/jhasdjashdas/support/sendMessage/{{ $ticket->id }}" method="POST">
                                @csrf
                                <input name="message" type="text" class="form-control bdrs-10em m-0" placeholder="Say something...">
                                <button type="submit" class="btn btn-primary bdrs-50p w-2r p-0 h-2r pos-a r-1 t-1"><i
                                        class="fa fa-paper-plane-o"></i></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.0.min.js"></script>
    <script>
        $('#scrollChat').scrollTop(9999999);
    </script>
@endsection

