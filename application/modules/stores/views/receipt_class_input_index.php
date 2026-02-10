<?php
/**
 * Created by PhpStorm.
 * User: tekrayem
 * Date: 27/11/14
 * Time: 10:33 ص
 */

$MODULE_NAME= 'stores';
$TB_NAME= 'receipt_class_input';
$TB_NAME2= 'receipt_class_input_detail';

$get_url =base_url("$MODULE_NAME/$TB_NAME/get_id");
$create_url =base_url("$MODULE_NAME/$TB_NAME/create");
$edit_url =base_url("$MODULE_NAME/$TB_NAME/edit");
$delete_url =base_url("$MODULE_NAME/$TB_NAME/delete");
$adopt_url=base_url("$MODULE_NAME/$TB_NAME/adopt");
$record_url=base_url("$MODULE_NAME/$TB_NAME/record");
$return_url=base_url("$MODULE_NAME/$TB_NAME/returnp");
//$get_details_url =base_url("$MODULE_NAME/$TB_NAME2/get_page");
$customer_url =base_url('payment/customers/public_index');
$delete_details_url=base_url("$MODULE_NAME/$TB_NAME2/delete");
$get_page_url=base_url("$MODULE_NAME/$TB_NAME/get_page");
$select_items_url=base_url("$MODULE_NAME/classes/public_index");
echo AntiForgeryToken();
?>
<div class="row">
    <div class="toolbar">

        <div class="caption"> استلام أصناف تحت التوريد  </div>

        <ul><?php
            if(HaveAccess($create_url)) echo "<li><a  href='{$create_url} '><i class='glyphicon glyphicon-plus'></i>جديد </a> </li>";
       /*     if(HaveAccess($get_url) || HaveAccess($edit_url)) echo "<li><a
  onclick='javascript:get_val(get_id());'
  href='javascript:;'><i class='glyphicon glyphicon-edit'></i>تحرير</a> </li>";*/
            if(HaveAccess($delete_url)) echo "<li><a onclick='javascript:{$TB_NAME}_delete();' href='javascript:;'><i class='glyphicon glyphicon-remove'></i>حذف</a> </li>";
             ?>
            <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>

        </ul>

    </div>

    <div class="form-body">
        <div class="form-body">
            <form class="form-vertical"  id="<?=$TB_NAME?>_form" >
                <div class="modal-body inline_form">
                    <div class="form-group col-sm-2">
                        <label class="control-label"> حالة المحضر</label>
                        <div>
                            <select name="is_recorded" id="txt_is_recorded" class="form-control" />
                            <option></option>
                            <option value="3">غير محول</option>
                            <option value="1">محول</option>
                            <option value="2">معتمد</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group col-sm-2">
                        <label class="control-label"> محول لسند مخزني؟</label>
                        <div>
                            <select name="is_convert" id="txt_is_convert" class="form-control" />
                            <option></option>

                            <option value="1">نعم</option>
                            <option value="3">لا</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group col-sm-2">
                        <label class="control-label">حالة الإرجاع  </label>
                        <div>
                            <select name="is_return" id="txt_is_return" class="form-control" />
                            <option></option>

                            <option value="1">نعم</option>
                            <option value="3">لا</option>
                            </select>

                        </div>
                    </div>

                    <div class="form-group col-sm-2">
                        <label class="control-label">طلبات بها بيانات مرفوضة  </label>
                        <div>
                            <select name="has_refused_amounts" id="txt_has_refused_amounts" class="form-control" />
                            <option></option>

                                <option value="1">نعم</option>
                            <option value="2">لا</option>
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
            <?=modules::run($get_page_url, $page, $is_recorded, $is_convert, $is_return, $has_refused_amounts);?>

        </div>
    </div>

</div>


<?php

$scripts = <<<SCRIPT
<script type="text/javascript">
    var count = 0;
     var count1 = 0;
    $(document).ready(function() {
        $('#{$TB_NAME}_tb').dataTable({
            "lengthMenu": [ [-1,10,20,30,40,50,100 ], [ "الكل",10,20,30,40,50,100] ],
            "sPaginationType": "full_numbers"
        });
    });

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
                    container.html(data);
                });
            }
        }else
            alert('يجب تحديد السجلات المراد حذفها');
    }
    function get_val(id){
    alert(id);
//get_to_link('( {$get_url}).'/'.id.'/'.( isset($action)?$action.'/':''));
   //{$TB_NAME}_get(get_id());
    }
/*
function {$TB_NAME}_get(id){
    get_data('{$get_url}',{id:id},function(data){
        $.each(data, function(i,item){
            $('#txt_receipt_class_input_id').val(item.RECEIPT_CLASS_INPUT_ID);
            $('#txt_receipt_class_input_date').val( item.RECEIPT_CLASS_INPUT_DATE);
            $('#txt_order_id').val( item.ORDER_ID);
            $('#dp_store_id').val( item.STORE_ID);
            $('#h_txt_customer_resource_id').val( item.CUSTOMER_RESOURCE_ID);
            $('#txt_send_id').val( item.SEND_ID);
            $('#txt_send_case').val( item.SEND_CASE);
            $('#txt_send_hints').val( item.SEND_HINTS);
            $('#txt_record_id').val( item.RECORD_ID);
            $('#txt_record_case').val( item.RECORD_CASE);
            $('#txt_record_declaration').val( item.RECORD_DECLARATION);
            $('#txt_return_case').val( item.RETURN_CASE);
            $('#txt_return_hint').val( item.RETURN_HINT);

            if  ( $('#txt_send_case').val()==2){
                $('#adopt').text('إلغاء اعتماد');
                $('#txt_record_id').prop('readonly',true);
                $('#txt_record_declaration').prop('readonly',true);
                $('#txt_return_hint').prop('readonly',true);

            }else   if  ( $('#txt_send_case').val()==1) {
                $('#adopt').text('اعتماد');
                $('#txt_record_id').prop('readonly',true);
                $('#txt_record_declaration').prop('readonly',true);
                $('#txt_return_hint').prop('readonly',true);

            }


            if  ( $('#txt_record_case').val()==2){
                $('#recordd').text('معتمد');
                $('#txt_record_id').prop('readonly',true);
                $('#txt_record_declaration').prop('readonly',true);
                $('#txt_return_hint').prop('readonly',true);
                $('#returnn').show();
                $('#recordd').show();
                $('#adopt').show();
                $('#sub').hide();
            }else if  ( $('#txt_record_case').val()==1){
                $('#recordd').text('اعتماد سند فحص و استلام');
                $('#txt_record_id').prop('readonly',true);
                $('#txt_record_declaration').prop('readonly',true);
                $('#txt_return_hint').prop('readonly',true);
                $('#returnn').hide();
                $('#recordd').show();
                $('#adopt').show();
                $('#sub').hide();
            }else {
                $('#recordd').text('حفظ سند فحص و استلام');
                $('#txt_record_id').prop('readonly',false);
                $('#txt_record_declaration').prop('readonly',false);
                $('#txt_return_hint').prop('readonly',true);
                $('#returnn').hide();
                $('#recordd').show();
                $('#adopt').show();
                $('#sub').hide();
            }
            if ($('#txt_return_case').val()==1){
                $('#txt_return_hint').prop('readonly',true);
                $('#returnn').hide();
                $('#recordd').hide();
                $('#adopt').hide();
                $('#sub').hide();
            }



            $('#{$TB_NAME}_from').attr('action','{$edit_url}');
            resetValidation($('#{$TB_NAME}_from'));




        });
    });
}
*/

   function clear_form(){

        clearForm($('#{$TB_NAME}_form'));

    }
      function search(){
$('#container').text('');
        var values= {page:1, is_recorded:$('#txt_is_recorded').val(), is_convert:$('#txt_is_convert').val(), is_return:$('#txt_is_return').val(), has_refused_amounts:$('#txt_has_refused_amounts').val()};
        get_data('{$get_page_url}',values ,function(data){
            $('#container').html(data);
        },'html');

    }

    function LoadingData(){

        var values= {page:1, is_recorded:$('#txt_is_recorded').val(), is_convert:$('#txt_is_convert').val(), is_return:$('#txt_is_return').val(), has_refused_amounts:$('#txt_has_refused_amounts').val()};
       ajax_pager_data('#receipt_class_input_tb > tbody',values);
    }
</script>

SCRIPT;

sec_scripts($scripts);

?>
