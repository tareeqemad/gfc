<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 18/11/15
 * Time: 11:50 ص
 */
$MODULE_NAME= 'donations';
$TB_NAME= 'donation';
$DET_TB_NAME1='public_get_details_dept';
$isCreate =isset($donation_dept) && count($donation_dept)  > 0 ?false:true;
$HaveRs = (!$isCreate)? true:false;
$rs=$isCreate ?array() : $donation_data[0];
$rs2=$isCreate ?array() : $donation_dept[0];

//print_r( $rs2);
/*$post_url=base_url("$MODULE_NAME/$TB_NAME/".($action == 'index'?'create':$action));
$back_url=base_url("$MODULE_NAME/$TB_NAME"); //$action
$class_output_url=base_url("stores/stores_class_output/get");
$select_accounts_url =base_url('financial/accounts/public_select_accounts');
$select_items_url=base_url("stores/classes/public_index");
$get_class_url =base_url('stores/classes/public_get_id');
$get_url= base_url("$MODULE_NAME/$TB_NAME/get");
$adopt_url= base_url("$MODULE_NAME/$TB_NAME/adopt");*/
$delete_url_dept= base_url("$MODULE_NAME/$TB_NAME/delete_details_dept");
$addtinal_lots_url= base_url("$MODULE_NAME/$TB_NAME/save_dept_addtinal");
/*$fid = (count($rs)>0)?$rs['DONATION_FILE_ID']:0;*/
$count=0;

?>
<?php
if ($install==0)
{
    ?>
    <div class="tb_container" >
    <table class="table" id="details_tb1" data-container="container" style="width: 3000px;max-width: 3000px;" >
    <thead>
    <tr>
        <th >اسم ووصف القسم</th>
        <th >الرمز العام للقسم</th>
        <th >قيمة المنحة</th>
        <th >خدمات توصيل المواد للمخازن </th>
        <th >الاجمالي</th>

        <th >نسبة الخصم</th>
        <th >قيمة الخصم</th>
        <th >الاجمالي شامل الخصم</th>

        <th >نسبة ضرائب الاستيراد (TAX)</th>
        <th >قيمة ضرائب الاستيراد (TAX)</th>
        <th >اجمالى تكلفة المواد شاملة الخصم وضرائب الاستيراد</th>

        <th >نسبة الضريبة</th>
        <th >قيمة الضريبة</th>


        <th >اجمالى تكلفة المواد شاملة الخصم وجميع الضرائب</th>
        <th >تسويات</th>
        <th>الاجمالي النهائي</th>
        <th>ملاحظات</th>



        <?php if ( HaveAccess($delete_url_dept) && (!$isCreate and ( (count($rs)>0)? $rs['DONATION_FILE_CASE']==1 : ''  ) and isset($can_edit)?$can_edit:false )) : ?>
            <th >حذف</th>
        <?php endif; ?>
    </tr>
    </thead>

    <tbody>
    <?php if(count($details_dept) <= 0) {  // ادخال ?>

        <tr>
            <td>
                <input type="hidden" name="ser_dept[]" value="0"  />
                <input name="donation_dept_name[]" class="form-control" id="txt_donation_dept_name_<?=$count?>" data-val="true" data-val-required="required" />
            </td>

            <td>

                <input name="donation_dept_code[]" class="form-control" id="txt_donation_dept_code_<?=$count?>" data-val="true" data-val-required="required" />
            </td>

            <td>
                <input name="donation_dept_value[]" value="" class="form-control" id="txt_donation_dept_value_<?=$count?>" data-val="true" data-val-required="required" />
            </td>
            <td><input name="expenses[]" value="" class="form-control" id="txt_expenses_<?=$count?>" data-val="true" data-val-required="required" /></td>
            <td><input name="total[]" value="" class="form-control" id="txt_total_<?=$count?>" data-val="true" readonly /></td>


            <td>
                <input name="discount_percentage[]" value="" class="form-control" id="txt_discount_percentage_<?=$count?>" data-val="true" data-val-required="required" />

            </td>
            <td>

                <input name="discount_value[]" value="" class="form-control" id="txt_discount_value_<?=$count?>" data-val="true" data-val-required="required" />

            </td>
            <td><input name="discount_total[]" value="" class="form-control" id="txt_discount_total_<?=$count?>" data-val="true" readonly /></td>

            <td> <input name="expenses_trans_percentage[]" value="" class="form-control" id="txt_expenses_trans_percentage_<?=$count?>" data-val="true" data-val-required="required" /></td>
            <td>
                <input name="expenses_transport[]" value="" class="form-control" id="txt_expenses_transport_<?=$count?>" data-val="true" data-val-required="required" />

            </td>
            <td><input name="expenses_transport_total[]" value="" class="form-control" id="txt_expenses_transport_total_<?=$count?>" data-val="true" readonly /></td>

            <td >
                <input name="vat_percent[]" value="" class="form-control" id="txt_vat_percent_<?=$count?>" data-val="true" data-val-required="required"  />

            </td>
            <td>
                <input name="vat_value[]" value="" class="form-control" id="txt_vat_value_<?=$count?>" data-val="true" data-val-required="required" />
            </td>
            <td><input name="vat_total[]" value="" class="form-control" id="txt_vat_total_<?=$count?>" data-val="true" readonly /></td>


            <td>
                <input name="expenses_adjustments[]" value="" class="form-control" id="txt_expenses_adjustments_<?=$count?>" data-val="true" data-val-required="required" />

            </td>
            <td><input name="all_total[]" value="" class="form-control" id="txt_all_total_<?=$count?>" data-val="true" readonly /></td>
            <td><textarea rows="1" name="department_note[]" class="form-control" id="txt_department_note_<?=$count?>" /></textarea></td>





            <td></td>
        </tr>
    <?php
    }else if(count($details_dept) > 0) { // تعديل
        $count = -1;
        foreach($details_dept as $row) {
            ++$count+1
            ?>
            <tr>
                <td>

                    <?php if ($row['CHECKED']==1){?>
                        <input type="hidden" name="ser_dept[]" value="<?=$row['SER']?>"  />
                        <input name="donation_dept_name[]" value="<?=$row['DONATION_DEPT_NAME']?>" class="form-control" id="txt_donation_dept_name_<?=$count?>" data-val="true" data-val-required="required" />
                    <?php }?>
                    <?php if ($row['CHECKED']==2){?>
                        <input type="hidden" name="ser_dept[]" value="<?=$row['SER']?>"  />
                        <input name="donation_dept_name[]" value="<?=$row['DONATION_DEPT_NAME']?>" class="form-control" id="txt_donation_dept_name_<?=$count?>" data-val="true" data-val-required="required" readonly />
                    <?php }?>



                </td>

                <td>

                    <?php if ($row['CHECKED']==1){?>
                        <input name="donation_dept_code[]" value="<?=$row['DONATION_DEPT_CODE']?>" class="form-control" id="txt_donation_dept_code_<?=$count?>" data-val="true" data-val-required="required" />
                    <?php }?>
                    <?php if ($row['CHECKED']==2){?>
                        <input name="donation_dept_code[]" value="<?=$row['DONATION_DEPT_CODE']?>" class="form-control" id="txt_donation_dept_code_<?=$count?>" data-val="true" data-val-required="required" readonly />
                    <?php }?>




                </td>

                <td>

                    <?php if ($row['CHECKED']==1){?>
                        <input name="donation_dept_value[]" value="<?=$row['DONATION_DEPT_VALUE']?>" class="form-control" id="txt_donation_dept_value_<?=$count?>" data-val="true" data-val-required="required"  /><?php }?>
                    <?php if ($row['CHECKED']==2){?>
                        <input name="donation_dept_value[]" value="<?=$row['DONATION_DEPT_VALUE']?>" class="form-control" id="txt_donation_dept_value_<?=$count?>" data-val="true" data-val-required="required" readonly/>
                    <?php }?>


                </td>
                <td>

                    <?php if ($row['CHECKED']==1){?>
                        <input name="expenses[]" value="<?=$row['EXPENSES']?>" class="form-control" id="txt_expenses_<?=$count?>" data-val="true" data-val-required="required"  />
                    <?php }?>
                    <?php if ($row['CHECKED']==2){?>
                        <input name="expenses[]" value="<?=$row['EXPENSES']?>" class="form-control" id="txt_expenses_<?=$count?>" data-val="true" data-val-required="required" readonly/>
                    <?php }?>


                </td>
                <td><input name="total[]" value="<?=$row['TOTAL']?>" class="form-control" id="txt_total_<?=$count?>" data-val="true" readonly /></td>



                <td>

                    <?php if ($row['CHECKED']==1){?>
                        <input name="discount_percentage[]" value="<?=$row['DISCOUNT_PERCENTAGE']?>" class="form-control" id="txt_discount_percentage_<?=$count?>" data-val="true" data-val-required="required" />     <?php }?>
                    <?php if ($row['CHECKED']==2){?>
                        <input name="discount_percentage[]" value="<?=$row['DISCOUNT_PERCENTAGE']?>" class="form-control" id="txt_discount_percentage_<?=$count?>" data-val="true" data-val-required="required" readonly />                    <?php }?>


                </td>
                <td>
                    <?php if ($row['CHECKED']==1){?>
                        <input name="discount_value[]" value="<?=$row['DISCOUNT_VALUE']?>" class="form-control" id="txt_discount_value_<?=$count?>" data-val="true" data-val-required="required" />
                    <?php }?>
                    <?php if ($row['CHECKED']==2){?>
                        <input name="discount_value[]" value="<?=$row['DISCOUNT_VALUE']?>" class="form-control" id="txt_discount_value_<?=$count?>" data-val="true" data-val-required="required" readonly />
                    <?php }?>


                </td>
                <td><input name="discount_total[]" value="<?=$row['DISCOUNT_TOTAL']?>" class="form-control" id="txt_discount_total_<?=$count?>" data-val="true" readonly /></td>

                <td>
                    <?php if ($row['CHECKED']==1){?>
                        <input name="expenses_trans_percentage[]" value="<?=$row['EXPENSES_TRANS_PERCENTAGE']?>" class="form-control" id="txt_expenses_trans_percentage_<?=$count?>" data-val="true" data-val-required="required" />
                    <?php }?>
                    <?php if ($row['CHECKED']==2){?>
                        <input name="expenses_trans_percentage[]" value="<?=$row['EXPENSES_TRANS_PERCENTAGE']?>" class="form-control" id="txt_expenses_trans_percentage_<?=$count?>" data-val="true" data-val-required="required" readonly/>

                    <?php }?>
                            </td>
                <td>
                    <?php if ($row['CHECKED']==1){?>
                        <input name="expenses_transport[]" value="<?=$row['EXPENSES_TRANSPORT']?>" class="form-control" id="txt_expenses_transport_<?=$count?>" data-val="true" data-val-required="required" />
                    <?php }?>
                    <?php if ($row['CHECKED']==2){?>
                        <input name="expenses_transport[]" value="<?=$row['EXPENSES_TRANSPORT']?>" class="form-control" id="txt_expenses_transport_<?=$count?>" data-val="true" data-val-required="required" readonly />

                    <?php }?>

                </td>
                <td><input name="expenses_transport_total[]" value="<?=$row['EXPENSES_TRANSPORT_TOTAL']?>" class="form-control" id="txt_expenses_transport_total_<?=$count?>" data-val="true" readonly /></td>

                <td>
                    <?php if ($row['CHECKED']==1){?>

                        <input name="vat_percent[]" value="<?=$row['VAT_PERCENT']?>" class="form-control" id="txt_vat_percent_<?=$count?>" data-val="true" data-val-required="required" />

                    <?php }?>
                    <?php if ($row['CHECKED']==2){?>

                        <input name="vat_percent[]" value="<?=$row['VAT_PERCENT']?>" class="form-control" id="txt_vat_percent_<?=$count?>" data-val="true" data-val-required="required" readonly />

                    <?php }?>
                </td>
                <td>

                    <?php if ($row['CHECKED']==1){?>

                        <input name="vat_value[]" value="<?=$row['VAT_VALUE']?>" class="form-control" id="txt_vat_value_<?=$count?>" data-val="true" data-val-required="required" />
                    <?php }?>
                    <?php if ($row['CHECKED']==2){?>

                        <input name="vat_value[]" value="<?=$row['VAT_VALUE']?>" class="form-control" id="txt_vat_value_<?=$count?>" data-val="true" data-val-required="required" readonly />
                    <?php }?>


                </td>
                <td><input name="vat_total[]" value="<?=$row['VAT_TOTAL']?>" class="form-control" id="txt_vat_total_<?=$count?>" data-val="true" readonly /></td>

                <td>

                    <?php if ($row['CHECKED']==1){?>


                        <input name="expenses_adjustments[]" value="<?=$row['EXPENSES_ADJUSTMENTS']?>" class="form-control" id="txt_expenses_adjustments_<?=$count?>" data-val="true" data-val-required="required" />
                    <?php }?>
                    <?php if ($row['CHECKED']==2){?>

                        <input name="expenses_adjustments[]" value="<?=$row['EXPENSES_ADJUSTMENTS']?>" class="form-control" id="txt_expenses_adjustments_<?=$count?>" data-val="true" data-val-required="required" readonly />
 <?php }?>

                </td>
                <td><input name="all_total[]" value="<?=$row['ALL_TOTAL']?>" class="form-control" id="txt_all_total_<?=$count?>" data-val="true" readonly /></td>
                <td>

                    <?php if ($row['CHECKED']==1){?>


                        <textarea rows="1" name="department_note[]" class="form-control" id="txt_department_note_<?=$count?>" /><?=$row['DEPARTMENT_NOTE']?></textarea>
                    <?php }?>
                    <?php if ($row['CHECKED']==2){?>


                        <textarea rows="1" name="department_note[]" class="form-control" id="txt_department_note_<?=$count?>" readonly /><?=$row['DEPARTMENT_NOTE']?></textarea>            <?php }?>

                    </td>





                <?php if ( HaveAccess($delete_url_dept) && (!$isCreate and ( (count($rs)>0)? $rs['DONATION_FILE_CASE']==1 : ''  ) and isset($can_edit)?$can_edit:false )) : ?>
                    <td><a onclick="javascript:delete_row_del_dept('<?=$row['SER']?>','<?=$row['DONATION_FILE_ID']?>');"  href='javascript:;'><i class='glyphicon glyphicon-remove'></i></a></td>
                <?php endif; ?>

            </tr>
        <?php
        }
    }
    ?>

    </tbody>
    <tfoot>
    <tr>
        <th>الاجمالي الكلي للمنحة</th>
        <th>
        </th>
        <th id="total_donation_dept_value"></th>
        <th id="total_expenses"></th>
        <th id="total_total"></th>
        <th></th>
        <th id="total_discount_value"></th>
        <th id="total_discount_total"></th>
        <th></th>
        <th id="total_expenses_transport"></th>
        <th id="total_expenses_transport_total"></th>
        <th></th>
        <th id="total_vat_value"></th>
        <th id="total_vat_total"></th>


        <th id="total_expenses_adjustments"></th>
        <th id="total_all_total"></th>
        <th></th>
    </tr>
    <tr>
        <th></th>
        <th>
            <?php if (count($details_dept) <=0 || ( (isset($can_edit)?$can_edit:false) and  $adopt==1 )) { ?>
                <a onclick="javascript:add_row(this,'input,textarea',false);" onfocus="javascript:add_row(this,'input,textarea',false);" href="javascript:;"><i class="glyphicon glyphicon-plus"></i>جديد</a>
            <?php } ?>

            <?php if (HaveAccess($addtinal_lots_url) and ( ( (isset($can_edit)?$can_edit:false) and  $adopt==2 ) )) { ?>
                <a onclick="javascript:add_row(this,'input,textarea',false);" onfocus="javascript:add_row(this,'input,textarea',false);" href="javascript:;"><i class="glyphicon glyphicon-plus"></i>جديد</a>

            <?php } ?>


        </th>
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
        <th></th>
    </tr>
    </tfoot>
    </table>
    </div>
<?php

}
elseif ($install==1)
{
    ?>
    <div class="tb_container">
        <table class="table" id="details_tb1" data-container="container" style="width: 100%">
            <thead>
            <tr>
                <th >اسم ووصف القسم</th>
                <th >الرمز العام للقسم</th>
                <th >تكلفة الاعمال</th>
                <th >نسبة الخصم</th>
                <th >اقيمة الخصم</th>

                <th >اجمالي تكلفة الاعمال شاملة الخصم</th>
                <th >مصاريف اضافية اخرى</th>

                <th>الاجمالي النهائي</th>
                <th>ملاحظات</th>



                <?php if ( HaveAccess($delete_url_dept) && (!$isCreate and ( (count($rs)>0)? $rs['DONATION_FILE_CASE']==1 : ''  ) and isset($can_edit)?$can_edit:false )) : ?>
                    <th >حذف</th>
                <?php endif; ?>
            </tr>
            </thead>

            <tbody>
            <?php if(count($details_dept) <= 0) {  // ادخال ?>

                <tr>
                    <td>
                        <input type="hidden" data-rest="true" name="ser_dept[]" value="0"  />
                        <input name="donation_dept_name[]" data-rest="true" class="form-control" id="txt_donation_dept_name_<?=$count?>" data-val="true" data-val-required="required" />
                    </td>

                    <td>

                        <input name="donation_dept_code[]" data-rest="true" class="form-control" id="txt_donation_dept_code_<?=$count?>" data-val="true" data-val-required="required" />
                    </td>

                    <td>
                        <input name="donation_dept_value[]" data-rest="true" data-name="1" value="" class="form-control" id="txt_donation_dept_value_<?=$count?>" data-val="true" data-val-required="required" />
                    </td>



                    <td>
                        <input name="discount_percentage[]" data-rest="true" value="" class="form-control" id="txt_discount_percentage_<?=$count?>" data-val="true" data-val-required="required" />

                    </td>
                    <td>

                        <input name="discount_value[]" data-rest="true" data-name="2" value=""   class="form-control" id="txt_discount_value_<?=$count?>" data-val="true" data-val-required="required" />

                    </td>
                    <td><input name="discount_total[]" data-rest="true" data-name="3" value="" class="form-control" id="txt_discount_total_<?=$count?>" data-val="true" readonly /></td>



                    <td>
                        <input name="expenses_adjustments[]" data-rest="true" data-name="4" value="" class="form-control" id="txt_expenses_adjustments_<?=$count?>" data-val="true" data-val-required="required" />

                    </td>
                    <td><input name="all_total[]" data-rest="true" data-name="5" value="" class="form-control" id="txt_all_total_<?=$count?>" data-val="true" readonly /></td>
                    <td><textarea rows="1" name="department_note[]" class="form-control" id="txt_department_note_<?=$count?>" /></textarea></td>





                    <td></td>
                </tr>
            <?php
            }else if(count($details_dept) > 0) { // تعديل
                $count = -1;
                foreach($details_dept as $row) {
                    ++$count+1
                    ?>
                    <tr>
                        <td>
                            <input type="hidden" name="ser_dept[]" value="<?=$row['SER']?>" data-rest="true" />
                            <input name="donation_dept_name[]" data-rest="true" value="<?=$row['DONATION_DEPT_NAME']?>" class="form-control" id="txt_donation_dept_name_<?=$count?>" data-val="true" data-val-required="required" />
                        </td>

                        <td>

                            <input name="donation_dept_code[]" data-rest="true" value="<?=$row['DONATION_DEPT_CODE']?>" class="form-control" id="txt_donation_dept_code_<?=$count?>" data-val="true" data-val-required="required" />
                        </td>

                        <td>
                            <input name="donation_dept_value[]" data-rest="true" data-name="1" value="<?=$row['DONATION_DEPT_VALUE']?>" class="form-control" id="txt_donation_dept_value_<?=$count?>" data-val="true" data-val-required="required" />
                        </td>



                        <td>
                            <input name="discount_percentage[]" data-rest="true" value="<?=$row['DISCOUNT_PERCENTAGE']?>" class="form-control" id="txt_discount_percentage_<?=$count?>" data-val="true" data-val-required="required" />
                        </td>
                        <td>

                            <input name="discount_value[]"  data-rest="true" data-name="2" value="<?=$row['DISCOUNT_VALUE']?>" class="form-control" id="txt_discount_value_<?=$count?>" data-val="true" data-val-required="required" />

                        </td>
                        <td><input name="discount_total[]" data-rest="true" data-name="3" value="<?=$row['DISCOUNT_TOTAL']?>" class="form-control" id="txt_discount_total_<?=$count?>" data-val="true" readonly /></td>


                        <td>
                            <input name="expenses_adjustments[]" data-rest="true" data-name="4" value="<?=$row['EXPENSES_ADJUSTMENTS']?>" class="form-control" id="txt_expenses_adjustments_<?=$count?>" data-val="true" data-val-required="required" />

                        </td>
                        <td><input name="all_total[]" data-rest="true" data-name="5" value="<?=$row['ALL_TOTAL']?>" class="form-control" id="txt_all_total_<?=$count?>" data-val="true" readonly /></td>
                        <td><textarea rows="1" name="department_note[]" class="form-control" id="txt_department_note_<?=$count?>" /><?=$row['DEPARTMENT_NOTE']?></textarea></td>





                        <?php if ( HaveAccess($delete_url_dept) && (!$isCreate and ( (count($rs)>0)? $rs['DONATION_FILE_CASE']==1 : ''  ) and isset($can_edit)?$can_edit:false )) : ?>
                            <td><a onclick="javascript:delete_row_del_dept('<?=$row['SER']?>','<?=$row['DONATION_FILE_ID']?>');"  href='javascript:;'><i class='glyphicon glyphicon-remove'></i></a></td>
                        <?php endif; ?>

                    </tr>
                <?php
                }
            }
            ?>

            </tbody>
            <tfoot>
            <tr>
                <th>الاجمالي الكلي للمنحة</th>
                <th>
                </th>
                <th id="total_donation_dept_value_1"></th>

                <th></th>
                <th id="total_discount_value_1"></th>
                <th id="total_discount_total_1"></th>

                <th id="total_expenses_adjustments_1"></th>
                <th id="total_all_total_1"></th>
                <th></th>
            </tr>
            <tr>
                <th></th>
                <th>
                    <?php if (count($details_dept) <=0 || ( (isset($can_edit)?$can_edit:false) and  $adopt==1 )) { ?>
                        <a onclick="javascript:add_row(this,'input,textarea',false);" onfocus="javascript:add_row(this,'input[data-rest],textarea',false);" href="javascript:;"><i class="glyphicon glyphicon-plus"></i>جديد</a>
                    <?php } ?>


                </th>
                <th ></th>
                <th></th>
                <th></th>
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
