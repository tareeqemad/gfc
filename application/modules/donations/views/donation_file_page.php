<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 25/10/15
 * Time: 11:06 ص
 */


$count = $offset;

?>
<div class="tbl_container">
    <table class="table" id="donation_tb" data-container="container">
        <thead>
        <tr>
            <th>#</th>
            <th>م</th>
            <th>الرقم</th>
            <th>رقم المنحة- من الجهة المانحة </th>
            <th>الجهة المانحة</th>
            <th>تاريخ اعتماد المنحة</th>
            <th>تاريخ نهاية المنحة</th>
            <th>اسم المنحة</th>
            <th>حساب المنحة</th>
            <th>الجهة الممولة</th>
            <th>نوع المنحة</th>
            <th>طبيعة المنحة</th>
            <th>عملة المنحة</th>
            <th>مخزن المنحة</th>
            <th>المدخل</th>
            <th>حالة الاعتماد</th>
            <?php if (HaveAccess(base_url("donations/donation/view/")))  {?> <th>تعديل حالات الاصناف</th> <?php } ?>

        </tr>
        </thead>
        <tbody>
        <?php if($page > 1): ?>
            <tr>
                <td colspan="12" id="page-<?= $page ?>" class="page-sector" data-page="<?= $page ?>"></td>
            </tr>
        <?php endif; ?>
        <?php foreach($page_rows as $row) : ?>

        <tr  ondblclick="javascript:show_row_details('<?=$row['DONATION_FILE_ID']?>','<?=$row['DONATION_TYPE']?>');" data-id='<?=$row['DONATION_FILE_ID']?>'>
            <td><input type='checkbox' class='checkboxes' value='<?=$row['DONATION_FILE_ID']?>'></td>
            <td><?=$count?></td>
            <td><?=$row['DONATION_FILE_ID']?></td>
            <td><?=$row['DONATION_ID']?></td>
            <td><?=$row['DONATION_NAME']?></td>
            <td><?=$row['DONATION_APPROVED_DATE']?></td>
            <td><?=$row['DONATION_END_DATE']?></td>
            <td><?=$row['DONATION_LABEL']?></td>
            <td><?=$row['DONATION_ACCOUNT']?></td>
            <td><?=$row['DONOR_NAME']?></td>
            <td><?=$row['DONATION_TYPE_NAME']?></td>
            <td><?=$row['DONATION_KIND_NAME']?></td>
            <td><?=$row['CURR_ID_NAME']?></td>
            <td><?=$row['STORE_ID_NAME']?></td>
            <td><?=get_user_name($row['INPUT_USER'])?></td>
            <td><?=$row['DONATION_FILE_CASE_NAME']?></td>
            <?php if((HaveAccess(base_url("donations/donation/view/")))){?>
                <td> <a href="<?=base_url("donations/donation/view/{$row['DONATION_FILE_ID']}" )?>"><i class='glyphicon glyphicon-share'></i></a> </td>

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
