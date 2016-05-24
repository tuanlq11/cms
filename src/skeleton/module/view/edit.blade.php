@extends($layout)

@section('page-header')
    @include('System::partial.pageHeader')
@stop

@section('flash')
    @include("System::partial.flash")
@stop

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">Edit {{ $controller->getModuleName() }}</div>
        <div class="panel-body">
            @section('form')
                @if(isset($form))
                    {!! form($form) !!}
                @endif
            @show
        </div>
    </div>

    <!-- TODO: Apply list of iframe -->
    @foreach($iframes as $iframe)
        <iframe src="{{$iframe}}" scrolling="no">

        </iframe>
    @endforeach
@stop
