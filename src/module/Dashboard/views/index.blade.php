@extends($layout)

@section('title')
    Admin Dashboard
    @endsection

    @section('content')
            <!-- Page Heading -->
    <div class="row">
        <div class="col-lg-12">
            <div class="page-header">Dashboard&nbsp;
                <small>Statistic Overview</small>
            </div>
            <ol class="breadcrumb styled">
                <li class="home">
                    <a href="{!! route('dashboard.index') !!}"><i class="fa fa-home fa-lg fa-fw"></i></a>
                </li>
                <li class="active">Dashboard</li>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="alert alert-info alert-dismissable">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <i class="fa fa-info-circle"></i> <strong>Welcome to Admin Back Office.</strong>
            </div>
        </div>
    </div>
@endsection