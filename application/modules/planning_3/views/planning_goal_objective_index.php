<?php
/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 01/04/18
 * Time: 08:47 ص
 */

$MODULE_NAME= 'planning';
$TB_NAME= 'planning_unit';
$manage_follow_branch =base_url("$MODULE_NAME/$TB_NAME/public_get_mange");
$get_url =base_url("$MODULE_NAME/$TB_NAME/get_id");
$create_url =base_url("$MODULE_NAME/$TB_NAME/create_goal");

$edit_url =base_url("$MODULE_NAME/$TB_NAME/edit");
$delete_url =base_url("$MODULE_NAME/$TB_NAME/delete");
$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page");
$back_budget_tech_url=base_url("budget/Projects/archive");
$page=1;
echo AntiForgeryToken();
?>

    <div class="row">
    <div class="toolbar">

        <div class="caption"><?=$title?></div>

        <ul>

            <?php if( HaveAccess($create_url)):  ?><li><a  href="<?= $create_url ?>"><i class="glyphicon glyphicon-plus"></i>جديد</a> </li><?php endif; ?>
            <li><a onclick='javascript:{$TB_NAME}_delete();' href='javascript:;'><i class='glyphicon glyphicon-remove'></i>حذف</a> </li>

            <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>


        </ul>

    </div>



    <div class="form-body">
        <div class="modal-body inline_form">
        </div>

        <form class="form-vertical"  id="<?=$TB_NAME?>_form" >
            <div class="modal-body inline_form">
                <div class="form-group col-sm-1">
                    <label class="control-label">المسلسل</label>
                    <div>
                        <input type="text" name="activity_no" id="txt_activity_no" class="form-control" dir="rtl">

                    </div>
                </div>
                <div class="form-group col-sm-1">
                    <label class="control-label">من عام</label>
                    <div>
                        <input type="text" data-val="true"  data-val-required="حقل مطلوب" name="year" id="txt_year" class="form-control" dir="rtl" value="" >
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">الى عام </label>
                    <div>
                        <input type="text" data-val="true"  data-val-required="حقل مطلوب" name="year" id="txt_year" class="form-control" dir="rtl" value="" >
                    </div>
                </div>




                <div class="form-group col-sm-2">
                    <label class="control-label">عنوان الخطة</label>
                    <div>
                        <input type="text" name="activity_name"  id="txt_activity_name" class="form-control ">
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