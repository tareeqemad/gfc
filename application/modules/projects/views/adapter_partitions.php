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
            <th style="width: 100px"> الاتجاه </th>
            <th style="width: 100px">  رقم ترتيب السكينة في المحول
            </th>

            <th style="width: 100px">  قدرة السكينة
            </th>
            <th style="width: 100px">سعة الفيوز
            </th>
            <th style="width: 100px"  >تاريخ تركيب السكينة
            </th>
            <th    >  ملاحظات
            </th>
            <th style="width: 20px"></th>
        </tr>
        </thead>

        <tbody>

        <?php if(count($details) <= 0) : ?>
            <tr>
                <td>
                    <input type="hidden" class="form-control" id="SER_0" value="0" name="SER[0]">
                    <select class="form-control" name="partition_direction[0]" id="dp_partition_direction_0">
                        <?php foreach($power_adapter_direction as $row) :?>
                            <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td>
                    <input class="form-control"   id="partition_id_0" name="partition_id[0]">
                </td>
                <td>
                    <input class="form-control"   id="partition_power_0" name="partition_power[0]">
                </td>
                <td>
                    <input class="form-control"   id="partition_capacity_0" name="partition_capacity[0]">
                </td>
                <td>
                    <input class="form-control"   data-type="date" data-date-format="DD/MM/YYYY" id="d_installation_date_0" name="d_installation_date[0]">
                </td>
                <td>
                    <input class="form-control"   id="hint_0" name="hint[0]">
                </td>
                <td></td>
            </tr>

        <?php else: $count = 0; ?>
        <?php endif; ?>

        <?php foreach($details as $row) :?>

            <tr>
                <td>
                    <input type="hidden" class="form-control" id="SER_<?= $count ?>" value="<?= $row['SER'] ?>" name="SER[<?= $count ?>]">
                    <select class="form-control" name="partition_direction[<?= $count ?>]" id="dp_partition_direction_<?= $count ?>">
                        <?php foreach($power_adapter_direction as $crow) :?>
                            <option <?= $row['PARTITION_DIRECTION'] == $crow['CON_NO'] ?'selected':'' ?> value="<?= $crow['CON_NO'] ?>"><?= $crow['CON_NAME'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td>
                    <input class="form-control" id="partition_id_<?= $count ?>" value="<?= $row['PARTITION_ID'] ?>" name="partition_id[<?= $count ?>]">
                </td>
                <td>
                    <input class="form-control" id="partition_power_<?= $count ?>" value="<?= $row['PARTITION_POWER'] ?>"  name="partition_power[<?= $count ?>]">
                </td>
                <td>
                    <input class="form-control" id="partition_capacity_<?= $count ?>" value="<?= $row['PARTITION_CAPACITY'] ?>" name="partition_capacity[<?= $count ?>]">
                </td>
                <td>
                    <input class="form-control" data-type="date" value="<?= $row['INSTALLATION_DATE'] ?>" data-date-format="DD/MM/YYYY" id="d_installation_date_<?= $count ?>" name="d_installation_date[<?= $count ?>]">
                </td>
                <td>
                    <input class="form-control" value="<?= $row['HINT'] ?>" id="hint_<?= $count ?>" name="hint[<?= $count ?>]">
                </td>
                <td>
                    <?php if (HaveAccess(base_url('projects/adapter/delete_partition')) && $count > 0) : ?>
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
                <?php if (count($details) <=0 || ( isset($can_edit)?$can_edit:false) ) : ?>
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