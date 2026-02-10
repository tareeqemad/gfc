<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 12/12/19
 * Time: 10:26 ص
 */

$MODULE_NAME= 'collection_offices';
$TB_NAME= 'Sub_notifications';
$count = 1;
?>


<div class="tbl_container">
    <table class="table" id="page_tb" data-container="container">
        <thead>
        <tr>
            <th>#</th>
            <th>رقم الاشتراك</th>
            <th>رقم الموقع</th>
            <th>الاسم</th>
            <th>الفاتورة الشهرية</th>
            <th>غير مسدد منذ</th>
            <th>الية التسديد</th>
            <th>متأخرات</th>
            <th>القيمة المطلوبة</th>
            <th>قسط</th>
            <th>الدفعة</th>
            <th>رقم الكشف</th>
            <th>تاريخ الاخطار</th>
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
                <td><?=$row['SUBSCRIBER']?></td>
                <td><?=$row['LOCATION_NO']?></td>
                <td><?=$row['NAME']?></td>
                <td><?=$row['FOR_MONTH']?></td>
                <td><?=$row['LAST_MONTH_PAID']?></td>
                <td><?=$row['IS_ALY']?></td>
                <td><?=$row['REMAINDER']?></td>
                <td><?=$row['NET_TO_PAY']?></td>
                <td><?=$row['COMMIT_VALUE']?></td>
                <td><?=$row['LAST_PAID']?></td>
                <td><?=$row['DISCLOSURE_SER']?></td>
                <td><?=$row['NOTI_DATE']?></td>
                <!--<?php if($row['COUNT_NOTI'] != 0 ){?>
                <td style="background-color: #dd968c">
                    <span class="glyphicon glyphicon-bell"><?="   ".$row['COUNT_NOTI']?></span>
                </td>
                <?php } else{ ?>
                    <td>
                        <span class="glyphicon glyphicon-bell"><?="   ".$row['COUNT_NOTI']?></span>
                    </td>
                <?php }?> -->
                <td>
                    <?php if($row['COUNT_NOTI'] == 0 ){ ?>
                    <button type="button" onclick="javascript:changeStatus_(0,'<?=$row['SER']?>');"
                            class="btn btn-danger btn-xs" href='javascript:;'><i
                            class='glyphicon glyphicon-share'></i>ارسال الاخطار</button>
                    <?php } ?>
					
					<?php if($row['COUNT_NOTI'] != 0 ){ ?>
					
					 <button type="button" onclick="javascript:print_report('<?=$row['SER']?>');"
                            class="btn btn-info btn-xs" href='javascript:;'><i
                            class='glyphicon glyphicon-share'></i>عرض التقرير</button>
							
					<?php } ?>
					
                </td>
                <?php
                $count++;
                ?>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
</div>


<?php echo $this->pagination->create_links(); ?>


<script>
    if (typeof initFunctions == 'function') {
        initFunctions();
    }
</script>



