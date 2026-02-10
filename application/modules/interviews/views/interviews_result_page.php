<?php
/**
 * Created by PhpStorm.
 * User: telbawab
 * Date: 19/10/2022
 * Time: 12:30 PM
 */
$count = $offset;
$MODULE_NAME = 'interviews';
$TB_NAME = 'Interviews_result';
$edit_status_url = base_url("$MODULE_NAME/$TB_NAME/edit_status");//تعديل الحالة
$show_achieve_url = base_url("$MODULE_NAME/$TB_NAME/show_achieve");//صلاحية عرض الانجازات للجنة
$show_plan_url = base_url("$MODULE_NAME/$TB_NAME/show_plan");
$today_date = '13/12/2022';
function get_color($id)
{
    if ($id == 2) {
        return 'text-danger';
    } else if ($id == 1) {
        return 'text-success';
    } else {
        return 'text-primary';
    }
}

?>
<div class="row">
    <div class="table-responsive tableRoundedCorner">
        <table class="table mg-b-0 text-md-nowrap roundedTable table-bordered" id="page_tb">
            <thead class="table-light">
            <tr class="text-center">
                <th>رقم الهوية</th>
                <th>الرقم الوظيفي</th>
                <th>الموظف</th>
                <th>الوظيفة المقدم عليها</th>
                <th>تقييم الخطة ورقيا<br>___ <br> <span class="<?= get_color(2) ?>" > 20 </span></th>
                <th>التكليف<br>___ <br> <span class="<?= get_color(2) ?>" > 5 </span></th>
                <th>تقييم الاداء<br>___ <br><span class="<?= get_color(2) ?>" > 5 </span></th>
                <th>ملف الانجاز<br>___ <br><span class="<?= get_color(2) ?>" > 15 </span></th>
                <th>ادارة النقاش ومهارات الاقناع<br>___ <br><span class="<?= get_color(2) ?>" > 5 </span></th>
                <th>وضوح وتسلسل الافكار<br>___ <br><span class="<?= get_color(2) ?>" > 7 </span></th>
                <th>شمولية العرض<br>___ <br><span class="<?= get_color(2) ?>" > 5 </span></th>
                <th>ادارة وقت العرض<br>___ <br><span class="<?= get_color(2) ?>" > 3 </span></th>
                <th>المعرفة الفنية<br>___ <br><span class="<?= get_color(2) ?>" >  7 </span></th>
                <th>المهارات القيادية<br>___ <br><span class="<?= get_color(2) ?>" >  12 </span></th>
                <th>اللغة الإنجليزية<br>___ <br><span class="<?= get_color(2) ?>" >  3 </span></th>
                <th>المهارات الادارية<br>___ <br><span class="<?= get_color(2) ?>" >  5 </span></th>
                <th>الحاسوب<br>___ <br><span class="<?= get_color(2) ?>" >  3 </span></th>
                <th>الاتصال والتواصل ولغة الجسد<br>___ <br><span class="<?= get_color(2) ?>" >  5 </span></th>
                <th>المجموع</th>
                <th>الترتيب</th>
            </tr>
            </thead>
            <tbody>
            <?php if ($page > 1): ?>
                <tr class="text-center">
                    <td colspan="8" id="page-<?= $page ?>" class="page-sector" data-page="<?= $page ?>"></td>
                </tr>
            <?php endif; ?>
            <?php
            foreach ($page_rows as $row) :?>
                <?php if($row['RANK'] == 1) {?>
                    <tr class="text-center" id="tr_<?= $row['EMP_NO'] ?>" style="background-color: #eeffea;">
                <?php } else {?>
                    <tr class="text-center"  id="tr_<?= $row['EMP_NO'] ?>">
                <?php } ?>
                <td><?= $row['ID'] ?></td>
                <td><?= $row['EMP_NO'] ?></td>
                <td><?= $row['NAME'] ?></td>
                <td><?= $row['ADS_NAME'] ?></td>
                <td><?= $row['PLAN_EVALUATION'] ?></td>
                <td><?= $row['ASSIGNED_WORK'] ?></td>
                <td><?= $row['EMP_EVALUATION'] ?></td>
                <td><?= $row['ACHIEVEMENT_FILE'] ?></td>
                <td><?= $row['PERSUASION_SKILLS'] ?></td>
                <td><?= $row['THOUGHT_CLARITY'] ?></td>
                <td><?= $row['COMPREHENSIVENESS_PRESENTATION'] ?></td>
                <td><?= $row['PRESENTATION_TIME_MANAGEMENT'] ?></td>
                <td><?= $row['TECHNICAL_KNOWLEDGE'] ?></td>
                <td><?= $row['LEADERSHIP_SKILLS'] ?></td>
                <td><?= $row['ENGLISH_LANGUAGE'] ?></td>
                <td><?= $row['MANAGEMENT_SKILLS'] ?></td>
                <td><?= $row['COMPUTER_SKILLS'] ?></td>
                <td><?= $row['COMMUNICATION_SKILLS'] ?></td>
                <td><?= $row['TOTAL'] ?></td>
                <td>
                    <span class="badge badge-pill badge-warning  <?= get_color($row['RANK']) ?>"><?= $row['RANK'] ?></span>
                </td>

                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
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




