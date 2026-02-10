<?php
/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 16/03/22
 * Time: 09:00 ص
 */

$MODULE_NAME= 'payment';
$TB_NAME= 'Car_maintenance';

$post_url= base_url("$MODULE_NAME/$TB_NAME/".($action == 'index'?'create':$action));
$get_url= base_url("$MODULE_NAME/$TB_NAME/get");
$get_car_info =base_url("$MODULE_NAME/$TB_NAME/public_get_car_info");
$adopt_url= base_url("$MODULE_NAME/$TB_NAME/adopt_");
$back_url= base_url("$MODULE_NAME/$TB_NAME/index");

$isCreate =isset($master_tb_data) && count($master_tb_data)  > 0 ?false:true;
$HaveRs = (!$isCreate)? true:false;
$rs=$isCreate ?array() : $master_tb_data[0];

$date_attr= " data-type='date' data-date-format='DD/MM/YYYY' data-val='true' data-val-regex-pattern='".date_format_exp()."' data-val-regex='Error' ";

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
            <form class="form-vertical" id="<?=$TB_NAME?>_form" method="post" role="form" action="<?=$post_url?>" novalidate="novalidate">
                <div class="modal-body inline_form">

                    <div class="col-sm-10">

                        <div class="form-group col-sm-1">
                            <label class="control-label">رقم الطلب</label>

                            <input type="text" value="<?=$HaveRs?$rs['SER']:''?>" readonly id="txt_ser" class="form-control" />
                            <?php if (( isset($can_edit)?$can_edit:false)) : ?>
                                <input type="hidden" value="<?=$HaveRs?$rs['SER']:''?>" name="ser" id="h_ser" />
                            <?php endif; ?>

                        </div>

                        <div class="form-group col-sm-3">
                            <label class="control-label">رقم السيارة</label>
                            <div>
                                <select data-val="true" name="car_id" id="txt_car_id" class="form-control sel2">
                                    <option value="">_________</option>
                                    <?php foreach ($con_group as $row) : ?>
                                        <option <?=$HaveRs?($rs['CAR_ID']==$row['CAR_NUM']?'selected':''):''?> value="<?= $row['CAR_NUM'] ?>" ><?= $row['CAR_OWNER'] ?> : <?= $row['CAR_NUM'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group col-sm-3">
                            <label class="control-label">صاحب العهدة</label>
                            <div>
                                <select data-val="true" name="car_owner" id="dp_car_owner" class="form-control sel2">
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
                                <select data-val="true" name="car_ownership" id="dp_car_ownership" class="form-control sel2">
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
                                <select type="text" name="car_type" id="dp_car_type" class="form-control sel2 ">
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
                                <select name="branch_id" id="dp_branch_id" class="form-control sel2">
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
                                <select name="driver_id" id="dp_driver_id" class="form-control sel2">
                                    <option value="">_________</option>
                                    <?php foreach($emp_no_cons as $row) :?>
                                        <option <?=$HaveRs?($rs['DRIVER_ID']==$row['EMP_NO']?'selected':''):''?> value="<?=$row['EMP_NO']?>"><?=$row['EMP_NO'].': '.$row['EMP_NAME']?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group col-sm-6">
                            <label class="control-label">وصف العطل</label>
                            <div>
                                <textarea value="" data-val-required="حقل مطلوب" class="form-control" name="des_problem" rows="1" id="txt_des_problem"  required><?=$HaveRs?$rs['DES_PROBLEM']:''?></textarea>
                            </div>
                        </div>

                    </div>

                </div>

                <div class="modal-footer">
                    <?php if ( HaveAccess($post_url)  && ($isCreate || ( $rs['ADOPT']==1 and isset($can_edit)?$can_edit:false) )  ) : ?>
                        <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                    <?php endif; ?>

                    <?php if (HaveAccess($adopt_url.'10') and $rs['ADOPT']==1 ) : ?>
                        <button type="button" id="btn_adopt_10" onclick='javascript:adopt_(10);' class="btn btn-success">اعتماد المدخل</button>
                    <?php endif; ?>

                </div>

            </form>
        </div>
    </div>
</div>

<?php
$scripts = <<<SCRIPT

<script type="text/javascript">

    $('.sel2').select2();
    $('#dp_car_type').prop('readonly', true);
    $('#dp_car_type').attr('readonly', true);
    
    $('#dp_branch_id').prop('readonly', true);
    $('#dp_branch_id').attr('readonly', true);
    
    $('#dp_car_owner').prop('readonly', true);
    $('#dp_car_owner').attr('readonly', true);
    
    $('#dp_car_ownership').prop('readonly', true);
    $('#dp_car_ownership').attr('readonly', true);

    function clear(){
        $('#txt_definition_code').val('');
        $('#txt_car_model').val('');
        $('#dp_car_owner').select2("val",'');
        $('#dp_car_type').select2("val",'');
        $('#dp_branch_id').select2("val",'');
        $('#dp_car_ownership').select2("val",'');
    }

    $('#txt_car_id').change(function(){
    
        var car_id =  $('#txt_car_id').val();
        
        if (car_id == '') {
            clear();
            return -1;
        } else{
            clear();
            get_data('{$get_car_info}', {car_id: car_id}, function (data) {
                $.each(data, function (i, value) {
                    $('#txt_definition_code').val(value.DEFINITION_CODE);
                    $('#dp_car_owner').select2("val",value.CAR_FILE_ID);
                    $('#dp_car_type').select2("val",value.CAR_CLASS);
                    $('#txt_car_model').val(value.CAR_MODEL);
                    $('#dp_branch_id').select2("val",value.BRANCH_ID);
                    $('#dp_car_ownership').select2("val",value.CAR_OWNERSHIP);
                });
            });
            }
    });
    
    $('button[data-action="submit"]').click(function(e){
        e.preventDefault();
        var msg= 'هل تريد حفظ الطلب ؟!';
        if(confirm(msg)){
                                 
            if ($('#txt_car_id').val()  == '' || $('#txt_car_id').val()  == 0 ) {
                danger_msg('رسالة','يرجى التأكد من رقم السيارة ..');
                return 0;
            }
                                        
            if ($('#dp_driver_id').val()  == '' || $('#dp_driver_id').val()  == 0 ) {
                danger_msg('رسالة','يجب ادخال اسم السائق ..');
                return 0;
            }
                              
            if ($('#txt_des_problem').val()  == '' || $('#txt_des_problem').val()  == 0 ) {
                danger_msg('رسالة','يجب ادخال وصف العطل ..');
                return 0;
            }
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

  
SCRIPT;

    $scripts = <<<SCRIPT
    {$scripts}

    function adopt_(no){
        if(no==10 ) var msg= 'هل تريد تأكيد الطلب ؟!';

        if(confirm(msg)){
            var values= {ser: "{$rs['SER']}"};
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