<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 19/01/15
 * Time: 09:09 ص
 */

$MODULE_NAME= 'stores';
$TB_NAME= 'stores_class_input';

$get_url =base_url("$MODULE_NAME/$TB_NAME/get_chain_id");
$get_page_url = base_url("$MODULE_NAME/$TB_NAME/get_page_mk");
//$transfer_url =base_url("$MODULE_NAME/$TB_NAME/transferInvoice");
$transfer_url =base_url("$MODULE_NAME/invoice_class_input/create");
$invoice_edit=base_url('stores/invoice_class_input/get_id');
$select_accounts_url =base_url('financial/accounts/public_select_accounts');
$customer_url =base_url('payment/customers/public_index');
$project_accounts_url = base_url('projects/projects/public_select_project_accounts');
echo AntiForgeryToken();
?>

<div class="row">
    <div class="toolbar">
        <div class="caption"><?= $title ?></div>
        <ul><?php
            //  if(HaveAccess($transfer_url))
            if(HaveAccess($transfer_url) && $type==0) echo "<li><a onclick='javascript:{$TB_NAME}_transfer(this);' href='javascript:;'><i class='glyphicon glyphicon-download'></i>تحويل لفاتورة شراء</a> </li>";
         //   if(true) echo "<li><a onclick='javascript:{$TB_NAME}_transfer_chain();' href='javascript:;'><i class='glyphicon glyphicon-download'></i>تحويل لقيد </a> </li>";

            ?>
        </ul>
    </div>

    <div class="form-body">
        <div class="modal-body inline_form">
        </div>
        <form class="form-vertical"  id="<?=$TB_NAME?>_form" >
            <div class="modal-body inline_form">
                <div class="form-group col-sm-2">
                    <label class="control-label"> مسلسل الإدخال </label>
                    <div>
                        <input type="text" data-val="true"   name="input_seq_t" id="txt_input_seq_t" class="form-control" >

                    </div>
                </div>
                <div class="form-group col-sm-2">
                    <label class="control-label"> رقم الإرسالية</label>
                    <div>
                        <input type="text" data-val="true"   name="send_id2" id="txt_send_id2" class="form-control" >
                        <input type="hidden" data-val="true"   name="class_input_id" id="txt_class_input_id" class="form-control" >

                    </div>
                </div>
                <div class="form-group col-sm-2">
                    <label class=" control-label">نوع المستفيد</label>
                    <div >
                        <select name="account_type"  id="dp_account_type" class="form-control">
                            <option value=""  >...</option>
                                <?php foreach($customer_type as $row) :?>
                                    <option  value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                <?php endforeach; ?>
                        </select>

                    </div>
                </div>
                <div class="form-group col-sm-3">
                    <label class="control-label">المورد</label>
                    <div >
                        <input name="customer_name" data-val="true"     class="form-control" readonly id="txt_customer_name" value=""   >
                        <input type="hidden" name="customer_resource_id" value="" id="h_txt_customer_name">

                    </div>
                </div>
            </div>
        </form>

        <div class="modal-footer">
            <button type="button" onclick="javascript:search();" class="btn btn-success"> إستعلام</button>
            <button class="btn btn-warning dropdown-toggle" onclick="$('#stores_class_input_tb').tableExport({type:'excel',escape:'false'});" data-toggle="dropdown"><i class="fa fa-bars"></i> تصدير</button>

            <button type="button" onclick="javascript:clear_form();"  class="btn btn-default"> تفريغ الحقول</button>
        </div>
        <div id="msg_container"></div>

        <div id="container">
            <?=modules::run($get_page_url,$page,$class_input_id,$input_seq_t,$type,$send_id2,$account_type,$customer_resource_id);?>
        </div>

    </div>

</div>

<?php
$scripts = <<<SCRIPT
<script>

  $('#txt_customer_name').bind("focus",function(e){

          selectAccount(this);

        });

        $('#txt_customer_name').click(function(e){

             selectAccount(this);

        });

    $('.pagination li').click(function(e){
        e.preventDefault();
    });
    function selectAccount(obj){
         var _type =$('#dp_account_type').val();

            if(_type == 1)
                _showReport('$select_accounts_url/'+$(obj).attr('id') );
            if(_type == 2)
                _showReport('$customer_url/'+$(obj).attr('id')+'/1');
             if(_type == 3)
                _showReport('$project_accounts_url/'+$(obj).attr('id')+'/2' );

    }

    function show_row_details(id){

        get_to_link('{$get_url}/'+id+'/edit/1/{$type}');

    }

    function search(){
        get_data('{$get_page_url}',{page:1, type:{$type}, class_input_id:$('#txt_class_input_id').val(),input_seq_t:$('#txt_input_seq_t').val(), send_id2:$('#txt_send_id2').val(),account_type:$('#dp_account_type').val(), customer_resource_id:$('#h_txt_customer_name').val()},function(data){
            $('#container').html(data);
        },'html');
    }

    function LoadingData(){
        ajax_pager_data('#stores_class_input_tb > tbody',{type:{$type}, class_input_id:$('#txt_class_input_id').val(),input_seq_t:$('#txt_input_seq_t').val(), send_id2:$('#txt_send_id2').val(),account_type:$('#dp_account_type').val(), customer_resource_id:$('#h_txt_customer_name').val()});
    }


function {$TB_NAME}_transfer(obj){
        var url = '{$transfer_url}';
        var tbl = '#{$TB_NAME}_tb';

        var container = $('#' + $(tbl).attr('data-container'));
        var val = [];
        var x;
        $(tbl + ' .checkboxes:checked').each(function (i) {
            val[i] = $(this).val();
        });
x=val[0];
for (v=1;v<val.length;v++){
x=x+'-'+ val[v];
}
alert(x);
        if(val.length > 0){
			 if (isDoubleClicked($(obj))) return;
            if(confirm('هل تريد بالتأكيد تحويل '+val.length+' سجلات وتحويل تفاصيلها ؟!!')){
	

   get_to_link('{$transfer_url}/'+x);
            /*  get_data('{$transfer_url}',{id:x },function(data){
                    if(data )
                     console.log(data);
                       setTimeout(function(){

                       get_to_link('{$invoice_edit}/'+data+'/edit/1');

                    }, 1000);

                },'html');*/
            }
        }else
            alert('يجب تحديد السجلات المراد تحويلها');
    }

    function clear_form(){
        clearForm($('#{$TB_NAME}_form'));

    }

</script>
SCRIPT;
sec_scripts($scripts);
?>
