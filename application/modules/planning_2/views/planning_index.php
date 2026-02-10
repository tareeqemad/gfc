<?php
/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 18/11/17
 * Time: 09:09 ص
 */

$MODULE_NAME= 'planning';
$TB_NAME= 'planning';
$manage_follow_branch =base_url("$MODULE_NAME/$TB_NAME/public_get_mange");
$get_url =base_url("$MODULE_NAME/$TB_NAME/get_id");
$create_without_cost_url =base_url("$MODULE_NAME/$TB_NAME/create_without_cost");
$create_url =base_url("$MODULE_NAME/$TB_NAME/create");
$edit_url =base_url("$MODULE_NAME/$TB_NAME/edit");
$delete_url =base_url("$MODULE_NAME/$TB_NAME/delete");
$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page");
$back_budget_tech_url=base_url("budget/Projects/archive");
$page=1;
echo AntiForgeryToken();
?>

<div class="row">
    <div class="toolbar">

        <div class="caption">تخطيط الأنشطة</div>

        <ul>
            <?php if( HaveAccess($create_url)):  ?><li><a  href="<?= $create_url ?>"><i class="glyphicon glyphicon-plus"></i>اضافة مشروع فني</a> </li><?php endif; ?>
            <?php if( HaveAccess($create_without_cost_url)):  ?><li><a  href="<?= $create_without_cost_url ?>"><i class="glyphicon glyphicon-plus"></i>اضافة مشروع غير فني</a> </li><?php endif; ?>
            <?php  if( ($tech==1)&&HaveAccess($back_budget_tech_url)):  ?><li><a  href="<?= $back_budget_tech_url ?>"><i class="icon icon-reply"></i>موازنة المشاريع للمشروع الفني</a> </li><?php  endif; ?>
            <?php if(HaveAccess($delete_url)) echo "<li><a onclick='javascript:{$TB_NAME}_delete();' href='javascript:;'><i class='glyphicon glyphicon-remove'></i>حذف</a> </li>";?>
            <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>

        </ul>

    </div>



    <div class="form-body">
        <div class="modal-body inline_form">
        </div>

        <form class="form-vertical"  id="<?=$TB_NAME?>_form" >
            <div class="modal-body inline_form">
                <div class="form-group col-sm-1">
                    <label class="control-label">رقم النشاط</label>
                    <div>
                        <input type="text" name="activity_no" id="txt_activity_no" class="form-control" dir="rtl">

                    </div>
                </div>
                <div class="form-group col-sm-1">
                    <label class="control-label">تعريف النشاط لعام</label>
                    <div>
                        <input type="text" data-val="true"  data-val-required="حقل مطلوب" name="year" id="txt_year" class="form-control" dir="rtl" value="<?php echo  /*date('Y')+1 */$year_paln;?>" >
                    </div>
                </div>

                <div class="form-group col-sm-1">

                    <label class="control-label">تصنيف النشاط</label>
                    <div>
                        <select  name="class_name" id="dp_class_name"  data-curr="false"  class="form-control"  >
                            <option value="">-</option>
                            <?php foreach($activity_class as $row) :?>
                                <option  value="<?= $row['CON_NO'] ?>" ><?php echo $row['CON_NAME']  ?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="field-validation-valid" data-valmsg-for="class_name" data-valmsg-replace="true">
                    </div>
                </div>

                <div class="form-group col-sm-1">

                    <label class="control-label">نوع النشاط</label>
                    <div>
                        <select  name="type" id="dp_type"  data-curr="false"  class="form-control"  >
                            <option value="">-</option>
                            <?php foreach($activity_type as $row) :?>
                                <option  value="<?= $row['CON_NO'] ?>" ><?php echo $row['CON_NAME']  ?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="field-validation-valid" data-valmsg-for="type" data-valmsg-replace="true">
                    </div>
                </div>



                <div class="form-group col-sm-2">
                    <label class="control-label">اسم المشروع الفني</label>
                    <div>
                        <input type="text" name="project_name"  id="txt_project_name" class="form-control ">
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">اسم النشاط</label>
                    <div>
                        <input type="text" name="activity_name"  id="txt_activity_name" class="form-control ">
                    </div>
                </div>

                <div class="form-group col-sm-2 hidden">

                    <label class="control-label"> الغاية  </label>
                    <div>
                        <select  name="objective" id="dp_objective"  data-curr="false"  class="form-control"  >
                            <option value="">-</option>
                            <?php foreach($all_objective as $row) :?>
                                <option  value="<?= $row['ID'] ?>" ><?php echo $row['ID_NAME']  ?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="field-validation-valid" data-valmsg-for="objective" data-valmsg-replace="true">
                    </div>
                </div>


                <div class="form-group col-sm-2 hidden">

                    <label class="control-label">الهدف الاستراتيجي</label>
                    <div>
                        <select  name="goal" id="dp_goal"  data-curr="false"  class="form-control"  >
                            <option value="">-</option>
                            <?php foreach($goal_list as $row) :?>
                                <option  value="<?= $row['ID'] ?>" ><?php echo $row['ID_NAME']  ?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="field-validation-valid" data-valmsg-for="goal" data-valmsg-replace="true">
                    </div>
                </div>

                <div class="form-group col-sm-2 hidden">

                    <label class="control-label">الهدف التشغيلي</label>
                    <div>
                        <select  name="goal_t" id="dp_goal_t"  data-curr="false"  class="form-control"  >
                            <option value="">-</option>
                            <?php foreach($goal_t_list as $row) :?>
                                <option  value="<?= $row['ID'] ?>" ><?php echo $row['ID_NAME']  ?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="field-validation-valid" data-valmsg-for="goal_t" data-valmsg-replace="true">
                    </div>
                </div>


                <div class="form-group col-sm-1" >

                    <label class="control-label">مصدر التمويل</label>
                    <div>
                        <select  name="finance" id="dp_finance"  data-curr="false"  class="form-control"  >
                            <option value="">-</option>
                            <?php foreach($finance_type as $row) :?>
                                <option  value="<?= $row['CON_NO'] ?>" ><?php echo $row['CON_NAME']  ?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="field-validation-valid" data-valmsg-for="finance" data-valmsg-replace="true">
                    </div>
                </div>
                <div class="form-group col-sm-1" >

                    <label class="control-label">مقر مسؤلية المتابعة</label>
                    <div>
                        <select  name="branch_follow" id="dp_branch_follow"  data-curr="false"  class="form-control"  >
                            <option value="">-</option>
                            <?php foreach($branches_follow as $row) :?>
                                <option value="<?= $row['NO'] ?>">
                                    <?= $row['NAME'] ?>
                                </option>


                            <?php endforeach; ?>
                        </select>

                    </div>
                </div>
                <div class="form-group col-sm-2" >

                    <label class="control-label">مسؤلية المتابعة</label>
                    <div>
                        <select  name="branch_follow_name" id="dp_branch_follow_name"  data-curr="false"  class="form-control"  >
                            <option value="">-</option>
                        </select>

                    </div>
                </div>
                <div class="form-group col-sm-1" >

                    <label class="control-label">مسؤلية التنفيذ</label>
                    <div>
                        <select  name="branch" id="dp_branch"  data-curr="false"  class="form-control"  >
                            <option value="">-</option>
                        <?php foreach($branches as $row) :?>
                            <option value="<?= $row['NO'] ?>">
                                <?= $row['NAME'] ?>
                            </option>


                        <?php endforeach; ?>
                            </select>

                    </div>
                </div>

                <div class="form-group col-sm-1">

                    <label class="control-label">من  شهر التنفيذ</label>
                    <div>
                        <select  name="from_month" id="dp_from_month"  data-curr="false"  class="form-control"  >
                            <option value="">-</option>
                            <?php for($i = 1; $i <= 12 ;$i++) :?>
                                <option   value="<?= $i ?>"><?= months($i) ?></option>
                            <?php endfor; ?>
                        </select>
                        <span class="field-validation-valid" data-valmsg-for="type" data-valmsg-replace="true">
                    </div>
                </div>

            </div>


        </form>


        <div class="modal-footer">

            <button type="button" onclick="javascript:search();" class="btn btn-success"> إستعلام</button>
            <button type="button" onclick="javascript:clear_form();"  class="btn btn-default"> تفريغ الحقول</button>


        </div>
        <div id="msg_container"></div>

        <div id="container">
            <?=modules::run($get_page_url,$page);?>
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

function {$TB_NAME}_delete(){
        var url = '{$delete_url}';
        var tbl = '#{$TB_NAME}_tb';
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
                    success_msg('رسالة','تم حذف السجلات بنجاح ..');
                    container.html(data);
                      window.location.reload();
                });
            }
        }else
            alert('يجب تحديد السجلات المراد حذفها');
    }
 function clear_form(){
      clearForm($('#{$TB_NAME}_form'));
          }
          function search(){

       var values= {page:1,activity_no:$('#txt_activity_no').val(),year:$('#txt_year').val(),class_name:$('#dp_class_name').val(),type:$('#dp_type').val(),project_name:$('#txt_project_name').val(),activity_name:$('#txt_activity_name').val(),finance:$('#dp_finance').val(),branch:$('#dp_branch').val(),from_month:$('#dp_from_month').val(),branch_follow_id:$('#dp_branch_follow').val(),manage_follow_id:$('#dp_branch_follow_name').val()};
        get_data('{$get_page_url}',values ,function(data){

            $('#container').html(data);
        },'html');
    }

    function LoadingData(){
     var values= {page:1,activity_no:$('#txt_activity_no').val(),year:$('#txt_year').val(),class_name:$('#dp_class_name').val(),type:$('#dp_type').val(),project_name:$('#txt_project_name').val(),activity_name:$('#txt_activity_name').val(),finance:$('#dp_finance').val(),branch:$('#dp_branch').val(),from_month:$('#dp_from_month').val(),branch_follow_id:$('#dp_branch_follow').val(),manage_follow_id:$('#dp_branch_follow_name').val()};
          ajax_pager_data('#page_tb > tbody',values);
    }
 $(document).ready(function() {
 $('#dp_branch').select2().on('change',function(){

        });
        $('#dp_finance').select2().on('change',function(){

        });

            $('#dp_type').select2().on('change',function(){

        });
              $('#dp_class_name').select2().on('change',function(){

        });
        $('#dp_from_month').select2().on('change',function(){

        });
          $('#dp_branch_follow').select2().on('change',function(){
  get_data('{$manage_follow_branch}',{no: $(this).val()},function(data){
            $('#dp_branch_follow_name').html('');
              $('#dp_branch_follow_name').append('<option></option>');
              $("#dp_branch_follow_name").select2('val','');

            $.each(data,function(index, item)
            {
            $('#dp_branch_follow_name').append('<option value=' + item.ST_ID + '>' + item.ST_NAME + '</option>');
            });
            });
        });

  $('#dp_branch_follow_name').select2().on('change',function(){



        });




});



</script>

SCRIPT;

sec_scripts($scripts);

?>