@extends($layout)

@section('page-header')
    @include('cms::partial.pageHeader')
@stop

@section('flash')
    @include("cms::partial.flash")
@stop

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">View {{ $controller->getModuleName() }}</div>
        <div class="panel-body">
            @section('form')
                @if(isset($form))
                    {!! form($form) !!}
                @endif
            @show
        </div>
    </div>
@stop
