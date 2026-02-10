<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 04/12/17
 * Time: 11:34 ص
 */

$MODULE_NAME= 'issues';
$TB_NAME= 'gedco_issues';
$DET_TB_NAME3='public_defendant_gedco_action_details';
$DET_TB_NAME2='public_get_request_details';
$DET_TB_NAME1='public_get_gedco_action_details';
$create_url= base_url("$MODULE_NAME/$TB_NAME/create");
$back_url=base_url("$MODULE_NAME/$TB_NAME");
$post_url= base_url("$MODULE_NAME/$TB_NAME/".($action == 'index'?'create':$action));
$get_url=base_url("$MODULE_NAME/$TB_NAME/get_issue_info");
$adopt_url= base_url("$MODULE_NAME/$TB_NAME/adopt");
$un_adopt_url= base_url("$MODULE_NAME/$TB_NAME/unadopt");
$rs=($isCreate)? array(): (count($issues_data) > 0 ? $issues_data[0] : array()) ;
$gedco_branch_issuess= modules::run("issues/issues/public_get_court_b", (count($rs)>0)?$rs['ISSUE_BRANCH']:$this->user->branch);
$edit_url =base_url("$MODULE_NAME/$TB_NAME/edit");
echo AntiForgeryToken();

?>

<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>

        <ul>
            <?php if( HaveAccess($create_url)):  ?><li><a  href="<?= $create_url ?>"><i class="glyphicon glyphicon-plus"></i>جديد</a> </li><?php endif; ?>
            <li><a  href="<?= $back_url ?>"><i class="icon icon-reply"></i> </a> </li>
            <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>
        </ul>

    </div>
</div>
<div class="form-body">
    <div id="msg_container"></div>
    <div id="container">
 <form class="form-vertical" id="<?=$TB_NAME?>_form" method="post" action="<?=$post_url?>" role="form" novalidate="novalidate">
     <div class="modal-body inline_form">
         <input type="hidden" value="<?php echo @$rs['SER'];?>" name="ser" id="txt_ser">
         <fieldset>
             <legend >بيانات المُدعى</legend>
             <div class="form-group">
                 <label class="col-sm-1 control-label">اسم المدعي</label>
                 <div class="col-sm-2">
                     <input type="text" data-val="true" value="<?php echo @$rs['NAME'] ;?>"   placeholder="اسم المدعي"  data-val-required="حقل مطلوب" name="name" id="txt_name" class="form-control">
                     <span class="field-validation-valid" data-valmsg-for="name" data-valmsg-replace="true"></span>
                 </div>

                 <label class="col-sm-1 control-label">عنوانه</label>
                 <div class="col-sm-2">
                     <input  type="text" data-val="true" value="<?php echo @$rs['ADDRESS'] ;?>"   placeholder="عنوانه"   name="address" id="txt_address" data-val-required="حقل مطلوب"  class="form-control">
                     <span class="field-validation-valid" data-valmsg-for="address" data-valmsg-replace="true"></span>
                 </div>

                 <label class="col-sm-1 control-label">وكيله</label>
                 <div class="col-sm-2">
                     <input  type="text" data-val="true" value="<?php echo @$rs['AGENT'] ;?>"   placeholder="وكيله"   name="agent" id="txt_agent" class="form-control">
                     <span class="field-validation-valid" data-valmsg-for="agent" data-valmsg-replace="true"></span>
                 </div>
                 <?php
                 if($this->user->branch==1)
                 {
                 ?>
                 <label class="col-sm-1 control-label">المحافظة</label>
                 <div class="col-sm-1">

                     <select name="branch" id="dp_branch" class="form-control">
                         <?php foreach($branches_all as $row) :?>
                             <?php
                             if($row['NO']<>1)
                             {
                                 ?>

                                 <option value="<?= $row['NO'] ?>" <?PHP if ($row['NO']==((count($rs)>0)?$rs['BRANCH']:0)){ echo " selected"; } ?> >
                                     <?= $row['NAME'] ?>
                                 </option>
                             <?php
                             }
                             ?>

                         <?php endforeach; ?>
                     </select>
                     <?php
                     }
                     ?>
                     </div>


                 </div>

             </div>

             </fieldset>
         <!-------------------------------------------------بيانات المدعى عليه------------------------------------------->
         <fieldset  class="field_set">
             <legend >بيانات المُدعى عليه</legend>
             <div class="details" >
                 <?php echo modules::run("$MODULE_NAME/$TB_NAME/$DET_TB_NAME3", (count(@$rs)>0)?@$rs['SER']:0,((count(@$rs)>0)? @$rs['ADOPT'] : '' )); ?>

             </div>


         </fieldset>

         <hr/>
         <!-------------------------------------------------بيانات القضية------------------------------------------->
         <fieldset  class="field_set">
             <legend >بيانات الدعوى</legend>
             <div class="form-group">

                 <label class="col-sm-1 control-label">اسم المحكمة</label>
                 <div class="col-sm-2">
                     <select name="court_name" id="dp_court_name" class="form-control">

                         <?php foreach($gedco_branch_issuess as $row) :?>
                             <option  value="<?= $row['CON_NO'] ?>" <?PHP if ($row['CON_NO']==((count($rs)>0)?$rs['COURT_NAME']:0)){ echo " selected"; } ?> ><?php echo $row['CON_NAME']  ?></option>
                         <?php endforeach; ?>
                     </select>
                 </div>

                 <div class="col-sm-1">

                     <input type="text" data-val="true" value="<?php echo @$rs['ISSUE_NO'] ;?>"  placeholder="رقم الدعوى"   data-val-required="حقل مطلوب" name="issue_no" id="txt_issue_no" class="form-control">
                     <span class="field-validation-valid" data-valmsg-for="issue_no" data-valmsg-replace="true"></span>
                 </div>

                 <div class="col-sm-1">
                     <input type="text" data-val="true" value="<?php echo @$rs['ISSUE_YEAR'] ;?>"   placeholder="سنة الدعوى"  data-val-required="حقل مطلوب" name="issue_year" id="txt_issue_year" class="form-control">
                     <span class="field-validation-valid" data-valmsg-for="issue_year" data-valmsg-replace="true"></span>
                 </div>
                 <label class="col-sm-1 control-label">نوع الدعوى</label>
                 <div class="col-sm-2">
                     <input type="text" data-val="true" value="<?php echo @$rs['ISSUE_TYPE'] ;?>"  placeholder="نوع الدعوى"  data-val-required="حقل مطلوب" name="issue_type" id="txt_issue_type" class="form-control">
                     <span class="field-validation-valid" data-valmsg-for="issue_type" data-valmsg-replace="true"></span>

                 </div>
                 <label class="col-sm-1 control-label">قيمة الدعوى</label>
                 <div class="col-sm-1">
                     <input type="text" data-val="true" value="<?php echo @$rs['ISSUE_VALUE'] ;?>"  placeholder="قيمة الدعوى"  data-val-required="حقل مطلوب" name="issue_value" id="txt_issue_value" class="form-control">
                     <span class="field-validation-valid" data-valmsg-for="issue_value" data-valmsg-replace="true"></span>

                 </div>
                 <label class="col-sm-1 control-label">العملة</label>
                 <div class="col-sm-1">
                     <select name="currency" id="dp_currency" class="form-control">

                         <?php foreach($currency as $row) :?>
                             <option  value="<?= $row['CON_NO'] ?>" <?PHP if ($row['CON_NO']==((count($rs)>0)?$rs['CURRENCY']:0)){ echo " selected"; } ?> ><?php echo $row['CON_NAME']  ?></option>
                         <?php endforeach; ?>
                     </select>

                 </div>
             </div>






         </fieldset>
         <hr/>
         <!-------------------------------------------الطلبات-------------------------------------------------->


         <fieldset  class="field_set">
             <legend>الطلبات</legend>

             <div class="details" >

                 <?php echo modules::run("$MODULE_NAME/$TB_NAME/$DET_TB_NAME2", (count(@$rs)>0)?@$rs['SER']:0,((count(@$rs)>0)? @$rs['ADOPT'] : '' )); ?>


             </div>

         </fieldset>

         <hr/>
         <!-------------------------------------------------اجراءات المحكمة------------------------------------------->
         <fieldset  class="field_set">
             <legend >اجراءات المحكمة</legend>

             <div  class="details" >
                 <?php echo modules::run("$MODULE_NAME/$TB_NAME/$DET_TB_NAME1", (count(@$rs)>0)?@$rs['SER']:0,((count(@$rs)>0)? @$rs['ADOPT'] : '' )); ?>

             </div>

         </fieldset>

         <hr/>
     <!-------------------------------------------------بيانات القضية------------------------------------------->
     <fieldset>

         <legend > حالة الدعوى</legend>
         <div class="form-group">

             <label class="col-sm-1 control-label"> حالة الدعوى</label>
             <div class="col-sm-2">
                 <select name="endedstatus" id="dp_endedstatus" class="form-control">

                     <?php foreach($endedstatus as $row) :?>
                         <option  value="<?= $row['CON_NO'] ?>" <?PHP if ($row['CON_NO']==((count($rs)>0)?$rs['STATUS']:0)){ echo " selected"; } ?> ><?php echo $row['CON_NAME']  ?></option>
                     <?php endforeach; ?>
                 </select>
             </div>






             </div>







     </fieldset>
     <hr/>
         <!-------------------------------------------------ملاحظات على القضية------------------------------------------->
         <fieldset  class="field_set">
             <legend >ملاحظات على القضية</legend>
             <div class="form-group">
                 <label class="col-sm-1 control-label">ملاحظات</label>


                 <div class="col-sm-8">
                     <textarea class="form-control" name="issues_notes"   id="txt_issues_notes"style="margin: 0px 0px 0px -413.896px; width: 1555px; height: 148px;"><?php echo @$rs['ISSUES_NOTES'] ;?></textarea>
                 </div>



             </div>
         </fieldset>

         <!--------------------------------------------------------------------------------------------------->


         <div class="modal-footer">

             <?php

             if (  HaveAccess($post_url)  && ($isCreate || ( @$rs['ADOPT']==1 ) ) AND  ( $isCreate || (@$rs['INSERT_USER']==$this->user->id)?1 : 0 ) ||  ( $isCreate || (@$rs['UPDATE_USER']==$this->user->id)?1 : 0 ) ) : ?>
                 <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
             <?php endif; ?>

             <?php  if ( HaveAccess($adopt_url) && (!$isCreate and ( (count($rs)>0)? $rs['ADOPT']==1 : '' )  and  ((($this->user->branch == $rs['ISSUE_BRANCH']) or ($this->user->branch==1))and  ( (@$rs['UN_ADOPT_USER']!='')?(@$rs['UN_ADOPT_USER']!=$this->user->id?1:0) : 1 )))) : ?>
                 <button type="button" id="btn_adopt" onclick='javascript:return_adopt(1);' class="btn btn-success">اعتماد  </button>
             <?php  endif; ?>
             <?php  if ( HaveAccess($un_adopt_url) && (!$isCreate and ( (count($rs)>0)? $rs['ADOPT']==2 : '' ) and   (($this->user->branch == $rs['ISSUE_BRANCH']) or ($this->user->branch==1)) and   (($this->user->id != $rs['ADOPT_USER']))/*$rs['ISSUE_BRANCH']*/ )) : ?>
                 <button type="button"  id="btn_unadopt" onclick="javascript:return_adopt(2);" class="btn btn-danger">الغاء الاعتماد</button>
             <?php  endif; ?>



         </div>
         </div>
</form>
    </div>
    </div>

<?php
$scripts = <<<SCRIPT
<script type="text/javascript">
var h_request_count=[];//for request
var h_defendant_count=[];//for defendant
var h_action_count=[];//for action
function return_adopt (type){

                if(type == 1){

					   get_data('{$adopt_url}',{id: $('#txt_ser').val()},function(data){


           if(data =='1')
           {

                            success_msg('رسالة','تم اعتماد بنجاح ..');
                          reload_Page();



                          }
                    },'html');

				                }
                            if(type == 2){
                    get_data('{$un_adopt_url }',{id:$('#txt_ser').val()},function(data){
                    if(data =='1')
                    {

                           success_msg('رسالة','تم  الغاء الاعتماد بنجاح ..');
                           reload_Page();
                    }
                    },'html');

                    }


                }
$('#dp_branch,#dp_court_name,#dp_currency,#dp_endedstatus').select2().on('change',function(){

       //  checkBoxChanged();

        });
$('button[data-action="submit"]').click(function(e){
        e.preventDefault();
    if(confirm('هل تريد الحفظ  ؟!')){
            $(this).attr('disabled','disabled');
            var form = $(this).closest('form');
            ajax_insert_update(form,function(data){

            console.log(data);
                if(parseInt(data)>1){
                   success_msg('رسالة','تم حفظ البيانات بنجاح ..');

                     get_to_link('{$get_url}/'+parseInt(data));
                }else{
                    danger_msg('تحذير..',data);
                }
            },'html');
        }
        setTimeout(function() {
            $('button[data-action="submit"]').removeAttr('disabled');
        }, 3000);

});
reBind_pram(0);
function reBind_pram(cnt){
$('table tr td .select2-container').remove();

$('input[name="d_name[]"]').each(function (i) {
           h_defendant_count[i]=$(this).closest('tr').find('input[name="h_defendant_count[]"]').val(i);
   });

$('input[name="req_no[]"]').each(function (i) {
           h_request_count[i]=$(this).closest('tr').find('input[name="h_request_count[]"]').val(i);
   });

$('input[name="issue_date_action[]"]').each(function (i) {
           h_action_count[i]=$(this).closest('tr').find('input[name="h_action_count[]"]').val(i);
 });

     $('select[name="d_branch[]"]').select2().on('change',function(){

       });
     $('select[name="type[]"]').select2().on('change',function(){

       });

   $('input[name="issue_date_action[]"]').on('focus',function(){

var cnt_tr=$(this).closest('tr').find('input[name="h_action_count[]"]').val();
 $('#txt_issue_date_action_'+cnt_tr).datetimepicker({

                });
        });

  $('input[name="j_date[]"]').on('focus',function(){

var cnt_tr=$(this).closest('tr').find('input[name="h_action_count[]"]').val();
 $('#txt_j_date_'+cnt_tr).datetimepicker({

                });
        });



    }
</script>

SCRIPT;

sec_scripts($scripts);

?>