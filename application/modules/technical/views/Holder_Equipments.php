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
            <th style="width: 100px"> نوع العتاد </th>
            <th style="width: 400px">رقم صنف العتاد
            </th>

            <th style="width: 150px">   رقم مسلسل الصنف

            </th>

            <th    >  البيان

            </th>
            <th style="width: 20px"></th>
        </tr>
        </thead>

        <tbody>

        <?php if(count($details) <= 0) : ?>
            <tr>
                <td>
                    <input type="hidden" class="form-control" id="SER_0" value="0" name="SER[0]">
                    <select class="form-control" name="d_class_id[0]" id="dp_d_class_id_0">
                        <?php foreach($CLASS_TYPE as $row) :?>
                            <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td>

                    <input type="text"  name="base_d_class_id[0]" id="h_txt_base_d_class_id_0" class="form-control  col-md-4">
                    <input readonly  class="form-control  col-md-8"  id="txt_base_d_class_id_0" />

                </td>
                <td>
                    <input class="form-control col-md-8"  id="h_d_feeder_line_0" name="d_feeder_line[0]">
                    <button type="button" onclick="javascript:_showReport('<?= base_url('projects/adapter/public_index')?>/d_feeder_line_0/');"   class="btn blue icon icon-search col-md-3"></button>
                </td>
                <td>
                    <input class="form-control"   id="d_notes_0" name="d_notes[0]">
                </td>

                <td></td>
            </tr>

        <?php else: $count = 0; ?>
        <?php endif; ?>

        <?php foreach($details as $row) :?>

            <tr>
                <td>
                    <input type="hidden" class="form-control" id="SER_<?= $count ?>" value="<?= $row['SER'] ?>" name="SER[<?= $count ?>]">
                    <select class="form-control" name="d_class_id[<?= $count ?>]" id="dp_d_class_id_<?= $count ?>">
                        <?php foreach($CLASS_TYPE as $crow) :?>
                            <option <?= $row['CLASS_ID'] == $crow['CON_NO'] ?'selected':'' ?> value="<?= $crow['CON_NO'] ?>"><?= $crow['CON_NAME'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td>
                     <input type="text"  name="base_d_class_id[<?= $count ?>]" value="<?= $row['BASE_CLASS_ID'] ?>" id="h_txt_base_d_class_id_<?= $count ?>" class="form-control  col-md-4">
                    <input readonly  class="form-control  col-md-8"  id="txt_base_d_class_id_<?= $count ?>" />
                </td>
                <td>
                    <input class="form-control col-md-8" id="h_d_feeder_line_<?= $count ?>" value="<?= $row['FEEDER_LINE'] ?>"  name="d_feeder_line[<?= $count ?>]">
                    <button type="button" onclick="javascript:_showPopup(this);"   class="btn blue icon icon-search col-md-3"></button>

                </td>
                <td>
                    <input class="form-control" id="d_notes_<?= $count ?>" value="<?= $row['NOTES'] ?>" name="d_notes[<?= $count ?>]">
                </td>

                <td>
                    <?php if (HaveAccess(base_url('technical/Holders/delete_equipments')) && $count > 0) : ?>
                        <a href="javascript:;" onclick="javascript:delete_details(this,<?= $row['SER'] ?>);"><i class="icon icon-trash delete-action"></i> </a>
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