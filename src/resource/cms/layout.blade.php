<!DOCTYPE html>
<html>
<head>
    <title>@yield('title', 'Core')</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- CSS Scripts -->
    <link rel="stylesheet" href="{{asset('/build/library/font-awesome/css/font-awesome.min.css')}}">
    <link rel="stylesheet" href="{{asset('/build/library/jquery-scrollbar/jquery.scrollbar.css')}}">
    <link rel="stylesheet" href="{{asset('/build/app/css/app.css')}}">
    <!-- Javascript -->
    <script type="text/javascript" src="{{asset('/build/library/jquery/jquery.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('/build/library/bootstrap/js/bootstrap.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('/build/library/jquery-scrollbar/jquery.scrollbar.min.js')}}"></script>
    {!! $stylesheets or '' !!}
    {!! $javascripts or '' !!}
    {!! $metas or '' !!}
</head>
<body>
<div id="wrapper" class="scrollbar-macosx">
    @if(!isset($is_iframe) || !$is_iframe)
            <!-- Site Header -->
    @include('System::partial.header')
            <!-- Site Navigation -->
    @include('System::partial.navigation')
    @endif

            <!-- Site Content -->
    <div id="page-wrapper" class="page-wrapper-scroll">
        <div class="container-fluid">
            <!-- Page Header -->
            @yield('page-header')

                    <!-- Flash Messages Block -->
            @yield('flash')

                    <!-- Page Content -->
            @yield('content')
        </div>
    </div>
</div>
@if(!isset($is_iframe) || !$is_iframe)
        <!-- Site Footer -->
@include('System::partial.footer')
@endif
</body>
</html>
