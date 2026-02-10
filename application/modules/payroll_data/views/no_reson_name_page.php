<?php
function tr_color($is_shift_emp){
    if ($is_shift_emp ==  1  ) {
        return '#fff7f4';
    }
}
$count = $offset;
$MODULE_NAME= 'payroll_data';
$TB_NAME= 'no_reson_name';
$no_signed_reson_cons = $this->rmodel->getAll('TRANSACTION_PKG', 'NO_SIGNED_RESON_ALL'); //array of constant
$change_reson_name_url= base_url("$MODULE_NAME/$TB_NAME/change_reson_name");

$MODULE_NAME_EXIT= 'hr_attendance';
$TB_NAME_EXIT= 'exit_permission';
$get_exit_url =base_url("$MODULE_NAME_EXIT/$TB_NAME_EXIT/get/");
$page_act = 'move_admin';
?>
<div class="table-responsive">
    <table class="table table-bordered" id="page_tb">
        <thead class="table-light">
        <tr>
            <th>م</th>
            <th>المقر</th>
            <th>الموظف</th>
            <th>الشهر</th>
            <th>اليوم</th>
            <th>تاريخ الحضور</th>
            <th>وقت الحضور</th>
            <th>مكان البصمة</th>
            <th>تاريخ الانصراف</th>
            <th>وقت الانصراف</th>
            <th style="width: 10%;">السبب</th>
            <th>الحالة</th>
            <th>الاذن</th>
        </tr>
        </thead>
        <tbody>
        <?php if ($page > 1): ?>
            <tr>
                <td colspan="11" id="page-<?= $page ?>" class="page-sector" data-page="<?= $page ?>"></td>
            </tr>
        <?php endif; ?>
        <?php
        foreach ($page_rows as $row) :?>

            <tr id="tr_<?= $row['THE_MONTH'] ?>" style="background-color: <?=tr_color($row['IS_SHIFT_EMP']) ?>">
                <td>
                    <?= $count ?>
                    <input type="hidden" id="h_p_ser" value="<?=$row['P_SER']?>">
                    <input type="hidden" id="h_emp_no" value="<?=$row['NO']?>">
                    <input type="hidden" id="h_the_month"  value="<?=$row['THE_MONTH']?>">
                </td>
                <td><?= $row['BRANCH_NAME'] ?></td>
                <td><?= $row['NO']?> - <?= $row['EMP_NAME'] ?></td>
                <td><?= $row['THE_MONTH'] ?></td>
                <td><?= $row['DAY_AR'] ?></td>
                <td><?= $row['TIME_I'] ?></td>
                <td><?= $row['PERIOD_TIME_IN'] ?></td>
                <td><?= $row['RECORD_BRANCH_I'] ?></td>
                <td><?= $row['TTIME_O'] ?></td>
                <td><?= $row['PERIOD_TIME_OUT'] ?></td>
                <td style="width: 10%;">
                    <?php if (HaveAccess($change_reson_name_url) && $row['ALLOW_EDIT'] == 0) { ?>
                        <select id="dp_reson_name_<?= $row['RESON'] ?>"
                                name="reson_name[<?= $row['RESON'] ?>]"
                                class="form-control reson" onchange="is_reson_name_change(this);">
                            <option value=""></option>
                            <?php  $chosenRESON_id = $row['RESON'];?>
                            <?php foreach ($no_signed_reson_cons as $row1){?>
                                <option <?=($chosenRESON_id==$row1['NO']?'selected="selected"':'')?> value="<?=$row1['NO']?>"><?=$row1['RESON']?></option>
                            <?php }
                            ?>
                        </select>
                    <?php }  elseif (HaveAccess ($update_is_active_url) && $row['ALLOW_EDIT'] >= 1) { ?>
                        <?=$row['RESON_NAME']?> - لا يمكن التعديل على كشف معتمد
                    <?php } else { ?>
                        <?= $row['RESON_NAME'] ?>
                    <?php } ?>
                </td>
                <td class="far_val text-center">
                    <?php if ($row['IS_ACTIVE'] == 1) { ?>
                        <i id="<?= $row['P_SER'] ?>" class="fa fa-meh-o" style="color: red;" title="يخصم"></i>
                    <?php } elseif ($row['IS_ACTIVE'] == 0) { ?>
                        <i id="<?= $row['P_SER'] ?>" class="fa fa-smile-o fa-lg" style="color: #63be7b;"
                           title="لا يخصم"></i>
                    <?php } ?>
                </td>
                <td>
                    <?php if ($row['SER_EXIT'] >= 1) {?>
                        <a href="<?=$get_exit_url?><?=$row['SER_EXIT']?>/<?=$page_act?>" class="modal-effect"   title="عرض الاذن" style="color: #2075f8">
                            <i class="fa fa-eye"></i>
                        </a>
                    <?php } ?>
                </td>
                <?php $count++; ?>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php echo $this->pagination->create_links(); ?>
<script>
    if (typeof initFunctions == 'function') {
        initFunctions();
    }
    if (typeof ajax_pager == 'function') {
        ajax_pager();
    }
    /*if (typeof reson_select2 == 'function') {
        reson_select2();
    }*/
</script>

