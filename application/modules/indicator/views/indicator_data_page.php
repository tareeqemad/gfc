
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
 * Date: 31/10/19
 * Time: 08:35 ص
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
if($branch==1)
    $branch_name= 'مقر الرئيسي';
else if($branch==2)
    $branch_name= 'مقر غزة';
else if($branch==3)
    $branch_name= 'مقر الشمال';
else if($branch==4)
    $branch_name= 'مقر الوسطى';
else if($branch==6)
    $branch_name= 'مقر خانيونس';
else if($branch==7)
    $branch_name= 'مقر رفح';
?>
<table class="table table-hover" id="<?=$TB_NAME?>_tb" data-container="container">
    <thead>
    <tr><th colspan="7"><?=$branch_name?></th></tr>
    <tr>
        <th class="hidden" >#</th>
        <th style="width:5%;">م</th>
        <th >التصنيف الاول</th>
        <th >التصنيف الثاني</th>
        <th>اسم المعلومة/المؤشر</th>
        <th >الوحدة</th>
        <th >الصيغة</th>


          <th>



              <div class="col-sm-12" >المحقق</div>


        </th>





    </tr>
    </thead>

    <tbody>
    <?php foreach($page_rows as $row) :

        ?>
        <!-- <tr class="<?=class_name($row['CLASS_'])?>"> -->
        <tr style="background-color:<?=class_name($row['CLASS'])?>">
            <td style="width:5%;"><?=$count?></td>
            <td style="width:10%;"><?=@$row['CLASS_NAME']?></td>
            <td style="width:10%;"><?=@$row['SECOND_CLASS_NAME']?></td>

            <td  style="width:15%;">

                <div class="row"><div class="col-md-11"> <?=@$row['INDECATOR_NAME']?><input type="hidden" name="indecator_ser[]" value="<?=@$row['INDECATOR_SER']?>" /></div><div class="col-md-1">
                        <a href="#"  data-toggle="tooltip" data-placement="top" title="<?=$row['NOTE']?>"><i class="icon icon-question-circle"></i></a>
                    </div></div>

            </td>
            <td><?=$row['UNIT_NAME']?></td>
            <td><?=@$row['SCALE_NAME']?></td>



            <td>

                          <input class="form-control" name="txt_value_branch[]" value="<?=@$row['VALUE_BRANCH']?>" id="txt_value_branch_<?=$count?>" data-val="false" data-val-required="required" />
                          <input type="hidden" name="value_branch_seq[]" id="value_branch_seq_id_<?= $count ?>" value="<?=@$row['VALUE_BRANCH_SEQ']?>" data-check='<?=@$row['VALUE_BRANCH_SEQ']?>' /></div>


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



