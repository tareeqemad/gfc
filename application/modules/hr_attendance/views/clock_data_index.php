<?php
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 07/08/18
 * Time: 10:01 ص
 */

$MODULE_NAME= 'hr_attendance';
$TB_NAME= 'clock_data';

//$get_url =base_url("$MODULE_NAME/$TB_NAME/get");
//$create_url =base_url("$MODULE_NAME/$TB_NAME/create");
$edit_url =base_url("$MODULE_NAME/$TB_NAME/edit");
$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page");
$trans_no_entry_url = base_url("$MODULE_NAME/$TB_NAME/trans_no_entry");

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

                <input type="hidden" name="delay" value="<?=$delay?>" id="h_delay" class="form-control">
                <input type="hidden" name="no_leave" value="<?=$no_leave?>" id="h_no_leave" class="form-control">
                <input type="hidden" name="no_entry" value="<?=$no_entry?>" id="h_no_entry" class="form-control">

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
                    <label class="control-label">التاريخ من </label>
                    <div>
                        <input type="text" <?=$date_attr?> value="<?=($entry_date!=-1)?$entry_date:date('d/m/Y')?>" name="entry_date" id="txt_entry_date" class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">الى</label>
                    <div>
                        <input type="text" <?=$date_attr?> value="<?=($entry_date_2!=-1)?$entry_date_2:''?>" name="entry_date_2" id="txt_entry_date_2" class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">الحركة</label>
                    <div>
                        <select name="function_key" id="dp_function_key" class="form-control sel2" >
                            <option value="">_________</option>
                            <?php foreach($function_key_cons as $row) :?>
                                <option value="<?=$row['CON_NO']?>"><?=$row['CON_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

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

                <div class="form-group col-sm-1">
                    <label class="control-label">نوع الموظفين </label>
                    <div>
                        <select name="emp_type" id="dp_emp_type" class="form-control sel2" >
                            <option selected value="1" >موظفي الشركة</option>
                            <option value="2" >فنيي مشروع الفاقد</option>
                        </select>
                    </div>
                </div>

            </div>
        </form>

        <div class="modal-footer">
            <button type="button" onclick="javascript:search();" class="btn btn-success"> إستعلام</button>
            <button type="button" onclick="$('#page_tb').tableExport({type:'excel',escape:'false'});" class="btn btn-success">  اكسل </button>
            <button type="button" onclick="javascript:clear_form();"  class="btn btn-default"> تفريغ الحقول</button>
        </div>

        <div id="msg_container"></div>

        <div id="container">
            <?=modules::run($get_page_url, $page, $page_act, $delay, $no_leave, $no_entry, $ser, $emp_no, $year, $entry_date, $entry_date_2, $branch_id, $function_key, $emp_type );?>
        </div>

    </div>

</div>


<?php
$scripts = <<<SCRIPT
<script>

    $('.sel2').select2();

    if('{$page_act}'=='my'){
        $('#dp_emp_no, #dp_branch_id, #dp_emp_type').select2('readonly',1); // + dp_entry_user
    }

    $('.pagination li').click(function(e){
        e.preventDefault();
    });

    function clear_form(){
        clearForm($('#{$TB_NAME}_form'));
        $('.sel2').select2('val','');
    }

    function values_search(add_page){
        var values= {page:1, page_act: '{$page_act}', ser:$('#txt_ser').val(), emp_no:$('#dp_emp_no').val(), entry_date:$('#txt_entry_date').val(), entry_date_2:$('#txt_entry_date_2').val(), branch_id:$('#dp_branch_id').val(), function_key:$('#dp_function_key').val(), emp_type:$('#dp_emp_type').val(), delay:$('#h_delay').val(), no_leave:$('#h_no_leave').val(), no_entry:$('#h_no_entry').val() };
        if(add_page==0)
            delete values.page;
        return values;
    }

    function search(){
        var values= values_search(1);
        var f_dt = date_yyyymmdd( $('#txt_entry_date').val() );
        var t_dt = date_yyyymmdd( $('#txt_entry_date_2').val() );
        get_data('{$get_page_url}/1/{$page_act}/'+$('#dp_emp_no').val()+'/'+f_dt+'/'+t_dt, values ,function(data){
            $('#container').html(data);
        },'html');
    }

    function LoadingData(){
        var values= values_search(0);
        ajax_pager_data('#page_tb > tbody',values);
    }
    
    function trans_no_entry(ser, emp_no, entry_date){
        get_data('{$trans_no_entry_url}', {ser:ser, emp_no:emp_no, entry_date:entry_date} ,function(data){
            if(data==1){
                success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                $('#td_ser_'+ser).html("<i class='glyphicon glyphicon glyphicon-ok' title='مرحل' style='color: #0a8800;font-size: large'></i>");
            }else{
                danger_msg('تحذير..',data);
            }
        },'html');
    }

</script>
SCRIPT;
sec_scripts($scripts);
?>
