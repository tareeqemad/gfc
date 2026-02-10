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
$TB_NAME= 'indicator';
$edit_url =base_url("$MODULE_NAME/$TB_NAME/get");

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
        <th>القطاع</th>
        <th>التصنيف</th>

        <th>اسم المؤشر</th>
        <th>الفارق المؤثر</th>
        <th>الملاحظات</th>
        <th>طريقة حساب المستهدف</th>


        <th>التقييم</th>

        <th>حالة الاعتماد</th>
        <th>الحالة</th>
        <?php if (HaveAccess(base_url("indicator/indecator_info/get_indecator_info/")))  {?> <th>عرض/تحرير</th> <?php } ?>


    </tr>
    </thead>

    <tbody>
    <?php foreach($page_rows as $row) :

        ?>

        <tr>
            <td><?=$count?></td>
            <td><?=@$row['SECTOR_NAME']?></td>
            <td><?=@$row['CLASS_NAME']?></td>


            <td>
                <?=@$row['INDECATOR_NAME'];?>
            </td>
            <td>
                <?=@$row['EFFECT_VALUE'].'%';?>
            </td>
            <td>
                <?=@$row['NOTE'];?>
            </td>
            <td>
                <?=@$row['EQUATION_TARGET'];?>
            </td>

            <td>
                <?=@$row['EFFECT_NAME'];?>
            </td>

            <td>
                <?=@$row['ADOPT_NAME'];?>
            </td>
            <td>
                <?=@$row['INDECATOR_FLAGE_NAME'];?>
            </td>



            <?php if((HaveAccess(base_url("indicator/indecator_info/get_indecator_info/")))){?>
                <td>  <a href="<?=base_url("indicator/indecator_info/get_indecator_info/{$row['INDECATOR_SER']}" )?>"><i class='glyphicon glyphicon-share'></i></a> </td>
            <?php } ?>



















            <?php $count++ ?>
        </tr>
    <?php endforeach;?>

    </tbody>
</table>
</div>