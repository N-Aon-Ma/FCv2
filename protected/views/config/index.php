<div class="form">
<?php echo CHtml::beginForm(); ?>
<table>
    <tr><th>Параметр</th><th>Значение</th></tr>
    <?php foreach($items as $i=>$item): ?>
        <tr>
            <td><?php echo $item->label; ?></td>
            <td><?php echo CHtml::activeTextField($item,"[$i]value"); ?> </td>
        </tr>
    <?php endforeach; ?>
</table>

<?php echo CHtml::submitButton('Сохранить'); ?>
<?php echo CHtml::endForm(); ?>
</div><!-- form -->
