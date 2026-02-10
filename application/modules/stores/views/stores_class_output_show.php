<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 29/12/14
 * Time: 10:02 ص
 */

$MODULE_NAME= 'stores';
$TB_NAME= 'stores_class_output';

$back_url=base_url("$MODULE_NAME/$TB_NAME/index"); //$action
$select_items_url=base_url("$MODULE_NAME/classes/public_index");

$isCreate =isset($output_data) && count($output_data)  > 0 ?false:true;
$rs=$isCreate ?array() : $output_data[0];

$source= isset($source)?$source: 0;
$request_data= isset($request_data)?$request_data: array();

$output_url= base_url("$MODULE_NAME/$TB_NAME/get");
$post_url= base_url("$MODULE_NAME/$TB_NAME/".($action == 'index'?'create':$action));
$adopt_url= base_url("$MODULE_NAME/$TB_NAME/adopt");
$adopt_10_url= base_url("$MODULE_NAME/$TB_NAME/adopt_10");
$cancel_url= base_url("$MODULE_NAME/$TB_NAME/cancel");
$barcodes_url= base_url("$MODULE_NAME/$TB_NAME/set_barcodes");
$edit_account_url= base_url("$MODULE_NAME/$TB_NAME/edit_account");

$request_url= base_url("$MODULE_NAME/stores_payment_request/get");
$transport_url= base_url("$MODULE_NAME/stores_class_transport/get");

$select_accounts_url =base_url('financial/accounts/public_select_accounts');
$customer_url =base_url('payment/customers/public_index');
$select_rooms_url =base_url('pledges/rooms_structure_tree/public_select_rooms');
$room_cus_url= base_url('pledges/rooms_structure_tree/public_get_room_by_id');

$report_url = base_url("JsperReport/showreport?sys=financial/purchases");
$report_sn= report_sn();

$request_transport_no=0;
if(count($request_data) and $source==1)
    $request_transport_no= $request_data['REQUEST_NO'];
elseif(count($request_data) and $source==2)
    $request_transport_no= $request_data['CLASS_TRANSPORT_ID'];

$can_cancel= 0;
if( HaveAccess($cancel_url) and !$isCreate and $rs['ADOPT']!=0 )
    $can_cancel= 1;

echo AntiForgeryToken();
?>

<style>
    .td-red{background-color: #f3a8a8}

    .select2-results .select2-disabled,  .select2-results__option[aria-disabled=true] {
        display: none;
    }
</style>

<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>

        <ul>
            <?php if(!$isCreate and $rs['ADOPT']>=2 and intval($rs['FINANCIAL_CHAIN'])>0 ): ?><li><a target="_blank" href="<?=base_url("financial/financial_chains/get/{$rs['FINANCIAL_CHAIN']}/index?type=4")?>"><i class="glyphicon glyphicon-new-window"></i> القيد: <?=$rs['FINANCIAL_CHAIN']?> </a> </li><?php endif; ?>
            <?php  if( HaveAccess($back_url)):  ?><li><a  href="<?= $back_url ?>"><i class="icon icon-reply"></i> </a> </li><?php  endif; ?>
            <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>
        </ul>

    </div>
</div>
<div class="form-body">

    <div id="msg_container"></div>
    <div id="container">
        <form class="form-vertical" id="<?=$TB_NAME?>_form" method="post" action="<?=$post_url?>" role="form" novalidate="novalidate">
            <div class="modal-body inline_form">

                <div class="form-group col-sm-1">
                    <label class="control-label">رقم سند الصرف</label>
                    <div>
                        <input type="text" readonly id="txt_class_output_id" class="form-control ">
                        <?php if (( isset($can_edit)?$can_edit:false)) : ?>
                            <input type="hidden" name="class_output_id" id="h_class_output_id">
                        <?php endif; ?>
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label id="l_source" class="control-label"> <?=($source==1)? 'رقم الطلب' : 'رقم المناقلة' ?> <a target="_blank" style="cursor: pointer" href="javascript:;" ><i class="glyphicon glyphicon-new-window"></i></a> </label>
                    <div>
                        <input type="text" readonly id="txt_request_no" class="form-control">
                        <input type="hidden" name="request_no" id="h_request_no">
                        <input type="hidden" name="source" value="<?=$source?>" id="h_source">
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">المخزن المصروف منه</label>
                    <div>
                        <select name="from_store_id" id="dp_from_store_id" class="form-control" data-val="true"  data-val-required="حقل مطلوب"  >
                            <option></option>
                            <?php foreach($stores as $row) :?>
                                <option value="<?=$row['STORE_ID']?>"><?=$row['STORE_NO'].': '.$row['STORE_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="field-validation-valid" data-valmsg-for="from_store_id" data-valmsg-replace="true"></span>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">نوع سند الصرف</label>
                    <div>
                        <select name="class_output_type" id="dp_class_output_type" class="form-control" data-val="true"  data-val-required="حقل مطلوب"  >
                            <option></option>
                            <?php foreach($output_type as $row) :?>
                                <option value="<?=$row['CON_NO']?>"><?=$row['CON_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="field-validation-valid" data-valmsg-for="class_output_type" data-valmsg-replace="true"></span>
                    </div>
                </div>

                <div class="form-group col-sm-3">
                    <label class="control-label">الحساب المصروف إليه</label>
                    <div>
                        <input type="text" data-val="false" readonly data-val-required="required" class="form-control" id="txt_class_output_account_id" />
                        <input type="hidden" name="class_output_account_id" id="h_txt_class_output_account_id" />
                        <span class="field-validation-valid" data-valmsg-for="class_output_account_id" data-valmsg-replace="true"></span>
                    </div>
                </div>

                <div class="form-group col-sm-2" id="div_customer_account_type" style="display: none">
                    <label class="control-label">نوع حساب المستفيد</label>
                    <div>
                        <select name="customer_account_type" id="dp_customer_account_type" class="form-control" data-val="true"  data-val-required="حقل مطلوب"  >
                            <option value=""> </option>
                            <?php foreach($customer_account_type as $row) :?>
                                <option disabled value="<?=$row['CON_NO']?>"><?=$row['CON_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="field-validation-valid" data-valmsg-for="customer_account_type" data-valmsg-replace="true"></span>
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label">تاريخ تحديث الحساب </label>
                    <div>
                        <input type="text" readonly data-type="text" id="txt_update_account_date" class="form-control" />
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label"> الفرع  </label>
                    <div>
                        <select disabled name='branch' id='dp_branch' class='form-control'>
                            <option></option>
                            <?php foreach($branches as $row) :?>
                                <option value="<?=$row['NO']?>"><?=$row['NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>


                <div class="form-group col-sm-1" style="display: none" id="branch_from_user">
                    <label class="control-label"> الفرع حسب الادخال  </label>
                    <div>
                        <select name='branch_from_user' id='dp_branch_from_user' class='form-control'>
                            <option></option>
                            <?php foreach($branches as $row) :?>
                                <option value="<?=$row['NO']?>"><?=$row['NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div style="clear:both;"></div>

                <div id="div_customer_account_type1" style="display: none">
                    <div class="form-group col-sm-1">
                        <label class="control-label">رقم الغرفة</label>
                        <div>
                            <input type="text" readonly id="txt_room_id" class="form-control" >
                        </div>
                    </div>

                    <div class="form-group col-sm-2">
                        <label class="control-label">اسم الغرفة</label>
                        <div>
                            <input type="text" readonly id="txt_room_name" class="form-control" >
                        </div>
                    </div>
                </div>

                <div style="clear:both;"></div>

                <input type="hidden" name="room_id" id="h_room_id" class="form-control" >

                <div class="form-group col-sm-9">
                    <label class="control-label">بيان</label>
                    <div>
                        <input type="text" name="note" data-val="false"  data-val-required="حقل مطلوب"  id="txt_note" class="form-control ">
                        <span class="field-validation-valid" data-valmsg-for="note" data-valmsg-replace="true"></span>
                    </div>
                </div>

                <div class="form-group col-sm-9" id="cancel_note" style="display: none">
                    <label class="control-label">بيان الالغاء</label>
                    <div>
                        <input type="text" name="cancel_note" id="txt_cancel_note" class="form-control ">
                    </div>
                </div>

                <div style="clear: both"></div>

                <div class="form-group col-sm-2" id="entry_date" style="display: none">
                    <label class="control-label">تاريخ الادخال</label>
                    <div></div>
                </div>

                <div class="form-group col-sm-2" id="entry_user" style="display: none">
                    <label class="control-label">اسم المدخل</label>
                    <div></div>
                </div>

                <div class="form-group col-sm-2" id="adopt_date_10" style="display: none">
                    <label class="control-label">تاريخ اعتماد المختص</label>
                    <div></div>
                </div>

                <div class="form-group col-sm-2" id="adopt_user_10" style="display: none">
                    <label class="control-label">اسم المعتمد المختص</label>
                    <div></div>
                </div>

                <div class="form-group col-sm-2" id="adopt_date" style="display: none">
                    <label class="control-label">تاريخ اعتماد المخازن</label>
                    <div></div>
                </div>

                <div class="form-group col-sm-2" id="adopt_user" style="display: none">
                    <label class="control-label">اسم معتمد المخازن</label>
                    <div></div>
                </div>

                <div style="clear: both"></div>

                <?php echo modules::run("$MODULE_NAME/$TB_NAME/public_get_details", (count($rs))?$rs['CLASS_OUTPUT_ID']:0, $source, $request_transport_no , (count($rs))?$rs['ADOPT']:0 ); ?>

            </div>

            <div class="modal-footer">
                <?php if (  HaveAccess($post_url) && ($isCreate || ( $rs['ADOPT']==1 and isset($can_edit)?$can_edit:false) )  ) : ?>
                    <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                <?php endif; ?>

                <?php if ( !$isCreate and HaveAccess($edit_account_url) and $rs['ADOPT']==20 ) : ?>
                    <button type="button" id="btn_edit_account" onclick='javascript:edit_account();' class="btn btn-primary">تعديل الحساب</button>
                <?php endif; ?>

                <?php if ( HaveAccess($barcodes_url) && $isCreate  ) : ?>
                    <button type="button" id="btn_barcodes" onclick='javascript:barcodes();' class="btn btn-info"> انشاء اكواد الباركود </button>
                <?php endif; ?>

                <?php if ( !$isCreate and $rs['ADOPT']!=0 ) : ?>
                    <button type="button" onclick="$('#details_tb').tableExport({type:'excel',escape:'false'});" class="btn btn-success">  اكسل </button>
                    <button type="button" id="print_rep" onclick="javascript:print_rep();" class="btn btn-success"> <i class="glyphicon glyphicon-print"></i> طباعة </button>
                <?php endif; ?>

                <?php if ( HaveAccess($adopt_10_url) and !$isCreate and ($rs['ADOPT']==1 and $rs['NEED_TEC_ADOPT']==1 ) ) : ?>
                    <button type="button" id="btn_adopt" onclick='javascript:adopt(10);' class="btn btn-success"> اعتماد الجهة المختصة </button>
                <?php endif; ?>

                <?php if ( HaveAccess($adopt_url) and !$isCreate and (($rs['ADOPT']==1 and $rs['NEED_TEC_ADOPT']==0) or $rs['ADOPT']==10 ) ) : ?>
                    <button type="button" id="btn_adopt" onclick='javascript:adopt(1);' class="btn btn-success">اعتماد المخازن</button>
                <?php endif; ?>

                <?php if ( $can_cancel ) : ?>
                    <button type="button" id="btn_cancel" onclick='javascript:adopt(0);' class="btn btn-danger">الغاء السند</button>
                <?php endif; ?>

                <?php if ($isCreate and 0): ?>
                    <button type="button" onclick="javascript:clear_form();"  class="btn btn-default"> تفريغ الحقول</button>
                <?php endif; ?>
            </div>

        </form>
    </div>
</div>

<?php
$scripts = <<<SCRIPT
<script>

/*
    var count = 0;

    var class_unit_json= {class_unit}; // $
    var select_class_unit= '';

    $.each(class_unit_json, function(i,item){
        select_class_unit += "<option value='"+item.CON_NO+"' >"+item.CON_NAME+"</option>";
    });

    var class_type_json= {class_type}; // $
    var select_class_type= '';

    $.each(class_type_json, function(i,item){
        select_class_type += "<option value='"+item.CON_NO+"' >"+item.CON_NAME+"</option>";
    });

    reBind();

    $('select[name="class_unit[]"]').append(select_class_unit);
    $('select[name="class_unit[]"]').each(function(){
        $(this).val($(this).attr('data-val'));
    });

    $('select[name="class_type[]"]').append(select_class_type);
    $('select[name="class_type[]"]').each(function(){
        $(this).val($(this).attr('data-val'));
    });
*/
    $('#dp_from_store_id , #dp_class_output_type, .codes_ser , #dp_customer_account_type').select2(); // select[name="class_unit[]"] , select[name="class_type[]"]
    $('#dp_from_store_id').select2('readonly',1);
    

    $('#txt_class_output_account_id').click(function(e){
        var _type = $('#dp_class_output_type').select2('val');
        if(_type == 1)
            _showReport('$select_accounts_url/'+$(this).attr('id') );
        else if(_type == 2)
            _showReport('$customer_url/'+$(this).attr('id')+'/1');
        else if(_type == 4)
	        _showReport('$select_rooms_url/'+$(this).attr('id'));
    });

    $('button[data-action="submit"]').click(function(e){
        e.preventDefault();
        if(!chk_codes_ser()) return 0;

        if(confirm('هل تريد حفظ السند ؟!')){
            $(this).attr('disabled','disabled');
            var form = $(this).closest('form');
            ajax_insert_update(form,function(data){
                if(parseInt(data)>1){
                    success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                    get_to_link('{$output_url}/'+parseInt(data)+'/edit');
                }else if(data==1){
                    success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                    get_to_link(window.location.href);
                }else{
                    danger_msg('تحذير..',data);
                }
            },'html');
        }
        setTimeout(function() {
            $('button[data-action="submit"]').removeAttr('disabled');
        }, 3000);
    });


    $('#dp_class_output_type').change(function(){
        $('#txt_class_output_account_id').val('');
        $('#h_txt_class_output_account_id').val('');
        chk_output_type();
        chk_customer_account_type();
    });

    function chk_output_type(){
        var typ= $('#dp_class_output_type').val();
        if(typ ==2)
            $('div#branch_from_user').fadeIn(300);
        else{
            $('div#branch_from_user').fadeOut(300);
            $('#dp_branch_from_user').val('');
        }
    }

    function chk_customer_account_type(){
        if( $('#dp_class_output_type').val()==2 ){
            $('#div_customer_account_type').show();
        }else{
            $('#div_customer_account_type').hide();
        }
        
        if( $('#dp_class_output_type').val()==4 ){
            $('#div_customer_account_type1').show();
        }
    }

    $('.codes_ser').change(function(){
        $(this).closest('td').find('input[name="class_code_ser[]"]').val( $(this).val() );
    });

    function chk_codes_ser(){
        var ret= true;
        var codes_ser= $('input[name="class_code_ser[]"]');
        $.each(codes_ser, function(i,item){
            var td= $(item).closest('td');
            var tr= $(item).closest('tr');
            if(td.find('#h_class_acount_type').val()==1){
                var dp_codes= td.find('.codes_ser');
                var amount= tr.find('input[name="amount[]"]').val();
                if($(item).val()!=''){
                    var cnt_codes= $(item).val().split(",").length;
                    if(td.find('#h_class_unit').val()==12 || td.find('#h_class_unit').val()==29 || td.find('#h_class_unit').val()==9 || td.find('#h_class_unit').val()==10 ){
                        if( cnt_codes != amount ){
                            alert('اختر باركود العهد بنفس عدد الكمية');
                            td.addClass   ("td-red", 500, "easeInBack");
                            td.removeClass("td-red", 1000, "easeInBack");
                            ret= false;
                        }
                    }else{
                        if(cnt_codes!=1){
                            alert('اختر باركود واحد فقط');
                            td.addClass   ("td-red", 500, "easeInBack");
                            td.removeClass("td-red", 1000, "easeInBack");
                            ret= false;
                        }
                    }
                }else if(amount!=0){
                    
                    if( tr.find('input[name="class_type[]"]').val() != 3 ){  // استثناء الاصناف التالفة من الباركود 07/06/2020
                        alert('اختر باركود العهد');
                        td.addClass   ("td-red", 500, "easeInBack");
                        td.removeClass("td-red", 1000, "easeInBack");
                        ret= false;
                    }
                    
                }
            }
        });
        return ret;
    }
    
    function get_room(){
        return false; // تم الالغاء 202210
                
        if ( ($('#dp_customer_account_type').val()==10) && ($('#dp_class_output_type').val()==2) ){
            
            if ( $('#h_txt_class_output_account_id').val()=='' ){
                danger_msg('تحذير','يجب اختيار موظف من المستفيدين -  الحساب المصروف إليه ');
                return 0;
            }

            get_data('{$room_cus_url}',{'customer_id':$('#h_txt_class_output_account_id').val()} ,function(data){
                if (data.length == 1){
                    var item= data[0];
                    $('#txt_room_id').val(item.ROOM_ID);
                    $('#h_room_id').val(item.ROOM_ID);
                    $('#txt_room_name').val(item.ROOM_NAME);
                } else {
                    danger_msg('تحذير','الموظف غير مسكن على هيكلية الغرف');
                }
            });
            $('#div_customer_account_type1').show();
            
        }else{
            $('#txt_room_id').val('');
            $('#h_room_id').val('');
            $('#txt_room_name').val('');
            $('#div_customer_account_type1').hide();
        }
    }
    
    $('#dp_class_output_type').change(function(){
        $('#dp_customer_account_type').select2('val','');
        {
            $('#div_customer_account_type1').hide();
            $('#txt_room_id').val('');
            $('#h_room_id').val('');
            $('#txt_room_name').val('');
        }
        
    });
    
    $('#dp_customer_account_type').change(function(){
        get_room();
    });
    
    function setDefaultCustomerAccount(){
        $('#dp_customer_account_type').select2('val','');
        get_room();
    }


/*
    function addRow(){
        count = count+1;
        var html ='<tr> <td><input type="hidden" name="ser[]" value="0" /><input name="class_name[]" readonly class="form-control" id="txt_class_id'+count+'" /><input type="hidden" name="class_id[]" id="h_txt_class_id'+count+'" /></td> <td><input name="amount[]" class="form-control" id="txt_amount'+count+'" /></td> <td><select name="class_unit[]" class="form-control" id="unit_txt_class_id'+count+'" /></select></td> <td><select name="class_type[]" class="form-control" id="txt_class_type'+count+'" /></select></td></tr>';
        $('#details_tb tbody').append(html);

        reBind(1);
    }

    function reBind(s){
        if(s==undefined)
            s=0;
        $('input[name="class_name[]"]').click("focus",function(e){
            _showReport('$select_items_url/'+$(this).attr('id'));
        });
        if(s){
            $('select#unit_txt_class_id'+count).append('<option></option>'+select_class_unit).select2();
            $('select#txt_class_type'+count).append('<option></option>'+select_class_type).select2();
        }
    }
*/

SCRIPT;

if($isCreate and $source==1){
    $scripts = <<<SCRIPT
    {$scripts}
    
    $( document ).ready(function() {
        get_room();
    });

    if(set_barcode== 0)
        $('#btn_barcodes').attr('disabled','disabled');

    $('#txt_request_no').val('{$request_data['REQUEST_NO']}');
    $('#h_request_no').val('{$request_data['REQUEST_NO']}');
    $('#dp_from_store_id').select2('val', '{$request_data['REQUEST_STORE_FROM']}');
    $('#dp_class_output_type').select2('val','{$request_data['REQUEST_SIDE']}');
    $('#txt_class_output_account_id').val('{$request_data['REQUEST_SIDE_ACCOUNT_NAME']}');
    $('#h_txt_class_output_account_id').val('{$request_data['REQUEST_SIDE_ACCOUNT']}');
    $('#dp_customer_account_type option[value="{$request_data['CUSTOMER_ACCOUNT_TYPE']}"]').prop('disabled',0);
    $('#dp_customer_account_type').select2('val', '{$request_data['CUSTOMER_ACCOUNT_TYPE']}');
    $('#txt_room_id').val('{$request_data['ROOM_ID']}');
    $('#h_room_id').val('{$request_data['ROOM_ID']}');
    $('#txt_room_name').val('{$request_data['ROOM_ID_NAME']}');
    chk_customer_account_type();
    $('#txt_note').val('{$request_data['NOTES']}');
    chk_output_type();

    function clear_form(){
        clearForm($('#{$TB_NAME}_form'));
    }

    function barcodes(){
        if(confirm('هل تريد بالتأكيد انشاء اكواد العهد ؟!')){
            get_data('{$barcodes_url}', {request_no: '{$request_data['REQUEST_NO']}' }, function(ret){
                if(ret==1){
                    success_msg('رسالة','تمت العملية بنجاح ..');
                    $('button').attr('disabled','disabled');
                    get_to_link(window.location.href);
                }else{
                    danger_msg('تحذير..',data);
                }
            }, 'html');
        }
    }

    </script>
SCRIPT;
}else if($isCreate and $source==2){
    $scripts = <<<SCRIPT
    {$scripts}
    
    $( document ).ready(function() {
        get_room();
    });

    $('#txt_request_no').val('{$request_data['CLASS_TRANSPORT_ID']}');
    $('#h_request_no').val('{$request_data['CLASS_TRANSPORT_ID']}');
    $('#dp_from_store_id').select2('val', '{$request_data['TO_STORE_ID']}');
    $('#dp_class_output_type').select2('val','{$request_data['REQUEST_SIDE']}');
    $('#txt_class_output_account_id').val('{$request_data['CLASS_TRANSPORT_ACCOUNT_NAME']}');
    $('#h_txt_class_output_account_id').val('{$request_data['CLASS_TRANSPORT_ACCOUNT_ID']}');
    $('#dp_customer_account_type option[value="{$request_data['CUSTOMER_ACCOUNT_TYPE']}"]').prop('disabled',0);
    $('#dp_customer_account_type').select2('val', '{$request_data['CUSTOMER_ACCOUNT_TYPE']}');
    chk_customer_account_type();
    $('#txt_note').val('{$request_data['NOTE']}');
    chk_output_type();

    function clear_form(){
        clearForm($('#{$TB_NAME}_form'));
    }

    </script>
SCRIPT;
}else{
    $scripts = <<<SCRIPT
    {$scripts}
    $('#txt_class_output_id').val('{$rs['CLASS_OUTPUT_ID']}');
    $('#h_class_output_id').val('{$rs['CLASS_OUTPUT_ID']}');
    $('#txt_request_no').val('{$rs['REQUEST_NO']}');
    $('#h_request_no').val('{$rs['REQUEST_NO']}');
    $('#h_source').val('{$rs['SOURCE']}');
    $('#dp_from_store_id').select2('val', '{$rs['FROM_STORE_ID']}');
    $('#dp_class_output_type').select2('val', '{$rs['CLASS_OUTPUT_TYPE']}');
    $('#txt_class_output_account_id').val('{$rs['CLASS_OUTPUT_ACCOUNT_ID_NAME']}');
    $('#h_txt_class_output_account_id').val('{$rs['CLASS_OUTPUT_ACCOUNT_ID']}');
    $('#dp_customer_account_type option[value="{$rs['CUSTOMER_ACCOUNT_TYPE']}"]').prop('disabled',0);
    $('#dp_customer_account_type').select2('val', '{$rs['CUSTOMER_ACCOUNT_TYPE']}');
    $('#txt_update_account_date').val('{$rs['UPDATE_ACCOUNT_DATE']}');
    chk_customer_account_type();
    $('#txt_note').val('{$rs['NOTE']}');
    $('#dp_branch').val('{$rs['BRANCH']}');
    $('#dp_branch_from_user').val('{$rs['BRANCH_FROM_USER']}');
    $('div#entry_date div').text('{$rs['ENTRY_DATE']}');
    $('div#entry_user div').text('{$rs['ENTRY_USER_NAME']}');
    $('div#adopt_date div').text('{$rs['ADOPT_DATE']}');
    $('div#adopt_user div').text('{$rs['ADOPT_USER_NAME']}');
    $('div#adopt_date_10 div').text('{$rs['ADOPT_DATE_10']}');
    $('div#adopt_user_10 div').text('{$rs['ADOPT_USER_10_NAME']}');
    $('div#entry_date, div#entry_user, div#adopt_date, div#adopt_user, div#adopt_date_10, div#adopt_user_10').show();
    
    $('#txt_room_id').val('{$rs['ROOM_ID']}');
    $('#h_room_id').val('{$rs['ROOM_ID']}');
    $('#txt_room_name').val('{$rs['ROOM_ID_NAME']}');
    //$('#div_customer_account_type1').show(); // تم الالغاء 202210

    chk_output_type();

    if( $('a.class_codes_url').length!=0 ){
        $.each( $('a.class_codes_url') , function() {
            $(this).prop('href', $(this).prop('href').replace("url_store", "{$rs['FROM_STORE_ID']}") );
        });
    }

    if({$can_cancel}== 1)
        $('div#cancel_note').show();

    if(('{$rs['CANCEL_NOTE']}').length > 3){
        $('#txt_cancel_note').val('{$rs['CANCEL_NOTE']}');
        $('div#cancel_note').show();
    }

    if('{$rs['SOURCE']}' == '1'){
        $('#l_source').html(function () {
            return $(this).html().replace("المناقلة", "الطلب");
        });
        $('label#l_source a').attr('href','{$request_url}/{$rs['REQUEST_NO']}');
    }else
        $('label#l_source a').attr('href','{$transport_url}/{$rs['REQUEST_NO']}');


    function adopt(no){
        if(dont_adopt == 1 && no!=0){
            danger_msg('تحذير..','لم يتم الاعتماد لان الكمية المطلوبة اكبر من المتاحة');
            return false;
        }
        if(no==0 && $('#txt_cancel_note').val() =='' ){
            danger_msg('تحذير..','ادخل بيان الالغاء');
            return false;
        }
        var msg= '';
        if(no==0)
             msg= 'هل تريد الغاء السند ؟!';
         else
             msg= 'هل تريد اعتماد السند ؟!';
        if(confirm(msg)){
            $('button').attr('disabled','disabled');
            var adopt_url, adopt_btn;
            if(no==1){
                adopt_url= '{$adopt_url}';
                adopt_btn= $('#btn_adopt');
            }else if(no==0){
                adopt_url= '{$cancel_url}';
                adopt_btn= $('#btn_cancel');
            }else if(no==10){
                adopt_url= '{$adopt_10_url}';
            }
            var values= {class_output_id: "{$rs['CLASS_OUTPUT_ID']}" , cancel_note: $('#txt_cancel_note').val() };
            get_data(adopt_url, values, function(ret){
                if(ret==1){
                    if(no==0)
                        success_msg('رسالة','تم الغاء السند بنجاح ..');
                    else
                        success_msg('رسالة','تم اعتماد السند بنجاح ..');

                    //adopt_btn.attr('disabled','disabled');
                    $('button').attr('disabled','disabled');
                }else{
                    danger_msg('تحذير..',data);
                }
            }, 'html');
        }
    }
    
    function edit_account(){
        var msg= 'سيتم تعديل رقم الحساب وتحديث القيد، تأكيد؟!';
                
        if(confirm(msg)){
            var values= {class_output_id: "{$rs['CLASS_OUTPUT_ID']}" , class_output_type: $('#dp_class_output_type').val(), class_output_account_id: $('#h_txt_class_output_account_id').val(), customer_account_type: $('#dp_customer_account_type').val() };
            get_data('{$edit_account_url}', values, function(ret){
                if(ret==1){
                    success_msg('رسالة','تم العملية بنجاح ..');
                    $('button').attr('disabled','disabled');
                }else{
                    danger_msg('تحذير..',data);
                }
            }, 'html');
        }
    }

    $('#print_rep').click(function(){
        _showReport('{$report_url}&report_type=pdf&report=Stores_Class_Output&p_id={$rs['CLASS_OUTPUT_ID']}&sn={$report_sn}');
    });


    </script>
SCRIPT;
}

sec_scripts($scripts);

?>
