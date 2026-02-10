<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 11/04/18
 * Time: 09:22 ص
 */
$MODULE_NAME= 'planning';
$TB_NAME= 'planning';
$isCreate =isset($target_details) && count($target_details)  > 0 ?false:true;
$count_target=0;

?>
<div class="tb_container">
<table class="table" id="t_details_tb1" data-container="container"  align="center">
<thead>
<tr>
    <th style="width: 15%">اسم المخرج(النتائج)</th>
    <th style="width: 10%">المستهدف من المشروع</th>
    <th style="width: 8%">الوحدة</th>
    <th style="width: 8%">الصيغة</th>
   
  
   
    <?php /*if ( HaveAccess($delete_url_details) && (!$isCreate )) :
    if($adopt==1)
    {
    ?>
        <th style="width: 3%">حذف</th>
    <?php }
    endif;*/ ?>
</tr>
</thead>

<tbody>
<?php if(count($target_details) <= 0) {  // ادخال ?>
    <tr>
        <td>
             <input class="form-control" name="t_result[]" id="txt_t_result_<?=$count_target?>"  data-val ="true"   data-val-required="حقل مطلوب"  />
             <input  type="hidden" name="h_t_activities_plan_ser[]"  id="h_t_activities_plan_ser_<?=$count_target?>" data-val="false" data-val-required="required" >
             <input type="hidden" name="t_seq[]" id="t_seq_id[]" value="0"  />
             <input type="hidden" name="h_t_count[]" id="h_t_count_<?=$count_target?>" />

            <span class="field-validation-valid" data-valmsg-for="t_result[]" data-valmsg-replace="true"></span>
        </td>
        <td>
            <input type="text"  data-val="true"  data-val-required="حقل مطلوب"   name="target[]" id="txt_target_<?=$count_target?>" class="form-control" dir="rtl" >
            <span class="field-validation-valid" data-valmsg-for="target[]" data-valmsg-replace="true"></span>
          
        </td>
        <td>
		<input type="text"  data-val="true"  data-val-required="حقل مطلوب"   name="unit[]" id="txt_unit_<?=$count_target?>" class="form-control" dir="rtl">
            <span class="field-validation-valid" data-valmsg-for="unit[]" data-valmsg-replace="true"></span>
            <!--
			<select name="unit[]" data-val="true"  data-val-required="حقل مطلوب"  id="dp_unit_<?=$count_target?>" class="form-control" >
                <option></option>
                <?php foreach($unit as $t_row) :?>
                    <option value="<?= $t_row['CON_NO'] ?>" >
                        <?= $t_row['CON_NAME'] ?></option>
                <?php endforeach; ?>

            </select>
            <span class="field-validation-valid" data-valmsg-for="unit[]" data-valmsg-replace="true"></span>
			-->
        </td>
        <td>
           <select name="scale[]" data-val="true"  data-val-required="حقل مطلوب"  id="dp_scale_<?=$count_target?>" class="form-control" >
                <option></option>
                <?php foreach($scale as $t_row) :?>
                    <option value="<?= $t_row['CON_NO'] ?>" >
                        <?= $t_row['CON_NAME'] ?></option>
                <?php endforeach; ?>

            </select>
            <span class="field-validation-valid" data-valmsg-for="scale[]" data-valmsg-replace="true"></span>
        </td>
       

       

        <?php /*if ( HaveAccess($delete_url_details) && (!$isCreate )) :
        if($adopt==1)
        {
        ?>
            <td>


            </td>
        <?php }
        endif; */?>
    </tr>
<?php
}else if(count($target_details) > 0) { // تعديل
    $count_target = -1;
    foreach($target_details as $t_row) {
        ++$count_target+1;

       

        ?>

        <tr>
            <td>
			<input class="form-control" name="t_result[]" id="txt_t_result_<?=$count_target?>"  data-val ="true"   data-val-required="حقل مطلوب" value="<?=$t_row['T_RESULT'];?>" />
             <input  type="hidden" name="h_t_activities_plan_ser[]"  id="h_t_activities_plan_ser_<?=$count_target?>" data-val="false" data-val-required="required" value="<?=$t_row['ACTIVITIES_PLAN_SER']?>">
             <input type="hidden" name="t_seq[]" id="t_seq_id[]"  value="<?=$t_row['T_SEQ']?>"   />
             <input type="hidden" name="h_t_count[]" id="h_t_count_<?=$count_target?>" />

            <span class="field-validation-valid" data-valmsg-for="t_result[]" data-valmsg-replace="true"></span>
				
            </td>
            <td>
            <input type="text"  data-val="true"  data-val-required="حقل مطلوب"   name="target[]" id="txt_target_<?=$count_target?>" class="form-control" dir="rtl" value="<?=$t_row['TARGET'];?>" >
            <span class="field-validation-valid" data-valmsg-for="target[]" data-valmsg-replace="true"></span>
          
        </td>
        <td>
            <!--
			<select name="unit[]" data-val="true"  data-val-required="حقل مطلوب"  id="dp_unit_<?=$count_target?>" class="form-control" >
                <option></option>
                <?php foreach($unit as $u_row) :?>
                    <option value="<?= $u_row['CON_NO'] ?>" <?PHP if ($u_row['CON_NO']==$t_row['UNIT']){ echo " selected"; } ?>>
                        <?= $u_row['CON_NAME'] ?></option>
                <?php endforeach; ?>

            </select>
            <span class="field-validation-valid" data-valmsg-for="unit[]" data-valmsg-replace="true"></span>
			-->
			<input type="text"  data-val="true"  data-val-required="حقل مطلوب"   name="unit[]" id="txt_unit_<?=$count_target?>" class="form-control" dir="rtl" value="<?=$t_row['UNIT'];?>">
            <span class="field-validation-valid" data-valmsg-for="unit[]" data-valmsg-replace="true"></span>
        </td>
        <td>
           <select name="scale[]" data-val="true"  data-val-required="حقل مطلوب"  id="dp_scale_<?=$count_target?>" class="form-control" >
                <option></option>
                <?php foreach($scale as $s_row) :?>
                    <option value="<?= $s_row['CON_NO'] ?>" <?PHP if ($s_row['CON_NO']==$t_row['SCALE']){ echo " selected"; } ?>>
                        <?= $s_row['CON_NAME'] ?></option>
                <?php endforeach; ?>

            </select>
            <span class="field-validation-valid" data-valmsg-for="scale[]" data-valmsg-replace="true"></span>
        </td>





            <?php /*if ( HaveAccess($delete_url_details) && (!$isCreate ) )  :
                if($adopt==1)
                {
                ?>

                <td>
                    <a onclick="javascript:delete_t_row_del('<?=$t_row['f_seq1']?>','<?=$t_row['ACTIVITY_NAME']?>');"  href='javascript:;'><i class='glyphicon glyphicon-remove'></i></a>


                </td>
            <?php } endif; */?>
        </tr>
    <?php

    }
}

?>
</tbody>

<tfoot>
<tr>
    <th></th>
    <th></th>
    <th></th>
    <th></th>
  


  
    <?php /*if ( HaveAccess($delete_url_details) && (!$isCreate )) : ?>
        <th></th>
    <?php endif;*/ ?>
</tr>
<tr>
    <th>
        <a onclick="javascript:add_row(this,'input:text,input:hidden[name^=t_seq],textarea,select',false);"  href="javascript:;"><i class="glyphicon glyphicon-plus"></i>جديد</a>
    </th>
    <th></th>
    <th></th>
    <th></th>
  
    
    <?php /*if ( HaveAccess($delete_url_details) && (!$isCreate )) : ?>
        <th></th>
    <?php endif;*/ ?>
</tr>

</tfoot>
</table></div>