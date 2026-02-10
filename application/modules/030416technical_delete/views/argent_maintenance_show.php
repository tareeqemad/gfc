<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 11/01/15
 * Time: 11:28 ص
 */
$back_url=base_url('technical/Argent_Maintenance');


if(!isset($result))
    $result= array();
$HaveRs = count($result) > 0;

$rs =$HaveRs? $result[0] : $result;

?>

    <div class="row">
        <div class="toolbar">

            <div class="caption"><?= $title ?></div>
            <ul>
                <?php  if( HaveAccess($back_url)):  ?><li><a  href="<?= $back_url ?>"><i class="icon icon-reply"></i> </a> </li><?php  endif; ?>
                <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>
            </ul>


        </div>

        <div class="form-body">

            <div id="msg_container"></div>
            <div id="container">
                <form class="form-form-vertical" id="hpp_form" method="post" action="<?=base_url('technical/Argent_Maintenance/'.($HaveRs?'edit':'create'))?>" role="form" novalidate="novalidate">
                    <div class="modal-body inline_form">

                        <div class="form-group col-sm-1">
                            <label class="control-label"> الرقم  </label>
                            <div>
                                <input type="text"  readonly  value="<?= $HaveRs? $rs['ARGENT_MAINTENANCE_ID'] : "" ?>"  name="argent_maintenance_id" id="txt_argent_maintenance_id" class="form-control">
                            </div>
                        </div>



                        <div class="form-group col-sm-4">
                            <label class="control-label"> وصف المشكلة   </label>
                            <div>
                                <input type="text" data-val="true" data-val-required="يجب إدخال وصف المشكلة"    value="<?= $HaveRs? $rs['PROBLEM_DESCRIPTION'] : "" ?>"  name="problem_description" id="txt_problem_description" class="form-control">
                            </div>
                        </div>


                        <div class="form-group col-sm-2">
                            <label class="control-label"> اسم المواطن   </label>
                            <div>
                                <input name="customer_name" data-val="true" data-val-required="يجب إدخال اسم المواطن"   value="<?= $HaveRs? $rs['CUSTOMER_NAME'] : "" ?>" class="form-control"  id="txt_customer_name" />
                            </div>
                        </div>

                        <div class="form-group col-sm-3">
                            <label class="control-label">العنوان</label>
                            <div>
                                <input  name="address"  data-val="true" data-val-required="يجب إدخال العنوان"   value="<?= $HaveRs? $rs['ADDRESS'] : "" ?>" class="form-control"  id="txt_address" />
                            </div>
                        </div>
                        <hr>

                        <div class="form-group col-sm-1">
                            <label class="control-label">  الجوال </label>
                            <div>
                                <input type="text"   value="<?= $HaveRs? $rs['MOBILE'] : "" ?>"  name="mobile" id="txt_mobile" class="form-control">
                            </div>
                        </div>



                        <div class="form-group col-sm-1">
                            <label class="control-label">  الهاتف </label>
                            <div>
                                <input type="text"   value="<?= $HaveRs? $rs['TEL'] : "" ?>"  name="tel" id="txt_tel" class="form-control">
                            </div>
                        </div>



                        <div class="form-group  col-sm-2">
                            <label class="control-label"> تاريخ ووقت تنفيذ المهمة
                            </label>
                            <div>
                                <input name="mission_process_date" data-val="true"    value="<?= $HaveRs? $rs['MISSION_PROCESS_DATE'] : "" ?>"   data-val-required="حقل مطلوب"   id="txt_mission_process_date" data-type="date"  data-date-format="DD/MM/YYYY"   data-val-regex="التاريخ غير صحيح!" data-val-regex-pattern="<?= date_format_exp() ?>"  class="form-control">


                            </div>

                        </div>

                        <div class="form-group col-sm-1">
                            <label class="control-label">نوع العطل</label>
                            <div>
                                <select class="form-control" name="problem_type" id="dp_problem_type">
                                    <option></option>
                                    <?php foreach($PROBLEM_TYPE as $row) :?>
                                        <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-sm-5">
                            <label class="control-label">  تشخيص العطل من قبل الفريق الفنى
                            </label>
                            <div>
                                <input type="text"   value="<?= $HaveRs? $rs['PROBLEM_DIAGONISTIC'] : "" ?>"  name="problem_diagonistic" id="txt_problem_diagonistic" class="form-control">
                            </div>
                        </div>


                        <hr>

                        <div style="clear: both;">
                            <fieldset>
                                <legend>فريق العمل المكلف</legend>
                                <?php echo modules::run('technical/Argent_Maintenance/public_get_works',$HaveRs?$rs['ARGENT_MAINTENANCE_ID']:0); ?>
                            </fieldset>
                        </div>
                        <hr>

                        <div style="clear: both;">
                            <fieldset>
                                <legend> الادوات  المستخدمة والمرجعة</legend>
                                <?php echo modules::run('technical/Argent_Maintenance/public_get_tools',$HaveRs?$rs['ARGENT_MAINTENANCE_ID']:0); ?>
                            </fieldset>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">إغلاق</button>

                    </div>
                </form>

            </div>

        </div>
    </div>

<?php
$delete_tools_url = base_url('technical/Argent_Maintenance/delete_tools');
$delete_works_url = base_url('technical/Argent_Maintenance/delete_works');
$adapters_url =base_url('projects/adapter/public_index');
$hpp_url =base_url('technical/HighPowerPartition/public_index');
$select_items_url=base_url("stores/classes/public_index");

$customer_url =base_url('payment/customers/public_index');

$shared_script=<<<SCRIPT
      $('button[data-action="submit"]').click(function(e){
        e.preventDefault();
        if(confirm('هل تريد حفظ السند ؟!')){
            var form = $(this).closest('form');
            ajax_insert_update(form,function(data){

                success_msg('رسالة','تم حفظ البيانات بنجاح ..');

               // reload_Page();

            },'html');
        }
    });

reBind();

function reBind(){
        $('input[id*="txt_d_class_id"]').click("focus",function(e){
        _showReport('$select_items_url/'+$(this).attr('id'));

    });

     $('input[id*="txt_d_employee_id"]').click(function(){

_showReport('$customer_url/'+$(this).attr('id')+'/3');

            });

    }


 $('#h_txt_class_id,#h_txt_base_class_id').keyup(function(){
    $('#'+$(this).attr('id').replace('h_','')).val('');
 });

 function delete_details_tools(a,id){
             if(confirm('هل تريد حذف البند ؟!')){

                  get_data('{$delete_tools_url}',{id:id},function(data){
                             if(data == '1'){
                                $(a).closest('tr').remove();

                               }else{
                                     danger_msg( 'تحذير','فشل في العملية');
                               }
                        });
                 }
         }


function delete_details_works(a,id){
             if(confirm('هل تريد حذف البند ؟!')){

                  get_data('{$delete_works_url}',{id:id},function(data){
                             if(data == '1'){
                                $(a).closest('tr').remove();

                               }else{
                                     danger_msg( 'تحذير','فشل في العملية');
                               }
                        });
                 }
         }


SCRIPT;


$create_script=<<<SCRIPT
    <script>
        {$shared_script}
    </script>
SCRIPT;



$edit_script=<<<SCRIPT
    <script>
        {$shared_script}
    </script>
SCRIPT;

if($HaveRs)
    sec_scripts($edit_script);
else
    sec_scripts($create_script);

?>