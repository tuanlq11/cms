<?php if ($showLabel && $showField): ?>
    <?php if ($options['wrapper'] !== false): ?>
        <div <?= $options['wrapperAttrs'] ?> >
    <?php endif; ?>
<?php endif; ?>

<?php if ($showLabel && $options['label'] !== false): ?>
    <?= Form::label($name, $options['label'], $options['label_attr']) ?>
<?php endif; ?>
        <!-- TO DO : // YES NO CHECKBOX HERE -->
        <div class="row yes-no-checkbox-container" id="<?= $options['container_id'] ?>">
            <?= Form::checkbox(null, 1, (bool)$options['value'], ['id' => str_replace(['[',']'],['_',''],$options['attr']['id'])])?>
            <label for="<?= str_replace(['[',']'],['_',''],$options['attr']['id']) ?>" class="label-success"></label>
            <?= Form::text($name, (int)$options['value'], $options['attr']) ?>
        </div>
<?php if ($showLabel && $showField): ?>
	<?php if ($options['wrapper'] !== false): ?>
		</div>
	<?php endif; ?>
<?php endif; ?>

<script type="text/javascript">
    $(document).ready(function () {
        var $container = $("#<?= $options['container_id'] ?>");
        var $labelCheckbox = $container.find('label');
        var $inputText = $container.find('input[type="text"]');
        $labelCheckbox.on('click', function (){
            $inputText.val() == '1' ? $inputText.val('0'): $inputText.val('1');
        });
    });
</script>