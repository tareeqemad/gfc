<?php

    function return_color($recieve_month,$from_month_){
        if ($recieve_month <  $from_month_) {
            echo "ddd_1";
        }else{
            echo "ddd_0";
        }
     }

?>
<style>
    .ddd_0 {
        color: #4459b9;
    }

    .ddd_1 {
        color: #f34343 ;
    }
</style>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="collections_tbl">
                <thead class="table-active">
                <tr>
                    <th>الشهر</th>
                    <th>رقم الاشتراك</th>
                    <th>تاريخ الادخال</th>
                    <th>المبلغ المدفوع</th>
                    <th>مبلغ الشحن</th>
                    <th>سداد من المتأخرات</th>
                    <th>نوع الاشتراك</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $total_paid_value = 0;
                $total_charge_value = 0;
                $total_to_bills = 0;
                foreach ($rertMainAdopt as $rows) {
                    $total_paid_value += $rows['PAID_VALUE'];
                    $total_charge_value += $rows['CHARGE_VALUE'];
                    $total_to_bills += $rows['TO_BILLS'];
                    $old_date_timestamp = strtotime($rows['ENTRY_DATE']);
                    $new_date = date('d-m-Y', $old_date_timestamp);
                    ?>
                    <tr class="<?=return_color($rows['FOR_MONTH'],$from_month_)?>">
                        <td><?= $rows['FOR_MONTH'] ?></td>
                        <td><?= $rows['SUBSCRIBER'] ?></td>
                        <td><?= $new_date ?></td>
                        <td><?= $rows['PAID_VALUE'] ?></td>
                        <td><?= $rows['CHARGE_VALUE'] ?></td>
                        <td><?= $rows['TO_BILLS'] ?></td>
                        <td><?= $rows['TYPE_NAME'] ?></td>
                    </tr>
                <?php } ?>
                </tbody>
                <tfoot>
                <tr>
                    <td>الاجمالي</td>
                    <td></td>
                    <td></td>
                    <td class="text-danger"><?= number_format($total_paid_value, 2) ?></td>
                    <td class="text-danger"><?= number_format($total_charge_value, 2) ?></td>
                    <td class="text-danger"><?= number_format($total_to_bills, 2) ?></td>
                    <td colspan="10"></td>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>