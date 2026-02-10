<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

$MODULE_NAME = 'purchases';
$TB_NAME = 'Receipt_logistical_contracts';
$items_recept = 'public_items_recept';
$group_recept = 'public_group_recept';
$post_url = base_url("$MODULE_NAME/$TB_NAME/" . ($action == 'index' ? 'create' : $action));
$adopt_url = base_url("$MODULE_NAME/$TB_NAME/adopt_");
$back_url = base_url("$MODULE_NAME/$TB_NAME");
$get_url = base_url("$MODULE_NAME/$TB_NAME/get");
$record_member_url = base_url("$MODULE_NAME/$TB_NAME/record_record_member");
$committee_emails_url = base_url("$MODULE_NAME/$TB_NAME/public_committee_emails");
$committee_emails = modules::run("$committee_emails_url", $this->uri->segment(4));
$SUB = $committee_emails['SUB'];
$TXT = $committee_emails['TXT'];
$EMAIL = $committee_emails['EMAIL'];
$USERNAME = $committee_emails['USERNAME'];
$adopt_recept_url = base_url("$MODULE_NAME/$TB_NAME/adopt_reacept");
$cancel_adopt_recept_url = base_url("$MODULE_NAME/$TB_NAME/cancel_reacept");
$unadopt_commitee_recept_url = base_url("$MODULE_NAME/$TB_NAME/unadopt_commitee_recept");
$select_orders_url = base_url("purchases/orders/public_index_modify_");
$details_logistic_items_url = base_url("$MODULE_NAME/$TB_NAME/$items_recept");
$select_accounts_url = base_url('financial/accounts/public_select_accounts');
$customer_url = base_url('payment/customers/public_index');
$project_accounts_url = base_url('projects/projects/public_select_project_accounts');
$details_group_url = base_url("$MODULE_NAME/$TB_NAME/$group_recept");
$gfc_domain = gh_gfc_domain();
$isCreate = isset($result) && count($result) > 0 ? false : true;
$HaveRs = (!$isCreate) ? true : false;
$rs = $isCreate ? array() : $result[0];
$ser = $HaveRs ? $rs['RECEIPT_CLASS_INPUT_ID'] : '';
echo AntiForgeryToken();


?>


    <div class="row">
        <div class="toolbar">

            <div class="caption"><?= $title ?></div>
            <ul>
                <?php //HaveAccess($back_url)
                if (TRUE): ?>
                    <li><a href="<?= $back_url ?>"><i class="icon icon-reply"></i> </a>
                    </li><?php endif; ?>
                <li><a onclick="<?= $help ?>" href="javascript:" class="help"><i class="icon icon-question-circle"></i></a>
                </li>
            </ul>


        </div>
    </div>
    <div class="form-body">

        <div id="msg_container"></div>
        <div id="container">

            <form class="form-vertical" id="<?= $TB_NAME ?>_form" method="post" action="<?= $post_url ?>" role="form"
                  novalidate="novalidate">
                <div class="modal-body inline_form">
                    <fieldset>
                        <legend>بيانات محضر الفحص و الاستلام</legend>
                        <div class="row">
                            <div class="form-group col-sm-2">
                                <label class="control-label">رقم محضر الفحص و الاستلام</label>
                                <div>
                                    <input type="text" value="<?= $HaveRs ? $rs['RECEIPT_CLASS_INPUT_ID'] : '' ?>"
                                           name="receipt_class_input_id"
                                           id="txt_receipt_class_input_id" class="form-control" readonly/>
                                </div>
                            </div>
                            <div class="form-group col-sm-2">
                                <label class="control-label">رقم أمر التوريد (s)</label>
                                <div>

                                    <input type="text" value="<?= $HaveRs ? $rs['ORDER_ID_TEXT'] : '' ?>"
                                           data-val="true" data-val-required="حقل مطلوب"
                                           name="order_id"
                                           id="txt_order_id" class="form-control" readonly/>

                                    <input type="hidden" name="order_id_ser"
                                           value="<?= $HaveRs ? $rs['ORDER_ID'] : '' ?>"
                                           id="txt_order_id_ser" class="form-control" dir="rtl" readonly>
                                    <span class="field-validation-valid" data-valmsg-for="order_id"
                                          data-valmsg-replace="true"></span>
                                </div>
                            </div>
                            <div class="form-group col-sm-2">
                                <label class="control-label">رقم أمر التوريد (الفعلي)</label>
                                <div>
                                    <input type="text" data-val="true" data-val-required="حقل مطلوب"
                                           name="real_order_id"
                                           value="<?= $HaveRs ? $rs['REAL_ORDER_ID'] : '' ?>"
                                           id="txt_real_order_id" class="form-control" dir="rtl" readonly>
                                    <span class="field-validation-valid" data-valmsg-for="real_order_id"
                                          data-valmsg-replace="true"></span>
                                </div>
                            </div>
                            <div class="form-group col-sm-2">
                                <label class="control-label">المورد</label>
                                <div>
                                    <input name="customer_name" data-val="true" data-val-required="حقل مطلوب"
                                           class="form-control" readonly id="txt_customer_name"
                                           value="<?= $HaveRs ? $rs['CUSTOMER_ID_NAME'] : '' ?>">
                                    <input type="hidden" name="customer_resource_id" id="h_txt_customer_name"
                                           value="<?= $HaveRs ? $rs['CUSTOMER_RESOURCE_ID'] : '' ?>">
                                    <span class="field-validation-valid" data-valmsg-for="customer_name"
                                          data-valmsg-replace="true"></span>
                                </div>
                            </div>
                            <div class="form-group col-sm-2">
                                <label class="control-label">تصنيف الأعمال</label>
                                <div>
                                    <select name="class_type" id="dp_class_type" class="form-control sel2">
                                        <?php foreach ($class_type as $row) : ?>
                                        <?php if($row['CON_NO']==1){
                                            ?>
                                            <option <?= $HaveRs ? ($rs['CLASS_TYPE'] == $row['CON_NO'] ? 'selected' : '') : '' ?>
                                                value="<?= $row['CON_NO'] ?>"><?= $row['CON_NO'] . ': ' . $row['CON_NAME'] ?></option>
                                            <?php }?>
                                        <?php endforeach; ?>

                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-sm-2">
                                <label class="control-label">تاريخ اعتماد المحضر</label>
                                <div>
                                    <input type="text" value="<?= $HaveRs ? $rs['ADOPT_DATE'] : '' ?>"
                                           name="adopt_date"
                                           id="txt_adopt_date" class="form-control" readonly/>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-3">
                                <label class="control-label">قائمة الملاحظات </label>
                                <div>
                                    <select name="note_list" id="dp_note_list" class="form-control sel2">
                                        <?php foreach ($record_declaration_list as $row) : ?>
                                            <option <?= $HaveRs ? ($rs['NOTE_LIST'] == $row['CON_NO'] ? 'selected' : '') : '' ?>
                                                value="<?= $row['CON_NO'] ?>"><?= $row['CON_NO'] . ': ' . $row['CON_NAME'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-sm-3">
                                <label class="control-label">نوع اللجنة</label>
                                <div>
                                    <select name="committees_id" id="dp_committees_id" class="form-control sel2">
                                        <option>اختر اللجنة</option>
                                        <?php foreach ($class_input_class_type as $row) : ?>
                                            <option <?= $HaveRs ? ($rs['COMMITTEES_ID'] == $row['COMMITTEES_ID'] ? 'selected' : '') : '' ?>
                                                value="<?= $row['COMMITTEES_ID'] ?>"><?= $row['COMMITTEES_ID'] . ': ' . $row['COMMITTEES_NAME'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <?php
                    if ($HaveRs) {
                        ?>
                        <hr/>
                        <fieldset>
                            <legend>المرفقات</legend>
                            <?php
                            echo modules::run('attachments/attachment/index', $rs['RECEIPT_CLASS_INPUT_ID'], 'RECEIPT_LOGISTIC_CONTRACTS_TB');
                            ?>

                        </fieldset>
                        <?php
                    }
                    ?>
                    <hr/>

                    <fieldset>
                        <legend> بيانات المواد</legend>
                        <div style="clear: both" id="classes">
                            <input type="hidden" id="h_data_search"/>
                            <?php
                            echo modules::run("$MODULE_NAME/$TB_NAME/$items_recept", $HaveRs ? $rs['RECEIPT_CLASS_INPUT_ID'] : 0);
                            ?>
                    </fieldset>
                    <hr/>
                    <fieldset>
                        <legend>بيانات الأعضاء</legend>
                        <div style="clear: both" id="groups">
                            <?php
                            echo modules::run("$MODULE_NAME/$TB_NAME/$group_recept", $HaveRs ? $rs['RECEIPT_CLASS_INPUT_ID'] : 0);
                            ?>
                        </div>

                    </fieldset>


                </div>

                <div class="modal-footer">
                    <?php
                    if ((HaveAccess($post_url) && ($isCreate || ($rs['IS_ADOPT'] == 0) && ($rs['ADOPT'] == 1)))) {
                        ?>
                        <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                        <?php
                    }
                    if ((HaveAccess($adopt_recept_url) && (!$isCreate && ($rs['ALL_EMP_ADOPT'] == 0) && ($rs['ADOPT'] == 1)))) {
                        ?>
                        <button type='button' id='Head_Commmite_btn'
                                class='btn btn-success' data-dismiss='modal'>إعتماد رئيس اللجنة
                        </button>
                        <?php
                    }
                    if ((HaveAccess($unadopt_commitee_recept_url) && (!$isCreate && ($rs['IS_ADOPT'] > 0)) && ($rs['ADOPT'] == 1 || $rs['ADOPT']==10))) {
                        ?>
                        <button type='button' id='unadopt_commitee_recept_btn'  class='btn btn-danger' data-dismiss='modal'>إلغاء اعتماد اللجنة
                        </button>
                        <?php
                    }
                    if ((HaveAccess($cancel_adopt_recept_url) && (!$isCreate && ($rs['IS_ADOPT'] == 0)) && ($rs['ADOPT'] == 1))) {

                        ?>
                        <button type='button' id='cancle_commmite_btn'
                                class='btn btn-danger' data-dismiss='modal'>إلغاء المحضر
                        </button>
                        <?php
                    }?>
                    <?php if (HaveAccess($adopt_url . '30') and  $rs['ADOPT'] == 20) : ?>
                        <button type="button" id="btn_adopt_30" onclick="adopt_(30);"
                                class="btn btn-success btn_adopt_class">اعتماد المدير العام
                        </button>
                    <?php endif; ?>

                    <?php if (HaveAccess($adopt_url . '_20') and !$isCreate and $rs['ADOPT'] >= 20 and $ser != "") : ?>
                        <button type="button" id="btn_adopt__20" onclick='adopt_("_20");'
                                class="btn btn-danger btn_adopt_class">
                            ارجاع للمعد
                        </button>
                    <?php endif; ?>



                </div>

            </form>
        </div>
    </div>

<?php
$scripts = <<<SCRIPT

<script type="text/javascript">
     
     $(document).ready(function() {

 
           //////////////////////////////////////////////////////////////////
 $('#unadopt_commitee_recept_btn').click(function(){


    if(confirm('هل تريد إتمام العملية ؟')){

 var tbl = '#{$TB_NAME}_tb';
    var container = $('#' + $(tbl).attr('data-container'));
    var form =  $(this).closest('form');
    $(form).attr('action','{$unadopt_commitee_recept_url}');
    ajax_insert_update(form,function(data){
   if(data==1){
            success_msg('رسالة','تم حفظ البيانات بنجاح ..');
            get_to_link(window.location.href);
        }else{
            danger_msg('تحذير..',data);
        }
    },"html");

}
    });
         //////////////////////////////////////////////////////////////////
$('#Head_Commmite_btn').click(function(){


    if(confirm('هل تريد إتمام العملية ؟')){

 var tbl = '#{$TB_NAME}_tb';
    var container = $('#' + $(tbl).attr('data-container'));
    var form =  $(this).closest('form');
    $(form).attr('action','{$adopt_recept_url}');
    ajax_insert_update(form,function(data){
   if(data==1){
            success_msg('رسالة','تم حفظ البيانات بنجاح ..');
            get_to_link(window.location.href);
        }else{
            danger_msg('تحذير..',data);
        }
    },"html");

}
    }); 
        //////////////////////////////////////////////////////////////////
$('#cancle_commmite_btn').click(function(){
    
 if(confirm('هل تريد إتمام العملية ؟')){

 var tbl = '#{$TB_NAME}_tb';
    var container = $('#' + $(tbl).attr('data-container'));
    var form =  $(this).closest('form');
    $(form).attr('action','{$cancel_adopt_recept_url}');
    ajax_insert_update(form,function(data){
   if(data==1){
            success_msg('رسالة','تم حفظ البيانات بنجاح ..');
            get_to_link(window.location.href);
        }else{
            danger_msg('تحذير..',data);
        }
    },"html");

}
    });     
////////////////////////////////////////////////    
  });
        // do your stuff here


     
     $('.sel2').select2(); 
     count1 = $('input[name="h_group_ser[]"]').length+1;
     //////////////////////////////////////////////////////////////////////////
       //  reBind(1);
    ///////////////////////////////////////////////////////////////////////////
     $('#txt_order_id').dblclick(function(e){
         
         _showReport('$select_orders_url' );
       $('txt_order_id_ser').trigger('change');

       
     });
        
    //////////////////////////////////////////////////////////////////////////// 
      function setDefaultCustomerAccount(){
            var order_id=$('#txt_order_id_ser').val();
            var class_type=$('#dp_class_type').val();             
              get_data('{$details_logistic_items_url}',{order_id:order_id,class_type:class_type},function(data){
                $('#classes').html(data);
                //reBind();
              },'html');
      }
     ////////////////////////////////////////////////////////////////////////////
     $('#dp_committees_id').select2().on('change',function(){
         
        var committees_id=$(this).val();
        
        get_data('{$details_group_url}',{committees_id:committees_id},function(data){
            $('#groups').html(data);
            //reBind();
        },'html');
        
     });
   ////////////////////////////////////////////////////////////////////////////////////
 
 $('#dp_class_type').select2().on('change',function(){
      
      setDefaultCustomerAccount();
        
     });
 /////////////////////////////////////////////////////////////////////////////////////////////
 function addRowGroup(){
//if($('input:text',$('#receipt_class_input_groupTbl')).filter(function() { return this.value == ""; }).length <= 0){

    count1 = $('input[name="h_group_ser[]"]').length+1;
    var html ='<tr><td >'+( count1)+' <input type="hidden" value="0" name="h_group_ser[]" id="h_group_ser_'+count1+'>"  class="form-control col-sm-3"> </td>'+
    '<td><input type="text" name="emp_no[]" data-val="true"  id="emp_no_'+count1+'"  class="form-control col-sm-8"> </td>'+
     '<td> <input type="text" name="group_person_id[]" data-val="true"   id="group_person_id_'+count1+'"  class="form-control col-sm-8">  </td>'+
      '<td><input type="text" name="group_person_date[]"  data-val="true"   id="group_person_date_'+count1+'>"   class="form-control">  </td>'+
     '<td><input type="text" name="member_note[]"  data-val="true"   id="member_note_'+count1+'>"   class="form-control">  </td>'+
      '<td><input type="checkbox" name="status['+count1+']" checked  value="1" data-val="true"   id="status_'+count1+'>"   class="form-control">  </td><td></td><td></td><td></td></tr>';

  if($('#status_'+count1).is(':checked')) $(this).val(1);
  else  $(this).val(2);

    $('#receipt_class_input_groupTbl tbody').append(html);
  count1 = count1+1;
//}
}
///////////////////////////////////////////////////////////////////////////////////////////////////
    $('button[data-action="submit"]').click(function(e){

        e.preventDefault();
        var tbl = '#{$TB_NAME}_tb';
        var container = $('#' + $(tbl).attr('data-container'));
        var form = $(this).closest('form');
        var amount;
        var customer_type;
        var customer_accounts_name;
        var customer_accounts_id;
        var item_recipt_no;
        var emp_no;
        var group_person_id;
        var group_person_date;
        var class_type=$('#dp_class_type').val();
        var error=1;
        $('input[name="amount[]"]').each(function (i) {

           
           amount=$(this).closest('tr').find('input[name="amount[]"]').val();
           customer_type=$(this).closest('tr').find('select[name="customer_type[]"]').val();
           customer_accounts_name=$(this).closest('tr').find('input[name="customer_accounts_name[]"]').val();
           customer_accounts_id=$(this).closest('tr').find('input[name="customer_accounts_id[]"]').val();
           item_recipt_no=$(this).closest('tr').find('input[name="item_recipt_no[]"]').val();
          
                 
           
   

                if (amount == '' || amount < 0){
                 danger_msg('يجب ادخال الكمية المستلمة!!','');
                 error=0;
                }
                 
                  
                /*else if (amount > 0) {
                    if (customer_type == '' || customer_type == 0)
                    {
                    danger_msg('اختر نوع المستفيد!!','');  
                    error=0;
                    }
                                        
                    else if (customer_accounts_name == '' && customer_accounts_name == '')
                    {
                     danger_msg('اختر اسم المستفيد!!',''); 
                     error=0;
                    }
                   
                    else if (class_type == 3) {
                        if (item_recipt_no == '' || item_recipt_no < 0)
                        {
                        danger_msg('!!أدخل الإرسالية ',''); 
                        error=0;
                        }
                        
                      }
                } else if (amount == 0) {
                    
                    if (customer_type != '' || customer_type != 0)
                    {
                    danger_msg('الكمية المستلمة صفر و بالتالي لا يتوجب عليك اختيار نوع الحساب!!',''); 
                    error=0;
                    }
                        
         
                    else if (customer_accounts_name != '')
                    {
                    danger_msg('!!لكمية المستلمة صفر و بالتالي لا يتوجب عليك ادخال اسم المستفيد!!',''); 
                   
                    }
                     
                     
                    if (item_recipt_no != '')
                    {
                    danger_msg('!!لكمية المستلمة صفر و بالتالي لا يتوجب عليك ادخال الإرسالية ','');
                    error=0;
                    }
                          
               
                }*/
            





    });
                $('input[name="emp_no[]"]').each(function (i) {

           
           emp_no=$(this).closest('tr').find('input[name="emp_no[]"]').val();
           group_person_id=$(this).closest('tr').find('input[name="group_person_id[]"]').val();
           group_person_date=$(this).closest('tr').find('input[name="group_person_date[]"]').val();
                        
             if (emp_no == '' || emp_no <= 0)
             {
             danger_msg('يجب ادخال الرقم الوظيفي!!','');
             error=0;
             }
                
    
                else if (group_person_id == '')
                {
                danger_msg('يجب ادخال رقم الهوية!!','');
                error=0;
                }
                 
        
                else if (group_person_date == '')
                {
                danger_msg('!!أدخل اسم العضو ','');
                error=0;
                }
                
              
    });
        
     
if(error){
       ajax_insert_update(form,function(data){

            if(parseInt(data)>=1){
                success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                get_to_link('{$get_url}/'+parseInt(data));
            }else{
                danger_msg('تحذير..',data);
            }

        },"html");
        }
    });
////////////////////
   var btn___= '';
 $('.btn_recordd1').click( function(){
      btn___ = $(this);
 });     
///////////////////////////////////////////////////////////////////////////////////////////////////////////
function {$TB_NAME}_memebers_record(obj){
 if(confirm('هل تريد إتمام العملية ؟')){ 
    var tbl = '#{$TB_NAME}_tb';
    var container = $('#' + $(tbl).attr('data-container'));
    var form =  $(obj).closest('form');
    $(form).attr('action','{$record_member_url}');
        ajax_insert_update(form,function(data){
                if(data==1){
            success_msg('رسالة','تم حفظ البيانات بنجاح ..');
            $('button').attr('disabled','disabled');
            var sub= '{$SUB}';
            var text= '{$TXT}';
           
            text+= '<br>للاطلاع افتح الرابط';
            text+= ' <br>{$gfc_domain}{$get_url}/{$ser}';
            _send_mail(btn___,'{$EMAIL}',sub,text);
           btn___ = ''; 
          get_to_link(window.location.href);
       }else{
          danger_msg('تحذير..',data);
        }
 
    },"html");
 }
}
 reBind(1);


    
    function reBind(s) {
        if (s == undefined)
            s = 0;

        $('input[name="customer_accounts_name[]').bind("click", function (e) {
            var _type = $(this).closest('tr').find('select[name="customer_type[]"]').val();
            selectAccount($(this), _type);
        });
        
           $('select[name="customer_type[]').bind("change", function (e) {
            
           $(this).closest('tr').find('input[name="customer_accounts_name[]"]').val('');          
        });


    }

    function selectAccount(obj, _type) {


        var select_accounts_url = $('#select_accounts_url').val();
        var customer_url = $('#customer_url').val();
        var project_accounts_url = $('#project_accounts_url').val();

        if (_type == 1)
            _showReport(select_accounts_url + '/' + $(obj).attr('id'));
        if (_type == 2)
            _showReport(customer_url + '/' + $(obj).attr('id') + '/');
        if (_type == 3)
            _showReport(project_accounts_url + '/' + $(obj).attr('id') + '/');
        if (_type == 5)
            _showReport(customer_url + '/' + $(obj).attr('id') + '/4');
    }
     function adopt_(no){
       
        var msg= 'هل تريد الاعتماد ؟!';
      
        if(no==0) msg= '!!هل تريد بالتأكيد الغاء المستخلص؟لا يمكن التراجع عن هذه العملية';
        if(no=='_20' || no=='_30' || no=='_40') msg= 'هل تريد بالتأكيد الغاء الاعتماد؟!';
        
        if(confirm(msg)){
            var values= {ser: "{$ser}"};
            
            get_data('{$adopt_url}'+no, values, function(ret){
                if(!isNaN(ret)){
                    success_msg('رسالة','تمت العملية بنجاح..');
                    $('button').attr('disabled','disabled');
                    
                    setTimeout(function(){
                        if(no=='_20' || no=='_30' || no=='_40')
                            get_to_link(window.location.href);
                         else
                        get_to_link(window.location.href);
                    },1000);
                }else{
                    danger_msg('تحذير..',ret);
                }
            }, 'html');
        }
    }
    </script>
SCRIPT;
sec_scripts($scripts);
?>
