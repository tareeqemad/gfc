<?php
$count = $offset;
?>
<div class="table-responsive">
    <table class="table table-bordered" id="page_tb">
        <thead class="table-light">
        <tr>
            <th>م</th>
            <th>السبب</th>
            <th>الحالة</th>
        </tr>
        </thead>
        <tbody>
        <?php if($page > 1): ?>
            <tr>
                <td colspan="3" id="page-<?= $page ?>" class="page-sector" data-page="<?= $page ?>"></td>
            </tr>
        <?php endif; ?>
        <?php foreach($page_rows as $row) :?>
            <tr class="tr_<?=$row['NO']?>" ondblclick="javascript:edit('<?= $row['NO'] ?>')" title="تعديل">
                <td><?=$count?></td>
                <td><?=$row['RESON']?></td>
                <td>
                    <?php if ( $row['IS_ACTIVE'] == 1){?>
                        يخصم
                    <?php } else if ($row['IS_ACTIVE'] == 0) { ?>
                       لا يخصم
                    <?php } else { ?>

                    <?php  } ?>
                </td>
                <?php $count++; ?>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
</div>
<?php echo $this->pagination->create_links(); ?>
<script>
    if (typeof initFunctions == 'function') {
        initFunctions();
    }
    if (typeof ajax_pager == 'function') {
        ajax_pager();
    }
</script>
