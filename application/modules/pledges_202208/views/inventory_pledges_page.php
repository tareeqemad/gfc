<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * User: tekrayem
 * Date: 22/12/20
 * Time: 08:15 ص
 */

$MODULE_NAME= 'pledges';
$TB_NAME= 'inventory_pledges';
$edit_barcode_url=base_url("$MODULE_NAME/$TB_NAME/edit_barcode");
$is_exist_url= base_url("$MODULE_NAME/$TB_NAME/exist_pledge");
$count = $offset;

function class_name($status){

   if($status==1){
        return 'case_1';
    }elseif($status==2){
        return 'case_3';
    }

}

?>

<div class="tbl_container">
    <table class="table" id="page_tb" data-container="container">
        <thead>
        <tr>
            <th><input type="checkbox" id="select-all"></th>
            <th>#</th>
            <th>م</th>
            <th>حساب المستفيد </th>
            <th> الغرفة</th>
            <th>مصدر العهدة</th>
            <th>حالة العهدة</th>
            <th>الصنف</th>
            <th>حالة الصنف</th>
            <th>الكمية</th>
            <th>الحالة </th>
            <th>نوع العهدة</th>
            <th>أقسام العهد</th>
            <th>الباركود</th>
            <th  >تابع لعهدة</th>
            <th  > العهدة موجودة</th>
            <?php if (HaveAccess(base_url("pledges/inventory_pledges/edit/")))  {?> <th>العرض</th> <?php } ?>
            <?php if (HaveAccess(base_url("pledges/inventory_pledges/movement/")))  {?> <th>نقل</th> <?php } ?>
               <?php if (HaveAccess($edit_barcode_url))  {?> <th>إضافة الباركود</th> <?php } ?>
        </tr>
        </thead>
        <tbody>
        <?php if($page > 1): ?>
            <tr>
                <td colspan="12" id="page-<?= $page ?>" class="page-sector" data-page="<?= $page ?>"></td>
            </tr>
        <?php endif; ?>
        <?php foreach($page_rows as $row) :?>

            <tr class="<?=class_name($row['IS_EXIST'])?>" ondblclick="javascript:show_row_details('<?=$row['FILE_ID']?>');">
                <td> <?php //if ($row['IS_ADD']==1) { ?>
                <input type="checkbox"  class="checkboxes" value="<?=$row['FILE_ID'] ?>" />
                <?php //} ?></td>
                <td><?=$count?></td>

                <td><?=$row['FILE_ID']?></td>
                <td><?=$row['CUSTOMER_ID'].': '.$row['CUSTOMER_ID_NAME']?></td>
                <td><?=$row['ROOM_ID'].': '.$row['ROOM_ID_NAME']?></td>
                <td><?=$row['SOURCE_NAME']?></td>
                <td><?php if ($row['STATUS']==2) echo $row['STATUS_NAME']."-".$row['CUSTOMER_MOVMENT_ID_NAME'] ; else echo $row['STATUS_NAME']; ?></td>
                <td><?=$row['CLASS_ID'].': '.$row['CLASS_NAME']?></td>
                <td><?=$row['CLASS_TYPE_NAME']?></td>
                <td><?=$row['AMOUNT']?></td>
                <td><?=$row['ADOPT_NAME']?></td>
                <td><?=$row['CUSTODY_TYPE_NAME']?></td>
                <td><?=$row['PERSONAL_CUSTODY_TYPE_NAME']?></td>
                <td><?=$row['BARCODE']?></td>
                <td onclick="javascript:show_row_details('<?=$row['D_FILE_ID']?>');"><?=$row['D_FILE_ID']?></td>
                <td><input type="checkbox" id="is_exist" name="is_exist" <?php if ($row['IS_EXIST']==1) { ?>checked="checked" <?php } else {?> <?php } ?> onclick="changeExist(this,'<?=$row['FILE_ID']?>');"></td>

                <?php if((HaveAccess(base_url("pledges/inventory_pledges/edit/")))){?>
                    <td> <?php if ($row['IS_ADD']==1) { ?> <a target="_blank" href="<?=base_url("pledges/inventory_pledges/edit/{$row['FILE_ID']}" )?>"><i class='glyphicon glyphicon-share'></i></a> </td>
                    <?php } ?>
                <?php }


                ?>

                <?php if((HaveAccess(base_url("pledges/inventory_pledges/movement/")))){?>
                    <td> <?php if  (($row['STATUS']==1 || $row['STATUS']==2) and ($row['FOLLOW_FILE_ID']==''  ) and ($row['IS_EXIST']!=1)) { ?> <a target="_blank" href="<?=base_url("pledges/inventory_pledges/movement/{$row['FILE_ID']}" )?>"><i class='glyphicon glyphicon-share'></i></a></td>
                    <?php } ?>
                <?php }


                ?>


                <?php if(HaveAccess($edit_barcode_url)){?>
                    <td> <?php if ($row['BARCODE']=='' and $row['CLASS_ACOUNT_TYPE']==1 and ($row['STATUS']==1)) { ?>
                        <i  class="glyphicon-barcode" onclick="inventory_pledges_get('<?=$row['FILE_ID']?>');"></i></td>
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
    $('#select-all').click(function(event) {
        var chk= false;
        if(this.checked) {
            chk= true;
        }
        $('.checkboxes:checkbox').each(function() {
            this.checked = chk;
        });
    });

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
