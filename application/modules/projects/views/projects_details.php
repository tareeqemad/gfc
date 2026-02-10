<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 12/01/15
 * Time: 01:20 م
 */
$count = 0;
$change_calculation_cable_url = base_url('projects/projects/change_calculation_cable');
?>

<div class="tbl_container">
    <table class="table" id="projects_detailsTbl" data-container="container">
        <thead>
        <tr>
            <th><a href="javascript:;" style="color: #F0E34E;" onclick="changeLang()">تغير اللغة</a> رقم الصنف </th>
            <th style="width: 100px">  الوحدة  </th>
            <th style="width: 100px">  الحالة  </th>
            <th style="width: 100px">  الكمية </th>
            <th style="width: 100px">  كمية العميل </th>
            <th style="width: 100px">  الكمية المتبقية</th>
            <th style="width: 100px"  > السعر (بطاقة الصنف)</th>
            <th style="width: 100px"  > سعر البيع</th>
            <th>رصيد الرئيسي</th>
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

                <td>
                    <input name="customer_amount[]" value="0"  data-val="true" data-val-required="حقل مطلوب"    class="form-control"  id="customer_amount_id_<?= $count ?>" >
                </td>

                <td data-empty="true"></td>
                <td >
                    <input type="hidden" name="befor_up_sal_price[]" id="bu_price_class_id_<?= $count ?>">
                    <input type="hidden" name="befor_up_buy_price[]" id="bu_buy_price_class_id_<?= $count ?>">
                    <input type="hidden" name="used_price[]" id="used_price_class_id_<?= $count ?>">
                    <input type="hidden" name="used_buy_price[]" id="used_buy_price_class_id_<?= $count ?>">
                    <input name="price[]" readonly data-val="true"  data-val-required="حقل مطلوب"     class="form-control"  id="price_class_id_<?= $count ?>" >
                </td>
                <td>
                    <input name="sal_price[]"  data-val="true" readonly data-val-required="حقل مطلوب"      class="form-control"  id="sal_price_class_id_<?= $count ?>" >
                </td>
                <td data-empty="true">

                </td>
                <td>
                    <input name="notes[]"      class="form-control"  id="notes_<?= $count ?>" >
                </td>
                <td class="price v_balance">
                    <input type='hidden' value='2' name='h_is_calculate[]'  id='h_is_calculate_<?= $count ?>'>
                </td>
            </tr>

        <?php else: $count = -1; ?>
        <?php endif; ?>

        <?php foreach($details as $row) :?>

            <?php if($action =='update_items' || ( $action !='update_items' && $row['ITEM_CASE'] != 3 )): ?>
                <?php $count++; ?>
                <tr <?= ($action =='update_items')? "data-item-case='{$row['ITEM_CASE']}' data-item-case='{$row['ITEM_CASE']}' ":"" ?>  class="<?=  ($action =='update_items')? ($row['ITEM_CASE'] == 2 ?'case_0':'') :"" ?>">
                    <td>
                        <input type="hidden" name="SER[]" value="<?= $row['SER'] ?>">
                        <input type="hidden" name="ITEM_CASE[]" value="<?= $row['ITEM_CASE'] ?>">
                        <input type="text" name="class_id[]" value="<?= $row['CLASS_ID'] ?>"   id="h_class_id_<?= $count ?>"  class="form-control col-sm-3">
                        <input name="class_id_name[]" data-val="true" value='<?= $row['CLASS_ID_NAME'] ?>'  data-arabic="<?= $row['CLASS_ID_NAME'] ?>" data-english="<?= $row['CLASS_ID_NAME_EN'] ?>" readonly data-val-required="حقل مطلوب"   class="form-control col-sm-9" readonly id="class_id_<?= $count ?>"  >
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
                        <?php if ($action == 'update_items' && $row['ITEM_CASE'] == 4 ) { ?>

                            <input name="amount[]" value="<?= $row['AMOUNT'] ?>" data-val="true" data-val-required="حقل مطلوب"
                                   class="form-control" id="amount_class_id_<?= $count ?>">
                        <?php } else if ($action != 'update_items' && $row['ITEM_CASE'] == 4) {?>
                            <input name="amount[]" value="<?= $row['AMOUNT'] ?>" data-val="true" data-val-required="حقل مطلوب"
                                   class="form-control" id="amount_class_id_<?= $count ?>">
                        <?php }else  { ?>
                            <input name="amount[]" value="<?= $row['AMOUNT'] ?>" data-val="true" data-val-required="حقل مطلوب"
                                   class="form-control" id="amount_class_id_<?= $count ?>">
                        <?php } ?>
                    </td>
                    <td>
                        <input name="customer_amount[]" value="<?= $row['CUSTOMER_AMOUNT'] ?>"   data-val="true" data-val-required="حقل مطلوب"    class="form-control"  id="customer_amount_id_<?= $count ?>" >
                    </td>


                    <td  data-empty="true"><?=$row['LEFT_AMOUNT']?></td>
                    <td >
                        <input type="hidden" name="befor_up_sal_price[]"  value="<?= $row['BEFOR_UP_SAL_PRICE']   ?>"  id="bu_price_class_id_<?= $count ?>">
                        <input type="hidden" name="befor_up_buy_price[]"  value="<?= $row['BEFORE_UP_BUY_PRICE']   ?>"  id="bu_buy_price_class_id_<?= $count ?>">
                        <input type="hidden" name="befor_used_buy_price[]"  value="<?= $row['USED_BUY_PRICE']   ?>"  id="bu_used_buy_price_class_id_<?= $count ?>">
                        <input type="hidden" name="used_price[]" value="<?= $row['USED_PRICE']   ?>" id="used_price_class_id_<?= $count ?>">
                        <input name="price[]" value="<?= $row['PRICE'] ?>" data-sale="<?= $row['BEFOR_UP_SAL_PRICE'] ?>"
                               data-buy="<?= $row['BEFORE_UP_BUY_PRICE'] ?>" data-new-buy="<?= $row['BUY_PRICE']?>" data-used-buy="<?= $row['USED_BUY_PRICE'] ?>" readonly
                               data-val="true" data-val-required="حقل مطلوب" class="form-control"
                               id="price_class_id_<?= $count ?>">
                    </td>
                    <td>
                        <input name="sal_price[]" <?= ($action =='update_items')? "data-case='{$row['ITEM_CASE']}'":"" ?>  value="<?= $row['SAL_PRICE']* $row['CURR_VALUE'] ?>" readonly data-val="true"  data-val-required="حقل مطلوب"      class="form-control"  id="sal_price_class_id_<?= $count ?>" >
                    </td>
                    <td  data-empty="true">
                        <?= $row['EMERGENCY_AMOUNT'] ?>
                    </td>
                    <td>
                        <input name="notes[]" value="<?= $row['NOTES'] ?>"      class="form-control"  id="notes_<?= $count ?>" >
                    </td>

                    <td id="td_ser_<?=$row['SER']?>">

                        <?php if ((( isset($can_edit)?$can_edit:false) && ($action == 'index' || $action =='Maintenance') && (($row['PROJECT_CASE'] <=1 || $row['PROJECT_CASE'] == 8)) || ( $row['PROJECT_CASE'] < 7 &&HaveAccess(base_url('projects/projects/ss_edit')))) || ( $row['ITEM_CASE'] == 3 && HaveAccess(base_url('projects/projects/delete_details')))) : ?>
                            <a href="javascript:;" onclick="javascript:delete_details(this,<?= $row['SER'] ?>);"><i class="icon icon-trash delete-action"></i> </a>
                        <?php endif; ?>

                        <?php if (($action =='update_items' && HaveAccess(base_url('projects/projects/update_inUse')) && $row['ITEM_CASE'] == 1)) : ?>
                            <a onclick="javascript:update_in_use(this,<?= $row['SER'] ?>);"  href="javascript:;"><i class="icon icon-exchange"></i></a>
                        <?php endif; ?>
                        <?php if($row['CAL_STATUS'] != 0 && HaveAccess($change_calculation_cable_url)){
                            if($row['IS_CALCULATE']==1){
                                echo "<i class='glyphicon glyphicon-plus'  id='dp_h_is_calculate_{$count}'   title='يحسب' style='color: #0a8800;font-size: large'  onclick=\"javascript:change_calculation_cable({$row['SER']},2,this);\"></i><input type='hidden' value='{$row['IS_CALCULATE']}' name='h_is_calculate[]'  id='h_is_calculate_{$count}'>";
                            }else if ($row['IS_CALCULATE'] == 2) {
                                echo "<i class='glyphicon glyphicon-minus'   id='dp_h_is_calculate_{$count}' title='لا يحسب' style='color: #F40E21FF; font-size: large'  onclick=\"javascript:change_calculation_cable({$row['SER']},1,this);\"></i><input type='hidden' value='{$row['IS_CALCULATE']}' name='h_is_calculate[]'  id='h_is_calculate_{$count}'> ";
                            }
                        }elseif (0){
                            echo "<input type='hidden' value='{$row['IS_CALCULATE']}' name='h_is_calculate[]'  id='h_is_calculate_{$count}'>";
                        }else{
                            echo "<input type='hidden' value='{$row['IS_CALCULATE']}' name='h_is_calculate[]'  id='h_is_calculate_{$count}'>";
                        } ?>
                    </td>
                </tr>
            <?php endif; ?>
        <?php endforeach;?>

        </tbody>
        <tfoot>
        <tr>
            <th rowspan="1" class="align-right" colspan="5">
                <?php if (count($details) <=0 || ( isset($can_edit)?$can_edit:false) || ($action =='update_items' && HaveAccess(base_url('projects/projects/update_items')) )) : ?>
                    <a onclick="javascript:addRow();" onfocus="javascript:addRow();" href="javascript:;"><i class="glyphicon glyphicon-plus"></i>جديد</a>
                <?php endif; ?>
            </th>

            <th colspan="2">تكلفة المواد</th>
            <th id="inv_total"></th>
            <th  colspan="4"></th>

        </tr>
        <tr>
            <th rowspan="7" class="align-right" colspan="5">

            </th>

            <th colspan="2">% رسوم التخطيط و الإشراف</th>
            <th id="design_cost"></th>
            <th  colspan="4"></th>

        </tr>
        <tr>


            <th colspan="2">% رسوم التنفيذ و التركيب</th>
            <th  id="supervision_cost" ></th>
            <th  colspan="4"></th>

        </tr>
        <tr>


            <th   colspan="2">  رسوم أخرى   </th>
            <th id="extra_cost"></th>
            <th  colspan="4"></th>

        </tr>
        <tr>


            <th colspan="2"> رسوم مرجعة </th>
            <th id="return_cost"></th>
            <th  colspan="4"></th>

        </tr>

        <tr>


            <th colspan="2"> اعمال مدنية </th>
            <th id="civil_works"></th>
            <th  colspan="4"></th>

        </tr>
        <tr>


            <th   colspan="2">المساهمة</th>
            <th id="company_value"></th>
            <th  colspan="4"></th>

        </tr>
        <tr>


            <th colspan="2">الإجمالي</th>
            <th id="inv_nettotal"></th>
            <th  colspan="4"></th>

        </tr>
        </tfoot>
    </table>
</div>

<script>
    if (typeof initFunctions == 'function') {
        initFunctions();
    }


</script>