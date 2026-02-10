<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * employee: Ahmed Barakat
 * Date: 8/27/14
 * Time: 8:46 AM
 */
$delete_url =base_url('employees/employees/delete');
$get_url =base_url('employees/employees/get_id');
$edit_url =base_url('employees/employees/edit');
$create_url =base_url('employees/employees/create');

?>
<?= AntiForgeryToken() ?>
<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>

        <ul>
            <!-- <?php /*if( HaveAccess($create_url)): */?><li><a onclick="javascript:employee_create();" href="javascript:;"><i class="glyphicon glyphicon-plus"></i>جديد </a> </li><?php /*endif;*/?>
            <?php /*if( HaveAccess($delete_url)): */?><li><a  onclick="javascript:employee_delete();" href="javascript:;"><i class="glyphicon glyphicon-remove"></i>حذف</a> </li><?php /*endif;*/?>
           --> <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>

            <!--     <li><a href="#">بحث</a> </li>-->
        </ul>

    </div>

    <div class="form-body">

        <div id="msg_container"></div>




        <div id="container">
            <form class="form-horizontal" id="employee_from" method="post" action="<?=base_url('employees/employees/create')?>" role="form" novalidate="novalidate">
                <div class="modal-body inline_form">

                    <div class="form-group">
                        <label class="col-sm-1 control-label"> الرقم الوظيفي </label>
                        <div class="col-sm-2">
                            <input type="text" data-val="true"   data-val-required="حقل مطلوب" name="no" id="txt_no" class="form-control">
                            <span class="field-validation-valid" data-valmsg-for="no" data-valmsg-replace="true"></span>
                        </div>

                        <label class="col-sm-1 control-label">  إسم الموظف </label>
                        <div class="col-sm-5">
                            <input type="text" data-val="true"   data-val-required="حقل مطلوب" name="name" id="txt_name" class="form-control">
                            <span class="field-validation-valid" data-valmsg-for="name" data-valmsg-replace="true"></span>
                        </div>

                        <label class="col-sm-1 control-label">  تاريخ الميلاد  </label>
                        <div class="col-sm-2">
                            <input type="text" data-val="true" data-type="date"  data-date-format="DD/MM/YYYY"   data-val-regex="التاريخ غير صحيح!" data-val-regex-pattern="<?= date_format_exp() ?>"   data-val-required="حقل مطلوب" name="birth_date" id="txt_birth_date" class="form-control">
                            <span class="field-validation-valid" data-valmsg-for="birth_date" data-valmsg-replace="true"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-1 control-label">  المقر    </label>
                        <div class="col-sm-2">
                            <select name="branch" id="dp_branch" class="form-control">
                                <option></option>
                                <?php foreach($branches as $row) :?>
                                    <option value="<?= $row['NO'] ?>"><?= $row['NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <label class="col-sm-1 control-label">  مركز المسئولية   </label>
                        <div class="col-sm-5">
                            <input name="gcc_st_no" id="dp_gcc_st_no" class="form-control easyui-combotree" data-options="url:'<?= base_url('settings/gcc_structure/public_get_structure_json')?>',method:'get',animate:true,lines:true,required:true"/>
                        </div>

                    </div>
                    <div class="clearfix"></div>
                    <hr/>


                    <div class="form-group">
                        <label class="col-sm-1 control-label">  التدرج الوظيفي   </label>
                        <div class="col-sm-2">
                            <select name="kad_no" id="dp_kad_no" class="form-control">
                                <option></option>
                                <?php foreach($Kaders as $row) :?>
                                    <option value="<?= $row['NO'] ?>"><?= $row['NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <label class="col-sm-1 control-label">   الدرجة  </label>
                        <div class="col-sm-2">
                            <select name="degree" id="dp_degree" class="form-control">
                                <option></option>
                                <?php foreach($Grads as $row) :?>
                                    <option value="<?= $row['NO'] ?>"><?= $row['NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <label class="col-sm-1 control-label">  العلاوات الفعلية   </label>
                        <div class="col-sm-2">
                            <input type="text" name="periodical_allownces" id="txt_periodical_allownces" class="form-control"/>

                        </div>

                        <label class="col-sm-1 control-label">  العلاوة الدورية   </label>
                        <div class="col-sm-2">
                            <input type="text" name="hire_years" id="txt_hire_years" class="form-control"/>

                        </div>
                    </div>


                    <div class="form-group">

                        <label class="col-sm-1 control-label">العلاوة الاشرافية</label>
                        <div class="col-sm-2">
                            <input type="text" name="premium_supervisory" id="txt_premium_supervisory" class="form-control"/>

                        </div>

                        <label class="col-sm-1 control-label"> المسمي المهني </label>
                        <div class="col-sm-2">

                            <select name="w_no" id="dp_w_no" class="form-control">
                                <option></option>
                                <?php foreach($W_NO_DATA as $row) :?>
                                    <option data-val="<?= $row['ALLOWNCE'] ?>" value="<?= $row['W_NO'] ?>">(<?= $row['ALLOWNCE'] ?>%) <?= $row['W_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>


                        <label class="col-sm-1 control-label">   علاوة تخصص   </label>
                        <div class="col-sm-2">
                            <input type="text" name="job_allownce_pct" id="txt_job_allownce_pct" class="form-control"/>

                        </div>

                        <label class="col-sm-1 control-label">  تكملة 1   </label>
                        <div class="col-sm-2">
                            <input type="text" name="job_allownce_pct_extra" id="txt_job_allownce_pct_extra" class="form-control"/>

                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-1 control-label">   تكملة 2 (8%)   </label>
                        <div class="col-sm-2">
                            <input type="text" name="comoany_alternative" id="txt_comoany_alternative" class="form-control"/>

                        </div>

                        <label class="col-sm-1 control-label"> المؤهل العلمي </label>
                        <div class="col-sm-2">
                            <select name="q_no" id="dp_q_no" class="form-control">
                                <option></option>
                                <?php foreach($Q_NO_DATA as $row) :?>
                                    <option value="<?= $row['Q_NO'] ?>"><?= $row['Q_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <label class="col-sm-1 control-label"> الفئة  </label>
                        <div class="col-sm-2">

                            <select name="sp_no" id="dp_sp_no" class="form-control">
                                <option></option>
                                <?php foreach($S_P_NO as $row) :?>
                                    <option value="<?= $row['NO'] ?>"><?= $row['NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <label class="col-sm-1 control-label">   المسمي الوظيفي   </label>
                        <div class="col-sm-2">
                            <select name="w_no_admin" id="dp_w_no_admin" class="form-control">
                                <option></option>
                                <?php foreach($W_NO_ADMIN_DATA as $row) :?>
                                    <option value="<?= $row['NO'] ?>"><?= $row['NAME_J'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                    <button type="reset"  onclick="javascript:clearForm($(this).closest('form'))"  class="btn btn-default"> تفريغ الحقول</button>
                    <button type="button" onclick="javascript:employee_get(0,true);" class="btn btn-success"> إستعلام</button>
                    |

                    <button type="button" onclick="javascript:employee_get(emp_page+1,false);" class="btn btn-default"> التالي</button>
                    <button type="button" onclick="javascript:employee_get(emp_page-1,false);" class="btn btn-default"> السابق</button>

                </div>
            </form>

        </div>

    </div>

</div>


<?php


$scripts = <<<SCRIPT

<script>

    var emp_no;
    var emp_name;
    var emp_branch;
    var emp_gcc_st_no;
    var emp_page =0;
    var page_count= 1;
    $(function(){

        $('input[type="text"],body').bind('keydown', 'f3', function() {

            employee_get(0,true);
            return false;
        });

        $('input[type="text"],body').bind('keydown', 'f2', function() {
            clearForm($('#employee_from'));
            emp_no = emp_name=emp_branch=emp_gcc_st_no ='';
            emp_page =0; page_count= 1;
               $('#txt_no').prop('readonly',false);
            return false;
        });


        $('input[type="text"],body').bind('keydown', 'down', function() {
            employee_get(emp_page+1,false);
            return false;
        });


        $('input[type="text"],body').bind('keydown', 'up', function() {
            employee_get(emp_page-1,false);
            return false;
        });

       /* $('input[data-type="date"]').datetimepicker({
					pickTime: false

		});*/

    });

    function employee_create(){


        clearForm($('#employee_from'));

        $('#txt_employee_id').prop('readonly',false);

        $('#employee_from').attr('action','{$create_url}');


    }

    function employee_delete(){

        if(confirm('هل تريد حذف الحساب ؟!!!')){


            var url = '{$delete_url}';

            var tbl = '#employeeTbl';

            var container = $('#' + $(tbl).attr('data-container'));

            var val = [];
            /*
             ajax_delete(url, val ,function(data){

             success_msg('رسالة','تم حذف السجلات بنجاح ..');
             container.html(data);

             });*/
        }

    }

    function employee_get(page,search){



        if(page >= 0 && page < page_count){
            emp_page = page;
            if(search){
                emp_no= $('#txt_no').val();
                emp_name = $('#txt_name').val();
                emp_branch = $('#dp_branch').val();
                emp_gcc_st_no=$('#dp_gcc_st_no').val();
            }
            get_data('{$get_url}',{no:emp_no,name:emp_name,branch:emp_branch,gcc_st_no:emp_gcc_st_no,page:emp_page},function(data){

                if(data.length == 0){
                    clearForm($('#employee_from'));
                    return;
                }

                page_count = data.count;

                $.each(data.result, function(i,item){
                    console.log('',item);
                    $('#txt_no').prop('readonly',true);
                    $('#txt_no', $('#employee_from')).val(item.NO);
                    $('#txt_name', $('#employee_from')).val(item.NAME);
                    $('#txt_birth_date', $('#employee_from')).val(item.BIRTH_DATE);
                    $('#dp_w_no', $('#employee_from')).val(item.W_NO);
                    $('#dp_q_no', $('#employee_from')).val(item.Q_NO);
                    $('#dp_sp_no', $('#employee_from')).val(item.SP_NO);
                       $('#dp_w_no_admin', $('#employee_from')).val(item.W_NO_ADMIN);
                    $('#txt_premium_supervisory', $('#employee_from')).val(item.PREMIUM_SUPERVISORY);
                    $('#dp_kad_no', $('#employee_from')).val(item.KAD_NO);
                    $('#txt_job_allownce_pct_extra', $('#employee_from')).val(item.JOB_ALLOWNCE_PCT_EXTRA);
                    $('#txt_job_allownce_pct', $('#employee_from')).val(item.JOB_ALLOWNCE_PCT);
                    $('#txt_hire_years', $('#employee_from')).val(item.HIRE_YEARS);
                    $('#dp_degree', $('#employee_from')).val(item.DEGREE);
                    $('#txt_comoany_alternative', $('#employee_from')).val(item.COMOANY_ALTERNATIVE);
                    $('#dp_branch', $('#employee_from')).val(item.BRANCH);
                    $('#dp_gcc_st_no', $('#employee_from')).val(item.GCC_ST_NO);


                    $('#txt_periodical_allownces', $('#employee_from')).val(item.PERIODICAL_ALLOWNCES);


                        $('input[data-type="date"]').data("DateTimePicker").setDate(item.BIRTH_DATE);

                       $('#dp_gcc_st_no').combotree('setValue', item.GCC_ST_NO);
                         $('#dp_gcc_st_no').combotree('setText', $('.tree-title[data-id="'+item.GCC_ST_NO+'"]').text());

                    $('#employee_from').attr('action','{$edit_url}');

                    resetValidation($('#employee_from'));
                    $('#employeesModal').modal();

                });
            });
        }
    }
    $('button[data-action="submit"]').click(function(e){

        e.preventDefault();


        var tbl = '#employeeTbl';

        var container = $('#' + $(tbl).attr('data-container'));
        var form = $(this).closest('form');

        var isCreate = form.attr('action').indexOf('create') >= 0;

        ajax_insert_update(form,function(data){

            success_msg('رسالة','تم حفظ البيانات بنجاح ..');

            container.html(data);

            $('#employeesModal').modal('hide');


        },"html");

    });



</script>

SCRIPT;

sec_scripts($scripts);



?>
