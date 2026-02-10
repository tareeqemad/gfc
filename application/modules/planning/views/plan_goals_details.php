<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 26/09/19
 * Time: 11:37 ص
 */

$MODULE_NAME= 'planning';
$TB_NAME= 'planning_unit';
$isCreate =isset($goals_res) && count($goals_res)  > 0 ?false:true;
$delete_url_details= base_url("$MODULE_NAME/$TB_NAME/delete_goals");
$HaveRs1 = (count($goals_res)  > 0)? true:false;
$count=0;

$P_Stratgic='StrategicPlan';

?>
<div class="tb_container">
    <table class="table" id="details_tb1" data-container="container"  align="center">
        <thead>
        <tr>
            <th style="width:8%">رمز المحور</th>
            <th >اسم المحور</th>
            <th style="width:8%" >الوزن النسبي</th>
           <!-- <th style="width:6%">حفظ</th>-->
            <?php if(count($goals_res) > 0) { ?>
                <th style="width:8%">الأهداف الإستراتجية (العامة)</th>
            <?php }?>
                <th style="width:8%">الأهداف الإستراتجية (الخاصة)</th>
				<?php 
if($this->uri->segment(3)!=$P_Stratgic)
{
?>
				    <?php if ( HaveAccess($delete_url_details) && ($HaveRs1 )) :
    if($HaveRs1)
    {
    ?>
        <th style="width: 3%">حذف</th>
    <?php }
    endif; ?>
	<?php }?>
            <!--<th style="width:6%">اعتماد</th>-->
        </tr>
        </thead>
        <tbody>
        <?php if(count($goals_res) <= 0) { ?>
            <tr>
                <td>
                    <input type="text" data-val="true"  data-val-required="حقل مطلوب" name="id_label[]" id="txt_id_label_<?=$count?>" class="form-control" dir="rtl" >
                    <span class="field-validation-valid" data-valmsg-for="id_label[]" data-valmsg-replace="true"></span>
                    <input type="hidden" data-val="true"    data-val-required="حقل مطلوب"  name="id[]" id="txt_id_<?=$count?>" value="0" class="form-control">
                    <input type="hidden" data-val="true"    data-val-required="حقل مطلوب"  name="plan_no[]" id="txt_plan_no_<?=$count?>" value="<?=$id;?>" class="form-control">
                    <input type="hidden" name="h_count[]" id="h_count_<?=$count?>" />
                </td>

                <td>
                    <input type="text" data-val="true"  data-val-required="حقل مطلوب" name="id_name[]" id="txt_id_name_<?=$count?>" class="form-control" dir="rtl" >
                    <span class="field-validation-valid" data-valmsg-for="id_name[]" data-valmsg-replace="true"></span>
                </td>
                <td>
                    <input class="form-control" name="weight[]" id="txt_weight_<?=$count?>" data-val="true"  data-val-required="حقل مطلوب"  />
                    <span class="field-validation-valid" data-valmsg-for="weight[]" data-valmsg-replace="true"></span>

                </td>
								<?php 
if($this->uri->segment(3)!=$P_Stratgic)
{
?>
				         <?php if ( HaveAccess($delete_url_details) && ($HaveRs1 )) :
    if($HaveRs1)
    {
        ?>
            <td>


            </td>
        <?php }
        endif; ?>
		<?php }?>
               <!-- <td>

                    <button type="button" id="btn_save_<?=$count?>" class="btn btn-primary" name="save[]">حفظ</button>
                </td>
                <td>
                    <button type="button" id="btn_active_<?=$count?>" class="btn btn-warning hidden" data-ser1='<?=@$row['SEQ']?>'  name="active[]">الاهداف الاستراتيجية</button>
                </td>

                <td>
                    <button type="button" id="btn_adopt_<?=$count?>" class="btn btn-info hidden" name="adopt[]">اعتماد</button>

                </td>-->

            </tr>

            <?php
            $count++;
        }else if(count($goals_res) > 0) {
            $count = 0;
            foreach($goals_res as $row1) {

                ?>

                <tr>

                    <td>
                        <input type="text" data-val="true"  data-val-required="حقل مطلوب" name="id_label[]" id="txt_id_label_<?=$count?>" value="<?=$row1['ID_LABEL']?>" class="form-control" dir="rtl" >
                        <span class="field-validation-valid" data-valmsg-for="id_label[]" data-valmsg-replace="true"></span>
                        <input type="hidden" data-val="true"    data-val-required="حقل مطلوب"  name="id[]" id="txt_id_<?=$count?>" value="<?=$row1['ID']?>" class="form-control">
                        <input type="hidden" data-val="true"    data-val-required="حقل مطلوب"  name="plan_no[]" id="txt_plan_no_<?=$count?>" value="<?=$id;?>" class="form-control">
                        <input type="hidden" name="h_count[]" id="h_count_<?=$count?>" value="<?=$count?>" />
                    </td>

                    <td>
                        <input type="text" data-val="true"  data-val-required="حقل مطلوب" name="id_name[]" id="txt_id_name_<?=$count?>" value="<?=$row1['ID_NAME']?>" class="form-control" dir="rtl" >
                        <span class="field-validation-valid" data-valmsg-for="id_name[]" data-valmsg-replace="true"></span>
                    </td>
                    <td>
                        <input class="form-control" name="weight[]" id="txt_weight_<?=$count?>" value="<?=$row1['WEIGHT']?>" data-val="true"  data-val-required="حقل مطلوب"  />
                        <span class="field-validation-valid" data-valmsg-for="weight[]" data-valmsg-replace="true"></span>
                    </td>
                    <!--
                    <td>

                        <button type="button" id="btn_save_<?=$count?>" class="btn btn-primary" name="save[]">حفظ</button>
                    </td> -->
                    <td>
                        <i class="fa fa-file" onclick="show_doc(<?=$row1['ID']?>,<?=$id?>);"></i>

                    </td>

                    <td>
                        <i class="fa fa-file" onclick="show_doc_spefic(<?=$row1['ID']?>,<?=$id?>);"></i>

                    </td>
					        				<?php 
if($this->uri->segment(3)!=$P_Stratgic)
{
?>
				         <?php if ( HaveAccess($delete_url_details) && ($HaveRs1 )) :
    if($HaveRs1)
    {
        ?>

                <td>
                    <a onclick="javascript:delete_row_del('<?=$row1['ID']?>','<?=$row1['ID_NAME']?>');"  href='javascript:;'><i class='glyphicon glyphicon-remove'></i></a>


                </td>
            <?php } endif; ?>

<?php }?>
                </tr>
            <?php
                $count++;

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
                <a onclick="javascript:add_row(this,'input:text,input:hidden[name^=id]',false);" onfocus="javascript:add_row(this,'input:text,input:hidden[name^=id]',false);" href="javascript:;" class="new"><i class="glyphicon glyphicon-plus"></i>جديد</a>
            </th>
			<?php 
			}
			?>
                           <th>
                الاجمالي الكلي لاوزان
            </th>
            <th id="total_weight"></th>
            <?php if(count($goals_res) > 0) { ?>
            <th ></th>
            <?php } ?>
            <th ></th>
							<?php 
if($this->uri->segment(3)!=$P_Stratgic)
{
?>
            
			 				         <?php if ( HaveAccess($delete_url_details) && ($HaveRs1 )) :
    if($HaveRs1)
    {
        ?>
            <th ></th> 
        <?php }
        endif; ?>
		<?php }?>
           </tr>

        </tfoot>
    </table></div>




