<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 18/03/18
 * Time: 11:51 ص
 */

$MODULE_NAME= 'planning';
$TB_NAME= 'planning';
$isCreate =isset($details) && count($details)  > 0 ?false:true;
$delete_url_details= base_url("$MODULE_NAME/$TB_NAME/delete_sub_details");
$count_activity=0;
$save_active_url= base_url("$MODULE_NAME/$TB_NAME/create_edit_sub_activites");
$manage_follow_branch =base_url("$MODULE_NAME/$TB_NAME/public_get_mange");
$cycle_exe_branch =base_url("$MODULE_NAME/$TB_NAME/public_get_cycle");
$dep_exe_branch =base_url("$MODULE_NAME/$TB_NAME/public_get_dep");

//var_dump($details);
//echo $id;
?>


<div class="tb_container">
    <table class="table" id="details_tb1" data-container="container"  align="center">
        <thead>
        <tr>
            <th style="width: 8%">الجهة</th>
            <th style="width: 8%">المقر</th>
            <th style="width: 15%">الادارة</th>
            <th style="width: 15%">الدائرة</th>
            <th style="width: 15%">القسم</th>
            <th >اسم المشروع/نشاط</th>
            <th style="width: 8%">من الشهر</th>
            <th style="width: 8%">الى شهر</th>
            <th style="width: 8%">الوزن النسبي</th>
            <?php if ( HaveAccess($delete_url_details) && (!$isCreate )) : ?>
                <th style="width: 3%">حذف</th>
            <?php endif; ?>
        </tr>
        </thead>

        <tbody>
        <?php if(count($details) <= 0) {  // ادخال ?>
            <tr>
                <td>
                    <select name="planning_dir[]" data-val="true"  data-val-required="حقل مطلوب"  id="dp_planning_dir_<?=$count_activity?>" class="form-control">
                        <option></option>
                        <?php foreach($planning_dir as $row) :?>
                            <option value="<?= $row['CON_NO'] ?>"  ><?= $row['CON_NAME'] ?></option>

                        <?php endforeach; ?>

                    </select>
                    <input  type="hidden" name="h_txt_activities_plan_ser[]"  id="h_txt_activities_plan_ser_<?=$count_activity?>" value="<?=$id?>" data-val="false" data-val-required="required" >
                    <input type="hidden" name="seq1[]" id="seq1_id[]" value="0"  />
                    <input type="hidden" name="h_count[]" id="h_count_<?=$count_activity?>" />


                </td>
                <td>
                    <select name="branch[]" data-val="true"  data-val-required="حقل مطلوب"  id="dp_branch_<?=$count_activity?>" class="form-control">
                        <option></option>
                        <?php foreach($branches as $row) :?>
                            <option value="<?= $row['NO'] ?>" ><?= $row['NAME'] ?></option>


                        <?php endforeach; ?>


                    </select>
                    <!-- <span class="field-validation-valid" data-valmsg-for="type[]" data-valmsg-replace="true"></span>-->
                </td>
                <td>
                    <select name="manage_id[]" data-val="true"  data-val-required="حقل مطلوب"  id="dp_manage_id_<?= $count_activity ?>" class="form-control">
                        <option></option>
                        <?php foreach($b as $row) :?>
                            <option value="<?= $row['ST_ID'] ?>" ><?= $row['ST_NAME'] ?></option>


                        <?php endforeach; ?>

                    </select>
                    <!-- <span class="field-validation-valid" data-valmsg-for="type[]" data-valmsg-replace="true"></span>-->
                </td>
                <td>
                    <select name="cycle_id[]" data-val="true"  data-val-required="حقل مطلوب"  id="dp_cycle_id_<?= $count_activity ?>" class="form-control">
                        <option></option>
                        <?php foreach($cycle_exe as $row) :?>
                            <option value="<?= $row['ST_ID'] ?>"><?= $row['ST_NAME'] ?></option>


                        <?php endforeach; ?>

                    </select>
                    <!-- <span class="field-validation-valid" data-valmsg-for="type[]" data-valmsg-replace="true"></span>-->
                </td>
                <td>
                    <select name="department_id[]" data-val="true"  data-val-required="حقل مطلوب"  id="dp_department_id_<?= $count_activity ?>" class="form-control">
                        <option></option>
                        <?php foreach($dep_exe as $row) :?>
                            <option value="<?= $row['ST_ID'] ?>"><?= $row['ST_NAME'] ?></option>


                        <?php endforeach; ?>

                    </select>
                    <!-- <span class="field-validation-valid" data-valmsg-for="type[]" data-valmsg-replace="true"></span>-->
                </td>

                <td>
                    <input class="form-control" name="activity_name[]" id="txt_activity_name_<?=$count_activity?>" data-val="false" data-val-required="required" />


                </td>
                <td>

                    <select name="from_month[]" id="dp_from_month_<?=$count_activity?>" data-curr="false" class="form-control">
                        <option  value="">-</option>
                        <?php for($i = 1; $i <= 12 ;$i++) :?>
                            <option value="<?= $i ?>"><?= months($i) ?></option>
                        <?php endfor; ?>

                    </select>

                </td>
                <td>

                    <select name="to_month[]" id="dp_to_month_<?=$count_activity?>" data-curr="false" class="form-control">
                        <option  value="">-</option>
                        <?php for($i = 1; $i <= 12 ;$i++) :?>
                            <option value="<?= $i ?>"><?= months($i) ?></option>
                        <?php endfor; ?>

                    </select>

                </td>
                <td>
                    <input class="form-control" name="weight[]" id="txt_weight_<?=$count_activity?>" data-val="false" data-val-required="required" />

                </td>

                <?php if ( HaveAccess($delete_url_details) && (!$isCreate )) : ?>
                    <td>


                    </td>
                <?php endif; ?>
            </tr>
        <?php
        }else if(count($details) > 0) { // تعديل
            $count_activity = -1;
            foreach($details as $row) {
                ++$count_activity+1;

                $b=modules::run("$MODULE_NAME/$TB_NAME/public_get_mange_b",$row['BRANCH']);
                $cycle_exe=modules::run("$MODULE_NAME/$TB_NAME/public_get_cycle_b",$row['MANAGE_ID']);
                $dep_exe=modules::run("$MODULE_NAME/$TB_NAME/public_get_dep_p", $row['CYCLE_ID']);

                ?>

                <tr>
                    <td>
                        <select name="planning_dir[]" data-val="true"  data-val-required="حقل مطلوب"  id="dp_planning_dir_<?=$count_activity?>" class="form-control">
                            <option></option>
                            <?php foreach($planning_dir as $row1) :?>
                                <option value="<?= $row1['CON_NO'] ?>" <?PHP if ($row1['CON_NO']==$row['PLANNING_DIR']){ echo " selected"; } ?> ><?= $row1['CON_NAME'] ?></option>

                            <?php endforeach; ?>

                        </select>
                        <input  type="hidden" name="h_txt_activities_plan_ser[]"  id="h_txt_activities_plan_ser_<?=$count_activity?>" value="<?=$row['ACTIVITIES_PLAN_SER']?>" data-val="false" data-val-required="required" >
                        <input type="hidden" name="seq1[]" id="seq1_id[]" value="<?=$row['SEQ1']?>"  />
                        <input type="hidden" name="h_count[]" id="h_count_<?=$count_activity?>" />
                        <!-- <span class="field-validation-valid" data-valmsg-for="type[]" data-valmsg-replace="true"></span>-->
                    </td>
                    <td>
                        <select name="branch[]" data-val="true"  data-val-required="حقل مطلوب"  id="dp_branch_<?=$count_activity?>" class="form-control">
                            <option></option>
                            <?php foreach($branches as $row2) :?>
                                <option value="<?= $row2['NO'] ?>" <?PHP if ($row2['NO']==$row['BRANCH']){ echo " selected"; } ?>><?= $row2['NAME'] ?></option>


                            <?php endforeach; ?>


                        </select>
                        <!-- <span class="field-validation-valid" data-valmsg-for="type[]" data-valmsg-replace="true"></span>-->
                    </td>
                    <td>
                        <select name="manage_id[]" data-val="true"  data-val-required="حقل مطلوب"  id="dp_manage_id_<?= $count_activity ?>" class="form-control">
                            <option></option>
                            <?php foreach($b as $row3) :?>
                                <option value="<?= $row3['ST_ID'] ?>" <?PHP if ($row3['ST_ID']==$row['MANAGE_ID']){ echo " selected"; } ?>><?= $row3['ST_NAME'] ?></option>


                            <?php endforeach; ?>

                        </select>
                        <!-- <span class="field-validation-valid" data-valmsg-for="type[]" data-valmsg-replace="true"></span>-->
                    </td>
                    <td>
                        <select name="cycle_id[]" data-val="true"  data-val-required="حقل مطلوب"  id="dp_cycle_id_<?= $count_activity ?>" class="form-control">
                            <option></option>
                            <?php foreach($cycle_exe as $row4) :?>
                                <option value="<?= $row4['ST_ID'] ?>" <?PHP if ($row4['ST_ID']==$row['CYCLE_ID']){ echo " selected"; } ?>><?= $row4['ST_NAME'] ?></option>


                            <?php endforeach; ?>

                        </select>
                        <!-- <span class="field-validation-valid" data-valmsg-for="type[]" data-valmsg-replace="true"></span>-->
                    </td>
                    <td>
                        <select name="department_id[]" data-val="true"  data-val-required="حقل مطلوب"  id="dp_department_id_<?= $count_activity ?>" class="form-control">
                            <option></option>
                            <?php foreach($dep_exe as $row5) :?>
                                <option value="<?= $row5['ST_ID'] ?>" <?PHP if ($row5['ST_ID']==$row['DEPARTMENT_ID']){ echo " selected"; } ?>><?= $row5['ST_NAME'] ?></option>


                            <?php endforeach; ?>

                        </select>
                        <!-- <span class="field-validation-valid" data-valmsg-for="type[]" data-valmsg-replace="true"></span>-->
                    </td>

                    <td>
                        <input class="form-control" name="activity_name[]" id="txt_activity_name_<?=$count_activity?>"  value="<?=$row['ACTIVITY_NAME']?>" data-val="false" data-val-required="required" />


                    </td>
                    <td>

                        <select name="from_month[]" id="dp_from_month_<?=$count_activity?>" data-curr="false" class="form-control">
                            <option  value="">-</option>
                            <?php for($i = 1; $i <= 12 ;$i++) :?>
                                <option value="<?= $i ?>" <?PHP if ($i==$row['FROM_MONTH']){ echo " selected"; } ?>><?= months($i) ?></option>
                            <?php endfor; ?>

                        </select>

                    </td>
                    <td>

                        <select name="to_month[]" id="dp_to_month_<?=$count_activity?>" data-curr="false" class="form-control">
                            <option  value="">-</option>
                            <?php for($i = 1; $i <= 12 ;$i++) :?>
                                <option value="<?= $i ?>" <?PHP if ($i==$row['TO_MONTH']){ echo " selected"; } ?>><?= months($i) ?></option>
                            <?php endfor; ?>

                        </select>

                    </td>
                    <td>
                        <input class="form-control" name="weight[]" id="txt_weight_<?=$count_activity?>" value="<?=$row['WEIGHT']?>" data-val="false" data-val-required="required" />

                    </td>

                    <?php if ( HaveAccess($delete_url_details) && (!$isCreate )) : ?>
                        <td>
                            <a onclick="javascript:delete_row_del('<?=$row['SEQ1']?>','<?=$row['ACTIVITY_NAME']?>');"  href='javascript:;'><i class='glyphicon glyphicon-remove'></i></a>


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
            <th></th>
            <th></th>
            <th></th>


            <th></th>
            <th></th>
            <th>
                الاجمالي الكلي لاوزان
            </th>
            <th id="total_weight"></th>
            <?php if ( HaveAccess($delete_url_details) && (!$isCreate )) : ?>
                <th></th>
            <?php endif; ?>
        </tr>
        <tr>
            <th>
                <a onclick="javascript:add_row(this,'input:text,input:hidden[name^=seq1],textarea,select',false);"  href="javascript:;"><i class="glyphicon glyphicon-plus"></i>جديد</a>
            </th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
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
    <button type="button" data-action="submit" class="btn btn-primary save_activites">حفظ البيانات</button>
    <!--<button type="button" onclick="javascript:return_adopt(1);" class="btn btn-danger">اعتماد</button>-->


</div>


<?php
$scripts = <<<SCRIPT
<script type="text/javascript">
var main_from_month = '{$main_from_month}';
var main_to_month = '{$main_to_month}';
var count=[];





       $('.save_activites').on('click',  function (e) {

        var url = '{$save_active_url}';
        var tbl = '#details_tb1';
        var container = $('#' + $(tbl).attr('data-container'));
         var planning_dir = [];
         var branch = [];
         var manage_id=[];
         var cycle_id=[];
         var department_id=[];
         var activity_name=[];
         var seq1=[];
         var act_ser=[];
         var weight=[];
         var to_month=[];
         var from_month=[];

$('select[name="planning_dir[]"]').each(function (i) {

           if($(this).closest('tr').find('select[name="planning_dir[]"]').val()=='' || $(this).closest('tr').find('select[name="planning_dir[]"]').val()==0)
           {
           danger_msg('يتوجب عليك جميع الحقول');
           return 0;

           }
           else if($(this).closest('tr').find('select[name="branch[]"]').val()=='' || $(this).closest('tr').find('select[name="branch[]"]').val()==0)
           {
              danger_msg('يتوجب عليك جميع الحقول');
           return 0;
           }
           else if($(this).closest('tr').find('select[name="manage_id[]"]').val()=='' || $(this).closest('tr').find('select[name="manage_id[]"]').val()==0)
           {
           danger_msg('يتوجب عليك جميع الحقول');
           return 0;
           }
           else if($(this).closest('tr').find('select[name="cycle_id[]"]').val()=='' || $(this).closest('tr').find('select[name="cycle_id[]"]').val()==0)
           {
          danger_msg('يتوجب عليك جميع الحقول');
           return 0;
           }
         /* else if($(this).closest('tr').find('select[name="department_id[]"]').val()=='' || $(this).closest('tr').find('select[name="department_id[]"]').val()==0)
           {
           danger_msg('يتوجب عليك جميع الحقول');
           return 0;
           }*/
           else if($(this).closest('tr').find('input[name="activity_name[]"]').val()=='')
           {
            danger_msg('يتوجب عليك جميع الحقول');
           return 0;
           }

           else if($(this).closest('tr').find('select[name="from_month[]"]').val()=='' || $(this).closest('tr').find('select[name="from_month[]"]').val()==0)
           {
           danger_msg('يتوجب عليك جميع الحقول');
           return 0;
           }

           else if($(this).closest('tr').find('select[name="to_month[]"]').val()=='' || $(this).closest('tr').find('select[name="to_month[]"]').val()==0)
           {
          danger_msg('يتوجب عليك جميع الحقول');
           return 0;
           }
          else if($(this).closest('tr').find('input[name="weight[]"]').val()=='' || $(this).closest('tr').find('input[name="weight[]"]').val()==0)
           {
          danger_msg('يتوجب عليك جميع الحقول');
           return 0;
           }








    });
        $('select[name="planning_dir[]"]').each(function (i) {

           planning_dir[i]=$(this).closest('tr').find('select[name="planning_dir[]"]').val();
           branch[i]=$(this).closest('tr').find('select[name="branch[]"]').val();
           manage_id[i]=$(this).closest('tr').find('select[name="manage_id[]"]').val();
           cycle_id[i]=$(this).closest('tr').find('select[name="cycle_id[]"]').val();
           department_id[i]=$(this).closest('tr').find('select[name="department_id[]"]').val();
           activity_name[i]=$(this).closest('tr').find('input[name="activity_name[]"]').val();
           from_month[i]=$(this).closest('tr').find('select[name="from_month[]"]').val();
           to_month[i]=$(this).closest('tr').find('select[name="to_month[]"]').val();
           weight[i]=$(this).closest('tr').find('input[name="weight[]"]').val();
           seq1[i]=$(this).closest('tr').find('input[name="seq1[]"]').val();
           act_ser[i]=$(this).closest('tr').find('input[name="h_txt_activities_plan_ser[]"]').val();






    });

if($('#total_weight').text()!=100)
{
 danger_msg('لم يتم الحفظ يجب ان يكون المجوع الكلي للاوزان = 100');
}

else
{




     if(planning_dir.length > 0){
      /*var arr_data = [branch,part,activity,ser1];*/



             get_data(url,{ser:seq1,activity_no:act_ser,planning_dir:planning_dir,branch:branch,manage_id:manage_id,cycle_id:cycle_id,department_id:department_id,activity_name:activity_name,from_month:from_month,to_month:to_month,weight:weight},function(data){

             if(data>1)
             {

                 success_msg('رسالة', 'تم العملية بنجاح ..');
             get_to_link(window.location.href);



             }

                 else
                  danger_msg('لم يتم الحفظ', data);


            });


        }else
          //  alert('لا يوجد سجلات ممكن حفظها');

          danger_msg('لا يوجد سجلات ممكن حفظها');
}
        });

function delete_row_del(id, branch_name) {
  if (confirm(' هل تريد بالتأكيد حذف المشروع '+ branch_name +' ؟!!')) {

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

$('.select2-container').remove();

$('select[name="planning_dir[]"]').each(function (i) {


           count[i]=$(this).closest('tr').find('input[name="h_count[]"]').val(i);






    });


 $('input[name="weight[]"]').keyup(function (e) {

//e.preventDefault();


  calcall();
    });

$('select[name="planning_dir[]"]').select2().on('change',function(){

       //  checkBoxChanged();

        });


      $('select[name="branch[]"]').select2().on('change',function(){

//$('select[name="branch[]"]').on('change', function () {
var cnt_tr=$(this).closest('tr').find('input[name="h_count[]"]').val();
       //alert('loop==>'+cnt_tr);


            get_data('{$manage_follow_branch}',{no: $(this).val()},function(data){
             $('#dp_manage_id_'+cnt_tr).html('');
             $('#dp_manage_id_'+cnt_tr).append('<option></option>');
             $('#dp_manage_id_'+cnt_tr).select2('val','');
             $('#dp_cycle_id_'+cnt_tr).select2('val','');

            $.each(data,function(index, item)
            {

             $('#dp_manage_id_'+cnt_tr).append('<option value=' + item.ST_ID + '>' + item.ST_NAME + '</option>');
            });
            });



        });
       $('select[name="manage_id[]"]').select2().on('change',function(){
     var cnt_tr=$(this).closest('tr').find('input[name="h_count[]"]').val();
      // alert('loop==>'+cnt_tr);

        //  checkBoxChanged();
             get_data('{$cycle_exe_branch}',{no: $(this).val()},function(data){
               $('#dp_cycle_id_'+cnt_tr).html('');
             $('#dp_cycle_id_'+cnt_tr).append('<option></option>');
             $('#dp_cycle_id_'+cnt_tr).select2('val','');
             $('#dp_department_id_'+cnt_tr).select2('val','');
            $.each(data,function(index, item)
            {
            $('#dp_cycle_id_'+cnt_tr).append('<option value=' + item.ST_ID + '>' + item.ST_NAME + '</option>');
            });
            });

        });


         $('select[name="cycle_id[]"]').select2().on('change',function(){
         var cnt_tr=$(this).closest('tr').find('input[name="h_count[]"]').val();
      // alert('loop==>'+cnt_tr);
       //  checkBoxChanged();

            get_data('{$dep_exe_branch}',{no: $(this).val()},function(data){
               $('#dp_department_id_'+cnt_tr).html('');
             $('#dp_department_id_'+cnt_tr).append('<option></option>');
             $('#dp_department_id_'+cnt_tr).select2('val','');

            $.each(data,function(index, item)
            {
            $('#dp_department_id_'+cnt_tr).append('<option value=' + item.ST_ID + '>' + item.ST_NAME + '</option>');
            });
            });


        });

        $('select[name="department_id[]"]').select2().on('change',function(){




       //  checkBoxChanged();


        });


 $('select[name="from_month[]"]').select2().on('change',function(){

if($(this).val()>= main_from_month  && $(this).val()<= main_to_month )
{

}
else
{
 var cnt_tr=$(this).closest('tr').find('input[name="h_count[]"]').val();
      // alert('loop==>'+cnt_tr);
danger_msg('ادخال خاطئ للشهر يتوجب ان يكون ما بين شهر '+ main_from_month +'  ل '+ main_to_month);
$('#dp_from_month_'+cnt_tr).select2('val','');
}


        });
 $('select[name="to_month[]"]').select2().on('change',function(){
if($(this).val()>= main_from_month  && $(this).val()<= main_to_month )
{

}
else
{
 var cnt_tr=$(this).closest('tr').find('input[name="h_count[]"]').val();
       //alert('loop==>'+cnt_tr);
danger_msg('ادخال خاطئ للشهر يتوجب ان يكون ما بين شهر '+ main_from_month +'  ل '+ main_to_month);
$('#dp_to_month_'+cnt_tr).select2('val','');
}

        });
}




</script>

SCRIPT;

sec_scripts($scripts);

?>


