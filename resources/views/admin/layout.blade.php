<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,shrink-to-fit=no">
    <title>Admin panel</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
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
<div>
    <div class="sidebar">
        <div class="sidebar-inner">
            <!-- ### $Sidebar Header ### -->
            <div class="sidebar-logo">
                <div class="peers ai-c fxw-nw">
                    <div class="peer peer-greed">
                        <a class="sidebar-link td-n" href="/jhasdjashdas">
                            <div class="peers ai-c fxw-nw">
                                <div class="peer">
                                    <div class="logo">
                                        <img src="/assets/static/images/logo.png" alt="">
                                    </div>
                                </div>
                                <div class="peer peer-greed">
                                    <h5 class="lh-1 mB-0 logo-text">Adminator</h5>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="peer">
                        <div class="mobile-toggle sidebar-toggle">
                            <a href="" class="td-n">
                                <i class="ti-arrow-circle-left"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <ul class="sidebar-menu scrollable pos-r">
                <li class="nav-item mT-30 actived">
                    <a class="sidebar-link" href="{{ route('admin.index') }}">
                <span class="icon-holder">
                  <i class="c-blue-500 ti-home"></i>
                </span>
                        <span class="title">Статистика</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="sidebar-link" href="{{ route('admin.users') }}">
                        <span class="icon-holder">
                            <i class="c-light-blue-500 ti-user"></i>
                        </span>
                        <span class="title">Пользователи</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="sidebar-link" href="{{ route('admin.bots') }}">
                        <span class="icon-holder">
                            <i class="c-light-blue-500 ti-user"></i>
                        </span>
                        <span class="title">Боты</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="sidebar-link" href="{{ route('admin.withdraws') }}">
                        <span class="icon-holder">
                            <i class="c-light-blue-500 ti-briefcase"></i>
                        </span>
                        <span class="title">Выводы</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="sidebar-link" href="{{ route('admin.promocodes') }}">
                        <span class="icon-holder">
                            <i class="c-light-blue-500 ti-gift"></i>
                        </span>
                        <span class="title">Промокоды</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="sidebar-link" href="{{ route('admin.settings') }}">
                        <span class="icon-holder">
                            <i class="c-light-blue-500 ti-settings"></i>
                        </span>
                        <span class="title">Настройки сайта</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="sidebar-link" href="{{ route('admin.support') }}">
                        <span class="icon-holder">
                            <i class="c-light-blue-500 ti-headphone-alt"></i>
                        </span>
                        <span class="title">Поддержка</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="sidebar-link" href="{{ route('admin.items') }}">
                        <span class="icon-holder">
                            <i class="c-light-blue-500 ti-pie-chart"></i>
                        </span>
                        <span class="title">Предметы</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="sidebar-link" href="{{ route('admin.giveaways') }}">
                        <span class="icon-holder">
                            <i class="c-light-blue-500 ti-pie-chart"></i>
                        </span>
                        <span class="title">Розыгрыши</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="sidebar-link" href="{{ route('admin.admins') }}">
                        <span class="icon-holder">
                            <i class="c-light-blue-500 ti-face-smile"></i>
                        </span>
                        <span class="title">Админ. пользователи</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <div class="page-container">
        <div class="header navbar">
            <div class="header-container">
                <ul class="nav-left">
                    <li>
                        <a id='sidebar-toggle' class="sidebar-toggle" href="javascript:void(0);">
                            <i class="ti-menu"></i>
                        </a>
                    </li>
                </ul>
                <ul class="nav-right">
                    <li class="dropdown">
                        <a href="" class="dropdown-toggle no-after peers fxw-nw ai-c lh-1" data-toggle="dropdown">
                            <div class="peer mR-10">
                                <img class="w-2r bdrs-50p" src="https://randomuser.me/api/portraits/men/10.jpg" alt="">
                            </div>
                            <div class="peer">
                                <span class="fsz-sm c-grey-900">Admin</span>
                            </div>
                        </a>
                        <ul class="dropdown-menu fsz-sm">
                            <li>
                                <a href="/jhasdjashdas/logout" class="d-b td-n pY-5 bgcH-grey-100 c-grey-700">
                                    <i class="ti-power-off mR-10"></i>
                                    <span>Выйти</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
        @yield('content')
        <footer class="bdT ta-c p-30 lh-0 fsz-sm c-grey-600">
            <span>Copyright © 2019 Designed by <a href="https://colorlib.com" target='_blank'
                                                  title="Colorlib">Colorlib</a>. All rights reserved.</span>
        </footer>
    </div>
</div>
</div>
<script type="text/javascript" src="/assets/js/vendor.js"></script>
<script type="text/javascript" src="/assets/js/bundle.js?v=4"></script>
<script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script type="text/javascript" src="//cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready( function () {
        $('#dataTableWithdraw').DataTable();
    });
</script>
</body>
</html>
