<style>
    .large.tooltip-inner {
        max-width: 350px;
        width: 350px;
    }

input[type=text] {
  font-size:16px;
}

</style>
<?php
/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 27/02/19
 * Time: 11:21 ص
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

$branch_name='جميع المقرات';




$count_weight=0;
$count_t_wegith=0;
$PREV_SUM_INDECATOR=0;
$differ=0;
//$count_t_wegith=0;
?>
<table class="table table-hover" id="<?=$TB_NAME?>_tb" data-container="container">
<thead>
<tr>
    <th class="hidden" >#</th>
    <th>م</th>
    <th style="width:15%;">القطاع</th>
    <th style="width:15%;">التصنيف</th>
    <th style="width:18%;">اسم المؤشر</th>
    <th hidden>الوزن</th>
	<th>الوحدة</th>
	<th>النسبة المقبولة%</th>


    <th>
        <div class="row">
            <div class="col-md-12 text-center" id="show_branch"><?=$branch_name;?></div>

        </div>

        <hr>
        <br>
        <div class="row">

            <div class="col-md-3" data-toggle="tooltip" data-placement="top" title="">مستهدف</div>
            <div class="col-md-3">محقق</div>
			<!-- <div class="col-md-2"> -->
            <div class="col-md-3 hidden">الفرق</div>
            <div class="col-md-2">نسبة الانجاز%</div>
			<div class="col-md-3">نسبة الانحراف%</div>
            <div class="col-md-1">الحالة</div>
            <!-- <div class="col-md-1">#</div> -->
            <div class="col-md-1" hidden>الوزن المحقق</div>
           <!-- <div class="col-md-1">                حالة اعتماد المستهدف
            </div>
           -->
        </div>
    </th>


</tr>
</thead>

<tbody>
<?php foreach($page_rows as $row) :
?>


        <tr style="background-color:<?=class_name($row['SECTOR'])?>">


    <td><?=$count?></td>
    <td><?=@$row['SECTOR_NAME']?></td>
    <td><?=@$row['CLASS_NAME']?></td>


    <td><div class="row"><div class="col-md-11"><?=@$row['INDECATOR_NAME']?><input type="hidden" name="indecator_ser[]" value="<?=@$row['INDECATOR_SER']?>" /></div><div class="col-md-1">
                <a href="#"  data-toggle="tooltip" data-placement="top" title="<?=@$row['NOTE']?>"><i class="icon icon-question-circle"></i></a>
            </div></div></td>
    <td dir="ltr" hidden><?php
        if(@$row['WEIGHT']==0)
            echo @$row['WEIGHT'];
        else if(@$row['WEIGHT']<1)
            echo '0'.@$row['WEIGHT'];
        else
            echo @$row['WEIGHT'];

        ?><input type="hidden" name="weight[]" value="<?=@$row['WEIGHT']?>" />
        <?php $count_weight=$count_weight+@$row['WEIGHT']; ?>
    </td>
	<td dir="ltr"><?php
                 
                     echo @$row['UNIT_NAME'];
                 
		
		?>
		
            </td>
	 <td dir="ltr"><?php
                 
                     echo 100-@$row['EFFECT_VALUE'].'%';
                 
		
		?>
		
            </td>

    <td>



    <div class="row">
        <div class="col-md-3">
            <?php
            $target_north=@$row['NORTH'];
            $target_gaza=@$row['GAZA'];
            $target_middle=@$row['MIDDLE'];
            $target_khan=@$row['KHAN'];
            $target_rafa=@$row['RAFA'];

            $target_gedco_all=$target_north+$target_gaza+$target_middle+$target_khan+$target_rafa;



            if($target_gedco_all==0)
                $target_gedco_all = 'N/A';
				
				
				$value_north=@$row['VALUE_NORTH'];
            $value_gaza=@$row['VALUE_GAZA'];
            $value_middle=@$row['VALUE_MIDDLE'];
            $value_khan=@$row['VALUE_KHAN'];
            $value_rafa=@$row['VALUE_RAFA'];

            $value_gedco_all=$value_north+$value_gaza+$value_middle+$value_khan+$value_rafa;



           /* if($value_gedco_all==0)
                $value_gedco_all = 'N/A';*/


            ?>
            <input type="text" class="form-control" name="t_value[]" value="<?=@$target_gedco_all;?>" id="txt_north_<?=$count?>" data-val="false" data-val-required="required" />
            </div>
        <div class="col-md-3">
            <input type="text" class="form-control" name="value[]" value="<?=@$value_gedco_all;?>" readonly  />
        </div>
		<!-- <div class="col-md-2"> -->
        <div class="col-md-3 hidden">
            <?php
          
                if(@$row['SCALE']==2)
                {
                    $differ=(@$value_gedco_all-@$target_gedco_all)/100;
                    ?>
                    <input type="text" class="form-control" name="T_DIFFER[]" value="<?=round($differ);?>" readonly  />
                <?php
                }
                else
                {
                    $differ=(@$value_gedco_all-@$target_gedco_all);
                    ?>
                    <input type="text" class="form-control" name="T_DIFFER[]" value="<?=round($differ);?>" readonly  />
                <?php
                }
            
          
            ?>
        </div>

        <div class="col-md-2">
            <input type="text" class="form-control" name="persent_north[]" value="<?php
            if($row['SCALE'] <> 2)
             {
            if (@$target_gedco_all==0)
			{
                echo 'N/A';
				$deviation= 'N/A';
			}	
            else
            {
   
                //$x=floatval  (($row['VALUE_DATA']-$target)/($target));
                // echo round ($x).'%';
				$T_PERSENT=round(((@$value_gedco_all/@$target_gedco_all)*100));
				$deviation=(100-@$row['EFFECT_VALUE'])-$T_PERSENT;
                echo $T_PERSENT.'%';
            }

            }
              else if($row['SCALE'] == 2)
			  {
                $T_PERSENT=@value_gedco_all;
				$deviation=(100-@$row['EFFECT_VALUE'])-$T_PERSENT;
                echo $T_PERSENT.'%';
			  }
            ?>" readonly  />
        </div>
		<div class="col-md-3">
				
				
                    <input type="text" dir="rtl" class="form-control" name="deviation[]" value="<?=$deviation;?>" readonly  />
                </div>
        <?php
        //  echo @$row['T_PERSANT']-100;
        if($row['EFFECT_FLAG']==1)
        {
        //if($differ<($row['EFFECT_VALUE']/100))
        if((@$T_PERSENT-100)>($row['EFFECT_VALUE']*-1))
        {
        ?>
        <div class="col-md-1" style="background-color: lightgrey; border: 8px solid green;">

            <?php
            }
            else   if((@$T_PERSENT-100)<($row['EFFECT_VALUE']*-1))//if($differ>($row['EFFECT_VALUE']/100))
            {
            ?>
            <div class="col-md-1" style="background-color: lightgrey; border: 8px solid red;">

                <?php
                }
                else   if((@$T_PERSENT-100)==($row['EFFECT_VALUE']*-1))//if($differ == ($row['EFFECT_VALUE']/100))

                {
                ?>
				<!--
                <div class="col-md-1" style="background-color: lightgrey; border: 8px solid #ffff00;">
				-->
				<div class="col-md-1" style="background-color: lightgrey; border: 8px solid green;">

                    <?php
                    }
                    }
                    else if($row['EFFECT_FLAG']==2)
                    {
                    if((@$T_PERSENT-100)<($row['EFFECT_VALUE']*-1))  //if($differ>($row['EFFECT_VALUE']/100))
                    {
                    ?>
                    <div class="col-md-1" style="background-color: lightgrey; border: 8px solid green;">

                        <?php
                        }
                        else  if((@$T_PERSENT-100)>($row['EFFECT_VALUE']*-1))//if($differ<($row['EFFECT_VALUE']/100))
                        {
                        ?>
                        <div class="col-md-1" style="background-color: lightgrey; border: 8px solid red;">

                            <?php
                            }
                            else  if((@$T_PERSENT-100)==($row['EFFECT_VALUE']*-1))//if($differ == ($row['EFFECT_VALUE']/100))
                            {
                            ?>
							<!--
                            <div class="col-md-1" style="background-color: lightgrey; border: 8px solid #ffff00;">
-->
<div class="col-md-1" style="background-color: lightgrey; border: 8px solid green;">
                                <?php
                                }
                                }
                                if($row['EFFECT_FLAG']==3)
                                {
                                ?>
                                <div class="col-md-1" style="background-color: lightgrey; border: 8px solid #0095cc;">
                                    <?php

                                    }
                                    ?>
                                    <?php
            $PREV_NORTH=@$row['PREV_VALUE_NORTH'];
            $PREV_GAZA=@$row['PREV_VALUE_GAZA'];
            $PREV_MIDDLE=@$row['PREV_VALUE_MIDDLE'];
            $PREV_KHAN=@$row['PREV_VALUE_KHAN'];
            $PREV_RAFA=@$row['PREV_VALUE_RAFA'];

            $prev_value_gedco_all=$PREV_NORTH+$PREV_GAZA+$PREV_MIDDLE+$PREV_KHAN+$PREV_RAFA;

                                    if (intval(@$prev_value_gedco_all) > intval(@$value_gedco_all)) {

                                        echo ' <span> <i class="icon icon-arrow-down" style="color:red"></i></span>';
                                    }
                                    elseif (@$prev_value_gedco_all < @$value_gedco_all) {
                                        echo  ' <span> <i class="icon icon-arrow-up" style="color:green"></i></span>';
                                    }
                                    elseif (@$prev_value_gedco_all == @$value_gedco_all) {
                                        echo ' <span> <i class="icon icon-arrow-right" style="color:#0095cc"></i></span>';
                                    }
                                    ?>
                                </div>
                                <!-- <div class="col-md-1">
                                    <input class="form-control" name="t_order[]" value="<?=@$row['T_ORDER'];?>" readonly  />


                                </div>  -->
                                <div class="col-md-1 hidden">
                                    <?php 
									if($T_PERSENT>100)
									$T_PERSENT=100;
                                       @$T_WEGITH=round((@$T_PERSENT/100)*@$row['WEIGHT'],2);

                                     ?>
                                    <input class="form-control" name="t_wegith[]" value="<?=@$T_WEGITH;?>" dir="rtl" readonly  />
                                    <?php $count_t_wegith=$count_t_wegith+@$T_WEGITH; ?>
                                </div>
                                <!-- <div class="col-md-1">

                                    <input class="form-control" name="t_adopt[]" value="<?=@$row['T_ADOPT_NAME'];?>" readonly  />

                                </div> -->


    </td>











    <?php $count++ ?>
    </tr>
<?php endforeach;?>

</tbody>

</table>
<br>
<table class="table hidden" style="width: 50%" >


    <tbody>


    <tr>
        <th> الإجمالي الكلي لاوزان المقرات </th>

        <th id="n_amount_total"><?=$count_t_wegith;?></th>





    </tr>
    <tr>
        <th> الإجمالي الكلي لاوزان المؤشرات</th>


        <th id="total_weight_branches"><?=$count_weight;?></th>



    </tr>
    <tr>
        <th>الإجمالي الكلي</th>
        <?php
        if($count_weight==0)
            $total='';
        else
            $total=round(($count_t_wegith/$count_weight)*100);
/*

        if (@$prev_sum_indecator > $total) {

            $sign= ' <span> <i class="icon icon-arrow-down" style="color:red"></i></span>';
        }
        elseif (@$prev_sum_indecator < $total) {
            $sign=' <span> <i class="icon icon-arrow-up" style="color:green"></i></span>';
        }
        elseif (@$prev_sum_indecator == $total) {
            $sign= ' <span> <i class="icon icon-arrow-right" style="color:#0095cc"></i></span>';
        }
		*/
        ?>
        <th id="result_amount_total"><span id="span_result_amount_total"><?=$total.' % '.@$sign;?></span></th>




    </tr>


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
    /* reBind_pram(0);
     function reBind_pram(cnt){

     $('input[name="weight[]"]').each(function () {
     var total_weight_branches=0;
     var weight = $(this).closest('tr').find('input[name="weight[]"]').val();
     total_weight_branches += Number(weight);
     $('#total_weight_branches').text(isNaNVal(Number(total_weight_branches)));
     });

     }
     */
</script>



