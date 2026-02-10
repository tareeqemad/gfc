<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani + mtaqia
 * Date: 12/06/16
 * Time: 12:43 م
 */

$MODULE_NAME= 'hr';
$TB_NAME= 'evaluation_order';

$get_url =base_url("$MODULE_NAME/$TB_NAME/get");
$create_url =base_url("$MODULE_NAME/$TB_NAME/create");
$edit_url =base_url("$MODULE_NAME/$TB_NAME/edit");
$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page");

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

                <!-- mtaqia -->

                <div class="form-group col-sm-1">
                    <label class="control-label">رقم أمر التقييم</label>
                    <div>
                        <input type="text" name="evaluation_order_id" id="txt_evaluation_order_id" class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">نوع أمر التقييم</label>
                    <div>
                        <select name="order_type" id="dp_order_type" class="form-control" >
                        <option value=""></option>
                        <?php foreach($order_type_all as $row) :?>
                            <option value="<?=$row['CON_NO']?>"><?=$row['CON_NAME']?></option>
                        <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">بداية التقييم </label>
                    <div>
                        <input type="text" name="order_start" id="txt_order_start" data-type="date" data-date-format="DD/MM/YYYY" data-val="true" class="form-control" data-val-regex="التاريخ غير صحيح!" data-val-regex-pattern="<?=date_format_exp()?>"  />
                        <span class="field-validation-valid" data-valmsg-for="from_date" data-valmsg-replace="true"></span>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">نهاية التقييم</label>
                    <div>
                        <input type="text" name="order_end" id="txt_order_end" data-type="date" data-date-format="DD/MM/YYYY" data-val="true" class="form-control" data-val-regex="التاريخ غير صحيح!" data-val-regex-pattern="<?=date_format_exp()?>"  />
                        <span class="field-validation-valid" data-valmsg-for="from_date" data-valmsg-replace="true"></span>
                    </div>
                </div>

                <div class="form-group col-sm-3">
                    <label class="control-label"> البيان </label>
                    <div>
                        <input type="text" name="note" id="txt_note" class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">حالة أمر التقييم</label>
                    <div>
                        <select name="status" id="dp_status" class="form-control" >
                        <option value=""></option>
                        <?php foreach($status_all as $row) :?>
                            <option value="<?=$row['CON_NO']?>"><?=$row['CON_NAME']?></option>
                        <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label"> تفعيل مرحلة التقييم </label>
                    <div>
                        <select name="level_active" id="dp_level_active" class="form-control" >
                            <option value=""></option>
                            <?php foreach($level_active_all as $row) :?>
                                <option value="<?=$row['CON_NO']?>"><?=$row['CON_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">مدخل أمر التقييم</label>
                    <div>
                        <select name="entry_user" id="dp_entry_user" class="form-control" >
                        <option></option>
                        <?php foreach($entry_user_all as $row) :?>
                            <option value="<?=$row['ID']?>"><?=$row['USER_NAME']?></option>
                        <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <!-- mtaqia -->

            </div> <!-- /.modal-body inline_form -->
        </form>

        <div class="modal-footer">
            <button type="button" onclick="javascript:search();" class="btn btn-success"> إستعلام</button>
            <button type="button" onclick="javascript:clear_form();"  class="btn btn-default"> تفريغ الحقول</button>
        </div>

        <div id="msg_container"></div>

        <div id="container">
            <?=modules::run($get_page_url, $page, $evaluation_order_id, $order_type, $note, $entry_user, $status, $level_active);?>
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
        var values= {page:1, evaluation_order_id:$('#txt_evaluation_order_id').val(), order_type:$('#dp_order_type').val(), note:$('#txt_note').val(), entry_user:$('#dp_entry_user').val(), status:$('#dp_status').val(), level_active:$('#dp_level_active').val() };
        get_data('{$get_page_url}',values ,function(data){
            $('#container').html(data);
        },'html');
    }

    function LoadingData(){
        var values= {evaluation_order_id:$('#txt_evaluation_order_id').val(), order_type:$('#dp_order_type').val(), note:$('#txt_note').val(), entry_user:$('#dp_entry_user').val(), status:$('#dp_status').val(), level_active:$('#dp_level_active').val() };
        ajax_pager_data('#page_tb > tbody',values);
    }

</script>
SCRIPT;
sec_scripts($scripts);
?>
