<?php
/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 09/07/23
 * Time: 12:00 م
 */

$MODULE_NAME= 'salary';
$TB_NAME= 'Paid_fonts';

$post_url= base_url("$MODULE_NAME/$TB_NAME/".($action == 'index'?'create':$action));
$get_url= base_url("$MODULE_NAME/$TB_NAME/get");
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
                                <label>رقم الجوال</label>
                                <div>
                                    <input type="text" value="<?=$HaveRs?$rs['JAWAL_NO']:''?>" name="jawal_no" id="txt_jawal_no" class="form-control" maxlength="10" onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))"/>
                                </div>
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
                                <label>المبلغ المعمتد</label>
                                <div>
                                    <input type="text" value="<?=$HaveRs?$rs['APPROVED_AMOUNT']:''?>" name="approved_amount" id="txt_approved_amount" class="form-control" onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))"/>
                                </div>
                            </div>

                            <div class="form-group col-sm-2">
                                <label>قيمة الفاتورة</label>
                                <div>
                                    <input type="tel" value="<?=$HaveRs?$rs['BILL_VALUE']:''?>" name="bill_value" id="txt_bill_value" class="form-control"  onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))"/>
                                </div>
                            </div>

                            <div class="form-group col-sm-2">
                                <label>الجهة المستفيدة</label>
                                <div>
                                    <select name="beneficiary" id="dp_beneficiary" class="form-control sel2">
                                        <option value="">_________</option>
                                        <?php foreach ($beneficiary as $row) : ?>
                                            <option <?=$HaveRs?($rs['BENEFICIARY']==$row['ST_ID']?'selected':''):''?>  value="<?=$row['ST_ID']?>"><?=$row['ST_NAME']?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group col-sm-2">
                                <label>نوع الشريحة</label>
                                <div>
                                    <select name="slide_type" id="dp_slide_type" class="form-control sel2">
                                        <option value="">_________</option>
                                        <?php foreach ($slide_type as $row) : ?>
                                            <option <?=$HaveRs?($rs['SLIDE_TYPE']==$row['CON_NO']?'selected':''):''?>  value="<?=$row['CON_NO']?>"><?=$row['CON_NAME']?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group col-sm-2">
                                <label>ملاحظة</label>
                                <div>
                                    <input type="text"  value="<?=$HaveRs?$rs['NOTE']:''?>" name="note" id="txt_note" class="form-control" />
                                </div>
                            </div>

                            <div class="form-group col-sm-2">
                                <label>حالة الخط</label>
                                <div>
                                    <select name="line_status" id="dp_line_status" class="form-control sel2">
                                        <option value="">_________</option>
                                        <?php foreach ($line_status as $row) : ?>
                                            <option <?=$HaveRs?($rs['LINE_STATUS']==$row['CON_NO']?'selected':''):''?>  value="<?=$row['CON_NO']?>"><?=$row['CON_NAME']?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">

                            <?php if ( HaveAccess($post_url)  && ($isCreate || ( isset($can_edit)?$can_edit:false) )  ) : ?>
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