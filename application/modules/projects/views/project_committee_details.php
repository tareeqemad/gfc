<?php
/**
 * Created by PhpStorm.
 * User: mbadawi
 * Date: 28/07/16
 * Time: 11:10 ص
 */ 
$details= $return_details;
$count = 0;

?> 
<div class="tb_container">
   <table class="table" id="details_tb" data-container="container">
        <thead>
        <tr>
            <th style="width: 2%">#</th>
            <th style="width: 11%">اسم الموظف</th>
            <th style="width: 50%">رئيس اللجنة</th>
        </tr>
        </thead>

        <tbody>
         <?php if(count($details) <= 0) { ?>
            <tr>
                <td><i class="glyphicon glyphicon-sort" /></i></td>
                <td>
                    <input type="hidden" name="SER[]" value="0" />
                    <select name="EMP_ID[]" class="form-control" id="TXT_EMP_ID<?=$count?>" ><option></option></select>
                </td>
                <td>
                    <select name="THE_TYPEE[]" class="form-control" id="TXT_THE_TYPEE<?=$count?>"></select>
                </td>
            </tr>

        <?php
        }else
            $count = -1;
        ?>
         <?php
        foreach($details as $row) {
            $count++;
            ?>
            <tr>
            <td><?=$count+1?></td>
            <td>
             <input type="hidden" name="SER[]" value="<?=$row['SER']?>" />
             <select name="EMP_ID[]" class="form-control" id="TXT_EMP_ID<?=$count?>" data-val="<?=$row['EMP_ID']?>"><option></option></select>
             </td>
             <td>
               <select name="THE_TYPEE[]" class="form-control" id="TXT_THE_TYPEE<?=$count?>" data-val="<?=$row['THE_TYPE']?>"></select>
             </td>
            </tr>
            <?php } ?>
        </tbody>
        <tfoot>
           <tr>
            <th></th>
            <th><a onclick="javascript:addRows();" href="javascript:;"><i class="glyphicon glyphicon-plus"></i>جديد</a></th>
            <th></th>
        </tr>
        </tfoot>
   </table>
</div>