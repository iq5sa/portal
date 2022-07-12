

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">

<head>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>الرئيسية</title>

    <link rel="stylesheet" type="text/css" href="{{asset('css/main.css')}}">
    <!-- Font-icon css-->
    <link rel="stylesheet" type="text/css" href="{{asset('css/font-awesome.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('css/font.css')}}">

    <!-- Custom styles for this template -->
    <link href="{{asset('css/coming-soon.min.css')}}" rel="stylesheet">

</head>

<body>
{{--<img src="{{asset('images/office-desk_23-2147929786.jpg')}}">--}}

<img src="{{asset('images/438938-PEUBFW-33.svg')}}">

<div class="masthead">
    <div class="masthead-bg"></div>
    <div class="container h-100">
        <div class="row h-100">
            <div class="col-12 my-auto">
                <div class="masthead-content text-white py-5 py-md-0">
                    <h1 class="mb-3">مرحبا بك</h1>
                    <p class="mb-5">نظام ادارة الجامعات والكليات العراقية</p>
                    <div class="newsletter">
                        <a href="/login" class="btn btn-secondary btn-lg">أبدء</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<!-- Bootstrap core JavaScript -->
<!-- Essential javascripts for application to work-->
<script src="{{asset('js/jquery-3.2.1.min.js')}}"></script>
<script src="{{asset('js/popper.min.js')}}"></script>
<script src="{{asset('js/bootstrap.min.js')}}"></script>
<!-- Scripts -->
<script src="{{ asset('js/main.js') }}" defer></script>

<!-- The javascript plugin to display page loading on top-->
<script data-pace-options='{ "ajax": true }' src="{{asset('js/plugins/pace.min.js')}}"></script>

</body>

</html>
