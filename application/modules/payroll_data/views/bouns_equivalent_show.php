<?php
/**
 * Created by PhpStorm.
 * User: telbawab
 * Date: 09/02/2020
 * Time: 12:45 م
 */
$MODULE_NAME= 'payroll_data';
$TB_NAME= 'bouns_equivalent';
$back_url=base_url("$MODULE_NAME/$TB_NAME/index");
$create_url =base_url("$MODULE_NAME/$TB_NAME/create");
$post_url= base_url("$MODULE_NAME/$TB_NAME/".($action == 'index'?'create':$action));
$get_url= base_url("$MODULE_NAME/$TB_NAME/get");
$date_attr= "data-type='date' data-date-format='DD/MM/YYYY' data-val='true' data-val-regex-pattern='".date_format_exp()."' data-val-regex='Error' ";
$get_emp_data =base_url("$MODULE_NAME/$TB_NAME/public_get_emp_data");
$edit_award_Grouptb_url= base_url("$MODULE_NAME/$TB_NAME/public_edit_award_Grouptb");

$isCreate =isset($master_tb_data) && count($master_tb_data)  > 0 ?false:true;
$HaveRs = (!$isCreate)? true:false;
$rs=$isCreate ?array() : $master_tb_data[0];
$transfer_comm_url=base_url("$MODULE_NAME/$TB_NAME/transfer_comm");
$back_comm_url =base_url("$MODULE_NAME/$TB_NAME/back_comm_url");
$agree_ma=isset($rs['AGREE_MA'])? $rs['AGREE_MA'] :'';
$ser=isset($rs['SER'])? $rs['SER'] :'';
?>
<div class="row">
    <div class="toolbar">
        <div class="caption"><?= $title ?></div>
        <ul>
            <?php if (HaveAccess($create_url)): ?>
                <li><a href="<?= $create_url ?>"><i class="glyphicon glyphicon-plus"></i>جديد</a> </li>
            <?php endif; ?>
        </ul>
    </div>
</div>
<div class="form-body">
    <div id="container">
        <form class="form-horizontal" id="<?= $TB_NAME ?>_form" method="post" action="<?php echo $post_url ?>" role="form" novalidate="novalidate">

            <div class="modal-body inline_form">

                <fieldset class="field_set">
                    <legend ><?=$title.(($HaveRs)?' - '.$rs['EMP_NAME']:'')?></legend>
                    <div class="form-group">
                        <label class="col-sm-1 control-label">رقم الطلب</label>
                        <div class="col-sm-2">
                            <input type="text" value="<?=$HaveRs?$rs['SER']:''?>" readonly id="txt_ser" class="form-control" />
                            <?php if (( isset($can_edit)?$can_edit:false)) : ?>
                                <input type="hidden" value="<?=$HaveRs?$rs['SER']:''?>" name="ser" id="h_ser" />
                            <?php endif; ?>
                        </div>
                     <label class="col-sm-1 control-label">رقم الموظف </label>
                        <div class="col-sm-2">
                            <select name="emp_no" id="dp_emp_no" class="form-control sel2" >
                                <option value=""></option>
                                <?php foreach ($emp_no_cons as $row) : ?>
                                    <option <?=$HaveRs?($rs['EMP_NO']==$row['EMP_NO']?'selected':''):''?> value="<?=$row['EMP_NO']?>" >
                                        <?= $row['EMP_NO'].":".$row['EMP_NAME']?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <label class="col-sm-1 control-label">الشهر</label>
                        <div class="col-sm-2">
                            <input type="text" placeholder="الشهر"
                                   name="month"
                                   id="txt_month" class="form-control" value="<?= date('Ym') ?>" >
                        </div>

                        <label class="col-sm-1 control-label">نوع المكافأة </label>
                        <div class="col-sm-2">
                            <select name="type_reward" id="dp_type_reward" class="form-control  sel2 >
                                <option value=""></option>
                                <?php foreach ($type_reward as $row) : ?>
                                    <option <?=$HaveRs?($rs['TYPE_REWARD']==$row['CON_NO']?'selected':''):''?> value="<?=$row['CON_NO']?>" >
                                        <?= $row['CON_NAME']?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                    </div> <!--END FIRST ROW-->

                    <div class="form-group"> <!--START SECOND ROW-->

                        <label class="col-sm-1 control-label">قيمة المكافأة </label>
                        <div class="col-sm-2">
                            <input type="number"
                                   name="value_ma"
                                   id="txt_value_ma"
                                   value="<?= $HaveRs ? $rs['VALUE_MA'] : "" ?>" data-val="true"
                                   class="form-control ">
                        </div>

                        <label class="col-sm-1 control-label">ملاحظة المدخل </label>
                        <div class="col-sm-2">
                            <input type="text"
                                   name="note"
                                   id="txt_note"
                                   value="<?= $HaveRs ? $rs['NOTE'] : "" ?>" data-val="true"
                                   class="form-control ">
                        </div>

                        <label class="col-sm-1 control-label">نوع اللجنة </label>
                        <div class="col-sm-2">
                            <select name="committee_no" style="width: 250px"
                                    id="dp_committee_no" data-val="true" data-val-required="حقل مطلوب" class="form-control ">
                                <option></option>
                                <?php foreach ($class_input_class_type as $row) : ?>
                                    <option <?=$HaveRs?($rs['COMMITTEE_NO']==$row['COMMITTEES_ID']?'selected':''):''?> value="<?=$row['COMMITTEES_ID']?>" >
                                        <?= $row['COMMITTEES_NAME']?></option>
                                <?php endforeach; ?>
                            </select>
                            <span class="field-validation-valid" data-valmsg-for="committee_no"
                                  data-valmsg-replace="true"></span>
                        </div>
                    </div>  <!--END SECOND ROW-->
                    <div class="form-group">
                        <label class="col-sm-1 control-label">الراتب الأساسي </label>
                        <div class="col-sm-2">
                            <input type="text" id="txt_b_salary"
                                   value="<?= $HaveRs ? $rs['B_SALARY'] : "" ?>" data-val="true"
                                   class="form-control" readonly>
                        </div>

                        <label class="col-sm-1 control-label">المقر </label>
                        <div class="col-sm-2">
                            <input type="text" id="txt_branch_name" name="emp_branch"
                                   value="<?= $HaveRs ? $rs['BRANCH_NAME'] : "" ?>" data-val="true"
                                   class="form-control" readonly>
                        </div>

                        <label class="col-sm-1 control-label">الادارة </label>
                        <div class="col-sm-2">
                            <input type="text" id="txt_head_department_name"
                                   value="<?= $HaveRs ? $rs['HEAD_DEPARTMENT_NAME'] : "" ?>" data-val="true"
                                   class="form-control" readonly>
                        </div>

                        <label class="col-sm-1 control-label">نوع التعين </label>
                        <div class="col-sm-2">
                            <input type="text" id="txt_emp_type_name"
                                   value="<?= $HaveRs ? $rs['EMP_TYPE_NAME'] : "" ?>" data-val="true"
                                   class="form-control" readonly>
                        </div>


                    </div>

                      <div class="form-group">

                          <label class="col-sm-1 control-label">طبيعة العمل </label>
                          <div class="col-sm-2">
                              <input type="text" id="txt_w_no_name"
                                     value="<?= $HaveRs ? $rs['W_NO_NAME'] : "" ?>" data-val="true"
                                     class="form-control" readonly>
                          </div>

                          <label class="col-sm-1 control-label"> المسمى الوظيفي </label>
                          <div class="col-sm-2">
                              <input type="text" id="txt_w_no_admin_name"
                                     value="<?= $HaveRs ? $rs['W_NO_ADMIN_NAME'] : "" ?>" data-val="true"
                                     class="form-control" readonly>
                          </div>
                      </div>
                    <div class="form-group">
                        <label class="col-sm-1 control-label"> حالة الطلب من قبل اللجنة </label>
                        <div class="col-sm-2">
                            <input type="text"
                                   value="<?= $HaveRs ? $rs['COMMITTEE_CASE_NAME'] : "" ?>" data-val="true"
                                   class="form-control" readonly>
                        </div>


                        <label class="col-sm-1 control-label"> ملاحظة  اللجنة </label>
                        <div class="col-sm-8">
                            <input type="text"
                                   value="<?= $HaveRs ? $rs['COMMITTEE_NOTE'] : "" ?>" data-val="true"
                                   class="form-control" readonly>
                        </div>
                    </div>

                    <fieldset class="field_set">
                        <legend >الاعمال الخاصة بطلب المكافأة</legend>
                        <div class="form-group">

                            <label class="col-sm-1 control-label">الاعمال</label>
                            <div class="col-sm-8">
                                <input type="text"
                                       name="work_detail"
                                       id="txt_work_detail"
                                       value="<?= $HaveRs ? $rs['WORK_DETAIL'] : "" ?>" data-val="true"
                                       class="form-control">
                            </div>
                        </div>
                    </fieldset>



                    <?php if ( ($action == "edit") && $agree_ma >= 2 ) : ?>

                        <fieldset class="field_set">
                            <legend>رأي اللجنة النهائي بعد الاطلاع </legend>
                            <div class="form-group">
                                <label class="col-sm-1 control-label">الحالة </label>
                                <div class="col-sm-2">
                                    <select name="committee_case" id="dp_committee_case" class="form-control sel2" >
                                        <option value=""></option>
                                        <?php foreach ($committee_case as $row) : ?>
                                            <option <?=$HaveRs?($rs['COMMITTEE_CASE']==$row['CON_NO']?'selected':''):''?> value="<?=$row['CON_NO']?>" >
                                                <?= $row['CON_NAME']?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <label class="col-sm-1 control-label">ملاحظة اللجنة</label>
                                <div class="col-sm-8">
                                    <input type="text"
                                           name="committee_note"
                                           id="txt_committee_note"
                                           value="<?= $HaveRs ? $rs['COMMITTEE_NOTE'] : "" ?>" data-val="true"
                                           class="form-control">
                                </div>
                            </div>
                        </fieldset>
                        <hr/>
                        <fieldset  >
                            <legend>بيانات الأعضاء</legend>
                            <div style="clear: both" id="classes"> <!-- <input type="hidden" id="h_data_search" />-->
                                <div style="..."   >
                                    <?php
                                    echo modules::run("$MODULE_NAME/$TB_NAME/public_get_group_receipt",$ser);
                                    // echo modules::run('stores/receipt_class_input/public_get_group_details',0);
                                    ?>

                                </div>
                            </div>
                        </fieldset>
                <?php endif; ?>

                    <hr/>
                    <div class="modal-footer">
                        <?php if ( HaveAccess($transfer_comm_url) && $agree_ma == 1) : ?>
                            <button type="button"  onclick="transfer_to_comm();" class="btn btn-warning">
                                <i class="si si-reload"></i>
                                تحويل الى اللجنة
                            </button>
                        <?php endif; ?>

                        <?php if ( HaveAccess($post_url) &&  ($isCreate || ( $rs['AGREE_MA']==1 and isset($can_edit)?$can_edit:false) )  ) : ?>
                            <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                        <?php endif; ?>
                    </div>

                </fieldset>

        </form>
    </div>
</div>
<?php
$scripts = <<<SCRIPT
<script type="text/javascript">
    var count = 0;
    var count1 = 0;

     count1 = $('input[name="h_group_ser[]"]').length;
     $('#group_tb').prop('hidden',true);
 
     
     $('.sel2:not("[id^=\'s2\']")').select2();

    var table = 'bouns_equivalent';
  
     $('#txt_month').datetimepicker({
            format: 'YYYYMM',
            minViewMode: 'months',
            pickTime: false,
            
        });
 
  $('#dp_emp_no').change(function(){
        get_emp_data(0)
     });    
  
    function get_emp_data(){
        var no =  $('#dp_emp_no').val();
        if (no == '') {
            return -1;
        } else
             get_data('{$get_emp_data}', {no: no}, function (data) {
                 $.each(data, function (i, value) {
                // console.log(option.NAME);
                $('#txt_b_salary').val(value.B_SALARY);
                $('#txt_branch_name').val(value.BRANCH_NAME);
                $('#txt_head_department_name').val(value.HEAD_DEPARTMENT_NAME);
                $('#txt_emp_type_name').val(value.EMP_TYPE_NAME);
                $('#txt_w_no_name').val(value.W_NO_NAME);
                $('#txt_w_no_admin_name').val(value.W_NO_ADMIN_NAME);
                  });
             });
     }
        
    
    function transfer_to_comm() {
        var ser =  $('input[name="ser"]').val();
        var committee_no =  $('select[name="committee_no"]').val();
         if (committee_no == '') {
                danger_msg('يرجى ادخال الطلب المحول الى اللجنة');
            }else{
             get_data('{$transfer_comm_url}',{ser: ser,committee_no:committee_no}, function (data) {
            //console.log(data);
          if(data==1){
                success_msg('رسالة','تم تحويل الطلب الى اللجنة المعتمدة بنجاح  ..');
                get_to_link(window.location.href);
            }else{
                danger_msg('تحذير..',data);
            }
          }, 'html');
              
          }
    } //end function
    
   
   function addRowGroup(){
//if($('input:text',$('#receipt_class_input_groupTbl')).filter(function() { return this.value == ""; }).length <= 0){
    var html ='<tr><td >'+( count1+1)+' <input type="hidden" value="0" name="h_group_ser[]" id="h_group_ser_'+count1+'>"  class="form-control col-sm-3"> </td>'+
    '<td><input type="text" name="emp_no[]" data-val="true"  id="emp_no_'+count1+'"  class="form-control col-sm-8"> </td>'+
     '<td> <input type="text" name="group_person_id[]" data-val="true"   id="group_person_id_'+count1+'"  class="form-control col-sm-8">  </td>'+
      '<td><input type="text" name="group_person_date[]"  data-val="true"   id="group_person_date_'+count1+'>"   class="form-control">  </td>'+
     '<td><input type="text" name="member_note[]"  data-val="true"   id="member_note_'+count1+'>"   class="form-control">  </td>'+
       '<td><input type="checkbox" name="status['+count1+']" checked      id="status_'+count1+'"   class="form-control"></td></tr>';
    $('#receipt_class_input_groupTbl tbody').append(html);
     count1 = count1+1;
//}
            }
            
            
            
            
            
    function addGroup(obj){
        
            
            var tbl = '#suppliers_offers_detTbl';
            var container = $('#' + $(tbl).attr('data-container'));
            var form = $('#bouns_equivalent_form');
            $(form).attr('action','{$edit_award_Grouptb_url}');
            ajax_insert_update(form,function(data){
                if(data==1){
                    success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                    get_to_link(window.location.href);
                }else{
                    danger_msg('تحذير..',data);
                }
            },"html");
       
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
    
 
</script>
SCRIPT;
sec_scripts($scripts);
?>
