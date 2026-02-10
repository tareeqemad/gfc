<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$MODULE_NAME= 'purchases';
$TB_NAME= 'activity';


$get_url =base_url("$MODULE_NAME/$TB_NAME/get_id");
$create_url =base_url("$MODULE_NAME/$TB_NAME/create");
$edit_url =base_url("$MODULE_NAME/$TB_NAME/edit");
$delete_url =base_url("$MODULE_NAME/$TB_NAME/delete");
$select_accounts_url =base_url('financial/accounts/public_select_accounts');
echo AntiForgeryToken();
?>

<div class="row">
    <div class="toolbar">

        <div class="caption"><?=$title?></div>

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
                <h4 class="modal-title">بيانات النشاط</h4>
            </div>
            <form class="form-horizontal" id="<?=$TB_NAME?>_from" method="post" action="<?=base_url("$MODULE_NAME/$TB_NAME/create")?>" role="form" novalidate="novalidate">
                <div class="modal-body">

                    <div class="form-group">
                        <label class="col-sm-3 control-label">رقم النشاط</label>
                        <div class="col-sm-2">
                            <input type="text"  name="ser" readonly id="txt_ser" class="form-control ltr">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">اسم النشاط</label>
                        <div class="col-sm-7">
                            <input type="text" data-val="true"  data-val-required="حقل مطلوب" name="activity_name" id="txt_activity_name" class="form-control" maxlength="200">
                            <span class="field-validation-valid" data-valmsg-for="activity_name" data-valmsg-replace="true"></span>
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

    $(document).ready(function() {
        $('#{$TB_NAME}_tb').dataTable({
            "lengthMenu": [ [10,20,30,40,50,100, -1], [10,20,30,40,50,100, "الكل"] ],
            "sPaginationType": "full_numbers"
        });
    });

    $(function () {
        $('#{$TB_NAME}Modal').on('shown.bs.modal', function () {
            $('#txt_tb_name').focus();
        });
    });

    function get_id(){
        return ( $("tbody>[class~='selected']").attr('data-id') );
    }

    function {$TB_NAME}_create(){
        clearForm($('#{$TB_NAME}_from'));
        $('#txt_ser').val({$count_all}+1);
        $('#{$TB_NAME}_from').attr('action','{$create_url}');
        $('#{$TB_NAME}Modal').modal();
    }

    function {$TB_NAME}_get(id){
     get_data('{$get_url}',{id:id},function(data){
            $.each(data, function(i,item){
                $('#txt_ser').val(item.SER);
                $('#txt_activity_name').val( item.ACTIVITY_NAME);
                $('#txt_ser').prop('readonly',true);
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
            if(confirm('هل تريد بالتأكيد حذف '+val.length+' سجلات وحذف تفاصيلها ؟!!')){
                ajax_delete(url, val ,function(data){
                    success_msg('رسالة','تم حذف السجلات بنجاح ..');
                    container.html(data);
                      window.location.reload();
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
            $('#{$TB_NAME}Modal').modal('hide');
            window.location.reload();
        },"html");
    });

   


</script>

SCRIPT;

sec_scripts($scripts);

?>
