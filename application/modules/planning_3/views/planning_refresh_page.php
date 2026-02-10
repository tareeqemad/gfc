<?php
/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 09/01/18
 * Time: 09:11 ص
 */


$MODULE_NAME= 'planning';
$TB_NAME= 'planning';
$edit_url =base_url("$MODULE_NAME/$TB_NAME/get");
$edit_without_cost_url=base_url("$MODULE_NAME/$TB_NAME/get_without_cost");
$count = 1;
$page=1;
echo AntiForgeryToken();
function class_name($month){
    if($month==date('m')){
        return 'case_2';
    }
}

?>

<table class="table" id="<?=$TB_NAME?>_tb" data-container="container">
    <thead>
    <tr>
        <th class="hidden"  >#<input type="checkbox" class="group-checkable" data-set="#emp_tb .checkboxes"></th>
        <th>م</th>
        <th>العام</th>
        <th>المقر</th>
        <th>مسؤولية التنفيذ</th>
        <th>اسم المشروع</th>
        <th>مدة التنفيذ</th>
        <th>بداية المشروع</th>
        <th>نهاية المشروع</th>


        <th>حالة الانجاز</th>
        <th style="width: 3%">%نسبة الانجاز</th>
        <th>المبررات</th>
        <th>حالة الخطة</th>
        <th>مرحل الى شهر</th>








    </tr>
    </thead>

    <tbody>
    <?php foreach($page_rows as $row) :

        ?>
        <tr class="<?=class_name($row['NEW_FROM_MONTH'])?>">
            <td class="hidden"><input type='checkbox' class='checkboxes' name="ischeck[]" value='<?=@$row['SER']?>' data-ser='<?=@$row['SER']?>'  data-check='<?=@$row['IS_CHECK']?>' ser_plan='<?=@$row['SEQ']?>' for_month='<?=@$row['MONTH_ACHIVE']?>'></td>
            <td><?=@$count?> <input type="hidden" name="seq[]" value="<?=$row['SEQ']?>" />
                <input type="hidden" name="ser_plan[]" value="<?=@$row['SER_PLAN']?>" />
                <input type="hidden" name="ser[]" value="<?=$row['SER']?>" />
            </td>
            <td><?=@$row['YEAR']?></td>
            <td><?=@$row['BRANCH_NAME']?></td>
            <td><?=@$row['MANAGE_EXE_ID_NAME']?></td>
            <td><?=@$row['ACTIVITY_NAME'].''.@$row['PROJECT_NAME']?></td>
            <td><?=@$row['TO_MONTH']-$row['FROM_MONTH']?></td>
            <td><?=/*@ months($row['NEW_FROM_MONTH']).'==> '.*/@months($row['NEW_FROM_MONTH']) ?></td>
            <td><?php

                if((12-$row['NEW_FROM_MONTH'])<($row['TO_MONTH']-$row['FROM_MONTH']))
                    echo /*months($row['TO_MONTH']).'==> '.*/@months((12-$row['NEW_FROM_MONTH'])+$row['NEW_FROM_MONTH']);
                else
                    echo /*months($row['TO_MONTH']).'==> '.*/@months($row['NEW_FROM_MONTH']+($row['TO_MONTH']-$row['FROM_MONTH']));

                ?></td>
            <td><?=@$row['STATUS_NAME']?>
            </td>
            <td style="width: 3%"><?=@$row['PERSANT']?>
              </td>
            <td><?=@$row['NOTES']?>
            </td>
            <td><?=@$row['IS_END_NAME']?>
            </td>
            <td>
                <select  name="from_month[]" id="dp_from_month"  data-curr="false"  class="form-control"  >
                    <option value="">-</option>
                    <?php for($i = 1; $i <= 12 ;$i++) :?>
                        <option <?PHP if ($i==$row['MONTH_ACHIVE']){ echo " selected"; } ?>  value="<?= $i ?>"><?= months($i) ?></option>


                    <?php endfor; ?>
                </select>
                        <span class="field-validation-valid" data-valmsg-for="type" data-valmsg-replace="true">
            </td>







            <?php $count++ ?>
        </tr>
    <?php endforeach;?>

    </tbody>
</table>
</div>


<script type="text/javascript">
    $(document).ready(function() {

        reBind();
        function reBind(){


            $('select[name="from_month[]"]').select2().on('change',function(){

                //  checkBoxChanged();


            });


        }

    });


</script>

