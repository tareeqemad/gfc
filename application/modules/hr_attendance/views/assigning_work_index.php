<?php
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 07/06/18
 * Time: 12:00 م
 */

$MODULE_NAME= 'hr_attendance';
$TB_NAME= 'assigning_work';

$get_url =base_url("$MODULE_NAME/$TB_NAME/get");
$create_url =base_url("$MODULE_NAME/$TB_NAME/create");
$edit_url =base_url("$MODULE_NAME/$TB_NAME/edit");
$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page");
$adopt_all_url= base_url("$MODULE_NAME/$TB_NAME/adopt_");

if(HaveAccess($edit_url))
    $edit= 'edit';
else
    $edit= '';

$date_attr= " data-type='date' data-date-format='DD/MM/YYYY' data-val='true' data-val-regex-pattern='".date_format_exp()."' data-val-regex='Error' ";

echo AntiForgeryToken();
?>

<script> var show_page=true; </script>

<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>

        <ul>
            <?php if( HaveAccess($create_url)):  ?><li><a  href="<?=$create_url.'/'.$page_act?>"><i class="glyphicon glyphicon-plus"></i>جديد </a> </li><?php endif; ?>
            <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>
        </ul>

    </div>

    <div class="form-body">

        <form class="form-vertical" id="<?=$TB_NAME?>_form" >
            <div class="modal-body inline_form">

                <div class="form-group col-sm-1">
                    <label class="control-label">رقم السند</label>
                    <div>
                        <input type="text" name="ser" id="txt_ser" class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">الموظف</label>
                    <div>
                        <select name="emp_no" id="dp_emp_no" class="form-control sel2" >
                            <option value="">_________</option>
                            <?php foreach($emp_no_cons as $row) :?>
                                <option value="<?=$row['EMP_NO']?>"><?=$row['EMP_NO'].': '.$row['EMP_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">تاريخ التكليف من</label>
                    <div>
                        <input type="text" <?=$date_attr?> name="ass_start_date" id="txt_ass_start_date" class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">الى</label>
                    <div>
                        <input type="text" <?=$date_attr?> name="ass_end_date" id="txt_ass_end_date" class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">الحالة</label>
                    <div>
                        <select name="adopt" id="dp_adopt" class="form-control sel2" >
                            <option value="">_________</option>
                            <?php foreach($adopt_cons as $row) :?>
                                <option value="<?=$row['CON_NO']?>"><?=$row['CON_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">عرض السجلات المعلقة فقط</label>
                    <div>
                        <select name="not_closed" id="dp_not_closed" class="form-control sel2" >
                            <option value="1">نعم - المعلقة فقط</option>
                            <option value="">لا - اعرض الكل</option>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">نوع الموظفين </label>
                    <div>
                        <select name="emp_type" id="dp_emp_type" class="form-control sel2" >
                            <option selected value="1" >موظفي الشركة</option>
                            <option value="2" >فنيي مشروع الفاقد</option>
                        </select>
                    </div>
                </div>

                <div style="clear: both"></div>

                <div class="form-group col-sm-1">
                    <label class="control-label">المقر </label>
                    <div>
                        <select name="branch_id" id="dp_branch_id" class="form-control sel2" >
                            <option value="">_________</option>
                            <?php foreach($branches as $row) :?>
                                <option value="<?=$row['NO']?>" ><?=$row['NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">مدخل الطلب</label>
                    <div>
                        <select name="entry_user" id="dp_entry_user" class="form-control sel2" >
                            <option value="">_________</option>
                            <?php foreach($entry_user_all as $row) :?>
                                <option value="<?=$row['ID']?>"><?=$row['USER_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">الوجبات</label>
                    <div>
                        <select name="food_no" id="dp_food_no" class="form-control sel2" >
                            <option value="">_________</option>
                            <?php foreach($food_no_cons as $row) :?>
                                <option value="<?=$row['CON_NO']?>"><?=$row['CON_NAME']?></option>
                            <?php endforeach; ?>
                            <option value="10">وجبة او اكثر</option>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-3">
                    <label class="control-label">الأعمال المطلوب انجازها</label>
                    <div>
                        <input type="text" name="work_required" id="txt_work_required" class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-4">
                    <label class="control-label">ملاحظات</label>
                    <div>
                        <input type="text" name="notes" id="txt_notes" class="form-control">
                    </div>
                </div>

            </div>
        </form>

        <div class="modal-footer">
            <button type="button" onclick="javascript:search();" class="btn btn-success"> إستعلام</button>
            <button type="button" onclick="$('#page_tb').tableExport({type:'excel',escape:'false'});" class="btn btn-success">  اكسل </button>
            <?php if( $page_act=='manager' or $page_act=='move_admin' ){ ?>
                <button type="button" onclick="javascript:adopt_all();" class="btn btn-warning"> اعتماد المحدد</button>
            <?php } ?>
            <button type="button" onclick="javascript:clear_form();"  class="btn btn-default"> تفريغ الحقول</button>
        </div>

        <div id="msg_container"></div>

        <div id="container">
            <?=modules::run($get_page_url, $page, $page_act, $ser, $emp_no, $work_required, $notes, $food_no, $ass_start_date, $ass_end_date, $adopt, $entry_user, $branch_id, $not_closed, $emp_type );?>
        </div>

    </div>

</div>


<?php
$scripts = <<<SCRIPT
<script>

    $('.sel2').select2();

    if('{$page_act}'=='my'){
        $('#dp_emp_no, #dp_branch_id, #dp_entry_user, #dp_emp_type').select2('readonly',1);
    }

    if('{$page_act}'!='move_admin'){
        $('#dp_not_closed').select2('readonly',1);
    }

    $('.pagination li').click(function(e){
        e.preventDefault();
    });

    function show_row_details(id){
        get_to_link('{$get_url}/'+id+'/{$page_act}');
    }

    function clear_form(){
        clearForm($('#{$TB_NAME}_form'));
        $('.sel2').select2('val','');
    }

    function values_search(add_page){
        var values= {page:1, page_act: '{$page_act}', ser:$('#txt_ser').val(), emp_no:$('#dp_emp_no').val(), work_required:$('#txt_work_required').val(), notes:$('#txt_notes').val(), food_no:$('#dp_food_no').val(), ass_start_date:$('#txt_ass_start_date').val(), ass_end_date:$('#txt_ass_end_date').val(), adopt:$('#dp_adopt').val(), entry_user:$('#dp_entry_user').val(), branch_id:$('#dp_branch_id').val(), not_closed:$('#dp_not_closed').val(), emp_type:$('#dp_emp_type').val() };
        if(add_page==0)
            delete values.page;
        return values;
    }

    function search(){
        var values= values_search(1);
        get_data('{$get_page_url}',values ,function(data){
            $('#container').html(data);
        },'html');
    }

    function LoadingData(){
        var values= values_search(0);
        ajax_pager_data('#page_tb > tbody',values);
    }

    function adopt_all(){
        var no= -99;
        var ser= 0;
        var cnt= 0;
        cnt= $('#page_tb .checkboxes:checked').length;
        var msg= 'هل تريد اعتماد جميع السجلات المحددة؟؟ #'+cnt;

        if(cnt==0){
            alert('يجب تحديد السجلات المراد اعتمادها اولا..');
            return 0;
        }

        if(confirm(msg)){
            if('{$page_act}'=='manager'){
                no=20;
            }else if('{$page_act}'=='move_admin'){
                no=40;
            }

            info_msg('تنويه..','سيتم تحديث الصفحة تلقائيا عند انتهاء العملية');
            $('button').attr('disabled','disabled');

            $('#page_tb .checkboxes:checked').each(function(i){

                ser= $(this).val();
                get_data('{$adopt_all_url}'+no, {ser:ser} , function(ret){
                    if(ret==1){
                        success_msg('رسالة','تمت العملية بنجاح ');
                    }else{
                        danger_msg('تحذير..',ret);
                    }
                }, 'html');

            }); // each

            setTimeout(function(){
                info_msg('تنويه..','جار تحديث الصفحة..');
                get_to_link(window.location.href);
            },4000);

        } // confirm msg
    }

    </script>
SCRIPT;
sec_scripts($scripts);
?>
