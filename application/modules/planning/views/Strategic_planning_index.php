<?php
/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 18/11/17
 * Time: 09:09 ص
 */

$MODULE_NAME = 'planning';
$TB_NAME = 'Strategic_planning';
$TB_NAME1 = 'planning';
$manage_follow_branch = base_url("$MODULE_NAME/$TB_NAME/public_get_mange");
$get_url = base_url("$MODULE_NAME/$TB_NAME/get_id");
$create = base_url("$MODULE_NAME/$TB_NAME/create");
$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page");
$delete_url = base_url("$MODULE_NAME/$TB_NAME/delete");
$back_budget_tech_url = base_url("budget/Projects/archive");
$get_all_goal = base_url("$MODULE_NAME/$TB_NAME/public_get_obj");
$get_all_plans = base_url("$MODULE_NAME/$TB_NAME/public_get_plan");
$page = 1;
$FROM_YEAR = $year_paln[0]['FROM_YEAR'];
$TO_YEAR = $year_paln[0]['TO_YEAR'];
$curr_plan = modules::run("$MODULE_NAME/$TB_NAME/public_get_plan_id", $year_paln[0]['SER']);
$manage_exe_branch = base_url("$MODULE_NAME/$TB_NAME1/public_get_mange");
$cycle_exe_branch = base_url("$MODULE_NAME/$TB_NAME1/public_get_cycle");
$dep_exe_branch = base_url("$MODULE_NAME/$TB_NAME1/public_get_dep");
//var_dump();
$report_url = base_url("JsperReport/showreport?sys=planning");
echo AntiForgeryToken();
?>
    <script> var show_page = true; </script>
    <div class="row">
        <div class="toolbar">

            <div class="caption">الخطة الإستراتيجية</div>

            <ul>
                <!--<?php if (HaveAccess($create_url)): ?><li><a  href="<?= $create_url ?>"><i class="glyphicon glyphicon-plus"></i>اضافة مشروع فني</a> </li><?php endif; ?>-->
                <?php if (HaveAccess($create)): ?>
                    <li><a href="<?= $create ?>"><i class="glyphicon glyphicon-plus"></i>جديد</a> </li><?php endif; ?>
                <!--<?php if (($tech == 1) && HaveAccess($back_budget_tech_url)): ?><li><a  href="<?= $back_budget_tech_url ?>"><i class="icon icon-reply"></i>موازنة المشاريع للمشروع الفني</a> </li><?php endif; ?>-->
                <?php if (HaveAccess($delete_url)) echo "<li><a onclick='javascript:{$TB_NAME}_delete();' href='javascript:;'><i class='glyphicon glyphicon-remove'></i>حذف</a> </li>"; ?>
                <li><a  href="<?=base_url('uploads/statgic_plan_user_manual.pdf')?>" target="_blank"><i class="icon icon-question-circle"></i>دليل المستخدم</a>


            </ul>

        </div>


        <div class="form-body">
            <div class="modal-body inline_form">
            </div>

            <form class="form-vertical" id="<?= $TB_NAME ?>_form">
                <div class="modal-body inline_form">

                    <div class="form-group col-sm-1">
                        <label class="control-label">من عام</label>
                        <div>
                            <input type="text" data-val="true" data-val-required="حقل مطلوب" name="FROM_YEAR"
                                   id="txt_FROM_YEAR" class="form-control" dir="rtl" value="<?= $FROM_YEAR; ?>">
                        </div>
                    </div>

                    <div class="form-group col-sm-1">
                        <label class="control-label">إلى عام</label>
                        <div>
                            <input type="text" data-val="true" data-val-required="حقل مطلوب" name="TO_YEAR"
                                   id="txt_TO_YEAR" class="form-control" dir="rtl" value="<?= $TO_YEAR; ?>">
                        </div>
                    </div>


                    <div class="form-group col-sm-4">

                        <label class="control-label">الخطة</label>
                        <div>
                            <select name="dp_plan" id="dp_plan" data-curr="false" class="form-control">
                                <option></option>
                                <?php foreach ($curr_plan as $row) : ?>
                                    <option value="<?= $row['SER'] ?>">
                                        <?= $row['TITLE'] . ': ' . $row['FROM_YEAR'] . ' - ' . $row['TO_YEAR'] ?>
                                    </option>


                                <?php endforeach; ?>
                            </select>

                        </div>
                    </div>
                    <br>

                    <div class="form-group col-sm-3">
                        <label class="col-sm-2 control-label">المحور</label>
                        <div>

                            <select name="objective" data-val="true" data-val-required="حقل مطلوب" id="dp_objective"
                                    class="form-control">

                                <option></option>
                                <?php foreach ($all_objective as $row) : ?>
                                    <option value="<?= $row['ID'] ?>"><?= $row['ID_NAME'] ?></option>

                                <?php endforeach; ?>


                            </select>

                            <span class="field-validation-valid" data-valmsg-for="objective"
                                  data-valmsg-replace="true"></span>


                        </div>
                    </div>


                    <div class="form-group col-sm-3">
                        <label class="col-sm-6 control-label">الهدف الاستراتيجي(العام)</label>
                        <div>

                            <select name="goal" data-val="true" data-val-required="حقل مطلوب" id="dp_goal"
                                    class="form-control">

                                <option></option>
                                <?php foreach ($goal_list as $row) : ?>
                                    <option value="<?= $row['ID'] ?>"><?= $row['ID_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>

                            <span class="field-validation-valid" data-valmsg-for="goal"
                                  data-valmsg-replace="true"></span>

                        </div>
                    </div>


                    <div class="form-group col-sm-3">
                        <label class="col-sm-6 control-label">الهدف الاستراتيجي(الخاص)</label>
                        <div>

                            <select name="goal_t" id="dp_goal_t" data-val="true" data-val-required="حقل مطلوب"
                                    class="form-control">

                                <option></option>
                                <?php foreach ($goal_t_list as $row) : ?>
                                    <option value="<?= $row['ID'] ?>"><?= $row['ID_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>

                            <span class="field-validation-valid" data-valmsg-for="goal_t"
                                  data-valmsg-replace="true"></span>

                        </div>
                    </div>
                    <br>
                    <div class="form-group col-sm-2">
                        <label class="control-label">اسم المشروع</label>
                        <div>
                            <input type="text" name="activity_name" id="txt_activity_name" class="form-control ">
                        </div>
                    </div>
                    <div class="form-group col-sm-1">

                        <label class="control-label">مسؤلية التنفيذ</label>
                        <div>
                            <select name="branch" id="dp_branch" data-curr="false" class="form-control">
                                <option value="">-</option>
                                <?php foreach ($branches as $row) : ?>
                                    <option value="<?= $row['NO'] ?>">
                                        <?= $row['NAME'] ?>
                                    </option>


                                <?php endforeach; ?>
                            </select>

                        </div>
                    </div>
                    <div class="form-group col-sm-2">

                        <label class="control-label">الادارة</label>
                        <div>
                            <select name="manage_exe_id" data-val="true" data-val-required="حقل مطلوب"
                                    id="dp_manage_exe_id" class="form-control">
                                <option value=""></option>

                            </select>
                            <span class="field-validation-valid" data-valmsg-for="manage_exe_id"
                                  data-valmsg-replace="true">
                        </div>
                    </div>
                    <div class="form-group col-sm-2">

                        <label class="control-label">الدائرة</label>
                        <div>
                            <select name="cycle_exe_id" id="dp_cycle_exe_id" class="form-control">
                                <option value=""></option>
                            </select>
                            <span class="field-validation-valid" data-valmsg-for="cycle_exe_id"
                                  data-valmsg-replace="true">
                        </div>
                    </div>
                    <div class="form-group col-sm-2">

                        <label class="control-label">القسم</label>
                        <div>
                            <select name="department_exe_id" id="dp_department_exe_id" class="form-control">
                                <option value=""></option>
                            </select>
                            <span class="field-validation-valid" data-valmsg-for="department_exe_id"
                                  data-valmsg-replace="true">
                        </div>
                    </div>




                </div>


            </form>


            <div class="modal-footer">
                <button type="button" onclick="javascript:search();" class="btn btn-success"> إستعلام</button>
                <button type="button" onclick="javascript:print_report();" class="btn btn-danger">طباعة التقرير<span
                            class="glyphicon glyphicon-print"></span></button>
                <button type="button" onclick="javascript:clear_form();" class="btn btn-default"> تفريغ الحقول</button>


            </div>
            <div id="msg_container"></div>

            <div id="container">

            </div>
        </div>

    </div>

<?php
$scripts = <<<SCRIPT
<script type="text/javascript">


 $('.pagination li').click(function(e){
        e.preventDefault();
    });

//search();

 function clear_form(){
      clearForm($('#{$TB_NAME}_form'));
          }
          function search(){
if($('#txt_FROM_YEAR').val()=='' || $('#txt_TO_YEAR').val() =='')
{danger_msg('يتوجب ادخال من عام الى عام !!');}
else 
{
       var values= {page:1,FROM_YEAR:$('#txt_FROM_YEAR').val(),TO_YEAR:$('#txt_TO_YEAR').val(),activity_name:$('#txt_activity_name').val(),dp_plan:$('#dp_plan').val(),branch:$('#dp_branch').val(),dp_manage_exe_id:$('#dp_manage_exe_id').val(),dp_cycle_exe_id:$('#dp_cycle_exe_id').val(),dp_department_exe_id:$('#dp_department_exe_id').val(),dp_objective:$('#dp_objective').val(),dp_goal:$('#dp_goal').val(),dp_goal_t:$('#dp_goal_t').val()};
        get_data('{$get_page_url}',values ,function(data){

            $('#container').html(data);
        },'html');
    }
}
    function LoadingData(){
  var values= {page:1,FROM_YEAR:$('#txt_FROM_YEAR').val(),TO_YEAR:$('#txt_TO_YEAR').val(),activity_name:$('#txt_activity_name').val(),dp_plan:$('#dp_plan').val(),branch:$('#dp_branch').val(),dp_manage_exe_id:$('#dp_manage_exe_id').val(),dp_cycle_exe_id:$('#dp_cycle_exe_id').val(),dp_department_exe_id:$('#dp_department_exe_id').val(),dp_objective:$('#dp_objective').val(),dp_goal:$('#dp_goal').val(),dp_goal_t:$('#dp_goal_t').val()};         ajax_pager_data('#page_tb > tbody',values);
    }




		$("#txt_FROM_YEAR,#txt_TO_YEAR").on('change',function(){
    get_data('{$get_all_plans}',{from_year: $('#txt_FROM_YEAR').val(),to_year:$('#txt_TO_YEAR').val()},function(data){

            $('#dp_plan').html('');
			$('#dp_plan').append('<option></option>');
			$('#dp_objective').html('');
			 $('#dp_objective').append('<option></option>');
			 $('#dp_goal').html('');
             $('#dp_goal').append('<option></option>');
			 $('#dp_goal_t').html('');
             $('#dp_goal_t').append('<option></option>');
             $("#dp_plan").select2('val','');
             $("#dp_objective").select2('val','');
			 $("#dp_goal").select2('val','');
			 $("#dp_goal_t").select2('val','');
            $.each(data,function(index, item)
            {
            $('#dp_plan').append('<option value=' + item.SER + '>' + item.TITLE+': '+item.FROM_YEAR+' - '+item.TO_YEAR+ '</option>');
            });
            });
});
  $('#dp_plan').select2().on('change',function(){
  
   get_data('{$get_all_goal}',{plan_id: $('#dp_plan').val(),father_id:0},function(data){

             $('#dp_objective').html('');
			 $('#dp_objective').append('<option></option>');
			 $('#dp_goal').html('');
             $('#dp_goal').append('<option></option>');
			 $('#dp_goal_t').html('');
             $('#dp_goal_t').append('<option></option>');
             $("#dp_objective").select2('val','');
             $("#dp_goal").select2('val','');
			 $("#dp_goal_t").select2('val','');
            $.each(data,function(index, item)
            {
            $('#dp_objective').append('<option value=' + item.ID + '>' + item.ID_NAME + '</option>');
            });
            });

        });
		 $('#dp_objective').select2().on('change',function(){
  get_data('{$get_all_goal}',{plan_id: $('#dp_plan').val(),father_id:$(this).val()},function(data){

           
			 $('#dp_goal').html('');
             $('#dp_goal').append('<option></option>');
			 $('#dp_goal_t').html('');
             $('#dp_goal_t').append('<option></option>');
             $("#dp_goal").select2('val','');
             $("#dp_goal_t").select2('val','');
            $.each(data,function(index, item)
            {
            $('#dp_goal').append('<option value=' + item.ID + '>' + item.ID_NAME + '</option>');
            });
            });
        });
		 $('#dp_goal').select2().on('change',function(){
		  //  checkBoxChanged();
          get_data('{$get_all_goal}',{plan_id: $('#dp_plan').val(),father_id:$(this).val()},function(data){
            $('#dp_goal_t').html('');
             $('#dp_goal_t').append('<option></option>');
             $("#dp_goal_t").select2('val','');
            $.each(data,function(index, item)
            {
            $('#dp_goal_t').append('<option value=' + item.ID + '>' + item.ID_NAME + '</option>');
            });
            });

        });
		 $('#dp_goal_t').select2().on('change',function(){

        });
		///////////////////////////////////////

   function print_report(){
		var rep_url = '{$report_url}&report_type=pdf&report=stratgic_activities&p_from_year='+$('#txt_FROM_YEAR').val()+'&p_to_year='+$('#txt_TO_YEAR').val()+'&p_goal='+$('#dp_goal').val()+'&p_goal_t='+$('#dp_goal_t').val()+'&p_objective='+$('#dp_objective').val();
		_showReport(rep_url);
    }
	///////
	 $('#dp_branch').select2().on('change',function(){
          get_data('{$manage_exe_branch}',{no: $(this).val()},function(data){
            $('#dp_manage_exe_id').html('');
              $('#dp_manage_exe_id').append('<option></option>');
              $("#dp_manage_exe_id").select2('val','');
                 $("#dp_cycle_exe_id").select2('val','');
                 $("#dp_department_exe_id").select2('val','');
            $.each(data,function(index, item)
            {
            $('#dp_manage_exe_id').append('<option value=' + item.ST_ID + '>' + item.ST_NAME + '</option>');
            });
            });
        });
		$('#dp_manage_exe_id').select2().on('change',function(){

       //  checkBoxChanged();
        //  checkBoxChanged();
             get_data('{$cycle_exe_branch}',{no: $(this).val()},function(data){
            $('#dp_cycle_exe_id').html('');
              $('#dp_cycle_exe_id').append('<option></option>');
              $("#dp_cycle_exe_id").select2('val','');
             $("#dp_department_exe_id").select2('val','');
            $.each(data,function(index, item)
            {
            $('#dp_cycle_exe_id').append('<option value=' + item.ST_ID + '>' + item.ST_NAME + '</option>');
            });
            });

        });


         $('#dp_cycle_exe_id').select2().on('change',function(){

       //  checkBoxChanged();
        //  checkBoxChanged();

       //  checkBoxChanged();
             get_data('{$dep_exe_branch}',{no: $(this).val()},function(data){
           $('#dp_department_exe_id').html('');
           $('#dp_department_exe_id').append('<option></option>');
            $("#dp_department_exe_id").select2('val','');

            $.each(data,function(index, item)
            {
            $('#dp_department_exe_id').append('<option value=' + item.ST_ID + '>' + item.ST_NAME + '</option>');
            });
            });

        });

         $('#dp_department_exe_id').select2().on('change',function(){

       //  checkBoxChanged();



        });
         
         function {$TB_NAME}_delete(){
        var url = '{$delete_url}';
        var tbl = '#page_tb';
        //var tbl1 = '#{$TB_NAME}';
        var container = $('#' + $(tbl).attr('data-container'));
         var val = [];
        //  var val2 = [];
     
        $(tbl + ' .checkboxes:checked').each(function (i) {
            
            val[i] = $(this).val();
           // val2[i]=$(this).attr('data-id') ;

        });

        if(val.length > 0){
            if(confirm('هل تريد بالتأكيد حذف '+val.length+' سجلات وحذف تفاصيلها ؟!!')){
              ajax_delete(url, val ,function(data){
                 
     if(data==1)
         {
             success_msg('رسالة','تم حذف السجلات بنجاح ..');
                     search();
         }
                    else
                        {
        
                              
                            danger_msg('', data);
                            search();
                        
                                 }
                    
                      

             
                });
            }
        }else
            alert('يجب تحديد السجلات المراد حذفها');
    }
</script>

SCRIPT;

sec_scripts($scripts);

?>