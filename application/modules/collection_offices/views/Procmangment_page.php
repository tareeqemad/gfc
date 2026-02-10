<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 01/03/20
 * Time: 01:22 م
 */


$MODULE_NAME= 'training';
$TB_NAME= 'Procmangment';
$count = 1;

if($proc_type == 2){ ?>

    <div class="tbl_container">
        <table class="table" id="page_tb" data-container="container">
            <thead>
            <tr>
                <th>#</th>
                <th>مكتب التحصيل</th>
                <th>رقم الاشتراك</th>
                <th>شهر الفاتورة</th>
                <th>رقم القضية</th>
                <th>تاريخ القضية</th>
                <th>اسم المحكمة</th>
                <th>نوع الدعوى</th>
                <th>قيمة الدعوى</th>

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
            <?php foreach ($rows as $row) : ?>
                <tr id="tr_<?=$row['SER']?>" >
                    <td><?=$count?></td>
                    <td><?=$row['OFFICE_NAME']?></td>
                    <td><?=$row['SUBSCRIBER']?></td>
                    <td><?=$row['FOR_MONTH']?></td>
                    <td><?=$row['ISSUE_NO']?></td>
                    <td><?=$row['ISSUE_DATE']?></td>
                    <td><?=$row['COURT_NAME']?></td>
                    <td><?=$row['ISSUE_TYPE']?></td>
                    <td><?=$row['ISSUE_VALUE']?></td>
                    <!--<td><a  onclick="show_details(<?= $row['SER'] ?>,2 );"
                            data-toggle="modal"
                            data-target="#showmsgrec"  class="btn btn-warning btn-xs">الشيكات</a>
                        <a  onclick="show_details(<?= $row['SER'] ?> ,1);"
                            data-toggle="modal"
                            data-target="#showmsgrec"  class="btn btn-danger btn-xs">الكمبيالات</a>

                    </td> -->
                    <?php
                    $count++; ?>

                </tr>
            <?php endforeach;?>
            </tbody>
        </table>
    </div>


<?php } else { ?>

    <div class="tbl_container">
        <table class="table" id="page_tb" data-container="container">
            <thead>
            <tr>
                <th>#</th>
                <th>مكتب التحصيل</th>
                <th>رقم الاشتراك</th>
                <th>شهر الفاتورة</th>
                <th>نوع الاجراء</th>
                <th>تاريخ الاجراء</th>
                <th>موظف الشركة</th>
                <th>الطرف الاخر</th>
                <th>نتيجة الاجراء</th>
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
            <?php foreach ($rows as $row) : ?>
                <tr id="tr_<?=$row['SER']?>" >
                    <td><?=$count?></td>
                    <td><?=$row['OFFICE_NAME']?></td>
                    <td><?=$row['SUBSCRIBER']?></td>
                    <td><?=$row['FOR_MONTH']?></td>
                    <td><?=$row['TRANS_TYPE_NAME']?></td>
                    <td><?=$row['FOR_MONTH']?></td>
                    <td><?=$row['EMP_NAME']?></td>
                    <td><?=$row['TRANSM_NAME']?></td>
                    <td><?=$row['TRAN_RESULT_NAME']?></td>
                    <?php
                    $count++; ?>

                </tr>
            <?php endforeach;?>
            </tbody>
        </table>
    </div>


<?php }?>







<script>

</script>



