<?php if ($options['wrapper'] !== false): ?>
<div <?= $options['wrapperAttrs'] ?> >
    <?php endif; ?>

    <?= Html::link($options['url'], $options['label'], $options['attr']) ?>
    <?php include 'help_block.php' ?>

    <?php if ($options['wrapper'] !== false): ?>
</div>
<?php endif; ?>
