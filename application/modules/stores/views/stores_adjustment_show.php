<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 22/01/15
 * Time: 03:19 م
 */

$MODULE_NAME= 'stores';
$TB_NAME= 'stores_adjustment';

$back_url=base_url("$MODULE_NAME/$TB_NAME/index"); //$action
$select_items_url=base_url("$MODULE_NAME/classes/public_index");

$isCreate =isset($adjustment_data) && count($adjustment_data)  > 0 ?false:true;
$rs=$isCreate ?array() : $adjustment_data[0];

$post_url= base_url("$MODULE_NAME/$TB_NAME/".($action == 'index'?'create':$action));
$adjustment_url= base_url("$MODULE_NAME/$TB_NAME/get");
$adopt_url= base_url("$MODULE_NAME/$TB_NAME/adopt");
$cancel_url= base_url("$MODULE_NAME/$TB_NAME/cancel");

$select_accounts_url =base_url('financial/accounts/public_select_accounts');
$get_class_url =base_url('stores/classes/public_get_id');

$print_url = gh_itdev_rep_url().'/gfc.aspx?data='.get_report_folder().'&' ;

echo AntiForgeryToken();
?>

<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>

        <ul>
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
                        <input type="text" readonly id="txt_stores_adjustment_id" class="form-control ">
                        <?php if (( isset($can_edit)?$can_edit:false)) : ?>
                            <input type="hidden" name="stores_adjustment_id" id="h_stores_adjustment_id">
                        <?php endif; ?>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">نوع السند </label>
                    <div>
                        <select name="stores_adjustment_type" id="dp_stores_adjustment_type" class="form-control" data-val="true"  data-val-required="حقل مطلوب"  >
                            <option></option>
                            <?php foreach($adjustment_type as $row) :?>
                                <option value="<?=$row['CON_NO']?>"><?=$row['CON_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="field-validation-valid" data-valmsg-for="stores_adjustment_type" data-valmsg-replace="true"></span>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label"> المخزن </label>
                    <div>
                        <select name="store_id" id="dp_store_id" class="form-control" data-val="true"  data-val-required="حقل مطلوب"  >
                            <option></option>
                            <?php foreach($stores as $row) :?>
                                <option value="<?=$row['STORE_ID']?>"><?=$row['STORE_NO'].': '.$row['STORE_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="field-validation-valid" data-valmsg-for="store_id" data-valmsg-replace="true"></span>
                    </div>
                </div>

                <div class="form-group col-sm-3">
                    <label class="control-label">  الحساب</label>
                    <div>
                        <input type="text" data-val="false" readonly data-val-required="required" class="form-control" id="txt_account_id" />
                        <input type="hidden" name="account_id" id="h_txt_account_id" />
                        <span class="field-validation-valid" data-valmsg-for="account_id" data-valmsg-replace="true"></span>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">تاريخ السند</label>
                    <div>
                        <input type="text" name="adjustment_date" data-type="date" data-date-format="DD/MM/YYYY" data-val="true" id="txt_adjustment_date" class="form-control" data-val-regex="التاريخ غير صحيح!" data-val-regex-pattern="<?=date_format_exp()?>" />
                        <span class="field-validation-valid" data-valmsg-for="adjustment_date" data-valmsg-replace="true"></span>
                    </div>
                </div>

                <div class="form-group col-sm-9">
                    <label class="control-label">بيان</label>
                    <div>
                        <input type="text" name="note" data-val="false"  data-val-required="حقل مطلوب"  id="txt_note" class="form-control ">
                        <span class="field-validation-valid" data-valmsg-for="note" data-valmsg-replace="true"></span>
                    </div>
                </div>

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

                <?php echo modules::run("$MODULE_NAME/$TB_NAME/public_get_details", (count($rs))?$rs['STORES_ADJUSTMENT_ID']:0, (count($rs))?$rs['ADOPT']:0 ); ?>

            </div>

            <div class="modal-footer">
                <?php if (  HaveAccess($post_url) && ($isCreate || ( $rs['ADOPT']==1 and isset($can_edit)?$can_edit:false) )  ) : ?>
                    <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                <?php endif; ?>

                <?php if ( !$isCreate  and $rs['ADOPT']!=0 ) : ?>
                    <button type="button" onclick="$('#details_tb').tableExport({type:'excel',escape:'false'});" class="btn btn-success">  اكسل </button>
                    <button type="button" id="print_rep" onclick="javascript:print_rep();" class="btn btn-success"> <i class="glyphicon glyphicon-print"></i> طباعة </button>
                <?php endif; ?>

                <?php if ( HaveAccess($adopt_url) and !$isCreate and $rs['ADOPT']==1 ) : ?>
                    <button type="button" id="btn_adopt" onclick='javascript:adopt(1);' class="btn btn-success">اعتماد  </button>
                <?php endif; ?>

                <?php if ( HaveAccess($cancel_url) and !$isCreate and $rs['ADOPT']!=0 ) : ?>
                    <button type="button" id="btn_cancel" onclick='javascript:adopt(0);' class="btn btn-danger">الغاء السند</button>
                <?php endif; ?>

                <?php if ($isCreate): ?>
                    <button type="button" onclick="javascript:clear_form();"  class="btn btn-default"> تفريغ الحقول</button>
                <?php   endif; ?>

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
    $('select[name="class_unit[]"] ,select[name="class_type[]"] , #dp_stores_adjustment_type , #dp_store_id').select2();
    $('select[name="class_unit[]"]').select2("readonly",true);

    $('#txt_account_id').click(function(e){
        _showReport('$select_accounts_url/'+$(this).attr('id') );
    });

    $('button[data-action="submit"]').click(function(e){
        e.preventDefault();
        if(confirm('هل تريد حفظ السند ؟!')){
            $(this).attr('disabled','disabled');
            var form = $(this).closest('form');
            ajax_insert_update(form,function(data){
                if(parseInt(data)>1){
                    success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                    get_to_link('{$adjustment_url}/'+parseInt(data)+'/edit');
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
        var html ='<tr> <td><i class="glyphicon glyphicon-sort" /></i></td> <td><input type="hidden" name="ser[]" value="0" /> <input name="class[]" class="form-control" id="i_txt_class_id'+count+'" /> <input type="hidden" name="class_id[]" id="h_txt_class_id'+count+'" /></td>  <td><input name="class_name[]" readonly class="form-control" id="txt_class_id'+count+'" /></td>  <td><input name="amount[]" class="form-control" id="txt_amount'+count+'" /></td> <td><select name="class_unit[]" class="form-control" id="unit_txt_class_id'+count+'" /></select></td> <td><select name="class_type[]" class="form-control" id="txt_class_type'+count+'" /></select></td> <td><input name="price[]" class="form-control" id="txt_price'+count+'" /></td> </tr>';
        $('#details_tb tbody').append(html);

        reBind(1);
    }

    $('input[type="text"],body').bind('keydown', 'down', function() {
        addRow();
        return false;
    });

    function reBind(s){
        if(s==undefined)
            s=0;
        $('input[name="class_name[]"]').click("focus",function(e){
            _showReport('$select_items_url/'+$(this).attr('id'));
        });

        $('input[name="class[]"]').bind("focusout",function(e){
            var id= $(this).val();
            var class_id= $(this).closest('tr').find('input[name="class_id[]"]');
            var name= $(this).closest('tr').find('input[name="class_name[]"]');
            var unit= $(this).closest('tr').find('select[name="class_unit[]"]');
            var amount= $(this).closest('tr').find('input[name="amount[]"]');
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
    $('#txt_stores_adjustment_id').val('{$rs['STORES_ADJUSTMENT_ID']}');
    $('#h_stores_adjustment_id').val('{$rs['STORES_ADJUSTMENT_ID']}');
    $('#dp_stores_adjustment_type').select2('val', '{$rs['STORES_ADJUSTMENT_TYPE']}');
    $('#dp_store_id').select2('val', '{$rs['STORE_ID']}');
    $('#txt_account_id').val('{$rs['ACCOUNT_ID_NAME']}');
    $('#h_txt_account_id').val('{$rs['ACCOUNT_ID']}');
    $('#txt_adjustment_date').val('{$rs['ADJUSTMENT_DATE']}');
    $('#txt_note').val('{$rs['NOTE']}');
    $('div#entry_date div').text('{$rs['ENTRY_DATE']}');
    $('div#entry_user div').text('{$rs['ENTRY_USER_NAME']}');
    $('div#adopt_date div').text('{$rs['ADOPT_DATE']}');
    $('div#adopt_user div').text('{$rs['ADOPT_USER_NAME']}');
    $('div#entry_date, div#entry_user, div#adopt_date, div#adopt_user').show();

    count = {$rs['COUNT_DET']} -1;

    function adopt(no){
        var url;
		if(no==0){
			confirm_msg ='هل تريد إلغاء السند ؟';
			url= '{$cancel_url}';
	    }else{
			confirm_msg ='هل تريد اعتماد السند ؟';
			url= '{$adopt_url}';
		}

        if(confirm(confirm_msg)){
            var values= {stores_adjustment_id: "{$rs['STORES_ADJUSTMENT_ID']}" };
            get_data(url, values, function(ret){
                if(ret==1){
                    success_msg('رسالة','تمت العملية بنجاح');
                    $('button').attr('disabled','disabled');
                }else{
                    danger_msg('تحذير..',data);
                }
            }, 'html');
        }
    }

    $('#print_rep').click(function(){
        _showReport('$print_url'+'report=STORES_ADJUSTMENT_REP&params[]={$rs['STORES_ADJUSTMENT_ID']}');
    });


    </script>
SCRIPT;
}

sec_scripts($scripts);

?>
