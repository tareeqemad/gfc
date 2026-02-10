<?php
/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 16/03/22
 * Time: 09:00 ص
 */

$MODULE_NAME= 'payment';
$TB_NAME= 'Car_maintenance_workshop';
$DET_TB_NAME= 'public_get_details';

$update_url= base_url("$MODULE_NAME/$TB_NAME/update");
$get_car_info =base_url("$MODULE_NAME/$TB_NAME/public_get_car_info");
$adopt_url= base_url("$MODULE_NAME/$TB_NAME/adopt_");
$get_class_url= base_url("$MODULE_NAME/$TB_NAME/public_get_id");
$back_url= base_url("$MODULE_NAME/$TB_NAME/index");

$report_url = base_url("JsperReport/showreport?sys=financial");
$isCreate =isset($master_tb_data) && count($master_tb_data)  > 0 ?false:true;
$HaveRs = (!$isCreate)? true:false;
$rs=$isCreate ?array() : $master_tb_data[0];
$adopt = $HaveRs?$rs['ADOPT']:'';
?>

<div class="row">

    <div class="toolbar">
        <div class="caption"><?= $title ?></div>
        <ul>
            <li><a  href="<?= $back_url ?>"><i  class="icon icon-reply"></i> </a></li>
        </ul>
    </div>

    <div class="form-body">
        <div id="msg_container"></div>
        <div id="container">
            <form class="form-vertical" id="<?=$TB_NAME?>_form" method="post" role="form" action="<?=$update_url?>" novalidate="novalidate">
                <div class="modal-body inline_form">

                    <div class="col-sm-10">

                        <div class="form-group col-sm-1">
                            <label class="control-label">رقم الطلب</label>
                            <div>
                                <input type="text" value="<?=$HaveRs?$rs['SER']:''?>" name="ser" id="txt_ser" class="form-control" readonly/>
                            </div>
                        </div>

                        <div class="form-group col-sm-1">
                            <label class="control-label">رقم السيارة</label>
                            <div>
                                <input type="text" value="<?=$HaveRs?$rs['CAR_ID']:''?>" name="car_id" id="txt_car_id" class="form-control" readonly/>
                            </div>
                        </div>

                        <div class="form-group col-sm-3">
                            <label class="control-label">صاحب العهدة</label>
                            <div>
                                <select data-val="true" name="car_owner" id="dp_car_owner" class="form-control " disabled>
                                    <option>_________</option>
                                    <?php foreach ($car_owner as $row) : ?>
                                        <option <?=$HaveRs?($rs['CAR_OWNER']==$row['CAR_FILE_ID']?'selected':''):''?> value="<?= $row['CAR_FILE_ID'] ?>" ><?= $row['CAR_OWNER'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group col-sm-2">
                            <label class="control-label">ملكية السيارة</label>
                            <div>
                                <select data-val="true" name="car_ownership" id="dp_car_ownership" class="form-control" disabled>
                                    <option>_________</option>
                                    <?php foreach ($car_ownership_list as $row) : ?>
                                        <option <?=$HaveRs?($rs['CAR_OWNERSHIP']==$row['CON_NO']?'selected':''):''?> value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group col-sm-1">
                            <label class="control-label">نوع السيارة</label>
                            <div>
                                <select type="text" name="car_type" id="dp_car_type" class="form-control " disabled>
                                    <option >__________</option>
                                    <?php foreach ($car_class as $row) : ?>
                                        <option <?=$HaveRs?($rs['CAR_TYPE']==$row['CON_NO']?'selected':''):''?> value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group col-sm-1">
                            <label class="control-label">الموديل</label>
                            <div>
                                <input type="text" value="<?=$HaveRs?$rs['CAR_MODEL']:''?>" name="car_model" id="txt_car_model" class="form-control" readonly/>
                            </div>
                        </div>

                        <div class="form-group col-sm-1">
                            <label class="control-label">رقم الهيكل</label>
                            <div>
                                <input type="text" value="<?=$HaveRs?$rs['DEFINITION_CODE']:''?>" name="definition_code" id="txt_definition_code" class="form-control" readonly/>
                            </div>
                        </div>

                        <div class="form-group col-sm-2">
                            <label class="control-label">المقر </label>
                            <div>
                                <select name="branch_id" id="dp_branch_id" class="form-control" disabled >
                                    <option value="">_________</option>
                                    <?php foreach($branches as $row) :?>
                                        <option <?=$HaveRs?($rs['BRANCH_ID']==$row['NO']?'selected':''):''?> value="<?=$row['NO']?>" ><?=$row['NAME']?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group col-sm-2">
                            <label class="control-label">سائق السيارة</label>
                            <div>
                                <select name="driver_id" id="dp_driver_id" class="form-control sel2" disabled >
                                    <option value="">_________</option>
                                    <?php foreach($emp_no_cons as $row) :?>
                                        <option <?=$HaveRs?($rs['DRIVER_ID']==$row['EMP_NO']?'selected':''):''?>  value="<?=$row['EMP_NO']?>"><?=$row['EMP_NO'].': '.$row['EMP_NAME']?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group col-sm-6">
                            <label class="control-label">وصف العطل</label>
                            <div>
                                <textarea data-val-required="حقل مطلوب" class="form-control" name="des_problem" rows="1" id="txt_des_problem"><?=$HaveRs?$rs['DES_PROBLEM']:''?></textarea>
                            </div>
                        </div>

                    </div>

                    <?php if ( $rs['ADOPT']>=20 ) : ?>
                    <br><br><hr><br>
                    <div class="col-sm-12">

                        <div class="form-group col-sm-2">
                            <label class="control-label">أسباب العطل</label>
                            <div>
                                <select name="reasons_problem" id="dp_reasons_problem" class="form-control sel2" >
                                    <option value="">_________</option>
                                    <?php foreach ($reasons_problem as $row) : ?>
                                        <option <?=$HaveRs?($rs['REASONS_PROBLEM']==$row['CON_NO']?'selected':''):''?> value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group col-sm-2">
                            <label class="control-label">نوع الصيانة</label>
                            <div>
                                <select name="maintenance_type" id="dp_maintenance_type" class="form-control sel2" >
                                    <option value="">_________</option>
                                    <?php foreach ($maintenance_type as $row) : ?>
                                        <option <?=$HaveRs?($rs['MAINTENANCE_TYPE']==$row['CON_NO']?'selected':''):''?> value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group col-sm-6">
                            <label class="control-label">الكشف الفني على السيارة / الآلية</label>
                            <div>
                                <textarea data-val-required="حقل مطلوب" class="form-control" name="technical_detection" rows="1" id="txt_technical_detection"><?=$HaveRs?$rs['TECHNICAL_DETECTION']:''?></textarea>
                            </div>
                        </div>

                    </div>

                    <br>
                    <br>

                    <fieldset class="field_set">
                        <legend>القطع التي تم تركيبها</legend>
                    <?php echo modules::run("$MODULE_NAME/$TB_NAME/$DET_TB_NAME", ($HaveRs)?$rs['SER']:0,$adopt); ?>
                    </fieldset>

                    <div class="form-group col-sm-5">
                        <label class="control-label">ملاحظات</label>
                        <div>
                            <textarea class="form-control" name="notes" rows="1" id="txt_notes"><?=$HaveRs?$rs['NOTES']:''?></textarea>
                        </div>
                    </div>

                    <div class="row pull-left">
                        <div style="clear: both;">
                            <span><?php echo modules::run('attachments/attachment/index',$HaveRs?$rs['SER']:'','CAR_MAINTENANCE_REQUEST_TB'); ?></span>
                        </div>
                    </div>

                    <?php endif; ?>

                </div>

                <div class="modal-footer">
                    <?php if ( HaveAccess($adopt_url.'20') and $rs['ADOPT']==10 ) : ?>
                        <button type="button" id="btn_adopt_20" onclick='javascript:adopt_(20);' class="btn btn-success">اعتماد الورشة</button>
                    <?php endif; ?>

                    <?php if ( HaveAccess($update_url) and $rs['ADOPT']==20 ) : ?>
                        <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                    <?php endif; ?>

                    <?php if ( HaveAccess($adopt_url.'30') and $rs['ADOPT']==20 ) : ?>
                        <button type="button" id="btn_adopt_30" onclick='javascript:adopt_(30);' class="btn btn-success">منجزة</button>
                    <?php endif; ?>

                    <?php if ( HaveAccess($adopt_url.'40') and $rs['ADOPT']==30 ) : ?>
                        <button type="button" id="btn_adopt_40" onclick='javascript:adopt_(40);' class="btn btn-success">استلام</button>
                    <?php endif; ?>

                    <?php if ($rs['ADOPT']==40 ) : ?>
                        <button type="button" onclick="javascript:print_report();"  class="btn btn-primary">طباعة التقرير<i class="glyphicon glyphicon-print"></i></button>
                    <?php endif; ?>
                </div>

            </form>

        </div>
    </div>
</div>

<?php
$scripts = <<<SCRIPT

<script type="text/javascript">

    $('.sel2:not("[id^=\'s2\']")').select2();
    $('#dp_class_unit').prop('readonly', true);
    $('#dp_class_unit').attr('readonly', true);
    var count = 0;
    reBind();
      
    function  reBind(s) {
        if(s==undefined){s=0;}
        
        $('.the_date').datetimepicker({
                format: 'DD/MM/YYYY',
                minViewMode: "days",
                pickTime: false
        });
        
        $('select[name="class_name[]"]').select2();
        $('select[name="class_unit[]"]').select2();
        $('select[name="class_type[]"]').select2();
        $('select[name="supplier_no[]"]').select2();
        $('select[name="class_status[]"]').select2();

        $('select[name="class_name[]"]').change(function(){
          
            var id =$(this).val();
                
            var class_id= $(this).closest('tr').find('select[name="class_name[]"]');
            var quantity= $(this).closest('tr').find('input[name="quantity[]"]');
            var class_unit= $(this).closest('tr').find('select[name="class_unit[]"]');
            var class_status= $(this).closest('tr').find('select[name="class_status[]"]');
            var supplier_no= $(this).closest('tr').find('select[name="supplier_no[]"]');
            var price= $(this).closest('tr').find('input[name="price[]"]');
            var consignment_no= $(this).closest('tr').find('input[name="consignment_no[]"]');
            var complementing_amount= $(this).closest('tr').find('input[name="complementing_amount[]"]');
            var review_amount= $(this).closest('tr').find('input[name="review_amount[]"]');
            var class_type= $(this).closest('tr').find('select[name="class_type[]"]');

            if(id == 0 ){
                class_id.val('');
                quantity.val('');
                class_unit.select2("val",'');
                supplier_no.select2("val",'');
                class_status.select2("val",'');
                price.val('');
                complementing_amount.val('');
                review_amount.val('');
                class_type.select2("val", '');
                consignment_no.val('');
                return 0;
            }
            
            get_data('{$get_class_url}',{id:id},function(data){
                
                if (data.length == 1){
                    var item= data[0];
                    class_unit.select2("val", item.CLASS_UNIT);
                    class_status.select2("val", item.CLASS_STATUS);
                }else{
                    class_unit.select2("val", 0);
                    class_status.select2("val", 0);
                }
            });            

        });

    }
    
     var class_name_data_options = '{$class_name_data_options}';
     var class_unit_data_options = '{$class_unit_data_options}';
     var class_type_data_options = '{$class_type_data_options}';
     var supplier_data_options = '{$supplier_data_options}';
     var class_status_data_options = '{$class_status_data_options}';

    function addRow(){
        var rowCount = $('#details_tb tbody tr').length;
        if(rowCount == 0){
            count = count+1;
        }else {
            count = rowCount+1
        }
        var html ='<tr> <td><i class="glyphicon glyphicon-sort" /></i></td><td>  <input name="txt_ser_det[]" id="txt_ser_det'+count+'" class="form-control" value="0" style="text-align: center" readonly> </td> <td><select name="class_name[]" id="dp_class_id'+count+'" class="form-control sel22">'+class_name_data_options+'</select></td> <td><input  name="quantity[]"  class="form-control" id="txt_quantity'+count+'" style="text-align: center" /></td>  <td> <select name="class_unit[]" id="dp_class_unit'+count+'" class="form-control sel22" >'+class_unit_data_options+'</select> </td> <td> <select name="class_status[]" id="dp_class_status'+count+'" class="form-control sel22" >'+class_status_data_options+'</select> </td>  <td><input  name="price[]"  class="form-control" id="txt_price'+count+'" style="text-align: center" /></td> <td><input  name="complementing_amount[]"  class="form-control" id="txt_complementing_amount'+count+'" style="text-align: center"/></td> <td><input  name="review_amount[]"  class="form-control" id="txt_review_amount'+count+'" style="text-align: center" /></td> <td><select name="supplier_no[]" id="dp_supplier_no'+count+'" class="form-control sel22" >'+supplier_data_options+'</select></td><td><input  name="bill_no[]"  class="form-control" id="txt_bill_no'+count+'" style="text-align: center" /></td> <td><input  name="bill_date[]"  class="form-control the_date" id="txt_bill_date'+count+'" style="text-align: center" /></td>  <td><input  name="consignment_no[]"  class="form-control" id="txt_consignment_no'+count+'" style="text-align: center" /></td><td class="text-center"><a onclick="javascript:remove_tr(this);" href="javascript:;" ><i class="fa fa-trash"></i></a></td></tr>';
        $('#details_tb tbody').append(html);
        reBind(count);
    }    
        
    function  remove_tr(obj){
        var tr = obj.closest('tr');
        $(tr).closest('tr').css('background','tomato');
        $(tr).closest('tr').fadeOut(800,function(){
            $(this).remove();
        });
    }
           
    $(function() {
        $( "#details_tb tbody" ).sortable();
    });

    function clear_form(){
        clearForm($('#{$TB_NAME}_form'));
    }
    
    function print_report() {
        var rep_name = 'Car_maintenance';
        var ser = $('#txt_ser').val();
        
        var rep_url = '{$report_url}&report_type='+'pdf'+'&report='+rep_name+'&p_ser='+ser;
        _showReport(rep_url); 
    }

    $('button[data-action="submit"]').click(function(e){
        e.preventDefault();
        var msg= 'هل تريد حفظ الطلب ؟!';
        if(confirm(msg)){
            
            if ($('#dp_reasons_problem').val() == 0 ) {
                danger_msg('رسالة','يجب اختيار نوع سبب العطل ..');
                return;
            }            
            if ($('#dp_maintenance_type').val()  == 0 ) {
                danger_msg('رسالة','يجب اختيار نوع الصيانة ..');
                return;
            }
            
            if ($('#txt_technical_detection').val()  == '' ) {
                danger_msg('رسالة','يجب تعبئة الكشف الفني ..');
                return;
            }            
 
            $(this).attr('disabled','disabled');
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
        setTimeout(function() {
            $('button[data-action="submit"]').removeAttr('disabled');
        }, 3000);
    });

SCRIPT;

$scripts = <<<SCRIPT
    {$scripts}
    function adopt_(no){
        if(no==20 ) var msg= 'هل تريد اعتماد الطلب ؟!';
        if(no==30 ) var msg= 'هل تريد تأكيد الطلب ؟!';
        if(no==40 ) var msg= 'هل تريد تأكيد الطلب ؟!';

        if(confirm(msg)){
            var values= {ser: "{$rs['SER']}"};
            
            if(no > 20){
                if ($('#dp_reasons_problem').val() == 0 ) {
                    danger_msg('رسالة','يجب اختيار سبب العطل ..');
                    return;
                }
                if ($('#dp_maintenance_type').val()  == 0 ) {
                    danger_msg('رسالة','يجب اختيار نوع الصيانة ..');
                    return;
                }
                if ($('#txt_technical_detection').val()  == '' ) {
                    danger_msg('رسالة','يجب تعبئة الكشف الفني ..');
                    return;
                }
            }

            get_data('{$adopt_url}'+no, values, function(ret){
                if(ret==1){
                    success_msg('رسالة','تمت العملية بنجاح..');
                    $('button').attr('disabled','disabled');
            
                    setTimeout(function(){
                        get_to_link(window.location.href);
                    },1000);
                }else{
                    danger_msg('تحذير..',data);
                }
            }, 'html');
        }else{
        
        } 
    }
    </script>
SCRIPT;
sec_scripts($scripts);
?>
