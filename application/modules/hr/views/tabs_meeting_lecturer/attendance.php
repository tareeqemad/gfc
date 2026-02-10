<?php
/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 15/08/23
 * Time: 14:50
 */

$MODULE_NAME= 'hr';
$TB_NAME= 'Meeting_lecturer';

$post_1_url= base_url("$MODULE_NAME/$TB_NAME/".($action == 'index'?'create':$action));
?>
<form class="form-vertical" id="<?=$TB_NAME?>_form_1" method="post" role="form" action="<?=$post_1_url?>" novalidate="novalidate">

            <div class="row">

                <div class="form-group col-sm-1">
                    <label>الرقم التسلسلي</label>
                    <input type="text" value="<?=$HaveRs?$rs['SER']:''?>" readonly id="txt_ser" class="form-control" />
                    <?php if (( isset($can_edit)?$can_edit:false)) : ?>
                        <input type="hidden" value="<?=$HaveRs?$rs['SER']:''?>" name="ser" id="h_ser" />
                    <?php endif; ?>
                </div>

                <div class="form-group col-sm-1">
                    <label>رقم الاجتماع</label>
                    <input type="text" value="<?=$HaveRs?$rs['MEETING_NO']:''?>" name="meeting_no" id="txt_meeting_no" class="form-control" onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))" />
                </div>

                <div class="form-group col-sm-2">
                    <label>عنوان الاجتماع</label>
                    <input type="text" value="<?=$HaveRs?$rs['MEETING_TITLE']:''?>" name="meeting_title" id="txt_meeting_title" class="form-control" />
                </div>


                <div class="form-group col-sm-2">
                    <label>تاريخ الاجتماع</label>
                    <div>
                        <input type="text" value="<?=$HaveRs?$rs['MEETING_DATE']:''?>" name="meeting_date" id="txt_meeting_date" class="form-control" onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))"/>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label>مكان الاجتماع</label>
                    <input type="text" value="<?=$HaveRs?$rs['MEETING_PLACE']:''?>" name="meeting_place" id="txt_meeting_place" class="form-control" />
                </div>

                <div class="form-group col-sm-1">
                    <label>وقت الاجتماع</label>
                    <input type="text" value="<?=$HaveRs?$rs['MEETING_TIME']:''?>" name="meeting_time" id="txt_meeting_time" class="form-control" />
                </div>

                <div class="form-group col-sm-2">
                    <label>مدة الاجتماع</label>
                    <input type="text" value="<?=$HaveRs?$rs['MEETING_DURATION']:''?>" name="meeting_duration" id="txt_meeting_duration" class="form-control" />
                </div>

                <div class="form-group col-sm-2">
                    <label>حالة الاجتماع</label>
                    <div>
                        <select name="meeting_status" id="dp_meeting_status" class="form-control sel2">
                            <option value="">_________</option>
                            <?php foreach ($meeting_status as $row) : ?>
                                <option <?=$HaveRs?($rs['MEETING_STATUS']==$row['CON_NO']?'selected':''):''?>  value="<?=$row['CON_NO']?>"><?=$row['CON_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-3">
                    <label>تصنيف الاجتماع</label>
                    <div>
                        <select name="meeting_type" id="dp_meeting_type" class="form-control sel2">
                            <option value="">_________</option>
                            <?php foreach ($meeting_type as $row) : ?>
                                <option <?=$HaveRs?($rs['MEETING_TYPE']==$row['CON_NO']?'selected':''):''?>  value="<?=$row['CON_NO']?>"><?=$row['CON_NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-3">
                    <label>عدد المواضيع المدرجة على جدول الأعمال</label>
                    <input type="text" value="<?=$HaveRs?$rs['TOPICS_INCLUDED']:''?>" name="topics_included" id="txt_topics_included" class="form-control" onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))" />
                </div>

                <div class="form-group col-sm-2">
                    <label>عدد المواضيع المدرجة ضمن مستجد</label>
                    <input type="text" value="<?=$HaveRs?$rs['TOPICS_NOVELTIES']:''?>" name="topics_novelties" id="txt_topics_novelties" class="form-control" onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))" />
                </div>

                <div class="form-group col-sm-2">
                    <label>عدد المواضيع المؤجلة</label>
                    <input type="text" value="<?=$HaveRs?$rs['TOPICS_DEFERRED']:''?>" name="topics_deferred" id="txt_topics_deferred" class="form-control" onkeypress="return (event.charCode !=8 && event.charCode ==0 || ( event.charCode == 46 || (event.charCode >= 48 && event.charCode <= 57)))" />
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered " id="attendance" data-container="container">
                        <thead class="table-primary">
                        <tr style="text-align: center">
                            <th style="width: 5%">م</th>
                            <th style="width: 20%">الاسم</th>
                            <th style="width: 15%">الوصف</th>
                            <th style="width: 10%">حالة الحضور</th>
                            <th style="width: 5%">الاجراء</th>
                        </tr>
                        </thead>

                        <tbody>

                        <?php if (count($master_det_data_d) > 0) { // تعديل
                            $count_d = -1;

                            foreach ($master_det_data_d as $row) {
                                $count_d++;
                                $d_count_d++;
                                ?>

                                <tr>
                                    <td>
                                        <input type="hidden" name="ser_d[]" id="ser_d<?= $count_d ?>" class="form-control" value="<?= $row['SER_D'] ?>" style="text-align: center" readonly>
                                        <input name="txt_ser_d[]" id="txt_ser_d<?= $d_count_d ?>" class="form-control" value="<?= $d_count_d ?>" style="text-align: center" readonly>
                                    </td>

                                    <td>
                                        <select name="emp_no[]" id="dp_emp_no<?= $count_d ?>" class="form-control sel2">
                                            <option value="">_________</option>
                                            <?php foreach ($emp_no_cons as $row2) : ?>
                                                <option value="<?= $row2['EMP_NO'] ?>" <?PHP if ($row['EMP_NO'] == $row2['EMP_NO']) echo " selected"; ?>><?= $row2['EMP_NO'] . ': ' . $row2['EMP_NAME'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>

                                    <td>
                                        <input name="description[]" class="form-control" id="description<?= $count_d ?>" value="<?= $row['DESCRIPTION'] ?>"style="text-align: center" />
                                    </td>

                                    <td>
                                        <select name="attendance_status[]" id="attendance_status<?=$count_d?>" class="form-control sel2" >
                                            <option value="">_________</option>
                                            <?php foreach ($attendance_status as $row2) : ?>
                                                <option value="<?= $row2['CON_NO'] ?>" <?PHP if ($row['ATTENDANCE_STATUS'] == $row2['CON_NO']) echo " selected"; ?>> <?= $row2['CON_NAME'] ?> </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>

                                    <td></td>
                                </tr>
                                <?php
                            }
                        }
                        ?>

                        </tbody>
                        <tfoot>
                        <tr>
                            <?php if (count($master_det_data_d) >= 0) { ?>
                                <th>
                                    <a onclick="javascript:addRow_attendance();" href="javascript:;"><i class="glyphicon glyphicon-plus"></i>جديد</a>
                                </th>
                            <?php } ?>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                        </tfoot>
                    </table>
                </div>

            </div>

        <div class="modal-footer">

            <?php if ( HaveAccess($post_1_url) && ($isCreate || ( $rs['ADOPT']==1 and isset($can_edit)?$can_edit:false) )  ) : ?>
                <button type="submit" data-action="submit" class="btn btn-primary me-2">حفظ البيانات</button>
            <?php endif; ?>

        </div>
</form>