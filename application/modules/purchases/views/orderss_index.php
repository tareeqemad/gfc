<?php
/**
 * Created by PhpStorm.
 * User: tekrayem
 * Date: 29/01/17
 * Time: 02:43 م
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');


$MODULE_NAME= 'purchases';
$TB_NAME= 'orders';


$get_page_url = base_url("$MODULE_NAME/$TB_NAME/public_get_orders");
$customer_url =base_url('payment/customers/public_index');
$get_url =base_url("$MODULE_NAME/$TB_NAME/get");
$order_purpose=1;
echo AntiForgeryToken();
?>

<div class="row">


    <div class="form-body">
        <div class="modal-body inline_form">
        </div>
        <form class="form-vertical"  id="<?=$TB_NAME?>_form" >
            <div class="form-group col-sm-1">
                <label class="control-label">رقم أمر التوريد</label>
                <div>
                   <!-- <input type="hidden"  name="text"  value="<?= $text ?>"   id="txt_text" class="form-control">-->
                   <input type="text" name="order_text_t"  id="txt_order_text_t" value="<?=$order_text_t?>" class="form-control">
                </div>
            </div>
         <!--   <div class="form-group col-sm-1">
                <label class="control-label">رقم المورد  </label>
                <div>

                    <input type="text" name="customer_id" id="txt_customer_id"  value="<?=$customer_id?>" class="form-control">
                </div>
            </div> -->
            <div class="form-group col-sm-4">
                <label class="control-label">المورد</label>
                <div>
                  <select name="customer_id" style="width: 250px" id="h_txt_customer_id" >
                        <option></option>
                        <?php foreach($customers as $row) :?>
                            <option value="<?= $row['CUSTOMER_ID'] ?>"><?=$row['CUSTOMER_NAME'] ?></option>
                        <?php endforeach; ?>
                    </select>

                </div>
            </div>

            <div class="form-group col-sm-1">
                <label class="control-label">عملة المورد</label>
                <div>
                    <select  name="customer_curr_id" id="txt_customer_curr_id"  data-curr="false"  class="form-control">
                        <option value="-1">...</option>
                        <?php foreach($currency as $row) :?>
                            <option  data-val="<?= $row['VAL'] ?>"  value="<?= $row['CURR_ID'] ?>"><?= $row['CURR_NAME'] ?></option>
                        <?php endforeach; ?>
                    </select>
                     </div>
            </div>

            <div style="width:150px; padding-top: 25px; float: left;">
                <button type="button" onclick="javascript:search();" class="btn btn-success">بحث</button>
                <button type="button" onclick="javascript:fclear();" class="btn btn-default">تفريغ </button>
            </div>
        </form>
        <div id="container">
            <?= modules::run($get_page_url,$page,$order_text_t,$customer_id,$customer_curr_id);?>
        </div>

    </div>

</div>

<?php
$scripts = <<<SCRIPT
<script>
 $('#h_txt_customer_id').select2();
    $('.pagination li').click(function(e){
        e.preventDefault();
    });


 /*  function show_row_details(id){

        get_to_link('{$get_url}/'+id+'/'+{$order_purpose});

    }*/

   function search(){
        get_data('{$get_page_url}',{page:1, order_text_t:$('#txt_order_text_t').val(),customer_id:$('#h_txt_customer_id').val(),customer_curr_id:$('#txt_customer_curr_id').val()},function(data){
            $('#container').html(data);
        },'html');
    }

    function LoadingData(){
        ajax_pager_data('#orders_tb > tbody',{ order_text_t:$('#txt_order_text_t').val(),customer_id:$('#h_txt_customer_id').val(),customer_curr_id:$('#txt_customer_curr_id').val()});

       }



    function fclear(){
        clearForm($('#{$TB_NAME}_form'));

    }
    function select_order(id,customer_id,customer_name,order_text){
         parent.$('#txt_order_id').val(order_text);
         parent.$('#txt_customer_name').val(customer_name);
         parent.$('#h_txt_customer_name').val(customer_id);
         parent.$('#dp_account_type').val(2);
        if (typeof parent.after_selected_order == 'function') {
         parent.after_selected_order(id);
        }
       }
</script>
SCRIPT;
sec_scripts($scripts);
?>
