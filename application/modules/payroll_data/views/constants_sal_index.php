<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 20/06/22
 * Time: 08:05 ص
 */

$MODULE_NAME= 'payroll_data';
$TB_NAME= 'Constants_sal';
$TB_NAME2= 'Constant_sal_details';

$get_url =base_url("$MODULE_NAME/$TB_NAME/get_id");
$create_url =base_url("$MODULE_NAME/$TB_NAME/create");
$edit_url =base_url("$MODULE_NAME/$TB_NAME/edit");
$delete_url =base_url("$MODULE_NAME/$TB_NAME/delete");
$get_details_url =base_url("$MODULE_NAME/$TB_NAME2/get_page");
$select_accounts_url =base_url('financial/accounts/public_select_accounts');
echo AntiForgeryToken();
?>

<!-- PAGE-HEADER -->
<div class="page-header">
    <div>
        <h1 class="page-title">ثوابت النظام</h1>
    </div>
    <div class="ms-auto pageheader-btn">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">الرواتب</a></li>
            <li class="breadcrumb-item active" aria-current="page">ثوابت النظام</li>
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

                    <a class="btn btn-danger" onclick='javascript:<?= $TB_NAME ?>_delete();' href='javascript:;'><i
                                class='glyphicon glyphicon-remove'></i>حذف</a>
                    <a class="btn btn-success" onclick='javascript:<?= $TB_NAME ?>_get(get_id());' href='javascript:;'><i
                                class='glyphicon glyphicon-edit'></i>تحرير</a>
                    <a class="btn btn-info" onclick='javascript:<?= $TB_NAME ?>_create();' href='javascript:;'><i
                                class='glyphicon glyphicon-plus'></i>جديد </a>
                    <?php if (HaveAccess($get_url) and HaveAccess($edit_url)) echo "<li><a onclick='javascript:{$TB_NAME}_get(get_id());' class=\"btn btn-info\" href='javascript:;'><i class='glyphicon glyphicon-edit'></i>تحرير</a> </li>"; ?>
                </div>
            </div><!-- end card header -->
            <div class="card-body">

                <div id="container">
                    <?= modules::run("$MODULE_NAME/$TB_NAME/get_page"); ?>
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
                <h5 class="modal-title">بيانات الثابت</h5>
                <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
            <form class="form-horizontal" id="<?=$TB_NAME?>_from" method="post" action="<?=base_url("$MODULE_NAME/$TB_NAME/create")?>" role="form" novalidate="novalidate">

                <div class="col-md-12">
                    <div class="row">
                    <div class="form-group col-md-3">
                        <label>رقم الثابت</label>
                        <input type="text" name="tb_no" readonly id="txt_tb_no" class="form-control">
                    </div>

                    <div class="form-group col-md-3">
                        <label>اسم الثابت</label>
                        <input type="text" name="tb_name" id="txt_tb_name" class="form-control">
                    </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" data-action="submit" class="btn btn-blue">حفظ البيانات</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="close_modal()">إغلاق</button>

                </div>
            </form>
            </div>

        </div>
    </div>
</div>


<div class="modal fade" id="<?=$TB_NAME2?>Modal">
    <div class="modal-dialog modal-xl" >
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">بيانات الثابت</h5>
                <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <form class="form-horizontal" id="<?=$TB_NAME2?>_from" method="post" action="<?=base_url("$MODULE_NAME/$TB_NAME2/edit")?>" role="form" novalidate="novalidate">
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="close_modal()">إغلاق</button>
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
        $('#DetailModal').on('shown.bs.modal', function () {
            $('#txt_tb_name').focus();
        });
    });

    function get_id(){
        return ( $("tbody>[class~='selected']").attr('data-id') );
    }

    function {$TB_NAME}_create(){
        clearForm($('#{$TB_NAME}_from'));
        $('#txt_tb_no').val({$count_all}+1);
        $('#{$TB_NAME}_from').attr('action','{$create_url}');
        $('#DetailModal').modal('show');
    }

    function {$TB_NAME}_get(id){
        get_data('{$get_url}',{id:id},function(data){
            $.each(data, function(i,item){
                $('#txt_tb_no').val(item.TB_NO);
                $('#txt_tb_name').val( item.TB_NAME);
                $('#txt_tb_no').prop('readonly',true);
                $('#{$TB_NAME}_from').attr('action','{$edit_url}');
                resetValidation($('#{$TB_NAME}_from'));
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
            if(confirm('هل تريد بالتأكيد حذف '+val.length+' سجلات وحذف تفاصيلها ؟!!')){
                ajax_delete(url, val ,function(data){
                    success_msg('رسالة','تم حذف السجلات بنجاح ..');
                    get_to_link(window.location.href);
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
            get_to_link(window.location.href);
            container.html(data);
            $('#DetailModal').modal('hide');
        },"html");
    });

    function {$TB_NAME2}_get(id, name){
        clearForm($('#{$TB_NAME2}_from'));
        $("#{$TB_NAME2}_from .modal-body").text('');
        $('#{$TB_NAME2}Modal .modal-title').text(name);

        get_data('{$get_details_url}', {tb_no:id}, function(ret){
            $('#{$TB_NAME2}Modal').modal('show');
            $("#{$TB_NAME2}_from .modal-body").html(ret);
        }, 'html');
    }
    
    function close_modal() {
        $('#DetailModal').modal('hide');
        $('#{$TB_NAME2}Modal').modal('hide');
    }

    reBind();
    function reBind(){

        $('.accounts').on('click',function(){

        _showReport('{$select_accounts_url}/'+$(this).attr('id')+'/-1/-1/0')

        });
    }

</script>

SCRIPT;

sec_scripts($scripts);

?>
