<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" href="/favicon.ico">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $settings->title }}</title>
    <meta name="description" content="{{ $settings->description }}">
    <meta name="keywords" content="{{ $settings->keywords }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}?v={{time()}}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}?v={{time()}}">
</head>
<body class="chat-active">
<div id="app" style="width: 100%;height: 100%;">
    <layout></layout>
</div>
</body>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="{{ asset('js/noty.min.js') }}?v={{time()}}"></script>
<script src="{{ mix('js/app.js') }}?v={{time()}}"></script>
@php
    if(isset($_GET['ref'])) {
        session_start();
        $_SESSION['ref'] = $_GET['ref'];
    }
@endphp
</html>
