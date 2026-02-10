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
$edit_barcode_url=base_url("$MODULE_NAME/$TB_NAME/edit_barcode");
$count = $offset;

function class_name($status){
    if($status==3){
        return 'case_5';
    }elseif($status==1){
        return 'case_1';
    }elseif($status==2){
        return 'case_3';
    }elseif($status==4){
        return 'case_2';
    }elseif($status==5){
        return 'case_4';
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
           <!-- <th> الغرفة</th> -->
            <th>مصدر العهدة</th>
            <th>حالة العهدة</th>
            <th>الصنف</th>
            <th>حالة الصنف</th>
            <th>الكمية</th>
            <th>نوع العهدة</th>
            <th>أقسام العهد</th>
            <th>الباركود القديم</th>
            <th>الباركود الجديد</th>
            <th>حساب المصروف للمستفيدين</th>
            <th>رقم سند الصرف</th>
            <th>العمر الزمني المتبقي </th>
            <th>حالة السند</th>
            <th>  المستخدم المعدل لحالة السند  </th>
            <th>المدخل</th>
            <th  >تابع لعهدة</th>
            <?php if (HaveAccess(base_url("pledges/customers_pledges/edit/")))  {?> <th>العرض</th> <?php } ?>
            <?php if (HaveAccess(base_url("pledges/customers_pledges/movement/")))  {?> <th>نقل</th> <?php } ?>
            <?php if (HaveAccess(base_url("pledges/customers_pledges/return_store/")))  {?> <th>ارجاع</th> <?php } ?>
            <?php if (HaveAccess($edit_barcode_url))  {?> <th>إضافة الباركود</th> <?php } ?>
        </tr>
        </thead>
        <tbody>
        <?php if($page > 1): ?>
            <tr>
                <td colspan="23" id="page-<?= $page ?>" class="page-sector" data-page="<?= $page ?>"></td>
            </tr>
        <?php endif; ?>
        <?php foreach($page_rows as $row) :?>

            <tr class="<?=class_name($row['STATUS'])?>" ondblclick="javascript:show_row_details('<?=$row['FILE_ID']?>');">
                <td><?=$count?></td>
                <td><?=$row['FILE_ID']?></td>
                <td><?=$row['CUSTOMER_ID'].': '.$row['CUSTOMER_ID_NAME']?></td>
             <!--   <td><? //=$row['ROOM_ID'].': '.$row['ROOM_ID_NAME']?></td> -->
                <td><?=$row['SOURCE_NAME']?></td>
                 <td><?php if ($row['STATUS']==2) echo $row['STATUS_NAME']."-".$row['CUSTOMER_MOVMENT_ID_NAME'] ; else echo $row['STATUS_NAME']; ?></td>
                <td><?=$row['CLASS_ID'].': '.$row['CLASS_NAME']?></td>
                <td><?=$row['CLASS_TYPE_NAME']?></td>
                <td><?=$row['AMOUNT']?></td>
                <td><?=$row['CUSTODY_TYPE_NAME']?></td>
                <td><?=$row['PERSONAL_CUSTODY_TYPE_NAME']?></td>
                <td><?=$row['CLASS_CODE_SER']?></td>
                <td><?=$row['BARCODE']?></td>
                <td><?=$row['EXP_ACCOUNT_CUST_NAME']?></td>
                <td><?=$row['CLASS_OUTPUT_ID']?></td>
                <td><?=$row['REM_AGE']?></td>
                <td><?=$row['ADOPT_NAME']?></td>
                <td><?=$row['STATUS_USER_NAME']?></td>
                <td><?=get_user_name($row['ENTRY_USER'])?></td>
                <td onclick="javascript:show_row_details('<?=$row['D_FILE_ID']?>');"><?=$row['D_FILE_ID']?></td>
              <?php if((HaveAccess(base_url("pledges/customers_pledges/edit/")))){?>
                    <td> <?php if ($row['STATUS']==1) { ?> <a target="_blank" href="<?=base_url("pledges/customers_pledges/edit/{$row['FILE_ID']}" )?>"><i class='glyphicon glyphicon-share'></i></a> </td>
                            <?php } ?>
                <?php }


                ?>

                <?php if((HaveAccess(base_url("pledges/customers_pledges/movement/")))){?>
                <td> <?php if (($row['STATUS']==1 || $row['STATUS']==2) and ($row['FOLLOW_FILE_ID']=='' and $row['ADOPT']==2 )) { ?> <a target="_blank" href="<?=base_url("pledges/customers_pledges/movement/{$row['FILE_ID']}" )?>"><i class='glyphicon glyphicon-share'></i></a></td>
                    <?php } ?>
                    <?php }


                    ?>
                <?php if((HaveAccess(base_url("pledges/customers_pledges/return_store/")))){?>
                    <td> <?php if (($row['STATUS']==1 || ($row['STATUS']==4))&&( $row['FOLLOW_FILE_ID']=='' and $row['ADOPT']==2)) { ?> <a target="_blank" href="<?=base_url("pledges/customers_pledges/return_store/{$row['FILE_ID']}" )?>"><i class='glyphicon glyphicon-share'></i></a></td>
                    <?php } ?>
                <?php }


                ?>
                <?php if(HaveAccess($edit_barcode_url)){?>
                    <td> <?php if ($row['BARCODE']==''  and $row['CLASS_ACOUNT_TYPE']==1 and ($row['STATUS']==1) ) { ?> <i  class="glyphicon-barcode" onclick="customers_pledges_get('<?=$row['FILE_ID']?>');"></i></td>
                    <?php } ?>
                <?php }


                ?>
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
