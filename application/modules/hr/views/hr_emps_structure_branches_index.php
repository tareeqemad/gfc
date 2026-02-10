<?php
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 09/03/19
 * Time: 01:18 م
 */

$MODULE_NAME= 'hr';
$TB_NAME= 'hr_emps_structure_tree_branches';

$create_url= base_url("$MODULE_NAME/$TB_NAME/create");
$edit_url= base_url("$MODULE_NAME/$TB_NAME/edit");
$get_url= base_url("$MODULE_NAME/$TB_NAME/get");
$edit_emp_url= base_url("$MODULE_NAME/$TB_NAME/edit_emp");
$get_print_url = base_url("$MODULE_NAME/$TB_NAME/get_to_print");
echo AntiForgeryToken();

?>


<div class="row">
    <div class="toolbar">

        <div class="caption"><?=$title?></div>

        <ul>
            <?php if( HaveAccess($get_url)): ?><li><a  onclick="javascript:get_emp();" href="javascript:;"><i class="glyphicon glyphicon-edit"></i>تحرير</a> </li> <?php endif;?>
            <?php if( HaveAccess($edit_url)): ?><li><a  onclick="javascript:emp_edit();" href="javascript:;"><i class="glyphicon glyphicon-transfer"></i>نقل </a> </li> <?php endif;?>
            <?php if( HaveAccess($get_print_url)): ?> <li><a onclick="javascript:emp_print();" href="javascript:;"><i class="glyphicon glyphicon-print"></i>طباعة اكسل</a> </li> <?php endif;?>
            <li><a  onclick="$.fn.tree.expandAll()" href="javascript:;"><i class="glyphicon  glyphicon-sort-by-attributes-alt"></i>توسيع</a> </li>
            <li><a  onclick="$.fn.tree.collapseAll()" href="javascript:;"><i class="glyphicon  glyphicon-sort-by-attributes"></i>طي</a> </li>
            <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>
        </ul>

    </div>


    <div class="form-body">
        <div id="msg_container"></div>

        <div class="input-group col-sm-4">
            <fieldset>
                هيكلية النظام الاداري الخاصة بالاجازات والاذونات والتكاليف
            </fieldset>
        </div>

        <div class="form-group">
            <div class="input-group col-sm-2">
                <span class="input-group-addon">  <i class="icon icon-search"></i></span>

                <input type="text" id="search-tree" class="form-control" placeholder="بحث">
            </div>
        </div>
        <?= $tree ?>
    </div>

</div>

<!-- Add Edit Emps Modal -->
<div class="modal fade" id="employee_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">اضافة موظف</h4>
            </div>
            <form class="form-horizontal" id="employee_form" method="post" action="<?=$create_url?>" role="form" novalidate="novalidate">
                <div class="modal-body inline_form">

                    <div class="form-group">
                        <label class="col-sm-3 control-label">الموظف</label>
                        <div class="col-sm-7">
                            <input type="hidden" name="manager_no" id="h_manager_no" />
                            <input type="hidden" name="ser" id="h_ser" />
                            <select name="employee_no" id="dp_employee_no" class="form-control"  >
                                <option value=""></option>
                                <?php foreach($employee_all as $row) :?>
                                    <option value="<?=$row['NO']?>"><?=$row['NO'].': '.$row['NAME']?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">الوظيفة</label>
                        <div class="col-sm-7">
                            <select name="job_id" id="dp_job_id" class="form-control"  >
                                <option value="">_________</option>
                                <?php foreach($jobs_all as $row) :?>
                                    <option value="<?=$row['NO']?>"><?=$row['NO'].': '.$row['NAME_J']?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">المسمى الإشرافي</label>
                        <div class="col-sm-7">
                            <select name="supervision" id="dp_supervision" class="form-control"  >
                                <option value="">_________</option>
                                <?php foreach($extra_name as $row) :?>
                                    <option value="<?=$row['CON_NO']?>"><?=$row['CON_NO'].': '.$row['CON_NAME']?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">المدير البديل</label>
                        <div class="col-sm-7">
                            <select name="alternative_manager_no" id="dp_alternative_manager_no" class="form-control"  >
                                <option value="">_________</option>
                                <?php foreach($employee_all as $row) :?>
                                    <option value="<?=$row['NO']?>"><?=$row['NO'].': '.$row['NAME']?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group" title="يتم تطبيقها على المسؤول وموظفينه">
                        <label class="col-sm-3 control-label">  مسموح ادخال تكليف عمل له</label>
                        <div class="col-sm-7">
                            <select name="open_assigning_work" id="dp_open_assigning_work" class="form-control" />
                            <option value="1">نعم</option>
                            <option value="0">لا</option>
                            </select>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <?php if ( HaveAccess($create_url) or HaveAccess($edit_emp_url) ) : ?>
                        <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                    <?php endif; ?>
                    <button type="button" class="btn btn-default" data-dismiss="modal">إغلاق</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Add Edit Emps Modal -->


<div class="modal fade" id="manager_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">نقل موظفين </h4>
            </div>
            <form class="form-vertical" id="manager_form" method="post" action="<?=$edit_url?>" role="form" novalidate="novalidate">
                <div class="modal-body inline_form">

                    <div class="form-group col-sm-6">
                        <label class="control-label"> المدير </label>
                        <div>
                            <input type="hidden" name="ser" id="h_sers" />

                            <select name="manager_no" id="dp_manager_no" class="form-control"  >
                                <option value=""></option>
                                <?php foreach($employee_all as $row) :?>
                                    <option value="<?=$row['NO']?>"><?=$row['NO'].': '.$row['NAME']?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <?php if ( HaveAccess($edit_url) ) : ?>
                        <button type="submit" data-action="submit" class="btn btn-primary">نقل </button>
                    <?php endif; ?>
                    <button type="button" class="btn btn-default" data-dismiss="modal">إغلاق</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->



<div class="modal fade" id="print_modal">
    <div class="modal-dialog _750">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">طباعة </h4>
            </div>
            <div class="modal-body inline_form">


            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<?php

$scripts = <<<SCRIPT

<script>

    function emp_print(){
        $('#print_modal .modal-body').text('');
        var parentId =$.fn.tree.selected().attr('data-no');
        get_data('{$get_print_url}',{employee_no:parentId} ,function(data){
            $('#print_modal .modal-body').html(data);
        },'html');
        $('#print_modal').modal();
        setTimeout(function() {
            $('#print_modal #page_tb').tableExport({type:'excel',escape:'false'});
        }, 1700);
    }

    $(function () {
        $('#emps_structure_tree').tree();
        $('#dp_employee_no,#dp_manager_no,#dp_job_id,#dp_supervision,#dp_alternative_manager_no,#dp_open_assigning_work').select2();
        $('.checkboxes:first').hide();
    });

    $('#employee_form button[data-action="submit"]').click(function(e){
        e.preventDefault();

        if( $('#dp_employee_no').select2('val') == '' )
            return 0;

        var form = $(this).closest('form');
        var isCreate = form.attr('action').indexOf('create') >= 0;
        var emp_text = $('#dp_employee_no').select2('data').text;

        ajax_insert_update(form,function(data){

            if(isCreate){
                var obj = jQuery.parseJSON(data);
                $.fn.tree.add( ( emp_text ),obj.id,"javascript:get('"+obj.id+"');");
                $('span[data-id="'+obj.id+'"]').attr('data-no',obj.no);
            }else{
                if(data == '1'){
                    //$.fn.tree.update(form.find('input[name="employee_no"]').val());
                }
            }
            $('#employee_modal').modal('hide');
            success_msg('رسالة','تم حفظ البيانات بنجاح ..');
        },"json");
    });

    function get_emp(){
        if($.fn.tree.level() == 1){
            alert('اختر الموظف الذي تريد عرض بياناته');
            return 0;
        }
        var elem =$.fn.tree.selected();
        var id = elem.attr('data-id');
        $('#h_ser').val(id);
        get_data('{$get_url}',{ser:id},function(item){
            $('#employee_form').attr('action','{$edit_emp_url}');
            $('#dp_employee_no').select2('val',item.EMPLOYEE_NO);
            $('#dp_employee_no').select2('readonly',1);
            $('#dp_job_id').select2('val',item.JOB_ID);
            $('#dp_supervision').select2('val',item.SUPERVISION);
            $('#dp_alternative_manager_no').select2('val',item.ALTERNATIVE_MANAGER_NO);
            $('#dp_open_assigning_work').select2('val',item.OPEN_ASSIGNING_WORK);
            $('#employee_modal').modal();
        });
    }

    function emp_edit(){
        var val = [];
        $('.checkboxes:checked').each(function (i) {
            val[i] = $(this).val();
        });

        if(val.length==0){
            alert('اختر الموظفين المراد نقلهم');
            return 0;
        }

        $('#manager_modal .modal-title').text('نقل '+val.length+' موظفين');

        $('#dp_manager_no').select2('val','');
        clearForm( $('#manager_form')  );
        $('#h_sers').val(val);
        $('#manager_modal').modal();
    }

    $('#manager_form button[data-action="submit"]').click(function(e){
        e.preventDefault();

        if( $('#dp_manager_no').select2('val') == '' )
            return 0;

        var form = $(this).closest('form');

        ajax_insert_update(form,function(data){
            if(data >= 1){
                $('#manager_modal').modal('hide');
                success_msg('رسالة','تم نقل '+data+' موظفين');
                setTimeout(function() {
                    get_to_link(window.location.href);
                }, 700);
            }else{
                danger_msg('تحذير','لم يتم النقل !!<br>'+data);
            }
        },"html");
    });

</script>
SCRIPT;

sec_scripts($scripts);

?>
