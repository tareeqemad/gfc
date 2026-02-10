<?php
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 24/03/19
 * Time: 11:08 ص
 */

$MODULE_NAME= 'hr_attendance';
$TB_NAME= 'assigning_work_sum';

$get_url =base_url("$MODULE_NAME/$TB_NAME/get");
$create_url =base_url("$MODULE_NAME/$TB_NAME/create");
$edit_url =base_url("$MODULE_NAME/$TB_NAME/edit");
$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page");

$date_attr= " data-type='date' data-date-format='DD/MM/YYYY' data-val='true' data-val-regex-pattern='".date_format_exp()."' data-val-regex='Error' ";

if(HaveAccess($edit_url))
    $edit= 'edit';
else
    $edit= '';

echo AntiForgeryToken();
?>

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
                    <label class="control-label">الفترة من</label>
                    <div>
                        <input <?=$date_attr?> value="<?=$date_from?>" type="text" name="date_from" id="txt_date_from" class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">الى</label>
                    <div>
                        <input <?=$date_attr?> value="<?=$date_to?>" type="text" name="date_to" id="txt_date_to" class="form-control">
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
            <?=modules::run($get_page_url, $emp_no, $branch_id, $date_from, $date_to);?>
        </div>

    </div>

</div>

<?php
$scripts = <<<SCRIPT
<script>

    $('.sel2').select2();

    function show_row_details(id){
        // get_to_link('{$get_url}/'+id+'/{$edit}');
    }

    function clear_form(){
        clearForm($('#{$TB_NAME}_form'));
        $('.sel2').select2('val','');
    }

    function search(){
        if( $('#txt_date_from').val()=='' || $('#txt_date_to').val()=='' ){
            alert('يجب تحديد الفترة');
            return 0;
        }
        var values= {emp_no:$('#dp_emp_no').val(), branch_id:$('#dp_branch_id').val(), date_from:$('#txt_date_from').val(), date_to:$('#txt_date_to').val() };
        get_data('{$get_page_url}',values ,function(data){
            $('#container').html(data);
        },'html');
    }

    </script>
SCRIPT;
sec_scripts($scripts);
?>
