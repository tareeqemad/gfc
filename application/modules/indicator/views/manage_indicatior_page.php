<style>
    .case_10{
        background-color:#FFC9BB;
    }
   .large.tooltip-inner {
        max-width: 350px;
        width: 350px;
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
        <th>التصنيف الاول</th>
		<th>التصنيف الثاني</th>

        <th>اسم المعلومة</th>
       
        <th class="hidden" >الملاحظات</th>
        <th>الصيغة</th>
		<th>وحدة القياس</th>
		<th>فترة القياس</th>

        <th>طريقة قياس</th>
        <th>استيراد بيانات المعلومة الى المؤشر</th>

        <th>حالة الاعتماد</th>
        <th>الحالة</th>
        <?php if (HaveAccess(base_url("indicator/manage_indecatior_info/get_indecator_info/")))  {?> <th>عرض/تحرير</th> <?php } ?>


    </tr>
    </thead>

    <tbody>
    <?php foreach($page_rows as $row) :

        ?>

        <tr>
            <td class="hidden" >#</td>
            <td><?=$count?></td>
            <td><?=@$row['SECTOR_NAME']?></td>
            <td><?=@$row['CLASS_NAME']?></td>
			<td><?=@$row['SECOND_CLASS_NAME']?></td>


            <td>
                <?php 
if(@$row['NOTE'] == '')
@$row['NOTE']="لايوجد ملاحظات مدخلة";

                ?>
                <div class="row"><div class="col-md-11"><?=@$row['INDECATOR_NAME'];?></div><div class="col-md-1">
                <a href="#"  data-toggle="tooltip" data-placement="top" title="<?=@$row['NOTE']?>"><i class="icon icon-question-circle"></i></a>
            </div></div>
                
               
            </td>
            
            <td class="hidden">
                <?=@$row['NOTE'];?>
            </td>
			<td><?=@$row['SCALE_NAME'];?></td>
		<td><?=@$row['UNIT_NAME'];?></td>
		<td><?=@$row['PERIOD_NAME'];?></td>

            <td><?=@$row['ENTERY_VALUE_WAY_NAME'];?></td>

            <td>
                <?php
                if ($row['EFFECT']==1)
                {
                if((HaveAccess(base_url("indicator/indecator_info/get_indecator_info/")))){?>
            <a href="<?=base_url("indicator/indecator_info/get_indecator_info/{$row['INDECATOR_SER']}" )?>">نعم</a>
            <?php }

            }

            else
            {
                ?>
                <?php if((HaveAccess(base_url("indicator/manage_indecatior_info/get_indecator_info/")))){?>
                  <a href="<?=base_url("indicator/manage_indecatior_info/get_indecator_info/{$row['INDECATOR_SER']}" )?>">لا</a>
            <?php }
            }


            ?>
            </td>

            <td>
                <?=@$row['ADOPT_NAME'];?>
            </td>
            <td>
                <?=@$row['INDECATOR_FLAGE_NAME'];?>
            </td>



            <?php if((HaveAccess(base_url("indicator/manage_indecatior_info/get_indecator_info/")))){?>
                <td>  <a href="<?=base_url("indicator/manage_indecatior_info/get_indecator_info/{$row['INDECATOR_SER']}" )?>"><i class='glyphicon glyphicon-share'></i></a> </td>
            <?php } ?>
















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
</script>