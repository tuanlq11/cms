<!-- Page Header -->
<div class="row">
	<div class="col-lg-12">
		<!-- TODO Update on next version, get action name from controller through getActionName function -->
		<div class="page-header">
			{{ array_get($controller->getConfig("{$action}"), "label", $action) }}
			<!-- Right action menu block -->
			@if($action=='index')
			<div class="pull-right page-buttons">
				<a class="btn btn-primary" data-target-panel=".add-user-panel" href="{{ $controller->getGeneratedUrl('create') }}"><i class="fa fa-plus-circle fa-fw"></i> Add New {{ $controller->getModuleName() }}</a>
			</div>
			@endif
		</div>
		<ol class="breadcrumb styled">
			<li class="home">
				<a href="{!! route('dashboard.index') !!}"><i class="fa fa-home fa-lg fa-fw"></i></a>
			</li>
			@if($action=='index')
				<li class="active">{{ $controller->getModuleName() }}</li>
			@else
				<li>
					<a href="{!! route(strtolower($controller->getModuleName()).'.index') !!}">{{ $controller->getModuleName() }}</a>
				</li>
				<li class="active">
					{{ array_get($controller->getConfig("{$action}"), "label", $action) }}
				</li>
			@endif
		</ol>
	</div>
</div>
<!-- End Page Header -->