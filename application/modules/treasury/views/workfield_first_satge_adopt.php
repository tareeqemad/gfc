<?php

$MODULE_NAME= 'treasury';
$TB_NAME= 'workfield';
$cancel_bills_url= base_url("$MODULE_NAME/$TB_NAME/cancel_bills");
$cancel_adopt1= base_url("$MODULE_NAME/$TB_NAME/cancel_adopt1");
$cancel_adopt2= base_url("$MODULE_NAME/$TB_NAME/cancel_adopt2");
$return_adopt1= base_url("$MODULE_NAME/$TB_NAME/return_adopt1");
$return_adopt = base_url("$MODULE_NAME/$TB_NAME/return_adopt");
$update_document_url= base_url("$MODULE_NAME/$TB_NAME/update_document");
$edit_bills_url= base_url("$MODULE_NAME/$TB_NAME/edit_bills");
$report_bills_url= base_url("$MODULE_NAME/$TB_NAME/report_bills");
$adopt_bills_url= base_url("$MODULE_NAME/$TB_NAME/adopt");
    /***تأكيد الالغاء***/
$adopt_cancel_bills_url=base_url("$MODULE_NAME/$TB_NAME/adopt_cancel");
$count = 0; $count_canceld = 0; $canceld_flag = 0; $count_for_check = 0;
$count_for_check_cancel = 0; $count_for_update = 0; $check_for_update = 0;
$sum = 0; $sum_canceld = 0; $sum_transfered = 0; $sum_public = 0;
$cancel_check_new=0;
    foreach ($bills as $bill){
        if($bill['STATUS'] == 5||$bill['STATUS'] == 8)
        {
            $cancel_check_new++;
        }
        if ( $bill['STATUS'] == 9 || $bill['STATUS'] == 3 ) {
           // if ( $bill['STATUS'] == 9 || $bill['STATUS'] == 3 ) {
            $sum_canceld+= $bill['THE_VALUE'];
            $canceld_flag++;
            if($bill['STATUS'] == 3){
                $count_for_check_cancel++;
            }
        } else {
            if ( $bill['STATUS'] == 1 || $bill['STATUS'] == 7 ) {
                $count_for_check++;
            }
            if ( $bill['STATUS'] == 2 ) {
                $count_for_update++;
            }
            if ( $bill['STATUS'] == 6 ) {
                $check_for_update++;
            }
            if ( $bill['STATUS'] == 1 || $bill['STATUS'] == 7|| $bill['STATUS'] == 5|| $bill['STATUS'] == 8 ) {
                if ( $bill['SOURCE'] == 2 ) {
                    $sum_public++;
                }
                $sum+= $bill['THE_VALUE'];
            }
            else if($bill['STATUS'] == 2 ||  $bill['STATUS'] == 4 || $bill['STATUS'] == 6)
                $sum_transfered+= $bill['THE_VALUE'];
        }
    }

?>

<div class="row">
    <div class="toolbar">
        <div class="caption"><?= $title ?> <strong><?= isset($user[0]['NAME']) ? $user[0]['NAME'] : '' ?></strong></div>
        <ul>
            <li><a href="<?= $back_url ?>"><i class="icon icon-reply"></i> </a></li>
        </ul>
    </div>

    <div class="form-body">
        <div class="alert alert-info"> سندات التحصيل الميداني الخاصة للموظف /
            <strong><?= isset($user[0]['NAME']) ? $user[0]['NAME'] : '' ?></strong>
        </div>

        <form class="form-vertical inline_form" method="post" action="" reload-parent="true">
            <div class="form-group">
                <label class="control-label col-sm-2">رقم المستند</label>
                <div class="col-sm-3">
                    <?php if($count_for_update == (count($bills) - $count_for_check_cancel) || (int)$teller >= 0){ ?>
                        <select name="dp_bank_doc" id="dp_bank_doc" class="form-control">
                            <?php foreach($bank_doc as $row) :?>
                                <option value="<?=$row['NO']?>"<?= $bank_doc_value == $row['NO'] ? 'selected' : '' ?> ><?= $row['NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    <?php } else { ?>
                        <input type="text" readonly id="txt_bank_doc_id" value="<?=$bank_doc?>"  class="form-control "/>
                    <?php }  ?>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-sm-2">رقم العملية</label>
                <div class="col-sm-3">
                    <input type="text" readonly id="txt_operation" value="<?=$operation?>" class="form-control "/>
                </div>
            </div>
        </form>
        <br> <br>
        <div class="container">
            <div class="info">
                <span class="alert alert-info box floatLeft mr-20"> اجمالي المعتمد<strong class="" id="total-selected"><?=$sum_transfered?></strong></span>
                <span class="alert alert-danger box floatLeft">اجمالي غير المعتمد<strong class="sum_bill"><?=$sum?></strong></span>
                <?php if( HaveAccess($adopt_bills_url) && $count_for_check > 0 && $count_for_check_cancel == 0 && $sum_public == 0&&$cancel_check_new == 0){  ?>
                        <?php if($user_type == 1){ ?>
                        <button class="btn btn-success" style="line-height: 95px;" onclick="javascript:Adopt_selected_bills(<?=$user_id?> , <?=$user_date?> );">اعتماد السندات</button>
                    <?php } else { ?>
                        <button class="btn btn-success" style="line-height: 95px;" onclick="javascript:Adopt_selected_bills_comp(<?=$user_id?> , <?=$user_date?>, <?=$comp_date?> );">اعتماد السندات</button>
                    <?php }  ?>
                <?php } else if($cancel_check_new > 0) { ?>
                    <span class="badge badge-3">يرجى تأكيد الايصالات الملغية من المحصل</span>
                <?php }
                else if($count_for_check > 0 && $count_for_check_cancel > 0) { ?>
                    <span class="badge badge-3">يرجى تأكيد الايصالات الملغية من المحصل</span>
                <?php }else if($sum_public > 0){ ?>
                    <span class="badge badge-3">يرجى تأكيد ايصالات التحصيل العام </span>
                <?php } else { ?>

                <?php }  ?>
            </div>
        </div>

        <table class="table info" id="Tbl">
            <thead>
            <tr>
                <th>#</th>
                <th class="hidden"><input type="checkbox"  class="group-checkable" data-set="#Tbl .checkboxes"/></th>
                <th>الاشتراك</th>
                <th>الاسم</th>
                <th>العنوان</th>
                <th>رقم الموبايل</th>
                <th>الشهر</th>
                <th>المبلغ</th>
                <th>التاريخ</th>
                <th>حالة الإيصال</th>
                <th>رقم الإيصال</th>
                <th>مستند بنك </th>
                <th>ملاحظات </th>
                <th></th>
            </tr>
            </thead>
            <tbody>
                <?php foreach ($bills as $row) :
                     if($row['STATUS'] != 9 && $row['STATUS'] != 3 ){
                        $count++;
                    if($row['STATUS'] == 2 && $row['SOURCE'] == 2)
                        echo '<tr class="case_3">';
                    else if($row['STATUS'] == 3 )
                        echo '<tr class="case_5">';
                    else if($row['STATUS'] == 8 )
                        echo '<tr class="case_5">';
                    else if($row['STATUS'] == 9)
                        echo '<tr class="case_0">';
                    else if($row['STATUS'] == 1 && $row['SOURCE'] == 2)
                        echo '<tr class="case_3">';
                    else
                        echo '<tr class="">';
                ?>
                    <td><?= $count ?></td>
                    <td class="hidden"> <?php if ($row['SOURCE'] != 2&&$row['STATUS'] != 3&&$row['STATUS'] != 9): ?>
                            <input type="checkbox" class="checkboxes"
                                   checked
                                   data-value="<?= $row['THE_VALUE'] ?>"
                                   value="<?= $row['SER'] ?>"/>
                        <?php endif; ?>
                    </td>
                    <td><?= $row['SUBSCRIBE'] ?></td>
                    <td><?= $row['NAME'] ?></td>
                    <td><?= $row['ADDRESS'] ?></td>
                    <td><?= $row['MOBILE'] ?></td>
                    <td><?= $row['MONTH'] ?></td>
                    <td><strong><?= $row['THE_VALUE'] ?></strong></td>
                    <td><?= $row['CDATE'] ?></td>
                    <td><?= $row['STATUS_NAME'] ?> </td>
                    <td><?= $row['USERID']."/". $row['EMP_SERIAL'] ?> </td>
                         <td><?= $row['RECORD_NO'] ?> </td>
                         <td><?= $row['NOTE_1'] ?> </td>
                    <td class="text-right">
                        <button type="button"
                                class="btn btn-xs btn-danger"
                                onclick="javascript:addNote(this,<?= $row['SER'] ?>);">إضافة ملاحظة
                        </button>
                        <?php  if($row['IMAGE_NAME'] != "") {  ?>
                            <a target="_blank" href="https://iapps.gedco.ps/uploads/images/<?= $row['IMAGE_NAME'] ?>"
                               class="btn btn-xs btn-warning">عرض صورة الايصال</a>
                                <!--<a target="_blank" href="https://iappstest.gedco.ps/uploads/images/<?= $row['IMAGE_NAME'] ?>"-->

                        <?php   } ?>
                        <?php  if($row['STATUS'] == 3 && HaveAccess($adopt_cancel_bills_url)) {  ?>
                        <button type="button"
                                class="btn btn-xs btn-success"
                                onclick="javascript:adopt_cancel_workfield_bill(this,<?= $row['SER'] ?>);"> تأكيد الإلغاء
                        </button>
                        <?php  } else if($row['STATUS'] == 1 && HaveAccess($cancel_bills_url) ) { ?>
                            <button type="button"
                                    class="btn btn-xs btn-danger"
                                    onclick="javascript:cancel_workfield_bill_adopt(this,<?= $row['SER'] ?>,5);"> إلغاء السند
                            </button>

                        <?php }
                        else if($row['STATUS'] == 5 && HaveAccess($cancel_adopt1) ) { ?>
                            <button type="button"
                                    class="btn btn-xs btn-danger"
                                    onclick="javascript:cancel_workfield_bill_adopt(this,<?= $row['SER'] ?>,8);">اعتماد الالغاء المدير المالي
                            </button>

                        <?php  if(  HaveAccess($return_adopt) ) { ?>

                            <button type="button"
                                    class="btn btn-xs btn-danger"
                                    onclick="javascript:cancel_workfield_bill_adopt(this,<?= $row['SER'] ?>,1);">ارجاع الغاء السند
                            </button>
<?php } ?>
                        <?php }


                        else if($row['STATUS'] == 8 && HaveAccess($cancel_adopt2) ) { ?>
                            <button type="button"
                                    class="btn btn-xs btn-danger"
                                    onclick="javascript:cancel_workfield_bill_adopt(this,<?= $row['SER'] ?>,9);">اعتماد الالغاء (رقابة)
                            </button>

                          <?php  if(  HaveAccess($return_adopt1) ) { ?>
                            <button type="button"
                                    class="btn btn-xs btn-danger"
                                    onclick="javascript:cancel_workfield_bill_adopt(this,<?= $row['SER'] ?>,5);">ارجاع للمدير المالي
                            </button>
<?php }?>

                      <?php }

                        if ($row['SOURCE'] == 2 && $row['STATUS'] == 1 && HaveAccess($cancel_bills_url)){ ?>
                            <button type="button"
                                    class="btn btn-xs btn-default"
                                    onclick="javascript:_showNewModal('<?= base_url('treasury/workfield/billdetails/'.$row['SER']) ?>','تفاصيل السند');"> تحرير و اعتماد
                            </button>
                        <?php }  ?>

                    </td>
                </tr>
                <?php
                    } endforeach;
                ?>
            </tbody>
            <tfoot>
            <tr>
                <td colspan="6"><strong>مجموع المعتمد</strong></td>
                <td><strong><?= $sum_transfered ?></strong></td>
                <td colspan="4"></td>
            </tr>
            </tfoot>
        </table>
        <div class="modal-footer">
            <?php if( HaveAccess($update_document_url) && $check_for_update == 0):  ?>
                    <button type="button" onclick="javascript:update_document(<?=$teller?>);" class="btn btn-primary">تعديل رقم المستند</button>
            <?php endif; ?>
            <?php if( HaveAccess($report_bills_url)):  ?>
                    <button type="button" onclick="javascript:print_report(<?=isset($user[0]['NO']) ? $user[0]['NO'] : '' ?>, <?=$this->user->id?>, <?=$teller?>, <?=$user_type == 1 ? $user_date : $comp_date?>,<?=$user_type?>);" class="btn btn-success"><span class="glyphicon glyphicon-print"></span>عرض التقرير</button>
            <?php endif; ?>
        </div>
        <?php if($canceld_flag > 0){ ?>
            <br><hr><br>
            <div class="alert alert-danger col-md-10 col-md-offset-1" >  سندات التحصيل الملغية  </div>
            <table class="table info" id="Tbl">
                <thead>
                <tr>
                    <th>#</th>
                    <th>الاشتراك</th>
                    <th>الاسم</th>
                    <th>العنوان</th>
                    <th>رقم الموبايل</th>
                    <th>الشهر</th>
                    <th>المبلغ</th>
                    <th>التاريخ</th>
                    <th>حالة الإيصال</th>
                    <th>ملاحظات</th>
                    <th style="width: 170px;"></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($bills as $row) :
                    if($row['STATUS'] == 9 || $row['STATUS'] == 3 ){ $count_canceld++;
                        if($row['STATUS'] == 3 )
                            echo '<tr class="case_5">';
                        else if($row['STATUS'] == 9)
                            echo '<tr class="case_0">';
                        ?>
                            <td><?= $count_canceld ?></td>
                            <td class="hidden"> <?php if ($row['STATUS'] == 3 || $row['STATUS'] == 9): ?>
                                    <input type="checkbox" class="checkboxes"
                                           checked
                                           data-value="<?= $row['THE_VALUE'] ?>"
                                           value="<?= $row['SER'] ?>"/>
                                <?php endif; ?>
                            </td>
                            <td><?= $row['SUBSCRIBE'] ?></td>
                            <td><?= $row['NAME'] ?></td>
                            <td><?= $row['ADDRESS'] ?></td>
                            <td><?= $row['MOBILE'] ?></td>
                            <td><?= $row['MONTH'] ?></td>
                            <td><strong><?= $row['THE_VALUE'] ?></strong></td>
                            <td><?= $row['CDATE'] ?></td>
                            <td><?= $row['STATUS_NAME'] ?> </td>
                            <td><?= $row['CANCEL_NOTE'] ?> </td>
                            <td class="text-right">
                            <?php if( HaveAccess($cancel_bills_url)):
                                if($row['STATUS']==3) {
                                    ?>
                                    <?php if( $row['TELLER_SERIAL'] == 0 && HaveAccess($adopt_cancel_bills_url)):?>

                                        <button type="button"
                                                class="btn btn-xs btn-success"
                                                onclick="javascript:adopt_cancel_workfield_bill(this,<?= $row['SER'] ?>);"> تأكيد الإلغاء
                                        </button>
                                    <?php endif; } endif; ?>

                            </td>
                        </tr>
                    <?php } endforeach;?>
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="6"><strong>المجموع</strong></td>
                    <td><strong><?= $sum_canceld ?></strong></td>
                    <td colspan="3"></td>
                </tr>
                </tfoot>
            </table>
        <?php } ?>


    </div>
</div>
