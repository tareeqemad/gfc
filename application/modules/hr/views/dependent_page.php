<?php
$get_page_url = base_url('hr/dependent/get_page');
$count = 1;
$attach_url = base_url("attachments/attachment/public_upload");
$new_student_url = base_url("hr/dependent_students/create");
$update_dates_url = base_url("hr/dependent/update_dates");

if (HaveAccess($update_dates_url)) {
    $can_update_dates = 1;
} else {
    $can_update_dates = 0;
}

function change_date_format($dt)
{
    return substr($dt, 3);
}

?>
<div class="table-responsive tableRoundedCorner">
    <table class="table table-bordered roundedTable" id="page_tb">
        <thead class="table-light">
        <tr>
            <th><input type="checkbox" class="group-checkable" data-set="#page_tb .checkboxes"/></th>
            <th>م</th>
            <th>رقم الهوية</th>
            <th>الاسم</th>
            <th>اسم الاب</th>
            <th>اسم الجد</th>
            <th>اسم العائلة</th>
            <th>الجنس</th>
            <th>صلة القرابة</th>
            <th>الحالة الاجتماعية</th>
            <th> تاريخ الحالة الاجتماعية</th>
            <th>حي / ميت</th>
            <th>تاريخ الوفاة</th>
           <!-- <th>اسم المدخل</th>
            <th>تاريخ الادخال</th>-->
            <th>الاعتماد</th>
            <th>من تاريخ</th>
            <th>حتى</th>
            <th>حفظ</th>
            <th>المرفقات</th>
            <th> طالب جامعي</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($page_rows as $rows) : ?>
            <tr>
                <td>
                    <?php if ($rows['ADOPT'] == 1) { ?>
                        <input type="checkbox" class="checkboxes" value="<?= $rows['SER'] ?>"/>
                    <?php } else { ?>
                    <?php } ?>

                </td>
                <td><?= $count ?></td>
                <td><?= $rows['IDNO_RELATIVE'] ?></td>
                <td><?= $rows['FNAME_ARB'] ?></td>
                <td><?= $rows['SNAME_ARB'] ?></td>
                <td><?= $rows['TNAME_ARB'] ?></td>
                <td><?= $rows['LNAME_ARB'] ?></td>
                <td><?= $rows['SEX_CD_NAME'] ?></td>
                <td><?= $rows['RELATIVE_CD_NAME'] ?></td>
                <td><?= $rows['SOCIAL_STATUS_CD_NAME'] ?></td>
                <td><?= $rows['DATE_STATUS'] ?></td>
                <td><?= $rows['STATUS_LIVE'] ?></td>
                <td><?= $rows['DETH_DT'] ?></td>
             <!--   <td><?//= $rows['ENTRY_USER_NAME'] ?></td>
                <td><?//= $rows['ENTRY_DATE'] ?></td>-->
                <td><?= $rows['ADOPT_NAME'] ?></td>
                <td><input type="text" value="<?= change_date_format($rows['FROM_DATE']) ?>"
                           class="form-control c_from_date"/></td>
                <td><input type="text" value="<?= change_date_format($rows['TO_DATE']) ?>"
                           class="form-control c_to_date"/></td>
                <td> <?php if ($can_update_dates) { ?>  <a title="حفظ التواريخ" target="" style="cursor: pointer"
                                                           onclick="update_dates($(this), <?= $rows['SER'] ?>)"><i
                            class="fa fa-check"></i></a> <?php } ?> </td>
                <td> <?= modules::run('attachments/attachment/indexInline', $rows['EMP_ID'], 'dependent_' . $rows['IDNO_RELATIVE'], 0); ?>  </td>
                <td><a target="" style="cursor: pointer" onclick="add_new_std(<?= $rows['SER'] ?>)"><i
                            class="glyphicon glyphicon-new-window" nj></i></a></td>
                <?php
                $count++;
                ?>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<script>
    $(function(){

        $('.c_from_date, .c_to_date').datetimepicker({
            format: 'MM/YYYY',
            minViewMode: "months",
            pickTime: false
        });

    });
</script>
