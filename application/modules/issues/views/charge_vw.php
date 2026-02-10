<?php
$MODULE_NAME= 'issues';
$TB_NAME= 'bonds';
//$count = $offset;
?>

<div class="tbl_container">
    <table class="table" id="charge_page_tb" data-container="container">
        <thead>
        <tr>
            <th colspan="3">   12 شحنات الأخيرة </th>
           
        </tr>
        <tr>
            <th>رقم الاشتراك</th>
            <th>تاريخ الشحنة</th>
            <th>قيمة الشحنة</th>
        </tr>
        </thead>
        <tbody>
       
        <?php foreach($page_rows as $row) :?>
            <tr>
                <td><?=@$row['SUB_NO']?></td>
                <td><?=@$row['BUYDATE']?></td>
                <td><?=@$row['ACCEPT']?></td>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>
</div>

<script>

</script>