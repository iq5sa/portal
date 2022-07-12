<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>نظام أدارة شؤون الطلبة</title>

    <!-- Main CSS-->
    <link rel="stylesheet" type="text/css" href="{{asset('css/main.css')}}">
    <!-- Font-icon css-->
    <link rel="stylesheet" type="text/css"
          href="{{asset('css/font-awesome.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/font.css')}}">
    @yield('styles')
</head>
<body class="app sidebar-mini rtl">
<div id="adminApp">
    <!-- Navbar-->
@include('admin.layouts.header')
<!-- Sidebar menu-->
    @include('admin.layouts.sidebar')
    <main class="app-content">
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
        @yield('content')
    </main>
</div>

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
