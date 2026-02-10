<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: tekrayem
 * Date: 07/03/21
 * Time: 12:56 م
 */
$MODULE_NAME= 'pledges';
$TB_NAME= 'customers_room_move';
$room_cus_url= base_url('pledges/rooms_structure_tree/public_get_room_by_id');

if ($action=='index') {
    $adopt=10;
    $ser='';
    $entry_date=date('d/m/Y');
    $employee_no='';
    $from_room_id='';
    $from_room_id_name='';
    $to_room_id='';
    $notes='';
    $employee_id='';
}else{
    $rs= $orders_data[0];
   $ser=isset($rs['SER'])? $rs['SER'] :'';
    $adopt=isset($rs['ADOPT'])? $rs['ADOPT'] :10;
    $entry_date=isset($rs['ENTRY_DATE'])? $rs['ENTRY_DATE'] :'';
    $employee_no=isset($rs['EMPLOYEE_NO'])? $rs['EMPLOYEE_NO'] :'';
    $from_room_id=isset($rs['FROM_ROOM_ID'])? $rs['FROM_ROOM_ID'] :'';
    $from_room_id_name =isset($rs['FROM_ROOM_ID_NAME'])? $rs['FROM_ROOM_ID_NAME'] :'';
    $to_room_id=isset($rs['TO_ROOM_ID'])? $rs['TO_ROOM_ID'] :'';
    $notes=isset($rs['NOTES'])? $rs['NOTES'] :'';
    $employee_id=isset($rs['EMPLOYEE_ID'])? $rs['EMPLOYEE_ID'] :'';
}
$back_url=base_url("$MODULE_NAME/$TB_NAME/index");
$create_url =base_url("$MODULE_NAME/$TB_NAME/create");
$post_url= base_url("$MODULE_NAME/$TB_NAME/".($action == 'index'?'create' :$action));
$get_url=base_url("$MODULE_NAME/$TB_NAME/get");
$edit_url=base_url("$MODULE_NAME/$TB_NAME/edit");
//$adopt1_url=base_url("$MODULE_NAME/$TB_NAME/adopt10");
$adopt20_url=base_url("$MODULE_NAME/$TB_NAME/adopt20");
$adopt30_url=base_url("$MODULE_NAME/$TB_NAME/adopt30");
//echo HaveAccess($edit_url);
$cancel_adopt20_url=base_url("$MODULE_NAME/$TB_NAME/cancel_adopt20");

echo AntiForgeryToken();
?>
<div class="row">
    <div class="toolbar">

        <div class="caption"><?=$title?></div>
        <ul>
            <?php if( HaveAccess($create_url)):  ?><li><a  href="<?= $create_url ?>"><i class="glyphicon glyphicon-plus"></i>جديد </a> </li><?php endif; ?>

            <?php //HaveAccess($back_url)
            if( TRUE):  ?><li><a  href="<?= $back_url ?>"><i class="icon icon-reply"></i> </a> </li><?php  endif; ?>
        </ul>

    </div>
</div>
<div class="form-body">

    <div id="msg_container"></div>
    <div id="container">

        <form class="form-vertical" id="<?=$TB_NAME?>_form" method="post" action="<?=$post_url?>" role="form" novalidate="novalidate">
            <div class="modal-body inline_form">

                    <div class="form-group col-sm-1">
                        <label class="control-label">مسلسل  </label>
                        <div>
                            <input type="text" readonly value="<?=$ser?>" name="ser"  id="txt_ser" class="form-control" />
                            <?php if (( isset($can_edit)?$can_edit:false)) : ?>
                                <input type="hidden" value="<?=$ser?>"   name="ser" id="h_ser">
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="form-group col-sm-2">
                        <label class="control-label"> تاريخ الإدخال  </label>
                        <div >
                            <input type="text" readonly value="<?=$entry_date?>" name="entry_date"  data-type="date"  data-date-format="DD/MM/YYYY"  data-val-regex="التاريخ غير صحيح!" data-val-regex-pattern="<?= date_format_exp() ?>" data-val="false"  data-val-required="حقل مطلوب"  id="txt_entry_date" class="form-control ">

                        </div></div>
                    <div class="form-group col-sm-2">
                        <label class="control-label"> حساب المستفيد - الهوية </label>
                        <div>
                            <select name="employee_id" id="txt_employee_id" class="form-control" >
                                <option value="">_________</option>
                                <?php foreach($customer_ids as $row) :?>
                                    <option <?php if($employee_id==$row['ID']) echo "selected";?> value="<?=$row['ID']?>"><?=$row['NO'].'-'.$row['NAME']?></option>
                                <?php endforeach; ?>
                            </select>
                            <span class="field-validation-valid" data-valmsg-for="employee_id" data-valmsg-replace="true"></span>
                        <input type="hidden" value="<?=$employee_no?>" name="employee_no" id="txt_employee_no">
                        </div></div>

                <div class="form-group col-sm-1">
                    <label class="control-label"> من رقم غرفة </label>
                    <div>
                        <input type="text" name="from_room_id" data-val="true" value="<?=$from_room_id?>"  readonly  data-type="text"   id="txt_from_room_id" class="form-control "data-val-required="حقل مطلوب"  >
                        <span class="field-validation-valid" data-valmsg-for="from_room_id" data-valmsg-replace="true"></span>

                    </div>
                </div>
                <div class="form-group col-sm-2">
                    <label class="control-label"> من اسم غرفة </label>
                    <div>
                        <input type="text" name="from_room_name" data-val="true" value="<?=$from_room_id_name?>"  readonly  data-type="text"   id="txt_from_room_name" class="form-control " data-val-required="حقل مطلوب"  >
                        <span class="field-validation-valid" data-valmsg-for="from_room_name" data-valmsg-replace="true"></span>

                    </div>
                </div>

                    <div class="form-group col-sm-2">
                        <label class="control-label">إلى غرفة</label>
                        <div>
                            <select name="to_room_id" id="dp_to_room_id" class="form-control" >
                                <option value="">_________</option>
                                <?php foreach($rooms_cons as $row) :?>
                                    <option <?php if($to_room_id==$row['ROOM_ID']) echo "selected";?>  value="<?=$row['ROOM_ID']?>" ><?=$row['ROOM_ID'].': '.$row['ROOM_PARENT_NAME'].' - '.$row['ROOM_NAME']?></option>
                                <?php endforeach; ?>
                            </select>  <span class="field-validation-valid" data-valmsg-for="to_room_id" data-valmsg-replace="true"></span>
                        </div></div>


                    <div class="form-group col-sm-6">
                        <label class="control-label">ملاحظات</label>
                        <div>
                            <textarea rows="3" name="notes" id="txt_notes" class="form-control" ><?=$notes?></textarea>

                            <!--  <input type="text" value="<?=$notes?>" name="notes" id="txt_notes" class="form-control" />-->
                        </div>
                    </div>

                <hr/>

            </div>

            <div class="modal-footer">
                <?php if ( ( HaveAccess($create_url)||HaveAccess($edit_url)) && (($adopt==10  ))  ) : ?>
                    <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                <?php endif; ?>

                <?php if (  (HaveAccess($adopt20_url)) and ($action=='edit') && ($adopt==10  )  ) : ?>
                    <button type="button" onclick='javascript:adopt20();' class="btn btn-primary">اعتماد الخدمات</button>
                <?php endif; ?>
                <?php //if (  (HaveAccess($cancel_adopt20_url)) and ($action=='edit') && ($adopt==20  )  ) : ?>
                <!--  <button type="button" onclick='javascript:cancel_adopt20();' class="btn btn-primary">إلغاء اعتماد الخدمات</button>-->
                <?php //endif; ?>

                <?php //if (  (HaveAccess($adopt30_url)) and ($action=='edit') && ($adopt==20  )  ) : ?>
                   <!-- <button type="button" onclick='javascript:adopt30();' class="btn btn-primary">اعتماد الرقابة   </button> -->
                <?php // endif; ?>

                <?php if ($isCreate): ?>
                    <button type="button" onclick="javascript:clear_form();"  class="btn btn-default"> تفريغ الحقول</button>
                <?php   endif; ?>

            </div>

        </form>
    </div>
</div>



<?php
$shared_js = <<<SCRIPT

   $('#txt_employee_id').select2();
    $('#dp_to_room_id').select2();
    
$('button[data-action="submit"]').click(function(e){
    e.preventDefault();
    var tbl = '#{$TB_NAME}_tb';
    var container = $('#' + $(tbl).attr('data-container'));
    var form = $(this).closest('form');
    ajax_insert_update(form,function(data){

                if(parseInt(data)>1){
                    success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                    get_to_link('{$get_url}/'+parseInt(data)+'/edit');
                }else if(data==1){
                    success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                    get_to_link(window.location.href);
                }else{
                    danger_msg('تحذير..',data);
                }


    },"html");
});


    
     $('#txt_employee_id').select2().on('change',function(){
  get_data('{$room_cus_url}',{customer_id:$('#txt_employee_id').val()},function(data){
       if(Object.keys(data).length>=1)
       {  var emp_info=data[0];
            $('#txt_from_room_id').val(emp_info.ROOM_ID);
        $('#txt_from_room_name').val(emp_info.ROOM_NAME);
          $('#txt_employee_no').val(emp_info.EMP_NO);
        }
        else
        {
          $('#txt_from_room_id').val('');
        $('#txt_from_room_name').val('');
          $('#txt_employee_no').val('');
        }
       });
  });
     

SCRIPT;



if(HaveAccess($create_url) and $action=='index' ){
    $scripts = <<<SCRIPT
<script>


  {$shared_js}


    function clear_form(){
        clearForm($('#{$TB_NAME}_from'));

    }



</script>

SCRIPT;
    sec_scripts($scripts);

}
else
    if(HaveAccess($edit_url) and $action=='edit'){


        $edit_script = <<<SCRIPT


<script>

  {$shared_js}


function adopt20(){
    if(confirm('هل تريد إتمام العملية ؟')){


    get_data('{$adopt20_url}',{id:{$ser}},function(data){
      if(data =='1')
      console.log(data);
                      success_msg('رسالة','تمت العملية بنجاح');
                      setTimeout(function(){

                  window.location.reload();

                }, 1000);
            },'html');


}


}


function adopt30(){

    if(confirm('هل تريد إتمام العملية ؟')){


    get_data('{$adopt30_url}',{id:{$ser}},function(data){
      if(data =='1')
      console.log(data);
                      success_msg('رسالة','تمت العملية بنجاح');
                      setTimeout(function(){

                  window.location.reload();

                }, 1000);
                      
            },'html');


}


}



function cancel_adopt20(){

    if(confirm('هل تريد إتمام العملية ؟')){


    get_data('{$cancel_adopt20_url}',{id:{$ser}},function(data){
      if(data =='1')
      console.log(data);
                      success_msg('رسالة','تمت العملية بنجاح');
                      setTimeout(function(){

                  window.location.reload();

                }, 1000);
            },'html');


}
}


</script>
SCRIPT;
        sec_scripts($edit_script);

    }
?>

