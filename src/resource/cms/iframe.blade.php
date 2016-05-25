<!DOCTYPE html>
<html>
<head>
    <title>@yield('title', 'Iframe')</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
</head>
<body style="padding-top: 0">
<div id="wrapper" class="scrollbar-macosx">
    @if(!isset($is_iframe) || !$is_iframe)
            <!-- Site Header -->
    @include('cms::partial.header')
            <!-- Site Navigation -->
    @include('cms::partial.navigation')
    @endif

            <!-- Site Content -->
    <div id="page-wrapper" class="page-wrapper-scroll" style="background-color: #fff;">
        <div>
            <!-- Page Header -->
            <div class="page-header" style="border-bottom: none;">
                {{ array_get($controller->getConfig("{$action}"), "label", $action) }}
                        <!-- Right action menu block -->
                @if($action=='listInGroup')
                    <div class="pull-right page-buttons">
                        <a class="btn btn-primary popup" data-target-panel=".add-user-panel" href="{{ $controller->getGeneratedUrl('addUserToGroup', ['is_iframe' => true,'group_id' => $group_id]) }}"><i class="fa fa-plus-circle fa-fw"></i> Add New {{ $controller->getModuleName() }} To Group</a>
                        <script type="text/javascript">
                            $(document).ready(function() {
                                $('.popup').click(function(event) {
                                    event.preventDefault();
                                    var height = 600;
                                    var width = 800;
                                    var left = (screen.width - width)/2;
                                    var top = (screen.height - height)/2;
                                    var popup = window.open($(this).attr("href"), "Add User To Group", "width="+width+",height="+height+",top="+top+",left="+left+",scrollbars=yes,");
                                    var timer = setInterval(function() {
                                        if(popup.closed) {
                                            clearInterval(timer);
                                            window.location.reload(true);
                                        }
                                    }, 1000);
                                });
                            });
                        </script>
                    </div>
                @endif
            </div>

            <!-- Flash Messages Block -->
            @include("cms::partial.flash")

            <!-- Page Content -->
            @section('filter')
                @include("cms::partial.filter")
            @show
            @section('list')
                @include("cms::partial.list")
            @show
        </div>
    </div>
</div>
@if(!isset($is_iframe) || !$is_iframe)
        <!-- Site Footer -->
@include('cms::partial.footer')
@endif
</body>
</html>

