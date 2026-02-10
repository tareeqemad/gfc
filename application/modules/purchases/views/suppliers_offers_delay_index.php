<?php
/**
 * Created by PhpStorm.
 * User: tekrayem
 * Date: 11/03/15
 * Time: 10:13 ص
 */


$MODULE_NAME= 'purchases';
$TB_NAME= 'suppliers_offers_delay';
$create_url =base_url("$MODULE_NAME/$TB_NAME/create?order_purpose=".$order_purpose);
$get_url =base_url("$MODULE_NAME/suppliers_offers/delay$order_purpose");
$delete_url =base_url("$MODULE_NAME/$TB_NAME/delete");
$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page");
$customer_url =base_url('payment/customers/public_index');
$select_order_url = base_url("$MODULE_NAME/purchase_order/public_index");
$do_order_delay_url= base_url("$MODULE_NAME/$TB_NAME/do_order_delay");
$do_order_items_delay_url= base_url("$MODULE_NAME/$TB_NAME/do_order_items_delay");
echo AntiForgeryToken();
?>

<div class="row">
    <div class="toolbar">
        <div class="caption"><?= $title ?></div><!---->
        <ul>
            <?php if( HaveAccess($create_url)):  ?><li><a  href="<?= $create_url ?>"><i class="glyphicon glyphicon-plus"></i>جديد </a> </li><?php endif; ?>
            <?php if( HaveAccess($delete_url) and false):  ?><li><a  onclick="javascript:ddelete();" href="javascript:;"><i class="glyphicon glyphicon-remove"></i>حذف </a> </li><?php endif; ?>
            <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>
        </ul>

    </div>

    <div class="form-body">
        <div class="modal-body inline_form">
        </div>
        <form class="form-vertical"  id="<?=$TB_NAME?>_form" >
            <div class="modal-body inline_form">
                <div class="form-group col-sm-1">
                    <label class="control-label">رقم  طلب التأجيل </label>
                    <div>
                        <input type="text" name="delay_id"  id="txt_delay_id" class="form-control" />
                        <input type="hidden" name="order_purpose" value="<?=$order_purpose?>"  id="dp_order_purpose">

                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">رقم مسلسل الشراء </label>
                    <div>
                        <input type="text"   name="purchase_order_id"  id="txt_purchase_order_id" class="form-control" />
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label"> الحالة </label>
                    <div>
                        <select name="award_case" id="dp_award_case" class="form-control" />
                        <option></option>
                        <?php foreach($award_case_all as $row) :?>
                            <option value="<?=$row['CON_NO']?>"><?=$row['CON_NAME']?></option>
                        <?php endforeach; ?>
                        </select>
                    </div>
                </div>

            </div>
        </form>

        <div class="modal-footer">
            <button type="button" onclick="javascript:search();" class="btn btn-success"> إستعلام</button>
            <button type="button" onclick="javascript:clear_form();"  class="btn btn-default"> تفريغ الحقول</button>
        </div>
        <div id="msg_container"></div>

        <div id="container">
            <?=modules::run($get_page_url, $page, $delay_id ,$purchase_order_id, $award_case,$order_purpose);?>
        </div>

    </div>

</div>

<?php
$scripts = <<<SCRIPT
<script>
  $('input[name="purchase_order_id"]').bind('keyup', '+', function(e) {
   $(this).val( $(this).val().replace('+', ''));
           _showReport('$select_order_url/'+$(this).attr('id')+'?order_purpose=$order_purpose' );
    });
  function ddelete(){
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
                });
            }
        }else
            alert('يجب تحديد السجلات المراد حذفها');
    }


    $('.pagination li').click(function(e){
        e.preventDefault();
    });

   function show_row_details(order,delay_id){

        get_to_link('{$get_url}/'+order+'/'+delay_id);

    }

   function search(){

        get_data('{$get_page_url}',{page:1, delay_id:$('#txt_delay_id').val(), purchase_order_id:$('#txt_purchase_order_id').val(), award_case:$('#dp_award_case').val(), order_purpose:$('#dp_order_purpose').val()},function(data){
            $('#container').html(data);
        },'html');
    }

    function LoadingData(){
        ajax_pager_data('#suppliers_offers_delay_tb > tbody',{delay_id:$('#txt_delay_id').val(), purchase_order_id:$('#txt_purchase_order_id').val(), award_case:$('#dp_award_case').val(), order_purpose:$('#dp_order_purpose').val()});
    }



    function clear_form(){
        clearForm($('#{$TB_NAME}_form'));

    }
    function do_order_delay(p_id,d_id,o){
      var url='';
    if (o==1) url='{$do_order_delay_url}';
    else  if (o==2) url='{$do_order_items_delay_url}';
        if(confirm('هل تريد تحويل الطلب ؟!')){
            get_data(url, {purchase_order_id: p_id,delay_id:d_id  }, function(ret){
                if(ret==1){
                    success_msg('رسالة','تم تحويل الطلب بنجاح ..');
                    $('a#do_order_'+d_id).hide();
                }else{
                    danger_msg('تحذير..',data);
                }
            }, 'html');
        }
    }

</script>
SCRIPT;
sec_scripts($scripts);
?>
