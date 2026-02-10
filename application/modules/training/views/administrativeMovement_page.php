<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 12/02/20
 * Time: 08:37 ص
 */


$MODULE_NAME= 'training';
$TB_NAME= 'administrativeMovement';
$manageAttendance =  base_url("$MODULE_NAME/$TB_NAME/get_attendance");
$manageAttendance_fin = base_url("$MODULE_NAME/$TB_NAME/get_attendance_fin");
$count = 1;
$con_co = 0;
?>


<div class="tbl_container">
    <table class="table" id="page_tb" data-container="container">
        <thead>
        <tr>
            <th>#</th>
            <th>رقم الهوية</th>
            <th>الاسم</th>
            <th>الادارة</th>
            <th>المقر</th>
            <th>تاريخ البداية</th>
            <th>تاريخ النهاية</th>
            <th>العقد</th>
            <th>الحركات الادارية</th>
            <?php
            $count++;
            ?>
        </tr>
        </thead>
        <tbody>
        <?php if($page > 1): ?>
            <tr >
                <td colspan="12" id="page-<?= $page ?>" class="page-sector" data-page="<?= $page ?>"></td>
            </tr>
        <?php endif; ?>
        <?php foreach ($rows as $row) :?>
            <tr   id="tr_<?=$row['SER']?>"  >
                <td><?=$count?></td>
                <td><?=$row['ID']?></td>
                <td><?=$row['NAME']?></td>
                <td><?=$row['MANAGE_NAME']?></td>
                <td><?=$row['BRANCH_NAME']?></td>
                <td><?=$row['START_DATE']?></td>
                <td><?=  $row['LAST_NEW'] == null ?$row['END_DATE'] : $row['LAST_NEW']?></td>
                <td>
                    <select name="contract" id="dp_contract_" class="form-control">
                        <option value="0">من  --- الى</option>
                        <?php foreach($rows_contract as $row2) :
                                if($row['SER'] == $row2['TRAINEE_SER']){
                                    $exp_date = str_replace('/', '-', $row2['END_DAT']);
                                    $datetime2 = date_create($exp_date);
                                    $datetime1 = date_create(date('d-m-Y'));
                                    $interval = date_diff($datetime1, $datetime2);
                                    $check_date = $interval->format('%R%a');
                                    if($check_date < 0){ ?>
                                        <option value="<?= $row2['SER'] ?>" > <?= $row2['START_DAT'] ." - ". $row2['END_DAT']?> </option>
                                    <?php }else{ $con_co = $row2['SER'];?>
                                        <option selected style="background-color: #caff8e" value="<?= $row2['SER'] ?>" > <?= $row2['START_DAT'] ." - ". $row2['END_DAT']?> </option>
                                    <?php }
                                }
                        endforeach; ?>
                    </select>
                </td>
                <td>
                    <?php if (HaveAccess($manageAttendance)): ?>
                    <a data-id="<?=$con_co; ?>" name="manageAttendance_btn" id="manageAttendance_btn" style="background-color: #dd807b; color:#FFFFFF "
                       onclick="manageAttendance(this)" class="btn btn-xs">دائرة التدريب</a>
                    <?php endif; ?>
                    <?php if (HaveAccess($manageAttendance_fin)): ?>
                    <a data-id="<?=$con_co; ?>" name="manageAttendance_fin_btn" id="manageAttendance_fin_btn" style="background-color: #8db7cc; color:#FFFFFF "
                        onclick="manageAttendance_fin(this)"  class="btn btn-xs">الادارة المالية</a>
                    <?php endif; ?>
                </td>

                <?php
                $count++; ?>

            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
</div>


<?php echo $this->pagination->create_links(); ?>






