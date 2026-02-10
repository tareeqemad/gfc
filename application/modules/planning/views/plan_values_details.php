<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 26/09/19
 * Time: 11:37 ص
 */
$P_Stratgic='StrategicPlan';
$MODULE_NAME= 'planning';
$TB_NAME= 'planning_unit';
$isCreate =isset($values_res) && count($values_res)  > 0 ?false:true;
$v_count=0;
?>
<div class="tb_container">
    <table class="table" id="details_tb1" data-container="container"  align="center">
        <thead>
        <tr>
            <th style="width:8%">رمز  القيمة</th>
            <th >اسم القيمة</th>
        </tr>
        </thead>
        <tbody>
        <?php if(count($values_res) <= 0) { ?>
            <tr>
                <td>
                    <input type="text" data-val="true"  data-val-required="حقل مطلوب" name="v_lable[]" id="txt_v_lable_<?=$v_count?>" class="form-control" dir="rtl" >
                    <span class="field-validation-valid" data-valmsg-for="v_lable[]" data-valmsg-replace="true"></span>
                    <input type="hidden" data-val="true"    data-val-required="حقل مطلوب"  name="v_id[]" id="txt_v_id_<?=$v_count?>" value="0" class="form-control">
                    <input type="hidden" data-val="true"    data-val-required="حقل مطلوب"  name="plan_no[]" id="txt_plan_no_<?=$v_count?>" value="<?=$id;?>" class="form-control">
                    <input type="hidden" name="h_count[]" id="h_count_<?=$v_count?>" />
                </td>

                <td>
                    <input type="text" data-val="true"  data-val-required="حقل مطلوب" name="v_name[]" id="txt_v_name_<?=$v_count?>" class="form-control" dir="rtl" >
                    <span class="field-validation-valid" data-valmsg-for="v_name[]" data-valmsg-replace="true"></span>
                </td>


            </tr>

            <?php
            $v_count++;
        }else if(count($values_res) > 0) {
            $v_count = 0;
            foreach($values_res as $row1) {

                ?>

                <tr>

                    <td>
                        <input type="text" data-val="true"  data-val-required="حقل مطلوب" name="v_lable[]" id="txt_v_lable_<?=$v_count?>" value="<?=$row1['V_LABLE']?>" class="form-control" dir="rtl" >
                        <span class="field-validation-valid" data-valmsg-for="v_lable[]" data-valmsg-replace="true"></span>
                        <input type="hidden" data-val="true"    data-val-required="حقل مطلوب"  name="v_id[]" id="txt_v_id_<?=$v_count?>" value="<?=$row1['SER']?>" class="form-control">
                        <input type="hidden" data-val="true"    data-val-required="حقل مطلوب"  name="plan_no[]" id="txt_plan_no_<?=$v_count?>" value="<?=$id;?>" class="form-control">
                        <input type="hidden" name="h_count[]" id="h_count_<?=$v_count?>" value="<?=$v_count?>" />
                    </td>

                    <td>
                        <input type="text" data-val="true"  data-val-required="حقل مطلوب" name="v_name[]" id="txt_v_name_<?=$v_count?>" value="<?=$row1['V_NAME']?>" class="form-control" dir="rtl" >
                        <span class="field-validation-valid" data-valmsg-for="v_name[]" data-valmsg-replace="true"></span>
                    </td>



                </tr>
            <?php
                $v_count++;

            }
        }

        ?>
        </tbody>

        <tfoot>
        <tr>
				<?php 
if($this->uri->segment(3)!=$P_Stratgic)
{
?>
            <th>
                <a onclick="javascript:add_row(this,'input:text,input:hidden[name^=v_id]',false);" onfocus="javascript:add_row(this,'input:text,input:hidden[name^=v_id]',false);" href="javascript:;" class="new"><i class="glyphicon glyphicon-plus"></i>جديد</a>
            </th>
			<?php }?>
                           <th>
            </th>
            
           </tr>

        </tfoot>
    </table></div>




