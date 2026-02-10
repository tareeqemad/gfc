<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 06/06/15
 * Time: 09:54 ص
 */

$count = $offset;

?>

<div class="tbl_container">
    <table class="table" id="page_tb" data-container="container">
        <thead>
        <tr>
            <th>#</th>
            <th>رقم السند</th>
            <th>تاريخ الادخال</th>
            <th>نوع السند</th>
            <th> بيان السند</th>
            <th>السنة المالية</th>
            <th>الاجمالي</th>
            <th>حالة السند</th>
            <th>المدخل</th>
        </tr>
        </thead>
        <tbody>
        <?php if($page > 1): ?>
            <tr>
                <td colspan="7" id="page-<?= $page ?>" class="page-sector" data-page="<?= $page ?>"></td>
            </tr>
        <?php endif; ?>
        <?php foreach($page_rows as $row) :?>

            <tr ondblclick="javascript:show_row_details('<?=$row['AMENDMENT_ID']?>');">
                <td><?=$count?></td>
                <td><?=$row['AMENDMENT_ID']?></td>
                <td><?=$row['ENTRY_DATE']?></td>
                <td><?=$row['AMENDMENT_TYPE_NAME']?></td>
                <td><?=$row['NOTE_S']?></td>
                <td><?=$row['YEAR']?></td>
                <td><?=$row['AMENDMENT_SUM']?></td>
                <td><?=$row['ADOPT_NAME']?></td>
                <td><?=get_user_name($row['ENTRY_USER'])?></td>
                <?php $count++ ?>
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
    if (typeof show_page == 'undefined'){
        document.getElementById("page_tb").style.display="none";
        document.getElementsByClassName("pagination")[0].style.display="none";
    }
</script>
