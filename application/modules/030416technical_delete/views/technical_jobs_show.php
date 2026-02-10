<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 06/07/15
 * Time: 11:06 ص
 */

$MODULE_NAME= 'technical';
$TB_NAME= 'technical_jobs';
$back_url=base_url("$MODULE_NAME/$TB_NAME/index");

$post_url= base_url("$MODULE_NAME/$TB_NAME/".($action == 'index'?'create':$action));
$create_url =base_url("$MODULE_NAME/$TB_NAME/create");
//$edit_approved_url =base_url("$MODULE_NAME/$TB_NAME/edit_approved");
//$edit_quote_url =base_url("$MODULE_NAME/$TB_NAME/edit_quote");
$get_url= base_url("$MODULE_NAME/$TB_NAME/get");

$select_items_url=base_url("stores/classes/public_index");
$get_class_url =base_url('stores/classes/public_get_id');
//$print_url= 'http://itdev:801/gfc.aspx';
$delete_url_plane = base_url("$MODULE_NAME/$TB_NAME/delete_details_plane");
$isCreate =isset($job_data) && count($job_data)  > 0 ?false:true;
$HaveRs = (!$isCreate)? true:false;
$rs=$isCreate ?array() : $job_data[0];

echo AntiForgeryToken();
?>

<div class="row">
    <div class="toolbar">

        <div class="caption"><?=$title?></div>

        <ul>
            <?php if( HaveAccess($create_url)):  ?><li><a  href="<?=$create_url?>"><i class="glyphicon glyphicon-plus"></i>جديد </a> </li><?php endif; ?>
            <?php if( HaveAccess($back_url)):  ?><li><a  href="<?= $back_url ?>"><i class="icon icon-reply"></i> </a> </li><?php  endif; ?>
            <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>
        </ul>

    </div>
</div>
<div class="form-body">

    <div id="msg_container"></div>
    <div id="container">
        <form class="form-vertical" id="<?=$TB_NAME?>_form" method="post" action="<?=$post_url?>" role="form" novalidate="novalidate">
            <div class="modal-body inline_form">

                <div class="form-group col-sm-1">
                    <label class="control-label">رقم المهمة</label>
                    <div>
                        <input type="text" readonly value="<?=$HaveRs?$rs['JOB_ID']:''?>" id="txt_Job_id" class="form-control" />
                        <?php if (( isset($can_edit)?$can_edit:false) ) : ?>
                            <input type="hidden" value="<?=$HaveRs?$rs['JOB_ID']:''?>" name="job_id" id="h_job_id">
                        <?php endif; ?>
                    </div>
                </div>

                <div class="form-group col-sm-3">
                    <label class="control-label">اسم المهمة</label>
                    <div>
                        <input type="text" value="<?=$HaveRs?$rs['JOB_NAME']:''?>" name="job_name" id="txt_job_name" class="form-control" />
                    </div>
                </div>

                <div class="form-group col-sm-7">
                    <label class="control-label">توصيف المهمة</label>
                    <div>
                        <input type="text" value="<?=$HaveRs?$rs['JOB_DESCRIPTION']:''?>" name="job_description" id="txt_job_description" class="form-control" />
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">الوقت المحدد لإنجاز المهمة</label>
                    <div>
                        <input type="text" name="specified_time" value="<?=$HaveRs?$rs['SPECIFIED_TIME']:''?>" id="txt_specified_time" class="form-control" data-val="true" data-val-required="حقل مطلوب"   maxlength="5"  data-val-regex="صيغة الوقت غير صحيحة" data-val-regex-pattern="^(?:(?:([01]?\d|2[0-3]):)?([0-5]?\d))?([0-0]?\d)$" />
                        <span class="field-validation-valid" data-valmsg-for="specified_time" data-valmsg-replace="true"></span>
                    </div>
                </div>

                <div class="form-group col-sm-7">
                    <label class="control-label">ملاحظات</label>
                    <div>
                        <input type="text" value="<?=$HaveRs?$rs['NOTES']:''?>" name="notes" id="txt_notes" class="form-control" />
                    </div>
                </div>

                <div style="clear: both"></div>
                <input type="hidden" id="h_data_search" />

                <?php echo modules::run("$MODULE_NAME/$TB_NAME/public_get_details_1", ($HaveRs)?$rs['JOB_ID']:0, ($HaveRs)?1:0 ); ?>
                <?php echo modules::run("$MODULE_NAME/$TB_NAME/public_get_details_2", ($HaveRs)?$rs['JOB_ID']:0, ($HaveRs)?1:0 ); ?>
                <?php /* echo modules::run("$MODULE_NAME/$TB_NAME/public_get_details_3", ($HaveRs)?$rs['JOB_ID']:0, ($HaveRs)?1:0 ); */ ?>
                <?php echo modules::run("$MODULE_NAME/$TB_NAME/public_get_details_4", ($HaveRs)?$rs['JOB_ID']:0, ($HaveRs)?1:0 ); ?>

            </div>

            <div class="modal-footer">
                <?php if (  HaveAccess($post_url) && ($isCreate || ( isset($can_edit)?$can_edit:false) )  ) : ?>
                    <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                <?php endif; ?>

                <?php if ( !$isCreate  and 0 ) : ?>
                    <button type="button" id="print_rep" onclick="javascript:print_rep();" class="btn btn-success"> <i class="glyphicon glyphicon-print"></i> طباعة </button>
                <?php endif; ?>

                <?php if ($isCreate and 0 ): ?>
                    <button type="button" onclick="javascript:clear_form();"  class="btn btn-default"> تفريغ الحقول</button>
                <?php endif; ?>
            </div>

        </form>
    </div>
</div>

<?php
$scripts = <<<SCRIPT
<script>

    var count_1 = 0;
    var count_2 = 0;
    var count_3 = 0;
    var count_4 = 0;

    var worker_jobs_json= {$worker_jobs};
    var select_worker_jobs= '';

    var cars_json= {$cars};
    var select_cars= '';

    $.each(worker_jobs_json, function(i,item){
        select_worker_jobs += "<option value='"+item.CON_NO+"' >"+item.CON_NAME+"</option>";
    });

    $.each(cars_json, function(i,item){
        select_cars += "<option value='"+item.CON_NO+"' >"+item.CON_NAME+"</option>";
    });

    reBind();

    $('select[name="worker_job_id[]"]').append(select_worker_jobs);
    $('select[name="car_id[]"]').append(select_cars);

    $('select[name="worker_job_id[]"]').each(function(){
        $(this).val($(this).attr('data-val'));
    });

    $('select[name="car_id[]"]').each(function(){
        $(this).val($(this).attr('data-val'));
    });

    $('select[name="worker_job_id[]"] ,select[name="car_id[]"]').select2();

    $('button[data-action="submit"]').click(function(e){
        e.preventDefault();
        var msg= 'هل تريد حفظ المهمة ؟!';
        if(confirm(msg)){
            $(this).attr('disabled','disabled');
            var form = $(this).closest('form');
            ajax_insert_update(form,function(data){
                if(parseInt(data)>1){
                    success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                    get_to_link('{$get_url}/'+parseInt(data)+'/edit');
                }else if(data==1){
                    success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                    get_to_link(window.location.href);
                }else{
                    danger_msg('تحذير..',data);
                }
            },'html');
        }
        setTimeout(function() {
            $('button[data-action="submit"]').removeAttr('disabled');
        }, 3000);
    });

    function addRow_1(){
        count_1 = count_1+1;
        var html ='<tr> <td><i class="glyphicon glyphicon-sort" /></i></td> <td><input type="hidden" name="w_ser[]" value="0" /> <select name="worker_job_id[]" class="form-control" id="txt_worker_job_id'+count_1+'" /></select> </td> <td><input name="worker_count[]" class="form-control" id="txt_worker_count'+count_1+'" /></td> <td><input name="task[]" class="form-control" id="txt_task'+count_1+'" /></td> </tr>';
        $('#details_tb_1 tbody').append(html);
        reBind(1);
    }

    function addRow_2(){
        count_2 = count_2+1;
        var html ='<tr> <td><i class="glyphicon glyphicon-sort" /></i></td> <td><input type="hidden" name="c_ser[]" value="0" /> <select name="car_id[]" class="form-control" id="txt_car_id'+count_2+'" /></select> </td> <td><input name="car_count[]" class="form-control" id="txt_car_count'+count_2+'" /></td> <td><input name="need_description[]" class="form-control" id="txt_need_description'+count_2+'" /></td> </tr>';
        $('#details_tb_2 tbody').append(html);
        reBind(2);
    }

    function addRow_3(){
        count_3 = count_3+1;
        var html ='<tr> <td><i class="glyphicon glyphicon-sort" /></i></td> <td><input type="hidden" name="t_ser[]" value="0" /> <input name="class[]" class="form-control" id="i_txt_class_id'+count_3+'" /> <input type="hidden" name="class_id[]" id="h_txt_class_id'+count_3+'" /></td> <td><input name="class_name[]" readonly class="form-control" id="txt_class_id'+count_3+'" /></td> <td><input name="class_unit[]" disabled class="form-control" id="unit_name_txt_class_id'+count_3+'" /></td> <td><input name="class_count[]" class="form-control" id="txt_class_count'+count_3+'" /></td>';
        $('#details_tb_3 tbody').append(html);
        reBind(3);
    }
       function addRow_4(){
        var counter=count_4+2;
        count_4 = count_4+1;

        var html ='<tr> <td><i class="glyphicon glyphicon-sort" /></i><input type="hidden" name="p_ser[]" value="0" /> </td> <td> <input name="plane_step_ser[]"  data-val="true" value="'+counter+'" data-val-required="required" class="form-control"  id="txt_plane_step_ser_'+count_4+'" /></td> <td>  <input name="plane_step[]"  data-val="true" data-val-required="required" class="form-control"  id="txt_plane_step_'+count_4+'" /></td> <td><input name="time_estimated[]" class="form-control" id="txt_time_estimated_'+count_4+'" /></td>';
        $('#details_tb_4 tbody').append(html);
        reBind(4);
    }

function calc_all_estimated_times() {

    var total_time_estimated = 0;
     $('input[name="time_estimated[]"]').each(function () {
        var time_estimated = $(this).closest('tr').find('input[name="time_estimated[]"]').val();
        total_time_estimated += Number(time_estimated);
        $('#total_time_estimated').text(isNaNVal(Number(total_time_estimated)));
    });

}
$('input[name="time_estimated[]"]').change(function () {
calc_all_estimated_times();
});

    function AddRowWithData(id,name_ar,unit,price,unit_name){
        addRow_3();
        $('#h_txt_class_id'+(count_3)).val(id);
        $('#i_txt_class_id'+(count_3)).val(id);
        $('#txt_class_id'+(count_3)).val(name_ar);
        $('#unit_name_txt_class_id'+(count_3)).val(unit_name);
        $('#report').modal('hide');
    }

    function reBind(s){
        if(s==undefined)
            s=0;

        if(s==0 || s==3){
            $('input[name="class_name[]"]').click("focus",function(e){
                _showReport('$select_items_url/'+$(this).attr('id')+ $('#h_data_search').val() );
            });

            $('input[name="class[]"]').bind("focusout",function(e){
                var id= $(this).val();
                var class_id= $(this).closest('tr').find('input[name="class_id[]"]');
                var name= $(this).closest('tr').find('input[name="class_name[]"]');
                var unit= $(this).closest('tr').find('input[name="class_unit[]"]');
                var class_count= $(this).closest('tr').find('input[name="class_count[]"]');
                if(id==''){
                    class_id.val('');
                    name.val('');
                    unit.val('');
                    return 0;
                }
                get_data('{$get_class_url}',{id:id, type:1},function(data){
                    if (data.length == 1){
                        var item= data[0];
                        class_id.val(item.CLASS_ID);
                        name.val(item.CLASS_NAME_AR);
                        unit.val(item.UNIT_NAME);
                        class_count.focus();
                    }else{
                        class_id.val('');
                        name.val('');
                        unit.val('');
                    }
                });
            });

            $('input[name="class[]"]').bind('keyup', '+', function(e) {
                $(this).val('');
                var class_name= $(this).closest('tr').find('input[name="class_name[]"]');
                actuateLink(class_name);
            });
        }

        if(s==1){
            $('select#txt_worker_job_id'+count_1).append('<option></option>'+select_worker_jobs).select2();
        }
        if(s==2){
            $('select#txt_car_id'+count_2).append('<option></option>'+select_cars).select2();
        }

       $('input[name="time_estimated[]"]').change(function () {
          calc_all_estimated_times();
        });
         calc_all_estimated_times();
    }


function delete_row_det_plane(id) {
    if (confirm('هل تريد بالتأكيد حذف هذا الإجراء ؟!!')) {

        var values = {id: id};
        get_data('{$delete_url_plane}', values, function (data) {

            if (data == '1') {

                success_msg('تمت عملية الحذف بنجاح');
                get_to_link(window.location.href);
            }

            else {
                danger_msg('لم يتم الحذف', data);
            }

        }, 'html');

    }

}


SCRIPT;

if($isCreate){
    $scripts = <<<SCRIPT
    {$scripts}

    $(function() {
        $( "#details_tb_1 tbody, #details_tb_2 tbody, #details_tb_3 tbody, #details_tb_4 tbody" ).sortable();
    });

    function clear_form(){
        clearForm($('#{$TB_NAME}_form'));
    }

    </script>
SCRIPT;

}else{
    $scripts = <<<SCRIPT
    {$scripts}

    count_1 = {$rs['COUNT_DET_1']} -1;
    count_2 = {$rs['COUNT_DET_2']} -1;
    count_3 = {$rs['COUNT_DET_3']} -1;
    count_4 = {$rs['COUNT_DET_4']} -1;
    </script>
SCRIPT;
}
sec_scripts($scripts);
?>
