<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 8/28/14
 * Time: 8:46 AM
 */

$count = $offset;
?>
<div class="tbl_container">
    <table class="table" id="carsTbl" data-container="container">
        <thead>
        <tr>
            <th  ><input type="checkbox"  class="group-checkable" data-set="#userTbl .checkboxes"/></th>
            <th  >#</th>
            <th>رقم الملف </th>
            <th> رقم الألية </th>
            <th> صاحب العهدة</th>
            <th>نوع الوقود </th>
			<th>تاريخ التعديل </th>
            <th>اسم معدل البيانات </th>
            <th style="width: 120px;">متابعة العداد</th>
            <th style="width: 120px;"> حالة الآلية </th>
            <th>المقر </th>
            <th class="price" >الرصيد</th>
            <th style="width: 80px;">تقارير</th>
            <th style="width: 80px;">كرت الوقود</th>

        </tr>
        </thead>
        <tbody>
        <?php if($page > 1): ?>
            <tr>
                <td colspan="11" id="page-<?= $page ?>" class="page-sector" data-page="<?= $page ?>"></td>

            </tr>
        <?php endif; ?>
        <?php foreach($rows as $row) :?>

            <?php if ( $row['MACHINE_CASE'] == 1  ) :  ?>
                <tr   ondblclick="javascript:get_to_link('<?= base_url('payment/cars/get_id/').'/'.$row['CAR_FILE_ID'] ?>');">
            <?php else : ?>
                <tr style="color:red;background-color:#f3dfdf;"  ondblclick="javascript:get_to_link('<?= base_url('payment/cars/get_id/').'/'.$row['CAR_FILE_ID'] ?>');">
            <?php endif; ?>

                <td><input type="checkbox" class="checkboxes" value="<?=$row['CAR_FILE_ID'] ?>"/></td>
                <td><?= $count ?></td>
                <td><?= $row['CAR_FILE_ID'] ?></td>
                <td><?= $row['CAR_NUM'] ?></td>
                <td><?= $row['CAR_OWNER'] ?></td>
                <td><?= $row['FUEL_TYPE_NAME'] ?></td>
				<td><?= $row['DATE_UPDATE'] ?></td>
                <td><?= $row['USER_UPDATE_NAME'] ?></td>
                <td><?= $row['CAR_CASE_NAME'] ?></td>
                <td><?= $row['MACHINE_CASE_NAME'] ?></td>
                <td><?= $row['BRANCH_ID_NAME'] ?></td>
                <td class="price"><?= $row['BANLANCE'] ?></td>
                <td>
                    <a href="javascript:;"  class="btn-xs btn-success" onclick="javascript:select_account('<?= $row['CAR_FILE_ID'] ?>','<?= $row['CAR_OWNER'] ?>','');"><i class="icon icon-print"></i> التقارير</a>
                </td>
            <td class='text-center'>
                <a href="javascript:;" onclick="javascript:print_report(<?= $row['CAR_FILE_ID'] ?>);" class="modal-effect" data-bs-effect="effect-rotate-left"  title="طباعة كرت الوقود"
                   style="color: #2075f8"><i class="glyphicon glyphicon-print"></i> </a>
            </td>
            </tr>
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



</script>