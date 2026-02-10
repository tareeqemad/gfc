<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 08/01/15
 * Time: 02:18 م
 */

$count = $offset;

$CI =& get_instance();

if($show_balance and $page>1){
    $balance= $CI->session->userdata('stores_balance');
}

function class_name($source){
    if($source==5){
        return 'case_5';
    }elseif($source==3){
        return 'case_0';
    }elseif($source==2){
        return 'case_4';
    }else{
        return 'case_1';
    }
}

?>

<div class="tbl_container">
    <table class="table" id="page_tb" data-container="container">
        <thead>
        <tr>
            <th>#</th>
            <th>رقم المخزن</th>
            <th>المخزن</th>
            <th>رقم الصنف</th>
            <th>الصنف</th>
            <th>الوحدة</th>
            <th>الحالة</th>
            <th>الحساب</th>
            <?=0?"<th>الحركة</th>":''?>
            <th>الكمية الواردة</th>
            <th>الكمية الصادرة</th>
            <?=($show_balance)? '<th>الرصيد</th>' : '' ?>
            <th>السعر</th>
            <th>السعر المتوسط</th>
            <th>تاريخ الحركة</th>
            <?=0?"<th>تاريخ السعر</th>":''?>
            <th>المصدر</th>
            <th>رقم المصدر</th>
            <th>فتح المصدر</th>
        </tr>
        </thead>
        <tbody>
        <?php if($page > 1): ?>
            <tr>
                <td colspan="16" id="page-<?= $page ?>" class="page-sector" data-page="<?= $page ?>"></td>
            </tr>
        <?php endif; ?>
        <?php foreach($page_rows as $row) :?>

            <tr class="<?=class_name($row['SOURCE'])?>" >
                <td><?=$count?></td>
                <td><?=$row['STORE_NO']?></td>
                <td><?=$row['STORE_NAME']?></td>
                <td><?=$row['CLASS_ID']?></td>
                <td><?=$row['CLASS_ID_NAME']?></td>
                <td><?=$row['CLASS_ID_UNIT']?></td>
                <td><?=$row['CLASS_TYPE_NAME']?></td>
                <td><?=$row['ACOUNT_ID_NAME']?></td>
                <?=0?"<td>{$row['ACTION_NAME']}</td>":''?>
                <td><?=$row['ACTION']==1? number_format($row['AMOUNT'],2):''?></td>
                <td><?=$row['ACTION']==2? number_format($row['AMOUNT'],2):''?></td>
                <?php
                    if($show_balance){
                        if($row['ACTION']==1){
                            $balance+=$row['AMOUNT'];
                        }elseif($row['ACTION']==2){
                            $balance-=$row['AMOUNT'];
                        }
                        echo "<td>".number_format($balance,2)."</td>";
                    }
                ?>
                <td><?=number_format($row['PRICE'],2)?></td>
                <td><?=number_format($row['AVG_PRICE'],2)?></td>
                <td><?=$row['ADOPT_DATE']?></td>
                <?=0?"<td>{$row['PRICE_DATE']}</td>":''?>
                <td><?=$row['SOURCE_NAME']?></td>
                <td><?=$row['PK']?></td>
                <td><a target="_blank" href="<?=base_url("stores/".str_replace("{id}","{$row['PK']}","{$row['SOURCE_TB']}") )?>" ><i class='glyphicon glyphicon-share'></i></a></td>
                <?php $count++ ?>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
</div>
<?php
    echo $this->pagination->create_links();
    if($show_balance)
        $CI->session->set_userdata('stores_balance',$balance);
?>

<script>
    if (typeof initFunctions == 'function') {
        initFunctions();
    }
    if (typeof ajax_pager == 'function') {
        ajax_pager();
    }
    if (typeof show_page == 'undefined' <?= isset($show_page) ? '&& false' : '' ?>){
        document.getElementById("page_tb").style.display="none";
        document.getElementsByClassName("pagination")[0].style.display="none";
    }
</script>
