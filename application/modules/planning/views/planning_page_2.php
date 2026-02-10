<style>
    .case_10 {
        background-color: #FFC9BB;
    }

    .large.tooltip-inner {
        max-width: 350px;
        width: 350px;
    }


</style>
<?php
/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 29/10/17
 * Time: 01:29 م
 */

$MODULE_NAME = 'planning';
$TB_NAME = 'planning';
$edit_url = base_url("$MODULE_NAME/$TB_NAME/get_tech_cost");
$popup_url = base_url("$MODULE_NAME/$TB_NAME/public_get_url");
$delete_url = base_url("$MODULE_NAME/$TB_NAME/delete");
$delete_unit_url = base_url("$MODULE_NAME/$TB_NAME/delete_unit");
$count = $offset;


?>
<input type="hidden" value="<?= $popup_url; ?>" id="popup_url">
<div class="tbl_container">
    <table class="table" id="page_tb" data-container="container">
        <thead>
        <tr>
            <th>#</th>
            <th>م</th>
            <!-- <th>العام</th> -->
            <!--<th>المقر</th>-->

            <th style="width:9%">المحور</th>
            <th style="width:15%">الهدف الإستراتيجي (العام)</th>
            <th style="width:14%">الهدف الإستراتيجي (الخاص)</th>
            <th></th>
            <!-- <th>التصنيف</th> -->
            <!-- <th>نوع المشروع</th> -->
            <th>رقم المشروع</th>
            <th>اسم المشروع</th>
            <?php
            if ($show == 1) {
                ?>
                <th>الإدارة</th>
                <?php
            } ?>
            <?php
            if ($show == 1) {
                ?>
                <th>الدائرة</th>
                <?php
            } ?>
            <?php
            if ($show == 1) {
                ?>
                <th>القسم</th>
                <?php
            } ?>
            <?php
            if ($show == 1) {
                ?>
                <th>الجهة</th>
                <?php
            } ?>
            <?php
            if ($show_dept == 1) {
                ?>
                <th>القسم</th>
                <?php
            } ?>
            <th>طبيعة الخطة</th>
            <th>نسبة الإنجاز</th>

            <!-- <th>التكلفة</th>-->
            <!-- <th>مصدر التمويل</th> -->
            <!-- <th>الإيراد</th> -->
            <!-- <th hidden>مسؤولية المتابعة</th> -->
            <!-- <th>مسؤولية التنفيذ</th> -->
            <th style="width:4%">مدة التنفيذ</th>
            <th>المهام</th>


            <?php if (HaveAccess($edit_url)) echo "<th>تحرير</th>"; ?>


        </tr>
        </thead>

        <tbody>

        <?php foreach ($page_rows as $row) : ?>
            <tr>

                <?php if (HaveAccess($delete_url))  : ?>

                    <td><input type='checkbox' class='checkboxes' value='<?= $row['SEQ'] ?>'
                               data-id='<?= $row['SEQ'] ?>'></td>
                <?php endif; ?>
                <?php if (HaveAccess($delete_unit_url))  : ?>
                    <td><input type='checkbox' class='unitcheckboxes' value='<?= $row['SEQ'] ?>'
                               data-id='<?= $row['SEQ'] ?>'></td>
                <?php endif; ?>

                <td class="text-right"><?= $count ?></td>
                <!-- <td><?= $row['YEAR'] ?></td> -->
                <!-- <td><?= $row['BRANCH_NAME'] ?></td> -->
                <td class="text-right"><?= $row['OBJECTIVE_NAME'] ?></td>
                <td class="text-right"><?= $row['GOAL_NAME'] ?></td>
                <td class="text-right"><?= $row['GOAL_T_NAME'] ?></td>
                <td class="text-center"><?= $row['TYPE_PROJECT_NAME'] ?></td>
                <!-- <td><?= $row['ACTIVE_CLASS_NAME'] ?></td> -->
                <!-- <td><?= $row['PROJECT_TYPE_'] ?></td> -->
                <td><?= $row['P_ACTIVITY_NO'] ?></td>
                <td class="text-right">
                    <?php
                    if ($row['NOTES'] == '') $row['NOTES'] = "لايوجد ملاحظات مدخلة";

                    ?>

                    <a href="#" data-toggle="tooltip" data-placement="top" title="<?= $row['NOTES'] ?>"><i
                                class="icon icon-question-circle"></i></a><?= $row['P_ACTIVITY_NAME'] ?>


                </td>
                <?php
                if ($show == 1) {
                    ?>
                    <td><?=$row['N_MANAGE_EXE_ID_NAME'] ?></td>
                    <?php
                } ?>
                <?php
                if ($show == 1) {
                    ?>
                    <td><?=  $row['N_CYCLE_ID_ID_NAME']?></td>
                    <?php
                } ?>
                <?php
                if ($show == 1) {
                    ?>
                    <td><?= $row['N_DEPARTMENT_ID_ID_NAME'] ?></td>
                    <?php
                } ?>
                <?php
                if ($show == 1) {
                    ?>
                    <td><?= $row['PLANNING_DIR_NAME'] ?></td>
                    <?php
                } ?>
                <?php
                if ($show_dept == 1) {
                    ?>
                    <td><?= $row['DEPARTMENT_ID'] ?></td>
                    <?php
                } ?>
                <td><?= $row['TYPE_EMP_NAME']?></td>
                <?php
                if($row['TOTAL_ACHIVE']>0)
                {
                ?>
                <td style="font-weight: bold;color: #117201;" ><?= $row['TOTAL_ACHIVE'] . '%' ?></td>
                <?php }
                else
                {
                ?>
                    <td style="font-weight: bold;" ><?= $row['TOTAL_ACHIVE'] . '%' ?></td>
                <?php }?>
                <!-- <td><?php echo $row['TOTAL_PRICE'];

                ?></td> -->
                <!-- <td><?= $row['PROJECT_TYPE_DON_NAME'] ?></td> -->

                <!-- <td><?= $row['INCOME'] ?></td> -->
                <!-- <td><?= $row['MANAGE_EXE_ID_NAME'] ?></td> -->
                <td class="text-center"><?= $row['EXE_TIME'] . ' شهر ' ?></td>
                <?php
                if($row['CNTR_ACTIVITIES']>0)
                {
                ?>
                <td class="text-center"><i style="font-weight: bold;color: #538CC2;" class="fa fa-file1" onclick="show_doc(<?= $row['SEQ'] ?>);"><?= $row['CNTR_ACTIVITIES']?></i></td>
                <?php }
                else
                {
                    ?>
                    <td class="text-center" ><i style="font-weight: bold;" class="fa fa-file1" onclick="show_doc(<?= $row['SEQ'] ?>);"><?= $row['CNTR_ACTIVITIES']?></i></td>

                <?php }?>

                <?php if (HaveAccess($edit_url))  : ?>


                    <?php if ($row['CLASS'] == 1) { ?>
                        <td class="text-center"><a
                                    href="<?= "https://gs.gedco.ps/Technical/projects/projects/get/{$row['PROJECT_ID']}/index" ?>"
                                    target="_blank"><i class='glyphicon glyphicon-share'></i></a></td>
                    <?php } else {
                        ?>
                        <td class="text-center"><a
                                    href="<?= base_url("planning/planning/get_tech_cost/{$row['SEQ']}") ?>"
                                    target="_blank"><i class='glyphicon glyphicon-share'></i></a></td>
                        <?php
                    }
                    ?>


                <?php endif; ?>




                <?php $count++ ?>
            </tr>
        <?php endforeach; ?>

        </tbody>
    </table>
</div>
<?php
echo $this->pagination->create_links();
?>

<script type="text/javascript">
    $(function () {
        // $('#element').tooltip('show')
        $('[data-toggle="tooltip"]').tooltip({
            template: '<div class="tooltip" role="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner large"></div></div>'
        });
    })

    if (typeof initFunctions == 'function') {
        initFunctions();
    }
    if (typeof ajax_pager == 'function') {
        ajax_pager();
    }
    if (typeof show_page == 'undefined') {
        document.getElementById("page_tb").style.display = "none";
        document.getElementsByClassName("pagination")[0].style.display = "none";
    }

    function show_doc(val) {

        _showReport($('#popup_url').val() + '/' + val);
        var windowHeight = $(window).height();
        var windowWidth = $(window).width();

        var boxHeight = $('.modal-dialog').height();
        $('.modal-dialog').css('width', '1500px');
        var boxWidth = $('.modal-dialog').width();
        /*
        alert(windowHeight);
        alert(windowWidth);
        alert(boxHeight);
        alert(boxWidth);
        */
//$('.modal-dialog').css({'center' : ((windowWidth - boxWidth)/2), 'top' : ((windowHeight - boxHeight)/2)});
        $('.modal-dialog').css({'center': ((windowWidth - boxWidth) / 2)});
//$('.modal-content').css('width','1500px');

    }


</script>

