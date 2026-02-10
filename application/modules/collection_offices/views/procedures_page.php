<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 06/07/20
 * Time: 01:32 م
 */

$MODULE_NAME= 'training';
$TB_NAME= 'Procedures';
$count = 1;

 ?>

    <div class="tbl_container">
        <table class="table" id="page_tb" data-container="container">
            <thead>
            <tr>
                <th>#</th>
                <th>مكتب التحصيل</th>
                <th>رقم الاشتراك</th>
                <th>شهر الفاتورة</th>
                <th>نوع التسوية </th>
                <th>تاريخ التسوية</th>
                <th>قيمة الدفعة النقدية الأولى</th>
                <th>تاريخ الدفعة النقدية الأولى</th>
                <th>نوع الاشتراك</th>
                <th>الحالة</th>
                <th></th>

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
                    <td><?=$row['TYPE_NAME']?></td>
                    <td><?=$row['AGG_DATE']?></td>
                    <td><?=$row['FIRST_PAY_VALUE']?></td>
                    <td><?=$row['FIRST_PAID_DATE']?></td>
                    <td><?=$row['SUB_TYPE_NAME']?></td>
					<td>
					<?php if($row['STATUS'] == 1 ) { ?>
						<span class="badge badge-2"><?=$row['STATUS_NAME']  ?></span>
					<?php } else {?>
						<span class="badge badge-danger"><?=$row['STATUS_NAME']  ?></span>
					<?php } ?></td>
					
                    <td><a  onclick="show_details(<?= $row['SER'] ?>,2 );"
                            data-toggle="modal"
                            data-target="#showmsgrec"  class="btn btn-warning btn-xs">الشيكات</a>
                        <a  onclick="show_details(<?= $row['SER'] ?>,1);"
                            data-toggle="modal"
                            data-target="#showmsgrec"  class="btn btn-danger btn-xs">الكمبيالات</a>

                    </td>
					
                    <?php
                    $count++; ?>

                </tr>
            <?php endforeach;?>
            </tbody>
        </table>
    </div>








<script>

</script>



