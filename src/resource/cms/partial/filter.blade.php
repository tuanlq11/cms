@if(!empty($filter))
	<!-- Page Filter -->
    <div class="row">
		<div class="col-lg-12">
			<div class="panel filter-form panel-default">
				<div class="panel-heading" data-toggle="collapse" href="#filterCollapsed" aria-expanded="true" aria-controls="collapseExample">
					{{ trans('core.label.filter') }}
				</div>
				<div id="filterCollapsed" class="panel-body collapse in">
                	{!! form($filter) !!}
            	</div>
        	</div>
    	</div>
    </div>
    <!-- End Page Filter -->
@endif