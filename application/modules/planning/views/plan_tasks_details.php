<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 29/03/18
 * Time: 12:12 م
 */

$MODULE_NAME= 'planning';
$TB_NAME= 'planning';
$isCreate =isset($child_objective) && count($child_objective)  > 0 ?false:true;
$child_count=0;

$save_part_url= base_url("$MODULE_NAME/$TB_NAME/create_edit_sub_activites");
?>

    <div class="tb_container">
	<div class="table-responsive-md">
  <table class="table">
  <thead>
    <tr>
      <th scope="col">الجهة</th>
      <th scope="col">المقر</th>
      <th scope="col">الإدارة</th>
      <th scope="col">الدائرة</th>
      <th scope="col">القسم</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <th scope="row"><?=$info['PLANNING_DIR_NAME'];?></th>
      <td><?=$info['BRANCH_NAME'];?></td>
      <td><?=$info['MANAGE_ID_NAME'];?></td>
      <td><?=$info['CYCLE_ID_NAME'];?></td>
	   <td><?=$info['DEPARTMENT_ID_NAME'];?><input type="hidden" name="task_total_weghit" id="task_total_weghit" value="<?=/*$total_weghit[0]['TOTAL_WEIGHT']*/$info['WEIGHT']?>"  /><input type="hidden" name="task_total_achive" id="task_total_achive" value="<?=$total_weghit[0]['TOTAL_ACHIVE']?>"  /></td>
    </tr>
  </tbody>
  </table>
</div>
        <table class="table" id="details_tb1" data-container="container"  align="center">
            <thead>
            <tr>

                <th style="width:20%">اسم المهمة</th>
                <th style="width:4%">من شهر</th>
                <th style="width:4%">إلى شهر</th>
                <th style="width:3%">الوزن النسبي</th>
				<th style="width:3%">%نسبة الإنجاز</th>
            </tr>
            </thead>
            <tbody>
            <?php if(count($details) <= 0) { 
			?>
                <tr>

                    <td>
					<input class="form-control" name="activity_name[]" id="txt_activity_name_<?=$child_count?>"  data-val ="true"   data-val-required="حقل مطلوب"  />
                    <span class="field-validation-valid" data-valmsg-for="activity_name[]" data-valmsg-replace="true"></span> 
					<input  type="hidden" name="h_txt_activities_plan_ser[]"  id="h_txt_activities_plan_ser_<?=$child_count?>" value="<?=$plan_no?>" data-val="false" data-val-required="required" >
                <input type="hidden" name="seq1[]" id="seq1_id_<?=$child_count?>" value="<?=$id?>"  />
				<input type="hidden" name="f_seq1[]" id="f_seq1_id_<?=$child_count?>" value="0"  />
                <input type="hidden" name="h_count[]" id="h_count_<?=$child_count?>" />
                    </td>
                    <td>
                        <select name="main_from_month[]" id="dp_main_from_month_<?=$child_count?>" data-curr="false" class="form-control"  data-val ="true"   data-val-required="حقل مطلوب" >
               
                 <?php foreach ($months as $i)  {?>
                    <option value="<?= $i['MONTH'] ?>"><?= months($i['MONTH']) ?></option>
                <?php } ?>

            </select>
           <span class="field-validation-valid" data-valmsg-for="main_from_month[]" data-valmsg-replace="true"></span>
                    </td>

                    <td>
                     <select name="main_to_month[]" id="dp_main_to_month_<?=$child_count?>" data-curr="true" class="form-control"  data-val ="false"   data-val-required="حقل مطلوب" >
             <?php foreach ($months as $i)  {?>
                    <option value="<?= $i['MONTH'] ?>"><?= months($i['MONTH']) ?></option>
                <?php } ?>

            </select>
            <span class="field-validation-valid" data-valmsg-for="main_to_month[]" data-valmsg-replace="true"></span>
                    </td>
                    <td>
					 <input class="form-control" name="weight[]" id="txt_weight_<?=$child_count?>"  data-val ="true"   data-val-required="حقل مطلوب"  />
                 <span class="field-validation-valid" data-valmsg-for="weight[]" data-valmsg-replace="true"></span>


                    </td>
					<td>
                          <input class="form-control" name="TASK_ACHIVE[]" id="txt_TASK_ACHIVE_<?=$child_count?>"  data-val ="true"   data-val-required="حقل مطلوب"  />
                          <span class="field-validation-valid" data-valmsg-for="TASK_ACHIVE[]" data-valmsg-replace="true"></span>
                    </td>

                </tr>

                <?php
                $child_count++;
            }else if(count($details) > 0) {
                $child_count = 0;
                foreach($details as $row) {

                    ?>

                    <tr>

                        <td>
			<input class="form-control" name="activity_name[]" id="txt_activity_name_<?=$child_count?>"  data-val ="true"   data-val-required="حقل مطلوب" value="<?=$row['ACTIVITY_NAME']?>"  />
                    <span class="field-validation-valid" data-valmsg-for="activity_name[]" data-valmsg-replace="true"></span> 
         
                            					<input  type="hidden" name="h_txt_activities_plan_ser[]"  id="h_txt_activities_plan_ser_<?=$child_count?>" value="<?=$plan_no?>" data-val="false" data-val-required="required" >
                <input type="hidden" name="seq1[]" id="seq1_id_<?=$child_count?>" value="<?=$id?>"  />
				<input type="hidden" name="f_seq1[]" id="f_seq1_id_<?=$child_count?>" value="<?=$row['SEQ1']?>"  />
                <input type="hidden" name="h_count[]" id="h_count_<?=$child_count?>" />
                        </td>
                        <td>
                            <select name="main_from_month[]" id="dp_main_from_month_<?=$child_count?>" data-curr="true" class="form-control"  data-val ="true"   data-val-required="حقل مطلوب" >
                    <option  value="">-</option>
                    <?php foreach ($months as $i)  {?>
                        <option value="<?= $i['MONTH'] ?>" <?PHP if ($i['MONTH']==$row['FROM_MONTH']){ echo " selected"; } ?>><?= months($i['MONTH']) ?></option>
                    <?php } ?>

                </select>
                <span class="field-validation-valid" data-valmsg-for="main_from_month[]" data-valmsg-replace="true"></span>
                        </td>

                        <td>
						
                           <select name="main_to_month[]" id="dp_main_to_month_<?=$child_count?>" data-curr="true" class="form-control"  data-val ="true"   data-val-required="حقل مطلوب" >
                    <option  value="">-</option>
                    <?php foreach ($months as $i)  {?>
                        <option value="<?= $i['MONTH'] ?>" <?PHP if ($i['MONTH']==$row['TO_MONTH']){ echo " selected"; } ?>><?= months($i['MONTH']) ?></option>
                    <?php } ?>

                </select>
                <span class="field-validation-valid" data-valmsg-for="main_to_month[]" data-valmsg-replace="true"></span>
                        </td>
                        <td>
                          <input class="form-control" name="weight[]" id="txt_weight_<?=$child_count?>" value="<?=$row['WEIGHT']?>"  data-val ="true"   data-val-required="حقل مطلوب"  />
                          <span class="field-validation-valid" data-valmsg-for="weight[]" data-valmsg-replace="true"></span>
                        </td>
						 <td>
                          <input class="form-control" name="TASK_ACHIVE[]" id="txt_TASK_ACHIVE_<?=$child_count?>" value="<?=$row['TASK_ACHIVE']?>"  data-val ="true"   data-val-required="حقل مطلوب"  />
                          <span class="field-validation-valid" data-valmsg-for="TASK_ACHIVE[]" data-valmsg-replace="true"></span>
                        </td>


                    </tr>
                    <?php
                    $child_count++;

                }
            }

            ?>
            </tbody>

            <tfoot>
            <tr>

                <th>
                    <a onclick="javascript:add_row(this,'input:text,input:hidden[name^=f_seq1],select',false);" onfocus="javascript:add_row(this,'input:text,input:hidden[name^=f_seq1],select',false);" href="javascript:;" class="new"><i class="glyphicon glyphicon-plus"></i>جديد</a>
                </th>
                <th></th>

                <th>
                   الإجمالي الكلي
                </th>
                <th id="total_weight"></th>
<th id="total_achive"></th>
            </tr>

            </tfoot>
        </table></div>




</div>

<div class="modal-footer">
   <?php if ( $isCreate or $child_objective[0]['ADOPT'] == 1) : ?>
    <button type="button" data-action="submit" class="btn btn-primary save_part">حفظ البيانات</button>
   <!-- <button type="button" data-action="submit" class="btn btn-info save_part">اعتماد</button>-->
   <?php endif; ?>

</div>


<?php
$scripts = <<<SCRIPT
<script type="text/javascript">
reBind_pram(0);
       calcall();
	  calachivecall();

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
		var SEQ1= [];
         var ACTIVITIES_PLAN_SER = [];
         var PLANNING_DIR = [];
         var BRANCH = [];
         var MANAGE_ID = [];
         var CYCLE_ID = [];
         var DEPARTMENT_ID =[];
		 var ACTIVITY_NAME =[];
		 var FROM_MONTH =[];
		 var TO_MONTH =[];
		 var WEIGHT =[];
		 var F_SEQ__ =[];
		 var TASK_ACHIVE=[];
		 
								   

        $('input[name="activity_name[]"]').each(function (i) {
		
        if($(this).closest('tr').find('input[name="activity_name[]"]').val() !='')
        {
		  SEQ1[i]=$(this).closest('tr').find('input[name="f_seq1[]"]').val();
          ACTIVITIES_PLAN_SER[i]={$plan_no};
		   F_SEQ__[i]={$id};
		   if(F_SEQ__[i] == 0)
		   {
		   PLANNING_DIR[i]=1;
		   }
		   else
		   {
		   PLANNING_DIR[i]=2;
		   }
          
          BRANCH[i]='';
          MANAGE_ID[i]='';
          CYCLE_ID[i]='';
		  DEPARTMENT_ID[i]='';
		  ACTIVITY_NAME[i]=$(this).closest('tr').find('input[name="activity_name[]"]').val();
		  FROM_MONTH[i]=$(this).closest('tr').find('select[name="main_from_month[]"]').val();
		  TO_MONTH[i]=$(this).closest('tr').find('select[name="main_to_month[]"]').val();
          WEIGHT[i]=$(this).closest('tr').find('input[name="weight[]"]').val();
		  TASK_ACHIVE[i]=$(this).closest('tr').find('input[name="TASK_ACHIVE[]"]').val();

        }
        });
		//console.log(FROM_MONTH);
		if(Number($('#total_weight').text()) != $('#task_total_weghit').val())
             danger_msg('مجموع الاوزان يبج ان يكون مساويا ' + $('#task_total_weghit').val());
     else
         {
      if(ACTIVITY_NAME.length > 0){
      get_data(url,{ser:SEQ1,activity_no:ACTIVITIES_PLAN_SER,planning_dir:PLANNING_DIR,branch:BRANCH,manage_id:MANAGE_ID,cycle_id:CYCLE_ID,department_id:DEPARTMENT_ID,activity_name:ACTIVITY_NAME,from_month:FROM_MONTH,to_month:TO_MONTH,weight:WEIGHT,F_SEQ__:F_SEQ__,TASK_ACHIVE:TASK_ACHIVE},function(data){
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
		
		
        if(Number(total_weight)>$('#task_total_weghit').val())
                {
				//alert(weight);
                    danger_msg('لقد تجاوزت الحد المسموح');
                    $(this).closest('tr').find('input[name="weight[]"]').val(0);
					total_weight -= Number(weight);
					$('#total_weight').text(isNaNVal(Number(total_weight)));
                }
                else
				{

        $('#total_weight').text(isNaNVal(Number(total_weight)));
		
		}
		   
    });



}

 function calachivecall() {

   
	var total_achive = Number($('#task_total_achive').val());;

    $('input[name="TASK_ACHIVE[]"]').each(function () {

        var weight = $(this).closest('tr').find('input[name="weight[]"]').val();
		var TASK_ACHIVE = $(this).closest('tr').find('input[name="TASK_ACHIVE[]"]').val();
       	total_achive+=(Number(TASK_ACHIVE)/100)*Number(weight);

		   if(Number(total_achive)>100)
                {
                    danger_msg('لقد تجاوزت الحد المسموح');
                    $(this).closest('tr').find('input[name="TASK_ACHIVE[]"]').val(0);
					total_achive-=(Number(TASK_ACHIVE)/100)*Number(weight);
                }
				   else
				{

        $('#total_achive').text(isNaNVal(Number(total_achive)));
		
		}
    });



}
function reBind_pram(cnt){

$('.select2-container',$('#details_tb1')).remove();

		
		$('select[name="main_to_month[]"]').select2().on('change',function(){

       //  checkBoxChanged();

        });
		$('select[name="main_from_month[]"]').select2().on('change',function(){

       //  checkBoxChanged();

        });

 $('input[name="weight[]"]').keyup(function (e) {

 calcall();
 calachivecall();
    });
	
	 $('input[name="TASK_ACHIVE[]"]').keyup(function (e) {
 calachivecall();
  calcall();
    });
	

	
    }
</script>

SCRIPT;

sec_scripts($scripts);

?>


