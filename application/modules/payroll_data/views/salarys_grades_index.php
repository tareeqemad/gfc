<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 19/06/22
 * Time: 08:30 ص
 */

$MODULE_NAME = 'payroll_data';
$TB_NAME = 'Salarys_grades';
$get_url = base_url("$MODULE_NAME/$TB_NAME/get_id");
$get_max_no_url = base_url("$MODULE_NAME/$TB_NAME/get_max_no");
$create_url = base_url("$MODULE_NAME/$TB_NAME/create");
$edit_url = base_url("$MODULE_NAME/$TB_NAME/edit");
$delete_url = base_url("$MODULE_NAME/$TB_NAME/delete");

echo AntiForgeryToken();
?>

<!-- PAGE-HEADER -->
<div class="page-header">
    <div>
        <h1 class="page-title">سلم الرواتب</h1>
    </div>
    <div class="ms-auto pageheader-btn">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">الرواتب</a></li>
            <li class="breadcrumb-item active" aria-current="page">سلم الرواتب</li>
        </ol>
    </div>
</div>
<!-- PAGE-HEADER END -->

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header align-items-center d-flex py-3">
                <div class="mb-0 flex-grow-1 card-title">
                    <?= $title ?>
                </div>
                <div class="flex-shrink-0">

                    <?php if ( HaveAccess($delete_url) ) : ?>
                        <a class="btn btn-danger" onclick='javascript:<?= $TB_NAME ?>_delete();' href='javascript:;'><i
                                    class="fe fe-trash-2 text-end text-white"></i>حذف</a>
                    <?php endif; ?>

                    <?php if ( HaveAccess($edit_url) ) : ?>
                        <a class="btn btn-success" onclick='javascript:<?= $TB_NAME ?>_get(get_id());' href='javascript:;'><i
                                    class="far fa-folder-open"></i>تحرير</a>
                    <?php endif; ?>

                    <?php if ( HaveAccess($create_url) ) : ?>
                        <a class="btn btn-info" onclick='javascript:<?= $TB_NAME ?>_create();' href='javascript:;'><i
                                    class="fa fa-plus"></i>جديد </a>
                    <?php endif; ?>

                </div>
            </div><!-- end card header -->
            <div class="card-body">
                <div class="row">
                    <?= modules::run("$MODULE_NAME/$TB_NAME/get_item"); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="DetailModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">بيانات العلاوات</h5>
                <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" id="<?= $TB_NAME ?>_form" method="post"
                      action="<?= base_url("$MODULE_NAME/$TB_NAME/create") ?>" role="form" novalidate="novalidate">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="form-group col-md-2">
                                <label>الرقم التسلسلي</label>
                                <input type="text" data-val="true" readonly data-val-required="حقل مطلوب" name="ser" id="txt_ser" class="form-control" maxlength="5" value="<?=$maxes?>" >
                                <span class="field-validation-valid" data-valmsg-for="ser" data-valmsg-replace="true"></span>
                            </div>

                            <div class="form-group col-md-3">
                                <label>الدرجة</label>
                                <input type="text" data-val="true" data-val-required="حقل مطلوب" name="gradesn_name" id="txt_gradesn_name" class="form-control" >
                                <span class="field-validation-valid" data-valmsg-for="gradesn_name" data-valmsg-replace="true"></span>
                            </div>

                            <div class="form-group col-md-2">
                                <label>الراتب الاساسي</label>
                                <input type="text"  data-val="true" data-val-required="حقل مطلوب" name="basic_salary" id="txt_basic_salary" class="form-control" onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))">
                                <span class="field-validation-valid" data-valmsg-for="basic_salary" data-valmsg-replace="true"></span>
                            </div>

                            <div class="form-group col-md-3">
                                <label>ملاحظات</label>
                                <input type="text" name="notes" id="txt_notes" class="form-control">
                            </div>

                            <div class="form-group col-md-2">
                                <label>نوع الموظف</label>

                                <select data-val="true" name="emp_type" id="dp_emp_type" class="form-control sel2" data-val="true" data-val-required="حقل مطلوب"
                                        required>
                                    <option value="">_________</option>
                                    <?php foreach ($emp_type_cons as $row) : ?>
                                        <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <span class="field-validation-valid" data-valmsg-for="emp_type" data-valmsg-replace="true"></span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" data-action="submit" class="btn btn-blue">حفظ البيانات</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="close_modal()">إلغاء</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<?php

$scripts = <<<SCRIPT
<script type="text/javascript">


    $('#{$TB_NAME}_tb').DataTable({
        responsive: true
    });
    
    $(function () {
        $('#DetailModal').on('shown.bs.modal', function () {
            $('#txt_gradesn_name').focus();
        });
    });
    
     function get_id(){
        return ( $("tbody>[class~='selected']").attr('data-id') );
    }

    function {$TB_NAME}_create(){
        clearForm($('#{$TB_NAME}_form'));
        $('#txt_ser').val({$maxes});
        $('#{$TB_NAME}_form').attr('action','{$create_url}');
        $('#DetailModal').modal('show');
    }

    function {$TB_NAME}_get(id){
        get_data('{$get_url}',{id:id},function(data){
            $.each(data, function(i,item){
                $('#txt_ser').val(item.NO);
                $('#txt_gradesn_name').val( item.NAME);
                $('#txt_basic_salary').val( item.BASIC_SALARY);
                $('#txt_notes').val( item.NOTES);
                $('#dp_emp_type').val( item.TYPE);
                $('#txt_ser').prop('readonly',true);
                $('#{$TB_NAME}_form').attr('action','{$edit_url}');
                resetValidation($('#{$TB_NAME}_form'));
                $('#DetailModal').modal('show');
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
                    get_to_link(window.location.href);
                    container.html(data);
                });
            }
        }else
            alert('يجب تحديد السجلات المراد حذفها');
    }
    
    function close_modal() {
        $('#DetailModal').modal('hide');
    }
    
    $('button[data-action="submit"]').click(function(e){
        e.preventDefault();
        var tbl = '#{$TB_NAME}_tb';
        var container = $('#' + $(tbl).attr('data-container'));
        var form = $(this).closest('form');
        ajax_insert_update(form,function(data){
            success_msg('رسالة','تم حفظ البيانات بنجاح ..');
            get_to_link(window.location.href);
            container.html(data);
            $('#DetailModal').modal('hide');

        },"html");
    });

</script>

SCRIPT;

sec_scripts($scripts);

?>
