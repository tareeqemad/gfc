<style>
    .case_10{
        background-color:#FFC9BB;
    }
</style>
<?php
/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 06/08/18
 * Time: 11:44 ص
 */

$MODULE_NAME= 'indicator';
$TB_NAME= 'indicate_target';



$count = 1;
$page=1;
function class_name($mode){

    if($mode == 0 ){

        return '#FFC9BB';
    }
    else
        return '#DFFFDF';


}
?>
<table class="table" id="<?=$TB_NAME?>_tb" data-container="container">
    <thead>
    <tr>
        <th class="hidden" >#</th>
        <th>م</th>
        <th>الشهر</th>
        <th>القيمة</th>

        
       
        <?php if (HaveAccess(base_url("indicator/indicate_target/tahseel_target/")))  {?> <th>عرض/تحرير</th> <?php } ?>


    </tr>
    </thead>

    <tbody>
    <?php foreach($page_rows as $row) :

        ?>

        <tr>
            <td><?=$count?></td>
            <td><?=@$row['FOR_MONTH']?></td>
            <td><?=@$row['VAL']?></td>


           



            <?php if((HaveAccess(base_url("indicator/indicate_target/tahseel_target/")))){?>
                <td>  <a href="<?=base_url("indicator/indicate_target/tahseel_target/{$row['FOR_MONTH']}" )?>"><i class='glyphicon glyphicon-share'></i></a> </td>
            <?php } ?>



















            <?php $count++ ?>
        </tr>
    <?php endforeach;?>

    </tbody>
</table>
</div>