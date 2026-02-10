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
        <th class="hidden" >#</th>
        <th>م</th>
        <th>العام</th>

        <th>الغاية</th>
        <th>الهدف الاستراتيجي</th>
        <th>الهدف التشغيلي</th>
        <th>التصنيف</th>
        <th>نوع النشاط</th>

        <th>اسم المشروع</th>
        <th>التكلفة</th>

        <th>مدة التنفيذ</th>

        <th>نهاية المشروع</th>
        <th>بداية المشروع</th>
        <th>الوزن النسبي%</th>

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


            <td style="width: 5%">
                <input class="form-control" name="persant[]" value="<?=@$row['PERSANT'];?>" id="txt_persant_<?=$count?>" data-val="false" data-val-required="required" />
            </td>








            <?php $count++ ?>
        </tr>
    <?php endforeach;?>

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
        <th>
            الاجمالي الكلي لاوزان
        </th>
        <th id="total_part_branches"></th>
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

    </tr>

    </tfoot>
</table>
</div>


<script type="text/javascript">
    $(document).ready(function() {
        reBind();
        calcall();

        $('input[name="persant[]"]').change(function () {
            // alert();
            calcall();
        });

        $('input[name="persant[]"]').keyup(function () {
            //alert();
            calcall();
        });


        function calcall() {

            var total_part_branches = 0;

            $('input[name="persant[]"]').each(function () {

                var part = $(this).closest('tr').find('input[name="persant[]"]').val();
                total_part_branches += Number(part);
                if(Number(total_part_branches)>100)
                {
                    danger_msg('لقد تجاوزت الحد المسموح');
                    $(this).closest('tr').find('input[name="persant[]"]').val(0);
                }
                else
                $('#total_part_branches').text(isNaNVal(Number(total_part_branches)));
            });



        }

        function reBind(){


            $('select[name="status[]"]').select2().on('change',function(){

                //  checkBoxChanged();
                if($(this).val()==3)
                {
                    $(this).closest('tr').find('select[name="is_end[]"]').select2('val',2);
                    $(this).closest('tr').find('input[name="persant[]"]').val(100);
                    //$(this).closest('tr').find('input[name="notes[]"]').prop("disabled",true);

                }
                else if($(this).val()==1)
                {
                    $(this).closest('tr').find('select[name="is_end[]"]').select2('val',1);
                    $(this).closest('tr').find('input[name="persant[]"]').val(0);
                    //$(this).closest('tr').find('input[name="notes[]"]').prop("disabled",true);

                }
                else if($(this).val()==4)
                {
                    $(this).closest('tr').find('select[name="is_end[]"]').select2('val',2);
                    //$(this).closest('tr').find('input[name="persant[]"]').val(0);
                    //$(this).closest('tr').find('input[name="notes[]"]').prop("disabled",true);

                }
                else
                {
                    $(this).closest('tr').find('select[name="is_end[]"]').select2('val',1);
                    $(this).closest('tr').find('input[name="persant[]"]').val('');
                    $(this).closest('tr').find('input[name="notes[]"]').prop("disabled",false);
                }

            });

            $('select[name="is_end[]"]').select2().on('change',function(){

                //  checkBoxChanged();

                if( $(this).closest('tr').find('select[name="status[]"]').val()==3)
                {
                    danger_msg('اجراء خاطئ الخطة لابد ان تكون منتهية لانها منجزة!!');
                    $(this).select2('val',2);
                }
                else  if( $(this).closest('tr').find('select[name="status[]"]').val()==4)
                {
                    danger_msg('اجراء خاطئ الخطة لابد ان تكون منتهية لانها مكررة!!');
                    $(this).select2('val',2);
                }
                else if( $(this).closest('tr').find('select[name="status[]"]').val()==1)
                {
                    if( $(this).val()==2)
                    {
                        danger_msg('اجراء خاطئ الخطة لابد ان تكون غير منتهية!!');
                        $(this).select2('val',1);
                    }
                }

            });

            $('input[name="persant[]"]').change(function () {
                if( $(this).closest('tr').find('select[name="status[]"]').val()==1)
                {
                    danger_msg('اجراء خاطئ الخطة لابد ان تكون نسبة الانجاز 0% لانها غير منفذ!!');
                    $(this).val(0);
                    // $(this).closest('tr').find('input[name="notes[]"]').prop("disabled",true);
                }
                else if( $(this).closest('tr').find('select[name="status[]"]').val()==3)
                {
                    danger_msg('اجراء خاطئ الخطة لابد ان تكون نسبة الانجاز 100% لانها منجزة!!');
                    $(this).val(100);
                    // $(this).closest('tr').find('input[name="notes[]"]').prop("disabled",true);
                }
                else if( $(this).closest('tr').find('select[name="status[]"]').val()==2)
                {
                    // $(this).val('');
                    $(this).closest('tr').find('input[name="notes[]"]').prop("disabled",false);
                    if($(this).val()==0  || $(this).val()>=100)
                    {
                        danger_msg('اجراء خاطئ الخطة غير منجزة!!');
                        $(this).val('');
                    }
                }

                else
                {
                    $(this).closest('tr').find('input[name="notes[]"]').prop("disabled",false);
                    if($(this).val()<0  || $(this).val()>100)
                    {
                        danger_msg('اجراء خاطئ الخطة يجب النسبة الا تتجاوز 0-100!!');
                        $(this).val('');
                    }
                }

            });


            $('input[name="persant[]"]').keyup(function () {
                if( $(this).closest('tr').find('select[name="status[]"]').val()==1)
                {
                    danger_msg('اجراء خاطئ الخطة لابد ان تكون نسبة الانجاز 0% لانها غير منفذ!!');
                    $(this).val(0);
                    // $(this).closest('tr').find('input[name="notes[]"]').prop("disabled",true);
                }
                else  if( $(this).closest('tr').find('select[name="status[]"]').val()==3)
                {
                    danger_msg('اجراء خاطئ الخطة لابد ان تكون نسبة الانجاز 100% لانها منجزة!!');
                    $(this).val(100);
                    // $(this).closest('tr').find('input[name="notes[]"]').prop("disabled",true);
                }
                else if( $(this).closest('tr').find('select[name="status[]"]').val()==2)
                {
                    $(this).closest('tr').find('input[name="notes[]"]').prop("disabled",false);
                    if($(this).val()==0  || $(this).val()>=100)
                    {
                        danger_msg('اجراء خاطئ الخطة يجب النسبة الا تتجاوز 0-100!!');
                        $(this).val('');
                    }
                }
                else
                {
                    $(this).closest('tr').find('input[name="notes[]"]').prop("disabled",false);
                    if($(this).val()<0  || $(this).val()>100)
                    {
                        danger_msg('اجراء خاطئ الخطة غير منجزة!!');
                        $(this).val('');
                    }
                }


            });
        }



    });


</script>

