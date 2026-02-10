<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 25/03/15
 * Time: 03:58 م
 */

$MODULE_NAME= 'purchases';
$TB_NAME= 'purchase_order';
$get_page_url = base_url("$MODULE_NAME/$TB_NAME/public_get_page");

?>
<script> var show_page=true; </script>

<div class="form-body">

    <div class="modal-body inline_form">
        <form  id="<?=$TB_NAME?>_form" method="get" action="<?=$get_page_url?>" role="form" novalidate="novalidate">

            <div class="form-group col-sm-1">
                <label class="control-label">رقم المسلسل</label>
                <div>
                    <input type="text" name="purchase_order_id" id="txt_purchase_order_id" class="form-control">
                </div>
            </div>

            <div class="form-group col-sm-1">
                <label class="control-label"> رقم طلب الشراء</label>
                <div>
                    <input type="text" name="purchase_order_num" id="txt_purchase_order_num" class="form-control">
                </div>
            </div>

            <div class="form-group col-sm-3">
                <label class="control-label"> البيان </label>
                <div>
                    <input type="text" name="notes" id="txt_notes" class="form-control">
                </div>
            </div>

            <div class="form-group col-sm-1">
                <label class="control-label">&nbsp;</label>
                <div>
                    <button type="button" onclick="javascript:<?=$TB_NAME?>_search();" class="btn btn-success">بحث</button>
                </div>
            </div>

        </form>
    </div>

    <div id="container">
        <?=modules::run("$MODULE_NAME/$TB_NAME/public_get_page",$text, $page, $committees, $committee_case, $purchase_order_id, $purchase_order_num, $notes); ?>
    </div>

</div>

<?php
$scripts = <<<SCRIPT
<script>

    function show_row_details(id,curr_id,curr_name,purchase_type_name,purchase_notes,purchase_order_num, quote_condition){
        parent.$('#$text').val(id);
        parent.$('#dp_curr_id').val(curr_id);
        parent.$('#txt_curr_id_name').val(curr_name);
        parent.$('#txt_purchase_type_name').val(purchase_type_name);
        parent.$('#txt_purchase_notes').val(purchase_notes);
        parent.$('#txt_purchase_order_num').val(purchase_order_num);
        parent.$('#txt_quote_condition').val(quote_condition);

        parent.$('#report').modal('hide');

        if (typeof parent.getDetails == 'function') {
            parent.getDetails();
        }
    }

    function {$TB_NAME}_search(){
        get_data('{$get_page_url}/$text/1/-1/-1/'+check_vars($('#txt_purchase_order_id').val())+'/'+check_vars($('#txt_purchase_order_num').val())+'/'+check_vars($('#txt_notes').val())+'?order_purpose={$order_purpose}',{},function(data){
            $('#container').html(data);
        },'html');
    }

    function check_vars(val){
        if(val==null || val=='' || val==undefined)
            return -1;
        else
            return val;
    }

    function clear_form(){
        clearForm($('#{$TB_NAME}_form'));
    }

</script>
SCRIPT;
sec_scripts($scripts);
?>
