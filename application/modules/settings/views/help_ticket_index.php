<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 09/09/14
 * Time: 09:37 ص
 */
echo AntiForgeryToken();
$MODULE_NAME= 'settings';
$TB_NAME= 'help_ticket';

$get_url =base_url("$MODULE_NAME/$TB_NAME/get_id");
$create_url =base_url("$MODULE_NAME/$TB_NAME/create");
$edit_url =base_url("$MODULE_NAME/$TB_NAME/edit");
$delete_url =base_url("$MODULE_NAME/$TB_NAME/delete");

?>
<div class="row">
    <div class="toolbar">

        <div class="caption">الدليل الارشادي</div>

        <ul>
            <?php if( HaveAccess($create_url)): ?>  <li><a onclick="javascript:<?=$TB_NAME?>_create();" href="javascript:;"><i class="glyphicon glyphicon-plus"></i>جديد </a> </li><?php endif;?>
            <?php if( HaveAccess($edit_url,$get_url)): ?>  <li><a onclick="javascript:<?=$TB_NAME?>_get(get_id());" href="javascript:;"><i class="glyphicon glyphicon-edit"></i>تحرير</a> </li><?php endif;?>
            <?php if( HaveAccess($delete_url)): ?>  <li><a onclick="javascript:<?=$TB_NAME?>_delete();" href="javascript:;"><i class="glyphicon glyphicon-remove"></i>حذف</a> </li><?php endif;?>
        </ul>

    </div>

    <div class="form-body">
        <div id="msg_container"></div>

        <div id="container">
            <?=modules::run("$MODULE_NAME/$TB_NAME/get_page"); ?>
        </div>
    </div>

</div>
<div class="modal fade" id="<?=$TB_NAME?>Modal">
    <div class="modal-dialog _90" >
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">الدليل الارشادي</h4>
            </div>
            <form class="form-horizontal" id="<?=$TB_NAME?>_form" method="post" action="<?=base_url("$MODULE_NAME/$TB_NAME/create")?>" role="form" novalidate="novalidate">
                <div class="modal-body">

                    <div class="form-group">
                        <label class="col-sm-3 control-label">المتسلسل</label>
                        <div class="col-sm-2">
                            <input type="text"  name="id" readonly id="txt_id" class="form-control ltr">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">العنوان</label>
                        <div class="col-sm-5">
                            <input type="text" data-val="true"  data-val-required="حقل مطلوب" name="title" id="txt_title" class="form-control" maxlength="100"  data-val-regex="!!ادخال خاطئ يتوجب ادخال نص"   dir="ltr">
                            <span class="field-validation-valid" data-valmsg-for="title" data-valmsg-replace="true"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">الرابط الارشادي</label>
                        <div class="col-sm-5">
                            <input type="text" data-val="true"  data-val-required="حقل مطلوب"   name="form_id"  id="txt_form_id" class="form-control" maxlength="100" data-val-regex="!!ادخال خاطئ يتوجب ادخال نص"   dir="ltr">
                            <span class="field-validation-valid" data-valmsg-for="form_id" data-valmsg-replace="true"></span>
                        </div>
                    </div>

                   <div class="form-group">
                        <label class="col-sm-3 control-label">النص الإدرشادي</label>
                        <div class="col-sm-9">
                            <textarea class="ckeditor" name="help_text" id="txt_help_text"></textarea>
                            <!--
                            <input type="text" data-val="true"  data-val-required="حقل مطلوب" name="help_text" id="txt_help_text" class="form-control" maxlength="100"  data-val-regex="!!ادخال خاطئ يتوجب ادخال نص"   dir="ltr">
                            -->
                            <span class="field-validation-valid" data-valmsg-for="help_text" data-valmsg-replace="true"></span>
                        </div>
                    </div>



                </div>
                <div class="modal-footer">
                    <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">إغلاق</button>

                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<?php


$scripts = <<<SCRIPT
<script type="text/javascript">

    $(function () {
        $('#{$TB_NAME}Modal').on('shown.bs.modal', function () {
            $('#txt_form_id').focus();
        });
    });

    function get_id(){

        return ( $("tbody>[class~='selected']").attr('data-id') );
    }

    function {$TB_NAME}_create(){
        clearForm($('#{$TB_NAME}_form'));
         CKEDITOR.instances.txt_help_text.setData('');
        $('#txt_id').val({$count_all}+1);
        $('#{$TB_NAME}_form').attr('action','{$create_url}');
        $('#{$TB_NAME}Modal').modal();
    }

    function {$TB_NAME}_get(id){

        get_data('{$get_url}',{id:id},function(data){
            $.each(data, function(i,item){
                $('#txt_id').val(item.ID);
                $('#txt_form_id').val(item.FORM_ID);
                $('#txt_help_text').val(item.HELP_TEXT);
                CKEDITOR.instances.txt_help_text.setData(item.HELP_TEXT);
                $('#txt_title').val(item.TITLE);
                $('#txt_id').prop('readonly',true);
                $('#{$TB_NAME}_form').attr('action','{$edit_url}');
                resetValidation($('#{$TB_NAME}_form'));
                $('#{$TB_NAME}Modal').modal();
            });
        });
    }

    function {$TB_NAME}_delete(){
        var url = '{$delete_url}';
        var tbl = '#{$TB_NAME}';

        var container = $('#' + $(tbl).attr('data-container'));
        var val = [];
        $(tbl + ' .checkboxes:checked').each(function (i) {
            val[i] = $(this).val();
        });

        if(val.length > 0){
            if(confirm('هل تريد بالتأكيد حذف '+val.length+' سجلات ؟!!')){
                ajax_delete(url, val ,function(data){
                    success_msg('رسالة','تم حذف السجلات بنجاح ..');
                    container.html(data);
                });
            }
        }else
            alert('يجب تحديد السجلات المراد حذفها');
    }

    $('button[data-action="submit"]').click(function(e){
        e.preventDefault();
        var tbl = '#{$TB_NAME}';
        var container = $('#' + $(tbl).attr('data-container'));

$('#txt_help_text').val(CKEDITOR.instances.txt_help_text.getData());
        var form = $(this).closest('form');
        ajax_insert_update(form,function(data){
            success_msg('رسالة','تم حفظ البيانات بنجاح ..');
            container.html(data);
            $('#{$TB_NAME}Modal').modal('hide');
        },"html");
    });




</script>

SCRIPT;

sec_scripts($scripts);

?>
