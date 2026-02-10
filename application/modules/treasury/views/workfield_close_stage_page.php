<?php

$MODULE_NAME= 'treasury';
$TB_NAME= 'workfield';
$report_bills_url= base_url("$MODULE_NAME/$TB_NAME/report_bills");
$back_stage_url= base_url("$MODULE_NAME/$TB_NAME/first_back_stage");
$report_receipt_url= base_url("$MODULE_NAME/$TB_NAME/report_receipt");
$close_bills_url= base_url("$MODULE_NAME/$TB_NAME/transfer");
$adopt_bill_record= base_url("$MODULE_NAME/$TB_NAME/update_daily_financial");

$i = 1;
$sum_rec = 0;
$sum_total = 0;
$flag = 0;

foreach ($users as $user){
    $sum_rec+= $user['RECEIVED_VALUE'];
    $sum_total+= $user['TOTAL'];
    if($user['STATUS'] == 1){
        $flag++;
    }
}
?>

<div class="form-body">
    <!--<div class="alert alert-info col-md-10 col-md-offset-1">اجماليات التحصيل الخاصة بمندوبي التحصيل الميداني لتاريخ
        <span class="badge badge-primary"><?=$date?></span>
    </div> -->
    <div class="container">
        <div class="info">
                <span class="alert alert-info box floatLeft  mr-20">
                    اجمالي المبلغ المرحل
                     <strong class="sum_total"><?=$sum_total?></strong>
                </span>

                <span class="alert alert-danger box floatLeft mr-20">
                    اجمالي المبلغ المقبوض
                     <strong class="sum_rec"  id="total-selected"><?=$sum_rec?></strong>
                </span>


            <?php //if( HaveAccess($close_bills_url) && (count($users) > 0) && (count($users) == $flag)):  ?>
               <!-- <button class="btn btn-success" style="line-height: 95px;" onclick="javascript:close_selected_bills();">
                    اغلاق المحدد
                </button> -->
            <?php //endif; ?>
        </div>
    </div>

    <table class="table info">
        <thead>
        <tr>
            <th>#</th>
            <th>الرقم الوظيفي</th>
            <th>الاسم</th>
            <th>المبلغ المرحل</th>
            <th>المبلغ المقبوض</th>
            <th><span class="badge badge-2">فائض</span> /
                <span class="badge badge-danger">عجز</span> </th>
            <th>الحالة</th>
			 <th>  رقم مستند بنك</th>
            <th>رقم القيد المالي</th>
			 
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($users as $row) : if($row['CLOSE_STATUS'] > 2 ){ $v_class= "readonly"; $v_status=""; echo '<tr class="case_5">'; } else{ $v_class=""; $v_status="غير مغلق / "; echo '<tr class="">';} ?>

                <td>
                    <?= $i++?>
                </td>
                <td>
                    <?= $row['NO'] ?>
                </td>
                <td>
                    <?= $row['NAME'] ?>
                </td>
                <td>
                    <strong>
                        <input type="text"  data-id="total_val" readonly id="txt_total_<?=$i?>" value="<?= $row['TOTAL'] ?>" data-pk="<?=$i?>" class="form-control"/>
                    </strong>
                </td>
                <td>
                    <input type="text" <?=$v_class?> data-id="income_val" id="txt_in_val_<?=$i?>" value="<?= $row['RECEIVED_VALUE'] ?>" data-pk="<?=$i?>" class="form-control in_val "/>
                    <input type="hidden" id="h_txt_in_val_<?=$i?>" value="<?= $row['RECEIVED_VALUE'] ?>" data-pk="<?=$i?>" class="form-control"/>
                </td>
                <td>
                    <?php $differance = $row['RECEIVED_VALUE'] - $row['TOTAL'] ?>
                    <input  readonly style="background-color:<?php if($differance > 0){ ?> #c5eac5 <?php } else if ($differance == 0) { ?> #ffffff <?php } else { ?> #e6afaf<?php }?>" type="text" data-id="differance_val" id="txt_dif_<?=$i?>"  value="<?= $differance ?>"  class="form-control "/>
                </td>
                <td><?=$v_status ." " . $row['STATUS_NAME'] ?></td>
				       <td><?= $row['RECORD_NO'] ?></td>
                <td><?= $row['FINANCIAL_CHAIN_NUMBER'] ?></td>
                <td>
                    <?php if($row['STATUS'] == 0 && $row['CLOSE_STATUS'] == 2 &&  HaveAccess($adopt_bill_record)){?>
                        <button class="btn btn-xs btn-warning" id="btn_adopt_bill_record" type="button" onclick="javascript:adopt_bill_record(this, <?=isset($row['NO']) ? $row['NO'] : '' ?>, <?=isset($row['TELLER_SERIAL']) ? $row['TELLER_SERIAL'] : '' ?>);" >اعتماد السجل </button>
                    <?php } else {
                        if( $row['FINANCIAL_CHAIN_NUMBER'] == 0 && $row['STATUS'] == 1 && $row['CLOSE_STATUS'] == 2 &&  HaveAccess($close_bills_url) ){
                            if($user_type == 1){ ?>
                                <button class="btn btn-xs btn-danger"  id="btn_close_selected_bills"  onclick="javascript:close_selected_bills(<?=$i?>, <?=isset($row['NO']) ? $row['NO'] : '' ?> , <?=$close_date?>, <?=isset($row['TELLER_SERIAL']) ? $row['TELLER_SERIAL'] : '' ?>);">
                                    اغلاق السند
                                </button>
                    <?php  } else{ ?>
                                <button class="btn btn-xs btn-danger"  id="btn_close_selected_bills"  onclick="javascript:close_selected_bills_comp(<?=$i?>, <?=isset($row['NO']) ? $row['NO'] : '' ?> , <?=$comp_date?>, <?=isset($row['TELLER_SERIAL']) ? $row['TELLER_SERIAL'] : '' ?>);">
                                    اغلاق السند
                                </button>
                    <?php  } ?>
                    <?php } else {
                            if( HaveAccess($report_receipt_url)):  ?>
                        <button class="btn btn-xs btn-primary" type="button"
                                        onclick="javascript:print_receipt(<?=isset($row['NO']) ? $row['NO'] : '' ?>,<?=$this->user->id?>,<?=$row['TELLER_SERIAL']?>,<?=$row['C_DATE']?>);" > ايصال القبض
                     <?php endif;
                            }
                        }
                    ?>
                    <?php if( HaveAccess($report_bills_url)):  ?>
                        <button class="btn btn-xs btn-success" type="button"
                                onclick="javascript:print_report(<?=isset($row['NO']) ? $row['NO'] : '' ?>,<?=$this->user->id?>,<?=$row['TELLER_SERIAL']?>,<?=$user_type == 1 ? $row['C_DATE'] : $comp_date?> ,<?=$user_type?>);" >عرض السندات
                    <?php endif; ?>

                    <?php   if( HaveAccess($back_stage_url) && ($row['CHECK_RETURN'] == 0) && ($row['CLOSE_STATUS'] == 2) ): ?>
                            <button class="btn btn-xs btn-info" type="button" onclick="javascript:back_stage(<?=$row['TELLER_SERIAL']?>,1);" >ارجاع
                    <?php endif; ?>

                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>


<?php
$scripts = <<<SCRIPT
<script type="text/javascript">

		 

</script>
SCRIPT;
sec_scripts($scripts);
?>

