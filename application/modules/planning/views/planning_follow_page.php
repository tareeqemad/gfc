<?php
/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 18/03/18
 * Time: 11:01 ص
 */

$MODULE_NAME= 'planning';
$TB_NAME= 'planning';
$edit_url =base_url("$MODULE_NAME/$TB_NAME/get");
$edit_without_cost_url=base_url("$MODULE_NAME/$TB_NAME/get_without_cost");
$select_items_url =base_url("$MODULE_NAME/$TB_NAME/public_follow_project");
$count = 1;
$page=1;
echo AntiForgeryToken();
function class_name($month){
    if($month==date('m')){
        return 'case_2';
    }
}

?>
<input type="hidden" id="base_url" value="<?=$select_items_url?>">
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


       <th>الأنشطة الفرعية</th>








    </tr>
    </thead>

    <tbody>
    <?php foreach($page_rows as $row) :

        ?>
        <tr class="<?=class_name(@$row['FROM_NO'])?>">
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
            <td> <button type="button" id="btn_active_<?= $count ?>" class="btn btn-warning" data-ser1='<?=@$row['SEQ']?>'  name="active[]">الأنشطة الفرعية</button> </td>










            <?php $count++ ?>
        </tr>
    <?php endforeach;?>

    </tbody>
</table>
</div>


<script type="text/javascript">
    $(document).ready(function() {

        reBind_pram(0);

        base_url
        function reBind_pram(cnt){


            $('select[name="from_month[]"]').select2().on('change',function(){

                //  checkBoxChanged();


            });
            $('button[name="active[]').on('click',  function (e) {

              //  alert($(this).attr('data-ser1'));
              //  console.log($('#base_url').val()+'/'+$(this).attr('data-ser1'));
                _showReport($('#base_url').val()+'/'+$(this).attr('data-ser1'));




            });


        }


    });


</script>

