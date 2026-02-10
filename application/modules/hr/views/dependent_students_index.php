<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mtaqia
 * Date: 2019-04-23
 * Time: 9:21 AM
 */

$MODULE_NAME= 'hr';
$TB_NAME= 'dependent_students';

$get_url =base_url("$MODULE_NAME/$TB_NAME/get");
$create_url =base_url("$MODULE_NAME/$TB_NAME/create");
$edit_url =base_url("$MODULE_NAME/$TB_NAME/edit");
$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page");

if(HaveAccess($edit_url))
    $edit= 'edit';
else
    $edit= '';

echo AntiForgeryToken();
?>

<script> var show_page=true; </script>

<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>

        <ul>
            <?php if( HaveAccess($create_url)):  ?><li><a  href="<?=$create_url?>"><i class="glyphicon glyphicon-plus"></i>جديد </a> </li><?php endif; ?>
            <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>
        </ul>

    </div>

    <div class="form-body">

        <form class="form-vertical" id="<?=$TB_NAME?>_form" >
            <div class="modal-body inline_form">

                <div class="form-group col-sm-3">
                    <label class="control-label">رقم السند</label>
                    <div>
                        <input type="text" name="ser_d" id="txt_ser_d" class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">مسلسل</label>
                    <div>
                        <input type="text" name="ser_m" id="txt_ser_m" class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">الرقم الوظيفي</label>
                    <div>
                        <input type="text" name="emp_no" id="txt_emp_no" class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">رقم هوية المعال</label>
                    <div>
                        <input type="text" name="idno_relative" id="txt_idno_relative" class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">المؤهل العلمي للمعال</label>
                    <div>
                        <input type="text" name="qualification_type" id="txt_qualification_type" class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">تاريخ الإعالة</label>
                    <div>
                        <input type="text" name="join_date" id="txt_join_date" data-type="date" data-date-format="DD/MM/YYYY" data-val="true" class="form-control" data-val-regex="التاريخ غير صحيح!" data-val-regex-pattern="<?=date_format_exp()?>"  />
                        <span class="field-validation-valid" data-valmsg-for="from_date" data-valmsg-replace="true"></span>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">تاريخ التخرج</label>
                    <div>
                        <input type="text" name="graduation_date" id="txt_graduation_date" data-type="date" data-date-format="DD/MM/YYYY" data-val="true" class="form-control" data-val-regex="التاريخ غير صحيح!" data-val-regex-pattern="<?=date_format_exp()?>"  />
                        <span class="field-validation-valid" data-valmsg-for="from_date" data-valmsg-replace="true"></span>
                    </div>
                </div>

            </div> <!-- /.modal-body inline_form -->
        </form>

        <div class="modal-footer">
            <button type="button" onclick="javascript:search();" class="btn btn-success"> إستعلام</button>
            <button type="button" onclick="javascript:clear_form();"  class="btn btn-default"> تفريغ الحقول</button>
        </div>

        <div id="msg_container"></div>

        <div id="container">
            <?=modules::run($get_page_url, $page, $ser_d, $ser_m, $emp_no, $idno_relative, $qualification_type, $join_date, $graduation_date);?>
        </div>

    </div>

</div>


<?php
$scripts = <<<SCRIPT
<script>

    $('.pagination li').click(function(e){
        e.preventDefault();
    });

    function clear_form(){
        clearForm($('#{$TB_NAME}_form'));
    }

    function show_row_details(id){
        get_to_link('{$get_url}/'+id+'/edit');
    }

    function search(){
        var values= {page:1, ser_d:$('#txt_ser_d').val(), ser_m:$('#txt_ser_m').val(), emp_no:$('#txt_emp_no').val(), idno_relative:$('#txt_idno_relative').val(), qualification_type:$('#txt_qualification_type').val(), join_date:$('#txt_join_date').val(), graduation_date:$('#txt_graduation_date').val() };
        get_data('{$get_page_url}',values ,function(data){
            $('#container').html(data);
        },'html');
    }

    function LoadingData(){
        var values= {ser_d:$('#txt_ser_d').val(), ser_m:$('#txt_ser_m').val(), emp_no:$('#txt_emp_no').val(), idno_relative:$('#txt_idno_relative').val(), qualification_type:$('#txt_qualification_type').val(), join_date:$('#txt_join_date').val(), graduation_date:$('#txt_graduation_date').val()};
        ajax_pager_data('#page_tb > tbody',values);
    }

</script>
SCRIPT;
sec_scripts($scripts);
?>

