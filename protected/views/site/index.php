<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
?>

<?php foreach($news as $i=>$item): ?>
<table>
    <tr>
        <td><?php echo $news[$i]->head; ?></td>
        <td><?php echo $news[$i]->body; ?></td>
        <td><?php echo $news[$i]->date; ?></td>
        <td><?php echo $news[$i]->head_image_url; ?></td>
        <td><?php echo $news[$i]->author->origin; ?></td>
    </tr>
</table>
<?php endforeach; ?>
