<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 25/04/15
 * Time: 12:55 م
 */

$MODULE_NAME= 'stores';
$TB_NAME= 'class_amount';
$get_page_url =base_url("$MODULE_NAME/$TB_NAME/stores_val_page");

echo AntiForgeryToken();
?>

<div class="row">
    <div class="toolbar">
        <div class="caption"> تقدير المخزون</div>
    </div>

    <div class="form-body">
        <form class="form-vertical" id="<?=$TB_NAME?>_form" >
            <div class="modal-body inline_form">

                <div class="form-group col-sm-2">
                    <label class="control-label">المخزن</label>
                    <div>
                        <select name="store_id" id="dp_store_id" class="form-control"  >
                            <option value="0">جميع المخازن</option>
                            <?php foreach($stores as $row) :?>
                                <option value="<?=$row['STORE_ID']?>"><?=$row['STORE_NO'].': '.$row['STORE_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label"> حالة الاصناف </label>
                    <div>
                        <select name="class_type" id="dp_class_type" class="form-control" />
                        <option value="0">الكل</option>
                        <?php foreach($class_type_all as $row) :?>
                            <option value="<?=$row['CON_NO']?>"><?=$row['CON_NAME']?></option>
                        <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">نوع الاصناف</label>
                    <div>
                        <select name="class_acount_type" id="dp_class_acount_type" class="form-control" >
                            <option value="0">الكل</option>
                            <?php foreach($class_acount_type_all as $row) :?>
                                <option value="<?=$row['CON_NO']?>"><?=$row['CON_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">خصم الحجوزات</label>
                    <div>
                        <select name="reserve" id="dp_reserve" class="form-control"  >
                            <option value="" selected >نعم</option>
                            <option value="2">نعم عدا الطلبات</option>
                            <option value="1">لا</option>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">حتى</label>
                    <div>
                        <input type="text" name="to_date" data-type="date" data-date-format="DD/MM/YYYY" data-val="true" id="txt_to_date" class="form-control" data-val-regex="التاريخ غير صحيح!" data-val-regex-pattern="<?=date_format_exp()?>" />
                        <span class="field-validation-valid" data-valmsg-for="to_date" data-valmsg-replace="true"></span>
                    </div>
                </div>

            </div>
        </form>

        <div class="modal-footer">
            <button type="button" onclick="javascript:search();" class="btn btn-success"> إستعلام</button>
            <div class="btn-group">
                <button class="btn btn-warning dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bars"></i> تصدير</button>
                <ul class="dropdown-menu " role="menu">
                    <li><a href="#" onclick="$('#page_tb').tableExport({type:'excel',escape:'false'});">  XLS</a></li>
                    <li><a href="#" onclick="$('#page_tb').tableExport({type:'doc',escape:'false'});">  Word</a></li>
                </ul>
            </div>
            <button type="button" onclick="javascript:clear_form();"  class="btn btn-default"> تفريغ الحقول</button>
        </div>

        <div id="msg_container"></div>



        <div id="container">

        </div>

    </div>

</div>

<?php

$scripts = <<<SCRIPT
<script type="text/javascript">

    $('#dp_store_id, #dp_class_acount_type, #dp_reserve, #dp_class_type').select2();

    function search(){
        $('#container').text('');
        var values= {store_id: $('#dp_store_id').val(), to_date: $('#txt_to_date').val(), class_type: $('#dp_class_type').val(), class_acount_type: $('#dp_class_acount_type').val(), reserve: $('#dp_reserve').val() };
        get_data('{$get_page_url}',values ,function(data){
            $('#container').html(data);
        },'html');
    }

    function clear_form(){
        clearForm($('#{$TB_NAME}_form'));
        $('#dp_store_id').select2('val',0);
        $('#dp_class_acount_type').select2('val',0);
        $('#dp_class_type').select2('val',0);
        $('#dp_reserve').select2('val','');
    }

</script>

SCRIPT;

sec_scripts($scripts);

?>
