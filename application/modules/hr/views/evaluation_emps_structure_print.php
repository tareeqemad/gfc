<?php
/**
 * Created by PhpStorm.
 * User: mtaqia
 * Date: 10/08/16
 * Time: 01:27 م
 */

$col_cnt= array();
for($i=1; $i<=count(array_keys($page_rows[0])) ;$i++){
    $col= array_column($page_rows, 'L'.$i);
    $col_cnt[$i]= count(array_filter($col));
}

$col_cnt= array_filter($col_cnt);
$col_index= array_keys($col_cnt);

?>

<div class="tbl_container">
    <table class="table" id="page_tb" data-container="container">
        <thead>
        <tr>
            <?php for($i=1;$i<=count($col_index);$i++){
                echo "<th> المستوى {$i} </th>";
            } ?>
        </tr>
        </thead>
        <tbody>
        <?php for($r=0;$r<count($page_rows);$r++){ ?>
            <tr>
                <?php for($i=0;$i<count($col_index);$i++){ ?>
                    <td><?=$page_rows[$r]['L'.$col_index[$i]]?></td>
                <?php } ?>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
