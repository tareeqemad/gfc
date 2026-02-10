<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

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
            <th>نوع المستفيد</th>
            <th>المصدر</th>

        </tr>
        </thead>
        <tbody>

        <?php if($page > 1): ?>
            <tr>
                <td colspan="11" id="page-<?= $page ?>" class="page-sector" data-page="<?= $page ?>"></td>

            </tr>
        <?php endif; ?>
        <?php

        foreach ($page_rows as $row){
            $count++;
            ?>
            <tr ondblclick="javascript:select_customer('<?=$row['CUSTOMER_ID']?>');">
                <td><?=$count?></td>
                <td><?=$row['CUSTOMER_ID']?></td>
                <td><?=$row['CUSTOMER_NAME']?></td>
                <td><?=$row['CUSTOMER_TYPE_DESC']?></td>
                <td><?=$row['SOURCE_NAME']?></td>


            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
<?php echo $this->pagination->create_links(); ?>
