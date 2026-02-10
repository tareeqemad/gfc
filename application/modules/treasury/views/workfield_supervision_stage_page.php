<?php
    $MODULE_NAME= 'treasury';
    $TB_NAME= 'workfield';
    $report_bills_url= base_url("$MODULE_NAME/$TB_NAME/report_bills");
    $second_back_stage_url= base_url("$MODULE_NAME/$TB_NAME/second_back_stage");
    $supervision_adopt= base_url("$MODULE_NAME/$TB_NAME/supervision_adopt");

    $i = 1; $sum_rec = 0; $sum_total = 0; $flag = 0;

    $ids_to_transfer ="0";
    foreach ($users as $user){
        $sum_rec+= $user['RECEIVED_VALUE'];
        $sum_total+= $user['TOTAL'];
        if( $user['CLOSE_STATUS'] == 4 || ($user['CLOSE_STATUS'] == 6 && $user['CHECK_RETURN'] == 0 ) ) {
            $ids_to_transfer.=',' . $user['TELLER_SERIAL'];
            $flag++;
        }
    }

?>
<hr>
<div class="form-body">
    <!--<div class="alert alert-info col-md-10 col-md-offset-1" >
        اجماليات التحصيل الخاصة بمندوبي التحصيل الميداني لتاريخ <span class="badge badge-4"><strong><?=$date?></strong></span>
    </div> -->
    <div class="container">
        <div class="info">
                <span class="alert alert-info box floatLeft  mr-20">
                    اجمالي المبلغ المرحل
                     <strong class="sum_total"><?=$sum_total?></strong>
                </span>

            <span class="alert alert-danger box floatLeft">
                    اجمالي المبلغ المقبوض
                     <strong class="sum_rec"  id="total-selected"><?=$sum_rec?></strong>
                </span>


            <?php if( HaveAccess($supervision_adopt) &&  (count($users) > 0) && ($flag > 0) ):  ?>
                <?php if($user_type == 1){ ?>
                    <button class="btn btn-success" style="line-height: 95px;" onclick="javascript:transfer_to_bills('<?=$ids_to_transfer?>' , <?=$new_date?>, 1);">
                        ترحيل على الفواتير
                    </button>
                <?php } else { ?>
                    <button class="btn btn-success" style="line-height: 95px;" onclick="javascript:transfer_to_bills('<?=$ids_to_transfer?>' , <?=$comp_date?>, 2);">
                        ترحيل على الفواتير
                    </button>
                <?php }?>

            <?php endif; ?>
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
            <th>
                <span class="badge badge-2">فائض</span> /
                <span class="badge badge-danger">عجز</span>
            </th>
			   <th>مستند بنك فواتير</th>
            <th>الحالة</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($users as $row) : if($row['CLOSE_STATUS'] == 6){ echo '<tr class="case_5">'; } else { echo '<tr class="">'; } ?>
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
                    <input type="text" readonly data-id="income_val" id="txt_in_val_<?=$i?>" value="<?= $row['RECEIVED_VALUE'] ?>" data-pk="<?=$i?>" class="form-control in_val"/>
                    <input type="hidden" id="h_txt_in_val_<?=$i?>" value="<?= $row['RECEIVED_VALUE'] ?>" data-pk="<?=$i?>" class="form-control"/>
                </td>
                <td>
                    <?php $differance = $row['RECEIVED_VALUE'] - $row['TOTAL'] ?>
                    <input  readonly style="background-color:<?php if($differance > 0){ ?> #c5eac5 <?php } else if ($differance == 0) { ?> #ffffff <?php } else { ?> #e6afaf<?php }?>" type="text" data-id="differance_val" id="txt_dif_<?=$i?>"  value="<?= $differance ?>"  class="form-control "/>
                </td>
				  <td>
                    <?= $row['RECORD_NO'] ?>
                </td>
                <td>
                    <?= $row['STATUS_NAME'] ?>
                </td>
				
                <td>
                    <?php if(HaveAccess($report_bills_url)):  ?>
                        <button class="btn btn-xs btn-success" type="button"
                                onclick="javascript:print_report(<?=isset($row['NO']) ? $row['NO'] : '' ?>,<?=$this->user->id?>,<?=$user_type == 1 ? $row['TELLER_SERIAL'] : $comp_date?>,<?=$row['C_DATE']?>,<?=$user_type?>);" >عرض السندات
                        </button>
                    <?php endif; ?>
                    <?php if(HaveAccess($second_back_stage_url) && ($row['CHECK_RETURN'] == 0)  ):  ?>
                        <button class="btn btn-xs btn-danger" type="button"
                                onclick="javascript:back_stage(<?=$row['TELLER_SERIAL']?>,2);" >ارجاع
                        </button>
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

