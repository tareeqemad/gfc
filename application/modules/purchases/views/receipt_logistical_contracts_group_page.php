<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

$count1 = 0;
$MODULE_NAME = 'purchases';
$TB_NAME = 'Receipt_logistical_contracts';

$record_member_url = base_url("$MODULE_NAME/$TB_NAME/record_record_member");

?>

<div class="tbl_container">
    <table class="table" id="receipt_class_input_groupTbl" data-container="container">
        <thead>
        <tr>
            <th> #</th>
            <th> الرقم الوظيفي</th>
            <th> رقم الهوية</th>
            <th>اسم العضو</th>
            <th>ملاحظات العضو</th>
            <th>حضور؟</th>
            <?php if (count($group_rec_logistic_details) > 0) { ?>
                <th>اعتماد محضر فحص و استلام</th><?php } ?>
            <?php if (count($group_rec_logistic_details) > 0) { ?>
                <th>تاريخ الإعتماد</th><?php } ?>
            <?php if (count($group_rec_logistic_details) > 0) { ?>
                <th>وقت الإعتماد</th><?php } ?>

        </tr>
        </thead>
        <tbody>
        <?php
        if (count($group_rec_logistic_details) <= 0) {
            foreach ($rec_groups as $row1) : ?>
                <?php $count1++; ?>
                <tr>
                    <td><?= ($count1 ) ?>
                        <input type="hidden" id="h_group_ser_<?= $count1 ?>" name="h_group_ser[]"
                               value="0">
                        <input type="hidden" id="h_logistic_ser_<?= $count1 ?>" name="h_logistic_ser[]"
                               value="<?= $rec_ser ?>">


                    </td>
                    <td>

                        <input type="text" name="emp_no[]" data-val="true" value="<?= $row1['EMP_NO'] ?>"
                               data-val-required="حقل مطلوب" id="emp_no_<?= $count1 ?>" class="form-control col-sm-8">

                    </td>
                    <td>
                        <input type="text" name="group_person_id[]" value="<?= $row1['ID_NO'] ?>"
                               id="group_person_id_<?= $count1 ?>" class="form-control col-sm-8">
                    </td>
                    <td><input type="text" name="group_person_date[]" data-val="true"
                               id="group_person_date_<?= $count1 ?>"
                               value="<?= $row1['NAME'] ?>" class="form-control"></td>
                    <td><input type="text" name="member_note[]" data-val="true" id="member_note_<?= $count1 ?>"
                               value="" class="form-control"></td>

                    <td><input type="checkbox"
                               name="status[<?= $count1 ?>]" checked
                               value="1"
                               id="status_<?= $count1 ?>" class="form-control">
                    </td>


                </tr>

            <?php endforeach;
        } else {

            ?>
            <?php foreach ($group_rec_logistic_details as $row1) : ?>
                <?php $count1++; ?>
                <tr>
                    <td><?= ($count1) ?>
                        <input type="hidden" id="h_group_ser_<?= $count1 ?>" name="h_group_ser[]"
                               value="<?= $row1['SER'] ?>">
                        <input type="hidden" id="h_logistic_ser_<?= $count1 ?>" name="h_logistic_ser[]"
                               value="<?= $rec_ser ?>">


                    </td>
                    <td>

                        <input type="text" name="emp_no[]" data-val="true" value="<?= $row1['EMP_NO'] ?>"
                               data-val-required="حقل مطلوب" id="emp_no_<?= $count1 ?>" class="form-control col-sm-8">

                    </td>
                    <td>
                        <input type="text" name="group_person_id[]" value="<?= $row1['GROUP_PERSON_ID'] ?>"
                               id="group_person_id_<?= $count1 ?>" class="form-control col-sm-8">
                    </td>
                    <td><input type="text" name="group_person_date[]" data-val="true"
                               id="group_person_date_<?= $count1 ?>"
                               value="<?= $row1['GROUP_PERSON'] ?>" class="form-control"></td>
                    <td><input type="text" name="member_note[]" data-val="true" id="member_note_<?= $count1 ?>"
                               value="<?= $row1['MEMBER_NOTE'] ?>" class="form-control"></td>

                    <td><input type="checkbox"
                               name="status[<?= $count1 ?>]" <?php if ($row1['STATUS'] == 1) echo "checked"; ?>
                               value="<?php if ($row1['STATUS'] == 1) echo "1"; else echo "2"; ?>"
                               id="status_<?= $count1 ?>" class="form-control">
                    </td>


                    <td>
                        <?php
                        if ($row1['STATUS'] == 1) {
                            if (HaveAccess($record_member_url) and $EMP_NO == $row1['EMP_NO'] and $row1['ADOPT'] == '') {
                                echo " <button type='button' id='btn_group" . $count1 . "' onclick='{$TB_NAME}_memebers_record(this);' class='btn btn-primary btn_recordd1' data-dismiss='modal'>اعتماد الفحص والاستلام</button>";

                            } else {
                                echo $row1['ADOPT_NAME'];
                            }
                        } else {
                            echo $row1['ADOPT_NAME'];
                        }


                        ?>

                    </td>
                    <td><input readonly type="text" name="adopt_date[]" id="adopt_date_<?= $count1 ?>"
                               value="<?= $row1['ADOPT_DATE'] ?>" class="form-control"></td>


                    <td><input type="text" readonly name="adopt_time[]" id="adopt_time_<?= $count1 ?>"
                               value="<?= $row1['ADOPT_TIME'] ?>" class="form-control"></td>

                    <td>
                </tr>
            <?php endforeach;
        } ?>

        </tbody>
        <tfoot>
        <tr>
            <th class="align-right" colspan="9">

                <a onclick="addRowGroup();" onfocus="addRowGroup();" href="javascript:"><i
                        class="glyphicon glyphicon-plus"></i>جديد</a>

            </th>


        </tr>
        </tfoot>
    </table>
</div>


<script>
    if (typeof initFunctions == 'function') {
        initFunctions();
    }


</script>