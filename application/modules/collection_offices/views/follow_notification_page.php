<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 12/12/19
 * Time: 12:18 م
 */


$MODULE_NAME= 'collection_offices';
$TB_NAME= 'Follow_notification';
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
			<th>عدد الأيام من تاريخ الاخطار</th>
            <th>الاجراء</th>
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
        <?php foreach ($rows as $row) : 
						  $exp_date = str_replace('/', '-', $row['NOTI_DATE']);
                          $datetime2 = date_create($exp_date);
                          $datetime1 = date_create(date('d-m-Y'));
                          $interval = date_diff($datetime1, $datetime2);
                          $check_date = $interval->format('%R%a');
						  
						  ?>
		
            <tr  <?php 
			if (intval(abs($check_date)) >= 14 ){
				echo "style='background-color: #dd807b'";
			}?> id="tr_<?=$row['SER']?>" >

                <td><?=$count?></td>
                <td><?=$row['SUBSCRIBER']?></td>
                <td><?=$row['LOCATION_NO']?></td>
                <td><?=$row['NAME']?></td>
                <td><?=$row['FOR_MONTH']?></td>
                <td><?=$row['LAST_MONTH_PAID']?></td>
                <?php if($row['IS_ALY'] == 1){ ?>
                    <td>يتبع تسديد الي</td>
                <?php }else{ ?>
                    <td>لا يتبع تسديد الي</td>
                <?php }?>
                <td><?=$row['REMAINDER']?></td>
                <td><?=$row['NET_TO_PAY']?></td>
                <td><?=$row['COMMIT_VALUE']?></td>
                <td><?=$row['LAST_PAID']?></td>
                <td><?=$row['DISCLOSURE_SER']?></td>
				<td><?=$row['NOTI_DATE']?></td>
				<td><?=intval(abs($check_date))?></td>
                <!--<?php if($row['COUNT_NOTI'] != 0 ){?>
                    <td style="background-color: #dd968c">
                        <span class="glyphicon glyphicon-bell"><?="   ".$row['COUNT_NOTI']?></span>
                    </td>
                <?php } else{ ?>
                    <td>
                        <span class="glyphicon glyphicon-bell"><?="   ".$row['COUNT_NOTI']?></span>
                    </td>
                <?php }?> -->
                <td >
                    <?php if($row['NOTI_STATUS'] == 0  ){?>
                        <button type="button" onclick="javascript:changeStatus_(1,'<?=$row['SER']?>');"
                                class="btn btn-info btn-xs" href='javascript:;'><i
                                class='glyphicon glyphicon-ok'></i>تم الحل</button>

                    <?php }
					if($row['NOTI_STATUS'] == 1  ){ ?>
						<span class="badge badge-2"><?=$row['NOTI_STATUS_NAME']?></span>
					<?php  }if($row['NOTI_STATUS'] == 2  ){ ?>
						<span class="badge badge-4"><?=$row['NOTI_STATUS_NAME']?></span>
					<?php }
					/*if($row['NOTI_STATUS'] == 0 ){ ?>
                        <button type="button" onclick="javascript:changeStatus_(2,'<?=$row['SER']?>');"
                                class="btn btn-danger btn-xs" href='javascript:;'><i
                                class='glyphicon glyphicon-remove'></i>لم يتم الحل </button>
                    <?php } */?>
                </td>
                <?php
                $count++; ?>

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



