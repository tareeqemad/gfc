<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 19/10/14
 * Time: 11:03 ص
 */

$MODULE_NAME= 'settings';
$TB_NAME= 'service_feez_account';

$get_url =base_url("$MODULE_NAME/$TB_NAME/get_id");
$create_url =base_url("$MODULE_NAME/$TB_NAME/create");
$edit_url =base_url("$MODULE_NAME/$TB_NAME/edit");
$delete_url =base_url("$MODULE_NAME/$TB_NAME/delete");

echo AntiForgeryToken();
?>

<div class="row">
    <div class="toolbar">

        <div class="caption">ارقام حسابات خدمات <?=$branch_data[0]['NAME']?></div>

        <ul><?php
            if(HaveAccess($create_url)) echo "<li><a onclick='javascript:{$TB_NAME}_create();' href='javascript:;'><i class='glyphicon glyphicon-plus'></i>جديد </a> </li>";
            if(HaveAccess($get_url) and HaveAccess($edit_url)) echo "<li><a onclick='javascript:{$TB_NAME}_get(get_id());' href='javascript:;'><i class='glyphicon glyphicon-edit'></i>تحرير</a> </li>";
            if(HaveAccess($delete_url)) echo "<li><a onclick='javascript:{$TB_NAME}_delete();' href='javascript:;'><i class='glyphicon glyphicon-remove'></i>حذف</a> </li>";
            ?></ul>

    </div>

    <div class="form-body">
        <div id="msg_container"></div>

        <div id="container">
            <?=modules::run("$MODULE_NAME/$TB_NAME/get_page"); ?>
        </div>
    </div>

</div>


<div class="modal fade" id="<?=$TB_NAME?>Modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">بيانات الخدمة</h4>
            </div>
            <form class="form-horizontal" id="<?=$TB_NAME?>_from" method="post" action="<?=$create_url?>" role="form" novalidate="novalidate">
                <div class="modal-body">

                    <div class="form-group">
                        <label class="col-sm-3 control-label">رقم الخدمة</label>
                        <div class="col-sm-2">
                            <input type="text" data-val="true"  data-val-required="حقل مطلوب" name="service_no" id="txt_service_no" class="form-control" maxlength="50">
                            <span class="field-validation-valid" data-valmsg-for="service_no" data-valmsg-replace="true"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">اسم الخدمة</label>
                        <div class="col-sm-7">
                            <input type="text" data-val="true"  data-val-required="حقل مطلوب" name="service_name" id="txt_service_name" class="form-control" maxlength="50">
                            <span class="field-validation-valid" data-valmsg-for="service_name" data-valmsg-replace="true"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">الحساب</label>
                        <div class="col-sm-7">
                            <input type="text" data-val="true"  data-val-required="حقل مطلوب" name="account_id" id="txt_account_id" class="form-control easyui-combotree" data-options="url:'<?= base_url('financial/accounts/public_get_accounts_json/1/40102')?>',method:'get',animate:true,lines:true">
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
    var max_service_no= {$max_service_no}+1;
    $(document).ready(function() {

        $('#{$TB_NAME}_tb').dataTable({
            "lengthMenu": [ [-1,10,20,30,40,50,100], ["الكل",10,20,30,40,50,100] ],
            "sPaginationType": "full_numbers"
        });
    });

    $(function () {
        $('#{$TB_NAME}Modal').on('shown.bs.modal', function () {
            $('#txt_service_name').focus();

        });
    });

    function get_id(){
        return ( $("tbody>[class~='selected']").attr('data-id') );
    }

    function {$TB_NAME}_create(){
        clearForm($('#{$TB_NAME}_from'));
        $('#txt_service_no').val(max_service_no);
        $('#txt_service_no').prop('readonly',false);
        $('#{$TB_NAME}_from').attr('action','{$create_url}');
        $('#{$TB_NAME}Modal').modal();
    }

    function {$TB_NAME}_get(id){
        get_data('{$get_url}',{service_no:id},function(data){
            $.each(data, function(i,item){
                $('#txt_service_no').val(item.SERVICE_NO);
                $('#txt_service_name').val( item.SERVICE_NAME);
                $('#txt_account_id').combotree('setValue', item.ACCOUNT_ID);
                $('#txt_service_no').prop('readonly',true);
                $('#{$TB_NAME}_from').attr('action','{$edit_url}');
                resetValidation($('#{$TB_NAME}_from'));
                $('#{$TB_NAME}Modal').modal();
            });
        });
    }

    function {$TB_NAME}_delete(){
        var url = '{$delete_url}';
        var tbl = '#{$TB_NAME}_tb';
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
        var tbl = '#{$TB_NAME}_tb';
        var container = $('#' + $(tbl).attr('data-container'));
        var form = $(this).closest('form');
        ajax_insert_update(form,function(data){
            success_msg('رسالة','تم حفظ البيانات بنجاح ..');
            container.html(data);
            if(form.attr("action")=='{$create_url}')
                max_service_no++;
            $('#{$TB_NAME}Modal').modal('hide');
        },"html");
    });

</script>

SCRIPT;

sec_scripts($scripts);

?>
