<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 17/03/18
 * Time: 12:47 م
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
        <th>م</th>
        <th>العام</th>
        <th>المقر</th>
        <th>رقم النشاط</th>
        <th>الغاية</th>
        <th>الهدف الاستراتيجي</th>
        <th>الهدف التشغيلي</th>
        <th>التصنيف</th>
        <th>نوع النشاط</th>
        <th>مصدر التمويل</th>
        <th>اسم المشروع</th>
        <th>التكلفة</th>
        <th>مسؤولية المتابعة</th>
        <th>مسؤولية التنفيذ</th>
        <th>مدة التنفيذ</th>

        <th>نهاية المشروع</th>
        <th>بداية المشروع</th>

    </tr>
    </thead>

    <tbody>
    <?php foreach($page_rows as $row) :

        ?>
        <tr class="<?=class_name($row['NEW_FROM_MONTH'])?>">
            <td class="hidden"><input type='checkbox' class='checkboxes' name="ischeck[]" value='<?=@$row['SER']?>' data-id='<?=@$row['SER']?>' data-check='<?=@$row['IS_CHECK']?>' ser_plan='<?=@$row['SER_PLAN']?>' ser_status_val='<?=@$row['STATUS']?>' ser_next_month_val='<?=@$row['NEW_FROM_MONTH']?>'></td>
            <td><?=@$count?>
                <input type="hidden" name="seq[]" value="<?=$row['SEQ']?>" />
                <input type="hidden" name="ser_plan[]" value="<?=$row['SER_PLAN']?>" />
                <input type="hidden" name="ser[]" value="<?=$row['SER']?>" />
            </td>
            <td><?=@$row['YEAR']?></td>
            <td><?='الغاية الرابعة (د): تحسين أداء الشركة ورفع كفاء موظفيها.'//@$row['BRANCH_NAME']?></td>
            <td><?='19- توظيف التقنيات المتوفرة لتحسين القدرة التشغيلية والكفاءة'//@$row['MANAGE_EXE_ID_NAME']?></td>
            <td><?='توعية الشركة بتقنية Load Flow'//@$row['ACTIVITY_NAME'].''.@$row['PROJECT_NAME']?></td>
            <td><?='مشروع بدون تكلفة';//@$row['YEAR']?></td>
            <td><?='مشروع';//@$row['YEAR']?></td>
            <td><?=@$row['PROJECT_NAME']?></td>
            <td><?='0'//@$row['PROJECT_NAME']?></td>


            <td><?=@$row['TO_MONTH']-$row['FROM_MONTH']?></td>
            <td><?=/*@ months($row['NEW_FROM_MONTH']).'==> '.*/@months($row['NEW_FROM_MONTH']) ?></td>
            <td><?php

                if((12-$row['NEW_FROM_MONTH'])<($row['TO_MONTH']-$row['FROM_MONTH']))
                    echo /*months($row['TO_MONTH']).'==> '.*/@months((12-$row['NEW_FROM_MONTH'])+$row['NEW_FROM_MONTH']);
                else
                    echo /*months($row['TO_MONTH']).'==> '.*/@months($row['NEW_FROM_MONTH']+($row['TO_MONTH']-$row['FROM_MONTH']));

                ?></td>


            <td><?=@$row['PROJECT_NAME']?></td>








            <?php $count++ ?>
        </tr>
    <?php endforeach;?>
    <tr data-id='<?= 413;@$row['SER']?>'>
        <td class="hidden"><input type='checkbox' class='checkboxes' name="ischeck[]" value='<?=@$row['SER']?>' data-id='<?=@$row['SER']?>' data-check='<?=@$row['IS_CHECK']?>' ser_plan='<?=@$row['SER_PLAN']?>' ser_status_val='<?=@$row['STATUS']?>' ser_next_month_val='<?=@$row['NEW_FROM_MONTH']?>'></td>
        <td><?=@$count?>
            <input type="hidden" name="seq[]" value="<?=@$row['SEQ']?>" />
            <input type="hidden" name="ser_plan[]" value="<?=@$row['SER_PLAN']?>" />
            <input type="hidden" name="ser[]" value="<?=@$row['SER']?>" />
        </td>
        <td><?=$year_paln;?></td>
        <td><?='المقر الرئيسي'?></td>
        <td><?='19A01414'?></td>
        <td><?='الغاية الأولى (أ): تطوير شبكات الكهرباء وإدارة توزيع الطاقة بكفاء وفعالية.'?></td>
        <td><?='01 - تطوير وتعزيز شبكات الكهرباء ';?></td>
        <td><?='تنفيذ مشاريع تطوير وتخفيف أحمال';?></td>
        <td><?='مشروع غير فني'?></td>
        <td><?='مشروع'?></td>


        <td></td>
        <td><?= 'e'?></td>
        <td>3</td>
        <td>مجلس ادارة الشركة</td>
        <td>سكرتير تنفيذى</td>
        <td>0</td>

        <td>يناير</td>

        <td>يناير</td>












    </tr>
    <tr data-id='<?= 414;@$row['SER']?>'>
        <td class="hidden"><input type='checkbox' class='checkboxes' name="ischeck[]" value='<?=@$row['SER']?>' data-id='<?=@$row['SER']?>' data-check='<?=@$row['IS_CHECK']?>' ser_plan='<?=@$row['SER_PLAN']?>' ser_status_val='<?=@$row['STATUS']?>' ser_next_month_val='<?=@$row['NEW_FROM_MONTH']?>'></td>
        <td>2
            <input type="hidden" name="seq[]" value="<?=@$row['SEQ']?>" />
            <input type="hidden" name="ser_plan[]" value="<?=@$row['SER_PLAN']?>" />
            <input type="hidden" name="ser[]" value="<?=@$row['SER']?>" />
        </td>
        <td><?=$year_paln;?></td>
        <td><?='المقر الرئيسي'?></td>
        <td><?='19A01413'?></td>
        <td><?='الغاية الأولى (أ): تطوير شبكات الكهرباء وإدارة توزيع الطاقة بكفاء وفعالية.'?></td>
        <td><?='01 - تطوير وتعزيز شبكات الكهرباء ';?></td>
        <td><?='تنفيذ مشاريع تطوير وتخفيف أحمال';?></td>
        <td><?='مشروع غير فني'?></td>
        <td><?='مشروع'?></td>


        <td></td>
        <td><?= '3'?></td>
        <td>3</td>
      <td>سكرتير تنفيذى</td>
       <td>مقر غزة الصيانة</td>
        <td>0</td>

        <td>يناير</td>

        <td>يناير</td>












    </tr>
    <tr data-id='<?= 415;@$row['SER']?>'>
        <td class="hidden"><input type='checkbox' class='checkboxes' name="ischeck[]" value='<?=@$row['SER']?>' data-id='<?=@$row['SER']?>' data-check='<?=@$row['IS_CHECK']?>' ser_plan='<?=@$row['SER_PLAN']?>' ser_status_val='<?=@$row['STATUS']?>' ser_next_month_val='<?=@$row['NEW_FROM_MONTH']?>'></td>
        <td>3
            <input type="hidden" name="seq[]" value="<?=@$row['SEQ']?>" />
            <input type="hidden" name="ser_plan[]" value="<?=@$row['SER_PLAN']?>" />
            <input type="hidden" name="ser[]" value="<?=@$row['SER']?>" />
        </td>
        <td><?=$year_paln;?></td>
        <td><?='المقر الرئيسي'?></td>
        <td><?='19A01410'?></td>
        <td><?='الغاية الأولى (أ): تطوير شبكات الكهرباء وإدارة توزيع الطاقة بكفاء وفعالية.'?></td>
        <td><?='01 - تطوير وتعزيز شبكات الكهرباء ';?></td>
        <td><?='تنفيذ مشاريع تطوير وتخفيف أحمال';?></td>
        <td><?='مشروع غير فني'?></td>
        <td><?='مشروع'?></td>


        <td></td>
        <td><?= '4'?></td>
        <td>3</td>
       <td>ادارة الحاسوب ونظم المعلومات </td>
       <td>سكرتير تنفيذى</td>
        <td>0</td>

        <td>يناير</td>

        <td>يناير</td>












    </tr>
    <tr data-id='<?= 416;@$row['SER']?>'>
        <td class="hidden"><input type='checkbox' class='checkboxes' name="ischeck[]" value='<?=@$row['SER']?>' data-id='<?=@$row['SER']?>' data-check='<?=@$row['IS_CHECK']?>' ser_plan='<?=@$row['SER_PLAN']?>' ser_status_val='<?=@$row['STATUS']?>' ser_next_month_val='<?=@$row['NEW_FROM_MONTH']?>'></td>
        <td>4
            <input type="hidden" name="seq[]" value="<?=@$row['SEQ']?>" />
            <input type="hidden" name="ser_plan[]" value="<?=@$row['SER_PLAN']?>" />
            <input type="hidden" name="ser[]" value="<?=@$row['SER']?>" />
        </td>
        <td><?=$year_paln;?></td>
        <td><?='المقر الرئيسي'?></td>
        <td><?='19A01409'?></td>
        <td><?='الغاية الأولى (أ): تطوير شبكات الكهرباء وإدارة توزيع الطاقة بكفاء وفعالية.'?></td>
        <td><?='01 - تطوير وتعزيز شبكات الكهرباء ';?></td>
        <td><?='تنفيذ مشاريع تطوير وتخفيف أحمال';?></td>
        <td><?='مشروع غير فني'?></td>
        <td><?='مشروع'?></td>


        <td></td>
        <td><?= 'اسم النشاط '?></td>
        <td>3</td>
      <td>ادارة الحاسوب ونظم المعلومات </td>
       <td>مجلس ادارة الشركة</td>
        <td>0</td>

        <td>يناير</td>

        <td>يناير</td>












    </tr>

    </tbody>
    <tfoot>
    <tr>
        <th></th>



        <th></th>

        <th></th>
        <th></th>
        <th></th><th></th><th></th>
        <th></th><th></th><th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>

    </tr>
    <tr>
        <th>

        </th>
        <th></th>
        <th></th>
        <th></th><th></th><th></th>
        <th></th><th></th><th></th>
        <th></th><th></th><th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>


        <th></th>

    </tr>

    </tfoot>
</table>
</div>


