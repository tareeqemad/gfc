<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$rs = ($isCreate) ? array() : $planning_data;
$type_project_name = (count($rs) > 0) ? $rs['TYPE_PROJECT'] : 1;
$class_name = (count($rs) > 0) ? $rs['CLASS'] : 0;
$fin_id = (count($rs) > 0) ? $rs['FINANCE'] : 0;
$no_edit = 1;
$readonly = "";
$class_no_edit = 1;
$class_readonly = "";
$hidden = "hidden";
$no_edit_year = 1;
$hidden_class = "";
$year_plan = "";
$hidden_item_class="";

if ($type_project_name == 2)
{
if($fin_id == "")
{
$rs['FINANCE_NAME']="شركة توزيع كهرباء محافظات غزة";
}
    $year_plan = "readonly";
    $hidden = "";
    $readonly = "readonly";
    $no_edit = 2;
    $hidden_class = "hidden";

    if ($class_name != 1)
    {
        $class_no_edit = 1;
        $class_readonly = "";
    }
    else
    {
        $class_no_edit = 2;
        $class_readonly = "readonly";
    }

}
else if ($type_project_name == 1)
{

    $hidden = "hidden";
    $hidden_class = "";
    if ($class_name != 1)
    {
        $class_no_edit = 1;
        $class_readonly = "";
        $year_plan = "readonly";
		
    }
    else
    {
        $class_no_edit = 2;
        $class_readonly = "readonly";
        $no_edit_year = 2;
        $year_plan = "readonly";
    }

}
$fin_hidden = "";

if ($class_name == 3)
{
    $fin_hidden = "hidden";
    $year_plan = "readonly";
	$hidden_item_class="hidden";

}
if (count($rs) > 0)
{
    $year_plan = "readonly";
}
else
{
    $year_plan = "";
}

$class_count=0;
?>

<div class="tb_container">
<table class="table items_class_tb" id="details_tb1" data-container="container"  align="center">
        <thead>
        <tr>
            <th style="width: 2%">#</th>
            <th style="width: 10%">رقم الصنف</th>
            <th style="width: 30%">اسم الصنف</th>
            <th style="width: 11%">الكمية المطلوبة</th>
            <th style="width: 11%">الوحدة</th>
            <th style="width: 12%">حالة الصنف</th>
			<th>السعر (بطاقة الصنف)</th>
			<th>الملاحظات</th>
        </tr>
        </thead>

        <tbody>
        <?php 
     if(count($details) <= 0 ){ ?>
            <tr>
                <td><i class="glyphicon glyphicon-sort" /></i><input type="hidden" name="project_serial[]" id="project_serial_id_<?=$class_count?>" value="<?=$id?>"  /></td>
                <td>
                    <input type="hidden" data-val="true"    data-val-required="حقل مطلوب"  name="class_ser_id[]" id="txt_class_ser_id_<?=$class_count?>" value="0" class="form-control">

                    <input class="form-control" name="class[]" id="i_txt_class_id_<?=$class_count?>" />
                    <input  type="hidden" name="class_id[]"  id="h_txt_class_id_<?= $class_count ?>" >
                </td>
                <td>
                    <input name="class_name[]" readonly data-val="true" data-val-required="required" class="form-control"  id="txt_class_id_<?=$class_count?>" />
                </td>
                <td>
                    <input name="request_amount[]" data-val="true" data-val-required="required" class="form-control" id="txt_request_amount_<?=$class_count?>" />
                </td>
                <td>
                    <select name="class_unit[]" class="form-control" id="unit_txt_class_id_<?=$class_count?>" >
					<option></option>
                            <?php foreach($class_unit as $unit) :?>
                                <option value="<?= $unit['CON_NO'] ?>"  ><?= $unit['CON_NAME'] ?></option>

                            <?php endforeach; ?>
					</select>
                </td>
                <td>
                    <select name="class_type[]" data-val="1" class="form-control" id="txt_class_type_<?=$class_count?>" >
					
                            <?php foreach($class_type as $type) :?>
                                <option value="<?= $type['CON_NO'] ?>"  ><?= $type['CON_NAME'] ?></option>

                            <?php endforeach; ?>
                </td>
				<td><input name="price[]" readonly data-val="true"  data-val-required="حقل مطلوب"     class="form-control"  id="price_class_id_<?= $class_count ?>" ></td>
				<td> <input name="pnotes[]"      class="form-control"  id="pnotes_<?= $class_count ?>" ></td>
            </tr>

        <?php
                $class_count++;
            }else if(count($details) > 0) {
                $class_count = 0;
                foreach($details as $row) {

                    ?>

                    <tr>

                          <td><i class="glyphicon glyphicon-sort" /></i><input type="hidden" name="project_serial[]" id="project_serial_id_<?=$class_count?>" value="<?=$id?>"  /></td>
                <td>
                    <input type="hidden" data-val="true"    data-val-required="حقل مطلوب"  name="class_ser_id[]" id="txt_class_ser_id_<?=$class_count?>" value="<?=$row['SER']?>" class="form-control">

                    <input class="form-control" name="class[]" id="i_txt_class_id_<?=$class_count?>" value="<?=$row['CLASS_ID']?>" />
                    <input  type="hidden" name="class_id[]"  id="h_txt_class_id_<?= $class_count ?>" value="<?= $row['CLASS_ID'] ?>" >
                </td>
                <td>
                    <input name="class_name[]" value="<?= $row['CLASS_ID_NAME'] ?>"  data-arabic="<?= $row['CLASS_ID_NAME'] ?>" data-english="<?= $row['CLASS_ID_NAME_EN'] ?>" readonly data-val="true" data-val-required="required" class="form-control"  id="txt_class_id_<?=$class_count?>" />
                </td>
                <td>
                    <input name="request_amount[]" data-val="true" data-val-required="required" class="form-control" id="txt_request_amount_<?=$class_count?>" value="<?= $row['AMOUNT'] ?>" />
                </td>
                <td>
                    <select name="class_unit[]" class="form-control" id="unit_txt_class_id_<?=$class_count?>" >
					<option></option>
                            <?php foreach($class_unit as $unit) :?>
                                <option value="<?= $unit['CON_NO'] ?>" <?PHP if ($unit['CON_NO']==$row['CLASS_UNIT']){ echo " selected"; } ?> ><?= $unit['CON_NAME'] ?></option>

                            <?php endforeach; ?>
					</select>
                </td>
                <td>
                    <select name="class_type[]" data-val="1" class="form-control" id="txt_class_type_<?=$class_count?>" >
					
                            <?php foreach($class_type as $type) :?>
                                <option value="<?= $type['CON_NO'] ?>"  <?PHP if ($type['CON_NO']==$row['CLASS_TYPE']){ echo " selected"; } ?>><?= $type['CON_NAME'] ?></option>

                            <?php endforeach; ?>
                </td>
				<td><input name="price[]" readonly data-val="true"  data-val-required="حقل مطلوب"     class="form-control"  id="price_class_id_<?= $class_count ?>" value="<?= $row['PRICE']   ?>"></td>
				<td> <input name="pnotes[]"      class="form-control"  id="pnotes_<?= $class_count ?>"  value="<?= $row['NOTES'] ?>"></td>


                    </tr>
                    <?php
                    $class_count++;

                }
            }
            ?>

        </tbody>
        <tfoot>
        <tr>
            <th></th>
            <th>
  
            </th>
            <th></th>
            <th></th>
            <th></th>
            
			   <th>
الإجمالي الكلي لنفقات            </th>
            <th id="total_price"></th>
			
			<th></th>
            <th></th>
        </tr>
		        <tr>
            <th></th>
            <th>
             <a onclick="javascript:add_row(this,'input:text,input:hidden[name^=class_ser_id]',false);" onfocus="javascript:add_row(this,'input:text,input:hidden[name^=class_ser_id]',false);" href="javascript:;" class="new"><i class="glyphicon glyphicon-plus"></i>جديد</a>

            </th>
            <th></th>
            <th></th>
            <th></th>
            
			   <th>
الإجمالي الكلي للإيرادات            </th>
            <th><?php
    if (count($rs) > 0)
    {
        if ($rs['INCOME'] == '') $PRINT_INCOME = 0;
        else $PRINT_INCOME = $rs['INCOME'];
    }

    ?>
    <div>
        <input  data-val="true"  data-val-required="حقل مطلوب"  class="form-control"  name="income" style="text-align:center;font-weight: bold;" id="income" value="<?php echo (count($rs) > 0) ? $PRINT_INCOME : 0; ?>" <?php echo $readonly; ?>/>
        <span class="field-validation-valid" data-valmsg-for="income" data-valmsg-replace="true"></span>
    </div></th>
			
			<th></th>
            <th></th>
        </tr>
        </tfoot>
    </table>
</div>

