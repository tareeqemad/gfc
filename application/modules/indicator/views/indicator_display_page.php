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
 * Date: 17/09/18
 * Time: 09:40 ص
 */

$MODULE_NAME= 'indicator';
$TB_NAME= 'indicator';
$edit_url =base_url("$MODULE_NAME/$TB_NAME/get");
$edit_without_cost_url=base_url("$MODULE_NAME/$TB_NAME/get_without_cost");
$count = 1;
$page=1;
function class_name($mode){

    if(!($mode % 2)){
        return '#DFFFDF';

    }
    else{
        return '#EEFDBF';
    }



}




@$count_weight=0;
@$count_t_wegith=0;
@$count_t_wegith_north=0;
@$count_t_wegith_gaza=0;
@$count_t_wegith_middle=0;
@$count_t_wegith_khan=0;
@$count_t_wegith_rafah=0;
$target_north=0;

?>
<table class="table table-hover" id="<?=$TB_NAME?>_tb" data-container="container">
    <thead>
    <tr>
        <th class="hidden" >#</th>
        <th>م</th>
        <th style="width:8%;">القطاع</th>
        <th style="width:10%;">التصنيف الاول</th>
        <th style="width:10%;">التصنيف الثاني</th>
        <th style="width:13%;">اسم المؤشر</th>
        <th hidden>الوزن</th>
		<th>الوحدة</th>
		<th>النسبة المقبولة%</th>


        <th>
            <div class="row">
                <div class="col-md-12 text-center">مقر الشمال</div>

            </div>

            <hr>
            <br>
            <div class="row">
                <div class="col-md-4" data-toggle="tooltip" data-placement="top" title="">%</div>
                <div class="col-md-4" title="مقارنة بالشهر الماضي">الحالة</div>
                <div class="col-md-4" title="مقارنة بالشهر الماضي">#</div>
                <!--
                <div class="col-md-3" data-toggle="tooltip" data-placement="top" title="">مستهدف</div>
                <div class="col-md-3">محقق</div>
                <div class="col-md-3">الفرق</div>
                <div class="col-md-3">%</div>
                -->

            </div>
        </th>
        <th>
            <div class="row">
                <div class="col-md-12 text-center">مقر غزة</div>

            </div>

            <hr>
            <br>
            <div class="row">
                <div class="col-md-4" data-toggle="tooltip" data-placement="top" title="">%</div>
                <div class="col-md-4" title="مقارنة بالشهر الماضي">الحالة</div>
                <div class="col-md-4" title="مقارنة بالشهر الماضي">#</div>
                <!--
                <div class="col-md-3" data-toggle="tooltip" data-placement="top" title="">مستهدف</div>
                <div class="col-md-3">محقق</div>
                <div class="col-md-3">الفرق</div>
                <div class="col-md-3">%</div>
                -->

            </div>
        </th>
        <th>
            <div class="row">
                <div class="col-md-12 text-center">مقر الوسطى</div>

            </div>

            <hr>
            <br>
            <div class="row">
                <div class="col-md-4" data-toggle="tooltip" data-placement="top" title="">%</div>
                <div class="col-md-4" title="مقارنة بالشهر الماضي">الحالة</div>
                <div class="col-md-4" title="مقارنة بالشهر الماضي">#</div>
                <!--
                <div class="col-md-3" data-toggle="tooltip" data-placement="top" title="">مستهدف</div>
                <div class="col-md-3">محقق</div>
                <div class="col-md-3">الفرق</div>
                <div class="col-md-3">%</div>
                -->

            </div>
        </th>
        <th>
            <div class="row">
                <div class="col-md-12 text-center">مقر خانيونس</div>

            </div>

            <hr>
            <br>
            <div class="row">
                <div class="col-md-4" data-toggle="tooltip" data-placement="top" title="">%</div>
                <div class="col-md-4" title="مقارنة بالشهر الماضي">الحالة</div>
                <div class="col-md-4" title="مقارنة بالشهر الماضي">#</div>

                <!--
               <div class="col-md-3" data-toggle="tooltip" data-placement="top" title="">مستهدف</div>
               <div class="col-md-3">محقق</div>
               <div class="col-md-3">الفرق</div>
               <div class="col-md-3">%</div>
               -->

            </div>
        </th>
        <th>
            <div class="row">
                <div class="col-md-12 text-center">مقر رفح</div>

            </div>

            <hr>
            <br>
            <div class="row">
                <div class="col-md-4" data-toggle="tooltip" data-placement="top" title="">%</div>
                <div class="col-md-4" title="مقارنة بالشهر الماضي">الحالة</div>
                <div class="col-md-4" title="مقارنة بالشهر الماضي">#</div>
                <!--
               <div class="col-md-3" data-toggle="tooltip" data-placement="top" title="">مستهدف</div>
               <div class="col-md-3">محقق</div>
               <div class="col-md-3">الفرق</div>
               <div class="col-md-3">%</div>
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
			<td><?=@$row['SECOND_CLASS_NAME']?></td>

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
		
		?>
		<input type="hidden" name="weight[]" value="<?=@$row['WEIGHT']?>" />
                <?php @$count_weight=@$count_weight+@$row['WEIGHT']; ?>
            </td>
			<td dir="ltr"><?php
                 
                     echo @$row['UNIT_NAME'];
                 
		
		?>
		
            </td>
     <td dir="ltr"><?php
                 
                     echo 100-@$row['EFFECT_VALUE'].'%';
                 
		
		?>
		
            </td>
			<?php
           if($row['T_ADOPT_NORTH']!=2 OR $row['ADOPT_NORTH']!=2) {?>

        <td style="background-color: lightgrey;">
            <?php }
    else{
        ?>
    <td>

            <?php
    }?>
                <div class="row">
                    <div class="col-md-4">
                        <?php
                        $target_north=@$row['NORTH'];

                        if($target_north==0)
                            $target_north = 'N/A';
                        else
                            $target_north =@$row['NORTH_T_PERSENT'].'%';

                        ?>
                        <input type="text" class="form-control" name="north_t_persent[]" value="<?=@$target_north;?>" id="txt_north_t_differ_<?=$count?>" data-val="false" data-val-required="required" />
                        <input type="hidden" name="north_seq[]" id="north_seq_id_<?= $count ?>" value="<?=@$row['NORTH_SEQ']?>" data-check='<?=@$row['NORTH_SEQ']?>' /></div>
                    <?php
                    if($row['EFFECT_FLAG']==1)
                    {
                    if((@$row['NORTH_T_PERSENT']-100)>($row['EFFECT_VALUE']*-1))
                   // if($row['NORTH_T_DIFFER']<($row['EFFECT_VALUE']/100))
                    {
                    ?>
                    <div class="col-md-4" style="background-color: lightgrey; border: 8px solid green;">

                        <?php
                        }
                        else  if((@$row['NORTH_T_PERSENT']-100)<($row['EFFECT_VALUE']*-1))//if($row['NORTH_T_DIFFER']>($row['EFFECT_VALUE']/100))
                        {
                        ?>
                        <div class="col-md-4" style="background-color: lightgrey; border: 8px solid red;">

                            <?php
                            }
                            else  if((@$row['NORTH_T_PERSENT']-100)==($row['EFFECT_VALUE']*-1))//if($row['NORTH_T_DIFFER'] == ($row['EFFECT_VALUE']/100))
                            {
                            ?>
							<!--
                            <div class="col-md-4" style="background-color: lightgrey; border: 8px solid #ffff00;">
							-->
						
                             <div class="col-md-4" style="background-color: lightgrey; border: 8px solid green;">
                                <?php
                                }
                                }
                                else if($row['EFFECT_FLAG']==2)
                                {
                                if((@$row['NORTH_T_PERSENT']-100)<($row['EFFECT_VALUE']*-1))
                               // if($row['NORTH_T_DIFFER']>($row['EFFECT_VALUE']/100))
                                {
                                ?>
                                <div class="col-md-4" style="background-color: lightgrey; border: 8px solid green;">

                                    <?php
                                    }
                                    else  if((@$row['NORTH_T_PERSENT']-100)>($row['EFFECT_VALUE']*-1))//if($row['NORTH_T_DIFFER']<($row['EFFECT_VALUE']/100))
                                    {
                                    ?>
                                    <div class="col-md-4" style="background-color: lightgrey; border: 8px solid red;">

                                        <?php
                                        }
                                        else  if((@$row['NORTH_T_PERSENT']-100)==($row['EFFECT_VALUE']*-1))//if($row['NORTH_T_DIFFER'] == ($row['EFFECT_VALUE']/100))
                                        {
                                        ?>
										<!--
                                        <div class="col-md-4" style="background-color: lightgrey; border: 8px solid #ffff00;">
                                         -->
										 <div class="col-md-4" style="background-color: lightgrey; border: 8px solid green;">
                                            <?php
                                            }
                                            }
                                            if($row['EFFECT_FLAG']==3)
                                            {
                                            ?>
                                            <div class="col-md-4" style="background-color: lightgrey; border: 8px solid #0095cc;">
                                                <?php

                                                }
                                                ?>
                                                <?php


                                                if ($row['PREV_VALUE_NORTH'] > $row['VALUE_NORTH']) {

                                                    echo ' <span> <i class="icon icon-arrow-down" style="color:red"></i></span>';
                                                }
                                                elseif ($row['PREV_VALUE_NORTH'] < $row['VALUE_NORTH']) {
                                                    echo ' <span> <i class="icon icon-arrow-up" style="color:green"></i></span>';
                                                }
                                                elseif ($row['PREV_VALUE_NORTH'] == $row['VALUE_NORTH']) {
                                                    echo ' <span> <i class="icon icon-arrow-right" style="color:#0095cc"></i></span>';
                                                }
                                                ?>
                    </div>
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="prev_value_north[]" value="<?=@$row['NORTH_T_ORDER']?>" readonly  />
                        <?php $count_t_wegith_north +=$row['NORTH_T_WEGITH'];?>
                    </div>
                   <!--
                    <div class="col-md-3">
                        <input class="form-control" name="differ_north[]" value="<?=@$row['DIFFER_NORTH']?>" readonly  />
                    </div>
                    -->
                </div>

            </td>
            <?php
           if($row['T_ADOPT_GAZA']!=2 OR $row['ADOPT_GAZA']!=2) {?>

        <td style="background-color: lightgrey;">
            <?php }
    else{
        ?>
    <td>

            <?php
    }?>
                <div class="col-md-4">
                    <?php
                    $target_gaza=@$row['GAZA'];

                    if($target_gaza==0)
                        $target_gaza = 'N/A';
                    else
                        $target_gaza =@$row['GAZA_T_PERSENT'].'%';


                    ?>
                    <input type="text" class="form-control" name="gaza_t_persent[]" value="<?=@$target_gaza;?>" id="txt_gaza_t_persent_<?=$count?>" data-val="false" data-val-required="required" />
                    <input type="hidden" name="gaza_seq[]" id="gaza_seq_id_<?= $count ?>" value="<?=@$row['GAZA_SEQ']?>" /></div>
                <?php
                if($row['EFFECT_FLAG']==1)
                {
                if((@$row['GAZA_T_PERSENT']-100)>($row['EFFECT_VALUE']*-1))
               // if($row['GAZA_T_DIFFER']<($row['EFFECT_VALUE']/100))
                {
                ?>
                <div class="col-md-4" style="background-color: lightgrey; border: 8px solid green;">

                    <?php
                    }
                    else  if((@$row['GAZA_T_PERSENT']-100)<($row['EFFECT_VALUE']*-1))//if($row['GAZA_T_DIFFER']>($row['EFFECT_VALUE']/100))
                    {
                    ?>
                    <div class="col-md-4" style="background-color: lightgrey; border: 8px solid red;">

                        <?php
                        }
                        else  if((@$row['GAZA_T_PERSENT']-100)==($row['EFFECT_VALUE']*-1))//if($row['GAZA_T_DIFFER'] == ($row['EFFECT_VALUE']/100))
                        {
                        ?>
						<!--
                        <div class="col-md-4" style="background-color: lightgrey; border: 8px solid #ffff00;">
						-->
                        <div class="col-md-4" style="background-color: lightgrey; border: 8px solid green;">
                            <?php
                            }
                            }
                            else if($row['EFFECT_FLAG']==2)
                            {
                            if((@$row['GAZA_T_PERSENT']-100)<($row['EFFECT_VALUE']*-1))// if($row['GAZA_T_DIFFER']>($row['EFFECT_VALUE']/100))
                            {
                            ?>
                            <div class="col-md-4" style="background-color: lightgrey; border: 8px solid green;">

                                <?php
                                }
                                else if((@$row['GAZA_T_PERSENT']-100)>($row['EFFECT_VALUE']*-1))// if($row['GAZA_T_DIFFER']<($row['EFFECT_VALUE']/100))
                                {
                                ?>
                                <div class="col-md-4" style="background-color: lightgrey; border: 8px solid red;">

                                    <?php
                                    }
                                    else if((@$row['GAZA_T_PERSENT']-100)==($row['EFFECT_VALUE']*-1))//if($row['GAZA_T_DIFFER'] == ($row['EFFECT_VALUE']/100))
                                    {
                                    ?>
									<!--
                                    <div class="col-md-4" style="background-color: lightgrey; border: 8px solid #ffff00;">
-->
                      <div class="col-md-4" style="background-color: lightgrey; border: 8px solid green;">
                                        <?php
                                        }
                                        }
                                        if($row['EFFECT_FLAG']==3)
                                        {
                                        ?>
                                        <div class="col-md-4" style="background-color: lightgrey; border: 8px solid #0095cc;">
                                            <?php

                                            }
                                            ?>
                                            <?php


                                            if (@$row['PREV_VALUE_GAZA'] > @$row['VALUE_GAZA']) {

                                                echo ' <span> <i class="icon icon-arrow-down" style="color:red"></i></span>';
                                            }
                                            elseif (@$row['PREV_VALUE_GAZA'] < @$row['VALUE_GAZA']) {
                                                echo ' <span> <i class="icon icon-arrow-up" style="color:green"></i></span>';
                                            }
                                            elseif (@$row['PREV_VALUE_GAZA'] == @$row['VALUE_GAZA']) {
                                                echo ' <span> <i class="icon icon-arrow-right" style="color:#0095cc"></i></span>';
                                            }
                                            ?>
                </div>
                <div class="col-md-4">
                    <input type="text" class="form-control" name="prev_value_gaza[]" value="<?=@$row['GAZA_T_ORDER']?>" readonly  />
                    <?php $count_t_wegith_gaza +=$row['GAZA_T_WEGITH'];?>
                </div>
               <!--
                <div class="col-md-3">
                    <input class="form-control" name="differ_gaza[]" value="<?=@$row['DIFFER_GAZA']?>" readonly  />
                </div>
                -->
            </td>
                     <?php
           if($row['T_ADOPT_MIDDEL']!=2 OR $row['ADOPT_MIDDEL']!=2) {?>

        <td style="background-color: lightgrey;">
            <?php }
    else{
        ?>
    <td>

            <?php
    }?>
                <div class="col-md-4">
                    <?php
                    $target_middle=@$row['MIDDLE'];

                    if($target_middle==0)
                        $target_middle = 'N/A';
                    else
                        $target_middle =@$row['MIDDLE_T_PERSENT'].'%';

                    ?>
                    <input type="text" class="form-control" name="txt_middle[]" value="<?=@$target_middle;?>" id="txt_middle_<?=$count?>" data-val="false" data-val-required="required" />
                    <input type="hidden" name="middle_seq[]" id="middle_seq_id_<?= $count ?>" value="<?=@$row['MIDDLE_SEQ']?>" /></div>
                <?php
                if($row['EFFECT_FLAG']==1)
                {
                if((@$row['MIDDLE_T_PERSENT']-100)>($row['EFFECT_VALUE']*-1))
                //if($row['MIDDLE_T_DIFFER']<($row['EFFECT_VALUE']/100))
                {
                ?>
                <div class="col-md-4" style="background-color: lightgrey; border: 8px solid green;">

                    <?php
                    }
                    else if((@$row['MIDDLE_T_PERSENT']-100)<($row['EFFECT_VALUE']*-1))// if($row['MIDDLE_T_DIFFER']>($row['EFFECT_VALUE']/100))
                    {
                    ?>
                    <div class="col-md-4" style="background-color: lightgrey; border: 8px solid red;">

                        <?php
                        }
                        else  if((@$row['MIDDLE_T_PERSENT']-100)==($row['EFFECT_VALUE']*-1))//if($row['MIDDLE_T_DIFFER'] == ($row['EFFECT_VALUE']/100))
                        {
                        ?>
                        <!-- 
						<div class="col-md-4" style="background-color: lightgrey; border: 8px solid #ffff00;">
                         -->
						 <div class="col-md-4" style="background-color: lightgrey; border: 8px solid green;">
                            <?php
                            }
                            }
                            else if($row['EFFECT_FLAG']==2)
                            {
                            if((@$row['MIDDLE_T_PERSENT']-100)<($row['EFFECT_VALUE']*-1))
                            //if($row['MIDDLE_T_DIFFER']>($row['EFFECT_VALUE']/100))
                            {
                            ?>
                            <div class="col-md-4" style="background-color: lightgrey; border: 8px solid green;">

                                <?php
                                }
                                else  if((@$row['MIDDLE_T_PERSENT']-100)>($row['EFFECT_VALUE']*-1))//if($row['MIDDLE_T_DIFFER']<($row['EFFECT_VALUE']/100))
                                {
                                ?>
                                <div class="col-md-4" style="background-color: lightgrey; border: 8px solid red;">

                                    <?php
                                    }
                                    else  if((@$row['MIDDLE_T_PERSENT']-100)==($row['EFFECT_VALUE']*-1))//if($row['MIDDLE_T_DIFFER'] == ($row['EFFECT_VALUE']/100))
                                    {
                                    ?>
                                   <!-- 
						<div class="col-md-4" style="background-color: lightgrey; border: 8px solid #ffff00;">
                         -->
						 <div class="col-md-4" style="background-color: lightgrey; border: 8px solid green;">
                                        <?php
                                        }
                                        }
                                        if($row['EFFECT_FLAG']==3)
                                        {
                                        ?>
                                        <div class="col-md-4" style="background-color: lightgrey; border: 8px solid #0095cc;">
                                            <?php

                                            }
                                            ?>
                                            <?php


                                            if (@$row['PREV_VALUE_MIDDLE'] > @$row['VALUE_MIDDLE']) {

                                                echo ' <span> <i class="icon icon-arrow-down" style="color:red"></i></span>';
                                            }
                                            elseif (@$row['PREV_VALUE_MIDDLE'] < @$row['VALUE_MIDDLE']) {
                                                echo ' <span> <i class="icon icon-arrow-up" style="color:green"></i></span>';
                                            }
                                            elseif (@$row['PREV_VALUE_MIDDLE'] == @$row['VALUE_MIDDLE']) {
                                                echo ' <span> <i class="icon icon-arrow-right" style="color:#0095cc"></i></span>';
                                            }
                                            ?>
                </div>
                <div class="col-md-4">
                    <input type="text" class="form-control" name="prev_value_middle[]" value="<?=@$row['MIDDLE_T_ORDER']?>" readonly  />
                    <?php $count_t_wegith_middle +=$row['MIDDLE_T_WEGITH'];?>

                </div>
              <!--
                <div class="col-md-3">
                    <input class="form-control" name="differ_middle[]" value="<?=@$row['DIFFER_MIDDLE']?>" readonly  />
                </div>
                -->
            </td>
              <?php
           if($row['T_ADOPT_KHAN']!=2 OR $row['ADOPT_KHAN']!=2) {?>

        <td style="background-color: lightgrey;">
            <?php }
    else{
        ?>
    <td>

            <?php
    }?>
                <div class="col-md-4">
                    <?php
                    $target_khan=@$row['KHAN'];

                    if($target_khan==0)
                        $target_khan = 'N/A';
                    else
                        $target_khan =@$row['KHAN_T_PERSENT'].'%';

                    ?>
                    <input type="text" class="form-control" name="txt_khan[]" value="<?=@$target_khan;?>" id="txt_khan_<?=$count?>" data-val="false" data-val-required="required" />
                    <input type="hidden" name="khan_seq[]" id="khan_seq_id_<?= $count ?>" value="<?=@$row['KHAN_SEQ']?>" /></div>
                <?php
                if($row['EFFECT_FLAG']==1)
                {
                if((@$row['KHAN_T_PERSENT']-100)>($row['EFFECT_VALUE']*-1))
                //if($row['KHAN_T_DIFFER']<($row['EFFECT_VALUE']/100))
                {
                ?>
                <div class="col-md-4" style="background-color: lightgrey; border: 8px solid green;">

                    <?php
                    }
                    else  if((@$row['KHAN_T_PERSENT']-100)<($row['EFFECT_VALUE']*-1))
                    //if($row['KHAN_T_DIFFER']>($row['EFFECT_VALUE']/100))
                    {
                    ?>
                    <div class="col-md-4" style="background-color: lightgrey; border: 8px solid red;">

                        <?php
                        }
                        else                  if((@$row['KHAN_T_PERSENT']-100)==($row['EFFECT_VALUE']*-1))
                        //if($row['KHAN_T_DIFFER'] == ($row['EFFECT_VALUE']/100))
                        {
                        ?>
                      <!-- 
						<div class="col-md-4" style="background-color: lightgrey; border: 8px solid #ffff00;">
                         -->
						 <div class="col-md-4" style="background-color: lightgrey; border: 8px solid green;">
                            <?php
                            }
                            }
                            else if($row['EFFECT_FLAG']==2)
                            {
                            if((@$row['KHAN_T_PERSENT']-100)<($row['EFFECT_VALUE']*-1))

                            //if($row['KHAN_T_DIFFER']>($row['EFFECT_VALUE']/100))
                            {
                            ?>
                            <div class="col-md-4" style="background-color: lightgrey; border: 8px solid green;">

                                <?php
                                }
                                else  if((@$row['KHAN_T_PERSENT']-100)>($row['EFFECT_VALUE']*-1))
                                // if($row['KHAN_T_DIFFER']<($row['EFFECT_VALUE']/100))
                                {
                                ?>
                                <div class="col-md-4" style="background-color: lightgrey; border: 8px solid red;">

                                    <?php
                                    }
                                    else   if((@$row['KHAN_T_PERSENT']-100)==($row['EFFECT_VALUE']*-1))
                                    //if($row['KHAN_T_DIFFER'] == ($row['EFFECT_VALUE']/100))
                                    {
                                    ?>
                            <!-- 
						<div class="col-md-4" style="background-color: lightgrey; border: 8px solid #ffff00;">
                         -->
						 <div class="col-md-4" style="background-color: lightgrey; border: 8px solid green;">
                                        <?php
                                        }
                                        }
                                        if($row['EFFECT_FLAG']==3)
                                        {
                                        ?>
                                        <div class="col-md-4" style="background-color: lightgrey; border: 8px solid #0095cc;">
                                            <?php

                                            }
                                            ?>
                                            <?php


                                            if (@$row['PREV_VALUE_KHAN'] > @$row['VALUE_KHAN']) {

                                                echo ' <span> <i class="icon icon-arrow-down" style="color:red"></i></span>';
                                            }
                                            elseif (@$row['PREV_VALUE_KHAN'] < @$row['VALUE_KHAN']) {
                                                echo ' <span> <i class="icon icon-arrow-up" style="color:green"></i></span>';
                                            }
                                            elseif (@$row['PREV_VALUE_KHAN'] == @$row['VALUE_KHAN']) {
                                                echo ' <span> <i class="icon icon-arrow-right" style="color:#0095cc"></i></span>';
                                            }
                                            ?>
                </div>
                <div class="col-md-4">
                    <input type="text" class="form-control" name="prev_value_khan[]" value="<?=@$row['KHAN_T_ORDER']?>" readonly  />
                    <?php $count_t_wegith_khan +=$row['KHAN_T_WEGITH'];?>

                </div>
            <!--
                <div class="col-md-3">
                    <input class="form-control" name="differ_KHAN[]" value="<?=@$row['DIFFER_KHAN']?>" readonly  />
                </div>
                -->
            </td>
               <?php
           if($row['T_ADOPT_RAFAH']!=2 OR $row['ADOPT_RAFAH']!=2) {?>

        <td style="background-color: lightgrey;">
            <?php }
    else{
        ?>
    <td>

            <?php
    }?>
                <div class="col-md-4">
                    <?php
                    $target_rafa=@$row['RAFA'];

                    if($target_rafa==0)
                        $target_rafa = 'N/A';
                    else
                        $target_rafa =@$row['RAFA_T_PERSENT'].'%';

                    ?>
                    <input type="text" class="form-control" name="txt_rafa[]" value="<?=@$target_rafa;?>" id="txt_rafa_<?=$count?>" data-val="false" data-val-required="required" />
                    <input type="hidden" name="rafa_seq[]" id="rafa_seq_id_<?= $count ?>" value="<?=@$row['RAFA_SEQ']?>" /></div>
                <?php
                if($row['EFFECT_FLAG']==1)
                {
                if((@$row['RAFA_T_PERSENT']-100)>($row['EFFECT_VALUE']*-1))

                //if($row['RAFA_T_DIFFER']<($row['EFFECT_VALUE']/100))
                {
                ?>
                <div class="col-md-4" style="background-color: lightgrey; border: 8px solid green;">

                    <?php
                    }
                    else  if((@$row['RAFA_T_PERSENT']-100)<($row['EFFECT_VALUE']*-1))//if($row['RAFA_T_DIFFER']>($row['EFFECT_VALUE']/100))
                    {
                    ?>
                    <div class="col-md-4" style="background-color: lightgrey; border: 8px solid red;">

                        <?php
                        }
                        else  if((@$row['RAFA_T_PERSENT']-100)==($row['EFFECT_VALUE']*-1))//if($row['RAFA_T_DIFFER'] == ($row['EFFECT_VALUE']/100))
                        {
                        ?>
                      <!-- 
						<div class="col-md-4" style="background-color: lightgrey; border: 8px solid #ffff00;">
                         -->
						 <div class="col-md-4" style="background-color: lightgrey; border: 8px solid green;">
                            <?php
                            }
                            }
                            else if($row['EFFECT_FLAG']==2)
                            {
                            if((@$row['RAFA_T_PERSENT']-100)<($row['EFFECT_VALUE']*-1))
                            //if($row['RAFA_T_DIFFER']>($row['EFFECT_VALUE']/100))
                            {
                            ?>
                            <div class="col-md-4" style="background-color: lightgrey; border: 8px solid green;">

                                <?php
                                }
                                else  if((@$row['RAFA_T_PERSENT']-100)>($row['EFFECT_VALUE']*-1))//if($row['RAFA_T_DIFFER']<($row['EFFECT_VALUE']/100))
                                {
                                ?>
                                <div class="col-md-4" style="background-color: lightgrey; border: 8px solid red;">

                                    <?php
                                    }
                                    else if((@$row['RAFA_T_PERSENT']-100)==($row['EFFECT_VALUE']*-1)) //if($row['RAFA_T_DIFFER'] == ($row['EFFECT_VALUE']/100))
                                    {
                                    ?>
                            <!-- 
						<div class="col-md-4" style="background-color: lightgrey; border: 8px solid #ffff00;">
                         -->
						 <div class="col-md-4" style="background-color: lightgrey; border: 8px solid green;">
                                        <?php
                                        }
                                        }
                                        if($row['EFFECT_FLAG']==3)
                                        {
                                        ?>
                                        <div class="col-md-4" style="background-color: lightgrey; border: 8px solid #0095cc;">
                                            <?php

                                            }
                                            ?>
                                            <?php


                                            if (@$row['PREV_VALUE_RAFA'] > @$row['VALUE_RAFA']) {

                                                echo ' <span> <i class="icon icon-arrow-down" style="color:red"></i></span>';
                                            }
                                            elseif (@$row['PREV_VALUE_RAFA'] < @$row['VALUE_RAFA']) {
                                                echo ' <span> <i class="icon icon-arrow-up" style="color:green"></i></span>';
                                            }
                                            elseif (@$row['PREV_VALUE_RAFA'] == @$row['VALUE_RAFA']) {
                                                echo ' <span> <i class="icon icon-arrow-right" style="color:#0095cc"></i></span>';
                                            }
                                            ?>
                </div>
                <div class="col-md-4">
                    <input type="text" class="form-control" name="prev_value_rafa[]" value="<?=@$row['RAFA_T_ORDER']?>" readonly  />
                    <?php $count_t_wegith_rafah +=$row['RAFA_T_WEGITH'];?>

                </div>
               <!--
                <div class="col-md-3">
                    <input class="form-control" name="differ_RAFA[]" value="<?=@$row['DIFFER_RAFA']?>" readonly  />
                </div>
                -->
            </td>










            <?php $count++ ?>
        </tr>
    <?php endforeach;?>

    </tbody>
</table>
<br>
<div class="row">
    <div class="col-md-8">
        <table class="table" id="colne">


            <tbody>
            <tr>
                <th></th>
                <th>مقر الشمال</th>
                <th>مقر غزة</th>
                <th>مقر الوسطى</th>
                <th>مقر خانيونس</th>
                <th>مقر رفح</th>







            </tr>
            <tr>

                <th> الإجمالي الكلي لاوزان المقر</th>

                <th id="n_amount_total"><?=@$count_t_wegith_north;?></th>
                <th id="n_amount_total"><?=@$count_t_wegith_gaza;?></th>
                <th id="n_amount_total"><?=@$count_t_wegith_middle;?></th>
                <th id="n_amount_total"><?=@$count_t_wegith_khan;?></th>
                <th id="n_amount_total"><?=@$count_t_wegith_rafah;?></th>



            </tr>

            <tr>
                <th> الإجمالي الكلي لاوزان المؤشرات</th>

                <th id="n_amount_total"><?=@$count_weight;?></th>
                <th id="n_amount_total"><?=@$count_weight;?></th>
                <th id="n_amount_total"><?=@$count_weight;?></th>
                <th id="n_amount_total"><?=@$count_weight;?></th>
                <th id="n_amount_total"><?=@$count_weight;?></th>





            </tr>

            <tr>
                <th>الإجمالي الكلي</th>

                <th id="north_res_amount_total"><?php
                    if($count_weight==0)
                        $total= 0;
                        else
                            $total=round((@$count_t_wegith_north/$count_weight)*100).'%';

echo  $total;
                    ?></th>
                <th id="gaza_res_amount_total"><?php
                    if($count_weight==0)
                        $total= 0;
                    else
                        $total= round((@$count_t_wegith_gaza/$count_weight)*100).'%';


                    echo  $total;
                    ?></th>
                <th id="middle_res_amount_total"><?php
                    if($count_weight==0)
                        $total= 0;
                    else
                        $total=round((@$count_t_wegith_middle/$count_weight)*100).'%';

                    echo  $total;
                    ?></th>
                <th id="khan_res_amount_total"><?php
                    if($count_weight==0)
                        $total= 0;
                    else
                        $total=round((@$count_t_wegith_khan/$count_weight)*100).'%';

                    echo  $total;
                    ?></th>
                <th id="rafah_res_amount_total"><?php
                    if($count_weight==0)
                        $total= 0;
                    else
                        $total=round((@$count_t_wegith_rafah/$count_weight)*100).'%';

                    echo  $total;
                    ?></th>




            </tr>


            </tbody>
        </table>
         </div>
    <div class="col-md-4">
        <table class="table">


            <tbody>
            <tr>
                <th>تقييم مجموع المقرات</th>








            </tr>
            <tr>

                <th id="all_res_amount_total"><?=@$count_t_wegith_north+@$count_t_wegith_gaza+@$count_t_wegith_khan+@$count_t_wegith_middle+@$count_t_wegith_rafah;?></th>



            </tr>


            <tr>




                <th id="n_amount_total"><?=@$count_weight*5;?></th>



            </tr>
            <tr>

               <?php
                if($count_weight==0)
                    $total= 0;
                else
                {

                    $total=round(((@$count_t_wegith_north+@$count_t_wegith_gaza+@$count_t_wegith_khan+@$count_t_wegith_middle+@$count_t_wegith_rafah)/($count_weight*5))*100);
                }
                ?>

                <th id="n_amount_total"><?=$total.'%';?></th>




            </tr>


            </tbody>
        </table>
    </div>

    </div>


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



