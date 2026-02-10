<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 29/03/18
 * Time: 09:45 ص
 */
$P_Stratgic='StrategicPlan';
$MODULE_NAME= 'planning';
$TB_NAME= 'planning_unit';
$GOALS_TB_NAME='public_get_goal';
$VALUES_TB_NAME='public_get_values';

if($this->uri->segment(3)!=$P_Stratgic)
{

$select_items_url =base_url("$MODULE_NAME/$TB_NAME/public_get_Objective/1");
$select_spfic_statgic_url=base_url("$MODULE_NAME/$TB_NAME/public_get_stratgic/1");

}
else
{
$select_items_url =base_url("$MODULE_NAME/$TB_NAME/public_get_Objective/2");
$select_spfic_statgic_url=base_url("$MODULE_NAME/$TB_NAME/public_get_stratgic/2");
}

//$create_url =base_url("$MODULE_NAME/$TB_NAME/create_goal");
//$post_url='';
$create_url =base_url("$MODULE_NAME/$TB_NAME/create");
$backs_url=base_url("$MODULE_NAME/$TB_NAME"); //$action
$post_url= base_url("$MODULE_NAME/$TB_NAME/".($action == 'index'?'create':$action));
$get_url= base_url("$MODULE_NAME/$TB_NAME/get");
$adopt_url= base_url("$MODULE_NAME/$TB_NAME/adopt");
$un_adopt_url= base_url("$MODULE_NAME/$TB_NAME/un_adopt");
$create_edit_plan = base_url("$MODULE_NAME/$TB_NAME/create_edit_plan");
$delete_url_details= base_url("$MODULE_NAME/$TB_NAME/delete_goals");
$HaveRs = (!$isCreate)? true:false;
$rs=$isCreate ?array() : $master_tb_data[0];

echo AntiForgeryToken();

?>

<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>
<?php 
if($this->uri->segment(3)!=$P_Stratgic)
{
?>
        <ul>
            <?php  if( HaveAccess($backs_url)):  ?><li><a  href="<?= $backs_url ?>"><i class="icon icon-reply"></i> </a> </li><?php  endif; ?>
            <?php if( HaveAccess($create_url)):  ?><li><a  href="<?= $create_url ?>"><i class="glyphicon glyphicon-plus"></i>جديد</a> </li><?php endif; ?>
            <li><a  href="<?=base_url('uploads/statgic_plan_user_manual.pdf')?>" target="_blank"><i class="icon icon-question-circle"></i>دليل المستخدم</a>
        </ul>
<?php }
else
{
    ?>
        <ul>
        <li><a  href="<?=base_url('uploads/statgic_plan_user_manual.pdf')?>" target="_blank"><i class="icon icon-question-circle"></i>دليل المستخدم</a>
        </ul>
            <?php
}

?>
    </div>
</div>
<div class="form-body">
    <div id="msg_container"></div>
    <div id="container">
        <form class="form-vertical" id="<?=$TB_NAME?>_form" method="post" action="<?=$post_url?>" role="form" novalidate="novalidate">
            <div class="modal-body inline_form">
                <fieldset>
                    <legend>الرؤية و الرسالة</legend>

                     <div class="form-group col-sm-1">
                         <label class="col-sm-2 control-label">المسلسل</label>
                         <div>
                             <input type="text" data-val="true" name="ser" id="txt_ser" class="form-control" dir="rtl" value="<?=$HaveRs?$rs['SER']:''?>" readonly>
                             <span class="field-validation-valid" data-valmsg-for="ser" data-valmsg-replace="true"></span>
                         </div>
                     </div>

                     <div class="form-group col-sm-9">
                        <label class="control-label">عنوان الخطة</label>
                        <div>
                          <input  data-val="true"  data-val-required="حقل مطلوب"  class="form-control"  name="title" id="txt_title" value="<?=$HaveRs?$rs['TITLE']:''?>" />
                          <span class="field-validation-valid" data-valmsg-for="title" data-valmsg-replace="true"></span>
                        </div>
                     </div>

                     <div class="form-group col-sm-1 ">
                        <label class="col-sm-2 control-label">من سنة</label>
                       <div>
                         <input type="text" data-val="true"  data-val-required="حقل مطلوب" name="from_year" id="txt_from_year" class="form-control" dir="rtl" value="<?=$HaveRs?$rs['FROM_YEAR']:$year_paln?>" >
                         <span class="field-validation-valid" data-valmsg-for="from_year" data-valmsg-replace="true"></span>
                       </div>
                     </div>

                     <div class="form-group col-sm-1 ">
                        <label class="col-sm-2 control-label">الى سنة</label>
                        <div>
                         <input type="text" data-val="true"  data-val-required="حقل مطلوب" name="to_year" id="txt_to_year" class="form-control" dir="rtl" value="<?=$HaveRs?$rs['TO_YEAR']:$year_paln+4?>" >
                         <span class="field-validation-valid" data-valmsg-for="to_year" data-valmsg-replace="true"></span>
                        </div>
                     </div>

                     <div style="clear: both;">

                     <div class="form-group col-sm-12">
                         <label class="control-label">الرؤية</label>
                         <div>
                             <textarea name="vision" rows="1" id="txt_vision" class="form-control"><?=$HaveRs?$rs['VISION']:''?></textarea>
                             <span class="field-validation-valid" data-valmsg-for="vision" data-valmsg-replace="true"></span>
                         </div>
                     </div>

                     <div style="clear: both;">

                     <div class="form-group col-sm-12">
                         <label class="control-label">الرسالة</label>
                         <div>
                            <textarea name="mission" rows="1" id="txt_mission" class="form-control"><?=$HaveRs?$rs['MISSION']:''?></textarea>
                            <span class="field-validation-valid" data-valmsg-for="mission" data-valmsg-replace="true"></span>
                         </div>
                     </div>

                     <div style="clear: both;">
<hr>
                     <div class="form-group col-sm-12">
                         <fieldset>

                             <legend>القيم</legend>

                             <div class="form-group col-sm-12">
                                 <?php echo modules::run("$MODULE_NAME/$TB_NAME/$VALUES_TB_NAME",$HaveRs?$rs['SER']:''); ?>
                             </div>
                             </fieldset>
                       <!-- <label class="control-label">القيم</label>
                        <div>
                            <textarea name="valuess" rows="5" id="txt_valuess" class="form-control"><?=$HaveRs?$rs['VALUESS']:''?></textarea>
                            <span class="field-validation-valid" data-valmsg-for="valuess" data-valmsg-replace="true"></span>
                        </div>-->
                     </div>

                    <!-- <div class="modal-footer">
<?php 
if($this->uri->segment(3)!=$P_Stratgic)
{
?>
                        <?php if (HaveAccess($post_url)&& ($isCreate || ( $rs['ADOPT']==1 and isset($can_edit)?$can_edit:false) )  ) : ?>
                        <button type="submit" data-action="submit" class="btn btn-primary" id="save1" name="save1">حفظ</button>
                        <?php endif; ?>
                        <?php if ( HaveAccess($adopt_url)&& (!$isCreate && ($rs['ADOPT']==1 and isset($can_edit)?$can_edit:false) )  ) : ?>
                        <button type="button"  onclick='javascript:adopt();' class="btn btn-info" id="btn_adopt">اعتماد</button>
                        <?php endif; ?>
                        <?php if ( HaveAccess($un_adopt_url)&& (!$isCreate && ($rs['ADOPT']==2 and isset($can_edit)?$can_edit:false) )  ) : ?>
                        <button type="button"  onclick='javascript:un_adopt();' class="btn btn-danger" id="btn_un_adopt">الغاء اعتماد</button>
                        <?php endif; ?>
						<?php }?>
                     </div> -->
                </fieldset>
<hr/>

    <fieldset class="hidden">
        <legend>ادارة الاهداف التشغيلية والبرامج</legend>
        <div class="form-group col-sm-2">
            <label class="control-label">من تاريخ تفعيل الاهداف التشغيلية</label>

            <div>
                <input type="text" name="donation_approved_date" data-type="date" data-date-format="DD/MM/YYYY"
                       data-val-regex="التاريخ غير صحيح!" data-val-regex-pattern="<?= date_format_exp() ?>"
                       data-val="true" data-val-required="حقل مطلوب" id="txt_donation_approved_date"
                       class="form-control " value="<?php if (count(@$rs) > 0) echo @$rs['DONATION_APPROVED_DATE']; ?>">
                <span class="field-validation-valid" data-valmsg-for="donation_approved_date"
                      data-valmsg-replace="true"></span>
            </div>
        </div>

        <div class="form-group col-sm-2">
            <label class="control-label">الى تاريخ تفعيل الاهداف التشغيلية</label>

            <div>
                <input type="text" name="donation_end_date" data-type="date" data-date-format="DD/MM/YYYY"
                       data-val-regex="التاريخ غير صحيح!" data-val-regex-pattern="<?= date_format_exp() ?>"
                       id="txt_donation_end_date" class="form-control "
                       value="<?php if (count(@$rs) > 0) echo @$rs['DONATION_END_DATE']; ?>">
                <span class="field-validation-valid" data-valmsg-for="donation_end_date"
                      data-valmsg-replace="true"></span>
            </div>
        </div>

        <div class="form-group col-sm-1">
            <label class="col-sm-2 control-label">تفعيل ادخال البرامج</label>
            <div>

                <select name="active_prog" id="dp_active_prog" class="form-control">

                    <option>نعم</option>
                    <option>لا</option>




                </select>


            </div>
        </div>

        <div class="modal-footer">

                <button type="submit" data-action="submit" class="btn btn-primary" id="save1" name="save1">حفظ</button>

            <button type="submit" data-action="submit" class="btn btn-info" id="save1" name="save1">اعتماد</button>
            <button type="submit" data-action="submit" class="btn btn-danger" id="save1" name="save1">الغاء اعتماد</button>

        </div>

    </fieldset>

                <?php if($HaveRs){ ?>
<fieldset>

    <legend>المحاور و الاهداف الاستراتيجية</legend>

    <div class="form-group col-sm-12">
        <?php echo modules::run("$MODULE_NAME/$TB_NAME/$GOALS_TB_NAME",$HaveRs?$rs['SER']:''); ?>
    </div>

                <?php } ?>
<hr/>
    <div class="modal-footer">
<?php 
if($this->uri->segment(3)!=$P_Stratgic)
{
?>
        <?php if ( HaveAccess($post_url)&& ($isCreate || ( $rs['ADOPT']==1 and isset($can_edit)?$can_edit:false) )  ) : ?>
            <button type="submit" data-action="submit" class="btn btn-primary" id="save1" name="save1">حفظ</button>
        <?php endif; ?>
        <?php if ( HaveAccess($adopt_url)&& (!$isCreate && ($rs['ADOPT']==1 and isset($can_edit)?$can_edit:false) )  ) : ?>
            <button type="button"  onclick='javascript:adopt();' class="btn btn-info" id="btn_adopt">اعتماد</button>
        <?php endif; ?>
        <?php if ( HaveAccess($un_adopt_url)&& (!$isCreate && ($rs['ADOPT']==2 and isset($can_edit)?$can_edit:false) )  ) : ?>
            <button type="button"  onclick='javascript:un_adopt();' class="btn btn-danger" id="btn_un_adopt">الغاء اعتماد</button>
        <?php endif; ?>
		<?php }?>
    </div>





</div>


        </form>
    </div>


<?php
$scripts = <<<SCRIPT
<script type="text/javascript">
var count=[];

  $('#dp_active_prog').select2().on('change',function(){

       //  checkBoxChanged();

        });

        ///////////////////////////////////////////////////////////////////////
$('button[data-action="submit"]').click(function(e){


        e.preventDefault();
        var msg= 'هل تريد حفظ الإطار الإستراتيجي؟!';
        if(confirm(msg)){
            $(this).attr('disabled','disabled');
            var form = $(this).closest('form');
            ajax_insert_update(form,function(data){

                if(parseInt(data)>1){
                    success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                    get_to_link('{$get_url}/'+parseInt(data));
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
///////////////////////////////////////

function adopt(){

  get_data('{$adopt_url}',{ser: $('#txt_ser').val()},function(data){
  
        if(data.trim() =='1')
		{
           success_msg('رسالة','تمت العملية بنجاح ..');
        reload_Page();
		}
    },'html');

}
///////////////////////////////////////

function un_adopt(){
  get_data('{$un_adopt_url}',{ser: $('#txt_ser').val()},function(data){
        if(data.trim() =='1')
		{
           success_msg('رسالة','تمت العملية بنجاح ..');
       reload_Page();
	   }
    },'html');

}

///////////////
function delete_row_del(id, name) {
  if (confirm(' هل تريد بالتأكيد حذف  '+name+' ؟!!')) {

        var values = {id: id};
        get_data('{$delete_url_details}', values, function (data) {

            if (data == 1) {

                success_msg('تمت عملية الحذف بنجاح');
                get_to_link(window.location.href);
            }

            else {
                danger_msg('لم يتم الحذف', data);
            }

        }, 'html');

    }

}
reBind_pram(0);
calcall();

function calcall() {

    var total_weight = 0;

    $('input[name="weight[]"]').each(function () {

        var weight = $(this).closest('tr').find('input[name="weight[]"]').val();
        total_weight += Number(weight);
        if(Number(total_weight)>100)
                {
                    danger_msg('لقد تجاوزت الحد المسموح');
                    $(this).closest('tr').find('input[name="weight[]"]').val(0);
                }
                else

        $('#total_weight').text(isNaNVal(Number(total_weight)));
    });



}
function reBind_pram(cnt){

$('input[name="id_label[]"]').each(function (i) {


           count[i]=$(this).closest('tr').find('input[name="h_count[]"]').val(i);






    });
     $('input[name="weight[]"]').keyup(function (e) {
 calcall();
    });

 $('button#btn_active_'+cnt).on('click',  function (e) {


_showReport('$select_items_url/'+$('#txt_ser').val()+'/'+$('#txt_id_'+cnt).val());



        });

$('button#btn_save_'+cnt).on('click',  function (e) {

        if($('#txt_id_label_'+cnt).val()=='')
           {
           danger_msg('رمز المحور غير مدخل!!');
           }
           else if($('#txt_id_name_'+cnt).val()=='')
           {
            danger_msg('اسم المحور غير مدخل!!');

           }
           else if($('#txt_plan_no_'+cnt).val()=='')
           {
            danger_msg('يتوجب حفظ الخطة اولا!!');

           }

             var id_label = $('#txt_id_label_'+cnt).val();
             var id = $('#txt_id_'+cnt).val();
             var plan_no =  $('#txt_plan_no_'+cnt).val();
             var id_name = $('#txt_id_name_'+cnt).val();

            get_data('{$create_edit_plan}', {id:id,id_label:id_label,plan_no:plan_no,id_name:id_name}, function (data) {
              if(parseInt(data)>=1){
                   success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                   $('#btn_active_'+cnt).removeClass("hidden");
                   $('#btn_adopt_'+cnt).removeClass("hidden");

                }else{
                    danger_msg('تحذير..',data);
                    }

            }, 'html');

 });




}

function show_doc(id,plan_no){
if(id == 0 || id =='' || plan_no ==0 || plan_no=='' )
 {
  danger_msg('يتوجب عليك حفظ المحور اولا!!');
  }
  else
  {
_showReport('$select_items_url/'+id+'/'+plan_no);

}
}
//////////////////////////////////
function show_doc_spefic(id,plan_no){
if(id == 0 || id =='' || plan_no ==0 || plan_no=='' )
 {
  danger_msg('يتوجب عليك حفظ المحور اولا!!');
  }
  else
  {
_showReport('$select_spfic_statgic_url/'+id+'/'+plan_no);
//alert(id);

}
}
</script>

SCRIPT;

sec_scripts($scripts);

?>
