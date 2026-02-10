<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 14/10/15
 * Time: 01:00 م
 */
$MODULE_NAME= 'donations';
$TB_NAME= 'donation';
$DET_TB_NAME='public_get_details';
$isCreate =isset($donation_data) && count($donation_data)  > 0 ?false:true;
$HaveRs = (!$isCreate)? true:false;
$rs=$isCreate ?array() : $donation_data[0];
$post_url=base_url("$MODULE_NAME/$TB_NAME/".($action == 'index'?'create':$action));
$back_url=base_url("$MODULE_NAME/$TB_NAME"); //$action
$class_output_url=base_url("stores/stores_class_output/get");
$select_accounts_url =base_url('financial/accounts/public_select_accounts');
$select_items_url=base_url("stores/classes/public_index");
$get_class_url =base_url('stores/classes/public_get_id');
$get_url= base_url("$MODULE_NAME/$TB_NAME/get");
$adopt_url= base_url("$MODULE_NAME/$TB_NAME/adopt");
$delete_url_details= base_url("$MODULE_NAME/$TB_NAME/delete_details");
$change_items_url=base_url("$MODULE_NAME/$TB_NAME/change_items");
$fid = (count($rs)>0)?$rs['DONATION_FILE_ID']:0;
$count=0;

?>
<?php
if ($donation_type_install==1)
{
    ?>

    <div class="tb_container">
    <table class="table" id="details_tb" data-container="container" style="width: 3000px;max-width: 3000px;">
    <thead>
    <tr>

        <th style="width: 3px">رقم الصنف حسب المنحة</th>
        <th style="width: 100px">رمز القسم</th>
        <th style="width: 150px" >رقم الصنف المخزني</th>
        <th style="width: 400px">اسم الصنف</th>
        <th style="width: 50px" >الوحدة</th>
        <th style="width: 90px">حالة الصنف المطلوب توريده</th>
        <th style="width: 110px">فعالية الصنف</th>
        <th style="width: 110px">الكمية المطلوبة</th>
        <?php if(!empty($details)){?>
        <th style="width: 3px">الكمية الموردة من السنوات السابقة</th>
        <?php }?>
        <th style="width: 110px" >سعر الصنف</th>


        <th style="width: 110px"> خدمات توصيل الصنف للمخازن  </th>
        <th style="width: 100px">  اجمالى تكلفة الصنف</th>

        <th style="width: 100px"> اجمالى تكلفة الصنف شامل الخصم </th>
        <th style="width: 100px"> نسبة ضرائب الاستيراد (TAX) </th>
        <th style="width: 100px"> قيمة ضرائب الاستيراد (TAX) </th>

        <th style="width: 100px"> اجمالى تكلفة الصنف شامل الخصم وضرائب الاستيراد </th>
        <!--  <th > يشمل الضريبة؟ </th>-->
        <th style="width: 100px"> اجمالى تكلفة الصنف شامل الخصم وجميع الضرائب </th>
        <th style="width: 100px"> تسويات</th>
        <th style="width: 100px">  التكلفة الاجمالية النهائية للصنف</th>
        <th style="width: 100px"> سعر الصنف شامل جميع المصاريف و الضرائب </th>
        <th style="width: 100px"> سعر الصنف النهائي غير شامل ض.م..ق </th>
        <th style="width: 200px">بيان حالة الصنف</th>
        <th style="width: 200px"> ملاحظات </th>
        <?php if ( HaveAccess($delete_url_details) && (!$isCreate and ( (count($rs)>0)? ($rs['DONATION_FILE_CASE']==1 || $rs['DONATION_FILE_CASE']==4 ) : ''  )  )) : ?>
            <th style="width: 10px">حذف</th>
        <?php endif; ?>
    </tr>
    </thead>

    <tbody>

    <?php if(count($details) <= 0) {  // ادخال ?>

        <tr>
            <td>
                <input type="hidden" name="ser[]" value="0"  />
                <input name="donation_class_id[]" class="form-control" id="donation_class_id_<?=$count?>" data-val="false" data-val-required="required" />
            </td>
            <td>

                <select name="donation_dept_ser[]" id="dp_donation_dept_ser_<?=$count?>" data-curr="false" class="form-control">
                    <option data-expenses_trans_percentage="0" data-discount_percentage="0"  data-vat_percent="0" data-vat_total="0" data-expenses_adjustments="0" data-checked="3" value="">-</option>
                    <?php foreach ($departments_all as $row1) : ?>
                        <option data-expenses_trans_percentage="<?=$row1['EXPENSES_TRANS_PERCENTAGE']?>" data-discount_percentage="<?=$row1['DISCOUNT_PERCENTAGE']?>"  data-vat_percent="<?=$row1['VAT_PERCENT']?>" data-vat_total="<?=$row1['VAT_TOTAL']?>" data-expenses_adjustments="<?=$row1['EXPENSES_ADJUSTMENTS']?>" data-checked="3" value="<?= $row1['SER'] ?>" ><?= $row1['DONATION_DEPT_CODE'] ?></option>
                    <?php endforeach; ?>
                </select>

            </td>
            <td>

                <input class="form-control" name="class[]" id="i_txt_class_id_<?=$count?>" data-val="false" data-val-required="required" />
                <input  type="hidden" name="class_id[]"  id="h_txt_class_id_<?= $count ?>" data-val="false" data-val-required="required" >
            </td>
            <td>
                <input name="class_name[]" readonly data-val="false" data-val-required="required" class="form-control"  id="txt_class_id_<?=$count?>" />
                <input name="en_class_id[]" readonly data-val="false" data-val-required="required" class="form-control"  id="en_txt_class_id_<?=$count?>" />
            </td>
            <td>
                <input name="class_unit_id[]" readonly class="form-control" id="unit_name_txt_class_id_<?=$count?>" />
                <input name="class_unit[]" type="hidden" class="form-control" id="unit_txt_class_id_<?=$count?>" />
            </td>

            <TD>
                <select name="class_type[]" id="dp_class_type_<?=$count?>"  class="form-control" data-val="false" data-val-required="required">
                    <?php foreach ($items_type as $row) : ?>
                        <option value="<?= $row['CON_NO'] ?>"><?php echo $row['CON_NAME'] ?></option>
                    <?php endforeach; ?>
                </select>
            </TD>
            <td>
                فعال
                <input name="class_case[]" type="hidden"  value="1" class="form-control" id="class_case_txt_class_id_<?=$count?>" data-val="false" data-val-required="required" />

            </td>
            <td>
                <input name="amount[]" class="form-control" id="txt_amount_<?=$count?>" data-val="false" data-val-required="required" />
            </td>
            <?php if(!empty($details)){?>
            <td>   <input name="BALANCE[]" disabled  class="form-control" id="BALANCE_<?=$count?>" />
            <?php }?>


            <td>
                <input name="price1[]" class="form-control" id="price_id_<?=$count?>" />
            </td>


            <td>
                <input name="d_expenses[]" class="form-control" id="txt_d_expenses_<?=$count?>" />
            </td>
            <td>
                <input readonly name="total_with_expenses[]" class="form-control" id="txt_total_with_expenses_<?=$count?>" />
            </td>
            <td>
                <input readonly name="price_without_discount[]" class="form-control" id="txt_price_without_discount_<?=$count?>" />
            </td>
            <td>
                <input  name="tax_persent[]" class="form-control" id="txt_tax_persent_<?=$count?>" />
            </td>
            <td>
                <input  name="tax_value[]" class="form-control" id="txt_tax_value_<?=$count?>" />
            </td>
            <td>
                <input readonly name="price_with_trans_expenses[]" class="form-control" id="txt_price_with_trans_expenses_<?=$count?>" />
            </td>
            <td hidden="hidden">

                <select name="vat_type[]" class="form-control" id="txt_vat_type_<?=$count?>" hidden="hidden">

                    <option value="0"/> -</option>
                    <option value="1">نعم</option>
                    <option value="2">لا </option>
                </select>
            </td>
            <td>
                <input readonly name="price_without_vat[]" class="form-control" id="txt_price_without_vat_<?=$count?>" />
            </td>


            <td>
                <input  readonly name="d_other_expenses[]" class="form-control" id="txt_d_other_expenses_<?=$count?>" />
            </td>
            <td>
                <input readonly name="last_price[]"  class="form-control" id="txt_last_price_<?=$count?>" />
            </td>

            <td>
                <input readonly name="last_total[]" class="form-control" id="txt_last_total_<?=$count?>" />
            </td>
            <td>
                <input readonly name="class_price_no_vat[]" class="form-control" id="txt_class_price_no_vat_<?=$count?>" />
            </td>
            <td>
                <textarea rows="1" name="class_case_hints[]" class="form-control" id="txt_class_case_hints_<?=$count?>" ></textarea>

            </td>
            <td>
                <textarea rows="1" name="hints[]" class="form-control" id="txt_hints_<?=$count?>" ></textarea>

            </td>
            <td></td>
        </tr>
    <?php
    }else if(count($details) > 0) { // تعديل
        $count = -1;
        foreach($details as $row) {
            ++$count+1
            ?>
            <tr>

            <td>

                <input type="hidden" name="ser[]" value="<?=$row['SER']?>" />
                <?php if ($row['CHECKED']==1){?>
                    <input name="donation_class_id[]" value="<?=$row['DONATION_CLASS_ID']?>" class="form-control" id="donation_class_id_<?=$count?>" readonly/>
                <?php }?>

                <?php if ($row['CHECKED']==2){?>
                    <input name="donation_class_id[]" value="<?=$row['DONATION_CLASS_ID']?>" class="form-control" id="donation_class_id_<?=$count?>" />
                <?php }?>

                <?php if ($row['CHECKED']==3){?>
                    <input name="donation_class_id[]" value="<?=$row['DONATION_CLASS_ID']?>" class="form-control" id="donation_class_id_<?=$count?>" />
                <?php }?>

            </td>
            <td>



                <?php if ($row['CHECKED']==1){?>
                <?=$row['DONATION_DEPT_SER_NAME']?>
                <select name="donation_dept_ser[]" id="dp_donation_dept_ser_<?=$count?>" data-curr="false" class="form-control"  style="display: none;" >
                    <option data-expenses_trans_percentage="0" data-discount_percentage="0"  data-vat_percent="0" data-vat_total="0" data-expenses_adjustments="0" data-checked="3" value="">-</option>
                    <?php foreach ($departments_all as $row1) : ?>
                        <option data-expenses_trans_percentage="<?=$row1['EXPENSES_TRANS_PERCENTAGE']?>" data-discount_percentage="<?=$row1['DISCOUNT_PERCENTAGE']?>"  data-vat_percent="<?=$row1['VAT_PERCENT']?>" data-vat_total="<?=$row1['VAT_TOTAL']?>" data-expenses_adjustments="<?=$row1['EXPENSES_ADJUSTMENTS']?>" data-checked="<?=$row['CHECKED']?>" value="<?= $row1['SER'] ?>"<?PHP  if ($row['DONATION_DEPT_SER'] == $row1['SER']) echo " selected"; ?> ><?= $row1['DONATION_DEPT_CODE'] ?></option>
                    <?php endforeach; ?>
                </select>
        <span class="field-validation-valid" data-valmsg-for="donation_dept_ser[]" data-valmsg-replace="true">
        <?php }?>

            <?php if ($row['CHECKED']==2){?>
            <select name="donation_dept_ser[]" id="dp_donation_dept_ser_<?=$count?>" data-curr="false" class="form-control">
                <option data-expenses_trans_percentage="0" data-discount_percentage="0"  data-vat_percent="0" data-vat_total="0" data-expenses_adjustments="0" data-checked="3" value="">-</option>
                <?php foreach ($departments_all as $row1) : ?>
                    <option data-expenses_trans_percentage="<?=$row1['EXPENSES_TRANS_PERCENTAGE']?>" data-discount_percentage="<?=$row1['DISCOUNT_PERCENTAGE']?>"  data-vat_percent="<?=$row1['VAT_PERCENT']?>" data-vat_total="<?=$row1['VAT_TOTAL']?>" data-expenses_adjustments="<?=$row1['EXPENSES_ADJUSTMENTS']?>" data-checked="<?=$row['CHECKED']?>" value="<?= $row1['SER'] ?>"<?PHP  if ($row['DONATION_DEPT_SER'] == $row1['SER']) echo " selected"; ?> ><?= $row1['DONATION_DEPT_CODE'] ?></option>
                <?php endforeach; ?>
            </select>
        <span class="field-validation-valid" data-valmsg-for="donation_dept_ser[]" data-valmsg-replace="true">
        <?php }?>

            <?php if ($row['CHECKED']==3){?>
            <select name="donation_dept_ser[]" id="dp_donation_dept_ser_<?=$count?>" data-curr="false" class="form-control">
                <option data-expenses_trans_percentage="0" data-discount_percentage="0"  data-vat_percent="0" data-vat_total="0" data-expenses_adjustments="0" data-checked="3" value="">-</option>
                <?php foreach ($departments_all as $row1) : ?>
                    <option data-expenses_trans_percentage="<?=$row1['EXPENSES_TRANS_PERCENTAGE']?>" data-discount_percentage="<?=$row1['DISCOUNT_PERCENTAGE']?>"  data-vat_percent="<?=$row1['VAT_PERCENT']?>" data-vat_total="<?=$row1['VAT_TOTAL']?>" data-expenses_adjustments="<?=$row1['EXPENSES_ADJUSTMENTS']?>" data-checked="<?=$row['CHECKED']?>" value="<?= $row1['SER'] ?>"<?PHP  if ($row['DONATION_DEPT_SER'] == $row1['SER']) echo " selected"; ?> ><?= $row1['DONATION_DEPT_CODE'] ?></option>
                <?php endforeach; ?>
            </select>
        <span class="field-validation-valid" data-valmsg-for="donation_dept_ser[]" data-valmsg-replace="true">
        <?php }?>


            </td>

            <td>

                <?php if ($row['CHECKED']==1){?>
                    <?=$row['CALSS_ID'];?>
                    <input  type="hidden" name="class_id[]" value="<?=$row['CALSS_ID']?>" id="h_txt_class_id_<?= $count ?>" data-val="true" data-val-required="required" >
                <?php }?>
                <?php if ($row['CHECKED']==2){?>
                    <?=$row['CALSS_ID'];?>
                    <input  type="hidden" name="class_id[]" value="<?=$row['CALSS_ID']?>" id="h_txt_class_id_<?= $count ?>" data-val="true" data-val-required="required" >
                <?php }?>
                <?php if ($row['CHECKED']==3){?>
                    <input name="class[]" value="<?=$row['CALSS_ID']?>" class="form-control"  id="i_txt_class_id_<?=$count?>" data-val="true" data-val-required="required"/>
                    <input  type="hidden" name="class_id[]" value="<?=$row['CALSS_ID']?>" id="h_txt_class_id_<?= $count ?>" data-val="true" data-val-required="required" >
                <?php }?>


            </td>

            <td>

                <?php if ($row['CHECKED']==1){?>
                    <?=$row['CALSS_ID_NAME']?>
                    <input name="class_name[]" readonly value="<?=$row['CALSS_ID_NAME']?>" class="form-control"  id="txt_class_id_<?=$count?>" data-val="true" data-val-required="required" type="hidden"/>
                    <input name="en_class_id[]" readonly value="<?=$row['CALSS_ID_NAME_EN']?>" class="form-control"  id="en_txt_class_id_<?=$count?>" data-val="true" data-val-required="required" type="hidden"/>
                <?php }?>
                <?php if ($row['CHECKED']==2){?>
                    <?=$row['CALSS_ID_NAME']?>
                    <input name="class_name[]" readonly value="<?=$row['CALSS_ID_NAME']?>" class="form-control"  id="txt_class_id_<?=$count?>" data-val="true" data-val-required="required" type="hidden"/>
                    <input name="en_class_id[]" readonly value="<?=$row['CALSS_ID_NAME_EN']?>" class="form-control"  id="en_txt_class_id_<?=$count?>" data-val="true" data-val-required="required" type="hidden"/>
                <?php }?>
                <?php if ($row['CHECKED']==3){?>
                    <input name="class_name[]" readonly value="<?=$row['CALSS_ID_NAME']?>" class="form-control"  id="txt_class_id_<?=$count?>" data-val="true" data-val-required="required"/>
                    <input name="en_class_id[]" readonly value="<?=$row['CALSS_ID_NAME_EN']?>" class="form-control"  id="en_txt_class_id_<?=$count?>" data-val="true" data-val-required="required"/>

                    <!--<input name="en_class_id[]" value="<?=$row['CALSS_ID_NAME']?>" readonly data-val="false" data-val-required="required" class="form-control"  id="en_txt_class_id_<?=$count?>" />-->

                <?php }?>

            </td>
            <td>



                <input name="class_unit_id[]" readonly value="<?=$row['CLASS_UNIT_NAME']?>" class="form-control" id="unit_name_txt_class_id_<?=$count?>" />
                <input name="class_unit[]" type="hidden" value="<?=$row['CLASS_UNIT']?>" class="form-control" id="unit_txt_class_id_<?=$count?>" />
            </td>
            <TD>


                <?php if ($row['CHECKED']==1){?>
                    <?=$row['CLASS_TYPE_NAME']?>
                    <select name="class_type[]" id="dp_class_type_<?=$count?>"  class="form-control" data-val="false" data-val-required="required" style="display: none;">

                        <?php foreach ($items_type as $rows2) : ?>
                            <option value="<?= $rows2['CON_NO'] ?>"
                                <?php if ($rows2['CON_NO'] == $row['CLASS_TYPE']) {
                                    echo " selected";
                                }
                                ?> ><?php echo $rows2['CON_NAME'] ?></option>
                        <?php endforeach; ?>
                    </select>
                <?php }?>

                <?php if ($row['CHECKED']==2){?>
                    <?=$row['CLASS_TYPE_NAME']?>
                    <select name="class_type[]" id="dp_class_type_<?=$count?>"  class="form-control" data-val="false" data-val-required="required" style="display: none;">

                        <?php foreach ($items_type as $rows2) : ?>
                            <option value="<?= $rows2['CON_NO'] ?>"
                                <?php if ($rows2['CON_NO'] == $row['CLASS_TYPE']) {
                                    echo " selected";
                                }
                                ?> ><?php echo $rows2['CON_NAME'] ?></option>
                        <?php endforeach; ?>
                    </select>
                <?php }?>

                <?php if ($row['CHECKED']==3){?>
                    <select name="class_type[]" id="dp_class_type_<?=$count?>"  class="form-control" data-val="false" data-val-required="required">

                        <?php foreach ($items_type as $rows2) : ?>
                            <option value="<?= $rows2['CON_NO'] ?>"
                                <?php if ($rows2['CON_NO'] == $row['CLASS_TYPE']) {
                                    echo " selected";
                                }
                                ?> ><?php echo $rows2['CON_NAME'] ?></option>
                        <?php endforeach; ?>
                    </select>
                <?php }?>


            </TD>
            <td><?=$row['CLASS_CASE_NAME'];?>
                <input name="class_case[]" type="hidden"  value="<?=$row['CLASS_CASE']?>" class="form-control" id="class_case_txt_class_id_<?=$count?>" data-val="true" data-val-required="required" />

            </td>


            <td>
                <?php if ($row['CHECKED']==1){?>
                    <?=$row['AMOUNT']?>
                    <input name="amount[]" value="<?=$row['AMOUNT']?>" class="form-control" id="txt_amount_<?=$count?>" data-val="true" data-val-required="required" type="hidden"/>
                <?php }?>
                <?php if ($row['CHECKED']==2){?>
                    <input name="amount[]" value="<?=$row['AMOUNT']?>" class="form-control" id="txt_amount_<?=$count?>" data-val="true" data-val-required="required"/>
                <?php }?>
                <?php if ($row['CHECKED']==3){?>
                    <input name="amount[]" value="<?=$row['AMOUNT']?>" class="form-control" id="txt_amount_<?=$count?>" data-val="true" data-val-required="required"/>
                <?php }?>

            </td>

         <td>   <input name="BALANCE[]" disabled value="<?=$row['BALANCE']?>" class="form-control" id="BALANCE_<?=$count?>" />
         </td>
            <td>
                <?php if ($row['CHECKED']==1){?>
                    <?=$row['PRICE']?>
                    <input name="price1[]" value="<?=$row['PRICE']?>" class="form-control" id="price_id_<?=$count?>" type="hidden"/>
                <?php }?>
                <?php if ($row['CHECKED']==2){?>
                    <input name="price1[]" value="<?=$row['PRICE']?>" class="form-control" id="price_id_<?=$count?>" />
                <?php }?>
                <?php if ($row['CHECKED']==3){?>
                    <input name="price1[]" value="<?=$row['PRICE']?>" class="form-control" id="price_id_<?=$count?>" />
                <?php }?>

            </td>


            <td>
                <?php if ($row['CHECKED']==1){?>
                    <?=$row['EXPENSES']?>
                    <input name="d_expenses[]" value="<?=$row['EXPENSES']?>" class="form-control" id="txt_d_expenses_<?=$count?>" type="hidden" />

                <?php }?>
                <?php if ($row['CHECKED']==2){?>
                    <input name="d_expenses[]" value="<?=$row['EXPENSES']?>" class="form-control" id="txt_d_expenses_<?=$count?>" />

                <?php }?>
                <?php if ($row['CHECKED']==3){?>
                    <input name="d_expenses[]" value="<?=$row['EXPENSES']?>" class="form-control" id="txt_d_expenses_<?=$count?>" />

                <?php }?>

            </td>
            <td>
                <input readonly name="total_with_expenses[]" value="<?=$row['ROUND_TOTAL_WITH_EXPENSES']?>" class="form-control" id="txt_total_with_expenses_<?=$count?>" />
            </td>
            <td>
                <input readonly name="price_without_discount[]" value="<?=$row['PRICE_WITHOUT_DISCOUNT']?>"  class="form-control" id="txt_price_without_discount_<?=$count?>" />
            </td>
            <td>
                <?php if ($row['CHECKED']==1){?>
                    <?=$row['TAX_PERSENT']?>
                    <input  name="tax_persent[]" value="<?=$row['TAX_PERSENT']?>" class="form-control" id="txt_tax_persent_<?=$count?>" type="hidden" />
                <?php }?>
                <?php if ($row['CHECKED']==2){?>
                    <input  name="tax_persent[]" value="<?=$row['TAX_PERSENT']?>" class="form-control" id="txt_tax_persent_<?=$count?>"  />

                <?php }?>
                <?php if ($row['CHECKED']==3){?>
                    <input  name="tax_persent[]" value="<?=$row['TAX_PERSENT']?>" class="form-control" id="txt_tax_persent_<?=$count?>" />

                <?php }?>

            </td>
            <td>
                <?php if ($row['CHECKED']==1){?>
                    <?=$row['TAX_VALUE']?>
                    <input  name="tax_value[]" value="<?=$row['TAX_VALUE']?>" class="form-control" id="txt_tax_value_<?=$count?>" type="hidden"/>
                <?php }?>
                <?php if ($row['CHECKED']==2){?>
                    <input  name="tax_value[]" value="<?=$row['TAX_VALUE']?>" class="form-control" id="txt_tax_value_<?=$count?>" />
                <?php }?>
                <?php if ($row['CHECKED']==3){?>
                    <input  name="tax_value[]" value="<?=$row['TAX_VALUE']?>" class="form-control" id="txt_tax_value_<?=$count?>" />
                <?php }?>


            </td>
            <td>
                <input readonly name="price_with_trans_expenses[]" value="<?=$row['PRICE_WITH_TRANS_EXPENSES']?>" class="form-control" id="txt_price_with_trans_expenses_<?=$count?>" />
            </td>
            <td hidden="hidden">

                <select name="vat_type[]" class="form-control" id="txt_vat_type_<?=$count?>" hidden="hidden">

                    <option value="0" <?php if($row['VAT_TYPE']==0) echo "selected"; ?> >-</option>
                    <option value="1" <?php if($row['VAT_TYPE']==1) echo "selected"; ?> >نعم</option>
                    <option value="2"  <?php if($row['VAT_TYPE']==2) echo "selected"; ?> >لا </option>
                </select>
            </td>
            <td>
                <input readonly name="price_without_vat[]" value="<?=$row['PRICE_WITHOUT_VAT']?>" class="form-control" id="txt_price_without_vat_<?=$count?>" />
            </td>


            <td>
                <input readonly name="d_other_expenses[]" value="<?=$row['OTHER_EXPENSES']?>"  class="form-control" id="txt_d_other_expenses_<?=$count?>" />
            </td>
            <td>
                <input readonly name="last_price[]" value="<?=$row['LAST_PRICE']?>" class="form-control" id="txt_last_price_<?=$count?>" />
            </td>
            <td>
                <input readonly name="last_total[]" value="<?=$row['LAST_TOTAL']?>" class="form-control" id="txt_last_total_<?=$count?>" />
            </td>
            <td>
                <input readonly name="class_price_no_vat[]" value="<?=$row['CLASS_PRICE_NO_VAT']?>" class="form-control" id="txt_class_price_no_vat_<?=$count?>" />
            </td>
            <td>
                <?php if ($row['CHECKED']==1){?>

                    <input  name="class_case_hints[]" value="<?=$row['CLASS_CASE_HINTS']?>" class="form-control" id="txt_class_case_hints_<?=$count?>" readonly />
                <?php }?>
                <?php if ($row['CHECKED']==2){?>
                    <textarea rows="1" name="class_case_hints[]" class="form-control" id="txt_class_case_hints_<?=$count?>" ><?=$row['CLASS_CASE_HINTS']?></textarea>
                <?php }?>
                <?php if ($row['CHECKED']==3){?>
                    <textarea rows="1" name="class_case_hints[]" class="form-control" id="txt_class_case_hints_<?=$count?>" ><?=$row['CLASS_CASE_HINTS']?></textarea>
                <?php }?>


            </td>
            <td>
                <?php if ($row['CHECKED']==1){?>

                    <input  name="hints[]" value="<?=$row['HINTS']?>" class="form-control" id="txt_hints_<?=$count?>" readonly />

                <?php }?>
                <?php if ($row['CHECKED']==2){?>
                    <textarea rows="1" name="hints[]" class="form-control" id="txt_hints_<?=$count?>" ><?=$row['HINTS']?></textarea>
                <?php }?>
                <?php if ($row['CHECKED']==3){?>
                    <textarea rows="1" name="hints[]" class="form-control" id="txt_hints_<?=$count?>" ><?=$row['HINTS']?></textarea>
                <?php }?>

            </td>
            <?php
            if ( HaveAccess($delete_url_details) && (!$isCreate and ( (count($rs)>0)? ($rs['DONATION_FILE_CASE']==1 || $rs['DONATION_FILE_CASE']==4 ) : ''  ))) : ?>
                <td>
                <?php if ( (count($rs)>0)? (($row['CLASS_CASE']==1) && ($row['ORDERDERD']==0)) : false) { ?>
                    <a onclick="javascript:delete_row_del('<?=$row['SER']?>','<?=$row['DONATION_FILE_ID']?>');"  href='javascript:;'><i class='glyphicon glyphicon-remove'></i></a>
                    <?php } ?>
                    </td>
            <?php endif; ?>

            </tr>
        <?php
        }
    }
    ?>

    </tbody>
    <tfoot>
    <tr>
        <th>الإجمالي الكلي</th>
        <th>
        </th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <!-- <th id="id_amount_total"></th>-->
        <th ></th>
        <?php if(!empty($details)){?>
        <th ></th>
        <?php }?>
        <th id="total_d_expenses"></th>
        <th id="total_total_with_expenses"></th>

        <th id="price_without_discount_total"></th>
        <th></th>
        <th id="tax_value_total"></th>
        <th id="price_with_trans_expenses_total"></th>

        <th id="price_without_vat_total"></th>
        <th id="total_d_other_expenses"></th>
        <th id="total_last_price"></th>
        <th id="total_last_total"></th>
        <th></th>
        <th></th>
        <th></th>
    </tr>
    <tr>
        <th></th>
        <th>
            <?php if (count($details) <=0 || ( (isset($can_edit)?$can_edit:false) and  $adopt==1 )) { ?>
                <a onclick="javascript:add_row(this,'input:text,input:hidden[name^=ser],textarea,select',false);" onfocus="javascript:add_row(this,'input:text,input:hidden[name^=ser],textarea,select',false);" href="javascript:;"><i class="glyphicon glyphicon-plus"></i>جديد</a>
            <?php }
            else
                if (HaveAccess($change_items_url) && $adopt==2)
                {
                    ?>  <a onclick="javascript:add_row(this,'input:text,input:hidden[name^=ser],textarea,select',false);" onfocus="javascript:add_row(this,'input:text,input:hidden[name^=ser],textarea,select',false);" href="javascript:;"><i class="glyphicon glyphicon-plus"></i>جديد</a>
                <?php
                }
            ?>

        </th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th ></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <?php if(!empty($details)){?>
        <th ></th>
        <?php }?>

    </tr>

    </tfoot>
    </table>
    </div>

<?php

}
elseif ($donation_type_install==3)
{
    ?>
    <div class="tb_container">
    <table class="table" id="details_tb" data-container="container" style="width: 100%">
    <thead>
    <tr>

        <th style="width: 10px">رقم الصنف حسب المنحة</th>
        <th style="width: 100px">ر مز القسم</th>
        <th>وصف الأعمال</th>
        <th  >الوحدة</th>
        <th >الكمية المطلوبة</th>
        <th >السعر</th>

        <th > التكلفة</th>

        <th > اجمالى تكلفة الأعمال شامل الخصم</th>
        <th > مصاريف أوضرائب اضافية اخري </th>
        <th > التكلفة الاجمالية النهائية للأعمال </th>

        <th >بيان حالة الصنف</th>
        <th > ملاحظات </th>
        <?php if ( HaveAccess($delete_url_details) && (!$isCreate and ( (count($rs)>0)? ($rs['DONATION_FILE_CASE']==1 || $rs['DONATION_FILE_CASE']==4 ) : ''  ) and isset($can_edit)?$can_edit:false )) : ?>
            <th>حذف</th>
        <?php endif; ?>
    </tr>
    </thead>

    <tbody>

    <?php if(count($details) <= 0) {  // ادخال ?>

        <tr>
            <td>
                <input type="hidden" name="ser[]" value="0"  />
                <input name="donation_class_id[]" class="form-control" id="donation_class_id_<?=$count?>" data-val="false" data-val-required="required" />
            </td>
            <td>

                <select name="donation_dept_ser[]" id="dp_donation_dept_ser_<?=$count?>" data-curr="false" class="form-control">
                    <option data-expenses_trans_percentage="0" data-discount_percentage="0"  data-vat_percent="0" data-vat_total="0" data-expenses_adjustments="0" value="">-</option>
                    <?php foreach ($departments_all as $row1) : ?>
                        <option data-expenses_trans_percentage="<?=$row1['EXPENSES_TRANS_PERCENTAGE']?>" data-discount_percentage="<?=$row1['DISCOUNT_PERCENTAGE']?>"  data-vat_percent="<?=$row1['VAT_PERCENT']?>" data-vat_total="<?=$row1['VAT_TOTAL']?>" data-expenses_adjustments="<?=$row1['EXPENSES_ADJUSTMENTS']?>" value="<?= $row1['SER'] ?>" ><?= $row1['DONATION_DEPT_CODE'] ?></option>
                    <?php endforeach; ?>
                </select>

            </td>
            <td>

                <select name="class_id[]" id="dp_class_id_<?=$count?>" data-curr="false" class="form-control" data-val="false" data-val-required="required">
                    <option value="">-</option>
                    <?php foreach ($class_install as $row2) : ?>
                        <option value="<?= $row2['CON_NO'] ?>" ><?= $row2['CON_NAME'] ?></option>
                    <?php endforeach; ?>
                </select>



            </td>

            <td>
                <select name="class_unit[]" id="dp_class_unit_<?=$count?>" data-curr="false" class="form-control" data-val="false" data-val-required="required">
                    <option value="">-</option>
                    <?php foreach ($class_type_show as $row3) : ?>
                        <option value="<?= $row3['CON_NO'] ?>" ><?= $row3['CON_NAME'] ?></option>
                    <?php endforeach; ?>
                </select>
            </td>



            <td>
                <input name="amount[]" data-name="amount" class="form-control" id="txt_amount_<?=$count?>" data-val="true" data-val-required="required"/>
            </td>

            <td>
                <input name="price1[]"  class="form-control" id="price_id_<?=$count?>" />
            </td>



            <td>
                <input readonly data-name="total_with_expenses" name="total_with_expenses[]"  class="form-control" id="txt_total_with_expenses_<?=$count?>" />
            </td>



            <td>
                <input readonly data-name="price_without_discount" name="price_without_discount[]"  class="form-control" id="txt_price_without_discount_<?=$count?>" />
            </td>


            <td>
                <input data-name="d_other_expenses" name="d_other_expenses[]"   class="form-control" id="txt_d_other_expenses_<?=$count?>" />
            </td>
            <td>
                <input readonly data-name="last_total" name="last_total[]"  class="form-control" id="txt_last_total_<?=$count?>" />
            </td>

            <td>
                <textarea rows="1" name="class_case_hints[]" class="form-control" id="txt_class_case_hints_<?=$count?>" ></textarea>
            </td>
            <td>
                <textarea rows="1" name="hints[]" class="form-control" id="txt_hints_<?=$count?>" ></textarea>

            </td>
            <td></td>
        </tr>
    <?php
    }else if(count($details) > 0) { // تعديل
        $count = -1;

        foreach($details as $row) {
            ++$count+1
            ?>
            <tr>
                <td>
                    <input type="hidden" name="ser[]" value="<?=$row['SER']?>" />
                    <input name="donation_class_id[]" value="<?=$row['DONATION_CLASS_ID']?>" class="form-control" id="donation_class_id_<?=$count?>" />
                </td>
                <td> <select name="donation_dept_ser[]" id="dp_donation_dept_ser_<?=$count?>" data-curr="false" class="form-control">
                        <option data-expenses_trans_percentage="0" data-discount_percentage="0"  data-vat_percent="0" data-vat_total="0" data-expenses_adjustments="0"  value="">-</option>
                        <?php foreach ($departments_all as $row1) : ?>
                            <option data-expenses_trans_percentage="<?=$row1['EXPENSES_TRANS_PERCENTAGE']?>" data-discount_percentage="<?=$row1['DISCOUNT_PERCENTAGE']?>"  data-vat_percent="<?=$row1['VAT_PERCENT']?>" data-vat_total="<?=$row1['VAT_TOTAL']?>" data-expenses_adjustments="<?=$row1['EXPENSES_ADJUSTMENTS']?>" value="<?= $row1['SER'] ?>"<?PHP  if ($row['DONATION_DEPT_SER'] == $row1['SER']) echo " selected"; ?> ><?= $row1['DONATION_DEPT_CODE'] ?></option>
                        <?php endforeach; ?>
                    </select>
                    <span class="field-validation-valid" data-valmsg-for="donation_dept_ser[]" data-valmsg-replace="true"></td>
                <td>

                    <select name="class_id[]" id="dp_class_id_<?=$count?>" data-curr="false" class="form-control" data-val="false" data-val-required="required">
                        <option value="">-</option>
                        <?php foreach ($class_install as $row2) : ?>
                            <option value="<?= $row2['CON_NO'] ?>" <?PHP  if ($row['CALSS_ID'] == $row2['CON_NO']) echo " selected"; ?> ><?= $row2['CON_NAME'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>


                <td>

                    <select name="class_unit[]" id="dp_class_unit_<?=$count?>" data-curr="false" class="form-control" data-val="false" data-val-required="required">
                        <option value="">-</option>
                        <?php foreach ($class_type_show as $row3) : ?>
                            <option value="<?= $row3['CON_NO'] ?>" <?PHP  if ($row['CLASS_UNIT'] == $row3['CON_NO']) echo " selected"; ?> ><?= $row3['CON_NAME'] ?></option>
                        <?php endforeach; ?>
                    </select>


                </td>


                <td>
                    <input data-name="amount" name="amount[]" value="<?=$row['AMOUNT']?>" class="form-control" id="txt_amount_<?=$count?>" data-val="true" data-val-required="required"/>
                </td>

                <td>
                    <input name="price1[]" value="<?=$row['PRICE']?>" class="form-control" id="price_id_<?=$count?>" />
                </td>



                <td>
                    <input readonly data-name="total_with_expenses" name="total_with_expenses[]" value="<?=$row['ROUND_TOTAL_WITH_EXPENSES']?>" class="form-control" id="txt_total_with_expenses_<?=$count?>" />
                </td>



                <td>
                    <input readonly data-name="price_without_discount" name="price_without_discount[]" value="<?=$row['PRICE_WITHOUT_DISCOUNT']?>" class="form-control" id="txt_price_without_discount_<?=$count?>" />
                </td>


                <td>
                    <input data-name="d_other_expenses" name="d_other_expenses[]" value="<?=$row['OTHER_EXPENSES']?>"  class="form-control" id="txt_d_other_expenses_<?=$count?>" />
                </td>
                <td>
                    <input readonly data-name="last_total" name="last_total[]" value="<?=$row['LAST_TOTAL']?>" class="form-control" id="txt_last_total_<?=$count?>" />
                </td>

                <td>
                    <textarea rows="1" name="class_case_hints[]" class="form-control" id="txt_class_case_hints_<?=$count?>" ><?=$row['CLASS_CASE_HINTS']?></textarea>
                </td>
                <td>
                    <textarea rows="1" name="hints[]" class="form-control" id="txt_hints_<?=$count?>" ><?=$row['HINTS']?></textarea>

                </td>
                <?php if ( HaveAccess($delete_url_details) && (!$isCreate and ( (count($rs)>0)? ($rs['DONATION_FILE_CASE']==1 || $rs['DONATION_FILE_CASE']==4 ) : ''  ) and isset($can_edit)?$can_edit:false )) : ?>
                    <td>

                        <a onclick="javascript:delete_row_del('<?=$row['SER']?>','<?=$row['DONATION_FILE_ID']?>');"  href='javascript:;'><i class='glyphicon glyphicon-remove'></i></a>

                    </td>
                <?php endif; ?>

            </tr>
        <?php
        }
    }
    ?>

    </tbody>
    <tfoot>
    <tr>
        <th>الإجمالي الكلي</th>
        <th>
        </th>
        <th></th>
        <th></th>
        <th id="n_amount_total"></th>
        <th></th>
        <th id="n_total_total_with_expenses"></th>
        <th id="n_total_price_without_discount"></th>
        <th id="n_total_other_expenses" ></th>

        <th id="n_total_last_total"></th>


        <th></th>
        <th></th>
    </tr>
    <tr>
        <th></th>
        <th>
            <?php if (count($details) <=0 || ( (isset($can_edit)?$can_edit:false) and  $adopt==1 )) { ?>
                <a onclick="javascript:add_row(this,'input:text,input:hidden[name^=ser],textarea,select',false);" onfocus="javascript:add_row(this,'input:text,input:hidden[name^=ser],textarea,select',false);" href="javascript:;"><i class="glyphicon glyphicon-plus"></i>جديد</a>
            <?php }
            else
                if (HaveAccess($change_items_url) && $adopt==2)
                {
                    ?>  <a onclick="javascript:add_row(this,'input:text,input:hidden[name^=ser],textarea,select',false);" onfocus="javascript:add_row(this,'input:text,input:hidden[name^=ser],textarea,select',false);" href="javascript:;"><i class="glyphicon glyphicon-plus"></i>جديد</a>
                <?php
                }
            ?>

        </th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th ></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>


    </tr>

    </tfoot>
    </table>
    </div>
<?php
}
?>
