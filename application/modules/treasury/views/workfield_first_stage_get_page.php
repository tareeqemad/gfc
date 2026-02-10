<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 9/9/2021
 * Time: 12:43 PM
 * سندات التحصيل المدخلة
 */

$MODULE_NAME= 'treasury';
$TB_NAME= 'workfield';
$show_bills_url= base_url("$MODULE_NAME/$TB_NAME/first_satge_adopt");
//$bank_doc_number= base_url("$MODULE_NAME/$TB_NAME/BankDocNumber");

?>
<hr>
<div class="form-body">
    <div class="alert alert-success col-md-10 col-md-offset-1" >
        اجماليات التحصيل الخاصة بمندوبي التحصيل الميداني
    </div>
    <input type="hidden" name="comp_date" id="txt_comp_date" value="<?=$comp_date?>">

    <table class="table info">
        <thead>
        <tr>
            <th>الرقم الوظيفي</th>
            <th>الاسم</th>
            <!--<th>الحساب المالي</th> -->
            <th>التكلفة</th>
            <th>الحالة</th>
            <th style="width: 100px;"></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($users as $row) :
                            if($row['STATUS'] == 1)
                                echo '<tr class="case_0">';
                            else if($row['STATUS'] == 2)
                                echo '<tr class="case_1">';
                            else if($row['STATUS'] == 4)
                                echo '<tr class="case_2">';
                            else if($row['STATUS'] == 6)
                                echo '<tr class="case_3">';
                            else
                                echo '<tr class="case_5">';
            ?>
                <td><?= $row['NO'] ?></td>
                <td><?= $row['NAME'] ?></td>
                <!--<td><?= $row['ACOUNT_NAME'] ?></td> -->
                <td><strong><?= $row['TOTAL'] ?></strong></td>
                <td><?= $row['STATUS_NAME'] ?></td>
                <td><?php if( HaveAccess($show_bills_url)):
                        if($row['TELLER_SERIAL'] != null) {
                            $teller = $row['TELLER_SERIAL'];
                        } else {
                            $teller = 0;
                        }
                        if($row['USER_TYPE'] == 1) { ?>
                            <a class="btn btn-xs btn-primary" href="<?= base_url('treasury/workfield/first_satge_adopt/' . $row['NO'].'/'.  $row['STATUS'].'/'. $date.'/'.$teller) ?>" >عرض السندات</a>
                        <?php } else { ?>
                            <a class="btn btn-xs btn-primary" href="<?= base_url('treasury/workfield/first_satge_comp/' . $row['NO'].'/'.  $row['STATUS'].'/'. $date.'/'.$teller.'/'.$comp_date) ?>" >عرض السندات</a>
                        <?php } ?>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
