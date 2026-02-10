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
$isCreate =isset($details) && count($details)  > 0 ?false:true;
$delete_url_details= base_url("$MODULE_NAME/$TB_NAME/delete_objective");
$HaveRs1 = (count($details)  > 0)? true:false;
$count=0;

/*$isCreate =isset($details) && count($details)  > 0 ?false:true;
$delete_url_details= base_url("$MODULE_NAME/$TB_NAME/delete_details");
$count_distrbute=0;
*/
$save_part_url= base_url("$MODULE_NAME/$TB_NAME/create_edit_stratgic_goals");

//var_dump($details);
//echo $id;
?>

    <div class="tb_container">
        <table class="table" id="details_tb1" data-container="container"  align="center">
            <thead>
            <tr>
                <th style="width:20%">رمز الهدف الإستراتيجي</th>
                <th >اسم الهدف الإستراتيجي</th>
                <th style="width:20%" >الوزن النسبي</th>
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
            <?php if(count($details) <= 0) { ?>
                <tr>
                    <td>
                        <input type="text" data-val="true"  data-val-required="حقل مطلوب" name="id_label[]" id="txt_id_label_<?=$count?>" class="form-control" dir="rtl" >
                        <span class="field-validation-valid" data-valmsg-for="id_label[]" data-valmsg-replace="true"></span>
                        <input type="hidden" data-val="true"    data-val-required="حقل مطلوب"  name="id[]" id="txt_id_<?=$count?>" value="0" class="form-control">
                        <input type="hidden" data-val="true"    data-val-required="حقل مطلوب"  name="plan_no[]" id="txt_plan_no_<?=$count?>" value="<?=$plan;?>" class="form-control">
                        <input type="hidden" data-val="true"    data-val-required="حقل مطلوب"  name="id_father[]" id="txt_id_father_<?=$count?>" value="<?=$id;?>" class="form-control">
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
            }else if(count($details) > 0) {
                $count = 0;
                foreach($details as $row1) {

                    ?>

                    <tr>

                        <td>
                            <input type="text" data-val="true"  data-val-required="حقل مطلوب" name="id_label[]" id="txt_id_label_<?=$count?>" value="<?=$row1['ID_LABEL']?>" class="form-control" dir="rtl" >
                            <span class="field-validation-valid" data-valmsg-for="id_label[]" data-valmsg-replace="true"></span>
                            <input type="hidden" data-val="true"    data-val-required="حقل مطلوب"  name="id[]" id="txt_id_<?=$count?>" value="<?=$row1['ID']?>" class="form-control">
                            <input type="hidden" data-val="true"    data-val-required="حقل مطلوب"  name="plan_no[]" id="txt_plan_no_<?=$count?>" value="<?=$plan;?>" class="form-control">
                            <input type="hidden" data-val="true"    data-val-required="حقل مطلوب"  name="id_father[]" id="txt_id_father_<?=$count?>" value="<?=$id;?>" class="form-control">
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
										<?php 
if($refrence!=$P_Stratgic)
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
if($refrence!=$P_Stratgic)
{
?>
                <th>
                    <a onclick="javascript:add_row(this,'input:text,input:hidden[name^=id]',false);" onfocus="javascript:add_row(this,'input:text,input:hidden[name^=id]',false);" href="javascript:;" class="new"><i class="glyphicon glyphicon-plus"></i>جديد</a>
                </th>
				<?php }?>
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
    <?php if ( $isCreate or $details[0]['ADOPT'] == 1) : ?>
    <button type="button" data-action="submit" class="btn btn-primary save_part">حفظ البيانات</button>
   <!-- <button type="button" data-action="submit" class="btn btn-info save_part">اعتماد</button>-->
    <?php endif; ?>

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

                                      if($('#total_weight').text()!=100)
{
 danger_msg('لم يتم الحفظ يجب ان يكون المجوع الكلي للاوزان = 100');
}
else
{

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
          id_father[i]={$id};
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
        }

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

 $('input[name="weight[]"]').keyup(function (e) {
 calcall();
    });
    }
</script>

SCRIPT;

sec_scripts($scripts);

?>


