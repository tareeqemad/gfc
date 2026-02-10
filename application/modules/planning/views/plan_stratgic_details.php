<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 29/03/18
 * Time: 12:12 م
 */

$MODULE_NAME= 'planning';
$TB_NAME= 'planning_unit';
$P_Stratgic='StrategicPlan';
$isCreate =isset($child_objective) && count($child_objective)  > 0 ?false:true;
$delete_url_details= base_url("$MODULE_NAME/$TB_NAME/delete_spefic_objective");
$HaveRs1 = (count($child_objective)  > 0)? true:false;
$child_count=0;

/*$isCreate =isset($child_objective) && count($child_objective)  > 0 ?false:true;
$delete_url_details= base_url("$MODULE_NAME/$TB_NAME/delete_details");
$child_count_distrbute=0;
*/
$save_part_url= base_url("$MODULE_NAME/$TB_NAME/create_edit_stratgic_goals");
//var_dump($child_objective);
//echo $id;
?>

    <div class="tb_container">
        <table class="table" id="details_tb1" data-container="container"  align="center">
            <thead>
            <tr>

                <th style="width:20%">الهدف الإستراتيجي(العام)</th>
                <th style="width:4%">رمز الهدف الإستراتيجي (الخاص)</th>
                <th style="width:20%">اسم الهدف الإستراتيجي(الخاص)</th>
                <th style="width:3%" >الوزن النسبي</th>
								<?php 
if($refrence!=$P_Stratgic)
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
            </tr>
            </thead>
            <tbody>
            <?php if(count($child_objective) <= 0) { ?>
                <tr>

                    <td>
                        <select name="id_father_name[]" data-val="true"  data-val-required="حقل مطلوب"  id="dp_id_father_name_<?=$child_count?>" class="form-control">
                            <option></option>
                            <?php foreach($details as $row20) :?>
                                <option value="<?= $row20['ID'] ?>"  ><?= $row20['ID_NAME'] ?></option>

                            <?php endforeach; ?>

                        </select>
                        <input type="hidden" name="h_count[]" id="h_count_<?=$child_count?>" />
                    </td>
                    <td>
                        <input type="text" data-val="true"  data-val-required="حقل مطلوب" name="id_label[]" id="txt_id_label_<?=$child_count?>" class="form-control" dir="rtl" >
                        <span class="field-validation-valid" data-valmsg-for="id_label[]" data-valmsg-replace="true"></span>
                        <input type="hidden" data-val="true"    data-val-required="حقل مطلوب"  name="id[]" id="txt_id_<?=$child_count?>" value="0" class="form-control">
                        <input type="hidden" data-val="true"    data-val-required="حقل مطلوب"  name="plan_no[]" id="txt_plan_no_<?=$child_count?>" value="<?=$plan;?>" class="form-control">
                        <!-- <input type="hidden" data-val="true"    data-val-required="حقل مطلوب"  name="id_father[]" id="txt_id_father_<?=$child_count?>" value="<?=$id;?>" class="form-control"> -->
                        <input type="hidden" name="h_count[]" id="h_count_<?=$child_count?>" />
                    </td>

                    <td>
                        <input type="text" data-val="true"  data-val-required="حقل مطلوب" name="id_name[]" id="txt_id_name_<?=$child_count?>" class="form-control" dir="rtl" >
                        <span class="field-validation-valid" data-valmsg-for="id_name[]" data-valmsg-replace="true"></span>
                    </td>
                    <td>
                        <input class="form-control" name="weight[]" id="txt_weight_<?=$child_count?>" data-val="true"  data-val-required="حقل مطلوب"  />
                        <span class="field-validation-valid" data-valmsg-for="weight[]" data-valmsg-replace="true"></span>

                    </td>
									<?php 
if($refrence!=$P_Stratgic)
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

                    <button type="button" id="btn_save_<?=$child_count?>" class="btn btn-primary" name="save[]">حفظ</button>
                </td>
                <td>
                    <button type="button" id="btn_active_<?=$child_count?>" class="btn btn-warning hidden" data-ser1='<?=@$row['SEQ']?>'  name="active[]">الاهداف الاستراتيجية</button>
                </td>

                <td>
                    <button type="button" id="btn_adopt_<?=$child_count?>" class="btn btn-info hidden" name="adopt[]">اعتماد</button>

                </td>-->

                </tr>

                <?php
                $child_count++;
            }else if(count($child_objective) > 0) {
                $child_count = 0;
                foreach($child_objective as $row10) {

                    ?>

                    <tr>

                        <td>
                            <select name="id_father_name[]" data-val="true"  data-val-required="حقل مطلوب"  id="dp_id_father_name_<?=$child_count?>" class="form-control">
                                <option></option>
                                <?php foreach($details as $row20) :?>
                                    <option value="<?= $row20['ID'] ?>" <?PHP if ($row20['ID']==$row10['ID_FATHER']){ echo " selected"; } ?> ><?= $row20['ID_NAME'] ?></option>

                                <?php endforeach; ?>

                            </select>
                            <input type="hidden" name="h_count[]" id="h_count_<?=$child_count?>" />
                        </td>
                        <td>
                            <input type="text" data-val="true"  data-val-required="حقل مطلوب" name="id_label[]" id="txt_id_label_<?=$child_count?>" value="<?=$row10['ID_LABEL']?>" class="form-control" dir="rtl" >
                            <span class="field-validation-valid" data-valmsg-for="id_label[]" data-valmsg-replace="true"></span>
                            <input type="hidden" data-val="true"    data-val-required="حقل مطلوب"  name="id[]" id="txt_id_<?=$child_count?>" value="<?=$row10['ID']?>" class="form-control">
                            <input type="hidden" data-val="true"    data-val-required="حقل مطلوب"  name="plan_no[]" id="txt_plan_no_<?=$child_count?>" value="<?=$plan;?>" class="form-control">
                            <!-- <input type="hidden" data-val="true"    data-val-required="حقل مطلوب"  name="id_father[]" id="txt_id_father_<?=$child_count?>" value="<?=$id;?>" class="form-control"> -->
                            <input type="hidden" name="h_count[]" id="h_count_<?=$child_count?>" value="<?=$child_count?>" />
                        </td>

                        <td>
                            <input type="text" data-val="true"  data-val-required="حقل مطلوب" name="id_name[]" id="txt_id_name_<?=$child_count?>" value="<?=$row10['ID_NAME']?>" class="form-control" dir="rtl" >
                            <span class="field-validation-valid" data-valmsg-for="id_name[]" data-valmsg-replace="true"></span>
                        </td>
                        <td>
                            <input class="form-control" name="weight[]" id="txt_weight_<?=$child_count?>" value="<?=$row10['WEIGHT']?>" data-val="true"  data-val-required="حقل مطلوب"  />
                            <span class="field-validation-valid" data-valmsg-for="weight[]" data-valmsg-replace="true"></span>
                        </td>
										<?php 
if($refrence!=$P_Stratgic)
{
?>
  <?php if ( HaveAccess($delete_url_details) && ($HaveRs1 )) :
    if($HaveRs1)
    {
        ?>

                <td>
                    <a onclick="javascript:delete_row_del('<?=$row10['ID']?>','<?=$row10['ID_NAME']?>');"  href='javascript:;'><i class='glyphicon glyphicon-remove'></i></a>


                </td>
            <?php } endif; ?>
			<?php }?>

                    </tr>
                    <?php
                    $child_count++;

                }
            }

            ?>
            </tbody>

            <tfoot>
            <tr>
				<?php 
if($refrence!=$P_Stratgic)
{
?>
                <th>
                    <a onclick="javascript:add_row(this,'input:text,input:hidden[name^=id],select',false);" onfocus="javascript:add_row(this,'input:text,input:hidden[name^=id],select',false);" href="javascript:;" class="new"><i class="glyphicon glyphicon-plus"></i>جديد</a>
                </th>
				<?php }?>
                <th></th>

                <th>
                    الاجمالي الكلي لاوزان
                </th>
                <th id="total_weight"></th>
								<?php 
if($refrence!=$P_Stratgic)
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




</div>
				<?php 
if($refrence!=$P_Stratgic)
{
?>
<div class="modal-footer">
   <?php /*if ( $isCreate or $child_objective[0]['ADOPT'] == 1) : */?>
    <button type="button" data-action="submit" class="btn btn-primary save_part">حفظ البيانات</button>
   <!-- <button type="button" data-action="submit" class="btn btn-info save_part">اعتماد</button>-->
   <?php /*endif;*/ ?>

</div>
<?php }?>


<?php
$scripts = <<<SCRIPT
<script type="text/javascript">
///////////////
function delete_row_del(id, name) {
  if (confirm(' هل تريد بالتأكيد حذف  '+name+' ؟!!')) {

        var values = {id: id};
        get_data('{$delete_url_details}', values, function (data) {

            if (data == 1) {

                success_msg('تمت عملية الحذف بنجاح');
                get_to_link(window.location.href);
            }

            else {
                danger_msg('لم يتم الحذف', data);
            }

        }, 'html');

    }

}
reBind_pram(0);
        calcall();

$('.save_part').on('click',  function (e) {
/*

                                      if($('#total_weight').text()!=100)
{
 danger_msg('لم يتم الحفظ يجب ان يكون المجوع الكلي للاوزان = 100');
}
else
{*/

        var url = '{$save_part_url}';
        var tbl = '#details_tb1';
        var container = $('#' + $(tbl).attr('data-container'));
         var id_label = [];
         var id = [];
         var plan_no = [];
         var id_name = [];
         var id_father = [];
         var weight =[];

        $('input[name="id_name[]"]').each(function (i) {
        if($(this).closest('tr').find('input[name="id_name[]"]').val() !='')
        {
          id_label[i]=$(this).closest('tr').find('input[name="id_label[]"]').val();
          id[i]=$(this).closest('tr').find('input[name="id[]"]').val();
          plan_no[i]={$plan};
          id_name[i]=$(this).closest('tr').find('input[name="id_name[]"]').val();
          id_father[i]=$(this).closest('tr').find('select[name="id_father_name[]"]').val();;
          weight[i]=$(this).closest('tr').find('input[name="weight[]"]').val();

        }
        });

      if(id_name.length > 0){
      get_data(url,{id:id,id_label:id_label,plan_no:plan_no,id_name:id_name,id_father:id_father,weight:weight},function(data){
             if(data>=1)
             {

            success_msg('رسالة','تم حفظ البيانات بنجاح ..');
             get_to_link(window.location.href);



             }

                 else
                  danger_msg('لم يتم الحفظ', data);


            });


        }else
            alert('لا يوجد سجلات ممكن حفظها');
       // }

        });

        function calcall() {

    var total_weight = 0;

    $('input[name="weight[]"]').each(function () {

        var weight = $(this).closest('tr').find('input[name="weight[]"]').val();
        total_weight += Number(weight);
        if(Number(total_weight)>100)
                {
                    danger_msg('لقد تجاوزت الحد المسموح');
                    $(this).closest('tr').find('input[name="weight[]"]').val(0);
                }
                else

        $('#total_weight').text(isNaNVal(Number(total_weight)));
    });



}
function reBind_pram(cnt){
$('.select2-container',$('#details_tb1')).remove();

$('select[name="id_father_name[]"]').select2().on('change',function(){

       //  checkBoxChanged();

        });

 $('input[name="weight[]"]').keyup(function (e) {
 calcall();
    });
    }
</script>

SCRIPT;

sec_scripts($scripts);

?>


