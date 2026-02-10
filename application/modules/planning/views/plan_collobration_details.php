<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 18/03/18
 * Time: 09:57 ص
 */

$MODULE_NAME= 'planning';
$TB_NAME= 'planning';
$isCreate =isset($details) && count($details)  > 0 ?false:true;
$delete_url_details= base_url("$MODULE_NAME/$TB_NAME/delete_details");
$count_distrbute=0;
$save_part_url= base_url("$MODULE_NAME/$TB_NAME/create_edit_part");
$manage_follow_branch =base_url("$MODULE_NAME/$TB_NAME/public_get_mange");
//var_dump($details);
//echo $id;
?>


<div class="tb_container">
    <table class="table" id="details_tb1" data-container="container"  align="center">
        <thead>
        <tr>
            <th >مقر التنفيذ</th>
            <th >جهة التنفيذ/ادارة</th>
            <th >اسم المشروع/نشاط</th>
            <th >الوزن النسبي</th>
            <?php if ( HaveAccess($delete_url_details) && (!$isCreate )) : ?>
                <th >حذف</th>
            <?php endif; ?>
        </tr>
        </thead>

        <tbody>
        <?php if(count($details) <= 0) {  // ادخال ?>
            <tr>
                <td>

                    <select name="branch[]" id="dp_branch_<?=$count_distrbute?>" data-curr="false" class="form-control">
                        <option  value="">-</option>
                        <?php  foreach ($branches as $row1) : ?>
                            <option  value="<?= $row1['NO'] ?>" ><?= $row1['NAME'] ?></option>
                        <?php endforeach; ?>
                    </select>

                </td>
                <td>

                    <select name="manage_exe_id[]" id="dp_manage_exe_id_<?=$count_distrbute?>" data-curr="false" class="form-control">
                        <option  value="">-</option>
                        <?php  foreach ($branches as $row1) : ?>
                            <option  value="<?= $row1['NO'] ?>" ><?= $row1['NAME'] ?></option>
                        <?php endforeach; ?>
                    </select>

                </td>
                <td>
                    <input class="form-control" name="part_dist_[]" id="txt_part_<?=$count_distrbute?>" data-val="false" data-val-required="required" />
                    <input  type="hidden" name="h_txt_activity_no_id[]"  id="h_txt_activity_no_id_<?= $count_distrbute ?>" value="<?=$id?>" data-val="false" data-val-required="required" >
                    <input type="hidden" name="ser1[]" id="ser1_id[]" value="0"  />

                </td>
                <td>
                    <input class="form-control" name="part_dist[]" id="txt_part_<?=$count_distrbute?>" data-val="false" data-val-required="required" value="0" />
                    <input  type="hidden" name="h_txt_activity_no_id[]"  id="h_txt_activity_no_id_<?= $count_distrbute ?>" value="<?=$id?>" data-val="false" data-val-required="required" >
                    <input type="hidden" name="ser1[]" id="ser1_id[]" value="0"  />

                </td>

                <?php if ( HaveAccess($delete_url_details) && (!$isCreate )) : ?>
                    <td>


                    </td>
                <?php endif; ?>
            </tr>
        <?php
        }else if(count($details) > 0) { // تعديل
            $count_distrbute = -1;
            foreach($details as $row) {
                ++$count_distrbute+1


                ?>

                <tr>
                    <td>

                        <select name="branch[]" id="dp_branch_<?=$count_distrbute?>" data-curr="false" class="form-control">
                            <option  value="">-</option>
                            <?php  foreach ($branches as $row1) : ?>
                                <option  value="<?= $row1['NO'] ?>" <?PHP if ($row1['NO']==$row['BRANCH']){ echo " selected"; } ?>><?= $row1['NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>

                    </td>
                    <td>
                        <input class="form-control" name="part_dist[]" value="<?=$row['PART']?>" id="txt_part_<?=$count_distrbute?>" data-val="false" data-val-required="required" />
                        <input  type="hidden" name="h_txt_activity_no_id[]" value="<?=$row['ACTIVITY_NO']?>" id="h_txt_activity_no_id_<?= $count_distrbute ?>" data-val="false" data-val-required="required" >
                        <input type="hidden" name="ser1[]" id="ser1_id_<?= $count_distrbute ?>" value="<?=$row['SEQ1']?>" />
                    </td>
                    <?php if ( HaveAccess($delete_url_details) && (!$isCreate )) : ?>
                        <td>
                            <a onclick="javascript:delete_row_del('<?=$row['SEQ1']?>','<?=$row['BRANCH_NAME']?>');"  href='javascript:;'><i class='glyphicon glyphicon-remove'></i></a>

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


            <th></th>
            <th></th>
            <th>
                الاجمالي الكلي لاوزان
            </th>
            <th id="total_part_branches"></th>
            <?php if ( HaveAccess($delete_url_details) && (!$isCreate )) : ?>
                <th></th>
            <?php endif; ?>
        </tr>
        <tr>
            <th>
                <a onclick="javascript:add_row(this,'input:text,input:hidden[name^=ser1],textarea,select',false);" onfocus="javascript:add_row(this,'input:text,input:hidden[name^=ser1],textarea,select',false);" href="javascript:;"><i class="glyphicon glyphicon-plus"></i>جديد</a>
            </th>
            <th></th>

            <th></th>
            <th></th>
            <?php if ( HaveAccess($delete_url_details) && (!$isCreate )) : ?>
                <th></th>
            <?php endif; ?>
        </tr>

        </tfoot>
    </table></div>

<div class="modal-footer">
    <button type="button" data-action="submit" class="btn btn-primary save_part">حفظ البيانات</button>
    <button type="button" onclick="javascript:return_adopt(1);" class="btn btn-danger">اعتماد</button>


</div>


<?php
$scripts = <<<SCRIPT
<script type="text/javascript">


                              $('.save_part').on('click',  function (e) {

        var url = '{$save_part_url}';
        var tbl = '#details_tb1';
        var container = $('#' + $(tbl).attr('data-container'));
         var branch = [];
         var part = [];
         var activity=[];
         var ser1=[];
         var act_ser;


        $('select[name="branch[]"]').each(function (i) {

           branch[i]=$(this).closest('tr').find('select[name="branch[]"]').val();
           part[i]=$(this).closest('tr').find('input[name="part_dist[]"]').val();
           activity[i]=$(this).closest('tr').find('input[name="h_txt_activity_no_id[]"]').val();
           ser1[i]=$(this).closest('tr').find('input[name="ser1[]"]').val();
           act_ser=$(this).closest('tr').find('input[name="h_txt_activity_no_id[]"]').val();






    });



     if(branch.length > 0){
      var arr_data = [branch,part,activity,ser1];
      console.log(arr_data);


              get_data(url,{ser1:ser1,activity_no_id: activity, branch: branch,part:part},function(data){
             if(data>1)
             {

             alert('تم عملية الحفظ بنجاح');
             get_to_link(window.location.href);



             }

                 else
                  danger_msg('لم يتم الحفظ', data);


            });


        }else
            alert('لا يوجد سجلات ممكن حفظها');

        });

function delete_row_del(id, branch_name) {
  if (confirm(' هل تريد بالتأكيد حذف حصة '+ branch_name +' ؟!!')) {

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



function calcall() {

    var total_part_branches = 0;

    $('input[name="part_dist[]"]').each(function () {

        var part = $(this).closest('tr').find('input[name="part_dist[]"]').val();
        total_part_branches += Number(part);
        if(Number(total_part_branches)>100)
                {
                    danger_msg('لقد تجاوزت الحد المسموح');
                    $(this).closest('tr').find('input[name="part_dist[]"]').val(0);
                }
                else

        $('#total_part_branches').text(isNaNVal(Number(total_part_branches)));
    });



}
function reBind_pram(cnt){
$('.select2-container').remove();




 $('input[name="part_dist[]"]').keyup(function (e) {

e.preventDefault();

  calcall();
    });


      $('select[name="branch[]"]').select2().on('change',function(){
if($(this).closest('tr').find('input[name="part_dist[]"]').val()=='')
{
$(this).closest('tr').find('input[name="part_dist[]"]').val(0);
}

            get_data('{$manage_follow_branch}',{no: $(this).val()},function(data){

             $('#dp_manage_exe_id_'+cnt).html('');
             $('#dp_manage_exe_id_'+cnt).append('<option></option>');
            // $('#dp_manage_exe_id_'+cnt).select2('val','');
             //$('#dp_cycle_exe_id_'+cnt).select2('val','');

            $.each(data,function(index, item)
            {

             $('#dp_manage_exe_id_'+cnt).append('<option value=' + item.ST_ID + '>' + item.ST_NAME + '</option>');
            });
            });



        });
        $('#dp_manage_exe_id').select2().on('change',function(){

        });


         $('select[name="manage_exe_id[]"]').select2().on('change',function(){




       //  checkBoxChanged();


        });


}




</script>

SCRIPT;

sec_scripts($scripts);

?>


