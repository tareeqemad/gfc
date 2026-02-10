<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 17/12/14
 * Time: 11:35 ص
 */

$MODULE_NAME= 'stores';
$TB_NAME= 'stores_class_transport';

if($action=='archive')
    $archive=1;
else
    $archive=0;

$back_url=base_url("$MODULE_NAME/$TB_NAME/index"); //$action
$select_items_url=base_url("$MODULE_NAME/classes/public_index");

$isCreate =isset($transport_data) && count($transport_data)  > 0 ?false:true;
$rs=$isCreate ?array() : $transport_data[0];

$transport_type= (!isset($transport_type) and count($rs)>0 ) ? $rs['TRANSPORT_TYPE'] :$transport_type;
$request_data= isset($request_data)?$request_data: array();

$post_url= base_url("$MODULE_NAME/$TB_NAME/".($action == 'index'?'create':$action));
$back_later_url= '';
if($archive){
    $post_url= base_url("$MODULE_NAME/$TB_NAME/error_404");
    $back_later_url= '/1/1';
}

$adopt_url= base_url("$MODULE_NAME/$TB_NAME/adopt");
$receipt_url= base_url("$MODULE_NAME/$TB_NAME/receipt");
$cancel_url= base_url("$MODULE_NAME/$TB_NAME/cancel");
$transport_url= base_url("$MODULE_NAME/stores_class_transport/get");
$output_url= base_url("$MODULE_NAME/stores_class_output/create");
$request_url= base_url("$MODULE_NAME/stores_payment_request/get");

$barcodes_url= base_url("$MODULE_NAME/$TB_NAME/set_barcodes");

$select_accounts_url =base_url('financial/accounts/public_select_accounts');
$customer_url =base_url('payment/customers/public_index');
$get_class_url =base_url('stores/classes/public_get_id');

$print_url = gh_itdev_rep_url().'/gfc.aspx?data='.get_report_folder().'&' ;
$report_url = base_url("JsperReport/showreport?sys=financial/archives");

$can_cancel= 0;
if ( HaveAccess($cancel_url) and !$isCreate ){
    $can_cancel= 1;
}

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
            <?php if(!$isCreate and $rs['ADOPT']==3): ?><li><a target="_blank" href="<?=base_url("stores/stores_class_output/index/1/-1/2/{$rs['CLASS_TRANSPORT_ID']}")?>"><i class="glyphicon glyphicon-new-window"></i>سندات الصرف </a> </li><?php endif; ?>
            <?php if(!$isCreate and $rs['ADOPT']>=2 and intval($rs['FINANCIAL_CHAIN'])>0 ): ?><li><a target="_blank" href="<?=base_url("financial/financial_chains/get/{$rs['FINANCIAL_CHAIN']}/index?type=4")?>"><i class="glyphicon glyphicon-new-window"></i> القيد: <?=$rs['FINANCIAL_CHAIN']?> </a> </li><?php endif; ?>
            <?php  if( HaveAccess($back_url)):  ?><li><a  href="<?= $back_url.$back_later_url?>"><i class="icon icon-reply"></i> </a> </li><?php  endif; ?>
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
                    <label class="control-label">رقم المناقلة</label>
                    <div>
                        <input type="text" readonly id="txt_class_transport_id" class="form-control">
                        <input type="hidden" name="class_transport_id" id="h_class_transport_id">
                        <input type="hidden" name="transport_type" id="h_transport_type" value="<?=encryption_case($transport_type,1)?>">
                    </div>
                </div>
                <div id="request_no" class="form-group col-sm-1">
                    <label class="control-label"> رقم الطلب <a target="_blank" style="cursor: pointer" href="javascript:;" ><i class="glyphicon glyphicon-new-window"></i></a> </label>
                    <div>
                        <input type="text" readonly id="txt_request_no" class="form-control">
                        <input type="hidden" name="request_no" id="h_request_no">
                    </div>
                </div>
                <div class="form-group col-sm-2">
                    <label class="control-label">من مخزن</label>
                    <div>
                        <select name="from_store_id" id="dp_from_store_id" class="form-control" data-val="true"  data-val-required="حقل مطلوب"  >
                            <option></option>
                            <?php foreach($stores as $row) :
                                if($transport_type==1 or ( $transport_type==2 and $row['ISDONATION']!=1 )){
                            ?>
                                <option value="<?=$row['STORE_ID']?>"><?=$row['STORE_NO'].': '.$row['STORE_NAME']?></option>
                            <?php } endforeach; ?>
                        </select>
                        <span class="field-validation-valid" data-valmsg-for="from_store_id" data-valmsg-replace="true"></span>
                    </div>
                </div>
                <div class="form-group col-sm-2">
                    <label class="control-label">الى مخزن</label>
                    <div>
                        <select name="to_store_id" id="dp_to_store_id" class="form-control" data-val="true"  data-val-required="حقل مطلوب"  >
                            <option></option>
                            <?php foreach($stores_all as $row) :
                                if($transport_type==1 or ( $transport_type==2 and $row['ISDONATION']!=1 )){
                            ?>
                                <option value="<?=$row['STORE_ID']?>"><?=$row['STORE_NO'].': '.$row['STORE_NAME']?></option>
                            <?php } endforeach; ?>
                        </select>
                        <span class="field-validation-valid" data-valmsg-for="to_store_id" data-valmsg-replace="true"></span>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">رقم الموظف الوسيط</label>
                    <div>
                        <input type="text" name="broker_emp_no" data-val="true"  data-val-required="حقل مطلوب"  id="txt_broker_emp_no" class="form-control">
                        <span class="field-validation-valid" data-valmsg-for="broker_emp_no" data-valmsg-replace="true"></span>
                    </div>
                </div>

                <div class="form-group col-sm-9">
                    <label class="control-label">بيان</label>
                    <div>
                        <input type="text" name="note" data-val="false"  data-val-required="حقل مطلوب"  id="txt_note" class="form-control">
                        <span class="field-validation-valid" data-valmsg-for="note" data-valmsg-replace="true"></span>
                    </div>
                </div>

                <?php if ($can_cancel): ?>
                    <div class="form-group col-sm-9"  >
                        <label class="control-label">بيان الالغاء</label>
                        <div>
                            <input type="text" name="cancel_note" id="txt_cancel_note" class="form-control ">
                        </div>
                    </div>
                <?php endif; ?>

                <div style="clear: both"></div>

                <div class="form-group col-sm-2">
                    <label class="control-label">الجهة المنقول اليها</label>
                    <div id="div_request_side">
                        <select name="request_side" id="dp_request_side" class="form-control" data-val="true"  data-val-required="حقل مطلوب"  >
                            <option></option>
                            <?php foreach($request_side as $row) :?>
                                <option value="<?=$row['CON_NO']?>"><?=$row['CON_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="field-validation-valid" data-valmsg-for="request_side" data-valmsg-replace="true"></span>
                    </div>
                </div>

                <div class="form-group col-sm-3">
                    <label class="control-label">الحساب المنقول اليه </label>
                    <div id="div_class_transport_account_id">
                        <input type="text" data-val="false" readonly data-val-required="required" class="form-control" id="txt_class_transport_account_id" />
                        <input type="hidden" name="class_transport_account_id" id="h_txt_class_transport_account_id" />
                        <span class="field-validation-valid" data-valmsg-for="class_transport_account_id" data-valmsg-replace="true"></span>
                    </div>
                </div>

                <div class="form-group col-sm-2" id="div_customer_account_type" style="display: none">
                    <label class="control-label">نوع حساب المستفيد</label>
                    <div id="div___customer_account_type">
                        <select name="customer_account_type" id="dp_customer_account_type" class="form-control" data-val="true"  data-val-required="حقل مطلوب"  >
                            <option></option>
                            <?php foreach($customer_account_type as $row) :?>
                                <option disabled value="<?=$row['CON_NO']?>"><?=$row['CON_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="field-validation-valid" data-valmsg-for="customer_account_type" data-valmsg-replace="true"></span>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">الغرض من النقل</label>
                    <div id="div_class_transport_type">
                        <select name="class_transport_type" id="dp_class_transport_type" class="form-control" data-val="true"  data-val-required="حقل مطلوب"  >
                            <option></option>
                            <?php foreach($class_transport_type as $row) :?>
                                <option value="<?=$row['CON_NO']?>"><?=$row['CON_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="field-validation-valid" data-valmsg-for="class_transport_type" data-valmsg-replace="true"></span>
                    </div>
                </div>


                <div style="clear: both"></div>

                <div id="adopt_data" style="display: none" >

                    <div class="form-group col-sm-3" >
                        <label class="control-label">اسم المدخل</label>
                        <div><?=$rs['ENTRY_USER_NAME']?></div>
                    </div>

                    <div class="form-group col-sm-2" >
                        <label class="control-label">تاريخ الادخال</label>
                        <div><?=$rs['ENTRY_DATE']?></div>
                    </div>

                    <div class="form-group col-sm-3" >
                        <label class="control-label">اسم المعتمد</label>
                        <div><?=$rs['ADOPT_USER_NAME']?></div>
                    </div>

                    <div class="form-group col-sm-2" >
                        <label class="control-label">تاريخ الاعتماد</label>
                        <div><?=$rs['ADOPT_DATE']?></div>
                    </div>

                    <div class="form-group col-sm-3" >
                        <label class="control-label">اسم المستلم</label>
                        <div><?=$rs['RECEIPT_USER_NAME']?></div>
                    </div>

                    <div class="form-group col-sm-2" >
                        <label class="control-label">تاريخ الاستلام</label>
                        <div><?=$rs['RECEIPT_DATE']?></div>
                    </div>

                    <div class="form-group col-sm-3" >
                        <label class="control-label">اسم الملغي</label>
                        <div><?=$rs['CANCEL_USER_NAME']?></div>
                    </div>

                    <div class="form-group col-sm-2" >
                        <label class="control-label">تاريخ الالغاء</label>
                        <div><?=$rs['CANCEL_DATE']?></div>
                    </div>

                </div>

                <div style="clear: both"></div>

                <input type="hidden" id="h_data_search" />

                <?php echo modules::run("$MODULE_NAME/$TB_NAME/public_get_details", $transport_type, (count($rs))?$rs['CLASS_TRANSPORT_ID']:0, (count($request_data))?$request_data['REQUEST_NO']:0,(count($rs))?$rs['ADOPT']:0 ); ?>

                </div>


            <div class="modal-footer">

                <?php if ( !$isCreate and $rs['ADOPT']!=0 ) : ?>
                    <button type="button" onclick="$('#details_tb').tableExport({type:'excel',escape:'false'});" class="btn btn-success">  اكسل </button>
                    <button type="button" id="print_rep" onclick="javascript:print_rep();" class="btn btn-success"> <i class="glyphicon glyphicon-print"></i> طباعة </button>
                <?php endif; ?>

                <?php if ($archive!=1){ ?>

                <?php if (($isCreate || ( $rs['ADOPT']==1 and isset($can_edit)?$can_edit:false))) : ?>
                    <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                <?php endif; ?>

                <?php if ( !$isCreate and $rs['ADOPT']!=0 ) : ?>
                    <span><?php echo modules::run('attachments/attachment/index',$rs['CLASS_TRANSPORT_ID'],'stores_class_transport'); ?></span>
                <?php endif; ?>

                <?php if ( HaveAccess($barcodes_url) && $isCreate  ) : ?>
                    <button type="button" id="btn_barcodes" onclick='javascript:barcodes();' class="btn btn-info"> انشاء اكواد الباركود </button>
                <?php endif; ?>

                <?php if ( HaveAccess($adopt_url) and !$isCreate and $rs['ADOPT']==1 ) : ?>
                    <button type="button" id="btn_adopt" onclick='javascript:adopt(1);' class="btn btn-success">اعتماد</button>
                <?php endif; ?>

                <?php if ( HaveAccess($receipt_url) and !$isCreate and $rs['ADOPT']==2) : ?>
                    <button type="button" id="btn_receipt" onclick='javascript:adopt(2);' class="btn btn-success">استلام</button>
                <?php endif; ?>

                <?php if ( HaveAccess($output_url) and !$isCreate and $rs['ADOPT']==3) : ?>
                    <button disabled type="button" onclick='javascript:transport_output();' class="btn btn-success">صرف المناقلة</button>
                <?php endif; ?>

                <?php if ( $can_cancel and $rs['ADOPT']!=0 ) : ?>
                    <button type="button" onclick='javascript:adopt(0);' class="btn btn-danger">الغاء السند</button>
                <?php endif; ?>

                <?php if ($isCreate): ?>
                    <button type="button" onclick="javascript:clear_form();"  class="btn btn-default"> تفريغ الحقول</button>
                <?php endif; ?>

                <?php } ?>

            </div>
        </form>
    </div>
</div>


<?php
$scripts = <<<SCRIPT
<script>

    var count = 0;

    var class_unit_json= {$class_unit};
    var select_class_unit= '';

    var class_type_json= {$class_type};
    var select_class_type= '';

    $.each(class_unit_json, function(i,item){
        select_class_unit += "<option value='"+item.CON_NO+"' >"+item.CON_NAME+"</option>";
    });

    $.each(class_type_json, function(i,item){
        select_class_type += "<option value='"+item.CON_NO+"' >"+item.CON_NAME+"</option>";
    });

    reBind();

    $('select[name="class_unit[]"]').append(select_class_unit);
    $('select[name="class_type[]"]').append(select_class_type);

    $('select[name="class_unit[]"]').each(function(){
        $(this).val($(this).attr('data-val'));
    });

    $('select[name="class_type[]"]').each(function(){
        $(this).val($(this).attr('data-val'));
    });

    $('select[name="class_unit[]"], select[name="class_type[]"], #dp_from_store_id, #dp_to_store_id, #dp_request_side, #dp_class_transport_type, .codes_ser , #dp_customer_account_type').select2();
    $('select[name="class_unit[]"]').select2("readonly",true);

    function addRow(){
        count = count+1;
        var html ='<tr> <td><input type="hidden" name="ser[]" value="0" /> <input name="class[]" class="form-control" id="i_txt_class_id'+count+'" /> <input type="hidden" name="class_id[]" id="h_txt_class_id'+count+'" /></td>  <td><input name="class_name[]" readonly class="form-control" id="txt_class_id'+count+'" /></td> <td><input name="amount[]" class="form-control" id="txt_amount'+count+'" /></td> <td></td> <td></td> <td><select name="class_unit[]" class="form-control" id="unit_txt_class_id'+count+'" /></select></td> <td></td> <td></td> <td><select name="class_type[]" class="form-control" id="txt_class_type'+count+'" /></select></td> </tr>';
        $('#details_tb tbody').append(html);

        reBind(1);
    }

    function AddRowWithData(id,name_ar,unit,price,unit_name){
        addRow();
        $('#h_txt_class_id'+(count)).val(id);
        $('#i_txt_class_id'+(count)).val(id);
        $('#txt_class_id'+(count)).val(name_ar);
        $('#unit_txt_class_id'+(count)).select2("val", unit);
        $('#report').modal('hide');
    }

    function reBind(s){
        if(s==undefined)
            s=0;
        $('input[name="class_name[]"]').click("focus",function(e){
            _showReport('$select_items_url/'+$(this).attr('id')+ $('#h_data_search').val() );
        });

        $('input[name="class[]"]').bind("focusout",function(e){
            var id= $(this).val();
            var class_id= $(this).closest('tr').find('input[name="class_id[]"]');
            var name= $(this).closest('tr').find('input[name="class_name[]"]');
            var unit= $(this).closest('tr').find('select[name="class_unit[]"]');
            var amount= $(this).closest('tr').find('input[name="request_amount[]"]');
            if(id==''){
                class_id.val('');
                name.val('');
                unit.select2("val", '');
                return 0;
            }
            get_data('{$get_class_url}',{id:id, type:1},function(data){
                if (data.length == 1){
                    var item= data[0];
                    class_id.val(item.CLASS_ID);
                    name.val(item.CLASS_NAME_AR);
                    unit.select2("val", item.CLASS_UNIT_SUB);
                    amount.focus();
                }else{
                    class_id.val('');
                    name.val('');
                    unit.select2("val", '');
                }
            });
        });

        $('input[name="class[]"]').bind('keyup', '+', function(e) {
            $(this).val('');
            var class_name= $(this).closest('tr').find('input[name="class_name[]"]');
            actuateLink(class_name);
        });

        if(s){
            $('select#unit_txt_class_id'+count).append('<option></option>'+select_class_unit).select2().select2("readonly",true);
            $('select#txt_class_type'+count).append('<option></option>'+select_class_type).select2().select2('val','1');
        }
    }

    $('button[data-action="submit"]').click(function(e){
        e.preventDefault();
        if(!chk_codes_ser()) return 0;

        if(confirm('هل تريد حفظ المناقلة ؟!')){
            $(this).attr('disabled','disabled');
            var form = $(this).closest('form');
            ajax_insert_update(form,function(data){
                if(parseInt(data)>1){
                    success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                    get_to_link('{$transport_url}/'+parseInt(data)+'/edit');
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

    $('#txt_class_transport_account_id').click(function(e){
        var _type = $('#dp_request_side').select2('val');
        if(_type == 1)
            _showReport('$select_accounts_url/'+$(this).attr('id') );
        else if(_type == 2)
            _showReport('$customer_url/'+$(this).attr('id')+'/1');
    });

    $('#dp_request_side').change(function(){
        $('#txt_class_transport_account_id').val('');
        $('#h_txt_class_transport_account_id').val('');
        chk_customer_account_type(0);
    });

    function chk_customer_account_type(request_side){
        if( $('#dp_request_side').val()==2 || parseInt(request_side)==2 ){
            $('#div_customer_account_type').show();
        }else{
            $('#div_customer_account_type').hide();
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

                    if( tr.find('input[name="class_type[]"]').val() != 3 ){  // استثناء الاصناف التالفة من الباركود 03/11/2019
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


SCRIPT;

if($isCreate and $transport_type==1){
    $scripts = <<<SCRIPT
    {$scripts}

    if(set_barcode== 0)
        $('#btn_barcodes').attr('disabled','disabled');

    $('#txt_request_no').val('{$request_data['REQUEST_NO']}');
    $('#h_request_no').val('{$request_data['REQUEST_NO']}');
    $('#dp_from_store_id').select2('val', '{$request_data['REQUEST_STORE_FROM']}');
    $('#dp_to_store_id').select2('val', '1');
    $('#div_request_side').text('{$request_data['REQUEST_SIDE_NAME']}');
    $('#div_class_transport_account_id').text('{$request_data['REQUEST_SIDE_ACCOUNT_NAME']}');
    $('#div_class_transport_type').text('{$request_data['REQUEST_TYPE_NAME']}');
    $('#div___customer_account_type').text('{$request_data['CUSTOMER_ACCOUNT_TYPE_NAME']}');
    chk_customer_account_type('{$request_data['REQUEST_SIDE']}');
    $('#txt_note').val('{$request_data['NOTES']}');

    $('#dp_from_store_id').select2('readonly',1);

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

}elseif($isCreate and $transport_type==2){
    $scripts = <<<SCRIPT
    {$scripts}


    $('div#request_no').hide();

    function clear_form(){
        clearForm($('#{$TB_NAME}_form'));
    }

    </script>
SCRIPT;

}elseif($transport_type==1){
    $scripts = <<<SCRIPT
    {$scripts}

    $('#div_request_side').text('{$rs['REQUEST_SIDE_NAME']}');
    $('#div_class_transport_account_id').text('{$rs['CLASS_TRANSPORT_ACCOUNT_NAME']}');
    $('#div_class_transport_type').text('{$rs['CLASS_TRANSPORT_TYPE_NAME']}');
    $('#div___customer_account_type').text('{$rs['CUSTOMER_ACCOUNT_TYPE_NAME']}');
    chk_customer_account_type('{$rs['REQUEST_SIDE']}');

    $('#dp_from_store_id').select2('readonly',1);

SCRIPT;
}elseif($transport_type==2){
    $scripts = <<<SCRIPT
    {$scripts}

    $('#dp_request_side').select2('val','{$rs['REQUEST_SIDE']}');
    $('#txt_class_transport_account_id').val('{$rs['CLASS_TRANSPORT_ACCOUNT_NAME']}');
    $('#h_txt_class_transport_account_id').val('{$rs['CLASS_TRANSPORT_ACCOUNT_ID']}');
    $('#dp_class_transport_type').select2('val','{$rs['CLASS_TRANSPORT_TYPE']}');
    $('#dp_customer_account_type option[value="{$rs['CUSTOMER_ACCOUNT_TYPE']}"]').prop('disabled',0);
    $('#dp_customer_account_type').select2('val', '{$rs['CUSTOMER_ACCOUNT_TYPE']}');
    chk_customer_account_type(0);

    count = {$rs['COUNT_DET']} -1;
    $('div#request_no').hide();

SCRIPT;
}

if(!$isCreate and ($transport_type==1 or $transport_type==2)){
    $scripts = <<<SCRIPT
    {$scripts}

    $('#txt_class_transport_id').val('{$rs['CLASS_TRANSPORT_ID']}');
    $('#h_class_transport_id').val('{$rs['CLASS_TRANSPORT_ID']}');
    $('#txt_request_no').val('{$rs['REQUEST_NO']}');
    $('#h_request_no').val('{$rs['REQUEST_NO']}');
    $('div#request_no label a').attr('href','{$request_url}/{$rs['REQUEST_NO']}');
    $('#dp_from_store_id').select2('val', '{$rs['FROM_STORE_ID']}');
    $('#dp_to_store_id').select2('val', '{$rs['TO_STORE_ID']}');
    $('#txt_note').val('{$rs['NOTE']}');
    $('#txt_cancel_note').val('{$rs['CANCEL_NOTE']}');
    $('#txt_broker_emp_no').val('{$rs['BROKER_EMP_NO']}');

    $('#adopt_data').show();

    if( $('a.class_codes_url').length!=0 ){
        $.each( $('a.class_codes_url') , function() {
            $(this).prop('href', $(this).prop('href').replace("url_store", "{$rs['FROM_STORE_ID']}") );
        });
    }

    function adopt(no){
        if(dont_adopt == 1 && no!=0){
            danger_msg('تحذير..','لم يتم الاعتماد لان الكمية المطلوبة اكبر من المتاحة');
            return false;
        }
        if( no==0 && $('#txt_cancel_note').val() =='' ){
            danger_msg('تحذير..','ادخل بيان الالغاء');
            return false;
        }
        var adopt_url, adopt_btn, msg;
        if(no==1){
            msg= 'اعتماد';
            adopt_url= '{$adopt_url}';
            adopt_btn= $('#btn_adopt');
        }else if(no==2){
            msg= 'استلام';
            adopt_url= '{$receipt_url}';
            adopt_btn= $('#btn_receipt');
        }else if(no==0){
            msg= 'الغاء';
            adopt_url= '{$cancel_url}';
            adopt_btn= $('#btn_cancel');
        }
        var values= {class_transport_id: "{$rs['CLASS_TRANSPORT_ID']}" , cancel_note: $('#txt_cancel_note').val()};
        if(confirm('هل تريد '+msg+' المناقلة؟!')){
            $('button').attr('disabled','disabled');
            get_data(adopt_url, values, function(ret){
                if(ret==1){
                    success_msg('رسالة','تم '+msg+' المناقلة بنجاح ..');
                    adopt_btn.attr('disabled','disabled');
                    $('button').attr('disabled','disabled');
                }else{
                    danger_msg('تحذير..',data);
                }
            }, 'html');
        }
    }

    function transport_output(){
        danger_msg('تحذير..','تم تعطيل الخاصية');
         // قبل تفعيل هذه الخاصية يجب تعديل شاشة ادخال سند الصرف لتستقبل اكواد العهد
        //get_to_link('{$output_url}/2/{$rs['CLASS_TRANSPORT_ID']}');
    }

    $('#print_rep').click(function(){
        _showReport('$report_url'+'&report=STORES_CLASS_TRANSPORT_TB&p_class_transport_id={$rs['CLASS_TRANSPORT_ID']}');
    });

    </script>
SCRIPT;
}

sec_scripts($scripts);

?>
