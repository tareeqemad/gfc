<?php
/**
 * Created by PhpStorm.
 * User: tekrayem
 * Date: 08/10/18
 * Time: 12:48 م
 */
$MODULE_NAME= 'purchases';
$TB_NAME= 'vouchers';

$get_url =base_url("$MODULE_NAME/$TB_NAME/get_id");
$create_url =base_url("$MODULE_NAME/$TB_NAME/create");
$edit_url =base_url("$MODULE_NAME/$TB_NAME/edit");
$delete_url =base_url("$MODULE_NAME/$TB_NAME/delete");
$adopt_url=base_url("$MODULE_NAME/$TB_NAME/adopt");
$cancel_adopt_url=base_url("$MODULE_NAME/$TB_NAME/cancel_adopt");
$select_order_url = base_url("$MODULE_NAME/purchase_order/public_index");
echo AntiForgeryToken();
?>

<div class="row">
    <div class="toolbar">

        <div class="caption"> <?=$title?> </div>

        <ul><?php
          //  if(HaveAccess($create_url)) echo "<li><a onclick='javascript:{$TB_NAME}_create();' href='javascript:;'><i class='glyphicon glyphicon-plus'></i>جديد </a> </li>";
            if(HaveAccess($get_url) and HaveAccess($edit_url)) echo "<li><a onclick='javascript:{$TB_NAME}_get(get_id1(),get_id2());' href='javascript:;'><i class='glyphicon glyphicon-edit'></i>تحرير</a> </li>";
           if(HaveAccess($delete_url)) echo "<li><a onclick='javascript:{$TB_NAME}_delete();' href='javascript:;'><i class='glyphicon glyphicon-remove'></i>حذف</a> </li>";
            ?>
            <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>

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
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title"> بيانات الإيصالات</h4>
            </div>
            <form class="form-horizontal" id="<?=$TB_NAME?>_from" method="post" action="<?=base_url("$MODULE_NAME/$TB_NAME/create")?>" role="form" novalidate="novalidate">
                <div class="modal-body inline_form">


                    <div class="form-group">
                        <label class="col-sm-5 control-label">رقم الإيصال </label>

                        <div class="col-sm-1">
                            <input type="text" data-val="true"  readonly  data-val-required="حقل مطلوب" name="voucher_id" id="txt_voucher_id" class="form-control">
                            <span class="field-validation-valid" data-valmsg-for="title" data-valmsg-replace="true"></span>
                        </div>
                        <div class="col-sm-1">

                            <input type="text" data-val="true"  readonly  data-val-required="حقل مطلوب" name="entry_ser" id="txt_entry_ser" class="form-control">
                            <input type="hidden" data-val="true"    data-val-required="حقل مطلوب" name="ser" id="txt_ser" class="form-control">

                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">رقم طلب الشراء</label>
                        <div class="col-sm-7">
                            <input type="text" data-val="true"  readonly  data-val-required="حقل مطلوب" name="purchase_order_id" id="txt_purchase_order_id" class="form-control">
                            <span class="field-validation-valid" data-valmsg-for="title" data-valmsg-replace="true"></span>
                            <input type="text" readonly  name="purchase_order_num"  id="txt_purchase_order_num" class="form-control" />

<input type="hidden" name="adopt" id="txt_adopt" >
                        </div>
                    </div>



                    <div class="form-group">
                        <label class="col-sm-4 control-label">اسم المورد </label>
                        <div class="col-sm-7">
                             <select name="customer_id" id="dp_customer_id" class="form-control">
                                <?php foreach($customer_ids as $row) :?>
                                    <option value="<?= $row['CUSTOMER_ID'] ?>"><?= $row['CUSTOMER_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                    </div>

                    <div class="form-group">
                        <label class="col-sm-4 control-label"> ملاحظات  </label>
                        <div class="col-sm-7">
                            <textarea data-val="true" class="form-control" dir="rtl"  data-val-required="حقل مطلوب"  name="hints" id="txt_hints"></textarea>
                            <span class="field-validation-valid" data-valmsg-for="body" data-valmsg-replace="true"></span>


                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="submit" id="butt" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                        <button type="button" id="adopt_butt" onclick="adoptt();" class="btn btn-primary"> اعتماد</button>
                        <button type="button" id="cancel_adopt_butt" onclick="cancel_adoptt();" class="btn btn-primary"> إلغاء اعتماد</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">إغلاق</button>

                    </div>
                </div>
            </form>
        </div> <!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->



<?php
$scripts = <<<SCRIPT
<script type="text/javascript">

$('#dp_customer_id').select2();


  $('input[name="purchase_order_id"]').bind("focus", function(e) {
   $(this).val( $(this).val().replace('+', ''));
           _showReport('$select_order_url/'+$(this).attr('id')+'?order_purpose=1' );
    });

        $('#{$TB_NAME}_tb').dataTable({
            "lengthMenu": [ [10,20,30,40,50,100, -1], [10,20,30,40,50,100, "الكل"] ],
            "sPaginationType": "full_numbers"
        });



    $(function () {
        $('#{$TB_NAME}Modal').on('shown.bs.modal', function () {
            $('#txt_title').focus();
        });
    });

    function get_id1(){
        return ( $("tbody>[class~='selected']").attr('data-id') );
    }

     function get_id2(){
        return ( $("tbody>[class~='selected']").attr('data-var') );
    }

    function {$TB_NAME}_create(){
        clearForm($('#{$TB_NAME}_from'));

        $('#{$TB_NAME}_from').attr('action','{$create_url}');

        $('#{$TB_NAME}Modal').modal();
    }

    function {$TB_NAME}_get(id,v){


        get_data('{$get_url}',{id:id,voucher:v},function(data){
       // console.log(data);

            $.each(data, function(i,item){
                $('#txt_ser').val('');
                $('#txt_voucher_id').val( '');
                 $('#txt_entry_ser').val( '');
                $('#txt_purchase_order_id').val( '');
                $('#txt_hints').val( '');
                $('#txt_purchase_order_num').val( '');
                $('#txt_adopt').val( '');
               $('select[name="customer_id"]').select2("val",'');


               $('#txt_ser').val(item.SER);
                $('#txt_voucher_id').val( item.VOUCHER_ID);
                 $('#txt_entry_ser').val( item.ENTRY_SER);
                $('#txt_purchase_order_id').val( item.PURCHASE_ORDER_ID);
                $('#txt_hints').val( item.HINTS);
                $('#txt_purchase_order_num').val( item.PURCHASE_ORDER_NUM);
                $('#txt_adopt').val( item.ADOPT);
               $('select[name="customer_id"]').select2("val",item.CUSTOMER_ID);

                if(item.ADOPT==1 || item.ADOPT ==2 ){
                 $('#butt').show();}
                 else{
            $('#butt').hide();}

              if (item.ADOPT == 2 ){
               $('#adopt_butt').show();
                $('#cancel_adopt_butt').hide();
             } else if (item.ADOPT ==3 ){
               $('#cancel_adopt_butt').show();
                $('#adopt_butt ').hide();
             } else {
               $('#cancel_adopt_butt').hide();
                $('#adopt_butt ').hide();
             }


                $('#{$TB_NAME}_from').attr('action','{$edit_url}');
                resetValidation($('#{$TB_NAME}_from'));

                $('#{$TB_NAME}Modal').modal();

            });
        });
    }


function cancel_adoptt(){
if(confirm('هل تريد إتمام العملية ؟')){
    var ser=$('#txt_ser').val();
   get_data('{$cancel_adopt_url}',{id:ser },function(data){

      if(data =='1'){
                      success_msg('رسالة','تمت العملية بنجاح');
                   window.location.reload();
}
            },'html');

}

}

function adoptt(){
if(confirm('هل تريد إتمام العملية ؟')){
    var ser=$('#txt_ser').val();
   get_data('{$adopt_url}',{id:ser },function(data){

      if(data =='1'){
                      success_msg('رسالة','تمت العملية بنجاح');
                   window.location.reload();
}
            },'html');

}
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
                   // container.html(data);
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
          //  container.html(data);
           window.location.reload();
            $('#{$TB_NAME}Modal').modal('hide');
        },"html");
        window.location.reload();
    });


</script>

SCRIPT;

sec_scripts($scripts);

?>
