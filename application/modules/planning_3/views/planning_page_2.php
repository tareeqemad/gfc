<?php
/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 29/10/17
 * Time: 01:29 م
 */

$MODULE_NAME= 'planning';
$TB_NAME= 'planning';
$edit_url =base_url("$MODULE_NAME/$TB_NAME/get");
$edit_without_cost_url=base_url("$MODULE_NAME/$TB_NAME/get_without_cost");


$count = 1;
?>

<table class="table" id="<?=$TB_NAME?>_tb" data-container="container">
    <thead>
    <tr>
        <th  >#</th>
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




        <?php if(HaveAccess($edit_url)) echo "<th>تحرير</th>"; ?>



    </tr>
    </thead>

    <tbody>
    <?php foreach($page_rows as $row) : ?>
    <tr>
        <td><input type='checkbox' class='checkboxes' value='<?=@$row['SEQ']?>' data-id='<?=@$row['PROJECT_ID']?>'></td>
        <td><?=@$count?></td>
        <td><?=@$row['YEAR']?></td>
        <td><?=@$row['BRANCH_NAME']?></td>
        <td><?=@$row['ACTIVITY_NO']?></td>
        <td><?=@$row['OBJECTIVE_NAME']?></td>
        <td><?=@$row['GOAL_NAME']?></td>
        <td><?=@$row['GOAL_T_NAME']?></td>
        <td><?=@$row['ACTIVE_CLASS_NAME']?></td>
        <td><?=@$row['PROJECT_TYPE_']?></td>
        <td><?=@$row['PROJECT_TYPE_DON_NAME']?></td>
        <td><?=@$row['ACTIVITY_NAME'].''.@$row['PROJECT_NAME']?></td>
        <td><?php echo $row['TOTAL_PRICE'];

            ?></td>
        <td><?=@$row['MANAGE_FOLLOW_ID_NAME']?></td>
        <td><?=@$row['MANAGE_EXE_ID_NAME']?></td>
        <td><?=@$row['TO_MONTH']-$row['FROM_MONTH']?></td>
        <td><?=@ months($row['TO_MONTH']) ?></td>
        <td><?=@ months($row['FROM_MONTH']) ?></td>


        <?php if(HaveAccess($edit_url) || HaveAccess($edit_without_cost_url))  : ?>
            <?php if($this->user->branch==1){?>
           <?php if($row['CLASS']==1){?>
            <td> <a href="<?=base_url("planning/planning/get/{$row['SEQ']}" )?>"><i class='glyphicon glyphicon-share'></i></a> </td>
                <?php
                }

                 else{?>
                    <td> <a href="<?=base_url("planning/planning/get_tech_cost/{$row['SEQ']}" )?>"><i class='glyphicon glyphicon-share'></i></a> </td>

        <?php }}

                else
                {
                    if($this->user->branch==$row['ENTRY_BRANCH'] ){
                 if($row['CLASS']==1){?>
                        <td> <a href="<?=base_url("planning/planning/get/{$row['SEQ']}" )?>"><i class='glyphicon glyphicon-share'></i></a> </td>
                    <?php
                    }

                    else{?>
                        <td> <a href="<?=base_url("planning/planning/get_tech_cost/{$row['SEQ']}" )?>"><i class='glyphicon glyphicon-share'></i></a> </td>

                    <?php }
                }
                    else
                      ?>
                        <td></td>
                        <?php

            }?>

        <?php endif; ?>




        <?php $count++ ?>
    </tr>
    <?php endforeach;?>

    </tbody>
</table>
</div>



