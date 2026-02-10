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
 * Date: 24/07/18
 * Time: 01:33 م
 */
$MODULE_NAME= 'indicator';
$TB_NAME= 'indicate_target';

$save_all_url = base_url("$MODULE_NAME/$TB_NAME/save_all_target_tahseel");
$count = 1;
$page=1;
$C_FOR_MONTH=date("Ym",strtotime("-1 month"));

?>
<table class="table table-hover" id="<?=$TB_NAME?>_tb" data-container="container" style="width:50%">
    <thead>
    <tr>
       
        <th style="width:2%;">م</th>
        <th style="width:10%;">اسم المؤشر</th>
		<th style="width:10%;">معامل الضرب</th>
		
       

        
            

    </tr>
    </thead>

    <tbody>
        <?php foreach($page_rows as $row) :

        ?>
		 <tr>
            <td><?=$count?><input type="hidden" name="SER[]" value="<?=@$row['SER']?>" /></td>
			<td><?=@$row['INDECATOR_NAME']?><input type="hidden" name="indecator_ser[]" value="<?=@$row['INDECATOR_SER']?>" /></td>
			<td><?php 
			if(@$row['PERCENT']>=1)
			$persent=@$row['PERCENT'];
			else if(@$row['PERCENT']==0)
			$persent=@$row['PERCENT'];
			else
			$persent='0'.@$row['PERCENT'];
			?>
			<input class="form-control" dir="rtl" name="txt_percent[]" value="<?=@$persent?>" id="txt_percent_<?=$count?>" data-val="false" data-val-required="required" />
			</td>
			
			<?php $count++ ?>
			</tr>
		<?php endforeach;?>

    </tbody>
	<footer>
	<tr>
	<td></td>
	
	<td>

           <!-- <button type="button" onclick="javascript:search();" class="btn btn-success"> إستعلام</button>-->
            <?php  if( HaveAccess($save_all_url) and  ((@$page_rows[0]['INSERT_USER']==$this->user->id) or empty(@$page_rows[0]['INSERT_USER'])) ){  ?>
			<?php
			if($isCreate==1)
			{
			
			?>
                <button type="submit" id="save_btn_show" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
				
				
            <?php } 
			
			else if ($page_rows[0]['FOR_MONTH']== $C_FOR_MONTH)
			{
			?>
			<button type="submit" id="save_btn_show" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
			<?php
			
			}
			} ?>



        </td>
		<td></td>
		</tr>
		</footer>
</table>
 
</div>





