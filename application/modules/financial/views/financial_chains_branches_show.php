<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 20/12/14
 * Time: 10:16 ص
 */

$MODULE_NAME= 'stores';
$TB_NAME= 'stores_payment_request';

$back_url=base_url("$MODULE_NAME/$TB_NAME/index"); //$action
$select_items_url=base_url("$MODULE_NAME/classes/public_index");

$isCreate =isset($request_data) && count($request_data)  > 0 ?false:true;
$rs=$isCreate ?array() : $request_data[0];

$post_url= base_url("$MODULE_NAME/$TB_NAME/".($action == 'index'?'create':$action));
$request_url= base_url("$MODULE_NAME/$TB_NAME/get");
$adopt_t_url= base_url("$MODULE_NAME/$TB_NAME/technical_adopt");
$adopt_f_url= base_url("$MODULE_NAME/$TB_NAME/financial_adopt");
$transport_url= base_url("$MODULE_NAME/stores_class_transport/create");
$output_url= base_url("$MODULE_NAME/stores_class_output/create");

$select_accounts_url =base_url('financial/accounts/public_select_accounts');
$customer_url =base_url('payment/customers/public_index');

//$print_url =  base_url('/reports');
$report_url = base_url("JsperReport/showreport?sys=financial/archives");





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
                    <label class="control-label">رقم الطلب</label>
                    <div>
                        <input type="text" readonly id="txt_request_no" class="form-control ">
                        <?php if (( isset($can_edit)?$can_edit:false)) : ?>
                            <input type="hidden" name="request_no" id="h_request_no">
                        <?php endif; ?>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">الجهة الطالبة</label>
                    <div>
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
                    <label class="control-label">حساب الجهة الطالبة</label>
                    <div>
                        <input type="text" data-val="false" readonly data-val-required="required" class="form-control" id="txt_request_side_account" />
                        <input type="hidden" name="request_side_account" id="h_txt_request_side_account" />
                        <span class="field-validation-valid" data-valmsg-for="request_side_account" data-valmsg-replace="true"></span>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">الجهة المطلوب منها</label>
                    <div>
                        <select name="request_store_from" id="dp_request_store_from" class="form-control" data-val="true"  data-val-required="حقل مطلوب"  >
                            <option></option>
                            <?php foreach($stores as $row) :?>
                                <option value="<?=$row['STORE_ID']?>"><?=$row['STORE_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="field-validation-valid" data-valmsg-for="request_store_from" data-valmsg-replace="true"></span>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">نوع الطلب</label>
                    <div>
                        <select name="request_type" id="dp_request_type" class="form-control" data-val="true"  data-val-required="حقل مطلوب"  >
                            <option></option>
                            <?php foreach($request_type as $row) :?>
                                <option value="<?=$row['CON_NO']?>"><?=$row['CON_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="field-validation-valid" data-valmsg-for="request_type" data-valmsg-replace="true"></span>
                    </div>
                </div>


                <div class="form-group col-sm-2" id="project" style="display: none">
                    <label class="control-label">رقم المشروع</label>
                    <div>
                        <input type="text" name="project_id" data-val="false"  data-val-required="حقل مطلوب"  id="txt_project_id" class="form-control ">
                        <span class="field-validation-valid" data-valmsg-for="project_id" data-valmsg-replace="true"></span>
                    </div>
                </div>

                <div class="form-group col-sm-9">
                    <label class="control-label">بيان</label>
                    <div>
                        <input type="text" name="notes" data-val="false"  data-val-required="حقل مطلوب"  id="txt_notes" class="form-control ">
                        <span class="field-validation-valid" data-valmsg-for="notes" data-valmsg-replace="true"></span>
                    </div>
                </div>

                <div style="clear: both"></div>

                <?php echo modules::run("$MODULE_NAME/$TB_NAME/public_get_details", (count($rs))?$rs['REQUEST_NO']:0, (count($rs))?$rs['ADOPT']:0 ); ?>

            </div>

            <div class="modal-footer">
                <?php if (  HaveAccess($post_url) && ($isCreate || ( $rs['ADOPT']==1 and isset($can_edit)?$can_edit:false) )  ) : ?>
                    <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                <?php endif; ?>

                <button type="button" id="print_rep" onclick="javascript:print_rep();" class="btn btn-success"> <i class="glyphicon glyphicon-print"></i> طباعة </button>

                <?php if ( HaveAccess($adopt_t_url) and !$isCreate and $rs['ADOPT']==1 and $rs['REQUEST_TYPE']<=2 ) : ?>
                    <button type="button" id="btn_t_adopt" onclick='javascript:adopt(1);' class="btn btn-success">اعتماد الادارة الفنية</button>
                <?php endif; ?>

                <?php if ( HaveAccess($adopt_f_url) and !$isCreate and ($rs['ADOPT']==2 or ($rs['ADOPT']==1 and $rs['REQUEST_TYPE']>2))  ) : ?>
                    <button type="button" id="btn_f_adopt" onclick='javascript:adopt(2);' class="btn btn-success">اعتماد الادارة المالية</button>
                <?php endif; ?>

                <?php if ( HaveAccess($transport_url) and !$isCreate and $rs['ADOPT']==3) : ?>
                    <button type="button" onclick='javascript:request_transport();' class="btn btn-success">مناقلة الطلب</button>
                <?php endif; ?>

                <?php if ( HaveAccess($output_url) and !$isCreate and $rs['ADOPT']==3) : ?>
                    <button type="button" onclick='javascript:request_output();' class="btn btn-success">صرف الطلب</button>
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

    $.each(class_unit_json, function(i,item){
        select_class_unit += "<option value='"+item.CON_NO+"' >"+item.CON_NAME+"</option>";
    });

    reBind();

    $('select[name="class_unit[]"]').append(select_class_unit);
    $('select[name="class_unit[]"]').each(function(){
        $(this).val($(this).attr('data-val'));
    });
    $('select[name="class_unit[]"] , #dp_request_side , #dp_request_store_from , #dp_request_type').select2();

    $('#txt_request_side_account').click(function(e){
        var _type = $('#dp_request_side').select2('val');
        if(_type == 1)
            _showReport('$select_accounts_url/'+$(this).attr('id') );
        else if(_type == 2)
            _showReport('$customer_url/'+$(this).attr('id')+'/1');
    });

    $('button[data-action="submit"]').click(function(e){
        e.preventDefault();
        if(confirm('هل تريد حفظ الطلب ؟!')){
            $(this).attr('disabled','disabled');
            var form = $(this).closest('form');
            ajax_insert_update(form,function(data){
                if(parseInt(data)>1){
                    success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                    get_to_link('{$request_url}/'+parseInt(data)+'/edit');
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
        var html ='<tr> <td><input type="hidden" name="ser[]" value="0" /><input name="class_name[]" readonly class="form-control" id="txt_class_id'+count+'" /><input type="hidden" name="class_id[]" id="h_txt_class_id'+count+'" /></td> <td><input name="request_amount[]" class="form-control" id="txt_request_amount'+count+'" /></td> <td><select name="class_unit[]" class="form-control" id="unit_txt_class_id'+count+'" /></select></td> </tr>';
        $('#details_tb tbody').append(html);

        reBind(1);
    }

    $('#dp_request_side').change(function(){
        $('#txt_request_side_account').val('');
        $('#h_txt_request_side_account').val('');
    });

    $('#dp_request_type').change(function(){
        chk_request_type();
    });

    function chk_request_type(){
        var typ= $('#dp_request_type').val();
        if(typ ==1)
            $('div#project').fadeIn(300);
        else
            $('div#project').fadeOut(300);
    }

    function reBind(s){
        if(s==undefined)
            s=0;
        $('input[name="class_name[]"]').click("focus",function(e){
            _showReport('$select_items_url/'+$(this).attr('id'));
        });
        if(s){
            $('select#unit_txt_class_id'+count).append('<option></option>'+select_class_unit).select2();
        }
    }


SCRIPT;

if($isCreate){
    $scripts = <<<SCRIPT
    {$scripts}
    function clear_form(){
        clearForm($('#{$TB_NAME}_form'));
        chk_request_type();
    }

    </script>
SCRIPT;
}else{
    $scripts = <<<SCRIPT
    {$scripts}
    $('#txt_request_no').val('{$rs['REQUEST_NO']}');
    $('#h_request_no').val('{$rs['REQUEST_NO']}');
    $('#dp_request_side').select2('val', '{$rs['REQUEST_SIDE']}');
    $('#dp_request_store_from').select2('val', '{$rs['REQUEST_STORE_FROM']}');
    $('#txt_request_side_account').val('{$rs['REQUEST_SIDE_ACCOUNT_NAME']}');
    $('#h_txt_request_side_account').val('{$rs['REQUEST_SIDE_ACCOUNT']}');
    $('#dp_request_type').select2('val', '{$rs['REQUEST_TYPE']}');
    $('#txt_project_id').val('{$rs['PROJECT_ID']}');
    $('#txt_notes').val('{$rs['NOTES']}');
    chk_request_type();


    function adopt(no){
        if(confirm('هل تريد اعتماد الطلب ؟!')){
            var adopt_url, adopt_btn;
            if(no==1){
                adopt_url= '{$adopt_t_url}';
                adopt_btn= $('#btn_t_adopt');
            }else if(no==2){
                adopt_url= '{$adopt_f_url}';
                adopt_btn= $('#btn_f_adopt');
            }
            var values= {request_no: "{$rs['REQUEST_NO']}" };
            get_data(adopt_url, values, function(ret){
                if(ret==1){
                    success_msg('رسالة','تم اعتماد الطلب بنجاح ..');
                    adopt_btn.attr('disabled','disabled');
                }else{
                    danger_msg('تحذير..',data);
                }
            }, 'html');
        }
    }

    function request_transport(){
        get_to_link('{$transport_url}/{$rs['REQUEST_NO']}');
    }

    function request_output(){
        get_to_link('{$output_url}/{$rs['REQUEST_NO']}');
    }

    $('#print_rep').click(function(){
        _showReport('$report_url?report=STORES_PAYMENT_REQUEST_TB&p_request_no={$rs['REQUEST_NO']}');
    });

    </script>
SCRIPT;
}

sec_scripts($scripts);

?>
