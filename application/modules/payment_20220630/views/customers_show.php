<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 02/12/14
 * Time: 12:17 م
 */

$MODULE_NAME= 'payment';
$TB_NAME= 'customers';

$editx_url =base_url("$MODULE_NAME/$TB_NAME/edit_pur");
$createx_url =base_url("$MODULE_NAME/$TB_NAME/create_pur");
$deletex_url =base_url("$MODULE_NAME/$TB_NAME/delete_pur");
$today= date('d/m/Y');

$back_url=base_url("$MODULE_NAME/$TB_NAME/index"); //$action

$isCreate =isset($customer_data) && count($customer_data)  > 0 ?false:true;
$rs=$isCreate ?array() : $customer_data[0];
$customer_id=(!$isCreate)?$rs['CUSTOMER_ID']:0 ;

echo AntiForgeryToken();
?>

<style>
    fieldset{margin-bottom:10px }
</style>

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
        <form class="form-vertical" id="<?=$TB_NAME?>_form" method="post" action="<?=base_url("$MODULE_NAME/$TB_NAME/".($action == 'index'?'create':$action))?>" role="form" novalidate="novalidate">
            <div class="modal-body inline_form">

                <fieldset>
                    <legend></legend>
                <div class="form-group col-sm-2">
                    <label class="control-label">رقم الهوية أو مشتغل المرخص</label>
                    <div>
                        <input type="text" name="customer_id" placeholder="رقم الهوية أو مشتغل المرخص"  data-val="true"  data-val-required="حقل مطلوب"  id="txt_customer_id" maxlength="11" class="form-control ">
                        <span class="field-validation-valid" data-valmsg-for="customer_id" data-valmsg-replace="true"></span>
                    </div>
                </div>

                <div class="form-group col-sm-3">
                    <label class="control-label">اسم المستفيد</label>
                    <div>
                        <input type="text" name="customer_name" placeholder="شخص، مورد شركة، الخ.."  data-val="true"  data-val-required="حقل مطلوب"  id="txt_customer_name" maxlength="120" class="form-control ">
                        <span class="field-validation-valid" data-valmsg-for="customer_name" data-valmsg-replace="true"></span>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label"> نوع المستفيد </label>
                    <div>
                        <select name="customer_type" id="dp_customer_type" class="form-control">
                            <?php foreach($customer_type as $row) :?>
                                <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-1">
                    <label class="control-label"> نوع الحساب </label>
                    <div>
                        <select name="account_type" id="dp_account_type" class="form-control">
                            <option disabled value="1">حساب واحد</option>
                            <option value="2">حسابات متعددة </option>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-2" id="account_id">
                    <label class="control-label">رقم الحساب</label>
                    <div>
                        <input type="text" name="account_name" data-val="true" readonly data-val-required="حقل مطلوب"  id="txt_account_id" class="form-control ">
                        <input type="hidden" name="account_id" id="h_txt_account_id">
                        <span class="field-validation-valid" data-valmsg-for="account_id" data-valmsg-replace="true"></span>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">رقم الحساب الدولي</label>
                    <div>
                        <input type="text" name="iban" data-val="false"  data-val-required="حقل مطلوب"  id="txt_iban" class="form-control ">
                        <span class="field-validation-valid" data-valmsg-for="iban" data-valmsg-replace="true"></span>
                    </div>
                </div>
                </fieldset>

                <fieldset id="fs_supplier">
                    <legend>بيانات المورد</legend>
                    <div class="form-group col-sm-3">
                        <label class="control-label">اسم صاحب الشركة</label>
                        <div>
                            <input type="text" name="company_owner" data-val="false"  data-val-required="حقل مطلوب"  id="txt_company_owner" class="form-control ">
                            <span class="field-validation-valid" data-valmsg-for="company_owner" data-valmsg-replace="true"></span>
                        </div>
                    </div>

                    <div class="form-group col-sm-2 delegate">
                        <label class="control-label">رقم هوية المفوض بالتواصل</label>
                        <div>
                            <input type="text" name="company_delegate_id" maxlength="9" data-val="false"  data-val-required="حقل مطلوب"  id="txt_company_delegate_id" class="form-control ">
                            <span class="field-validation-valid" data-valmsg-for="company_delegate_id" data-valmsg-replace="true"></span>
                        </div>
                    </div>

                    <div class="form-group col-sm-3 delegate">
                        <label class="control-label">اسم المفوض بالتواصل</label>
                        <div>
                            <input type="text" name="customer_delegate_name" data-val="false"  data-val-required="حقل مطلوب"  id="txt_customer_delegate_name" class="form-control ">
                            <span class="field-validation-valid" data-valmsg-for="customer_delegate_name" data-valmsg-replace="true"></span>
                        </div>
                    </div>

                    <div class="form-group col-sm-2">
                        <label class="control-label"> نطاق عمل المورد </label>
                        <div>
                            <select name="company_work_scope" id="dp_company_work_scope" class="form-control">
                                <?php foreach($company_work_scope as $row) :?>
                                    <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group col-sm-2">
                        <label class="control-label"> تصنيف المورد  </label>
                        <div>
                            <select name="company_level_scope" id="dp_company_level_scope" class="form-control">
                                <?php foreach($company_level_scope as $row) :?>
                                    <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group col-sm-2">
                        <label class="control-label">مجال عمل المورد</label>
                        <div>
                            <select name="company_field_work" id="dp_company_field_work" class="form-control">
                                <?php foreach($company_field_work as $row) :?>
                                    <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group col-sm-2">
                        <label class="control-label">تاريخ فتح ملف المورد</label>
                        <div>
                            <input type="text" name="company_subscription_date" value="<?=$today?>" placeholder="اعتماده ضمن موردي الشركة" data-type="date"  data-date-format="DD/MM/YYYY"  data-val-regex="التاريخ غير صحيح!" data-val-regex-pattern="<?= date_format_exp() ?>" data-val="true"  data-val-required="حقل مطلوب"  id="txt_company_subscription_date" class="form-control ">
                            <span class="field-validation-valid" data-valmsg-for="company_subscription_date" data-valmsg-replace="true"></span>
                        </div>
                    </div>
                    <div class="form-group col-sm-3">
                    <a style="width: 100px; margin-left: 10px" href="javascript:;" onclick="javascript:show_pur();" class="icon-btn">
                        <i class="glyphicon glyphicon-certificate"></i>
                        <div> أنواع المشتريات للمورد </div>
                    </a>
               </div>
                </fieldset>

                <fieldset>
                    <legend></legend>

                <div class="form-group col-sm-2">
                    <label class="control-label">البنك</label>
                    <div>
                        <select name="bank" id="dp_bank" class="form-control">
                                <option value=""></option>
                            <?php foreach($banks as $row) :?>
                                <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">رقم الحساب البنكي الحالي</label>
                    <div>
                        <input type="text" name="customer_bank_account" data-val="false"  data-val-required="حقل مطلوب"  id="txt_customer_bank_account" class="form-control ">
                        <span class="field-validation-valid" data-valmsg-for="customer_bank_account" data-valmsg-replace="true"></span>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">رقم الحساب البنكي السابق</label>
                    <div>
                        <input type="text" name="customer_bank_old_account" data-val="false"  data-val-required="حقل مطلوب"  id="txt_customer_bank_old_account" class="form-control ">
                        <span class="field-validation-valid" data-valmsg-for="customer_bank_old_account" data-valmsg-replace="true"></span>
                    </div>
                </div>
                </fieldset>

				  <fieldset>
                    <legend></legend>

                    <div class="form-group col-sm-2">
                        <label class="control-label">دخول العطاءات</label>
                        <div>
                            <select name="quotes_entrance" id="dp_quotes_entrance" class="form-control">
                                <option value="1">مسموح</option>
                                <option value="2">غير مسموح</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group col-sm-8">
                        <label class="control-label">السباب</label>
                        <div>
                            <input type="text" name="quotes_entrance_hints" class="form-control ">

                        </div>
                    </div>

                </fieldset>
				
                <fieldset>
                    <legend>بيانات الاتصال</legend>
                    <div class="form-group col-sm-2">
                        <label class="control-label">رقم الهاتف</label>
                        <div>
                            <input type="text" name="phone" min="9999" max="999999999999999" maxlength="15" data-val="false"  data-val-required="حقل مطلوب"  id="txt_phone" class="form-control ">
                            <span class="field-validation-valid" data-valmsg-for="phone" data-valmsg-replace="true"></span>
                        </div>
                    </div>

                    <div class="form-group col-sm-2">
                        <label class="control-label">رقم الجوال</label>
                        <div>
                            <input type="text" name="mobil" min="9999" max="999999999999999" maxlength="15" data-val="false"  data-val-required="حقل مطلوب"  id="txt_mobil" class="form-control ">
                            <span class="field-validation-valid" data-valmsg-for="mobil" data-valmsg-replace="true"></span>
                        </div>
                    </div>

                    <div class="form-group col-sm-2">
                        <label class="control-label">رقم الفاكس</label>
                        <div>
                            <input type="text" name="fax" min="9999" max="999999999999999" maxlength="15" data-val="false"  data-val-required="حقل مطلوب"  id="txt_fax" class="form-control ">
                            <span class="field-validation-valid" data-valmsg-for="fax" data-valmsg-replace="true"></span>
                        </div>
                    </div>

                    <div class="form-group col-sm-2">
                        <label class="control-label">البريد الإلكتروني</label>
                        <div>
                            <input type="text" name="email" data-val="false"  data-val-required="حقل مطلوب"  id="txt_email" class="form-control ">
                            <span class="field-validation-valid" data-valmsg-for="email" data-valmsg-replace="true"></span>
                        </div>
                    </div>

                    <div class="form-group col-sm-2">
                        <label class="control-label">الموقع الإلكتروني</label>
                        <div>
                            <input type="text" name="web" data-val="false"  data-val-required="حقل مطلوب"  id="txt_web" class="form-control ">
                            <span class="field-validation-valid" data-valmsg-for="web" data-valmsg-replace="true"></span>
                        </div>
                    </div>

                    <div class="form-group col-sm-10">
                        <label class="control-label">العنوان</label>
                        <div>
                            <input type="text" name="address" data-val="false"  data-val-required="حقل مطلوب"  id="txt_address" class="form-control ">
                            <span class="field-validation-valid" data-valmsg-for="address" data-valmsg-replace="true"></span>
                        </div>
                    </div>

                    <div style="clear: both"></div>

                    <div class="form-group col-sm-2">
                        <label class="control-label">رقم القضية</label>
                        <div>
                            <input type="text" name="t_ser_no" data-val="false"  data-val-required="حقل مطلوب"  id="txt_t_ser_no" class="form-control ">
                            <span class="field-validation-valid" data-valmsg-for="t_ser_no" data-valmsg-replace="true"></span>
                        </div>
                    </div>

                    <div class="form-group col-sm-2">
                        <label class="control-label">تاريخ القضية</label>
                        <div>
                            <input type="text" name="t_date" data-type="date" data-date-format="DD/MM/YYYY"  data-val-regex="التاريخ غير صحيح!" data-val-regex-pattern="<?= date_format_exp() ?>" data-val="false"  data-val-required="حقل مطلوب"  id="txt_t_date" class="form-control ">
                            <span class="field-validation-valid" data-valmsg-for="t_date" data-valmsg-replace="true"></span>
                        </div>
                    </div>

                    <div class="form-group col-sm-2">
                        <label class="control-label">القيمة الاجمالية على القضية </label>
                        <div>
                            <input type="text" name="t_total_value" data-val="false"  data-val-required="حقل مطلوب"  id="txt_t_total_value" class="form-control ">
                            <span class="field-validation-valid" data-valmsg-for="t_total_value" data-valmsg-replace="true"></span>
                        </div>
                    </div>

                    <div class="form-group col-sm-2">
                        <label class="control-label">القيمة المستحقة للدفع </label>
                        <div>
                            <input type="text" name="t_rest_value" data-val="false"  data-val-required="حقل مطلوب"  id="txt_t_rest_value" class="form-control ">
                            <span class="field-validation-valid" data-valmsg-for="t_rest_value" data-valmsg-replace="true"></span>
                        </div>
                    </div>

                </fieldset>

                <fieldset id="accounts_details" style="display: none">
                    <legend >ارقام الحسابات</legend>
                    <?php echo modules::run("$MODULE_NAME/$TB_NAME/public_get_details_2",(count($rs))?$rs['CUSTOMER_ID']:0); ?>
                </fieldset>

                <fieldset>
                    <legend>بيانات اشتراكات الكهرباء</legend>
                    <?php echo modules::run("$MODULE_NAME/$TB_NAME/public_get_details",(count($rs))?$rs['CUSTOMER_ID']:0); ?>
                </fieldset>

                <?php if (!$isCreate){ ?>
                <div class="form-group col-sm-3">
                    <label class="control-label">اسم المدخل</label>
                    <div id="div_entry_user"></div>
                </div>
                <?php } ?>

            </div>

            <hr/>

            <div class="modal-footer">
                <?php if (( isset($can_edit)?$can_edit:false)) : ?>
                    <input type="hidden" name="customer_seq" id="txt_customer_seq">
                <?php endif; ?>

                <?php if (($isCreate || ( isset($can_edit)?$can_edit:false))) : ?>
                    <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                <?php endif; ?>

                <?php if ($isCreate): ?>
                    <button type="button" onclick="javascript:clear_form();"  class="btn btn-default"> تفريغ الحقول</button>
                <?php   endif; ?>

            </div>

        </form>
    </div>
</div>
<div class="modal fade" id="pur_Modal">
    <div class="modal-dialog" style="width: 900px">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title"> أنواع طلبات الشراء</h4>
            </div>

            <form class="form-horizontal" id="purchase_tb_from" method="post" action="<?=base_url("<?=$editx_url?>")?>" role="form" novalidate="novalidate">

            <div class="modal-body">

                <table class="table" id="purchase_tb" data-container="container">
                    <thead>
                    <tr>
                        <th  >#</th>
                        <th>نوع طلب الشراء</th>
                        <th>ملاحظات</th>
                         <?php  if(HaveAccess($createx_url) or HaveAccess($editx_url))
                            echo "<th>حفظ</th>"; ?>
                        <?php if(HaveAccess($deletex_url)) echo "<th>حذف</th>"; ?>
                    </tr>
                    </thead>

                    <tbody>
                    <?php
                    $count=0;
                    foreach ($get_all as $row1){
                    $count++;?>
                        <tr data-id="<?=$row1['PURCHASE_TYPE']?>">
                            <td><input type="hidden" name="ser" id="txt_ser" value="<?=(!$isCreate)?$row1['SER']:0?>""><?=$count?></td>
                            <td> <div > <select name="purchase_order_class_type" id="txt_purchase_order_class_type" class="form-control" >
                                    <option value="">_________</option>
                                    <?php foreach($purchase_order_class_types as $row) :?>
                                        <option <?=(!$isCreate)?($row1['PURCHASE_TYPE']==$row['CON_NO']?'selected':''):''?> value="<?=$row['CON_NO']?>" ><?=$row['CON_NAME']?></option>
                                    <?php endforeach; ?>
                                </select></div></td>
                            <td> <div><input name="note" class="form-control" id="txt_note" value="<?=(!$isCreate)?$row1['NOTE']:''?>"></div></td>
                            <?php  if(HaveAccess($createx_url) or HaveAccess($editx_url)) { ?>  <td>
                                <a onclick="javascript:savePur('<?=$row1['PURCHASE_TYPE']?>','<?=$row1['NOTE']?>');" href="javascript:;"><i class="glyphicon glyphicon-ok"></i> </a>


                                </td> <?php } ?>
                            <?php if(HaveAccess($deletex_url)){ ?>  <td>
                                <a onclick="javascript:deletePur('<?=$row1['SER']?>');" href="javascript:;"><i class="glyphicon glyphicon-trash"></i> </a>


                                </td><?php } ?>
                        </tr>
                    <?PHP } ?>
                    </tbody>
                    <?php
                    if(HaveAccess($createx_url)){?>
                    <tfoot>
                    <tr>
                        <th></th>
                        <th><a onclick="javascript:createPur();" href="javascript:;"><i class="glyphicon glyphicon-plus"></i>جديد</a></th>
                        <th></th>
                    <?php    if(HaveAccess($createx_url) or HaveAccess($editx_url)) echo "<th></th>";
                        if(HaveAccess($deletex_url)) echo"<th></th>"; ?>
                    </tr>
                    </tfoot>
                    <?php } ?>
                    </table>


            </div>
                </form>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">إغلاق</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<?php
$exp = float_format_exp();
$select_accounts_url =base_url('financial/accounts/public_select_accounts');

$shared_js = <<<SCRIPT

    var count_1 = 0;
    var count_2 = 0;

    {
        $('#dp_account_type').val(2);
        $('div#account_id').hide();
        $('#accounts_details').show();
    }
///////////////////////////////////////

 function show_pur(){
        $('#pur_Modal').modal();
    }
 function savePur (purchase_type,note){

        var tr= $("#purchase_tb tbody [data-id="+purchase_type+"]");
        var ser= tr.find("#txt_ser").val();
        var purchase_order_class_type= tr.find("#txt_purchase_order_class_type").val();
        var note= tr.find("#txt_note").val();


     if (ser>0){
         values= {ser:ser,customer_id:{$customer_id},purchase_type:purchase_order_class_type,note:note};
         url= '{$editx_url}';
     }
        else
     {
         values=  {ser:ser,customer_id:{$customer_id},purchase_type:purchase_order_class_type,note:note};
         url= '{$createx_url}';

     }
        get_data(url, values, function(ret){

      if(Number(ret)==1){
               success_msg('رسالة','تم حفظ البيانات بنجاح ..');
               get_to_link(window.location.href);
            }else{
            danger_msg('تحذير..',ret);
         }
        }, 'html');

    }

     function deletePur (ser){

        if(confirm('هل تريد بالتأكيد حذف السجل ؟!!')){
            var url = '{$deletex_url}';
            var values= {ser:ser};
            ajax_delete_any(url, values ,function(data){
            if(data==1){
               success_msg('رسالة','تم حذف السجل بنجاح ..');
             get_to_link(window.location.href);
            }
            else{
              danger_msg('تحذير..',data);
             }

            });
        }
    }

      var d_count = '{$count}';
       function createPur() {
        d_count++;
        $('#purchase_tb tfoot').hide();
        var count= '{$count}'+1 ;
var htm=  "<tr data-id='0'>" +
         "<td><input type='hidden' name='ser' id='txt_ser' value='0'>"+count+"</td>"+
          "<td> <div > <select name='purchase_order_class_type' id='txt_purchase_order_class_type' class='form-control' >"+
          "<option value=''>_________</option>";

         htm=htm+"{$x}";

htm=htm+" </select></div></td> <td> <div><input name='note' class='form-control' id='txt_note' ></div></td><td>"+
  "<a onclick='javascript:savePur(0,0);' href='javascript:;'><i class='glyphicon glyphicon-ok'></i> </a></td>"+
"<td></td><tr>";


          $("#purchase_tb tbody").append(htm);

    }
//////////////////////////////////////////
    $('button[data-action="submit"]').click(function(e){
        e.preventDefault();
        $(this).attr('disabled','disabled');
        check_form();
        if(confirm('هل تريد حفظ المستفيد ؟!')){
            var form = $(this).closest('form');
            ajax_insert_update(form,function(data){
                if(data==1){
                    success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                    get_to_link(window.location.href);
                }else{
                    danger_msg('تحذير..',data);
                }
            },'html');
        }
        if('{$action}'!='edit')
            $(this).removeAttr('disabled');
        else{
            setTimeout(function() {
                $('button[data-action="submit"]').removeAttr('disabled');
            }, 3000);
        }
    });

    var account_det_type_json= {$account_det_type};
    var select_account_det_type= '';

    $.each(account_det_type_json, function(i,item){
        select_account_det_type += "<option value='"+item.CON_NO+"' >"+item.CON_NAME+"</option>";
    });

    reBind();

    $('select[name="account_det_type[]"]').append(select_account_det_type);

    $('select[name="account_det_type[]"]').each(function(){
        $(this).val($(this).attr('data-val'));
    });


    $('input[name="account_name"]').bind("focus",function(e){
        _showReport('$select_accounts_url/'+$(this).attr('id')+'/-1/');
    });
    $('input[name="account_name"]').click(function(e){
        _showReport('$select_accounts_url/'+$(this).attr('id')+'/-1/' );
    });

    function check_form(){
        if($('#txt_customer_id').val()=='' || $('#txt_customer_name').val()=='' || $('#txt_account_id').val()==''){
            $("html, body").animate({ scrollTop: 100 }, "slow");
        }
    }

    function addRow(){
        count_1 = count_1+1;
        var html ='<tr> <td><input type="hidden" name="SER[]" value="0" /><input name="elect_no[]" data-val="true" class="form-control" id="txt_elect_no'+count_1+'" /></td> <td><input name="notes[]" class="form-control" id="txt_notes'+count_1+'" /></td> </tr>';
        $('#customers_detailsTbl tbody').append(html);
        //reBind();
    }

    function addRow_2(){
        count_2 = count_2+1;
        var html ='<tr> <td><input type="hidden" name="A_SER[]" value="0" /><input name="customer_accounts_name[]" readonly class="form-control" id="txt_customer_accounts_id'+count_2+'" /><input type="hidden" name="customer_accounts_id[]" id="h_txt_customer_accounts_id'+count_2+'" /></td> <td><select name="account_det_type[]" class="form-control" id="txt_account_det_type'+count_2+'" /></select></td> <td><i class="glyphicon glyphicon-remove delete_account"></i></td> <td></td> </tr>';
        $('#customers_detailsTbl2 tbody').append(html);
        reBind(1);
    }

    $('.delete_account').click(function(){
        var tr = $(this).closest('tr');
        tr.find('input[name="customer_accounts_id[]"]').val('');
        tr.find('input[name="customer_accounts_name[]"]').val('');
    });

    function chk_customer_type(){
        var typ= $('#dp_customer_type').val();
        if(typ==6){
            $('#fs_supplier .form-group:not(.delegate)').hide();
        }else{
            $('#fs_supplier .form-group').show();
        }
        if(typ==1 || typ==6){
            $('#fs_supplier').slideDown(10);
        }else{
            $('#fs_supplier').slideUp(10);
        }
        /*
        if(typ==8){
            $('div#account_id').hide();
            $('#h_txt_account_id,#txt_account_id').val('0');
        }else{
            $('div#account_id').show();
            $('#h_txt_account_id,#txt_account_id').val('');
        }
        */
    }

    function chk_account_type(){
        var typ= $('#dp_account_type').val();
        if(typ==1){
            $('div#account_id').show();
            $('#accounts_details').hide();
            $('input[name="customer_accounts_id[]"]').val('');
            $('input[name="customer_accounts_name[]"]').val('');
        }else{
            $('div#account_id').hide();
            $('#accounts_details').show();
        }
    }

    function reBind(s){
        if(s==undefined)
            s=0;
        $('input[name="customer_accounts_name[]"]').bind("focus",function(e){
            _showReport('$select_accounts_url/'+$(this).attr('id')+'/-1/');
        });
        $('input[name="customer_accounts_name[]"]').click(function(e){
            _showReport('$select_accounts_url/'+$(this).attr('id')+'/-1/' );
        });

        $('.delete_account').click(function(){
            var tr = $(this).closest('tr');
            tr.find('input[name="customer_accounts_id[]"]').val('');
            tr.find('input[name="customer_accounts_name[]"]').val('');
        });

        if(s){
            $('select#txt_account_det_type'+count_2).append(select_account_det_type).val(1);
        }
    }

    $('#dp_account_type').change(function(){
        chk_account_type();
    });


SCRIPT;

$scripts = <<<SCRIPT
<script>
    {$shared_js}

    if('{$action}'=='edit')
        get_to_link('{$back_url}');

    function clear_form(){
        clearForm($('#{$TB_NAME}_form'));
        chk_customer_type();
        chk_account_type();
        $('#txt_company_subscription_date').val("{$today}");
    }

    $('#dp_customer_type').change(function(){
        chk_customer_type();
    });

</script>
SCRIPT;

if($isCreate)
    sec_scripts($scripts);

else{
    $edit_script = <<<SCRIPT

<script>
    {$shared_js}

    count_1 = {$rs['COUNT_DET_1']};
    if(count_1>0)
        count_1--;

    count_2 = {$rs['COUNT_DET_2']};
    if(count_2>0)
        count_2--;

    $('#txt_customer_id').val('{$rs['CUSTOMER_ID']}');
    $('#txt_customer_name').val('{$rs['CUSTOMER_NAME']}');
    $('#txt_customer_name').attr('title','{$rs['CUSTOMER_NAME']}');
    $('#dp_customer_type').val('{$rs['CUSTOMER_TYPE']}');
    $('#dp_account_type').val(2); // '{$rs['ACCOUNT_TYPE']}'
    chk_customer_type();
    chk_account_type();
    $('#txt_customer_id').prop('readonly',true);
    $('#h_txt_account_id').val('{$rs['ACCOUNT_ID']}');
    $('#txt_account_id').val('{$rs['ACOUNT_NAME']}');
    $('#txt_account_id').attr('title','{$rs['ACOUNT_NAME']}');
    $('#txt_iban').val('{$rs['IBAN']}');
    $('#txt_company_owner').val('{$rs['COMPANY_OWNER']}');
    $('#txt_company_delegate_id').val('{$rs['COMPANY_DELEGATE_ID']}');
    $('#txt_customer_delegate_name').val('{$rs['CUSTOMER_DELEGATE_NAME']}');
    $('#dp_company_work_scope').val('{$rs['COMPANY_WORK_SCOPE']}');
    $('#dp_company_level_scope').val('{$rs['COMPANY_LEVEL_SCOPE']}');
    $('#dp_company_field_work').val('{$rs['COMPANY_FIELD_WORK']}');
    $('#txt_company_subscription_date').val('{$rs['COMPANY_SUBSCRIPTION_DATE']}');
    $('#dp_bank').val('{$rs['BANK']}');
    $('#txt_customer_bank_account').val('{$rs['CUSTOMER_BANK_ACCOUNT']}');
    $('#txt_customer_bank_old_account').val('{$rs['CUSTOMER_BANK_OLD_ACCOUNT']}');
    $('#txt_phone').val('{$rs['PHONE']}');
    $('#txt_mobil').val('{$rs['MOBIL']}');
    $('#txt_fax').val('{$rs['FAX']}');
    $('#txt_email').val('{$rs['EMAIL']}');
    $('#txt_web').val('{$rs['WEB']}');
    $('#txt_address').val('{$rs['ADDRESS']}');
    $('#txt_t_ser_no').val('{$rs['P_SER_NO']}');
    $('#txt_t_date').val('{$rs['P_DATE']}');
    $('#txt_t_total_value').val('{$rs['P_TOTAL_VALUE']}');
    $('#txt_t_rest_value').val('{$rs['P_REST_VALUE']}');
    $('#txt_customer_seq').val('{$rs['CUSTOMER_SEQ']}');
    $('#div_entry_user').text('{$rs['ENTRY_USER_NAME']}');
	$('select[name="quotes_entrance"]').val('{$rs['QUOTES_ENTRANCE']}');
    $('input[name="quotes_entrance_hints"]').val('{$rs['QUOTES_ENTRANCE_HINTS']}');

</script>
SCRIPT;
    if(isset($can_edit)?$can_edit:false)
        $edit_script =$edit_script;

    sec_scripts($edit_script);

}

?>
