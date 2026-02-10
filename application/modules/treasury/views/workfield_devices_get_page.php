<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 06/10/2022
 * Time: 10:02 AM
 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$MODULE_NAME = 'treasury';
$TB_NAME = 'workfield';
$show_bills_url = base_url("$MODULE_NAME/$TB_NAME/first_satge_adopt");
$change_status_device_url = base_url("$MODULE_NAME/$TB_NAME/change_status_device");
$receive_device_url = base_url("$MODULE_NAME/$TB_NAME/receive_device");
$count = 0;
?>

<hr>
<div class="form-body">
    <table class="table info">
        <thead>
        <tr>
            <th>#</th>
            <th>الرقم المسلسل للجهاز</th>
            <th>الرقم الوظيفي</th>
            <th>اسم المحصل</th>
            <th>حالة الجهاز</th>
            <th>تاريخ الاستلام</th>
            <th>تاريخ التسليم</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($page_rows as $row) :
            $count++;
            if($row['STATUS'] == 1 && $row['DELIVERY_DATE'] == null)
                echo '<tr class="case_4">';
            else if($row['STATUS'] == 2)
                echo '<tr class="case_0">';
            else
                echo '<tr class="case_5">';
            ?>
            <td><?= $count ?></td>
            <td><strong><?= $row['DEVICE_NO'] ?></strong></td>
            <td><?= $row['USER_NO'] ?></td>
            <td><?= $row['NAME'] ?></td>
            <td><?= $row['STATUS'] ?></td>
            <td><?= $row['RECEIVED_DATE'] ?></td>
            <td><?= $row['DELIVERY_DATE'] ?></td>
            <td>
               <?php if( HaveAccess($change_status_device_url) && $row['STATUS'] == 1 && $row['DELIVERY_DATE'] == null) { ?>
                <button class="btn btn-xs btn-danger"  onclick="javascript:change_status_device( <?=isset($row['DEVICE_SER']) ? $row['DEVICE_SER'] : '' ?>,3) ;">
                    تعطيل الجهاز
                </button>
                <?php } else if ( HaveAccess($change_status_device_url) && ($row['STATUS'] == 2  || $row['STATUS'] == 3) && $row['DELIVERY_DATE'] == null) { ?>
                    <button class="btn btn-xs btn-success"  onclick="javascript:change_status_device( <?=isset($row['DEVICE_SER']) ? $row['DEVICE_SER'] : '' ?>,1) ;">
                        تفعيل الجهاز
                    </button>
                <?php  } ?>
                <?php if( HaveAccess($receive_device_url) && $row['DELIVERY_DATE'] == null && $row['STATUS'] == 1) { ?>
                    <button class="btn btn-xs btn-primary"  onclick="javascript:receive_device(<?=isset($row['SER']) ? $row['SER'] : '' ?>) ;">
                        استلام الجهاز
                    </button>
                <?php } ?>
            </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
