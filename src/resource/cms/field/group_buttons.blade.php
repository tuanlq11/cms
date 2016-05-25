<?php
$items = $options['items'];
?>

@if($options['wrapper'] !== false)
    <div <?= $options['wrapperAttrs'] ?> >
@endif

    @foreach ($items as $item)
        {!!  $item->render()  !!}
    @endforeach

@if ($options['wrapper'] !== false)
    </div>
@endif
