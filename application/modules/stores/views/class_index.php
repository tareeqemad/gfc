<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 8/13/14
 * Time: 12:22 PM
 */


$delete_url =base_url('stores/classes/delete');
$get_url =base_url('stores/classes/get_id');
$edit_url =base_url('stores/classes/edit');
$create_url =base_url('stores/classes/create');
$select_accounts_url =base_url('financial/accounts/public_select_accounts');
$get_id_url =base_url('financial/accounts/public_get_id');
//$select_accounts_url= base_url("financial/accounts/public_get_accounts_all");

?>
<?= AntiForgeryToken() ?>
<div class="row">
    <div class="toolbar">

        <div class="caption"> شجرة الأصناف </div>

        <ul>
            <?php if( HaveAccess($create_url)): ?> <li><a onclick="javascript:class_create();" href="javascript:;"><i class="glyphicon glyphicon-plus"></i>جديد </a> </li> <?php endif;?>
            <?php if( HaveAccess($get_url,$edit_url)): ?><li><a   onclick="javascript:class_get($.fn.tree.selected().attr('data-id'));"  href="javascript:;"><i class="glyphicon glyphicon-edit"></i>تحرير</a> </li> <?php endif;?>
            <?php  if(HaveAccess($delete_url)): ?><li><a  onclick="javascript:class_delete();" href="javascript:;"><i class="glyphicon glyphicon-remove"></i>حذف</a> </li> <?php endif;?>
            <li><a  onclick="$.fn.tree.expandAll()" href="javascript:;"><i class="glyphicon  glyphicon-sort-by-attributes-alt"></i>توسيع</a> </li>
            <li><a  onclick="$.fn.tree.collapseAll()" href="javascript:;"><i class="glyphicon  glyphicon-sort-by-attributes"></i>طي</a> </li>
            <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>
        </ul>

    </div>

    <div class="form-body">

        <div id="msg_container"></div>
        <div class="form-group">
            <div class="input-group col-sm-3">
                <span class="input-group-addon">  <i class="icon icon-search"></i></span>

                <input type="text" id="search-tree" class="form-control" placeholder="بحث">
            </div>
        </div>
        <?= $tree ?>

    </div>

</div>


<div class="modal fade" id="classModal">
    <div class="modal-dialog" style="width: 900px">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title"> بيانات الأصناف</h4>
            </div>
            <form class="form-horizontal" id="class_from" method="post" action="<?=base_url('stores/class/create')?>" role="form" novalidate="novalidate">
                <div class="modal-body inline_form">

                    <div class="form-group">
                        <label class="col-sm-2 control-label">  الصنف الأب  </label>
                        <div class="col-sm-3">
                            <input type="number"  name="class_parent_id" readonly id="txt_class_parent_id" class="form-control ltr">
                        </div>
                        <div class="col-sm-6">
                            <input type="text"  name="class_parent_id_name_ar" readonly id="txt_class_parent_id_name_ar" class="form-control" >
                        </div>
                        </div>

                        <div class="form-group">
                        <label class="col-sm-2 control-label"> رقم الصنف </label>
                        <div class="col-sm-2">
                            <input type="number" name="class_id" readonly id="txt_class_id" class="form-control ltr">
                        </div>

                        </div>

                       <div class="form-group">
                        <label class="col-sm-4 control-label"> اسم الصنف باللغة الانجليزية </label>
                        <div class="col-sm-8">
                            <input data-val-regex-pattern="\w+" data-val="false" data-val-required="حقل مطلوب" name="class_name_en" id="txt_class_name_en" class="form-control" data-val-regex="ادخل حروف انجليزية  !!" dir="ltr" type="text">
                            <span class="field-validation-valid" data-valmsg-for="class_name_en" data-valmsg-replace="true"></span>
                        </div>

                    </div>

                    <div class="form-group">
                        <label class="col-sm-4 control-label"> اسم الصنف باللغة العربية </label>
                        <div class="col-sm-8">
                            <input type="text" data-val="true"  data-val-required="حقل مطلوب" name="class_name_ar" id="txt_class_name_ar" class="form-control" dir="rtl">
                            <span class="field-validation-valid" data-valmsg-for="class_name_ar" data-valmsg-replace="true"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-4 control-label"> وصف الصنف </label>
                        <div class="col-sm-8">
                            <input type="text" data-val="false"  data-val-required="حقل مطلوب" name="calss_description" id="txt_calss_description" class="form-control">
                            <span class="field-validation-valid" data-valmsg-for="calss_description" data-valmsg-replace="true"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label"> نوع الصنف </label>
                        <div class="col-sm-3">
                            <select name="calss_type" id="dp_calss_type" class="form-control" onchange="setTypes();">
                                    <?php foreach($calss_type as $row) :?>
                                    <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <label class="col-sm-3  control-label"> وحدة الصنف </label>
                        <div class="col-sm-3 ">
                            <select name="class_unit" id="dp_class_unit" class="form-control">
                                <option></option>
                                <?php foreach($class_unit as $row) :?>
                                    <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        </div>
                    <div class="form-group">

                        <label class="col-sm-3  control-label">  الوحدة الفرعية </label>
                        <div class="col-sm-3 ">
                            <select name="class_unit_sub" id="dp_class_unit_sub" class="form-control">
                                <option></option>
                                <?php foreach($class_unit_sub as $row) :?>
                                    <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <label class="col-sm-3 control-label">الكمية بالنسبة للوحدة الفرعية</label>
                        <div class="col-sm-3">
                            <input type="number" value="1" data-val="true"  data-val-required="حقل مطلوب" name="class_unit_count" id="txt_class_unit_count" class="form-control">
                            <span class="field-validation-valid" data-valmsg-for="class_unit_count" data-valmsg-replace="true"></span>
                        </div>
               </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label"> الحد الأدنى </label>
                        <div class="col-sm-3">
                            <input type="number" data-val="true"  data-val-required="حقل مطلوب" name="class_min" id="txt_class_min" class="form-control">
                            <span class="field-validation-valid" data-valmsg-for="class_min" data-valmsg-replace="true"></span>
                        </div>

                        <label class="col-sm-3  control-label">  الحد الأقصى </label>
                        <div class="col-sm-3">
                            <input type="number" data-val="true"  data-val-required="حقل مطلوب" name="class_max" id="txt_class_max" class="form-control">
                            <span class="field-validation-valid" data-valmsg-for="class_max" data-valmsg-replace="true"></span>
                        </div>
                      </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">  سياسة خروج الصنف </label>
                        <div class="col-sm-3">
                            <select name="class_outdoor" id="dp_class_outdoor" class="form-control">
                                <option></option>
                                <?php foreach($class_outdoor as $row) :?>
                                    <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>


                        <label class="col-sm-3 control-label"> نوع حساب تداول الصنف</label>
                        <div class="col-sm-3">
                            <select name="class_acount_type" id="dp_class_acount_type" class="form-control">
                                <?php foreach($class_acount_type as $row) :?>
                                    <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                <?php endforeach; ?>

                            </select>
                        </div>
                     </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">نوع الحساب</label>
                        <div class="col-sm-3">
                            <select name="account_type" id="dp_account_type" class="form-control">
                                <option value="1">رئيسي</option>
                                <option value="2">فرعي</option>
                            </select>
                        </div>


                            <div class="form-group">
                     <label class="col-sm-3 control-label">   تبعية الصنف في شجرة الحساب </label>
                        <div >
                            <input type="text" name="account_id" id="h_txt_account_id" />
                            <input type="text"  readonly  data-val="false" readonly data-val-required="حقل مطلوب" class="form-control" id="txt_account_id" />

                            <span class="field-validation-valid" data-valmsg-for="account_id" data-valmsg-replace="true"></span>
                        </div>
                                </div>
                         </div>

                    <hr>
                    <div class="form-group">
                        <label class="col-sm-3 control-label"> سعر الشراء</label>
                        <div class="col-sm-3">
                            <input type="number" data-val="false"  data-val-required="حقل مطلوب" name="class_purchasing" id="txt_class_purchasing" class="form-control">
                            <span class="field-validation-valid" data-valmsg-for="class_purchasing" data-valmsg-replace="true"></span>
                        </div>

                        <label class="col-sm-3 control-label"> سعر البيع</label>
                        <div class="col-sm-3">
                            <input type="number" data-val="true"  data-val-required="حقل مطلوب" name="class_payment" id="txt_class_payment" class="form-control">
                            <span class="field-validation-valid" data-valmsg-for="class_payment" data-valmsg-replace="true"></span>
                        </div>

                        <label class="col-sm-3 control-label">العملة</label>
                        <div class="col-sm-3">
                            <select name="curr_id" id="dp_curr_id" class="form-control">
                                   <?php foreach($currency as $row) :?>
                                    <option value="<?= $row['CURR_ID'] ?>"><?= $row['CURR_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <label class="col-sm-3 control-label"> بركود</label>
                        <div class="col-sm-3">
                            <input type="text" data-val="false"  data-val-required="حقل مطلوب" name="code" id="txt_code" class="form-control">
                            <span class="field-validation-valid" data-valmsg-for="code" data-valmsg-replace="true"></span>
                        </div>

                        <label class="col-sm-3 control-label">الرقم السابق للصنف</label>
                        <div class="col-sm-3">
                            <input type="text" data-val="true"  data-val-required="حقل مطلوب" name="old_class_id" id="txt_old_class_id" class="form-control">
                            <span class="field-validation-valid" data-valmsg-for="old_class_id" data-valmsg-replace="true"></span>
                        </div>

                        <label class="col-sm-3 control-label"> مسؤولية صرف الصنف </label>
                        <div class="col-sm-3">
                            <select name="responsible_in_out" id="dp_responsible_in_out" class="form-control">
                                   <?php foreach($responsible_in_out as $row) :?>
                                    <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                    <label class="col-sm-3 control-label">حساب المصروف</label>
                    <div>
                        <input type="text"  name="exp_account_cust" id="h_txt_exp_account_cust" />
                        <input type="text"   data-val="false" readonly data-val-required="required" class="form-control" id="txt_exp_account_cust" />

                                      </div>
                </div>
                    <hr>
                    <div class="form-group">
                        <label class="col-sm-3 control-label"> نوع الإهلاك</label>
                        <div class="col-sm-3">
                            <select data-val="true"  data-val-required="required"  name="destruction_type" id="dp_destruction_type" class="form-control">
                                <?php foreach($destruction_type as $row) :?>
                                    <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                            <span class="field-validation-valid" data-valmsg-for="destruction_type" data-valmsg-replace="true"></span>

                        </div>

                        <label class="col-sm-3 control-label">نسبة الإهلاك  <B>%</B></label>
                        <div class="col-sm-3">

                            <input type="number"  name="destruction_percent" id="txt_destruction_percent" class="form-control">
                                   </div>

                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">حساب الإهلاك</label>
                        <div>
                            <input type="text"  name="destruction_account_id" id="h_txt_destruction_account_id" />
                            <input type="text"   data-val="false" readonly data-val-required="required" class="form-control" id="txt_destruction_account_id" />

                        </div>
                    </div>
                    <hr>
                    <div class="form-group">
                        <label class="col-sm-3 control-label"> تاريخ الإدخال</label>
                        <div class="col-sm-3">
                            <input type="date"   readonly name="entry_date" id="txt_entry_date" class="form-control">
                               </div>
                        <label class="col-sm-3 control-label">  اسم المدخل</label>
                        <div class="col-sm-3">
                            <input type="text"  name="username" id="txt_username" class="form-control">
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="submit"   data-action="submit" class="btn btn-primary">حفظ البيانات</button>


                    <button type="button" class="btn btn-default" data-dismiss="modal">إغلاق</button>

                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal   EXP_ACCOUNT_CUST-->

<?php


$scripts = <<<SCRIPT


<script>
function validate(){
if ($('#dp_calss_type').val()==1){

if ($('#h_txt_account_id').val()==''){
alert ('يجب اختيار حساب تبعية الصنف');
return true;
}else {return false;}
}else {
return false ;}
}
    $(function () {

        $('#classModal').on('shown.bs.modal', function () {

            $('#txt_class_name_en').focus();
        })
 $('#txt_account_id').click(function(e){

            _showReport('$select_accounts_url/'+$(this).attr('id')+'/-1/1|2|3|4|5/0' );

    });
  $('#txt_exp_account_cust').click(function(e){

            _showReport('$select_accounts_url/'+$(this).attr('id')+'/-1/5/0' );

    });
  $('#txt_destruction_account_id').click(function(e){

            _showReport('$select_accounts_url/'+$(this).attr('id')+'/-1/5/0' );

    });
        $('#class').tree();
    });

function setTypes(){
if ($('#dp_calss_type').val()==2){
$('#dp_class_unit').val('');
$('#dp_class_outdoor').val('');
$('#txt_class_min').val('0');
$('#txt_class_max').val('0');
$('#txt_class_purchasing').val('0');
$('#txt_class_payment').val('0');
$('#dp_curr_id').val('');

}
}
    $('input[name="account_id"]').keyup(function(){
            get_account_name($(this));
        });
        $('input[name="account_id"]').change(function(){
            get_account_name($(this));
        });
            function get_account_name(obj){

            if($(obj).val().length >6  || $(obj).val().match("^60")){
                get_data('{$get_id_url}',{id:$(obj).val()},function(data){

                    if((data.length > 0) && (data[0].CURR_ID==$('#dp_curr_id').val())){
$('#txt_account_id').val(data[0].ACOUNT_NAME+' ('+data[0].CURR_NAME+')');
                    }else{
  $(obj).val('');
  $('#txt_account_id').val('');
                    }
                });
            }
    }


              $('input[name="exp_account_cust"]').keyup(function(){
            get_account_name_m($(this));
        });
        $('input[name="exp_account_cust"]').change(function(){
            get_account_name_m($(this));
        });

              function get_account_name_m(obj){

            if($(obj).val().length >6  || $(obj).val().match("^60")){
                get_data('{$get_id_url}',{id:$(obj).val()},function(data){

                    if((data.length > 0) && (data[0].CURR_ID==$('#dp_curr_id').val())){
$('#txt_exp_account_cust').val(data[0].ACOUNT_NAME+' ('+data[0].CURR_NAME+')');
                    }else{
  $(obj).val('');
  $('#txt_exp_account_cust').val('');
                    }
                });
            }
    }


  $('input[name="destruction_account_id"]').keyup(function(){
            get_account_name_d($(this));
        });
        $('input[name="destruction_account_id"]').change(function(){
            get_account_name_d($(this));
        });

              function get_account_name_d(obj){

            if($(obj).val().length >6  || $(obj).val().match("^60")){
                get_data('{$get_id_url}',{id:$(obj).val()},function(data){

                    if((data.length > 0) && (data[0].CURR_ID==$('#dp_curr_id').val())){
$('#txt_destruction_account_id').val(data[0].ACOUNT_NAME+' ('+data[0].CURR_NAME+')');
                    }else{
  $(obj).val('');
  $('#txt_destruction_account_id').val('');
                    }
                });
            }
    }
     function class_create(){

        clearForm($('#class_from'));

        if($(".tree span.selected").length <= 0 || $.fn.tree.level() >= 5 || $.fn.tree.selected().attr('data-classtype')==1){

            if(($.fn.tree.level() >= 5) ) {
  warning_msg('تحذير','غير مسموح بإدراج صنف جديد');

            }else{

                warning_msg('تحذير','غير مسموح بإدراج صنف جديد');
            }


            return;
        }





        var parentId =$.fn.tree.selected().attr('data-id');
        var productionId= $.fn.tree.lastElem().attr('data-id');
        var parentName = $('.tree li > span.selected').text();

        $('#txt_class_parent_id').val(parentId);
      $('#txt_class_id').val(class_id($.fn.tree.level(),productionId,parentId));
        $('#txt_class_parent_id_name_ar').val(parentName);



        $('#class_from').attr('action','{$create_url}');
        $('#classModal').modal();

    }

    function class_delete(){

        if(confirm('هل تريد حذف الصنف؟')){
            var elem =$.fn.tree.selected();
            var id = elem.attr('data-id');
            var url = '{$delete_url}';

            ajax_delete(url,id,function(data){
                if(data == '1'){

                    $.fn.tree.removeElem(elem);
                    success_msg('رسالة','تم حذف السجلات بنجاح ..');
                }else {
                  danger_msg('تحذير..',' رقم الصنف المراد حذفه مستخدم  ... لم يتم الحذف ');
                }
            });
        }

    }


    function class_get(id){

        get_data('{$get_url}',{id:id},function(data){


            $.each(data, function(i,item){

                $('#txt_class_parent_id').val(item.CLASS_PARENT_ID);
               $('#txt_class_id').val( item.CLASS_ID);
               $('#txt_class_parent_id_name_ar').val($.fn.tree.nodeText(item.CLASS_PARENT_ID));
                $('#txt_class_name_ar').val(item.CLASS_NAME_AR);
                  $('#txt_class_name_en').val(item.CLASS_NAME_EN);
                  $('#txt_calss_description').val(item.CALSS_DESCRIPTION);
                  $('#dp_calss_type').val(item.CALSS_TYPE);
                  $('#dp_class_unit').val(item.CLASS_UNIT);
                $('#txt_class_min').val(item.CLASS_MIN);
                 $('#txt_class_max').val(item.CLASS_MAX);
                  $('#txt_class_outdoor').val(item.CLASS_OUTDOOR);
                 $('#h_txt_account_id').val(item.ACCOUNT_ID);
                    $('#txt_account_id').val( item.ACCOUNT_ID_NAME);
                   $('#txt_class_purchasing').val(item.CLASS_PURCHASING);
                   $('#txt_class_payment').val(item.CLASS_PAYMENT);
                $('#txt_code').val(item.CODE);
                  $('#txt_old_class_id').val(item.OLD_CLASS_ID);
                $('#dp_class_unit_sub').val(item.CLASS_UNIT_SUB);
                 $('#txt_class_unit_count').val(item.CLASS_UNIT_COUNT);

                $('#dp_curr_id').val(item.CURR_ID);
                  $('#dp_responsible_in_out').val(item.RESPONSIBLE_IN_OUT);
                   $('#dp_class_acount_type').val(item.CLASS_ACOUNT_TYPE);
                $('#dp_account_type').val(item.ACCOUNT_TYPE);
                  $('#h_txt_exp_account_cust').val(item.EXP_ACCOUNT_CUST);
                    $('#txt_exp_account_cust').val( item.EXP_ACCOUNT_CUST_NAME);
 $('#dp_destruction_type').val(item.DESTRUCTION_TYPE);
   $('#txt_destruction_percent').val(item.DESTRUCTION_PERCENT);

                    $('#h_txt_destruction_account_id').val(item.DESTRUCTION_ACCOUNT_ID);
                    $('#txt_destruction_account_id').val( item.DESTRUCTION_ACCOUNT_ID_NAME);


                      $('#txt_username').val( item.USERNAME);
                       $('#txt_entry_date').val( item.ENTRY_DATE);


                $('#class_from').attr('action','{$edit_url}');

                $('#classModal').modal();

            });
        });
    }




    $('button[data-action="submit"]').click(function(e){

        e.preventDefault();

        if(validate())
        return;

        var form = $(this).closest('form');

        var isCreate = form.attr('action').indexOf('create') >= 0;

        ajax_insert_update(form,function(data){


            if(isCreate){

                var obj = data;

                $.fn.tree.add(obj.id+' : '+form.find('input[name="class_name_ar"]').val(),obj.id,"javascript:class_get('"+obj.id+"');");


            }else{

            var obj = data;
            if(obj.msg == 1){
                $.fn.tree.selected().attr('data-classtype',obj.CALSS_TYPE) ;
                $.fn.tree.update($.fn.tree.selected().attr('data-id')+' : '+form.find('input[name="class_name_ar"]').val());
             }}


            $('#classModal').modal('hide');

            success_msg('رسالة','تم حفظ البيانات بنجاح ..');
        },"json");

    });

</script>
SCRIPT;

sec_scripts($scripts);



?>

