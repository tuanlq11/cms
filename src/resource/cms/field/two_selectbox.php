<?php if ($showLabel && $showField): ?>
    <?php if ($options['wrapper'] !== false): ?>
        <div <?= $options['wrapperAttrs'] ?> >
    <?php endif; ?>
<?php endif; ?>

<?php if ($showLabel && $options['label'] !== false): ?>
    <?= Form::label($name, $options['label'], $options['label_attr']) ?>
<?php endif; ?>

<div class="row two-select-box-container" id="<?= $options['container_id'] ?>">
    <div id="unselected-box" class="col-lg-6">
        <label>Available</label>
        <?= Form::select(null, array_except($options['choices'], $options['selected']), [], $options['attr']) ?>
        <?= Form::button("Add", $options['btnSelect']) ?>
    </div>
    <div id="selected-box" class="col-lg-6">
        <label>Selected</label>
        <?= Form::select($name, array_only($options['choices'], $options['selected']), [], $options['attr']) ?>
        <?= Form::button("Remove", $options['btnUnSelect']) ?>
    </div>
</div>


<?php if ($showLabel && $showField): ?>
<?php if ($options['wrapper'] !== false): ?>
</div>
<?php endif; ?>
<?php endif; ?>

<script type="text/javascript">
    $(document).ready(function () {
        (function () {
            var $container = $("#<?= $options['container_id'] ?>");
            var $btnSelect = $container.find('#<?= $options['btnSelect']['id']?>');
            var $btnUnSelect = $container.find('#<?= $options['btnUnSelect']['id']?>');
            var $selectedBox = $container.find('#selected-box > select');
            var $unselectedBox = $container.find('#unselected-box > select');

            $btnSelect.on('click', function () {
                $unselectedBox.find('option:selected').each(function ($index) {
                    var $cloneOpt = $(this).clone();
                    $selectedBox.append($cloneOpt);
                    $(this).remove();
                });
            });

            $btnUnSelect.on('click', function () {
                $selectedBox.find('option:selected').each(function ($index) {
                    var $cloneOpt = $(this).clone();
                    $unselectedBox.append($cloneOpt);
                    $(this).remove();
                });
            });

            $container.closest('form').on('submit', function () {
                $selectedBox.find('option').prop('selected', true);
            });
        }())
    })
</script>
