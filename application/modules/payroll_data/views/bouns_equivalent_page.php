<?php
/**
 * Created by PhpStorm.
 * User: telbawab
 * Date: 09/02/2020
 * Time: 12:45 م
 */

$MODULE_NAME= 'payroll_data';
$TB_NAME= 'bouns_equivalent';
$count = $offset;
//اعتماد اللجنة
$adopt_comm_url= base_url("$MODULE_NAME/$TB_NAME/adopt_");
?>
<div class="tbl_container">
    <table class="table" id="page_tb" data-container="container">
        <thead>
        <tr>
            <th>
                <input type="checkbox" class="group-checkable"  data-set="#page_tb .checkboxes"/>
            </th>
            <th>#</th>
            <th>رقم الطلب</th>
            <th>رقم الموظف</th>
            <th>اسم الموظف</th>
            <th>المقر</th>
            <th>نوع التعين</th>
            <th>عن شهر</th>
            <th>الادارة</th>
            <th> طبيعة العمل</th>
            <th>المسمى الوظيفي</th>
            <th>الراتب الأساسي</th>
            <th>عن شهر</th>
            <th>نوع المكافأة</th>
            <th> قيمة المكافأة</th>
            <th>رأي اللجنة</th>
            <th>حالة الاعتماد</th>
        </tr>
        </thead>
        <tbody>
        <?php if($page > 1): ?>
            <tr>
                <td colspan="17" id="page-<?= $page ?>" class="page-sector" data-page="<?= $page ?>"></td>
            </tr>
        <?php endif; ?>
        <?php
        $valud_ma_adopt = 0;
        foreach($page_rows as $row) :
            $valud_ma_adopt+= $row['VALUE_MA'];
             ?>
            <tr id="tr_<?=$row['EMP_NO']?>" ondblclick="javascript:get_to_link('<?=  base_url("$MODULE_NAME/$TB_NAME/get/{$row['SER']}")  ?>');">
                <?php
                if (HaveAccess($adopt_comm_url.'2') and $row['AGREE_MA'] == 2 )  {
                    $check = "<input name='pp_ser[]' type='checkbox' class='checkboxes'  value='{$row['SER']}'  />";
                } else {
                    $check = "";
                }
                ?>
                <td><?= $check ?></td>
                <td><?=$count?></td>
                <td><?=$row['SER']?></td>
                <td><?=$row['EMP_NO']?></td>
                <td><?=$row['EMP_NAME']?></td>
                <td><?=$row['BRANCH_NAME']?></td>
                <td><?=$row['EMP_TYPE_NAME']?></td>
                <td><?=$row['MONTH']?></td>
                <td><?=$row['HEAD_DEPARTMENT_NAME']?></td>
                <td><?=$row['W_NO_NAME']?></td>
                <td><?=$row['W_NO_ADMIN_NAME']?></td>
                <td><?=number_format($row['B_SALARY'],2)?></td>
                <td>
                   <?=$row['MONTH']?>
                </td>
                <td>
                    <?=$row['TYPE_REWARD_NAME']?>
                </td>
                <td>
                    <?=number_format($row['VALUE_MA'],2)?>
                </td>
                <td><?php if($row['COMMITTEE_CASE'] == 1){ ?>
                        <span class="label label-success">مقبول </span>
                    <?php }elseif($row['COMMITTEE_CASE'] == 2){ ?>
                        <span class="label label-danger">مرفوض</span>
                    <?php }elseif($row['COMMITTEE_CASE'] == 3){ ?>
                        <span class="label label-warning">مؤجل</span>
                    <?php }?>
                </td>
                <td>
                    <?=$row['AGREE_MA_NAME']?>
                </td>
                <?php
                $count++; ?>
            </tr>
        <?php endforeach;?>
        </tbody>
        <tfoot>
        <tr>
            <td colspan="13"></td>
            <td>الاجمالي</td>
            <td style="background-color: #dfff2e;" id="valud_ma_adopt">
                <?=number_format($valud_ma_adopt,2);?>
            </td>
            <td colspan="3"></td>
        </tr>
        </tfoot>
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
    if (typeof show_page == 'undefined'){
        document.getElementById("page_tb").style.display="none";
        document.getElementsByClassName("pagination")[0].style.display="none";
    }
</script>
