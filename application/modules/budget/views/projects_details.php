<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 12/01/15
 * Time: 01:20 م
 */
$count = 0;
?>

<div class="tbl_container">
    <table class="table" id="projects_detailsTbl" data-container="container">
        <thead>
        <tr>
            <th> رقم الصنف </th>
            <th style="width: 100px">  الوحدة  </th>
            <th style="width: 100px">  الحالة  </th>
            <th style="width: 100px">  الكمية </th>

            <th style="width: 100px"  > سعر </th>

            <th style="width: 200px" > الملاحظات </th>
            <th ></th>
        </tr>
        </thead>

        <tbody>

        <?php if(count($details) <= 0) : ?>
            <tr>
                <td>
                    <input type="hidden" name="SER[]" value="0">
                    <input type="text" name="class_id[]"   id="h_class_id_<?= $count ?>"  class="form-control col-sm-3">
                    <input name="class_id_name[]" data-val="true" readonly data-val-required="حقل مطلوب"   class="form-control col-sm-9" readonly id="class_id_<?= $count ?>"  >
                </td>
                <td>
                    <input name="class_unit_name[]" readonly data-val="true"  data-val-required="حقل مطلوب"      class="form-control"  id="unit_class_id_<?= $count ?>" >
                    <input name="class_unit[]"  type="hidden"  id="h_unit_class_id_<?= $count ?>" >

                </td>

                <td>

                    <select name="class_type[]" class="form-control">
                        <option value="1" >جديد</option>
                        <option value="2">مستعمل</option>
                    </select>

                </td>

                <td>
                    <input name="amount[]"  data-val="true" data-val-required="حقل مطلوب"    class="form-control"  id="amount_class_id_<?= $count ?>" >
                </td>

                <td >

                    <input name="price[]" readonly data-val="true"  data-val-required="حقل مطلوب"     class="form-control"  id="price_class_id_<?= $count ?>" >
                </td>


                <td>
                    <input name="notes[]"      class="form-control"  id="notes_<?= $count ?>" >
                </td>
                <td class="price v_balance"></td>
            </tr>

        <?php else: $count = -1; ?>
        <?php endif; ?>

        <?php foreach($details as $row) :?>


                <?php $count++; ?>
                <tr>
                    <td>
                        <input type="hidden" name="SER[]" value="<?= $row['SER'] ?>">

                        <input type="text" name="class_id[]" value="<?= $row['CLASS_ID'] ?>"   id="h_class_id_<?= $count ?>"  class="form-control col-sm-3">
                        <input name="class_id_name[]" data-val="true" value='<?= $row['CLASS_ID_NAME'] ?>'  readonly data-val-required="حقل مطلوب"   class="form-control col-sm-9" readonly id="class_id_<?= $count ?>"  >
                    </td>
                    <td>
                        <input name="class_unit_name[]" value="<?= $row['UNIT_NAME'] ?>" readonly data-val="true"  data-val-required="حقل مطلوب"      class="form-control"  id="unit_class_id_<?= $count ?>" >
                        <input name="class_unit[]" value="<?= $row['CLASS_UNIT'] ?>"  type="hidden"  id="h_unit_class_id_<?= $count ?>" >

                    </td>

                    <td>

                        <select name="class_type[]" class="form-control">
                            <option <?= $row['CLASS_TYPE'] == 1?'selected' : '' ?> value="1" >جديد</option>
                            <option <?= $row['CLASS_TYPE'] == 2?'selected' : '' ?> value="2">مستعمل</option>
                        </select>


                    </td>
                    <td>
                        <input name="amount[]" value="<?= $row['AMOUNT'] ?>" data-val="true" data-val-required="حقل مطلوب"  class="form-control"  id="amount_class_id_<?= $count ?>" >
                    </td>

                    <td >

                         <input name="price[]" value="<?= $row['PRICE']   ?>" data-sale="<?= $row['PRICE']  ?>" readonly data-val="true"  data-val-required="حقل مطلوب"     class="form-control"  id="price_class_id_<?= $count ?>" >
                    </td>


                    <td>
                        <input name="notes[]" value="<?= $row['NOTES'] ?>"      class="form-control"  id="notes_<?= $count ?>" >
                    </td>

                    <td>

                        <?php if ((( isset($can_edit)?$can_edit:false) && ($action == 'index' || $action =='Maintenance') && (($row['PROJECT_CASE'] <=1 || $row['PROJECT_CASE'] == 8)) || ( $row['PROJECT_CASE'] < 7 &&HaveAccess(base_url('projects/projects/ss_edit')))) || ( $row['ITEM_CASE'] == 3 && HaveAccess(base_url('projects/projects/delete_details')))) : ?>
                            <a href="javascript:;" onclick="javascript:delete_details(this,<?= $row['SER'] ?>);"><i class="icon icon-trash delete-action"></i> </a>
                        <?php endif; ?>

                        <?php if (($action =='update_items' && HaveAccess(base_url('projects/projects/update_inUse')) && $row['ITEM_CASE'] == 1)) : ?>
                            <a onclick="javascript:update_in_use(this,<?= $row['SER'] ?>);"  href="javascript:;"><i class="icon icon-exchange"></i></a>
                        <?php endif; ?>
                    </td>
                </tr>

        <?php endforeach;?>

        </tbody>
        <tfoot>
        <tr>
            <th rowspan="1" class="align-right" colspan="4">
                <?php if (count($details) <=0 || ( isset($can_edit)?$can_edit:false) || ($action =='update_items' && HaveAccess(base_url('projects/projects/update_items')) )) : ?>
                    <a onclick="javascript:addRow();" onfocus="javascript:addRow();" href="javascript:;"><i class="glyphicon glyphicon-plus"></i>جديد</a>
                <?php endif; ?>
            </th>

            <th colspan="2">تكلفة المواد</th>
            <th id="inv_total"></th>
            <th  colspan="3"></th>

        </tr>
        <tr>
            <th rowspan="6" class="align-right" colspan="4">

            </th>

            <th colspan="2">% رسوم التخطيط و الإشراف</th>
            <th id="design_cost"></th>
            <th  colspan="3"></th>

        </tr>
        <tr>


            <th colspan="2">% رسوم التنفيذ و التركيب</th>
            <th  id="supervision_cost" ></th>
            <th  colspan="3"></th>

        </tr>


        <tr>


            <th colspan="2">الإجمالي</th>
            <th id="inv_nettotal"></th>
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