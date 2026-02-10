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
    <th style="width:8%;">القطاع</th>
        <th style="width:15%;">التصنيف الاول</th>
        <th style="width:15%;">التصنيف الثاني</th>
        <th style="width:15%;">اسم المؤشر</th>
    <th hidden>الوزن</th>
	<th>الوحدة</th>
	


    <th>
        <div class="row">
            <div class="col-md-12 text-center" id="show_branch"><?=$branch_name;?></div>

        </div>

        <hr>
        <br>
        <div class="row">

           
            <div class="col-md-12">محقق</div>        
            
            
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

        ?><input type="hidden" name="weight[]" value="<?=@$row['WEIGHT']?>" />
        <?php $count_weight=$count_weight+@$row['WEIGHT']; ?>
    </td>
	<td dir="ltr"><?php
                 
                     echo @$row['UNIT_NAME'];
                 
		
		?>
		
            </td>
	 

    <?php
           if(( $row['ADOPT_NORTH']!=2) OR ( $row['ADOPT_GAZA']!=2 )OR ( $row['ADOPT_MIDDEL']!=2)OR ( $row['ADOPT_KHAN']!=2) OR ( $row['ADOPT_RAFAH']!=2)) {?>

        <td style="background-color: lightgrey;">
            <?php }
    else{
        ?>
    <td>

            <?php
    }?>



    <div class="row">
        <div class="col-md-12">
            <?php
            		
	    $value_north=@$row['VALUE_NORTH'];
            $value_gaza=@$row['VALUE_GAZA'];
            $value_middle=@$row['VALUE_MIDDLE'];
            $value_khan=@$row['VALUE_KHAN'];
            $value_rafa=@$row['VALUE_RAFA'];

            $value_gedco_all=$value_north+$value_gaza+$value_middle+$value_khan+$value_rafa;





            ?>
            <input type="text" class="form-control" name="t_value[]" value="<?=@$value_gedco_all;?>" id="txt_north_<?=$count?>" data-val="false" data-val-required="required" />
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
  
</script>



