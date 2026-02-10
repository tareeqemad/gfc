<?php
 if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 8/28/14
 * Time: 8:46 AM
 */

$count = $offset - 1;
?>
<div class="tbl_container">
    <table class="table" id="projectTbl" data-container="container">
        <thead>
        <tr>

            <th style="width: 20px"  >#</th>
            <th style="width: 120px" >رقم الصنف</th>
            <th  >اسم الصنف</th>
            <th style="width: 120px" >السعر</th>
            <th  style="width: 50px"></th>

        </tr>
        </thead>
        <tbody>
        <?php if(count($rows) <= 0) : ?>
            <tr>
                <td>
                    <?= $count ?>
                    <input name="ser[]" type="hidden" value="0">
                </td>
                <td><input  name="class_id[]"   data-val="true" data-val-required="حقل مطلوب"  type="text" id="h_class_id_0" class="form-control" ></td>
                <td><input  name="class_id_name[]"   data-val="true" data-val-required="حقل مطلوب"   readonly type="text" id="class_id_0" class="form-control" ></td>
                <td><input  name="sale_price[]"   data-val="true" data-val-required="حقل مطلوب"  type="text" id="sale_price_0" class="form-control" ></td>
                <td></td>
            </tr>
        <?php endif; ?>
        <?php foreach($rows as $row) :?>
            <tr>
                <td><?= $count+1 ?>
                    <input name="ser[]" type="hidden"  value="<?= $row['SER'] ?>">
                </td>
                <td><input name="class_id[]"    data-val="true" data-val-required="حقل مطلوب"  value="<?= $row['CLASS_ID'] ?>" type="text" id="h_class_id_<?= $count ?>" class="form-control"></td>
                <td><input name="class_id_name[]"   data-val="true" data-val-required="حقل مطلوب"  value="<?= $row['CLASS_ID_NAME'] ?>" readonly type="text" id="class_id_<?= $count ?>" class="form-control"></td>
                <td><input name="sale_price[]"   data-val="true" data-val-required="حقل مطلوب"   value="<?= $row['SALE_PRICE'] ?>" type="text" id="sale_price_<?= $count ?>" class="form-control"></td>
                <td>
            <?php if(HaveAccess(base_url('projects/projects/prices/create'))): ?>
                    <a  href="javascript:;" onclick="javascript:delete_details(this,<?= $row['SER'] ?>);"><i class="icon icon-trash delete-action"></i> </a>
            <?php endif;?>
                </td>
            </tr>

            <?php $count =$count+1; ?>

        <?php endforeach;?>



        </tbody>

        <tfoot>
        <tr>
            <th rowspan="1" class="align-right" colspan="5">
                <?php if(HaveAccess(base_url('projects/projects/prices/create'))): ?>
                <a onclick="javascript:addRow();" onfocus="javascript:addRow();" href="javascript:;"><i class="glyphicon glyphicon-plus"></i>جديد</a>
                <?php endif;?>
            </th>
        </tr>
        </tfoot>
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