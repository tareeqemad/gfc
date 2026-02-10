<?php
/**
 * Created by PhpStorm.
 * User: mbadawi
 * Date: 27/06/16
 * Time: 11:51 ص
 */ 
$details= $return_details;
$count = 0;
$total = 0;

$details1= $return_details1;
$count1 = 0;
$total1 = 0;

?> 
<div class="tb_container">
   <table class="table" id="details_tb" data-container="container">
        <thead>
        <tr>
            <th style="width: 2%">#</th>
            <th style="width: 11%">اسم الموظف</th>
            <th style="width: 30%">رئيس اللجنة</th>
            <th style="width: 57%">ملاحظات</th>
        </tr>
        </thead>

        <tbody>
         <?php if(count($details) <= 0) { ?>
            <tr>
                <td><i class="glyphicon glyphicon-sort" /></i></td>
                <td>
                    <input type="hidden" name="SER[]" value="0" />
                    <select name="EMP_ID[]" class="form-control" id="TXT_EMP_ID<?=$count?>" tabindex="5"><option></option></select>
                </td>
                <td>
                    <select name="MANAGER[]" class="form-control" id="TXT_MANAGER<?=$count?>" tabindex="6"></select>
                </td>
                <td>
                    <input name="EMP_NOTE[]" data-val="0" data-val-required="required" class="form-control" tabindex="7" id="TXT_EMP_NOTE<?=$count?>" />
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
                    <select name="MANAGER[]" class="form-control" id="TXT_MANAGER<?=$count?>" data-val="<?=$row['MANAGER']?>"></select>
                </td>
                <td>
                    <input name="EMP_NOTE[]" data-val="0" data-val-required="required" class="form-control" id="EMP_NOTE<?=$count?>" value="<?=$row['EMP_NOTE']?>" />
                </td>
            </tr>
            <?php } ?>
        </tbody>
        <tfoot>
           <tr> 
            <th></th>
            <th>
                    <a onclick="javascript:addRowsEmp();" href="javascript:;"><i class="glyphicon glyphicon-plus"></i>جديد</a>
            </th>
            <th></th>
            <th></th>
        </tr>
        </tfoot>
   </table>
</div>
<!---------------------->
<div class="tb_container">
   <table class="table" id="details1_tb" data-container="container">
        <thead>
        <tr>
            <th style="width: 2%">#</th>
            <th style="width: 11%">مدير الإدارة</th>
            <th style="width: 11%">الإدارة</th>
            <th style="width: 37%">ملاحظات</th>
        </tr>
        </thead>

        <tbody>
         <?php if(count($details1) <= 0) { ?>
            <tr>
                <td><i class="glyphicon glyphicon-sort" /></i></td>
                <td>
                    <input type="hidden" name="SER1[]" value="0" />
                    <select name="EMP_MANAGER_ID[]" class="form-control" id="TXT_EMP_MANAGER_ID<?=$count1?>" tabindex="9"><option></option></select>
                </td>
                <td>
                    <select name="MANAGEMENT_NO[]" class="form-control" id="TXT_MANAGEMENT_NO<?=$count1?>" tabindex="10"><option></option></select>
                </td>
                <td>
                    <input name="TREE_NOTE[]" data-val="0" data-val-required="required" class="form-control" id="TXT_TREE_NOTE<?=$count1?>" tabindex="11" />
                </td>
            </tr>

        <?php
        }else
            $count1 = -1;
        ?>
         <?php
        foreach($details1 as $rowS) {
            $count1++;
            ?>
            <tr>
            <td><?=$count1+1?></td>
            <td>
              <input type="hidden" name="SER1[]" value="<?=$rowS['SER']?>" />
              <select name="EMP_MANAGER_ID[]" class="form-control" id="TXT_EMP_MANAGER_ID<?=$count1?>" data-val="<?=$rowS['EMP_MANAGER_ID']?>"><option></option></select>
             </td>
             <td>
                   <select name="MANAGEMENT_NO[]" class="form-control" id="TXT_MANAGEMENT_NO<?=$count1?>" data-val="<?=$rowS['MANAGEMENT_NO']?>"><option></option></select>
                </td>
                <td>
                    <input name="TREE_NOTE[]" data-val="0" data-val-required="required" class="form-control" id="TXT_TREE_NOTE<?=$count1?>" value="<?=$rowS['TREE_NOTE']?>" />
                </td>
            </tr>
            <?php } ?>
        </tbody>
        <tfoot>
           <tr> 
            <th></th>
            <th>
                    <a onclick="javascript:addRowsTree();" href="javascript:;"><i class="glyphicon glyphicon-plus"></i>جديد</a>
            </th>
            <th></th>
        </tr>
        </tfoot>
   </table>
</div>