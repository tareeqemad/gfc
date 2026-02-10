<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 09/07/15
 * Time: 11:22 ص
 */

$count=0;


?>

<div class="tb_container">
    <table class="table" id="carsTbl" data-container="container">
        <thead>
        <tr>
            <th style="width: 20px">#</th>
            <th style="width: 120px">نوع الآلية</th>
            <th style="width: 80px">العدد</th>
            <th  > وصف الاحتياج </th>
            <th style="width: 50px"></th>
        </tr>
        </thead>

        <tbody>

        <?php if(count($details) <= 0) {  // ادخال ?>
            <tr>
                <td><i class="glyphicon glyphicon-sort" /></i></td>
                <td>
                    <input type="hidden" name="c_ser[]" value="0" />
                    <select name="car_id[]" class="form-control" id="txt_car_id_<?=$count?>" />
                    <option></option>
                    <?php foreach($cars as $crow) :?>
                        <option value="<?= $crow['CON_NO'] ?>"><?= $crow['CON_NAME'] ?></option>
                    <?php endforeach; ?>
                    </select>
                </td>
                <td>
                    <input name="car_count[]" class="form-control" id="txt_car_count_<?=$count?>" />
                </td>
                <td>
                    <input name="need_description[]" class="form-control" id="txt_need_description_<?=$count?>" />
                </td>
                <td data-action="delete"></td>
            </tr>

        <?php
        }else if(count($details) > 0) { // تعديل
            $count = -1;
            foreach($details as $row) {
                ?>
                <tr>
                    <td><?=++$count+1?></td>
                    <td>
                        <input type="hidden" name="c_ser[]" value="<?=$row['SER']?>" />
                        <select name="car_id[]" class="form-control" id="txt_car_id_<?=$count?>" data-val="<?=$row['CAR_ID']?>" />
                        <option></option>
                        <?php foreach($cars as $crow) :?>
                            <option  <?= $row['CAR_ID'] == $crow['CON_NO'] ?'selected':'' ?> value="<?= $crow['CON_NO'] ?>"><?= $crow['CON_NAME'] ?></option>
                        <?php endforeach; ?>
                        </select>
                    </td>
                    <td>
                        <input name="car_count[]" class="form-control" id="txt_car_count_<?=$count?>" value="<?=$row['CAR_COUNT']?>" />
                    </td>
                    <td>
                        <input name="need_description[]" class="form-control" id="txt_need_description_<?=$count?>" value="<?=$row['NEED_DESCRIPTION']?>" />
                    </td>
                    <td data-action="delete">
                        <?php if ( $can_edit && HaveAccess(base_url('technical/WorkOrder/delete_cars')) ) : ?>
                            <a href="javascript:;" onclick="javascript:delete_details(this,<?= $row['SER'] ?>,'<?= base_url('technical/WorkOrder/delete_cars') ?>');"><i class="icon icon-trash delete-action"></i> </a>
                        <?php endif; ?>

                    </td>
                </tr>
            <?php
            }
        }
        ?>

        </tbody>
        <tfoot>
        <tr>
            <th></th>
            <th>
                <?php if (count($details) <=0 || ( (isset($can_edit)?$can_edit:false)  )) { ?>
                    <a onclick="javascript:add_row(this,'input',false);" href="javascript:;"><i class="glyphicon glyphicon-plus"></i>جديد</a>
                <?php } ?>
            </th>
            <th></th>
            <th></th>
        </tr>
        </tfoot>
    </table>
</div>
