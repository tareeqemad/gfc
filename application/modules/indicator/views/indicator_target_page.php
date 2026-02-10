<style>
    .large.tooltip-inner {
        max-width: 350px;
        width: 350px;
    }


    /*.table-hover tbody tr:hover td

    {

        background-color: #fbc4c4;
        color:red;



    }*/


</style>
<?php
/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 24/07/18
 * Time: 01:33 م
 */
$MODULE_NAME= 'indicator';
$TB_NAME= 'indicator';
$edit_url =base_url("$MODULE_NAME/$TB_NAME/get");
$edit_without_cost_url=base_url("$MODULE_NAME/$TB_NAME/get_without_cost");
$count = 1;
$page=1;
/*function class_name($mode){

    if(!($mode % 2)){

        return 'case_4';
    }
    else{
        return 'case_3';
    }

}*/
function class_name($mode){

    if(!($mode % 2)){
        return '#DFFFDF';

    }
    else{
        return '#EEFDBF';
    }



}
?>
<table class="table table-hover" id="<?=$TB_NAME?>_tb" data-container="container">
    <thead>
    <tr>
        <th class="hidden" >#</th>
        <th>م</th>
         <th style="width:10%;">التصنيف الاول</th>
        <th style="width:10%;">التصنيف الثاني</th>


        <th style="width:10%;">اسم المؤشر</th>
        <th data-toggle="tooltip" class="hidden"  data-placement="top" title="يتم احتساب النسبة كالتلي : النسبة = ((المستهدف- المحقق لشهر الماضي) / المحقق لشهر الماضي)*100">النسبة%</th>
       <!--
        <th>المستهدف لمقر الشمال</th>
        <th>المستهدف لمقر غزة</th>
        <th>المستهدف لمقر الوسطى</th>
        <th>المستهدف لمقر خانيونس</th>
        <th>المستهدف لمقر رفح</th>
-->
        <th>
            <div class="row">
                <div class="col-sm-12 text-center">مقر الشمال</div>

            </div>

           <hr>
            <br>
            <div class="row">

                <div class="col-sm-4" data-toggle="tooltip" data-placement="top" title="يتم احتساب المستهدف كالتالي : المستهدف = المحقق لشهر الماضي +(المحقق لشهر الماضي * النسبة)">المستهدف لشهر الحالي</div>
                <div class="col-sm-4">المستهدف لشهر الماضي</div>
                <div class="col-sm-4">الحقيقي لشهر الماضي</div>
            </div>
        </th>
        <th>
            <div class="row">
                <div class="col-sm-12 text-center">مقر غزة</div>

            </div>

            <hr>
            <br>
            <div class="row">

                <div class="col-sm-4" data-toggle="tooltip" data-placement="top" title="يتم احتساب المستهدف كالتالي : المستهدف = المحقق لشهر الماضي +(المحقق لشهر الماضي * النسبة)">المستهدف لشهر الحالي</div>
                <div class="col-sm-4">المستهدف لشهر الماضي</div>
                <div class="col-sm-4">الحقيقي لشهر الماضي</div>
            </div>
        </th>
        <th>
            <div class="row">
                <div class="col-sm-12 text-center">مقر الوسطى</div>

            </div>

            <hr>
            <br>
            <div class="row">

                <div class="col-sm-4" data-toggle="tooltip" data-placement="top" title="يتم احتساب المستهدف كالتالي : المستهدف = المحقق لشهر الماضي +(المحقق لشهر الماضي * النسبة)">المستهدف لشهر الحالي</div>
                <div class="col-sm-4">المستهدف لشهر الماضي</div>
                <div class="col-sm-4">الحقيقي لشهر الماضي</div>
            </div>
        </th>
        <th>
            <div class="row">
                <div class="col-sm-12 text-center">مقر خانيونس</div>

            </div>

            <hr>
            <br>
            <div class="row">

                <div class="col-sm-4" data-toggle="tooltip" data-placement="top" title="يتم احتساب المستهدف كالتالي : المستهدف = المحقق لشهر الماضي +(المحقق لشهر الماضي * النسبة)">المستهدف لشهر الحالي</div>
                <div class="col-sm-4">المستهدف لشهر الماضي</div>
                <div class="col-sm-4">الحقيقي لشهر الماضي</div>
            </div>
        </th>
        <th>
            <div class="row">
                <div class="col-sm-12 text-center">مقر رفح</div>

            </div>

            <hr>
            <br>
            <div class="row">

                <div class="col-sm-4" data-toggle="tooltip" data-placement="top" title="يتم احتساب المستهدف كالتالي : المستهدف = المحقق لشهر الماضي +(المحقق لشهر الماضي * النسبة)">المستهدف لشهر الحالي</div>
                <div class="col-sm-4">المستهدف لشهر الماضي</div>
                <div class="col-sm-4">الحقيقي لشهر الماضي</div>
            </div>
        </th>

    </tr>
    </thead>

    <tbody>
    <?php foreach($page_rows as $row) :

        ?>
        <!-- <tr class="<?=class_name($row['CLASS_'])?>"> -->
        <tr style="background-color:<?=class_name($row['CLASS'])?>">
            <td><?=$count?></td>
            <td style="width:10%;"><?=@$row['CLASS_NAME']?></td>
			<td style="width:10%;"><?=@$row['SECOND_CLASS_NAME']?></td>

            <td  style="width:15%;">

                <div class="row"><div class="col-md-11"> <?=@$row['INDECATOR_NAME']?><input type="hidden" name="indecator_ser[]" value="<?=@$row['INDECATOR_SER']?>" /></div><div class="col-md-1">
                        <a href="#"  data-toggle="tooltip" data-placement="top" title="<?=@$row['NOTE']?>"><i class="icon icon-question-circle"></i></a>
                    </div></div>

               </td>
            <td class="hidden" ><input class="form-control" name="txt_t_persant[]" value="<?=@$row['T_PERSENT']?>" id="txt_t_persant_<?=$count?>" data-val="false"  /></td>
            <td>
                <div class="row">
                    <div class="col-sm-4">
                        <input class="form-control" name="txt_north[]" value="<?=@$row['NORTH']?>" id="txt_north_<?=$count?>" data-val="false" data-val-required="required" />
                        <input type="hidden" name="north_seq[]" id="north_seq_id_<?= $count ?>" value="<?=@$row['NORTH_SEQ']?>" data-check='<?=@$row['NORTH_SEQ']?>' /></div>
                    <div class="col-sm-4">
                        <input class="form-control" name="prev_north[]" value="<?=@$row['PREV_NORTH']?>" readonly  />
                    </div>
                    <div class="col-sm-4">
                        <input class="form-control" name="prev_value_north[]" value="<?=@$row['PREV_VALUE_NORTH']?>" readonly  />
                    </div>
                </div>

            </td>
            <td>
                <div class="col-sm-4">
                    <input class="form-control" name="txt_gaza[]" value="<?=@$row['GAZA']?>" id="txt_gaza_<?=$count?>" data-val="false" data-val-required="required" />
                    <input type="hidden" name="gaza_seq[]" id="gaza_seq_id_<?= $count ?>" value="<?=@$row['GAZA_SEQ']?>" data-check='<?=@$row['GAZA_SEQ']?>'/></div>
                <div class="col-sm-4">
                    <input class="form-control" name="prev_gaza[]" value="<?=@$row['PREV_GAZA']?>" readonly  />
                </div>
                <div class="col-sm-4">
                    <input class="form-control" name="prev_value_gaza[]" value="<?=@$row['PREV_VALUE_GAZA']?>" readonly  />
                </div>
            </td>
            <td>
                <div class="col-sm-4">
                    <input class="form-control" name="txt_middle[]" value="<?=@$row['MIDDLE']?>" id="txt_middle_<?=$count?>" data-val="false" data-val-required="required" />
                    <input type="hidden" name="middle_seq[]" id="middle_seq_id_<?= $count ?>" value="<?=@$row['MIDDLE_SEQ']?>" data-check='<?=@$row['MIDDLE_SEQ']?>' /></div>
                <div class="col-sm-4">
                    <input class="form-control" name="prev_middle[]" value="<?=@$row['PREV_MIDDLE']?>" readonly  />
                </div>
                <div class="col-sm-4">
                    <input class="form-control" name="prev_value_middle[]" value="<?=@$row['PREV_VALUE_MIDDLE']?>" readonly  />
                </div>
            </td>
            <td>
                <div class="col-sm-4">
                    <input class="form-control" name="txt_khan[]" value="<?=@$row['KHAN']?>" id="txt_khan_<?=$count?>" data-val="false" data-val-required="required" />
                    <input type="hidden" name="khan_seq[]" id="khan_seq_id_<?= $count ?>" value="<?=@$row['KHAN_SEQ']?>" data-check='<?=@$row['KHAN_SEQ']?>' /></div>
                <div class="col-sm-4">
                    <input class="form-control" name="prev_khan[]" value="<?=@$row['PREV_KHAN']?>" readonly  />
                </div>
                <div class="col-sm-4">
                    <input class="form-control" name="prev_value_khan[]" value="<?=@$row['PREV_VALUE_KHAN']?>" readonly  />
                </div>
            </td>
            <td>
                <div class="col-sm-4">
                    <input class="form-control" name="txt_rafa[]" value="<?=@$row['RAFA']?>" id="txt_rafa_<?=$count?>" data-val="false" data-val-required="required" />
                    <input type="hidden" name="rafa_seq[]" id="rafa_seq_id_<?= $count ?>" value="<?=@$row['RAFA_SEQ']?>" data-check='<?=@$row['RAFA_SEQ']?>' /></div>
                <div class="col-sm-4">
                    <input class="form-control" name="prev_rafa[]" value="<?=@$row['PREV_RAFA']?>" readonly  />
                </div>
                <div class="col-sm-4">
                    <input class="form-control" name="prev_value_rafa[]" value="<?=@$row['PREV_VALUE_RAFA']?>" readonly  />
                </div>
            </td>










            <?php $count++ ?>
        </tr>
    <?php endforeach;?>

    </tbody>
</table>
</div>
<script type="text/javascript">
    $(function () {
        // $('#element').tooltip('show')
        $('[data-toggle="tooltip"]').tooltip({
            template: '<div class="tooltip" role="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner large"></div></div>'
        });
    })
   /* $(document).ready(function() {

        reBind();
        $('input[name="txt_t_persant[]"]').change();
        function reBind() {
            $('input[name="txt_t_persant[]"]').change(function () {
                if($(this).val()>0)
                {
                $(this).closest('tr').find('input[name="txt_north[]"]').prop('readonly',true);
                $(this).closest('tr').find('input[name="txt_gaza[]"]').prop('readonly',true);
                $(this).closest('tr').find('input[name="txt_middle[]"]').prop('readonly',true);
                $(this).closest('tr').find('input[name="txt_khan[]"]').prop('readonly',true);
                $(this).closest('tr').find('input[name="txt_rafa[]"]').prop('readonly',true);
                var prev_value_north = $(this).closest('tr').find('input[name="prev_value_north[]"]').val();
                var prev_value_gaza = $(this).closest('tr').find('input[name="prev_value_gaza[]"]').val();
                var prev_value_middle = $(this).closest('tr').find('input[name="prev_value_middle[]"]').val();
                var prev_value_khan = $(this).closest('tr').find('input[name="prev_value_khan[]"]').val();
                var prev_value_rafa = $(this).closest('tr').find('input[name="prev_value_rafa[]"]').val();
                $(this).closest('tr').find('input[name="txt_north[]"]').val((Number(prev_value_north)+(Number(prev_value_north)*(Number($(this).val())/100))).toFixed(2));
                $(this).closest('tr').find('input[name="txt_gaza[]"]').val((Number(prev_value_gaza)+(Number(prev_value_gaza)*(Number($(this).val())/100))).toFixed(2));
                $(this).closest('tr').find('input[name="txt_middle[]"]').val((Number(prev_value_middle)+(Number(prev_value_middle)*(Number($(this).val())/100))).toFixed(2));
                $(this).closest('tr').find('input[name="txt_khan[]"]').val((Number(prev_value_khan)+(Number(prev_value_khan)*(Number($(this).val())/100))).toFixed(2));
                $(this).closest('tr').find('input[name="txt_rafa[]"]').val((Number(prev_value_rafa)+(Number(prev_value_rafa)*(Number($(this).val())/100))).toFixed(2));


                }
                else if($(this).val() ==0 || $(this).val() == '')
                {

                    $(this).closest('tr').find('input[name="txt_north[]"]').prop('readonly',false);
                    $(this).closest('tr').find('input[name="txt_gaza[]"]').prop('readonly',false);
                    $(this).closest('tr').find('input[name="txt_middle[]"]').prop('readonly',false);
                    $(this).closest('tr').find('input[name="txt_khan[]"]').prop('readonly',false);
                    $(this).closest('tr').find('input[name="txt_rafa[]"]').prop('readonly',false);
                    $(this).closest('tr').find('input[name="txt_north[]"]').val(0);
                    $(this).closest('tr').find('input[name="txt_gaza[]"]').val(0);
                    $(this).closest('tr').find('input[name="txt_middle[]"]').val(0);
                    $(this).closest('tr').find('input[name="txt_khan[]"]').val(0);
                    $(this).closest('tr').find('input[name="txt_rafa[]"]').val(0);
                }
                else
                {
                    danger_msg('ادخال خاطئ لنسبة/او ان النسبة سالبة', '');
                    $(this).val(0);
                }




            });

            $('input[name="txt_north[]"]').change(function () {
                var prev_value_north = $(this).closest('tr').find('input[name="prev_value_north[]"]').val();
                $(this).closest('tr').find('input[name="txt_t_persant[]"]').val((((Number($(this).val())-Number(prev_value_north))/Number(prev_value_north))*100).toFixed(2));
                $('input[name="txt_t_persant[]"]').change();
            });

            $('input[name="txt_gaza[]"]').change(function () {
                var prev_value_gaza = $(this).closest('tr').find('input[name="prev_value_gaza[]"]').val();
                $(this).closest('tr').find('input[name="txt_t_persant[]"]').val((((Number($(this).val())-Number(prev_value_gaza))/Number(prev_value_gaza))*100).toFixed(2));
                $('input[name="txt_t_persant[]"]').change();
            });


            $('input[name="txt_middle[]"]').change(function () {
                var prev_value_middle = $(this).closest('tr').find('input[name="prev_value_middle[]"]').val();
                $(this).closest('tr').find('input[name="txt_t_persant[]"]').val((((Number($(this).val())-Number(prev_value_middle))/Number(prev_value_middle))*100).toFixed(2));
                $('input[name="txt_t_persant[]"]').change();
            });


            $('input[name="txt_khan[]"]').change(function () {
                var prev_value_khan = $(this).closest('tr').find('input[name="prev_value_khan[]"]').val();
                $(this).closest('tr').find('input[name="txt_t_persant[]"]').val((((Number($(this).val())-Number(prev_value_khan))/Number(prev_value_khan))*100).toFixed(2));
                $('input[name="txt_t_persant[]"]').change();
            });

            $('input[name="txt_rafa[]"]').change(function () {
                var prev_value_rafa = $(this).closest('tr').find('input[name="prev_value_rafa[]"]').val();
                $(this).closest('tr').find('input[name="txt_t_persant[]"]').val((((Number($(this).val())-Number(prev_value_rafa))/Number(prev_value_rafa))*100).toFixed(2));
                $('input[name="txt_t_persant[]"]').change();
            });
        }



    });
*/

</script>



