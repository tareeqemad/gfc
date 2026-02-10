<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 23/12/14
 * Time: 10:01 ص
 */

$count = $offset;

function class_name($adopt, $req_case){
    if($adopt==0 or $req_case==3){
        return 'case_0';
    }elseif($adopt==5 and $req_case==2){
        return 'case_3';
    }elseif($adopt==5 and $req_case==1){
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
            <th>م</th>
            <th>رقم الطلب</th>
            <th>تاريخ الادخال</th>
            <th>الجهة الطالبة</th>
            <th style="max-width: 400px">حساب الجهة الطالبة</th>
            <th>الجهة المطلوب منها</th>
            <th>نوع الطلب</th>
            <th>رقم المشروع</th>
            <th>طلبية الخدمات  </th>
            <th>حالة الاعتماد</th>
            <th>حالة الطلب</th>
            <th>المدخل</th>
        </tr>
        </thead>
        <tbody>
        <?php if($page > 1): ?>
            <tr>
                <td colspan="13" id="page-<?= $page ?>" class="page-sector" data-page="<?= $page ?>"></td>
            </tr>
        <?php endif; ?>
        <?php foreach($page_rows as $row) :?>

            <tr data-mk="<?=$row['REQUEST_CASE']?>" class="<?=class_name($row['ADOPT'], $row['REQUEST_CASE'])?>" ondblclick="javascript:show_row_details('<?=$row['REQUEST_NO']?>');">
                <td><?=$count?></td>
                <td><?=$row['REQUEST_NO']?></td>
                <td><?=$row['BOOK_NO']?></td>
                <td><?=$row['ENTRY_DATE']?></td>
                <td><?=$row['REQUEST_SIDE_NAME']?></td>
                <td style="max-width: 400px"><?=$row['REQUEST_SIDE_ACCOUNT_NAME']?></td>
                <td><?=$row['REQUEST_STORE_FROM_NAME']?></td>
                <td><?=$row['REQUEST_TYPE_NAME']?></td>
                <td><?=$row['PROJECT_ID']?></td>
                <td><?=$row['STORE_SERV_REQ']?></td>
                <td><?=$row['ADOPT_NAME']?></td>
                <td><?=$row['REQUEST_CASE_NAME']?></td>
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
