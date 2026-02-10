<?php

$MODULE_NAME= 'issues';
$TB_NAME= 'checks';

$create_url =base_url("$MODULE_NAME/$TB_NAME/create");
$count = $offset;



?>



<div class="tbl_container">
    <table class="table" id="page_tb" data-container="container">
        <thead>
        <tr>

            <th  >#</th>
            <th >رقم الشيك</th>
            <th>اسم صاحب الشيك</th>
            <th  >البنك</th>
            <th class="price">المبلغ</th>
            <th>العملة</th>
            <th  > تاريخ الاستحقاق </th>
            <th>حالة السند  </th>

            <th style="width: 183px;">معالجة</th>

        </tr>
        </thead>


        <tbody>
        <?php if($page > 1): ?>
            <tr >
                <td colspan="12" id="page-<?= $page ?>" class="page-sector" data-page="<?= $page ?>"></td>
            </tr>
        <?php endif; ?>
        <?php foreach($page_rows as $row) :?>
            <tr id="tr_<?=$row['SEQ']?>" >
                <td><?= $count ?></td>
                <td><?= $row['CHECK_ID'] ?></td>
                <td><?= $row['CHECK_CUSTOMER'] ?></td>
                <td><?= $row['CHECK_BANK_ID_NAME'] ?></td>
                <td><?= $row['CRIDET'] ?></td>
                <td><?= $row['CURR_ID_NAME'] ?></td>
                <td><?= $row['CHECK_DATE'] ?></td>
                <td><?= $row['CHECKS_CASE_NAME'] ?></td>

                <td class="align-center">

                <a href="<?= "{$create_url}/{$row['SEQ']}" ?>" ><i class='glyphicon glyphicon-share'></i></a>
                </td>

            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
</div>

<script>

</script>













