<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 13/03/18
 * Time: 10:27 ص
 */
$MODULE_NAME= 'planning';
$TB_NAME= 'Strategic_planning';
$isCreate =isset($details) && count($details)  > 0 ?false:true;
$str_count=0;
	$total_weghit=0;
			$total_achive=0;

//var_dump($details);
//echo $id;
?>


<div class="tb_container">
    <table class="table" id="details_tb1" data-container="container"  align="center">
        <thead>
        <tr>
            <th >#</th>
			<th style="width:30%" >المهمة</th>
			<th >من شهر</th>
			<th >الى شهر</th>
            <th >الجهة</th>
			<th >المقر</th>
			<th >الادارة</th>
			<th >الدائرة</th>
			<th >القسم</th>
			<th >الوزن النسبي</th>
			<th >نسبة الإنجاز</th>
			
        </tr>
        </thead>

        <tbody>
    <?php if(count($details) > 0) { // تعديل
            $str_count = 0;
			$total_weghit=0;
			$total_achive=0;
            foreach($details as $row) {
                ++$str_count+1
               //$total_weghit=$row['WEIGHT'];

                ?>

                <tr>
                   <td>
				   <?=$str_count;?>
                  </td>
					 <td>
                    <?=$row['ACTIVITY_NAME'];?>
                    </td>
					<td>
                    <?=months($row['FROM_MONTH']);?>
                    </td>
					<td>
                    <?=months($row['TO_MONTH']);?>
                    </td>
                    <td>
					<?=$row['PLANNING_DIR_NAME'];?>
                    </td>
					<td>
					<?=$row['BRANCH_NAME'];?>
                    </td>
					<td>
					<?=$row['MANAGE_ID_NAME'];?>
                    </td>
					<td>
					<?=$row['CYCLE_ID_NAME'];?>
                    </td>
					<td>
					<?=$row['DEPARTMENT_ID_NAME'];?>
                    </td>
                   
                    <td>
					<?=$row['WEIGHT'];?>
                    </td>
					<td>
					<?=$row['TASK_ACHIVE'].'%';?>
                    </td>

                </tr>
            <?php
$total_weghit+=$row['WEIGHT'];
$total_achive+=(($row['TASK_ACHIVE']/100)*$row['WEIGHT']);
            }
        }

        ?>
        </tbody>

        <tfoot>
        <tr>
            <th>
            
            </th>
         <th>
            
            </th><th>
            
            </th><th>
            
            </th><th>
			</th><th>
			</th><th>
            </th><th>
            </th><th>
            الاجمالي الكلي
            </th>
          <th><?=$total_weghit?></th>
		  <th><?=$total_achive.'%'?></th>
        </tr>
          

        </tfoot>
    </table></div>



<button type="button" onclick="$('#details_tb1').tableExport({type:'excel',escape:'false'});"
        class="btn btn-success"> اكسل
</button>



