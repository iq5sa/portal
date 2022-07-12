<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>نظام شؤون الطلبة</title>

    <!-- Main CSS-->
    <link rel="stylesheet" type="text/css" href="{{asset('css/main.css')}}">
    <!-- Font-icon css-->
    <link rel="stylesheet" type="text/css" href="{{asset('css/font-awesome.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/font.css')}}">
    @yield('styles')
</head>
<body style="overflow-x: hidden;">
<section class="material-half-bg">
    <div class="cover"></div>
    <div class="login-logo"></div>
</section>
<section class="login-content">
    <div class="logo text-center">
        <h2>مرحبا بك</h2>
        <h3>نظام ادارة شؤون الطلبة</h3>
    </div>
    <div class="login-box">
        @yield('content')
    </div>
</section>

<!-- Essential javascripts for application to work-->
<script src="{{asset('js/jquery-3.2.1.min.js')}}"></script>
<script src="{{asset('js/popper.min.js')}}"></script>
<script src="{{asset('js/bootstrap.min.js')}}"></script>
<!-- Scripts -->
<script src="{{ asset('js/main.js') }}" defer></script>

<!-- The javascript plugin to display page loading on top-->
<script data-pace-options='{ "ajax": true }' src="{{asset('js/plugins/pace.min.js')}}"></script>
@yield('scripts')
</body>
</html>
