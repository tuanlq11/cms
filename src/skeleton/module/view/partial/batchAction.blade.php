<?php
$batch_actions = $controller->getConfig("{$action}.batch_action");
$batchConfig = ['' => ''];
foreach ($batch_actions as $key => $item) {
    $batchConfig[$key] = array_get($item, 'label', $key);
}
?>

<?php if(count($batch_actions)>0):?>
    <form id="batchAction" method="post" action="{{$controller->getGeneratedUrl('batchAction')}}">
        {!! Form::token() !!}
        <div class="input-group">
            {!! Form::select("_batchAction", $batchConfig, null, [
                'class' => 'form-control'
            ]) !!}
            <span class="input-group-btn">
                <button class="btn btn-success">{{trans('Go')}}</button>
            </span>
        </div>
    </form>

    <script type="text/javascript">
        $(document).ready(function () {
            $('form#batchAction').submit(function (event) {
                $selected = $("input[name='objSelected']:checked").map(function () {
                    return this.value
                }).get();

                $.each($selected, function (i, v) {
                    var input = $("<input>").attr({"type": "hidden", "name": "selected[]"}).val(v);
                    $('form#batchAction').append(input);
                });

                if (confirm('Are you sure?') && $selected.length > 0) {
                    return;
                }

                event.preventDefault();
            })
        })
    </script>
<?php endif;?>