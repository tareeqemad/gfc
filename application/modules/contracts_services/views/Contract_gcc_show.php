<?php
/**
 * Created by PhpStorm.
 * User: telbawab
 * Date: 21/03/2020
 * Time: 11:14 ص
 */
$MODULE_NAME = 'contracts_services';
$TB_NAME = 'Contract_gcc';
$back_url = base_url("$MODULE_NAME/$TB_NAME");
$post_url = base_url("$MODULE_NAME/$TB_NAME/" . ($action == 'index' ? 'create' : $action));
$create_url = base_url("$MODULE_NAME/$TB_NAME/create");
$get_url = base_url("$MODULE_NAME/$TB_NAME/get");
$isCreate = isset($master_tb_data) && count($master_tb_data) > 0 ? false : true;
$HaveRs = (!$isCreate) ? true : false;
$rs = $isCreate ? array() : $master_tb_data[0];
$date_attr = "data-type='date' data-date-format='DD/MM/YYYY' data-val='true' data-val-regex-pattern='" . date_format_exp() . "' data-val-regex='Error' ";
$adopt_url = base_url("$MODULE_NAME/$TB_NAME/adopt_");
$adopt_detail = base_url("$MODULE_NAME/$TB_NAME/public_get_adopt_detail");


$gcc_no_ = $HaveRs ? $rs['GCC_NO'] : "";
$contract_no_ = $HaveRs ? $rs['CONTRACT_NO'] : "";
$contract_pro_ = $HaveRs ? $rs['CONTRACT_PRO'] : "";
$get_data = base_url("$MODULE_NAME/$TB_NAME/public_get_data_contracts");
$get_id_url = base_url("$MODULE_NAME/$TB_NAME/public_get_data_id");
//CONTRACT_SER_PARENT_TB
?>
<style>
    .custom-lbl {
        color: red;
    }

    .cust-form {
        width: 45%;
    }
</style>
<div class="row">
    <div class="toolbar">
        <div class="caption"><?= $title ?></div>
        <ul>
            <?php if (HaveAccess($create_url)): ?>
                <li><a href="<?= $create_url ?>"><i class="glyphicon glyphicon-plus"></i>جديد</a></li>
            <?php endif; ?>
        </ul>
    </div>
</div>
<div class="form-body">
    <div id="container">
        <form class="form-horizontal" id="<?= $TB_NAME ?>_form" method="post" action="<?php echo $post_url ?>"
              role="form" novalidate="novalidate">

            <div class="modal-body inline_form">

                <fieldset class="field_set">
                    <legend>اضافة تعاقد</legend>
                    <div class="form-group">


                        <label class="col-sm-1 control-label">رقم التعاقد</label>
                        <div class="col-sm-2">
                            <input type="text" readonly name="ser" value="<?= $HaveRs ? $rs['SER'] : "" ?>" id="txt_ser"
                                   class="form-control">
                        </div>

                        <input type="hidden" readonly name="branch_id" value="<?= $this->user->branch ?>"
                               id="txt_branch_no" class="form-control">

                        <label class="col-sm-1 control-label">الجهة الطالبة </label>
                        <div class="col-sm-2">
                            <select name="gcc_no" id="dp_gcc_no" class="form-control sel2">
                                <option value="">_________</option>
                                <?php foreach ($gcc_id_parent as $row) : ?>
                                    <option <?= $HaveRs ? ($rs['GCC_NO'] == $row['GCC_ST_NO'] ? 'selected' : '') : '' ?>
                                            value="<?= $row['GCC_ID'] ?>"><?= $row['CONTRACT_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>


                        <label class="col-sm-1 control-label"> الاجراء</label>
                        <div class="col-sm-2">
                            <select name="contract_pro" id="dp_contract_pro" class="form-control">
                                <option value=""></option>
                            </select>
                        </div>


                        <label class="col-sm-1 control-label"> التعاقد</label>
                        <div class="col-sm-2">
                            <select name="contract_no" id="dp_contract_name" class="form-control">
                                <option value=""></option>
                            </select>
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="col-sm-1 control-label">المورد </label>
                        <div class="col-sm-2">
                            <select name="customer_id" id="dp_cust_id" class="form-control sel2">
                                <option value="">_________</option>
                                <?php foreach ($customers as $row) : ?>
                                    <option <?= $HaveRs ? ($rs['CUSTOMER_ID'] == $row['CUSTOMER_ID'] ? 'selected' : '') : '' ?>
                                            value="<?= $row['CUSTOMER_ID'] ?>"><?= $row['CUSTOMER_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>


                        <label class="col-sm-1 control-label"> تاريخ بداية التعاقد </label>
                        <div class="col-sm-2">
                            <input type="text" <?= $date_attr ?> value="<?= $HaveRs ? $rs['CONTRACT_START'] : "" ?>"
                                   name="contract_start" id="txt_contract_start" class="form-control">
                        </div>


                        <label class="col-sm-1 control-label"> تاريخ نهاية التعاقد </label>
                        <div class="col-sm-2">
                            <input type="text" <?= $date_attr ?> value="<?= $HaveRs ? $rs['CONTRACT_END'] : "" ?>"
                                   name="contract_end" id="txt_contract_end" class="form-control">
                        </div>


                        <label class="col-sm-1 control-label custom-lbl"> المدة بالأيام </label>
                        <div class="col-sm-1">
                            <input type="text" name="duration_day" id="txt_duration_day" class="form-control cust-form"
                                   value="<?= $HaveRs ? $rs['DURATION_DAY'] : "" ?>" readonly>
                        </div>

                    </div>

                    <div class="form-group">
                        <label class="col-sm-1 control-label"> تفاصيل الاجراء </label>
                        <div class="col-sm-2">
                            <textarea class="form-control" rows="6" id="det_contract" readonly>

                            </textarea>
                        </div>

                        <label class="col-sm-1 control-label">ملاحظة </label>
                        <div class="col-sm-2">
                            <textarea class="form-control" rows="6" id="txt_notes" name="notes">
                                <?= $HaveRs ? $rs['NOTES'] : "" ?>
                            </textarea>
                        </div>

                        <?php if (HaveAccess($adopt_url . '2') and !$isCreate and $rs['ADOPT'] == 1) : ?>
                            <div class="form-group col-sm-3">
                                <label class="control-label">ملاحظة الاعتماد </label>
                                <div>
                                    <input type="text" name="adopt_note" id="txt_adopt_note" class="form-control"/>
                                </div>
                            </div>
                        <?php endif; ?>

                    </div>
                    <input type="hidden" id="txt_today" value="<?php echo date("d/m/Y") ?>">

                    <?php if ($action == "edit")
                        : ?>
                        <label id="remain_lbl_remeinder" class="custom-lbl">المدة المتبقية على انتهاء العقد هي </label>
                        <label id="remain_lbl" class="custom-lbl"></label> <label class="custom-lbl">يوم</label>
                    <?php endif; ?>

                    <fieldset class="field_set">
                        <legend>بيانات التعاقد</legend>
                        <div class="form-group">
                            <?php echo modules::run("$MODULE_NAME/$TB_NAME/public_get_detail", (($HaveRs ? count($rs) : 0) > 0) ? @$rs['SER'] : 0); ?>
                        </div>
                    </fieldset>

                    <div class="modal-footer">
                        <?php
                        //تجديد التعاقد
                        if (HaveAccess($post_url))  : ?>
                            <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                        <?php endif; ?>
                        <?php if (HaveAccess($adopt_url . '2') and !$isCreate and $rs['ADOPT'] == 1) : ?>
                            <button type="button" onclick='javascript:adopt_(2);' class="btn btn-success">اعتماد
                            </button>
                        <?php endif; ?>

                        <?php if (@$rs['ADOPT'] > 1) { ?>
                            <button type="button" onclick="show_detail_row(<?= $HaveRs ? $rs['SER'] : "" ?>);"
                                    class="btn btn-warning">بيانات الاعتماد
                            </button>
                        <?php } ?>

                    </div>
                </fieldset>
            </div>
        </form>
    </div>
</div>


<!--Modal Bootstrap بيانات الاعتماد-->
<!-- Modal -->
<div class="modal fade bd-example-modal-lg" id="exampleModal" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">بيانات الاعتماد</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="col-md-12">
                    <div class="row">
                        <div class="tbl_container <?= $TB_NAME; ?>_Detail_adopt"
                        ">
                        <table class="table">
                            <thead class="bg-primary">
                            <tr>
                                <th>#</th>
                                <th>الجهة المعتمدة</th>
                                <th>اسم المعتمد</th>
                                <th>تاريخ الاعتماد</th>
                                <th>ملاحظة الاعتماد</th>
                            </tr>
                            </thead>
                            <tbody id="append_data">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-dismiss="modal">اغلاق</button>
        </div>
    </div>
</div>
<!--Modal Bootstrap -->

<?php
$scripts = <<<SCRIPT
<script>



$(document).ready(function() {

    
    $('.sel2:not("[id^=\'s2\']")').select2();
    
    
    $('textarea').each(function(){
            $(this).val($(this).val().trim());
        }
    );
    get_remain_day();
      //الادارة
     var gcc_no_val= '{$gcc_no_}';
    $('#dp_gcc_no').select2('val',gcc_no_val);
    change_gcc_no(gcc_no_val);
    var contract_pro_val= '{$contract_pro_}';
    change_contract_no(contract_pro_val);
    get_contract_detail(contract_pro_val);
    
    
    
    var contract_no_= '{$contract_no_}';
    
        $('select[name="contract_no"]').attr('readonly','readonly');
        $('select[name="contract_pro"]').attr('readonly','readonly');
  // $('select[name="program_no"]').attr('readonly','readonly');

//Calculate Duration Time between TWO DATES ;
  $('#txt_contract_end').change(function(){
    var  t1= $("#txt_contract_start").val();
    var t2= $("#txt_contract_end").val();
   //Total time for one day
    var one_day=1000*60*60*24;  //Here we need to split the inputed dates to convert them into standard format for further execution
    var x=t1.split("/");     
    var y=t2.split("/");   //date format(Fullyear,month,date) 
    
    var date1=new Date(x[2],(x[1]-1),x[0]);
    
    // it is not coded by me,but it works correctly,it may be useful to all
    
    var date2=new Date(y[2],(y[1]-1),y[0]);
    
    var month1=x[1]-1;
    var month2=y[1]-1;
    
    //Calculate difference between the two dates, and convert to days
    
    _Diff=Math.ceil((date2.getTime()-date1.getTime())/(one_day));
    //console.log(_Diff);
    $('#txt_duration_day').val(_Diff);
 
}); // end change function



    function get_remain_day(){
    var t1= $("#txt_today").val();
    var t2= $("#txt_contract_end").val();
   //Total time for one day
    var one_day=1000*60*60*24;  //Here we need to split the inputed dates to convert them into standard format for further execution
    var x=t1.split("/");     
    var y=t2.split("/");   //date format(Fullyear,month,date) 
    
    var date1=new Date(x[2],(x[1]-1),x[0]);
    
    // it is not coded by me,but it works correctly,it may be useful to all
    
    var date2=new Date(y[2],(y[1]-1),y[0]);
    
    var month1=x[1]-1;
    var month2=y[1]-1;
    
    //Calculate difference between the two dates, and convert to days
    
    _Diff=Math.ceil((date2.getTime()-date1.getTime())/(one_day));
    //console.log(_Diff);
    $('#remain_lbl').text(_Diff);
 
    }


  
  
  
$('#dp_gcc_no').change(function(){
        change_gcc_no(0)
    })  ;  

$('#dp_contract_pro').change(function(){
  var data_id = this.value ;
  get_contract_detail(data_id);
  change_contract_no(0)
    });  //end function on change

 
    //جلب اجراءات - التعاقدات الادارة
    function change_gcc_no(set_val){
            $('#det_contract').val('');
             $('select[name="contract_pro"]').empty();
             $('select[name="contract_pro"]').removeAttr('readonly');
             var gcc_id =  $('#dp_gcc_no').val();
            if (gcc_id == '') {
                return -1;
            } else
             get_data('{$get_data}', {gcc_id: gcc_id}, function (data) {
                 $('select[name="contract_pro"]').append($('<option/>').attr("value", 0).text('_______'));
                 $.each(data, function (i, option) {
                     var options = '';
                     options += '<option  value="' + option.GCC_ID + '">' + option.CONTRACT_NAME + '</option>';
                     $('select[name="contract_pro"]').append(options);
                 });
                 if(set_val){
                    $('#dp_contract_pro').val(contract_pro_val);
                    //if(sub_no_!=''){
                     //  change_target_no(1);
                   // }
                 }
             });
         }
       
         
  //جلب اجراءات - التعاقدات الادارة
    function change_contract_no(set_val){
             $('select[name="contract_no"]').empty();
             $('select[name="contract_no"]').removeAttr('readonly');
             var gcc_id =  $('#dp_contract_no').val();
            if (gcc_id == '') {
                return -1;
            } else
             get_data('{$get_data}', {gcc_id: gcc_id}, function (data) {
               
                 $('select[name="contract_name"]').append($('<option/>').attr("value", 0).text('_______'));
                 $.each(data, function (i, option) {
                     var options = '';
                     options += '<option value="' + option.GCC_ID + '">' + option.CONTRACT_NAME + '</option>';
                     $('select[name="contract_name"]').append(options);
                 });
                 if(set_val){
                    $('#dp_contract_no').val(contract_name_);
                    //if(sub_no_!=''){
                     //  change_target_no(1);
                   // }
                 }
             });
         }
         
         function get_contract_detail(data_id){
         if (data_id == '') {
                return -1;
            } else
          get_data('{$get_id_url}', {data_id: data_id}, function (data) {
              $('#det_contract').val(data[0].CONTRACT_DETAIL);   
           
              }); //end get data function
              
         }
         
         
      $('button[data-action="submit"]').click(function(e){
    e.preventDefault();
    var msg= 'هل تريد حفظ الطلب ؟!';
    if(confirm(msg)){
        $(this).attr('disabled','disabled');
        var form = $(this).closest('form');
        ajax_insert_update(form,function(data){
            if(parseInt(data)>1){
                success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                get_to_link('{$get_url}/'+parseInt(data));
            }else if(data==1){
                success_msg('رسالة','تم تعديل البيانات بنجاح ..');
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

  
           
   });//END JQ TAGS
   
   
     function adopt_(no){
        var msg= 'هل تريد الاعتماد ؟!';
        if(confirm(msg)){
            var values= {ser: "{$rs['SER']}" , adopt_note: $('#txt_adopt_note').val() };
            get_data('{$adopt_url}'+no, values, function(ret){
                if(ret==1){
                    success_msg('رسالة','تم اعتماد الطلب بنجاح ..');
                    $('button').attr('disabled','disabled');
                 
                    setTimeout(function(){
                        get_to_link(window.location.href);
                    },2000);
                }else{
                    danger_msg('تحذير..',ret);
                }
            }, 'html');
        }
    }
      var table = 'contract_gcc';
     function show_detail_row(ser) {
        $('#exampleModal').modal('show');
          var html='';
         get_data('{$adopt_detail}', {ser:ser} , function(ret){
             //alert(ret);
         $('.'+table+'_Detail_adopt table tbody tr').remove();
         count1 = 1;
            var note_adopt = '';
         console.log(ret);
         $(ret).each(function(key, value) {
         // if (value.NOTE == null) {
           //   note_adopt ='';
         // }
         html = html+'<tr><td>'+count1+'</td><td>'+value.ADOPT_NAME+'</td><td>'+value.ADOPT_USER_NAME+'</td><td>'+value.ADOPT_DATE+'</td><td>'+value.NOTE+'</td></tr>';
          count1 = count1 + 1;

         });
         $('#append_data').append(html);
        });
    }
    
 
</script>
SCRIPT;
sec_scripts($scripts);
?>








