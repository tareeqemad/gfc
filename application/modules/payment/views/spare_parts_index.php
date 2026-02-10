<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 17/03/22
 * Time: 09:10 ص
 */
$MODULE_NAME= 'payment';
$TB_NAME= 'Spare_parts';
$get_url =base_url("$MODULE_NAME/$TB_NAME/get_id");
$create_url =base_url("$MODULE_NAME/$TB_NAME/create");
$edit_url =base_url("$MODULE_NAME/$TB_NAME/edit");
$delete_url =base_url("$MODULE_NAME/$TB_NAME/delete");

echo AntiForgeryToken();
?>

<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>
        <ul><?php
            if(HaveAccess($create_url)) echo "<li><a onclick='javascript:{$TB_NAME}_create();' href='javascript:;'><i class='glyphicon glyphicon-plus'></i>جديد </a> </li>";
            if(HaveAccess($get_url) and HaveAccess($edit_url)) echo "<li><a onclick='javascript:{$TB_NAME}_get(get_id());' href='javascript:;'><i class='glyphicon glyphicon-edit'></i>تحرير</a> </li>";
            if(HaveAccess($delete_url)) echo "<li><a onclick='javascript:{$TB_NAME}_delete();' href='javascript:;'><i class='glyphicon glyphicon-remove'></i>حذف</a> </li>"; ?>
        </ul>

    </div>

    <div class="form-body">
        <div id="msg_container"></div>

        <div id="container">
            <?=modules::run("$MODULE_NAME/$TB_NAME/get_item"); ?>
        </div>
    </div>

</div>

<div class="modal fade" id="<?=$TB_NAME?>Modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title">بيانات قطع الغيار</h4>
            </div>
            <form class="form-horizontal" id="<?=$TB_NAME?>_form" method="post" action="<?=base_url("$MODULE_NAME/$TB_NAME/create")?>" role="form" novalidate="novalidate">
                <div class="modal-body">

                    <div class="form-group">
                        <label class="col-sm-3 control-label">رقم الصنف</label>
                        <div class="col-sm-5">
                            <input type="text" data-val="true"  readonly data-val-required="حقل مطلوب" name="class_no" id="txt_class_no" class="form-control" maxlength="5"    dir="ltr">
                            <span class="field-validation-valid" data-valmsg-for="tb_no" data-valmsg-replace="true"></span>
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="col-sm-3 control-label">اسم الصنف</label>
                        <div class="col-sm-5">
                            <input type="text" data-val="true"  data-val-required="حقل مطلوب" name="class_name" id="txt_class_name" class="form-control" maxlength="20">
                            <span class="field-validation-valid" data-valmsg-for="tb_name" data-valmsg-replace="true"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">حالة الصنف</label>
                        <div class="col-sm-5">

                            <select type="text"  name="class_status" id="dp_class_status" class="form-control" >
                                <option></option>
                                <?php foreach($class_status as $row) :?>
                                    <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">الوحدة</label>
                        <div class="col-sm-5">
                            <select type="text"  name="class_unit" id="dp_class_unit" class="form-control" >
                                <option></option>
                                <?php foreach($class_unit as $row) :?>
                                    <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <?php if (HaveAccess($create_url) and HaveAccess($edit_url) ) : ?>
                        <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                    <?php endif; ?>
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
            $('#txt_class_name').focus();
        });
    });
    
     function get_id(){
        return ( $("tbody>[class~='selected']").attr('data-id') );
    }

    function {$TB_NAME}_create(){ 
        clearForm($('#{$TB_NAME}_form'));
        $('#txt_class_no').val({$count_all}+1);
        $('#{$TB_NAME}_form').attr('action','{$create_url}');
        $('#{$TB_NAME}Modal').modal();
        
    }

    function {$TB_NAME}_get(id){
        get_data('{$get_url}',{id:id},function(data){
            $.each(data, function(i,item){
                $('#txt_class_no').val(item.CLASS_NO);
                $('#txt_class_name').val( item.CLASS_NAME);
                $('#txt_class_unit').val( item.CLASS_UNIT);
                $('#dp_class_status').val( item.CLASS_STATUS);
                $('#txt_class_no').prop('readonly',true);
                $('#{$TB_NAME}_form').attr('action','{$edit_url}');
                resetValidation($('#{$TB_NAME}_form'));
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
            $('#{$TB_NAME}Modal').modal('hide');
        },"html");
    });

</script>

SCRIPT;

sec_scripts($scripts);

?>
