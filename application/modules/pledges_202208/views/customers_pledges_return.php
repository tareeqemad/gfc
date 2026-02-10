<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 20/09/15
 * Time: 11:37 ص
 */
$MODULE_NAME= 'pledges';
$TB_NAME= 'customers_pledges';
$backs_url=base_url("$MODULE_NAME/$TB_NAME/index/1/2"); //$action
$post_url= base_url("$MODULE_NAME/$TB_NAME/".($action == 'index'?'create':$action));
$exe_url= base_url("$MODULE_NAME/$TB_NAME/return_adopt");
$adopt_url= base_url("$MODULE_NAME/$TB_NAME/adopt");
$select_items_url=base_url("stores/classes/public_index");
$get_class_url =base_url('stores/classes/public_get_id');
$isCreate =isset($pledge_data) && count($pledge_data)  > 0 ?false:true;
$get_url= base_url("$MODULE_NAME/$TB_NAME/edit");
$rs=($isCreate)? array() : $pledge_data;
$fid = (count($rs)>0)?$rs['FILE_ID']:0;
$d_file_id = (count($rs)>0)?$rs['D_FILE_ID']:0;
$public_return_url = $back_url=base_url("$MODULE_NAME/$TB_NAME/cancel"); //$action
$stop_pledge_url = $back_url=base_url("$MODULE_NAME/$TB_NAME/stop"); //$action
$create_url =base_url("$MODULE_NAME/$TB_NAME/create");
$report_url = 'https://itdev.gedco.ps/gfc.aspx?data='.get_report_folder().'&' ;
$get_customer_class_details=base_url("$MODULE_NAME/$TB_NAME/public_get_customer_class_file_ids");

$class_type=(count($rs)>0)? $rs['CLASS_TYPE']: 0 ;
$report_url=  'https://itdev.gedco.ps/gfc.aspx?data='.get_report_folder().'&' ;
//echo "<pre>" ; print_r($rs);
echo AntiForgeryToken();
?>

<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>

        <ul>

            <?php  if( HaveAccess($backs_url)):  ?><li><a  href="<?= $backs_url ?>"><i class="icon icon-reply"></i> </a> </li><?php  endif; ?>
            <?php if( HaveAccess($create_url)):  ?><li><a  href="<?= $create_url ?>"><i class="glyphicon glyphicon-plus"></i>جديد </a> </li><?php endif; ?>
            <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>
        </ul>

    </div>
</div>
<div class="form-body">

<div id="msg_container"></div>
<div id="container">
<form class="form-vertical" id="<?=$TB_NAME?>_form" method="post" action="<?=$post_url?>" role="form" novalidate="novalidate">
<div class="modal-body inline_form">
    <hr/>
    <fieldset>
        <legend>  بيانات السند </legend>


        <div class="form-group col-sm-1">
            <label class="control-label">المسلسل</label>
            <div>
                <input type="text" name="file_id" value="<?php echo $rs['FILE_ID'] ;?>"  readonly  data-type="text"   id="txt_file_id" class="form-control ">
            </div>
        </div>


        <div class="form-group col-sm-1">
            <label class="control-label">نوع المستفيد</label>
            <div>
                <select disabled name="customer_type" id="dp_customer_type" class="form-control" >
                    <?php foreach($customer_type_cons as $row) :?>
                        <option <?=!$isCreate?($rs['CUSTOMER_TYPE']==$row['CON_NO']?'selected':''):''?> value="<?=$row['CON_NO']?>" ><?=$row['CON_NAME']?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>


        <div class="form-group col-sm-3">
            <label class="control-label">حساب المستفيد</label>
            <div>
                <input type="text" name="customer_id_name" value="<?php echo $rs['CUSTOMER_ID_NAME'] ;?>"  readonly  data-type="text"   id="txt_customer_id_name" class="form-control " readonly>
                <input type=hidden name="customer_id"  value="<?php echo $rs['CUSTOMER_ID'];?>"   data-type="text"   id="h_customer_id" class="form-control ">
            </div>
        </div>
        <div class="form-group col-sm-1">
            <label class="control-label">رقم الغرفة</label>
            <div>
                <input type="text" name="room_id" data-val="true" value="<?php if (count($rs)>0)echo $rs['ROOM_ID'] ;?>"  readonly  data-type="text"   id="txt_room_id" class="form-control "data-val-required="حقل مطلوب"  >
                <span class="field-validation-valid" data-valmsg-for="room_id" data-valmsg-replace="true"></span>

            </div>
        </div>
        <div class="form-group col-sm-3">
            <label class="control-label">اسم الغرفة</label>
            <div>
                <input type="text" name="room_name" data-val="true" value="<?php if (count($rs)>0)echo $rs['ROOM_ID_NAME'] ;?>"  readonly  data-type="text"   id="txt_room_name" class="form-control " data-val-required="حقل مطلوب"  >
                <span class="field-validation-valid" data-valmsg-for="room_name" data-valmsg-replace="true"></span>

            </div>
        </div>
        <div class="form-group col-sm-3">
            <label class="control-label"> تاريخ استلام العهده </label>
            <div>
                <input type="text" name="recieved_date" value="<?php echo $rs['RECIEVED_DATE'] ;?>"  readonly  data-type="text"   id="txt_recieved_date" class="form-control " readonly>

            </div>
        </div>


        <input type=hidden name="source" value="<?php echo $rs['SOURCE'];?>"    data-type="text"   id="h_source" class="form-control ">
        <!--  <div class="form-group col-sm-2">
                    <label class="col-sm-4 control-label">مصدر العهدة</label>
                    <div>
                        <select  name="source" id="dp_source"  data-curr="false"  class="form-control">
                            <?php //foreach($source_all as $row) :?>
                                <option  value="<?//= $row['CON_NO'] ?>"><?//= $row['CON_NAME'] ?></option>
                            <?php //endforeach; ?>
                        </select>
                    </div>
                </div>-->
        <!--<div class="form-group col-sm-3">
            <label class="col-sm-5 control-label"> رقم سند الصرف </label>
            <div >
                <input type="text" name="class_output_id" value=""  readonly  data-type="text"   id="txt_class_output_id" class="form-control ">

            </div></div>-->
        <input type=hidden name="status"  value="<?php echo $rs['STATUS'];?>"   data-type="text"   id="h_status" class="form-control ">
        <!--   <div class="form-group col-sm-3">
                    <label class="col-sm-4 control-label">حالة العهدة</label>
                    <div>
                        <select  name="status" id="dp_status"  data-curr="false"  class="form-control">
                            <?php// foreach($status_all as $row) :?>
                                <option  value="<?//= $row['CON_NO'] ?>"><?//= $row['CON_NAME'] ?></option>
                            <?php// endforeach; ?>
                        </select>
                    </div>
                </div> -->

        <div class="form-group col-sm-9">
            <label class="control-label"> البيان </label>
            <div >
                <input type="text"   data-val-required="حقل مطلوب" name="notes" id="txt_notes" class="form-control" dir="rtl" value="<?php echo $rs['NOTES'] ;?>" data-val="true" readonly data-val-required="حقل مطلوب">


            </div></div>

    </fieldset><hr/>
    <hr/>
    <fieldset>
        <legend>  الباركود </legend>
        <div class="form-group col-sm-3">
            <label class="control-label">الباركود</label>

            <div >
                <input type="text"  name="class_code_ser" id="txt_class_code_ser" class="form-control" dir="rtl" value="<?php echo  $rs['BARCODE'] ;?>" readonly>


            </div>

        </div>

    </fieldset>
    <fieldset>
        <legend>  بيانات الصنف</legend>
        <div class="form-group col-sm-1">
            <label class="control-label">رقم الصنف</label>
            <div>
                <input class="form-control" type="text" name="class_id" id="h_txt_class_id" value="<?php echo  $rs['CLASS_ID'];?>" readonly>
                <span class="field-validation-valid" data-valmsg-for="class_id" data-valmsg-replace="true"></span>
            </div>
        </div>
        <div class="form-group col-sm-3">
            <label class="control-label">الصنف</label>
            <div>
                <input readonly class="form-control"  id="txt_class_id" value="<?php echo $rs['CLASS_ID_NAME'] ;?>"  />
            </div>
        </div>
        <div class="form-group col-sm-1">
            <label class="control-label">الوحدة</label>
            <div>
                <input readonly class="form-control"  id="txt_class_unit" value="<?php echo $rs['CLASS_UNIT_NAME'] ;?>"/>
                <input type="hidden" class="form-control"  name="class_unit" id="h_class_unit"  value="<?php echo $rs['CLASS_UNIT'] ;?>"/>
            </div>
        </div>

        <div class="form-group col-sm-1">
            <label class="control-label">السعر</label>
            <div>
                <input readonly class="form-control"  name="price" id="txt_price" value="<?php echo $rs['PRICE'] ;?>" />
            </div>
        </div>
        <div class="form-group col-sm-2">
            <label class="control-label">رقم حساب المصروف للمستفيدين </label>
            <div>
                <input readonly name="exp_account_cust" id="txt_exp_account_cust" class="form-control" value="<?php echo  $rs['EXP_ACCOUNT_CUST'] ;?>">
            </div>
        </div>
        <div class="form-group col-sm-2">
            <label class="control-label">حساب المصروف للمستفيدين </label>
            <div>
                <input readonly name="exp_account_cust_name" id="txt_exp_account_cust_name" class="form-control" value="<?php echo $rs['EXP_ACCOUNT_CUST_NAME'] ;?>">
            </div>
        </div>
        <div class="form-group col-sm-1">
            <label class="control-label">حالة الصنف</label>
            <div>
                <select name="class_type" id="dp_class_type" class="form-control" data-val="true"  data-val-required="حقل مطلوب">
                    <option></option>
                    <?php foreach($class_type_all as $row) :?>
                        <option value="<?=$row['CON_NO']?>" <?php if($row['CON_NO']==$class_type) echo 'selected'; ?>><?=$row['CON_NAME']?></option>
                    <?php endforeach; ?>
                </select>
                <span class="field-validation-valid" data-valmsg-for="class_type" data-valmsg-replace="true"></span>

            </div>
        </div>
        <div class="form-group col-sm-9">
            <label class="control-label">وصف الصنف  </label>
            <div>
                <textarea cols="100" rows="5" name="note_class_id" readonly id="note_txt_class_id"><?php echo (count($rs)>0)? $rs['NOTE_CLASS_ID']: '' ;?></textarea>
                <span class="field-validation-valid" data-valmsg-for="note_class_id" data-valmsg-replace="true"></span>
            </div>
        </div>

        <hr/>
        <fieldset>
            <legend>  بيانات الاهلاكات</legend>
            <div class="form-group col-sm-1">
                <label class="control-label">نوع الاهلاك</label>
                <div>
                    <input readonly class="form-control"  id="txt_destruction_type" value="<?php echo $rs['DESTRUCTION_TYPE_NAME'] ;?>"/>
                    <input type="hidden" class="form-control"  name="destruction_type" id="h_destruction_type" value="<?php echo  $rs['DESTRUCTION_TYPE'] ;?>" />
                </div>
            </div>
            <div class="form-group col-sm-2">
                <label class="control-label">نسبة الإهلاك السنوية</label>
                <div>
                    <input readonly name="destruction_percent" id="txt_destruction_percent" class="form-control" value="<?php echo  $rs['DESTRUCTION_PERCENT'] ;?>">
                </div>
            </div>
            <div class="form-group col-sm-2">
                <label class="control-label">رقم حساب مجمع الإهلاك السنوى </label>
                <div>
                    <input readonly name="destruction_account_id" id="txt_destruction_account_id" class="form-control" value="<?php echo  $rs['DESTRUCTION_ACCOUNT_ID'] ;?>">
                </div>
            </div>
            <div class="form-group col-sm-2">
                <label class="control-label">اسم حساب مجمع الإهلاك السنوى </label>
                <div>
                    <input readonly name="destruction_account_name" id="txt_destruction_account_name" class="form-control" value="<?php echo  $rs['DESTRUCTION_ACCOUNT_ID_NAME'] ;?>">
                </div>
            </div>
            <div class="form-group col-sm-2">
                <label class="control-label">متوسط العمر الافتراضي</label>
                <div>
                    <input readonly name="average_life_span" id="txt_average_life_span" class="form-control" value="<?php echo  $rs['AVERAGE_LIFE_SPAN'];?>">
                </div>
            </div>
            <div class="form-group col-sm-1">
                <label class="control-label">نوع المتوسط</label>
                <div>
                    <input readonly class="form-control"  id="txt_average_life_span_type" value="<?php echo  $rs['AVERAGE_LIFE_SPAN_TYPE_NAME'] ;?>"/>
                    <input type="hidden" class="form-control"  name="average_life_span_type" id="h_average_life_span_type" value="<?php echo $rs['AVERAGE_LIFE_SPAN_TYPE'] ;?>"/>
                </div>
            </div>
        </fieldset>
        <hr/>
    </fieldset>
    <hr/>
    <fieldset>
        <legend> العهدة تابعة لعهدة أخرى؟</legend>
        <div class="form-group col-sm-3">
            <label class="control-label">رقم العهدة</label>
            <div>
                <select  name="d_file_id" id="dp_d_file_id"  data-curr="false"  class="form-control" data-val="true" data-val-required="حقل مطلوب" >
                    </select>
                <span class="field-validation-valid" data-valmsg-for="customer_id" data-valmsg-replace="true"></span>
            </div>
        </div>
    </fieldset>
    <hr/>
    <fieldset>
        <legend>  بيانات الارجاع الى المخزن</legend>


        <div class="form-group col-sm-3">
            <label class="control-label">المخزن</label>
            <?php
       //     if($rs['CLASS_CODE_SER']=='')
        //    {
            ?>
            <div>
                <select name="store_id" style="width: 250px" id="dp_store_id" data-val="true"  data-val-required="حقل مطلوب">
                    <option></option>
                    <?php foreach($stores as $row) :?>
                        <option  <?php if ($rs['STORE_ID']==$row['STORE_ID']) echo "selected"; ?> value="<?= $row['STORE_ID'] ?>"><?= $row['STORE_NO'].":".$row['STORE_NAME'] ?></option>
                    <?php endforeach; ?>
                </select>


                <span class="field-validation-valid" data-valmsg-for="store_id" data-valmsg-replace="true"></span>

            </div>
            <?php
        //    }
       //    else
        //    {
                ?>
            <!--    <div>
                    <input readonly class="form-control"  id="txt_store_id" value="<?php echo $rs['STORE_ID_BY_CODE_NAME'] ;?>"/>
                    <input type="hidden" class="form-control"  name="store_id" id="h_store_id" value="<?php echo  $rs['STORE_ID_BY_CODE'] ;?>" />
                </div> -->
            <?php

        //    }?>

        </div>

</div>

</fieldset>
<div class="modal-footer">
    <?php //echo ( $rs['ADOPT']==1 and isset($can_edit)?$can_edit:false) ;
    if (  HaveAccess($post_url) && ( ( $rs['ADOPT']==2 and  $rs['FOLLOW_FILE_ID']=='') )) : ?>
        <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
    <?php endif; ?>

    <?php if ( !$isCreate  and $rs['ADOPT']!=0 and $rs['STATUS']==4 and  $rs['FOLLOW_FILE_ID']<>'') : ?>
        <button type="button" id="print_rep" onclick="javascript:print_rep();" class="btn btn-success"> <i class="glyphicon glyphicon-print"></i> طباعة </button>
    <?php endif; ?>

    <?php //echo ( $rs['ADOPT']==1 and isset($can_edit)?$can_edit:false) ;
    if (  HaveAccess($exe_url) && ( ( $rs['ADOPT']==2 and  $rs['FOLLOW_FILE_ID']=='') and $rs['STATUS']==4 )) : ?>
        <button type="button" id="btn_adopt" onclick='javascript:return_adopt(1);' class="btn btn-danger">تنفيذ ارجاع العهدة الى المخزن</button>
    <?php endif; ?>


</div>
<div style="clear: both;">
    <input type="hidden" id="h_data_search" />
</div>
<div style="clear: both;">
    <?php echo modules::run('settings/notes/public_get_page',(count($rs)>0)?$rs['FILE_ID']:0,'customers_pledges'); ?>
    <?php echo (count($rs)>0)?  modules::run('attachments/attachment/index',$rs['FILE_ID'],'customers_pledges') : ''; ?>
</div>
</form>
</div>
</div>
<?php echo modules::run('settings/notes/index'); ?>

<?php

$notes_url =notes_url();


$scripts = <<<SCRIPT
<script type="text/javascript">
var action_type;
function clear_form(){
        clearForm($('#{$TB_NAME}_form'));
    }


     $('#dp_store_id').select2().on('change',function(){

        });



       function return_adopt(type){

            action_type = type;

            $('#notesModal').modal();



       }

        function apply_action(){

                if(action_type == 1){

                    get_data('{$exe_url}',{id:{$fid}},function(data){

                    if(data =='1')
                            success_msg('رسالة','تم ارجاع العهدة الى المخزن بنجاح ..');
                            reload_Page();
                    },'html');

                }



                get_data('{$notes_url}',{source_id:{$fid},source:'customers_pledges',notes:$('#txt_g_notes').val()},function(data){
                    $('#txt_g_notes').val('');
                },'html');

                $('#notesModal').modal('hide');


        }

$(document).ready(function() {

$( "#print_rep" ).on( 'click', function (){

        url ='$report_url'+'report=store_rep/own_customer_rep&params[]=&params[]='+$('#txt_file_id').val();
          _showReport(url);
});

        $('button[data-action="submit"]').click(function(e){
        e.preventDefault();
        if(confirm('هل تريد ارجاع العهدة الى المخزن ؟!')){
            var form = $(this).closest('form');
            ajax_insert_update(form,function(data){
                if(data>=1){
                    success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                    get_to_link(window.location.href);
                }else{
                    danger_msg('تحذير..',data);
                }
            },'html');
        }
    });


});


 $('#dp_d_file_id').select2();
 if($('#txt_file_id').val() != ''){
 //alert('{$d_file_id}');
   var select_file_ids= "";
$.ajax({
    type: 'POST',
    url: '{$get_customer_class_details}',
    data: {customer_id:$('#h_customer_id').val(),file_id:$('#txt_file_id').val() },
    dataType: 'json',
    success: function (data) {
     $('select[name="d_file_id"]').empty();
        $.each(data, function(index, element) {
         if ('{$d_file_id}'==element.FILE_ID )
         select_file_ids = "<option   value='"+element.FILE_ID+"' selected >"+'رقم العهدة /'+element.FILE_ID+':'+' رقم الصنف/'+element.CLASS_ID+':'+'اسم الصنف/ '+element.CLASS_NAME+':'+' الباركود/ '+element.CLASS_CODE_SER+':'+' سند صرف / '+element.CLASS_OUTPUT_ID+"</option>";


         $('select[name="d_file_id"]').append(select_file_ids);
        });
        if (select_file_ids =="")
         $('select[name="d_file_id"]').append("<option value='0'>-----</option>");

         $('select[name="d_file_id"]').change();
    }

});
   }

</script>

SCRIPT;

sec_scripts($scripts);

?>

