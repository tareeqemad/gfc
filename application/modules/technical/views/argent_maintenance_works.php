<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 06/06/15
 * Time: 08:54 ص
 */

?>
<div class="tbl_container">
    <table class="table" id="projects_detailsTbl" data-container="container">
        <thead>
        <tr>

            <th style="width: 400px"> رقم الموظف
            </th>
            <th >الملاحظات</th>

            <th style="width: 20px"></th>

        </tr>
        </thead>

        <tbody>

        <?php if(count($details) <= 0) : ?>
            <tr>

                <td>
                    <input type="hidden" class="form-control" id="wSER_0" value="0" name="WSER[0]">
                    <input type="text"  name="d_employee_id[0]" id="h_txt_d_employee_id_0" class="form-control  col-md-4">
                    <input readonly  class="form-control  col-md-8"  id="txt_d_employee_id_0" />

                </td>

                <td>
                    <input class="form-control"   id="d_hints_0" name="d_hints[0]">
                </td>

                <td></td>

            </tr>

        <?php else: $count = 0; ?>
        <?php endif; ?>

        <?php foreach($details as $row) :?>

            <tr>

                <td>
                    <input type="hidden" class="form-control" id="wSER_<?= $count ?>" value="<?= $row['SER'] ?>" name="WSER[<?= $count ?>]">
                    <input type="text"  name="d_employee_id[<?= $count ?>]" id="h_txt_d_employee_id_<?= $count ?>" value="<?= $row['EMPLOYEE_ID'] ?>" class="form-control  col-md-4">
                    <input readonly  class="form-control  col-md-8" value="<?= $row['CUSTOMER_NAME'] ?>"  id="txt_d_employee_id_<?= $count ?>" />
                </td>
                <td>
                    <input class="form-control"  value="<?= $row['HINTS'] ?>"  id="d_hints_<?= $count ?>" name="d_hints[<?= $count ?>]">
                </td>



                <td>
                    <?php if (HaveAccess(base_url('technical/Argent_Maintenance/delete_works')) && $count > 0) : ?>
                        <a href="javascript:;" onclick="javascript:delete_details_works(this,<?= $row['SER'] ?>);"><i class="icon icon-trash delete-action"></i> </a>
                    <?php endif; ?>

                </td>
            </tr>
            <?php $count++; ?>
        <?php endforeach;?>

        </tbody>
        <tfoot>
        <tr>
            <th rowspan="1" class="align-right" colspan="4">
                <?php if (count($details) <=0 || ( isset($can_edit)?$can_edit:false)) : ?>
                    <a onclick="javascript:add_row(this,'input',false);" onfocus="javascript:add_row(this,'input',false);" href="javascript:;"><i class="glyphicon glyphicon-plus"></i>جديد</a>
                <?php endif; ?>
            </th>


            <th  colspan="3"></th>

        </tr>

        </tfoot>
    </table>
</div>

<script>
    if (typeof initFunctions == 'function') {
        initFunctions();
    }


</script>