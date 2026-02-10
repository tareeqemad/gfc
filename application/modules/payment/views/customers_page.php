<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 06/11/14
 * Time: 10:40 ص
 */

$TB_NAME= 'customers';
$count = $offset;
?>
<div class="tbl_container">
    <table class="table selected-red" id="<?=$TB_NAME?>_tb" data-container="container">
        <thead>
        <tr>
            <th>#</th>
            <th>رقم الهوية أو مشتغل المرخص</th>
            <th>اسم المستفيد</th>
            <th class="price">الرصيد</th>
            <th>نوع المستفيد</th>
            <th>الحساب</th>
            <th>المصدر</th>
            <th style="width: 80px;"></th>
        </tr>
        </thead>
        <tbody>

        <?php if($page > 1): ?>
            <tr>
                <td colspan="11" id="page-<?= $page ?>" class="page-sector" data-page="<?= $page ?>"></td>

            </tr>
        <?php endif; ?>
        <?php

        foreach ($get_list as $row){
            $count++;
            ?>
            <tr ondblclick="javascript:select_customer('<?=$row['CUSTOMER_ID']?>','<?=$row['CUSTOMER_NAME']?>','<?=$row['ACCOUNT_TYPE_LIST']?>');">
                <td><?=$count?></td>
                <td><?=$row['CUSTOMER_ID']?></td>
                <td><?=$row['CUSTOMER_NAME']?></td>
                <td class="price"><?=check_credit($row['BALANCE'])?></td>
                <td><?=$row['CUSTOMER_TYPE_DESC']?></td>
                <td><?=$row['ACOUNT_NAME']?></td>
                <td><?=$row['SOURCE_NAME']?></td>
                <td>
                    <a href="javascript:;"  class="btn-xs btn-success" onclick="javascript:select_account('<?= $row['CUSTOMER_ID'] ?>','<?= $row['CUSTOMER_NAME'] ?>','');"><i class="icon icon-print"></i> التقارير</a>
                 </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
<?php echo $this->pagination->create_links(); ?>
