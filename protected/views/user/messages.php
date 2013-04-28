<table>
        <?php for($i=0; $i<count($messages); $i++): ?>
     <tr>
        <td><?php echo $messages[$i]['author']; ?></td>
        <td><?php echo $messages[$i]['read']; ?></td>
        <td><?php echo $messages[$i]['date']; ?></td>
        <td><?php echo $messages[$i]['value']; ?></td>
    </tr>
        <?php endfor; ?>
</table>