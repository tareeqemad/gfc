<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 04/01/18
 * Time: 09:16 ص
 */
$MODULE_NAME= 'planning';
$TB_NAME= 'planning';
$backs_url=base_url("$MODULE_NAME/$TB_NAME"); //$action
$get_url =base_url("$MODULE_NAME/$TB_NAME/get_id");
$create_url =base_url("$MODULE_NAME/$TB_NAME/create_without_cost");
$edit_url =base_url("$MODULE_NAME/$TB_NAME/edit");
$delete_url =base_url("$MODULE_NAME/$TB_NAME/delete");
$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page_weight");
$save_all_url = base_url("$MODULE_NAME/$TB_NAME/save_all_evaluate");
$adopt_all_url= base_url("$MODULE_NAME/$TB_NAME/adopt_all_evaluate");
$post_url= base_url("$MODULE_NAME/$TB_NAME/save_all_evaluate");
$manage_exe_branch =base_url("$MODULE_NAME/$TB_NAME/public_get_mange");

$page=1;
echo AntiForgeryToken();
?>

<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>

        <ul>

            <?php  if( HaveAccess($backs_url)):  ?><li><a  href="<?= $backs_url ?>"><i class="icon icon-reply"></i> </a> </li><?php  endif; ?>
            <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>

        </ul>

    </div>



    <div class="form-body">
        <div class="modal-body inline_form">
        </div>

        <form class="form-vertical" id="<?=$TB_NAME?>_form" method="post" action="<?=$post_url?>" role="form" novalidate="novalidate">
            <div class="modal-body inline_form">

                <div class="form-group col-sm-1">
                    <label class="col-sm-1 control-label">المقر</label>
                    <div>
                        <select name="branch_exe_id" data-val="true"  data-val-required="حقل مطلوب"  id="dp_branch_exe_id" class="form-control">
                            <option></option>
                            <?php foreach($branches as $row) :?>
                                <option value="<?= $row['NO'] ?>" ><?= $row['NAME'] ?></option>


                            <?php endforeach; ?>

                        </select>

                        <span class="field-validation-valid" data-valmsg-for="branch_exe_id" data-valmsg-replace="true"></span>




                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="col-sm-2 control-label">الادارة</label>
                    <div>

                        <select name="manage_exe_id" data-val="true"  data-val-required="حقل مطلوب"  id="dp_manage_exe_id" class="form-control">
                            <option></option>
                            <?php foreach($b as $row) :?>
                                <option value="<?= $row['ST_ID'] ?>"><?= $row['ST_NAME'] ?></option>


                            <?php endforeach; ?>


                        </select>

                        <span class="field-validation-valid" data-valmsg-for="manage_exe_id" data-valmsg-replace="true"></span>

                    </div>
                </div>

            </div>

            <div class="modal-footer">

                <button type="button" onclick="javascript:search();" class="btn btn-success"> إستعلام</button>
                <?php  if( HaveAccess($save_all_url)):  ?>
                    <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                <?php  endif; ?>
                <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                <?php  if( HaveAccess($adopt_all_url)):  ?>
                    <!-- <button type="button" onclick="javascript:return_adopt(1);" class="btn btn-danger">اعتماد و ترحيل الخطة الشهرية</button>-->
                    <button type="button" onclick="javascript:return_adopt(1);" class="btn btn-danger">اعتماد</button>

                <?php  endif; ?>
                <button type="button" onclick="javascript:return_adopt(1);" class="btn btn-danger">اعتماد</button>

                <button type="button" onclick="javascript:clear_form();"  class="btn btn-default"> تفريغ الحقول</button>


            </div>
            <div id="msg_container"></div>

            <div id="container">

            </div>
        </form>



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


     $("#dp_branch").select2('val','');
     $("#dp_from_month").select2('val','');

          }
          function search(){


       var values= {page:1,branch:$('#dp_branch').val(),from_month:$('#dp_from_month').val()};
        get_data('{$get_page_url}',values ,function(data){

            $('#container').html(data);
        },'html');
    }

    function LoadingData(){
     var values= {page:1,branch:$('#dp_branch').val(),from_month:$('#dp_from_month').val()};
          ajax_pager_data('#page_tb > tbody',values);
    }
    reBind();
 $(document).ready(function() {


        $('#dp_branch_exe_id').select2().on('change',function(){

          get_data('{$manage_exe_branch}',{no: $(this).val()},function(data){
            $('#dp_manage_exe_id').html('');
              $('#dp_manage_exe_id').append('<option></option>');
             // $("#dp_manage_exe_id").select2('val','');
                 //$("#dp_cycle_exe_id").select2('val','');
                 //$("#dp_department_exe_id").select2('val','');
            $.each(data,function(index, item)
            {
            $('#dp_manage_exe_id').append('<option value=' + item.ST_ID + '>' + item.ST_NAME + '</option>');
            });
            });

        });
        $('#dp_manage_exe_id').select2().on('change',function(){

        });

            $('#dp_type').select2().on('change',function(){

        });
              $('#dp_class_name').select2().on('change',function(){

        });
        $('#dp_from_month').select2().on('change',function(){

        });

                       $('button[data-action="submit"]').click(function(e){
        e.preventDefault();
        if(confirm('هل تريد الحفظ  ؟!')){
            $(this).attr('disabled','disabled');
            var form = $(this).closest('form');
            ajax_insert_update(form,function(data){
           // alert(data);
                if(parseInt(data)>=1){
                 //alert(data);
                   success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                  // alert(data);

                      get_to_link(window.location.href);
                }else{
                     danger_msg('لم يتم الحفظ');
                }
            },'html');
        }
        setTimeout(function() {
            $('button[data-action="submit"]').removeAttr('disabled');
        }, 3000);

});


});



    function reBind(){


            $('select[name="status[]"]').select2().on('change',function(){

                //  checkBoxChanged();
                if($(this).val()==3)
                {
                    $(this).closest('tr').find('select[name="is_end[]"]').select2('val',2);
                    $(this).closest('tr').find('input[name="persant[]"]').val(100);
                    //$(this).closest('tr').find('input[name="notes[]"]').prop("disabled",true);

                }
                else if($(this).val()==1)
                {
                    $(this).closest('tr').find('select[name="is_end[]"]').select2('val',1);
                    $(this).closest('tr').find('input[name="persant[]"]').val(0);
                    //$(this).closest('tr').find('input[name="notes[]"]').prop("disabled",true);

                }
                else if($(this).val()==4)
                {
                    $(this).closest('tr').find('select[name="is_end[]"]').select2('val',2);
                    //$(this).closest('tr').find('input[name="persant[]"]').val(0);
                    //$(this).closest('tr').find('input[name="notes[]"]').prop("disabled",true);

                }
                else
                {
                    $(this).closest('tr').find('select[name="is_end[]"]').select2('val',1);
                    $(this).closest('tr').find('input[name="persant[]"]').val('');
                    $(this).closest('tr').find('input[name="notes[]"]').prop("disabled",false);
                }

            });

            $('select[name="is_end[]"]').select2().on('change',function(){

                //  checkBoxChanged();

                if( $(this).closest('tr').find('select[name="status[]"]').val()==3)
                {
                    danger_msg('اجراء خاطئ الخطة لابد ان تكون منتهية لانها منجزة!!');
                    $(this).select2('val',2);
                }
                else  if( $(this).closest('tr').find('select[name="status[]"]').val()==4)
                {
                    danger_msg('اجراء خاطئ الخطة لابد ان تكون منتهية لانها مكررة!!');
                    $(this).select2('val',2);
                }
                else if( $(this).closest('tr').find('select[name="status[]"]').val()==1)
                {
                    if( $(this).val()==2)
                    {
                        danger_msg('اجراء خاطئ الخطة لابد ان تكون غير منتهية!!');
                        $(this).select2('val',1);
                    }
                }

            });

            $('input[name="persant[]"]').change(function () {
                if( $(this).closest('tr').find('select[name="status[]"]').val()==1)
                {
                    danger_msg('اجراء خاطئ الخطة لابد ان تكون نسبة الانجاز 0% لانها غير منفذ!!');
                    $(this).val(0);
                    // $(this).closest('tr').find('input[name="notes[]"]').prop("disabled",true);
                }
                else if( $(this).closest('tr').find('select[name="status[]"]').val()==3)
                {
                    danger_msg('اجراء خاطئ الخطة لابد ان تكون نسبة الانجاز 100% لانها منجزة!!');
                    $(this).val(100);
                    // $(this).closest('tr').find('input[name="notes[]"]').prop("disabled",true);
                }
                else if( $(this).closest('tr').find('select[name="status[]"]').val()==2)
                {
                    // $(this).val('');
                    $(this).closest('tr').find('input[name="notes[]"]').prop("disabled",false);
                    if($(this).val()==0  || $(this).val()>=100)
                    {
                        danger_msg('اجراء خاطئ الخطة غير منجزة!!');
                        $(this).val('');
                    }
                }

                else
                {
                    $(this).closest('tr').find('input[name="notes[]"]').prop("disabled",false);
                    if($(this).val()<0  || $(this).val()>100)
                    {
                        danger_msg('اجراء خاطئ الخطة يجب النسبة الا تتجاوز 0-100!!');
                        $(this).val('');
                    }
                }

            });


            $('input[name="persant[]"]').keyup(function () {
                if( $(this).closest('tr').find('select[name="status[]"]').val()==1)
                {
                    danger_msg('اجراء خاطئ الخطة لابد ان تكون نسبة الانجاز 0% لانها غير منفذ!!');
                    $(this).val(0);
                    // $(this).closest('tr').find('input[name="notes[]"]').prop("disabled",true);
                }
                else  if( $(this).closest('tr').find('select[name="status[]"]').val()==3)
                {
                    danger_msg('اجراء خاطئ الخطة لابد ان تكون نسبة الانجاز 100% لانها منجزة!!');
                    $(this).val(100);
                    // $(this).closest('tr').find('input[name="notes[]"]').prop("disabled",true);
                }
                else if( $(this).closest('tr').find('select[name="status[]"]').val()==2)
                {
                    $(this).closest('tr').find('input[name="notes[]"]').prop("disabled",false);
                    if($(this).val()==0  || $(this).val()>=100)
                    {
                        danger_msg('اجراء خاطئ الخطة يجب النسبة الا تتجاوز 0-100!!');
                        $(this).val('');
                    }
                }
                else
                {
                    $(this).closest('tr').find('input[name="notes[]"]').prop("disabled",false);
                    if($(this).val()<0  || $(this).val()>100)
                    {
                        danger_msg('اجراء خاطئ الخطة غير منجزة!!');
                        $(this).val('');
                    }
                }


            });
        }

function return_adopt(type) {

   if (type == 1) {

    var url = '{$adopt_all_url}';
        var tbl = '#{$TB_NAME}_tb';
        var container = $('#' + $(tbl).attr('data-container'));
         var val = [];
         var ser_val = [];
         var ser_status=[];
         var ser_next_month_val=[];


        $('input[name="ischeck[]"]').each(function (i) {

        if($(this).attr('data-check')=='1')
        {

           val[i]=$(this).attr('data-id');
           ser_val[i]=$(this).attr('ser_plan');
           ser_status[i]=$(this).attr('ser_status_val');
           ser_next_month_val[i]=$(this).attr('ser_next_month_val');

        }


    });

//console.log();
        if(val.length > 0){
            if(confirm('سوف يتم اعتماد جميع السجلات المدخلة هل ترغب بالاعتماد؟!')){

              get_data(url,{ser: val, ser_plan: ser_val, ser_status: ser_status,ser_next_month_val:ser_next_month_val},function(data){
                 success_msg('رسالة','تم عملية الاعتماد بنجاح ..');
               window.location.reload();
            });

            }
        }else
            alert('لايوجد سجلات ممكن اعتمادها');



}

}

/////////////
    $('.group-checkable').click(function () {
    $('input:checkbox').prop('checked', this.checked);
    });
</script>

SCRIPT;

sec_scripts($scripts);

?>
