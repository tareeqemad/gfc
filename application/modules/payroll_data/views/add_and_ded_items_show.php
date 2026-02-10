<?php
/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 22/06/22
 * Time: 09:20 ص
 */

$MODULE_NAME= 'payroll_data';
$TB_NAME= 'Add_and_ded_items';

$post_url= base_url("$MODULE_NAME/$TB_NAME/".($action == 'index'?'create':$action));
$get_url= base_url("$MODULE_NAME/$TB_NAME/get");
$adopt_url= base_url("$MODULE_NAME/$TB_NAME/adopt_");
$back_url= base_url("$MODULE_NAME/$TB_NAME/index");

$isCreate =isset($master_tb_data) && count($master_tb_data)  > 0 ?false:true;
$HaveRs = (!$isCreate)? true:false;
$rs=$isCreate ?array() : $master_tb_data[0];

$date_attr= " data-type='date' data-date-format='DD/MM/YYYY' data-val='true' data-val-regex-pattern='".date_format_exp()."' data-val-regex='Error' ";

?>

    <div class="page-header">
        <div>
            <h1 class="page-title"><?= $title ?></h1>
        </div>
        <div class="ms-auto pageheader-btn">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0);">الرواتب</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?= $title ?></li>
            </ol>
        </div>
    </div>

    <div class="row">
    <div class="col-xl-12">
    <div class="card">
        <div class="card-header align-items-center d-flex py-3">
            <div class="mb-0 flex-grow-1 card-title">
                الطلب
            </div>
            <div class="flex-shrink-0">
                <a class="btn btn-info" href="<?= $back_url ?>"><i class="fa fa-reply"></i> </a>
            </div>
        </div><!-- end card header -->
        <div class="card-body">

                <form class="form-vertical" id="<?=$TB_NAME?>_form" method="post" role="form" action="<?=$post_url?>" novalidate="novalidate">
                    <div class="row">

                            <div class="form-group col-sm-1">
                                <label>رقم البند</label>
                                <input type="text" value="<?=$HaveRs?$rs['NO']:''?>" readonly id="txt_ser" class="form-control" />
                                <?php if (( isset($can_edit)?$can_edit:false)) : ?>
                                    <input type="hidden" value="<?=$HaveRs?$rs['NO']:''?>" name="ser" id="h_ser" />
                                <?php endif; ?>
                            </div>

                            <div class="form-group col-sm-3">
                                <label>اسم البند</label>
                                <div>
                                    <input type="text" value="<?=$HaveRs?$rs['NAME']:''?>" name="item_name" id="txt_item_name" class="form-control"/>
                                </div>
                            </div>

                            <div class="form-group col-sm-2">
                                <label>الفئة</label>
                                <div>
                                    <select type="text" name="special" id="dp_special" class="form-control sel2" >
                                        <option value="">__________</option>
                                        <?php foreach ($special as $row) : ?>
                                            <option <?=$HaveRs?($rs['IS_SPECIAL']==$row['CON_NO']?'selected':''):''?> value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group col-sm-2">
                                <label>نوع البند</label>
                                <div>
                                    <select type="text" name="item_type" id="dp_item_type" class="form-control sel2" >
                                        <option value="">__________</option>
                                        <?php foreach ($item_type as $row) : ?>
                                            <option <?=$HaveRs?($rs['IS_ADD']==$row['CON_NO']?'selected':''):''?> value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group col-sm-2">
                                <label>نوع الاستحقاق أو الاستقطاع</label>
                                <div>
                                    <select type="text" name="constant" id="dp_constant" class="form-control sel2" >
                                        <option value="">__________</option>
                                        <?php foreach ($constant as $row) : ?>
                                            <option <?=$HaveRs?($rs['IS_CONSTANT']==$row['CON_NO']?'selected':''):''?> value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group col-sm-1">
                                <label>القيمة</label>
                                <div>
                                    <input type="text" value="<?=$HaveRs?$rs['VAL']:''?>" name="item_val" id="txt_item_val" class="form-control" onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))"/>
                                </div>
                            </div>

                            <div class="form-group col-sm-2">
                                <label>المجموعة</label>
                                <div>
                                    <select type="text" name="con_group" id="dp_con_group" class="form-control sel2" >
                                        <option value="">__________</option>
                                        <?php foreach ($con_group as $row) : ?>
                                            <option <?=$HaveRs?($rs['CON_G']==$row['C_NO']?'selected':''):''?>  value="<?= $row['C_NO'] ?>"><?= $row['C_NAME'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group col-sm-2">
                                <label>خصم الاجازة</label>
                                <div>
                                    <select type="text" name="vacancy_ded" id="dp_vacancy_ded" class="form-control sel2" >
                                        <option value="">__________</option>
                                        <?php foreach ($vacancy_ded as $row) : ?>
                                            <option <?=$HaveRs?($rs['VACANCY_DED']==$row['CON_NO']?'selected':''):''?>  value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group col-sm-2">
                                <label>خصم الغياب</label>
                                <div>
                                    <select type="text" name="absence_ded" id="dp_absence_ded" class="form-control sel2" >
                                        <option value="">__________</option>
                                        <?php foreach ($absence_ded as $row) : ?>
                                            <option <?=$HaveRs?($rs['IS_ABS']==$row['CON_NO']?'selected':''):''?> value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                    </div>
                    <div class="modal-footer">
                        <?php if ( 1  && ($isCreate || ( $rs['IS_UPDATE']==0 and isset($can_edit)?$can_edit:false) )  ) : ?>
                            <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                        <?php endif; ?>

                        <?php if ( $rs['IS_UPDATE']==0 and isset($can_edit)?$can_edit:false ) : ?>
                            <button type="button" id="btn_adopt_1" onclick='javascript:adopt_(1);' class="btn btn-success">اعتماد المدخل</button>
                        <?php endif; ?>
                    </div>
                </form>
                <div id="container">
                </div>


        </div>
    </div>
    </div>
    </div>

<?php
$scripts = <<<SCRIPT

<script type="text/javascript">

    $('.sel2').select2();
 
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
        if(no==1 ) var msg= 'هل تريد تأكيد الطلب ؟!';

        if(confirm(msg)){
            var values= {ser: "{$rs['NO']}"};
            
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