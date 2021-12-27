<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,shrink-to-fit=no">
    <title>Sign In</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
    <style>#loader {
            transition: all .3s ease-in-out;
            opacity: 1;
            visibility: visible;
            position: fixed;
            height: 100vh;
            width: 100%;
            background: #fff;
            z-index: 90000
        }

        #loader.fadeOut {
            opacity: 0;
            visibility: hidden
        }

        .spinner {
            width: 40px;
            height: 40px;
            position: absolute;
            top: calc(50% - 20px);
            left: calc(50% - 20px);
            background-color: #333;
            border-radius: 100%;
            -webkit-animation: sk-scaleout 1s infinite ease-in-out;
            animation: sk-scaleout 1s infinite ease-in-out
        }

        @-webkit-keyframes sk-scaleout {
            0% {
                -webkit-transform: scale(0)
            }
            100% {
                -webkit-transform: scale(1);
                opacity: 0
            }
        }

        @keyframes sk-scaleout {
            0% {
                -webkit-transform: scale(0);
                transform: scale(0)
            }
            100% {
                -webkit-transform: scale(1);
                transform: scale(1);
                opacity: 0
            }
        }</style>
    <link href="/assets/css/style.css" rel="stylesheet">
</head>
<body class="app">
<div class="peers ai-s fxw-nw h-100vh">
    <div class="d-n@sm- peer peer-greed h-100 pos-r bgr-n bgpX-c bgpY-c bgsz-cv"
         style="background-image:url(/assets/static/images/bg.jpg)">
        <div class="pos-a centerXY">
            <div class="bgc-white bdrs-50p pos-r" style="width:120px;height:120px"><img class="pos-a centerXY"
                                                                                        src="/assets/static/images/logo.png"
                                                                                        alt=""></div>
        </div>
    </div>
    <div class="col-12 col-md-4 peer pX-40 pY-80 h-100 bgc-white scrollable pos-r" style="min-width:320px"><h4
            class="fw-300 c-grey-900 mB-40">Login</h4>
        <form action="/jhasdjashdas/login" method="POST">
            @csrf
            <div class="form-group"><label class="text-normal text-dark">Username</label> <input type="text"
                                                                                                 name="username"
                                                                                                 class="form-control"
                                                                                                 placeholder="John Doe"></div>
            <div class="form-group"><label class="text-normal text-dark">Password</label> <input type="password"
                                                                                                 name="password"
                                                                                                 class="form-control"
                                                                                                 placeholder="Password"></div>
            <div class="form-group">
                <div class="peers ai-c jc-sb fxw-nw">
                    <div class="peer">
                        <button class="btn btn-primary">Login</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript" src="/assets/js/vendor.js"></script>
<script type="text/javascript" src="/assets/js/bundle.js"></script>
</body>
</html>