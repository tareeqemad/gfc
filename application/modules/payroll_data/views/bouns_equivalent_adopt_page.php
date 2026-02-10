<?php
/**
 * Created by PhpStorm.
 * User: telbawab
 * Date: 20/02/2020
 * Time: 08:45 ص
 */

$MODULE_NAME= 'payroll_data';
$TB_NAME= 'bouns_equivalent_adopt';
$TB_NAME1 = 'bouns_equivalent';
$count = $offset;
//ارجاع الى اللجنة 2
$CancelAdopt = base_url("$MODULE_NAME/$TB_NAME/return_adopt1");
//10//اعتماد المدير العام
$ManagerAdopt_eq = base_url("$MODULE_NAME/$TB_NAME/ManagerAdopt_eq");
//اعتماد المراقب الداخلي/20/
$InternalObserver_eq = base_url("$MODULE_NAME/$TB_NAME/InternalObserver_eq");
//اعتماد المالية/30/
$FinicalDepart_eq = base_url("$MODULE_NAME/$TB_NAME/FinicalDepart_eq");
?>
<div class="tbl_container">
    <table class="table" id="page_tb" data-container="container">
        <thead>
        <tr>
            <th>
                <input type="checkbox" class="group-checkable"  data-set="#page_tb .checkboxes"/>
            </th>
            <th>#</th>
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
            <th>حالة الاعتماد</th>
            <th>
                بيانات الاعتماد
            </th>
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
            $valud_ma_adopt+= $row['VALUE_MA']; ?>
            <tr id="tr_<?=$row['EMP_NO']?>" ondblclick="javascript:get_to_link('<?=  base_url("$MODULE_NAME/$TB_NAME1/get/{$row['SER']}")  ?>');" title="<?=$row['WORK_DETAIL']?>">
                <?php
                if(HaveAccess($ManagerAdopt_eq) and  $row['AGREE_MA'] == 3 ){
                    $check= "<input name='ser[]' type='checkbox' class='checkboxes'  value='{$row['SER']}'  />";
                }elseif (HaveAccess($InternalObserver_eq) and $row['AGREE_MA'] == 10) {
                    $check= "<input name='ser[]' type='checkbox' class='checkboxes'  value='{$row['SER']}' />";
                }elseif (HaveAccess($FinicalDepart_eq) and  $row['AGREE_MA'] == 20) {
                    $check= "<input name='ser[]' type='checkbox' class='checkboxes'  value='{$row['SER']}' />";
                }
                else{
                    $check= '';
                }
                ?>
                <td><?= $check ?></td>
                <input type="hidden" id="h_ser" name="ser" value="<?=$row['SER']?>">
                <input type="hidden" id="emp_id" name="no" value="<?=$row['EMP_NO']?>">
                <input type="hidden" id="emp_name" name="emp_name" value="<?=$row['EMP_NAME']?>">
                <input type="hidden" id="month" name="month" value="<?=$row['MONTH']?>">
                <input type="hidden" id="agree_ma" name="agree_ma" value="<?=$row['AGREE_MA']?>">
                <input type="hidden" id="value_ma" name="value_ma" value="<?=$row['VALUE_MA']?>">
                <td><?=$count?></td>
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
                <td>
                    <?=$row['AGREE_MA_NAME']?>
                </td>
                <td><?php if($row['AGREE_MA'] >= 3){ ?>
                        <a  onclick="show_detail_row(this);"
                            class="btn btn-warning btn-xs">التفاصيل</a>
                    <?php } ?>
                </td>
                <?php
                $count++; ?>
            </tr>
        <?php endforeach;?>
        </tbody>
        <tfoot>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>الاجمالي</td>
            <td style="background-color: #dfff2e;" id="valud_ma_adopt">
                <?=number_format($valud_ma_adopt,2);?>
            </td>
            <td></td>
            <td></td>

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
