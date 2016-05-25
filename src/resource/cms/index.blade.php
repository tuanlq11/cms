@extends($layout)

@section('page-header')
    @include('cms::partial.pageHeader')
@stop

@section('flash')
    @include("cms::partial.flash")
@stop

@section('content')
    @section('filter')
        @include("cms::partial.filter")
    @show
    @section('list')
        @include("cms::partial.list")
    @show
@stop



