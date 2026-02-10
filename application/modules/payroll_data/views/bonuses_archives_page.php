<?php
/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 22/06/22
 * Time: 10:30 م
 */
$MODULE_NAME= 'payroll_data';
$TB_NAME= 'Bonuses';

$count=1;
?>

<div class="table-responsive">
    <table class="table table-bordered" id="bonuses_archives_tb" data-container="container">
        <thead class="table-primary">
        <tr>
            <th>#</th>
            <th>الرقم التسلسلي</th>
            <th>الدرجة</th>
            <th>العلاوة</th>
            <th>ملاحظات</th>
            <th>نوع العلاوة</th>
            <th>تاريخ التعديل</th>
            <th>معدل البيانات</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($page_rows as $row) : ?>

            <tr >
                <td><?=$count?></td>
                <td><?= $row['W_NO']?></td>
                <td><?= $row['W_NAME']?></td>
                <td><?= $row['ALLOWNCE']?></td>
                <td><?= $row['NOTES']?></td>
                <td><?= $row['BONUS_TYPE']?></td>
                <td><?= $row['GFC_REF_DATE']?></td>
                <td><?= $row['GFC_REF_USER']?></td>
                <?php
                $count++;
                ?>
            </tr>

        <?php endforeach; ?>
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
    if (typeof show_page == 'undefined'){
        document.getElementById("bonuses_archives_tb").style.display="none";
        document.getElementsByClassName("pagination")[0].style.display="none";
    }
</script>
