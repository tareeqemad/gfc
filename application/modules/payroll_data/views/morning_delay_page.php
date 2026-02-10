<?php
function tr_color($is_shift_emp){
    if ($is_shift_emp ==  1  ) {
        return '#fff7f4';
    }
}
$MODULE_NAME = 'payroll_data';
$TB_NAME = 'morning_delay';
$count = $offset;
$update_is_active_url = base_url("$MODULE_NAME/$TB_NAME/update_is_active");
?>
<div class="table-responsive">
    <table class="table  table-bordered" id="page_tb">
        <thead class="table-light">
        <tr>
            <th>م</th>
            <th>المقر</th>
            <th>الموظف</th>
            <th>الشهر</th>
            <th>اليوم</th>
            <th>التاريخ</th>
            <th>ساعة الحضور</th>
            <th>اعتماد الخصم</th>
            <th class="text-center" >قيمة الخصم بالساعة</th>
            <th class="text-center">الحالة</th>
        </tr>
        </thead>
        <tbody>
        <?php if ($page > 1): ?>
            <tr>
                <td colspan="10" id="page-<?= $page ?>" class="page-sector" data-page="<?= $page ?>"></td>
            </tr>
        <?php endif; ?>
        <?php
        foreach ($page_rows as $row) : /*echo '<pre>'; print_r($page_rows); die();*/ ?>
            <tr id="tr_<?= $row['P_SER'] ?>" ondblclick="javascript:show_detail_row(<?= $row['P_SER'] ?>);" style="background-color: <?=tr_color($row['IS_SHIFT_EMP']) ?>">
                <td>
                    <?= $count ?>
                    <input type="hidden" id="txt_min_delay" name="min_delay" value="<?=$row['MIN_DELAY']?>">
                </td>
                <td><?= $row['BRANCH_NAME'] ?></td>
                <td><?= $row['EMP_NO'] ?> - <?= $row['EMP_NAME'] ?></td>
                <td><?= $row['THE_MONTH'] ?></td>
                <td><?= $row['DAY_AR'] ?></td>
                <td><?= $row['THE_DATE'] ?></td>
                <td class="text-center"><?= $row['PERIOD_TIME'] ?></td>
                <td>
                    <?php if(HaveAccess ($update_is_active_url) && $row['ALLOW_EDIT'] == 0){ ?>
                        <select id="dp_is_active_<?= $row['IS_ACTIVE'] ?>"
                                name="is_active[<?= $row['IS_ACTIVE'] ?>]"
                                class="form-control is_active" onchange="is_active_change(this,<?= $row['P_SER'] ?>);">
                            <?php  $chosenACTIVE_id = $row['IS_ACTIVE'];?>
                            <?php foreach ($is_active_cons as $row1){?>
                                <option <?=($chosenACTIVE_id == $row1['CON_NO']?'selected="selected"':'')?> value="<?=$row1['CON_NO']?>"><?=$row1['CON_NAME']?></option>
                            <?php }
                            ?>
                        </select>
                    <?php } elseif (HaveAccess ($update_is_active_url) && $row['ALLOW_EDIT'] >= 1){?>
                       <p class="text-danger">
                            <?=$row['IS_ACTIVE_NAME']?> - لا يمكن التعديل على كشف معتمد
                       </p>
                     <?php } elseif( ! HaveAccess ($update_is_active_url)){ ?>
                        <?=$row['IS_ACTIVE_NAME']?>
                    <?php } ?>
                </td>
                <td id="dis_hour" class="text-center"><?= $row['VALVE'] ?></td>
                <td class="far_val text-center">
                    <?php if ($row['POST'] == 20) { ?>
                        <i id="<?= $row['P_SER'] ?>" class="fa fa-meh-o" style="color: red;" title="يخصم"></i>
                    <?php } elseif ($row['POST'] == 10) { ?>
                        <i id="<?= $row['P_SER'] ?>" class="fa fa-smile-o fa-lg" style="color: #63be7b;"
                           title="لا يخصم"></i>
                    <?php } ?>
                </td>
                <?php
                $count++; ?>
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
</script>

