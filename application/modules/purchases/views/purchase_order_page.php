<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 24/03/15
 * Time: 12:05 م
 */

$count = $offset;

function class_name($adopt, $count_left, $committee_case, $is_returned_600){
    if($adopt==0 or $count_left==0){
        return 'case_0';
    }elseif($adopt==50 and $count_left>0){
        return 'case_1'; // case_3
    }elseif($committee_case==5 ){
        return 'case_2';
    }elseif($is_returned_600){ // طلب شراء مرجع من عرض السعر
        return 'case_5';
    }else{
        return 'case_1';
    }
}

$committee_day= HaveAccess(base_url('purchases/suppliers_offers/committee_notify'))?1:0;

?>

<div class="tbl_container">
    <table class="table" id="page_tb" data-container="container">
        <thead>
        <tr>
            <th>-</th>
            <th>#</th>
            <th>رقم المسلسل</th>
            <th>رقم طلب الشراء</th>
            <th>المقر</th>
            <th>نوع الطلب</th>
            <th>نوع الاصناف</th>
            <th>البيان</th>
            <th>تاريخ الادخال</th>
            <th>المدخل</th>
            <th>حالة الطلب</th>
            <th>مصصم عرض السعر </th>
            <th>حالة عرض السعر</th>
            <th>حالة التحويل</th>
            <th>تحويل للتوريد</th>
            <th>تنفيذ اصناف مؤجلة</th>
        </tr>
        </thead>
        <tbody>
        <?php if($page > 1): ?>
            <tr>
                <td colspan="10" id="page-<?= $page ?>" class="page-sector" data-page="<?= $page ?>"></td>
            </tr>
        <?php endif; ?>
        <?php foreach($page_rows as $row) :?>

            <tr class="<?=class_name($row['ADOPT'], 1, $row['COMMITTEE_CASE'], $row['IS_RETURNED_600'])?>" ondblclick="javascript:show_row_details('<?=$row['PURCHASE_ORDER_ID']?>','<?=$row['QUOTE_CURR_ID']?>','<?=$row['QUOTE_CURR_ID_NAME']?>');">
                <td><input type="checkbox" <?php if (($row['ORDER_PURPOSE']==2) or ($row['PURCHASE_TYPE']>3) OR (($row['ADOPT']<>70)or ($row['DESIGN_QUOTE_CASE']<>1) or ($row['DESIGN_QUOTE_USER']<>'') or ($row['COMMITTEE_CASE']<>1))) echo "disabled"; ?> class="checkboxes" value="<?=$row['PURCHASE_ORDER_ID'] ?>" /></td>
                <td><?=$count?></td>
                <td><?=$row['PURCHASE_ORDER_ID']?></td>
                <td><?=$row['PURCHASE_ORDER_NUM']?></td>
                <td><?=$row['BRANCH_NAME']?></td>
                <td><?=$row['PURCHASE_TYPE_NAME']?></td>
                <td><?=$row['PURCHASE_ORDER_CLASS_TYPE_NAME']?></td>
                <td><?=$row['NOTES']?></td>
                <td><?=$row['ENTRY_DATE']?></td>
                <td title="<?=$row['ENTRY_USER_NAME']?>"><?=get_user_name($row['ENTRY_USER'])?></td>
                <td><?=$row['ADOPT_NAME']?></td>
                <td title="<?=$row['DESIGN_QUOTE_USER_NAME']?>"><?=get_user_name($row['DESIGN_QUOTE_USER'])?></td>
                <td><?=$row['DESIGN_QUOTE_CASE_NAME']?></td>
                <td><?=$committee_day && ($row['COMMITTEE_CASE']==2 || $row['COMMITTEE_CASE']==4)?
                        ($row['COMMITTEE_CASE_NAME'].'<a title="اضغط لارسال تنبيه لاعضاء اللجنة" onclick="javascript:committee_notify('.$row['PURCHASE_ORDER_ID'].','.$row['ORDER_PURPOSE'].','.$row['COMMITTEE_CASE'].');" class="btn-xs btn-warning" href="javascript:;">'.' ('.$row['COMMITTEE_DAY'].')يوم'.'</a>'):
                        (($row['COMMITTEE_CASE']>=5)?($row['COMMITTEE_CASE_NAME']/*.' <a title="تقرير البت والترسية" onclick="javascript:print_rep('.$row['PURCHASE_ORDER_ID'].');" class="btn-xs btn-success" href="javascript:;"><i class="glyphicon glyphicon-print"></i></a>'*/.'<a title="تقرير البت والترسية" onclick="javascript:print_rep_jasper('.$row['PURCHASE_ORDER_ID'].');" class="btn-xs btn-success" href="javascript:;"><i class="glyphicon glyphicon-print"></i></a>') :($row['COMMITTEE_CASE_NAME'])) ?></td>
                <?php if($row['HAS_ORDER']==2){ ?>
                    <td><a id="do_order_<?=$row['PURCHASE_ORDER_ID']?>" href="javascript:;" onclick="javascript:do_order('<?=$row['PURCHASE_ORDER_ID']?>','<?=$row['ORDER_PURPOSE']?>');" ><i class='glyphicon glyphicon-share'></i></a></td>
                <?php  }else echo "<td> </td>"; ?>
                <?php if($row['AWARD_DELAY']==1){ ?>
                    <td><a href="<?=base_url("purchases/suppliers_offers/delay{$row['ORDER_PURPOSE']}/{$row['PURCHASE_ORDER_ID']}")?>" ><i class='glyphicon glyphicon-share'></i></a></td>
                <?php
                }else echo "<td> </td>";
                $count++;
                ?>
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


