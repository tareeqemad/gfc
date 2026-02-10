<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 09/07/15
 * Time: 01:23 م
 */

$count=0;


?>

<div class="tb_container">
    <table class="table" id="toolsTbl" data-container="container">
        <thead>
        <tr>
            <th style="width: 2%">#</th>
            <th style="width: 8%">رقم أمر العمل</th>
            <th style="width: 8%">رقم الصنف</th>
            <th style="width: 25%">اسم الصنف</th>
            <th style="width: 7%">الوحدة</th>
            <th style="width: 8%">الكمية المستلمة</th>
          <!--  <th style="width: 8%">الكمية المستخدمة</th>-->
            <th style="width: 100px">  الحالة  </th>
            <th></th>
            <th style="width: 50px;"></th>
        </tr>
        </thead>

        <tbody>

        <?php if(count($details) <= 0) {  // ادخال ?>
            <tr>
                <td><i class="glyphicon glyphicon-sort" /></i></td>
                <td>
                    <input type="hidden" name="tl_work_order[]" value="" />
                    <input name="work_order_id_name[]" readonly data-val="true" data-val-required="required" class="form-control"  />
                </td>
                <td>
                    <input type="hidden" name="tl_ser[]" value="0" />

                    <input  type="text" class="form-control" name="class_id[]"  id="h_txt_class_id_<?= $count ?>" >
                </td>
                <td>
                    <input name="class_name[]" readonly data-val="true" data-val-required="required" class="form-control"  id="txt_class_id_<?=$count?>" />
                </td>
                <td>

                    <input class="form-control"  type="hidden" id="unit_txt_class_id_<?=$count?>" name="d_class_unit[<?=$count?>]">
                    <input class="form-control"  readonly type="text" id="unit_name_txt_class_id_<?=$count?>"  >
                </td>
                <td>
                    <input name="class_count[]" class="form-control" id="txt_class_count_<?=$count?>" />
                </td>

                <td>
                    <select name="class_type[]" class="form-control valid">
                        <option  value="1">جديد</option>
                        <option value="2">مستعمل</option>
                    </select>
                </td>
               <!-- <td>
                    <input name="class_count_used[]" class="form-control" id="txt_class_count_used_<?/*=$count*/?>" />
                </td>-->

                <td></td>
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
                        <input type="hidden" name="tl_work_order[]" value="<?=$row['WORK_ORDER_ID']?>" />
                        <input name="work_order_id_name[]" value="<?=$row['WORK_ORDER_ID']?>" readonly data-val="true" data-val-required="required" class="form-control"  />
                    </td>

                    <td>
                        <input type="hidden" name="tl_ser[]" value="<?=$row['SER']?>" />
                        <input type="hidden" name="tl_work_order[]" value="<?=$row['SER']?>" />
                        <input  type="text" class="form-control" name="class_id[]" value="<?=$row['CLASS_ID']?>" id="h_txt_class_id_<?= $count ?>" >
                    </td>

                    <td>
                        <input name="class_name[]" readonly value="<?=$row['CLASS_ID_NAME']?>" class="form-control"  id="txt_class_id_<?=$count?>" />
                    </td>

                    <td>
                        <input class="form-control" value="<?=$row['CLASS_UNIT']?>"  type="hidden" id="unit_txt_class_id_<?=$count?>" name="d_class_unit[<?=$count?>]">
                        <input class="form-control" value="<?=$row['CLASS_UNIT_NAME']?>" readonly type="text" id="unit_name_txt_class_id_<?=$count?>"  >

                    </td>

                    <td>
                        <input name="class_count[]" class="form-control" id="txt_class_count_<?=$count?>" value="<?=$row['CLASS_COUNT']?>" />
                    </td>
                  <!--  <td>
                        <input name="class_count_used[]" value="<?/*=$row['CLASS_COUNT_USED']*/?>" class="form-control" id="txt_class_count_used_<?/*=$count*/?>" />
                    </td>-->
                    <td>

                        <select name="class_type[]" class="form-control">
                            <option <?= $row['CLASS_TYPE'] == 1?'selected' : '' ?> value="1" >جديد</option>
                            <option <?= $row['CLASS_TYPE'] == 2?'selected' : '' ?> value="2">مستعمل</option>
                        </select>


                    </td>

                    <td></td>
                    <td data-action="delete">
                        <?php if ( $can_edit && HaveAccess(base_url('technical/WorkOrderAssignment/delete_tools'))  ) : ?>
                            <a href="javascript:;" onclick="javascript:delete_details(this,<?= $row['SER'] ?>,'<?= base_url('technical/WorkOrderAssignment/delete_tools') ?>');"><i class="icon icon-trash delete-action"></i> </a>
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
            <th></th>
            <th></th>
        </tr>
        </tfoot>
    </table>
</div>
