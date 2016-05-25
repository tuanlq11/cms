@if(count($items) > 0)
        <!-- Listing Table -->
<div class="col-sm-3 text-right">
    <div class="batch-action" id="dataTables-example_info" role="status" aria-live="polite">
        @section('batchAction')
            @include("cms::partial.batchAction")
        @show
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-body panel-body-table">
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th class="text-center" style="width: 1px">
                        <input id="select-all-box" type="checkbox"/>
                        <script type="text/javascript">
                            $('#select-all-box').on('change', function(){
                                var status = $('#select-all-box').is(':checked');
                                $('input[name="objSelected"]').each(function(){
                                    $(this).prop("checked", status);
                                });
                            });
                        </script>
                    </th>
                    @foreach($controller->getListFieldsConfig() as $key => $config)
                        <th id="{{$key}}" class="text-center">{{ trans($config['label']) }}</th>
                    @endforeach
                    <th class="text-center">{{trans('core.action_column')}}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($items as $item)
                    <tr>
                        <td class="text-center">{!! Form::input('checkbox', 'objSelected', $item['id']) !!}</td>
                        @foreach($controller->getListFieldsConfig() as $key => $config)
                            <td id="{{ $key . "_column" }}" class="text-center">
                                @if($template = array_get($config, 'template', null))
                                    @if($template[0] == '#')
                                        @include(ltrim($template, '#'), ['value' => $item[$key], 'config' => $config])
                                    @else
                                        {!! $template !!}
                                    @endif
                                @else
                                    {{ $item[$key] }}
                                @endif
                            </td>
                        @endforeach
                        <td class="col-settings text-center">
                            @if($item['_objectActions'] != null)
                            <div class="dropdown">
                                <button id="action-button-{{$item['id']}}" type="button"
                                        class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown"
                                        aria-expanded="false">
                                    Choose an action <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-right" role="menu">
                                    {!! $item['_objectActions'] !!}
                                </ul>
                            </div>
                            @else
                                None
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="row">
    @include("cms::partial.pagination")
</div>
@else
        <!-- <div class="col-lg-12"> -->
<div class="alert alert-warning alert-dismissable">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    <i class="fa fa-info-circle"></i> <strong>No item found.</strong>
</div>
<!-- </div> -->
@endif