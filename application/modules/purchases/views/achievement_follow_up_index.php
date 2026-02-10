<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$MODULE_NAME= 'purchases';
$TB_NAME= 'Achievement_follow_up';
$TB_NAME2= 'Achievement_follow_up_details';

$get_url =base_url("$MODULE_NAME/$TB_NAME/get_id");
$create_url =base_url("$MODULE_NAME/$TB_NAME/create");
$edit_url =base_url("$MODULE_NAME/$TB_NAME/edit");
$delete_url =base_url("$MODULE_NAME/$TB_NAME/delete");
$get_details_url =base_url("$MODULE_NAME/$TB_NAME2/get_page");
$select_accounts_url =base_url('financial/accounts/public_select_accounts');
echo AntiForgeryToken();
?>
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
    <div class="left-content">
        <span class="main-content-title mg-b-0 mg-b-lg-1"><?= $title ?></span>
    </div>
    <div class="justify-content-center mt-2">
        <ol class="breadcrumb">
            <li class="breadcrumb-item tx-15"><a href="javascript:void(0);">المشتريات</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?= $title ?></li>
        </ol>
    </div>
</div>
<!-- /breadcrumb -->
<!-- Row -->
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header align-items-center d-flex py-3">
                <div class="mb-0 flex-grow-1 card-title">
                    <?= $title ?>
                </div>
                <div class="flex-shrink-0">
                    <?php if( HaveAccess($create_url)):  ?>
                        <a class="btn btn-secondary" onclick="javascript:Achievement_follow_up_create();" href='javascript:;'><i class='glyphicon glyphicon-plus'></i>جديد </a>
                    <?php endif; ?>
                </div>
            </div><!-- end card header -->
            <div class="card-body">
                <form id="<?= $TB_NAME ?>_form">
                    <div class="row">

                    </div>

                    <div class="row">

                    </div>
                </form>
                <div class="flex-shrink-0">
                  <!--  <button type="button" onclick="javascript:search();" class="btn btn-primary"><i class="fa fa-search"></i> إستعلام</button>
                    <button type="button" onclick="$('#page_tb').tableExport({type:'excel',escape:'false'});" class="btn btn-success"><i class="fas fa-file-excel"></i>إكسل</button>
                    <button type="button" onclick="javascript:clear_form();"  class="btn btn-cyan-light"><i class="fas fa-eraser"></i>تفريغ الحقول</button>-->
                </div>
                <hr>
                <div id="container">
                    <?=modules::run("$MODULE_NAME/$TB_NAME/get_page"); ?>
                </div>
            </div><!--end card body-->
        </div><!--end card --->
    </div><!--end col lg-12--->

    <!-- Extra-large  modal -->
    <div class="modal fade" id="<?=$TB_NAME?>Modal">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content modal-content-demo">
                <div class="modal-header">
                    <h6 class="modal-title">بيانات</h6>
                    <button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                </div>
                <form class="form-horizontal" id="<?=$TB_NAME?>_from" method="post" action="<?=base_url("$MODULE_NAME/$TB_NAME2/edit")?>" role="form" novalidate="novalidate">

                <div class="modal-body">
                    <div class="tr_border">
                        <div class="row">

                            <div class="form-group col-md-2">
                                <label>رقم الثابت</label>
                                <input type="text"  name="ser" readonly id="txt_ser" class="form-control ltr">

                            </div>

                            <div class="form-group col-md-4">
                                <label>اسم الثابت</label>
                                <input type="text" data-val="true"  data-val-required="حقل مطلوب" name="activity_name" id="txt_activity_name" class="form-control">
                                <span class="field-validation-valid" data-valmsg-for="activity_name" data-valmsg-replace="true"></span>
                            </div>

                            <div class="form-group col-md-2">
                                <label> ترتيب الثابت</label>
                                <input type="text" data-val="true"  data-val-required="حقل مطلوب" name="proiorty" id="txt_proiorty" class="form-control" maxlength="3">
                                <span class="field-validation-valid" data-valmsg-for="proiorty" data-valmsg-replace="true"></span>
                            </div>

                            <div class="form-group col-md-4">
                                <label> فعالية الثابت</label>
                                <select name="status" data-val="true" data-val-required="حقل مطلوب" id="dp_status"
                                        class="form-control select2">

                                    <?php foreach ($status as $row) : ?>
                                        <option value="<?= $row['CON_NO'] ?>">
                                            <?= $row['CON_NAME'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <span class="field-validation-valid" data-valmsg-for="status"
                                      data-valmsg-replace="true"></span>
                            </div>


                              </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <?php  if(HaveAccess($edit_url) or HaveAccess($create_url)){?>
                    <button data-action="submit" type="submit" class="btn ripple btn-primary"  >حفظ</button>
                    <?php }?>
                    <button class="btn ripple btn-secondary" data-bs-dismiss="modal" type="button">اغلاق</button>
                </div>
            </div>
            </form>
        </div>
    </div>
    <!--End Extra-large modal -->


    <!-- Extra-large  modal -->
    <div class="modal fade" id="<?=$TB_NAME2?>Modal">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content modal-content-demo">
                <div class="modal-header">
                    <h6 class="modal-title">بيانات</h6>
                    <button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                </div>
                <form class="form-horizontal" id="<?=$TB_NAME2?>_from" method="post" action="<?=base_url("$MODULE_NAME/$TB_NAME2/edit")?>" role="form" novalidate="novalidate">

                <div class="modal-body">
                    <div class="row">
                        <div class="tr_border">

                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <?php  /*if(HaveAccess($edit_url) or HaveAccess($create_url)){?>
                        <button data-action="submit" type="submit" class="btn ripple btn-primary"  >حفظ</button>
                    <?php }*/?>
                   <!-- <button class="btn ripple btn-secondary" data-bs-dismiss="modal" type="button">اغلاق</button> -->
                </div>
                </form>
            </div>
        </div>
    </div>
    <!--End Extra-large modal -->

</div><!--end row--->
<?php
$scripts = <<<SCRIPT
<script type="text/javascript">

$('#dp_status').select2();
    $(document).ready(function() {
        $('#constantss_tb').dataTable({
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

    function Achievement_follow_up_create(){
        clear_form();
        $('#txt_ser').val({$count_all}+1);
        $('#{$TB_NAME}_from').attr('action','{$create_url}');
        $('#{$TB_NAME}Modal').modal('show');
    }

    function Achievement_follow_up_get(id){
        get_data('{$get_url}',{id:id},function(data){
            $.each(data, function(i,item){
                $('#txt_ser').val(item.SER);
                $('#txt_activity_name').val( item.ACTIVITY_NAME);
                $('#txt_proiorty').val( item.PROIORTY);
                //$('#dp_status').val(item.STATUS);
                $("#dp_status").val(item.STATUS);
                $('#txt_ser').prop('readonly',true);
                $('#{$TB_NAME}_from').attr('action','{$edit_url}');
                resetValidation($('#{$TB_NAME}_from'));
                $('#{$TB_NAME}Modal').modal('show');
            });
        });
    }

    function {$TB_NAME}_delete(){
        var url = '{$delete_url}';
        var tbl = '#constant_tb';
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
            $('#constantss_tb').html(data);
            $('#{$TB_NAME}Modal').modal('hide');
        },"html");
    });

    function constant_details_get(id, name){
        clearForm($('#{$TB_NAME2}_from'));
        $("#{$TB_NAME2}_from .modal-body").text('');
        $('#{$TB_NAME2}Modal .modal-title').text(name);

        get_data('{$get_details_url}', {ser:id}, function(ret){
            $('#{$TB_NAME2}Modal').modal('show');
            //('#{$TB_NAME2}Modal').modal('hide');
            $("#{$TB_NAME2}_from .modal-body").html(ret);
        }, 'html');
    }

    reBind_pram(0);
    function reBind_pram(cnt){
        //$('table tr td .select2-container').remove();
         /*$('select[name="status_dets[]"]').select2({
                dropdownParent: $("#Achievement_follow_up_detailsModal")
        });*/
       
    }
    
    /*$('select[name="status_dets[]"]').select2({
                dropdownParent: $("#Achievement_follow_up_detailsModal")
    });*/

 function clear_form(){
   $('#{$TB_NAME}_from')[0].reset();
}
</script>

SCRIPT;

sec_scripts($scripts);

?>
