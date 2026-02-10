<?php
/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 16/02/23
 * Time: 13:00 م
 */

$MODULE_NAME= 'salary';
$TB_NAME= 'Contact_data';

$post_url= base_url("$MODULE_NAME/$TB_NAME/".($action == 'index'?'create':$action));
$get_url= base_url("$MODULE_NAME/$TB_NAME/get");
$adopt_url= base_url("$MODULE_NAME/$TB_NAME/adopt_");
$back_url= base_url("$MODULE_NAME/$TB_NAME/index");

$isCreate =isset($master_tb_data) && count($master_tb_data)  > 0 ?false:true;
$HaveRs = (!$isCreate)? true:false;
$rs=$isCreate ?array() : $master_tb_data[0];

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
                        <?= $title ?>
                    </div>
                    <div class="flex-shrink-0">
                        <a class="btn btn-info" href="<?= $back_url ?>"><i class="fa fa-reply"></i> </a>
                    </div>
                </div><!-- end card header -->

                <div class="card-body">
                    <form class="form-vertical" id="<?=$TB_NAME?>_form" method="post" role="form" action="<?=$post_url?>" novalidate="novalidate">

                        <div class="row">

                            <div class="form-group col-sm-1">
                                <label>الرقم التسلسلي</label>
                                <input type="text" value="<?=$HaveRs?$rs['SER']:''?>" readonly id="txt_ser" class="form-control" />
                                <?php if (( isset($can_edit)?$can_edit:false)) : ?>
                                    <input type="hidden" value="<?=$HaveRs?$rs['SER']:''?>" name="ser" id="h_ser" />
                                <?php endif; ?>
                            </div>

                            <div class="form-group col-sm-2">
                                <label class="control-label">الموظف</label>
                                <div>
                                    <select name="emp_no" id="dp_emp_no" class="form-control sel2">
                                        <option value="">_________</option>
                                        <?php foreach ($emp_no_cons as $row) : ?>
                                            <option <?=$HaveRs?($rs['EMP_NO']==$row['EMP_NO']?'selected':''):''?> value="<?= $row['EMP_NO'] ?>"><?= $row['EMP_NO'] . ': ' . $row['EMP_NAME'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group col-sm-2">
                                <label>رقم الجوال</label>
                                <div>
                                    <input type="text" value="<?=$HaveRs?$rs['JAWAL_NO']:''?>" name="jawal_no" id="txt_jawal_no" class="form-control" maxlength="10" onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))"/>
                                </div>
                            </div>

                            <div class="form-group col-sm-2">
                                <label>رقم الجوال البديل</label>
                                <div>
                                    <input type="text" value="<?=$HaveRs?$rs['JAWAL_NO_2']:''?>" name="jawal_no_2" id="txt_jawal_no_2" class="form-control" maxlength="10" onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))"/>
                                </div>
                            </div>

                            <div class="form-group col-sm-2">
                                <label>رقم الهاتف الأرضي</label>
                                <div>
                                    <input type="tel" value="<?=$HaveRs?$rs['TEL_NO']:''?>" name="tel_no" id="txt_tel_no" class="form-control" maxlength="10" onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))"/>
                                </div>
                            </div>

                            <div class="form-group col-sm-2">
                                <label>الايميل</label>
                                <div>
                                    <input type="text" style="text-align:left"  value="<?=$HaveRs?$rs['EMAIL']:''?>" name="email" id="txt_email" class="form-control" />
                                </div>
                            </div>

                            <div class="form-group col-sm-2">
                                <label>مقاس الحذاء</label>
                                <div>
                                    <select name="shoes_measure" id="dp_shoes_measure" class="form-control sel2">
                                        <option value="">_________</option>
                                        <?php foreach ($measure_no as $row) : ?>
                                            <option <?=$HaveRs?($rs['SHOES_MEASURE']==$row['CON_NO']?'selected':''):''?>  value="<?=$row['CON_NO']?>"><?=$row['CON_NAME']?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group col-sm-2">
                                <label class="control-label">مقاس القميص</label>
                                <div>
                                    <select name="tshirt_measure" id="dp_tshirt_measure" class="form-control sel2">
                                        <option value="">_________</option>
                                        <?php foreach ($measure_letters as $row) : ?>
                                            <option <?=$HaveRs?($rs['TSHIRT_MEASURE']==$row['CON_NO']?'selected':''):''?>  value="<?=$row['CON_NO']?>"><?=$row['CON_NAME']?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group col-sm-2">
                                <label>مقاس البنطلون</label>
                                <div>
                                    <select name="pants_measure" id="dp_pants_measure" class="form-control sel2">
                                        <option value="">_________</option>
                                        <?php foreach ($measure_no as $row) : ?>
                                            <option <?=$HaveRs?($rs['PANTS_MEASURE']==$row['CON_NO']?'selected':''):''?>  value="<?=$row['CON_NO']?>"><?=$row['CON_NAME']?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group col-sm-2">
                                <label>مقاس الجاكيت</label>
                                <div>
                                    <select name="jacket_measure" id="dp_jacket_measure" class="form-control sel2">
                                        <option value="">_________</option>
                                        <?php foreach ($measure_letters as $row) : ?>
                                            <option <?=$HaveRs?($rs['JACKET_MEASURE']==$row['CON_NO']?'selected':''):''?>  value="<?=$row['CON_NO']?>"><?=$row['CON_NAME']?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <?php if (HaveAccess($post_url) && ($isCreate || ( isset($can_edit)?$can_edit:false) )  ) : ?>
                                <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
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
    
            if ( $('#txt_jawal_no_2').val() ==  $('#txt_jawal_no').val() ) {
                danger_msg('رسالة','يجب ان يختلف رقم الجوال عن رقم الجوال االبديل  ..');
                return;
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
    </script>
SCRIPT;
sec_scripts($scripts);
?>