<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 10/01/15
 * Time: 11:19 ص
 */

$MODULE_NAME= 'stores';
$TB_NAME= 'class_amount';

$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_stores_actions_page");
$select_items_url=base_url("$MODULE_NAME/classes/public_index");

echo AntiForgeryToken();
?>

<script> var show_page=true; </script>

<div class="row">
    <div class="toolbar">
        <div class="caption"><?= $title ?></div>
    </div>

    <div class="form-body">
        <form class="form-vertical" id="<?=$TB_NAME?>_form" >
            <div class="modal-body inline_form">

                <div class="form-group col-sm-2">
                    <label class="control-label">المخزن</label>
                    <div>
                        <select name="store_id" id="dp_store_id" class="form-control"  >
                            <option value="0">جميع المخازن</option>
                            <?php foreach($stores_all as $row) :?>
                                <option value="<?=$row['STORE_ID']?>"><?=$row['STORE_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">الحركة</label>
                    <div>
                        <select name="action" id="dp_action" class="form-control"  >
                            <option value="0">جميع الحركات</option>
                            <option value="1">وارد</option>
                            <option value="2">صادر</option>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">المصدر</label>
                    <div>
                        <select name="source" id="dp_source" class="form-control" />
                        <option></option>
                        <?php foreach($source_all as $row) :?>
                            <option value="<?=$row['CON_NO']?>"><?=$row['CON_NAME']?></option>
                        <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">رقم المصدر</label>
                    <div>
                        <input type="text"  name="pk" id="txt_pk" class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">الحركة من</label>
                    <div>
                        <input type="text" name="from_date" data-type="date" data-date-format="DD/MM/YYYY" data-val="true" id="txt_from_date" class="form-control" data-val-regex="التاريخ غير صحيح!" data-val-regex-pattern="<?=date_format_exp()?>"  />
                        <span class="field-validation-valid" data-valmsg-for="from_date" data-valmsg-replace="true"></span>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">الى</label>
                    <div>
                        <input type="text" name="to_date" data-type="date" data-date-format="DD/MM/YYYY" data-val="true" id="txt_to_date" class="form-control" data-val-regex="التاريخ غير صحيح!" data-val-regex-pattern="<?=date_format_exp()?>" />
                        <span class="field-validation-valid" data-valmsg-for="to_date" data-valmsg-replace="true"></span>
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
            <?=modules::run($get_page_url, $page, $store_id, $action, $source, $pk, $from_date, $to_date);?>
        </div>

    </div>

</div>

<?php
$scripts = <<<SCRIPT
<script>

    $('.pagination li').click(function(e){
        e.preventDefault();
    });

    $('#dp_store_id, #dp_action, #dp_source').select2();

    function search(){
        var values= {page:1, store_id:$('#dp_store_id').val(), action:$('#dp_action').val(), source:$('#dp_source').val(), pk:$('#txt_pk').val(), from_date:$('#txt_from_date').val(), to_date:$('#txt_to_date').val() };
        get_data('{$get_page_url}',values ,function(data){
            $('#container').html(data);
        },'html');
    }

    function LoadingData(){
        var values= {store_id:$('#dp_store_id').val(), action:$('#dp_action').val(), source:$('#dp_source').val(), pk:$('#txt_pk').val(), from_date:$('#txt_from_date').val(), to_date:$('#txt_to_date').val() };
        ajax_pager_data('#page_tb > tbody',values);
    }

    function clear_form(){
        clearForm($('#{$TB_NAME}_form'));
        $('#dp_store_id, #dp_action, #dp_source').select2('val','');
    }

</script>
SCRIPT;
sec_scripts($scripts);
?>
