@extends($layout)

@section('page-header')
    @include('System::partial.pageHeader')
@stop

@section('flash')
    @include("System::partial.flash")
@stop

@section('content')
    @section('filter')
        @include("System::partial.filter")
    @show
    @section('list')
        @include("System::partial.list")
    @show
@stop



