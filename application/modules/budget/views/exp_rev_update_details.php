<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 03/11/14
 * Time: 12:58 م
 */

$TB_NAME= 'exp_rev_update_details';
?>

<table class="table" id="<?=$TB_NAME?>_tb" data-container="container">
    <thead>
    <tr>
        <th>م</th>
        <th>الدائرة</th>
        <th>الاجمالي</th>
    </tr>
    </thead>
    <tbody>
    <?php
    $count=0;
    foreach ($dpt_total as $row){
        $count++;
        echo "
            <tr>
                <td>$count</td>
                <td>{$row['ST_NAME']}</td>
                <td class='total'>".number_format($row['TOTAL'],2)."</td>
            </tr>
            ";
    }
    ?>
    </tbody>

    <tfoot>
    <tr>
        <th></th>
        <th>الاجمالي</th>
        <th class='total'></th>
    </tr>
    </tfoot>

</table>

<script type="text/javascript" >
    $(document).ready(function() {
        totals('<?=$TB_NAME?>_tb');
    });
</script>
