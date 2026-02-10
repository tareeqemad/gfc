<?php
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 16/10/18
 * Time: 09:50 ص
 */

function case_trans($val=0){
    if($val>0){
        $ret= 'background-color: #adffb4';
    }elseif($val<0){
        $ret= 'background-color: #ffb0b3';
    }else{
        $ret='';
    }
    return $ret;
}

?>

<div class="alert alert-info" role="alert">
    <strong> رصيد الاجازات
    <?=$emp_balance['BALANCE_YEAR']?>
    </strong>
    <p>
    <table class="table" id="teamTbl" data-container="container">
        <thead>
        <tr>
            <th>الرصيد / الاجازة</th>
            <th>عادية</th>
            <th>طارئة</th>
            <th>مرضية</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td style="font-weight: bold">كلي</td>
            <td><?=$emp_balance['NORMAL_TOTAL']?></td>
            <td><?=$emp_balance['EMERGENCY_TOTAL']?></td>
            <td>-</td>
        </tr>
        <tr>
            <td style="font-weight: bold">سنوات اخرى</td>
            <td style="<?=case_trans($emp_balance['NORMAL_TRANS'])?>"><?=$emp_balance['NORMAL_TRANS']?></td>
            <td>-</td>
            <td>-</td>
        </tr>
        <tr>
            <td style="font-weight: bold">مستهلك</td>
            <td><?=$emp_balance['NORMAL_USED']?></td>
            <td><?=$emp_balance['EMERGENCY_USED']?></td>
            <td><?=$emp_balance['SICK_USED']?></td>
        </tr>
        <tr>
            <td style="font-weight: bold">بدل اذونات</td>
            <td><?=$emp_balance['NORMAL_EXIT_USED']?></td>
            <td><?=$emp_balance['EMERGENCY_EXIT_USED']?></td>
            <td>-</td>
        </tr>
        <tr>
            <td style="font-weight: bold">متبقي</td>
            <td <?=($emp_balance['NORMAL_LEFT']<0)?'style="background-color: #ffb0b3"':''?> ><?=$emp_balance['NORMAL_LEFT']?></td>
            <td <?=($emp_balance['EMERGENCY_LEFT']<0)?'style="background-color: #ffb0b3"':''?> ><?=$emp_balance['EMERGENCY_LEFT']?></td>
            <td>-</td>
        </tr>
        </tbody>
    </table>
    </p>
</div>
