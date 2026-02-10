<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 09/02/15
 * Time: 02:07 م
 */

$MODULE_NAME= 'pledges';
$TB_NAME= 'customers_room_move';
$count = $offset;

function class_name($status){
    if($status==30){
        return 'case_5';
    }elseif($status==10){
        return 'case_1';
    }elseif($status==20){
        return 'case_3';
    }elseif($status==0){
        return 'case_0';
    }

}

?>

<div class="tbl_container">
    <table class="table" id="page_tb" data-container="container">
        <thead>
        <tr>
            <th>#</th>
            <th>م</th>
            <th>حساب المستفيد </th>
            <th> من غرفة</th>
            <th>إلى غرفة </th>
            <th>تاريخ الإدخال</th>
            <th> الحالة</th>

              </tr>
        </thead>
        <tbody>
        <?php if($page > 1): ?>
            <tr>
                <td colspan="12" id="page-<?= $page ?>" class="page-sector" data-page="<?= $page ?>"></td>
            </tr>
        <?php endif; ?>
        <?php foreach($page_rows as $row) :?>

            <tr class="<?=class_name($row['ADOPT'])?>" ondblclick="javascript:show_row_details('<?=$row['SER']?>');">
                <td><?=$count?></td>
                <td><?=$row['SER']?></td>
                <td><?=$row['EMPLOYEE_ID'].': '.$row['CUSTOMER_ID_NAME']?></td>
                <td><?=$row['FROM_ROOM_ID'].': '.$row['FROM_ROOM_ID_NAME']?></td>
                <td><?=$row['TO_ROOM_ID'].': '.$row['TO_ROOM_ID_NAME']?></td>
                <td><?=$row['ENTRY_DATE']?></td>
                <td><?=$row['ADOPT_NAME']?></td>

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
