<?php
/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 29/10/17
 * Time: 01:29 م
 */

$MODULE_NAME= 'planning';
$TB_NAME= 'Strategic_planning';
$edit_url =base_url("$MODULE_NAME/$TB_NAME/get");
$get_seq_url=base_url("$MODULE_NAME/$TB_NAME/public_get_seq");
$count = $offset;


?>

<div class="tbl_container">
    <table class="table" id="page_tb" data-container="container">
    <thead>
    <tr>

        <th>#</th>
        <th>م</th>
        <th class="hidden"></th>
        <th>المحور</th>
        <th>الهدف الاستراتيجي (العام)</th>
        <th>الهدف الاستراتيجي (الخاص)</th>
         <th>اسم المشروع</th>
        <th>جهة التنفيذ</th>
        <th>التكلفة  (ش)</th>
		 <!--<th>التكلفة السنوية</th>
		 <th>اجمالي ايراد المشروع</th>
		 <th>الايراد السنوي</th>

        



-->
        <?php
        for($i=1;$i<=$max;$i++)
        {
        ?>
            <th></th>
        <?php
        }
        ?>
<th>مدة التنفيذ بالسنوات</th>
    



    </tr>
    </thead>

    <tbody>
    <?php foreach($page_rows as $row) : ?>

        <td><input type='checkbox' class='checkboxes' value='<?=$row['STRATGIC_SER']?>' data-id='<?=$row['STRATGIC_SER']?>'></td>
        <td><?=$count?></td>
        <td class="hidden"><?=$row['OBJECTIVE_LABEL']?></td>
        <td><?=$row['OBJECTIVE_NAME']?></td>
        <td><?=$row['GOAL_NAME']?></td>
        <td><?=$row['GOAL_T_NAME']?></td>
        <td><?=$row['ACTIVITY_NAME']?></td>
        <td><?=$row['MANAGE_EXE_ID_NAME']?></td>
        <td><?php echo $row['STRATGIC_TOTAL_PRICE'];

            ?></td>
			<!--<td><?php echo $row['TOTAL_PRICE_BUDGET'];

            ?></td>

                <td><?=$row['STRATGIC_INCOME']?></td>
				<td><?=$row['INCOME']?></td>

       
-->
    
 <?php
  $from_year=$row['FROM_YEAR'];
  $to_year=$row['TO_YEAR'];
        for($i=1;$i<=$max;$i++)
        {
        
        if($from_year<=$to_year)
		{
	   $seq=Modules::run("$MODULE_NAME/$TB_NAME/public_get_seq",$row['STRATGIC_SER'],$from_year);
        ?>
            <td style="background-color:#ffffb8;color:#003399;font-weight: bold;"> <?php if(HaveAccess($edit_url))  : ?><a href="<?=base_url("planning/Strategic_planning/get/{$seq[0]['SEQ']}" )?>"><?=$from_year++;?> <?php endif; ?></a></td>
        <?php
		}
		else
		{
		?>
		<th></th>
		<?php
		}
        }
        ?>


       
 <td><?=$row['EXE_YEARS'].' سنة ';?></td>
       




            





       




        <?php $count++ ?>
    </tr>
    <?php endforeach;?>

    </tbody>
</table>
</div>
<?php
echo $this->pagination->create_links();
?>

<script>
    if (typeof initFunctions == 'function') {
        initFunctions();
    }
    if (typeof ajax_pager == 'function') {
        ajax_pager();
    }
    if (typeof show_page == 'undefined'){
        document.getElementById("page_tb").style.display="none";
        document.getElementsByClassName("pagination")[0].style.display="none";
    }
</script>

