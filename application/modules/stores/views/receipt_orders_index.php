<?php
/**
 * Created by PhpStorm.
 * User: tekrayem
 * Date: 15/12/14
 * Time: 12:04 م
 */
$MODULE_NAME= 'stores';
$TB_NAME= 'receipt_class_input';
$get_url =base_url("$MODULE_NAME/$TB_NAME/public_get_receipt_orders");

?>

<div class="form-body">

    <div class="modal-body inline_form">
        <form  id="<?=$TB_NAME?>_form" method="get" action="<?=$get_url?>" role="form" novalidate="novalidate">

            <div class="form-group col-sm-2">
                <label class="control-label">رقم أمر التوريد</label>
                <div>
                    <input type="text"  name="id" id="txt_id" value="<?= $id ?>" class="form-control">
                </div>
            </div>

            <div class="form-group col-sm-3">
                <label class="control-label"> رقم محضر الفحص والاستلام </label>
                <div>
                    <input type="text"  name="name"  value="<?= $name ?>"  id="txt_name" class="form-control">
                </div>
            </div>



            <input type="hidden"  name="text"  value="<?= $text ?>"   id="txt_text" class="form-control">


            <div class="form-group col-sm-1">
                <label class="control-label">&nbsp;</label>
                <div>
                    <button type="button" onclick="javascript:<?=$TB_NAME?>_search();" class="btn btn-success">بحث</button>
                </div>
            </div>

            <div class="form-group col-sm-1">
                <label class="control-label">&nbsp;</label>
                <div>
                    <button type="button" onclick="javascript:<?=$TB_NAME?>_clear();" class="btn btn-success">تفريغ الحقول</button>
                </div>
            </div>
        </form>
    </div>

    <div id="container">
        <?=modules::run("$MODULE_NAME/$TB_NAME/public_get_receipt_orders", array('text'=>$text, 'id'=>$id, 'name'=>$name,  'page'=>$page)); ?>
    </div>

</div>

</div>

<?php
$scripts = <<<SCRIPT
<script>
    function {$TB_NAME}_search(){
        var id= $('#txt_id').val();
        var name= $('#txt_name').val();
             var text= $('#txt_text').val();
        get_data('{$get_url}',{id:id, name:name,text:text},function(data){
            $('#container').html(data);
        },'html');
    }

    function {$TB_NAME}_clear(){
        $('#{$TB_NAME}_form :input').val('');
    }
    function select_class(receord_id_text,order_id,receord_id,cust_id,cust_name,class_input_class_type,store_id){

           parent.$('#txt_order_id_text').val(receord_id_text);
            parent.$('#txt_order_id').val(order_id);
            parent.$('#txt_receord_id').val(receord_id);
           parent.$('#h_txt_customer_name').val(cust_id);
   parent.$('#txt_customer_name').val(cust_name);
      parent.$('#dp_class_input_class_type').val(class_input_class_type);
         parent.$('#dp_store_id').val(store_id);
             parent.$('#report').modal('hide');

    }

</script>
SCRIPT;
sec_scripts($scripts);

?>
