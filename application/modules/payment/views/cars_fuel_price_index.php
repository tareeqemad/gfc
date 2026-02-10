<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: Ahmed barakat
 * Date: 16/11/16
 * Time: 09:06 ص
 */


$MODULE_NAME= 'payment';
$TB_NAME= 'cars_fuel_price';


$get_url =base_url("$MODULE_NAME/$TB_NAME/get_id");
$create_url =base_url("$MODULE_NAME/$TB_NAME/create");
$edit_url =base_url("$MODULE_NAME/$TB_NAME/edit");
$delete_url =base_url("$MODULE_NAME/$TB_NAME/delete");

$select_accounts_url =base_url('financial/accounts/public_select_accounts');
$adopt_url = base_url("$MODULE_NAME/$TB_NAME/adopt");

echo AntiForgeryToken();
?>

<div class="row">
    <div class="toolbar">

        <div class="caption"> <?= $title ?></div>

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
                <h4 class="modal-title"> البيانات</h4>
            </div>
            <form class="form-horizontal" id="<?=$TB_NAME?>_from" method="post" action="<?=base_url("$MODULE_NAME/$TB_NAME/create")?>" role="form" novalidate="novalidate">
                <div class="modal-body">

                    <div class="form-group">
                        <label class="col-sm-3 control-label">رقم </label>
                        <div class="col-sm-2">
                            <input type="text"  name="prfuel_ser" readonly id="txt_prfuel_ser" class="form-control ltr">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">التاريخ </label>
                        <div class="col-sm-7">
                            <input type="text" data-val="true" readonly  data-type="date" data-date-format="DD/MM/YYYY"   data-val-required="حقل مطلوب" name="fuel_month_price" id="txt_fuel_month_price" class="form-control" >

                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">نوع الوقود </label>
                        <div class="col-sm-7">


                            <select type="text"  name="gasoline_id" id="dp_gasoline_id" class="form-control" required >
                                <?php foreach($fuel_type as $row) :?>
                                    <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label"> اسم المورد</label>
                        <div class="col-sm-7">

                            <select type="text"  name="supplier_id" id="dp_supplier_id" class="form-control" required >
                                <option></option>
                                <?php foreach($suppliers as $row) :?>
                                    <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">السعر </label>
                        <div class="col-sm-7">
                            <input type="text" data-val="true"  data-val-required="حقل مطلوب" name="gasoline_price" id="txt_gasoline_price" class="form-control" >

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
        $('#txt_tb_no').val({$count_all}+1);
        $('#{$TB_NAME}_from').attr('action','{$create_url}');
        $('#{$TB_NAME}Modal').modal();
    }

    function {$TB_NAME}_get(id){
        get_data('{$get_url}',{id:id},function(data){
            $.each(data, function(i,item){
                $('#txt_prfuel_ser').val(item.PRFUEL_SER);
                $('#txt_fuel_month_price').val( item.FUEL_MONTH_PRICE);
                $('#dp_gasoline_id').val( item.GASOLINE_ID);
                $('#txt_gasoline_price').val( item.GASOLINE_PRICE);
                $('#dp_supplier_id').val( item.SUPPLIER);


                $('#{$TB_NAME}_from').attr('action','{$edit_url}');
                resetValidation($('#{$TB_NAME}_from'));
                $('#{$TB_NAME}Modal').modal();
            });
        });
    }

    function {$TB_NAME}_adopt(id){
            var tbl = '#{$TB_NAME}_tb';
            var container = $('#' + $(tbl).attr('data-container'));
            if(confirm(' هل تريد اعتماد السجل')){

                get_data('{$adopt_url}', {id:id} ,function(data){
                    success_msg('تنبيه','تم اعتماد السجل بنجاح');
                    container.html(data);
                });
            }

    }

    $('button[data-action="submit"]').click(function(e){
         e.preventDefault();
        if($('#dp_supplier_id').val() == ''){
            danger_msg('تحذير','يرجى ادخال اسم المورد ..');
            return ;
        }
        var tbl = '#{$TB_NAME}_tb';
        var container = $('#' + $(tbl).attr('data-container'));
        var form = $(this).closest('form');
        ajax_insert_update(form,function(data){
            success_msg('رسالة','تم حفظ البيانات بنجاح ..');
            container.html(data);
            $('#{$TB_NAME}Modal').modal('hide');
        },"html");
    });


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
