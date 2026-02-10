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
//var_dump($details);
//echo $id;
?>


<div class="tb_container">
    <table class="table" id="details_tb1" data-container="container"  align="center">
        <thead>
        <tr>
            <th >#</th>
            <th >الجهة</th>
			<th >المقر</th>
			<th >الادارة</th>
			<th >الدائرة</th>
			<th >القسم</th>
			<th >المهام</th>
        </tr>
        </thead>

        <tbody>
    <?php if(count($details) > 0) { // تعديل
            $str_count = 0;
            foreach($details as $row) {
                ++$str_count+1


                ?>

                <tr>
                   <td>
				   <?=$str_count;?>
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
					<?php if($struc_user_info[0]['TYPE']==1)
{
if($struc_user_info[0]['ST_ID']==$row['MANAGE_ID'])
{
?>
                    <td>
					<i class="fa fa-tasks fa-2x text-primary" aria-hidden="true" onclick="SHOW_TASKS(<?=$row['F_SEQ']?>,<?=$row['ACTIVITIES_PLAN_SER']?>,<?=$adopt?>);"></i>

                    </td>
<?php
}
}
else
{
if($cycle_user_info[0]['ST_ID']==$row['CYCLE_ID'] || $cycle_user_info[0]['ST_ID']==$row['DEPARTMENT_ID'])
{
?>
                    <td>
					<i class="fa fa-tasks fa-2x text-primary" aria-hidden="true" onclick="SHOW_TASKS(<?=$row['F_SEQ']?>,<?=$row['ACTIVITIES_PLAN_SER']?>,<?=$adopt?>);"></i>

                    </td>
<?php
}
else
{

  ?>                  <td></td>		
 <?php                   
}
}
?>



                </tr>
            <?php

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
            
            </th>
          
        </tr>
          

        </tfoot>
    </table></div>







