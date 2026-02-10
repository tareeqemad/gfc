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

        <div class="caption"> أرشيف محاضر الفحص والاستلام </div>

        <ul>
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
			    <button type="button" onclick="$('#receipt_class_input_tb').tableExport({type:'excel',escape:'false'});" class="btn btn-success"><i class="fas fa-file-excel"></i>إكسل</button>

                <button type="button" onclick="javascript:clear_form();"  class="btn btn-default"> تفريغ الحقول</button>
            </div>
        <div id="msg_container"></div>

        <div id="container">
            <?=modules::run($get_page_url, $page, $is_recorded, $is_convert, $is_return, $has_refused_amounts,$case,$type);?>

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
            "lengthMenu": [ [10,20,30,40,50,100, -1], [10,20,30,40,50,100, "الكل"] ],
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



   function clear_form(){

        clearForm($('#{$TB_NAME}_form'));

    }
      function search(){
$('#container').text('');
        var values= {page:1, is_recorded:$('#txt_is_recorded').val(), is_convert:$('#txt_is_convert').val(), is_return:$('#txt_is_return').val(), has_refused_amounts:$('#txt_has_refused_amounts').val(),case:1,type:4};
        get_data('{$get_page_url}',values ,function(data){
            $('#container').html(data);
        },'html');

    }

    function LoadingData(){

        var values= {page:1, is_recorded:$('#txt_is_recorded').val(), is_convert:$('#txt_is_convert').val(), is_return:$('#txt_is_return').val(), has_refused_amounts:$('#txt_has_refused_amounts').val(),case:1,type:4};
       ajax_pager_data('#receipt_class_input_tb > tbody',values);
    }
</script>

SCRIPT;

sec_scripts($scripts);

?>
