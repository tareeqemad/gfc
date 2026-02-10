<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: tekrayem
 * Date: 04/10/15
 * Time: 12:23 م
 */
$count = $offset;

function class_name($adopt){
    if($adopt==1 ){
        return 'case_0';
    }else if($adopt==2){
        return 'case_1'; // case_3
    }else if($adopt==3){
        return 'case_2';
    }
    else if($adopt==4){
        return 'case_3';
    }
}


?>

<div class="tbl_container">
    <table class="table" id="page_tb" data-container="container">
        <thead>
        <tr>
            <th>#</th>
            <th>رقم المسلسل</th>
            <th>اسم الصنف  </th>
            <th>وصف الصنف</th>
            <th>اسم الصنف من المشتريات </th>
            <th>وصف المشتريات </th>
            <th> السعر 1</th>
            <th> السعر 2</th>
            <th> السعر 3</th>
            <th>  المتوسط</th>
            <th>تاريخ الادخال</th>
            <th>المدخل</th>
        </tr>
        </thead>
        <tbody>
        <?php if($page > 1): ?>
            <tr>
                <td colspan="10" id="page-<?= $page ?>" class="page-sector" data-page="<?= $page ?>"></td>
            </tr>
        <?php endif; ?>
        <?php foreach($page_rows as $row) :?>

            <tr class="<?=class_name($row['ADOPT'])?>" ondblclick="javascript:show_row_details('<?=$row['SER']?>');">
                <td><?=$count?></td>
                <td><?=$row['SER']?></td>
                <td><?=$row['CLASS_NAME_AR']?></td>
                <td><?=$row['CALSS_DESCRIPTION']?></td>
                <td><?=$row['PURCHASE_CLASS_NAME']?></td>
                <td><?=$row['PURCHASE_NOTES']?></td>
                <td><?=$row['PURCHASE_PRICE1']?></td>
                <td><?=$row['PURCHASE_PRICE2']?></td>
                <td><?=$row['PURCHASE_PRICE3']?></td>
                <td><?=$row['AVG_PRICE']?></td>
                <td><?=$row['ENTERY_DATE']?></td>
                <td title="<?=$row['ENTERY_USER_NAME']?>"><?=$row['ENTERY_USER_NAME']?></td>

            </tr>
        <?php $count=$count+1; endforeach;?>
        </tbody>
    </table>
</div>
<?php echo $this->pagination->create_links(); ?>

<script>
    if (typeof ajax_pager == 'function') {
        ajax_pager();
    }

</script>