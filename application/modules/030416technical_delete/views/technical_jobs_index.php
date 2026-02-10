<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 06/07/15
 * Time: 10:31 ص
 */

$MODULE_NAME= 'technical';
$TB_NAME= 'technical_jobs';

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

                <div class="form-group col-sm-1">
                    <label class="control-label">رقم المهمة</label>
                    <div>
                        <input type="text" name="job_id" id="txt_job_id" class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label"> اسم المهمة </label>
                    <div>
                        <input type="text" name="job_name" id="txt_job_name" class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label"> توصيف المهمة </label>
                    <div>
                        <input type="text" name="job_description" id="txt_job_description" class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-4">
                    <label class="control-label"> ملاحظات </label>
                    <div>
                        <input type="text" name="notes" id="txt_notes" class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">مدخل الطلب</label>
                    <div>
                        <select name="entry_user" id="dp_entry_user" class="form-control" />
                        <option></option>
                        <?php foreach($entry_user_all as $row) :?>
                            <option value="<?=$row['ID']?>"><?=$row['USER_NAME']?></option>
                        <?php endforeach; ?>
                        </select>
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
            <?=modules::run($get_page_url, $page, $job_id, $job_name, $job_description, $notes, $entry_user);?>
        </div>

    </div>

</div>

<?php
$scripts = <<<SCRIPT
<script>

    $('.pagination li').click(function(e){
        e.preventDefault();
    });

    function show_row_details(id){
        get_to_link('{$get_url}/'+id+'/{$edit}');
    }

    function search(){
        var values= {page:1, job_id:$('#txt_job_id').val(), job_name:$('#txt_job_name').val(), job_description:$('#txt_job_description').val(), notes:$('#txt_notes').val(), entry_user:$('#dp_entry_user').val() };
        get_data('{$get_page_url}',values ,function(data){
            $('#container').html(data);
        },'html');
    }

    function LoadingData(){
        var values= {job_id:$('#txt_job_id').val(), job_name:$('#txt_job_name').val(), job_description:$('#txt_job_description').val(), notes:$('#txt_notes').val(), entry_user:$('#dp_entry_user').val() };
        ajax_pager_data('#page_tb > tbody',values);
    }

    function clear_form(){
        clearForm($('#{$TB_NAME}_form'));
    }

</script>
SCRIPT;
sec_scripts($scripts);
?>
