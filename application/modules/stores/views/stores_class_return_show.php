<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 05/01/15
 * Time: 04:25 م
 */

$MODULE_NAME= 'stores';
$TB_NAME= 'stores_class_return';
$report_url = base_url("JsperReport/showreport?sys=financial/stores");
$back_url=base_url("$MODULE_NAME/$TB_NAME/index"); //$action
$select_items_url=base_url("$MODULE_NAME/classes/public_index");

$isCreate =isset($return_data) && count($return_data)  > 0 ?0:1;
$rs=$isCreate ?array() : $return_data[0];

$post_url= base_url("$MODULE_NAME/$TB_NAME/".($action == 'index'?'create':$action));
$return_url= base_url("$MODULE_NAME/$TB_NAME/get");
$adopt_url= base_url("$MODULE_NAME/$TB_NAME/adopt");
$cancel_url= base_url("$MODULE_NAME/$TB_NAME/cancel");
$edit_account_url= base_url("$MODULE_NAME/$TB_NAME/edit_account");

$get_class_url =base_url('stores/classes/public_get_id');
$select_accounts_url =base_url('financial/accounts/public_select_accounts');
$customer_url =base_url('payment/customers/public_index');
$project_accounts_url =base_url('projects/projects/public_select_project_accounts');
$select_rooms_url =base_url('pledges/rooms_structure_tree/public_select_rooms');
$room_cus_url= base_url('pledges/rooms_structure_tree/public_get_room_by_id');
$store_serv_req_url =base_url("$MODULE_NAME/$TB_NAME/serv_get");

$print_url = gh_itdev_rep_url().'/gfc.aspx?data='.get_report_folder().'&' ;

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
                    <label class="control-label">رقم السند</label>
                    <div>
                        <input type="text" readonly id="txt_class_return_id" class="form-control ">
                        <?php if (( isset($can_edit)?$can_edit:false)) : ?>
                            <input type="hidden" name="class_return_id" id="h_class_return_id">
                        <?php endif; ?>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">المخزن المرجع اليه</label>
                    <div>
                        <select name="to_store_id" id="dp_to_store_id" class="form-control" data-val="true"  data-val-required="حقل مطلوب"  >
                            <option></option>
                            <?php foreach($stores as $row) :?>
                                <option value="<?=$row['STORE_ID']?>"><?=$row['STORE_NO'].': '.$row['STORE_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="field-validation-valid" data-valmsg-for="to_store_id" data-valmsg-replace="true"></span>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">نوع سند الإرجاع</label>
                    <div>
                        <select name="class_return_type" id="dp_class_return_type" class="form-control" data-val="true"  data-val-required="حقل مطلوب"  >
                            <option></option>
                            <?php foreach($return_type as $row) :?>
                                <option value="<?=$row['CON_NO']?>"><?=$row['CON_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="field-validation-valid" data-valmsg-for="class_return_type" data-valmsg-replace="true"></span>
                    </div>
                </div>

                <div style="clear: both">

                <div class="form-group col-sm-1">
                    <label class="control-label">نوع الحساب</label>
                    <div>
                        <select name="account_type" id="dp_account_type" class="form-control" data-val="true"  data-val-required="حقل مطلوب"  >
                            <option></option>
                            <?php foreach($account_type as $row) :?>
                                <option value="<?=$row['CON_NO']?>"><?=$row['CON_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="field-validation-valid" data-valmsg-for="account_type" data-valmsg-replace="true"></span>
                    </div>
                </div>

                <div class="form-group col-sm-3">
                    <label class="control-label">حساب الارجاع </label>
                    <div>
                        <input type="text" data-val="false" readonly data-val-required="required" class="form-control" id="txt_class_return_account_id" />
                        <input type="hidden" name="class_return_account_id" id="h_txt_class_return_account_id" />
                        <span class="field-validation-valid" data-valmsg-for="class_return_account_id" data-valmsg-replace="true"></span>
                    </div>
                </div>

                <div class="form-group col-sm-2" id="div_customer_account_type" style="display: none">
                    <label class="control-label">نوع حساب المستفيد</label>
                    <div>
                        <select name="customer_account_type" id="dp_customer_account_type" class="form-control" data-val="true"  data-val-required="حقل مطلوب"  >
                            <option></option>
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


<div id="div_customer_account_type1" style="display: none">
                <div class="form-group col-sm-1">
                    <label class="control-label">رقم الغرفة</label>
                    <div>
                        <input type="text" name="room_id"  value=""  readonly  data-type="text"   id="txt_room_id" class="form-control " >

                    </div>
                </div>
                <div class="form-group col-sm-3">
                    <label class="control-label">اسم الغرفة</label>
                    <div>
                        <input type="text" name="room_name"  value=""  readonly  data-type="text"   id="txt_room_name" class="form-control "   >

                    </div>
                </div>
</div>

                <div class="form-group col-sm-2">
                    <label class="control-label"> رقم الطلبية من الخدمات <a style="cursor: pointer" id="a_store_serv_req" ><i class="glyphicon glyphicon-new-window"></i></a> </label>
                    <div>
                        <input type="text" name="store_serv_req" data-val="false" id="txt_store_serv_req" class="form-control ">
                    </div>
                </div>

                <div class="form-group col-sm-9">
                    <label class="control-label">بيان</label>
                    <div>
                        <input type="text" name="notes" data-val="false"  data-val-required="حقل مطلوب"  id="txt_notes" class="form-control ">
                        <span class="field-validation-valid" data-valmsg-for="notes" data-valmsg-replace="true"></span>
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

                <div class="form-group col-sm-3" id="entry_date" style="display: none">
                    <label class="control-label">تاريخ الادخال</label>
                    <div></div>
                </div>

                <div class="form-group col-sm-3" id="entry_user" style="display: none">
                    <label class="control-label">اسم المدخل</label>
                    <div></div>
                </div>

                <div class="form-group col-sm-3" id="adopt_date" style="display: none">
                    <label class="control-label">تاريخ الاعتماد</label>
                    <div></div>
                </div>

                <div class="form-group col-sm-3" id="adopt_user" style="display: none">
                    <label class="control-label">اسم المعتمد</label>
                    <div></div>
                </div>

                <div style="clear: both"></div>

                <input type="hidden" id="h_data_search" />

                <?php echo modules::run("$MODULE_NAME/$TB_NAME/public_get_details", (count($rs))?$rs['CLASS_RETURN_ID']:0, (count($rs))?$rs['ADOPT']:0 , $show_code_sers); ?>

            </div>

            <div class="modal-footer">
                <?php if (  HaveAccess($post_url) && ($isCreate || ( $rs['ADOPT']==1 and isset($can_edit)?$can_edit:false) )  ) : ?>
                    <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                <?php endif; ?>

                <?php if ( !$isCreate and $rs['ADOPT']!=0 ) : ?>
                    <span><?php echo modules::run('attachments/attachment/index',$rs['CLASS_RETURN_ID'],'stores_class_return'); ?></span>
                <?php endif; ?>

                <?php if ( !$isCreate and HaveAccess($edit_account_url) and $rs['ADOPT']==2 ) : ?>
                    <button type="button" id="btn_edit_account" onclick='javascript:edit_account();' class="btn btn-primary">تعديل الحساب</button>
                <?php endif; ?>

                <?php if ( !$isCreate and $rs['ADOPT']!=0 ) : ?>
                    <button type="button" onclick="$('#details_tb').tableExport({type:'excel',escape:'false'});" class="btn btn-success">  اكسل </button>
                    <button type="button" id="print_rep" onclick="javascript:print_rep();" class="btn btn-success"> <i class="glyphicon glyphicon-print"></i> طباعة </button>
                <?php endif; ?>

                <?php if ( HaveAccess($adopt_url) and !$isCreate and $rs['ADOPT']==1 ) : ?>
                    <button type="button" id="btn_adopt" onclick='javascript:adopt(1);' class="btn btn-success">اعتماد</button>
                <?php endif; ?>

                <?php if ( $can_cancel and $rs['ADOPT']!=0 ) : ?>
                    <button type="button" onclick='javascript:adopt(0);' class="btn btn-danger">الغاء السند</button>
                <?php endif; ?>

                <?php if ($isCreate): ?>
                    <button type="button" onclick="javascript:clear_form();"  class="btn btn-default"> تفريغ الحقول</button>
                <?php endif; ?>

            </div>

        </form>
    </div>
</div>



<?php
$scripts = <<<SCRIPT
<script>
 var index ;
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

    function do_after_select(){ //alert ($('#dp_customer_account_type').val()+' '+$('#dp_class_return_type').val()+' '+$('#dp_account_type').val());
         
            if (($('#dp_customer_account_type').val()==3)&&($('#dp_class_return_type').val()==24)&&($('#dp_account_type').val()==2)){
                    val=index.find('input[name="personal_custody_type[]"]').val();
            // alert(val);
              if ( (val !=1) && (val !=2) ){
                  index.find('input[name="class[]"]').val('');
                  index.find('input[name="class_name[]"]').val(''); 
                  index.find('select[name="class_unit[]"]').select2("val", '');
                 
                 danger_msg('تحذير','يجب أن يكون قسم العهدة شخصية أو إدارة');
             } 
            }
       
  }
  
    reBind();

    $('select[name="class_unit[]"]').append(select_class_unit);
    $('select[name="class_type[]"]').append(select_class_type);

    $('select[name="class_unit[]"]').each(function(){
        $(this).val($(this).attr('data-val'));
    });

    $('select[name="class_type[]"]').each(function(){
        $(this).val($(this).attr('data-val'));
    });

    $('select[name="class_unit[]"] , select[name="class_type[]"], #dp_to_store_id , #dp_account_type, #dp_class_return_type, #dp_customer_account_type, .codes_ser').select2();
    $('select[name="class_unit[]"]').select2("readonly",true);

    $('#txt_class_return_account_id').click(function(e){
        var _type = $('#dp_account_type').select2('val');
        if(_type == 1)
            _showReport('$select_accounts_url/'+$(this).attr('id') );
        else if(_type == 2)
            _showReport('$customer_url/'+$(this).attr('id')+'/1');
        else if(_type == 3)
            _showReport('$project_accounts_url/'+$(this).attr('id') );
        else if(_type == 4 )
	        _showReport('$select_rooms_url/'+$(this).attr('id'));
    });
    
    
    //id="div_customer_account_type" style="display: none"

        $('#dp_customer_account_type,#dp_class_return_type,#dp_account_type').change(function(){
            get_room(); 
        });
               
        function setDefaultCustomerAccount() {
            get_room(); 
        }
        
        function get_room(){
            return false; // تم الالغاء 202210
            
             if (($('#dp_customer_account_type').val()==10)&&($('#dp_class_return_type').val()==24)&&($('#dp_account_type').val()==2)){
                         
                         get_data('{$room_cus_url}',{'customer_id':$('#h_txt_class_return_account_id').val()} ,function(data){
                   if (data.length == 1){
        
                            var item= data[0];
                            $('#txt_room_id').val(item.ROOM_ID);
                            $('#txt_room_name').val(item.ROOM_NAME);
                            
                            } else {
                       danger_msg('تحذير','الموظف غير محدد له غرفة معينة');
                            }
                   
                   });
                       document.getElementById("div_customer_account_type1").style.display = "block";
                               $('input[name="class_name[]"]').each(function(){
                                  index= $(this).closest('tr');
                                  do_after_select();
                                });
                    }else{
                         $('#txt_room_id').val('');
                        $('#txt_room_name').val('');
                      document.getElementById("div_customer_account_type1").style.display = "none";
                     
                    }
        }
    $('button[data-action="submit"]').click(function(e){
        e.preventDefault();

        if({$isCreate}==0){
            if(!chk_codes_ser() ) return 0;
        }

        if(confirm('هل تريد حفظ السند ؟!')){
            $(this).attr('disabled','disabled');
            var form = $(this).closest('form');
            ajax_insert_update(form,function(data){
                if(parseInt(data)>1){
                    success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                    get_to_link('{$return_url}/'+parseInt(data)+'/edit');
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
           

    function addRow(){
        count = count+1;
        var html = '<tr> <td><i class="glyphicon glyphicon-sort" /></i></td> <td><input type="hidden" name="ser[]" value="0" /> <input name="class[]" class="form-control" id="i_txt_class_id'+count+'" /> <input type="hidden" name="class_id[]" id="h_txt_class_id'+count+'" /></td>  <td><input name="class_name[]" readonly class="form-control" id="txt_class_id'+count+'" /><input type="hidden" name="personal_custody_type[]" readonly class="form-control" id="txt_personal_custody_type'+count+'" /></td> <td><input name="amount[]" class="form-control" id="txt_amount'+count+'" /></td> <td><select name="class_unit[]" class="form-control" id="unit_txt_class_id'+count+'" /></select></td> <td><input type="hidden" name="class_code_ser[]" value="" /></td> <td><select name="class_type[]" class="form-control" id="txt_class_type'+count+'" /></select></td> <td></td></tr>';
        $('#details_tb tbody').append(html);
        reBind(1);
    }

    $('.codes_ser').change(function(){
        $(this).closest('td').find('input[name="class_code_ser[]"]').val( $(this).val() );
        $('#btn_adopt').attr('disabled','disabled');
    });


   /* function AddRowWithData(id,name_ar,unit,price,unit_name){
        addRow();
        $('#h_txt_class_id'+(count)).val(id);
        $('#i_txt_class_id'+(count)).val(id);
        $('#txt_class_id'+(count)).val(name_ar);
        $('#unit_txt_class_id'+(count)).select2("val", unit);
        $('#report').modal('hide');
    } */

    $('#dp_account_type').change(function(){
        $('#txt_class_return_account_id').val('');
        $('#h_txt_class_return_account_id').val('');
        chk_customer_account_type();
        {
            $('#div_customer_account_type1').hide();
            $('#txt_room_id').val('');
            $('#h_room_id').val('');
            $('#txt_room_name').val('');
        }
    });

    function chk_customer_account_type(){
        if( $('#dp_account_type').val()==2 ){
            $('#div_customer_account_type').show();
        }else{
            $('#div_customer_account_type').hide();
        }
        
        if( $('#dp_account_type').val()==4 ){
            $('#div_customer_account_type1').show();
        }
    }

    $('input[type="text"],body').bind('keydown', 'down', function() {
        addRow();
        return false;
    });

    $('#a_store_serv_req').click(function(){
        _showReport('$store_serv_req_url/'+ $('#txt_store_serv_req').val() );
    });

    function reBind(s){
        if(s==undefined)
            s=0;
        $('input[name="class_name[]"]').click("focus",function(e){
            index= $('input[name="class_name[]"]').closest('tr');
            _showReport('$select_items_url/'+$(this).attr('id')+ $('#h_data_search').val() );
        });

        $('input[name="class[]"]').bind("focusout",function(e){
            index= $(this).closest('tr');
            var id= $(this).val();
            var class_id= $(this).closest('tr').find('input[name="class_id[]"]');
            var name= $(this).closest('tr').find('input[name="class_name[]"]');
            var unit= $(this).closest('tr').find('select[name="class_unit[]"]');
            var amount= $(this).closest('tr').find('input[name="request_amount[]"]');
             var personal_custody_type= $(this).closest('tr').find('input[name="personal_custody_type[]"]');
           
            if(id==''){
                class_id.val('');
                name.val('');
                unit.select2("val", '');
                personal_custody_type.val('');
                return 0;
            }
            get_data('{$get_class_url}',{id:id, type:1},function(data){
                if (data.length == 1){
                    var item= data[0];
                    class_id.val(item.CLASS_ID);
                    name.val(item.CLASS_NAME_AR);
                    unit.select2("val", item.CLASS_UNIT_SUB);
                      personal_custody_type.val(item.PERSONAL_CUSTODY_TYPE);
                     do_after_select();
                    amount.focus();
                }else{
                    class_id.val('');
                    name.val('');
                    unit.select2("val", '');
                      personal_custody_type.val('');
                }
            });
        });

        if(s){
            $('select#unit_txt_class_id'+count).append('<option></option>'+select_class_unit).select2().select2("readonly",true);
            $('select#txt_class_type'+count).append('<option></option>'+select_class_type).select2().select2('val','1');
        }
    }


SCRIPT;

if($isCreate){
    $scripts = <<<SCRIPT
    {$scripts}

    $(function() {
        $( "#details_tb tbody" ).sortable();
    });

    function clear_form(){
        clearForm($('#{$TB_NAME}_form'));
    }

    </script>
SCRIPT;
}else{
    $scripts = <<<SCRIPT
    {$scripts}
    $('#txt_class_return_id').val('{$rs['CLASS_RETURN_ID']}');
    $('#h_class_return_id').val('{$rs['CLASS_RETURN_ID']}');
    $('#dp_to_store_id').select2('val', '{$rs['TO_STORE_ID']}');
    $('#dp_class_return_type').select2('val', '{$rs['CLASS_RETURN_TYPE']}');
    $('#dp_account_type').select2('val', '{$rs['ACCOUNT_TYPE']}');
    $('#txt_class_return_account_id').val('{$rs['CLASS_RETURN_ACCOUNT_NAME']}');
    $('#h_txt_class_return_account_id').val('{$rs['CLASS_RETURN_ACCOUNT_ID']}');
    $('#dp_customer_account_type option[value="{$rs['CUSTOMER_ACCOUNT_TYPE']}"]').prop('disabled',0);
    $('#dp_customer_account_type').select2('val', '{$rs['CUSTOMER_ACCOUNT_TYPE']}');
    $('#txt_update_account_date').val('{$rs['UPDATE_ACCOUNT_DATE']}');
    $('#txt_room_id').val('{$rs['ROOM_ID']}');
    $('#h_room_id').val('{$rs['ROOM_ID']}');
    $('#txt_room_name').val('{$rs['ROOM_ID_NAME']}');
    chk_customer_account_type();
    $('#txt_notes').val('{$rs['NOTES']}');
    $('#txt_cancel_note').val('{$rs['CANCEL_NOTE']}');
    $('#txt_store_serv_req').val('{$rs['STORE_SERV_REQ']}');
    $('div#entry_date div').text('{$rs['ENTRY_DATE']}');
    $('div#entry_user div').text('{$rs['ENTRY_USER_NAME']}');
    $('div#adopt_date div').text('{$rs['ADOPT_DATE']}');
    $('div#adopt_user div').text('{$rs['ADOPT_USER_NAME']}');
    $('div#entry_date, div#entry_user, div#adopt_date, div#adopt_user').show();

                  if (($('#dp_customer_account_type').val()==3)&&($('#dp_class_return_type').val()==24)&&($('#dp_account_type').val()==2)){ // alert(val);
                   document.getElementById("div_customer_account_type1").style.display = "block";
                    }else{
                      document.getElementById("div_customer_account_type1").style.display = "none";
                     }
                    
    count = {$rs['COUNT_DET']} -1;

    if( $('a.class_codes_url').length!=0 ){
        $.each( $('a.class_codes_url') , function() {
            $(this).prop('href', $(this).prop('href').replace("url_store", "{$rs['TO_STORE_ID']}") );
        });
    }

    function adopt(no){
        if( no==0 && $('#txt_cancel_note').val() =='' ){
            danger_msg('تحذير..','ادخل بيان الالغاء');
            return false;
        }
        if(no==1){
            if(!chk_codes_ser()) return 0;
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
            }
            var values= {class_return_id: "{$rs['CLASS_RETURN_ID']}" , cancel_note: $('#txt_cancel_note').val()};
            get_data(adopt_url, values, function(ret){
                if(ret==1){
                    if(no==0){
                        success_msg('رسالة','تم الغاء السند بنجاح ..');
                        $('button').attr('disabled','disabled');
                    }else{
                        success_msg('رسالة','تم اعتماد السند بنجاح ..');
                        $('button').attr('disabled','disabled');
                    }
                }else{
                    danger_msg('تحذير..',data);
                }
            }, 'html');
        }
    }
    
    function edit_account(){
        var msg= 'سيتم تعديل رقم الحساب وتحديث القيد، تأكيد؟!';
                
        if(confirm(msg)){
            var values= {class_return_id: "{$rs['CLASS_RETURN_ID']}" , account_type: $('#dp_account_type').val(), class_return_account_id: $('#h_txt_class_return_account_id').val(), customer_account_type: $('#dp_customer_account_type').val() };
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

    function chk_codes_ser(){
        var ret= true;
        var codes_ser= $('input[name="class_code_ser[]"]');
        $.each(codes_ser, function(i,item){
            var td= $(item).closest('td');
            var tr= $(item).closest('tr');
            if({$show_code_sers}==1){
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
                    alert('اختر باركود العهد');
                    td.addClass   ("td-red", 500, "easeInBack");
                    td.removeClass("td-red", 1000, "easeInBack");
                    ret= false;
                }
            }
        });
        return ret;
    }

    $('#print_rep').click(function(){
        _showReport('$report_url'+'&report=stores_class_return_rep&p_class_return_id={$rs['CLASS_RETURN_ID']}');
    });

    </script>
SCRIPT;
}

sec_scripts($scripts);

?>
