<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani + mbadawi
 * Date: 12/06/16
 * Time: 12:47 م
 */

$count = $offset;

?>

<div class="tbl_container">
   <table class="table" id="page_tb" data-container="container">
        <thead>
            <tr>
                <th>#</th>
                <th>رقم أمر التقييم</th>
                <th>نوع  أمر التقييم</th>
                <th>بداية التقييم </th>
                <th>نهاية التقييم</th>
                <th>حالة  أمر التقييم</th>
                <th>تفعيل مرحلة التقييم</th>
                <th>تاريخ الإدخال</th>
                <th>المدخل </th>
            </tr>
        </thead>
        <tbody>
        <?php if($page > 1): ?>
            <tr>
                <td colspan="10" id="page-<?= $page ?>" class="page-sector" data-page="<?= $page ?>"></td>
            </tr>
        <?php endif; ?>
            <?php foreach($page_rows as $row) :?>
            <tr class="" ondblclick="javascript:show_row_details('<?=$row['EVALUATION_ORDER_ID']?>');">
                 <td><?=$count?></td>
                 <td><?=$row['EVALUATION_ORDER_ID']?></td>
                 <td><?=$row['ORDER_TYPE_NAME']?></td>
                 <td><?=$row['ORDER_START']?></td>
                 <td><?=$row['ORDER_END']?></td>
                 <td><?=$row['STATUS_NAME']?></td>
                 <td><?=$row['LEVEL_ACTIVE_NAME']?></td>
                 <td><?=$row['ENTRY_DATE']?></td>
                 <td><?=get_short_user_name($row['ENTRY_USER_NAME'])?></td>
            </tr>
            <?php
                $count++;
                endforeach;
            ?>
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