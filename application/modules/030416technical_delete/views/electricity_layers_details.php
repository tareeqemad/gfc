<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 05/12/15
 * Time: 09:33 ص
 */

$count=0;

function class_name($case){
    if($case==0){
        return 'case_0';
    }elseif($case==1){
        return 'case_4';
    }else{
        return 'case_1';
    }
}
?>

<div class="tb_container">
    <table class="table" id="details_tb" data-container="container">
        <thead>
        <tr>
            <th style="width: 2%">#</th>
            <th style="width: 7%">رقم الوردية</th>
            <th style="width: 7%">من</th>
            <th style="width: 7%">الى</th>
            <th style="width: 7%">الحالة</th>
        </tr>
        </thead>

        <tbody>

<?php if(count($details) > 0) { // عرض
    $count = -1;
    foreach($details as $row) {
        ?>
        <tr class="<?=class_name($row['LAYERS_DETAIL_CASE'])?>" >
            <td><?=++$count+1?></td>
            <td><?=$row['SHIFT_ID']?></td>
            <td><?=$row['TIME_FROM']?></td>
            <td><?=$row['TIME_TO']?></td>
            <td><?=$row['LAYERS_DETAIL_CASE_NAME']?></td>
        </tr>
    <?php
    }
}
?>

        </tbody>
    </table>
</div>
