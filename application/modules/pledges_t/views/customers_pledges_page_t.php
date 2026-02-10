<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 09/02/15
 * Time: 02:07 م
 */
$MODULE_NAME= 'pledges';
$TB_NAME= 'customers_pledges';
$get_page_url=base_url("$MODULE_NAME/$TB_NAME/statusupdate");
$count = $offset;
?>
<style>

    .PLEDGES_EXIST_2 {
        background-color: red;

    }
</style>

<div class="tbl_container">
    <table class="table" id="page_tb_t" data-container="container">
        <thead>
        <tr>
            <th>#</th>
            <th>م</th>
            <th>حساب المستفيد </th>
            <th>الصنف</th>
            <th>حالة الصنف</th>
            <th>الكمية</th>
            <th>الباركود</th>
            <th>رقم سند الصرف</th>
            <th>حالة السند</th>
            <th>  المستخدم المعدل لحالة السند  </th>
            <th>المدخل</th>
            <th> حالة العهدة</th>
            <th>(غير موجودة)</th>

        </tr>
        </thead>
        <tbody>
        <?php if($page > 1): ?>
            <tr>
                <td colspan="12" id="page-<?= $page ?>" class="page-sector" data-page="<?= $page ?>"></td>
            </tr>
        <?php endif; ?>
        <?php foreach($page_rows_t as $row) :?>

            <tr class="" ondblclick="javascript:show_row_details('<?=$row['FILE_ID']?>');">
                <td><?=$count?></td>
                <td><?=$row['FILE_ID']?></td>
                <td><?=$row['CUSTOMER_ID']?></td>
                <td><?=$row['CLASS_ID'].': '.$row['CLASS_NAME']?></td>
                <td><?=$row['CLASS_TYPE_NAME']?></td>
                <td><?=$row['AMOUNT']?></td>
                <td><?=$row['CLASS_CODE_SER']?></td>
                <td><?=$row['CLASS_OUTPUT_ID']?></td>
                <td><?=$row['ADOPT_NAME']?></td>
                <td><?=get_user_name($row['ENTRY_USER'])?></td>
                <td onclick="javascript:show_row_details('<?=$row['D_FILE_ID']?>');"><?=$row['D_FILE_ID']?></td>
                <td onclick="javascript:update_status('<?=$row['FILE_ID']?>');"><span class="glyphicon glyphicon-minus"></span></td>
                <td  class='<?='PLEDGES_EXIST_'.$row['PLEDGES_EXIST']; ?>'></td>

                <?php  ?>
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
        document.getElementById("page_tb_t").style.display="none";
        document.getElementsByClassName("pagination")[0].style.display="none";
    }

</script>
