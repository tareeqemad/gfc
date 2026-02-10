<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 16/05/16
 * Time: 09:58 ص
 */

$MODULE_NAME= 'hr';
$TB_NAME= 'evaluation_tree';

$get_form_url= base_url("$MODULE_NAME/$TB_NAME/get_form");
$get_axes_url= base_url("$MODULE_NAME/$TB_NAME/get_axes");
$get_ask_url= base_url("$MODULE_NAME/$TB_NAME/get_ask");
$get_marks_url= base_url("$MODULE_NAME/$TB_NAME/public_get_marks");
$create_ask_url= base_url("$MODULE_NAME/$TB_NAME/create_ask");
$edit_ask_url= base_url("$MODULE_NAME/$TB_NAME/edit_ask");
$status_ask_url= base_url("$MODULE_NAME/$TB_NAME/status_ask");
$get_emps_url= base_url("$MODULE_NAME/$TB_NAME/get_emps");
$get_jobs_url= base_url("$MODULE_NAME/$TB_NAME/get_jobs");
$save_jobs_url= base_url("$MODULE_NAME/$TB_NAME/save_jobs");

$report_url = base_url("JsperReport/showreport?sys=hr/Employees_Evaluation");
$report_sn= report_sn();

echo AntiForgeryToken();
?>

<div class="row">
    <div class="toolbar">

        <div class="caption"><?=$title?></div>

        <ul>
            <?php if( HaveAccess($create_ask_url)): ?> <li><a onclick="javascript:create_ask();" href="javascript:;"><i class="glyphicon glyphicon-plus"></i>اضافة سؤال </a> </li> <?php endif;?>
            <?php if( HaveAccess($status_ask_url)): ?><li><a  onclick="javascript:status_ask();" href="javascript:;"><i class="glyphicon glyphicon-remove"></i>حذف السؤال</a> </li> <?php endif;?>
            <?php if(1): ?><li><a onclick="javascript:form_print($.fn.tree.selected().attr('data-id'),0);" href="javascript:;"><i class="glyphicon glyphicon-print"></i> طباعة </a> </li> <?php endif;?>
            <?php if(1): ?><li><a onclick="javascript:form_print($.fn.tree.selected().attr('data-id'),1);" href="javascript:;"><i class="glyphicon glyphicon-list-alt"></i> اكسل </a> </li> <?php endif;?>
            <?php if( HaveAccess($get_jobs_url)): ?><li><a  onclick="javascript:get_jobs($.fn.tree.selected().attr('data-id'));" href="javascript:;"><i class="glyphicon glyphicon-list"></i>الوظائف التابعة للنموذج</a> </li> <?php endif;?>
            <?php if( HaveAccess($get_emps_url)): ?><li><a  onclick="javascript:get_emps($.fn.tree.selected().attr('data-id'));" href="javascript:;"><i class="glyphicon glyphicon-list-alt"></i>الموظفين التابعين للنموذج</a> </li> <?php endif;?>
            <li><a  onclick="$.fn.tree.expandAll()" href="javascript:;"><i class="glyphicon  glyphicon-sort-by-attributes-alt"></i>توسيع</a> </li>
            <li><a  onclick="$.fn.tree.collapseAll()" href="javascript:;"><i class="glyphicon  glyphicon-sort-by-attributes"></i>طي</a> </li>
            <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>
        </ul>

    </div>

    <div class="form-body">

        <div id="msg_container"></div>
        <div class="form-group">
            <div class="input-group col-sm-2">
                <span class="input-group-addon">  <i class="icon icon-search"></i></span>

                <input type="text" id="search-tree" class="form-control" placeholder="بحث">
            </div>
        </div>
        <?= $tree ?>

    </div>

</div>


<div class="modal fade" id="form_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">بيانات النموذج </h4>
            </div>
            <form class="form-vertical" id="form_form" method="post" action="" role="form" novalidate="novalidate">
                <div class="modal-body inline_form">

                    <div class="form-group col-sm-6">
                        <label class="control-label"> اسم النموذج </label>
                        <div>
                            <input type="text" name="evaluation_form_name" id="txt_evaluation_form_name" class="form-control" />
                            <input type="hidden" name="evaluation_form_id" id="h_evaluation_form_id" />
                        </div>
                    </div>

                    <div class="form-group col-sm-12">
                        <label class="control-label"> الوصف </label>
                        <div>
                            <input type="text" name="description" id="txt_description" class="form-control" />
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">إغلاق</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- get emps الموظفين التابعين لنموذج محدد -->
<div class="modal fade" id="emps_modal">
    <div class="modal-dialog">
        <div class="modal-content _750">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">الموظفين التابعين للنموذج</h4>
            </div>
                <div class="modal-body inline_form">
                    <table class="table" id="emps_list_tb" data-container="container">
                        <thead>
                        <tr>
                            <th style="width: 5%">#</th>
                            <th style="width: 15%">الرقم الوظيفي</th>
                            <th style="width: 40%">الإسم</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- get emps الموظفين التابعين لنموذج محدد -->

<div class="modal fade" id="jobs_modal">
    <div class="modal-dialog">
        <div class="modal-content _750">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">الوظائف التابعة للنموذج</h4>
            </div>
            <form class="form-vertical" id="jobs_form" method="post" action="<?=$save_jobs_url?>" role="form" novalidate="novalidate">
                <div class="modal-body inline_form">
                    <input type="hidden" name="evaluation_form_id" id="h_evaluation_form_id" />
                    <table class="table" id="jobs_details_tb" data-container="container">
                        <thead>
                        <tr>
                            <th style="width: 5%">#</th>
                            <th style="width: 15%">الوظيفة</th>
                            <th style="width: 40%">ملاحظات</th>
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                        <tfoot>
                        <tr>
                            <th></th>
                            <th>
                                <?php if ( HaveAccess($save_jobs_url) ) { ?>
                                    <a onclick="javascript:addRow();" href="javascript:;"><i class="glyphicon glyphicon-plus"></i>جديد</a>
                                <?php } ?>
                            </th>
                            <th></th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="modal-footer">
                    <?php if ( HaveAccess($save_jobs_url)  ) : ?>
                        <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                    <?php endif; ?>
                    <button type="button" class="btn btn-default" data-dismiss="modal">إغلاق</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="axes_modal">
    <div class="modal-dialog">
        <div class="modal-content _750">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">بيانات المحور </h4>
            </div>
            <form class="form-vertical" id="axes_form" method="post" action="" role="form" novalidate="novalidate">
                <div class="modal-body inline_form">

                    <div class="form-group col-sm-4">
                        <label class="control-label"> النموذج التابع له </label>
                        <div>
                            <input type="text" name="evaluation_form_id_name" id="txt_evaluation_form_id_name" class="form-control" />
                            <input type="hidden" name="evaluation_form_id" id="h_evaluation_form_id" />
                        </div>
                    </div>

                    <div class="form-group col-sm-1">
                        <label class="control-label"> ترتيب المحور </label>
                        <div>
                            <input type="text" name="eaxes_order" id="txt_eaxes_order" class="form-control" />
                        </div>
                    </div>

                    <div class="form-group col-sm-4">
                        <label class="control-label"> المحور </label>
                        <div>
                            <input type="text" name="eaxes_id_name" id="txt_eaxes_id_name" class="form-control" />
                            <input type="hidden" name="eaxes_id" id="h_eaxes_id" />
                            <input type="hidden" name="efa_id" id="h_efa_id" />
                        </div>
                    </div>

                    <div class="form-group col-sm-1">
                        <label class="control-label"> الوزن النسبي </label>
                        <div>
                            <input type="text" name="eaxes_relative_weight" id="txt_eaxes_relative_weight" class="form-control" />
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">إغلاق</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<div class="modal fade" id="ask_modal">
    <div class="modal-dialog">
        <div class="modal-content _750">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">بيانات السؤال </h4>
            </div>
            <form class="form-vertical" id="ask_form" method="post" action="<?=$create_ask_url?>" role="form" novalidate="novalidate">
                <div class="modal-body inline_form">

                    <div class="form-group col-sm-4">
                        <label class="control-label"> المحور التابع له </label>
                        <div>
                            <input type="text" readonly name="eaxes_name" id="txt_eaxes_name" class="form-control" />
                            <input type="hidden" name="efa_id" id="h_efa_id" />
                        </div>
                    </div>

                    <div class="form-group col-sm-1">
                        <label class="control-label"> ترتيب السؤال </label>
                        <div>
                            <input type="text" name="evaluation_element_order" id="txt_evaluation_element_order" class="form-control" />
                        </div>
                    </div>

                    <div class="form-group col-sm-4">
                        <label class="control-label"> السؤال  </label>
                        <div>
                            <input type="text" name="evaluation_element_name" id="txt_evaluation_element_name" class="form-control" />
                            <input type="hidden" name="evaluation_element_id" id="h_evaluation_element_id" />
                        </div>
                    </div>

                    <div class="form-group col-sm-1">
                        <label class="control-label"> الوزن النسبي  </label>
                        <div>
                            <input type="text" name="evaluation_element_weight" id="txt_evaluation_element_weight" class="form-control" />
                        </div>
                    </div>


                    <div class="tb_container">
                        <table class="table" id="marks_details_tb" data-container="container">
                            <thead>
                            <tr>
                                <th style="width: 5%">#</th>
                                <th style="width: 20%">النطاق</th>
                                <th style="width: 15%">من</th>
                                <th style="width: 15%">الى</th>
                                <th style="width: 40%">الوصف</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $cnt=0;
                            foreach($range_order_all as $row) {
                                $cnt++;
                            ?>
                            <tr data-range="<?=$row['CON_NO']?>" >
                                <td>
                                    <?=$cnt?>
                                    <input type="hidden" id="h_marks_ser_<?=$row['CON_NO']?>" name="marks_ser[]" value="0" />
                                </td>
                                <td>
                                    <?=$row['CON_NAME']?>
                                    <input type="hidden" id="h_range_order_<?=$row['CON_NO']?>" value="<?=$row['CON_NO']?>" name="range_order[]" />
                                </td>
                                <td>
                                    <input type="text" id="txt_mark_from_<?=$row['CON_NO']?>" name="mark_from[]" class="form-control" />
                                </td>
                                <td>
                                    <input type="text" id="txt_mark_to_<?=$row['CON_NO']?>" name="mark_to[]" class="form-control" />
                                </td>
                                <td>
                                    <input type="text" id="txt_mark_range_description_<?=$row['CON_NO']?>" name="mark_range_description[]" class="form-control" />
                                </td>
                            </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>


                </div>
                <div class="modal-footer">
                    <?php if ( HaveAccess($create_ask_url) or HaveAccess($edit_ask_url)  ) : ?>
                        <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                    <?php endif; ?>
                    <button type="button" class="btn btn-default" data-dismiss="modal">إغلاق</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<?php

$scripts = <<<SCRIPT

<script>
    $(function () {
        $('#evaluation_tree').tree();

        $.each( $('#evaluation_tree .e_total_weight') , function(i,item){
            if( parseInt($(this).text()) != 100 && $(this).text()!='' ){
                $(this).closest('span').find('.e_name').css("color", "#c10b0b");
            }
        });
    });

    var jobs_json= {$jobs_all};
    var select_jobs= '<option value=""> _________ </option>';

    $.each(jobs_json, function(i,item){
        select_jobs += "<option value='"+item.NO+"' >"+item.NAME_J+"</option>";
    });

    function get(id,type){

        if(type=='FORM'){
            get_data('{$get_form_url}',{id:id},function(data){
                $.each(data, function(i,item){
                    clearForm( $('#form_form') );
                    $('#form_form #txt_evaluation_form_name').val(item.EVALUATION_FORM_NAME);
                    $('#form_form #h_evaluation_form_id').val(item.EVALUATION_FORM_ID);
                    $('#form_form #txt_description').val(item.DESCRIPTION);
                    $('#form_form input[type="text"]').prop('readonly',1);
                    $('#form_modal').modal();
                });
            });

        }else if(type=='AXES'){
            get_data('{$get_axes_url}',{id:id},function(data){
                $.each(data, function(i,item){
                    clearForm( $('#axes_form') );
                    $('#axes_form #txt_evaluation_form_id_name').val(item.EVALUATION_FORM_ID_NAME);
                    $('#axes_form #h_evaluation_form_id').val(item.EVALUATION_FORM_ID);
                    $('#axes_form #txt_eaxes_order').val(item.EAXES_ORDER);
                    $('#axes_form #txt_eaxes_id_name').val(item.EAXES_ID_NAME);
                    $('#axes_form #h_eaxes_id').val(item.EAXES_ID);
                    $('#axes_form #h_efa_id').val(item.EFA_ID);
                    $('#axes_form #txt_eaxes_relative_weight').val(item.EAXES_RELATIVE_WEIGHT);
                    $('#axes_form input[type="text"]').prop('readonly',1);
                    $('#axes_modal').modal();
                });
            });

        }else if(type=='ASK'){
            get_data('{$get_ask_url}',{id:id},function(data){
                $.each(data, function(i,item){
                    clearFormExpected( $('#ask_form') , $('input[name="marks_ser[]"],input[name="range_order[]"]') );
                    $('#ask_form #txt_eaxes_name').val(item.EAXES_NAME);
                    $('#ask_form #h_efa_id').val(item.EFA_ID);
                    $('#ask_form #txt_evaluation_element_order').val(item.EVALUATION_ELEMENT_ORDER);
                    $('#ask_form #txt_evaluation_element_name').val(item.EVALUATION_ELEMENT_NAME);
                    $('#ask_form #h_evaluation_element_id').val(item.EVALUATION_ELEMENT_ID);
                    $('#ask_form #txt_evaluation_element_weight').val(item.EVALUATION_ELEMENT_WEIGHT);

                    get_data('{$get_marks_url}',{id:id},function(data){
                        var mark_tr= $('#marks_details_tb tbody tr');
                        if(data.length > mark_tr.length )
                            alert('عدد السجلات المدخلة للنطاق اكبر من المعروضة، يرجى مراجعة ادارة الحاسوب');
                        $.each(data, function(i,item){
                            $('#h_marks_ser_'+item.RANGE_ORDER).val(item.MARKS_SER);
                            $('#txt_mark_from_'+item.RANGE_ORDER).val(item.MARK_FROM);
                            $('#txt_mark_to_'+item.RANGE_ORDER).val(item.MARK_TO);
                            $('#txt_mark_range_description_'+item.RANGE_ORDER).val(item.MARK_RANGE_DESCRIPTION);
                        });
                    });

                    // $('#txt_parent_id_name').val($.fn.tree.nodeText(item.PARENT_ID));
                    $('#ask_form').attr('action','{$edit_ask_url}');
                    $('#ask_modal').modal();
                });
            });
        }
    }

    function create_ask(){
        clearFormExpected( $('#ask_form') , $('input[name="marks_ser[]"],input[name="range_order[]"]') );
        if($(".tree span.selected").length <= 0 || $.fn.tree.level() != 2){
            if($.fn.tree.level() != 2){
                warning_msg('تحذير ! ','يجب تحديد المحور  المراد الاضافة عليه ..');
            }else{
                warning_msg('تحذير ! ','يجب إختيار الأب ..');
            }
            return;
        }

        var parentId =$.fn.tree.selected().attr('data-id');
            //var productionId= $.fn.tree.lastElem().attr('data-id');
        var parentName = $('.tree li > span.selected').text();

        $('#ask_form #h_efa_id').val(parentId);
            //$('#txt_technical_work_id').val(account_id($.fn.tree.level(),productionId,parentId));
        $('#txt_eaxes_name').val(parentName);
        $('input[name="mark_from[]"]:first').val(0);
        $('input[name="mark_to[]"]:last').val(100);

        $('#txt_mark_to_1,#txt_mark_from_2').val(50);
        $('#txt_mark_to_2,#txt_mark_from_3').val(65);
        $('#txt_mark_to_3,#txt_mark_from_4').val(75);
        $('#txt_mark_to_4,#txt_mark_from_5').val(85);

        $('#ask_form').attr('action','{$create_ask_url}');
        $('#ask_modal').modal();
    }

    $('#ask_form button[data-action="submit"]').click(function(e){
        e.preventDefault();
        var form = $(this).closest('form');
        var isCreate = form.attr('action').indexOf('create') >= 0;

        ajax_insert_update(form,function(data){

            if(isCreate){
                var obj = jQuery.parseJSON(data);
                $.fn.tree.add( (form.find('input[name="evaluation_element_name"]').val()),obj.id,"javascript:get('"+obj.id+"','ASK');");
            }else{
                if(data == '1'){
                    $.fn.tree.update(form.find('input[name="evaluation_element_name"]').val());
                }
            }

            $('#ask_modal').modal('hide');
            success_msg('رسالة','تم حفظ البيانات بنجاح ..');
        },"json");
    });

    function status_ask(){
        if($.fn.tree.level() != 3){
            warning_msg('تحذير','يجب تحديد السؤال المراد حذفه');
            return 0;
        }
        if(confirm('هل تريد الحذف بالتأكيد ؟!!!')){
            var elem =$.fn.tree.selected();
            var id = elem.attr('data-id');
            get_data('{$status_ask_url}',{evaluation_element_id:id},function(data){
                if(data == '1'){
                    $.fn.tree.removeElem(elem);
                    success_msg('رسالة','تم الحذف بنجاح ..');
                }else {
                    danger_msg('تحذير','لم يتم الحذف !!<br>'+data);
                }
            });
        }
    }

    function get_emps(id){
        if($.fn.tree.level() != 1){
            warning_msg('تحذير ! ','اختر النموذج لعرض الموظفين التابعين له ... ');
            return 0;
        }

        var cnt=0;
        get_data('{$get_emps_url}',{id:id},function(data){
            $('#emps_list_tb tbody').html('');
            $.each(data, function(i,item){
                cnt++;
                if(item.EMPLOYEE_NO== null) item.EMPLOYEE_NO= '';
                if(item.EMPLOYEE_NO_NAME== null) item.EMPLOYEE_NO_NAME= '';
                var row_html= '<tr><td>'+cnt+'</td><td>'+item.EMPLOYEE_NO+'</td><td>'+item.EMPLOYEE_NO_NAME+'</td><tr>';
				$('#emps_list_tb tbody').append(row_html);
            });

            $('#emps_modal').modal();
        });
    }

    function get_jobs(id){
        if($.fn.tree.level() != 1){
            warning_msg('تحذير ! ','اختر النموذج ..');
            return 0;
        }

        var cnt=0;
        $('#jobs_form #h_evaluation_form_id').val(id);
        get_data('{$get_jobs_url}',{id:id},function(data){
            $('#jobs_details_tb tbody').html('');
            $.each(data, function(i,item){
                cnt++;
                if(item.JOB_ID== null) item.JOB_ID= '';
                if(item.HINTS== null) item.HINTS= '';
                var row_html= '<tr> <td>'+cnt+'</td> <td><input type="hidden" name="job_ser[]" id="h_job_ser_'+cnt+'" value="'+item.JOB_SER+'" /><select name="job_id[]" class="form-control" id="dp_job_id_'+cnt+'" data-val="'+item.JOB_ID+'" ></select></td> <td><input name="hints[]" class="form-control" id="txt_hints_'+cnt+'" value="'+item.HINTS+'" /></td>';
                $('#jobs_details_tb tbody').append(row_html);
            });
            count= cnt;

            $('select[name="job_id[]"]').append(select_jobs);
            $('select[name="job_id[]"]').each(function(){
                $(this).val($(this).attr('data-val'));
            });
            $('select[name="job_id[]"]').select2();

            $('#jobs_modal').modal();
        });
    }

    function addRow(){
        count = count+1;
        var html= '<tr> <td>'+count+'</td> <td><input type="hidden" name="job_ser[]" id="h_job_ser_'+count+'" value="0" /><select name="job_id[]" class="form-control" id="dp_job_id_'+count+'"  ></select></td> <td><input name="hints[]" class="form-control" id="txt_hints_'+count+'" /></td>';
        $('#jobs_details_tb tbody').append(html);
        reBind();
    }

    function reBind(){
        $('select#dp_job_id_'+count).append(select_jobs).select2();
    }

    $('#jobs_form button[data-action="submit"]').click(function(e){
        e.preventDefault();
        var form = $(this).closest('form');
        ajax_insert_update(form,function(data){
            if(data==1){
                success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                get_jobs( $('#jobs_form #h_evaluation_form_id').val() );
            }else{
                danger_msg('تحذير..',data);
            }
        },"html");
    });


    function form_print(id,typ){
        if($.fn.tree.level() != 1){
            warning_msg('تحذير ! ','اختر النموذج ..');
            return 0;
        }
        if(typ==0)
            _showReport('{$report_url}&report_type=pdf&report=Hr_Form&p_evaluation_form_id='+id+'&sn={$report_sn}',true);
        else
            _showReport('{$report_url}&report_type=xls&report=Hr_Form_xls&p_evaluation_form_id='+id+'&sn={$report_sn}',true);
    }

</script>
SCRIPT;

sec_scripts($scripts);

?>
