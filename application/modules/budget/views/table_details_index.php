<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 01/12/14
 * Time: 09:19 ص
 */

$report_url =base_url('reports?type=31');
$count = 1;
$balance = 0;
$z= 0;
?>


<?php foreach($rows as $index => $row) :?>
    <tr>

        <td><?= $count ?></td>
        <td> </td>
        <td> <?= $row['S_T_SER'].":".nl2br($row['SNAME']) ?></td>
        <td><?= $row['BRANCH_NAME'] ?> </td>
        <td><?= n_format($row['TOTAL']) ?></td>
        <td> <?php $balance =$row['BALANCE'] < 0 ?$row['BALANCE'] * -1 :$row['BALANCE'] ; ?><?= n_format($balance)?></td>
        <td><?= $row['TOTAL'] == 0 ? 0 : number_format(($balance /$row['TOTAL'])*100,2) ?>%</td>
        <td> <?php $z = $row['TOTAL'] - $balance; ?> <?= n_format($z) ?></td>
        <td><?=$row['TOTAL'] == 0 ? 0 : number_format(($z/$row['TOTAL'])*100,2) ?>%</td>
    </tr>

    <?php $count++; ?>


<?php endforeach;?>
