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

            <th style="width: 400px">رقم صنف
            </th>
            <th style="width: 100px">الوحدة</th>
            <th style="width: 100px">العدد</th>
            <th style="width: 150px">حالة</th>
            <th style="width: 20px"></th>
            <th></th>
        </tr>
        </thead>

        <tbody>

        <?php if(count($details) <= 0) : ?>
            <tr>

                <td>

                    <input type="text"  name="d_class_id[0]" id="h_txt_d_class_id_0" class="form-control  col-md-4">
                    <input readonly  class="form-control  col-md-8"  id="txt_d_class_id_0" />

                </td>

                <td>
                    <input class="form-control"  type="hidden" id="unit_txt_d_class_id_0" name="d_class_unit[0]">
                    <input class="form-control"  readonly type="text" id="unit_name_txt_d_class_id_0"  >
                </td>

                <td>
                    <input class="form-control"   id="d_class_count_0" name="d_class_count[0]">
                </td>

                <td>
                    <input type="hidden" class="form-control" id="SER_0" value="0" name="SER[0]">
                    <select class="form-control" name="d_class_used_case[0]" id="dp_class_used_case_0">
                        <?php foreach($CLASS_TYPE as $row) :?>
                            <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
                
                <td></td>
                <td></td>
            </tr>

        <?php else: $count = 0; ?>
        <?php endif; ?>

        <?php foreach($details as $row) :?>

            <tr>

                <td>
                    <input type="text"  name="d_class_id[<?= $count ?>]" id="h_txt_d_class_id_<?= $count ?>" value="<?= $row['CLASS_ID'] ?>" class="form-control  col-md-4">
                    <input readonly  class="form-control  col-md-8" value="<?= $row['CLASS_ID_NAME'] ?>"   id="txt_d_class_id_<?= $count ?>" />
                </td>
                <td>

                    <input class="form-control" value="<?= $row['CLASS_UNIT'] ?>"  type="hidden" id="unit_txt_d_class_id_<?= $count ?>" name="d_class_unit[<?= $count ?>]">
                    <input class="form-control" value="<?= $row['CLASS_UNIT_NAME'] ?>"  readonly type="text" id="unit_name_txt_d_class_id_<?= $count ?>"  >
                </td>
                <td>
                    <input class="form-control"   value="<?= $row['CLASS_COUNT'] ?>"   id="d_class_count_<?= $count ?>" name="d_class_count[<?= $count ?>]">

                </td>


                <td>
                    <input type="hidden" class="form-control" id="SER_<?= $count ?>" value="<?= $row['SER'] ?>" name="SER[<?= $count ?>]">
                    <select class="form-control" name="d_class_used_case[<?= $count ?>]" id="dp_class_used_case_<?= $count ?>">
                        <?php foreach($CLASS_TYPE as $row) :?>
                            <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>

                <td>
                    <?php if (HaveAccess(base_url('technical/Argent_Maintenance/delete_tools')) && $count > 0) : ?>
                        <a href="javascript:;" onclick="javascript:delete_details_tools(this,<?= $row['SER'] ?>);"><i class="icon icon-trash delete-action"></i> </a>
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