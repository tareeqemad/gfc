<?php
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 28/11/18
 * Time: 01:29 م
 */

?>

<div class="alert alert-info" role="alert"><strong> اجمالي الاذونات لشهر <?=$emp_balance['MONTH_']?> </strong>
    <p>
    <table class="table" id="teamTbl" data-container="container">
        <tbody>
        <tr>
            <td style="font-weight: bold">عدد الاذونات</td>
            <td <?=($emp_balance['CNT']>3)?'style="background-color: #ffb0b3"':''?> ><?=$emp_balance['CNT']?></td>
        </tr>
        <tr>
            <td style="font-weight: bold">مدة الاذونات</td>
            <td><?=$emp_balance['TOTAL']?></td>
        </tr>
        </tbody>
    </table>
    </p>
</div>
