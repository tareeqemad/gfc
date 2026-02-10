<?php
/**
 * Created by PhpStorm.
 * User: mbadawi
 * Date: 13/06/16
 * Time: 11:15 ص
 */
$details= $return_details;
$count = 0;
$total = 0;

?> 
<div class="tb_container">
   <table class="table" id="details_tb" data-container="container">
        <thead>
        <tr>
            <th class="mah" style="display:none;"><input type="checkbox" name="All" id="All" disabled /></th>
            <th style="width: 2%">#</th>
            <th style="width: 11%">رقم السؤال</th>
            <th style="width: 50%">اسم السؤال</th>
            <th style="width: 37%">الوزن النسبي</th>
        </tr>
        </thead>

        <tbody>
         <?php if(count($details) <= 0) { ?>
            <tr>
                <td class="mah" style="display:none;"><input type="checkbox" name="c[]" id="c" />
                </td>
                <td><i class="glyphicon glyphicon-sort" /></i></td>
                <td>
                    <input name="element_order[]"  data-val="true" data-val-required="required" class="form-control"  id="txt_element_order<?=$count?>" />
                    <input  type="hidden" name="elem_id[]" value="0"  id="h_txt_elem_id<?= $count ?>" >
                </td>
                <td>
                    <input name="element_name[]"  data-val="true" data-val-required="required" class="form-control"  id="txt_element_name<?=$count?>" />
                </td>
                <td>
                    <input name="element_weight[]" data-val="true" data-val-required="required" class="form-control" id="txt_element_weight<?=$count?>" />
                </td>
            </tr>

        <?php
        }else
            $count = -1;
        ?>
         <?php
        foreach($details as $row) {
            $count++;
			$total += $row['ELEMENT_WEIGHT'];
            ?>
            <tr>
            <td class="mah" style="display:none;">
               <input type="checkbox" name="c[]" id="c" value="<?=$row['EEXTRA_ELEMENT_ID']?>" readonly />
            </td>
            <td><?=$count+1?></td>
            <td>
             <input name="element_order[]"  data-val="true" data-val-required="required" class="form-control"  id="txt_element_order<?=$count?>" readonly value="<?=$row['ELEMENT_ORDER']?>" />
             <input  type="hidden" name="elem_id[]"  id="h_txt_elem_id<?= $count ?>" value="<?=$row['EEXTRA_ELEMENT_ID']?>" >
             </td>
                
                <td>
           <input name="element_name[]"  data-val="true" data-val-required="required" class="form-control" readonly  id="txt_element_name<?=$count?>" value="<?=$row['ELEMENT_NAME']?>" />
                </td>
                <td>
           <input name="element_weight[]" data-val="true" data-val-required="required" class="form-control" readonly id="txt_element_weight<?=$count?>" value="<?=$row['ELEMENT_WEIGHT']?>" />
                </td>
            </tr>
            <?php } ?>
        </tbody>
        <tfoot>
           <tr>
            <th class="mah" style="display:none;"></th>
            <th></th>
            <th>
                   <?php if(!$can_edit){ ?>
                    <a onclick="javascript:addRows();" href="javascript:;"><i class="glyphicon glyphicon-plus"></i>جديد</a>
                    <?php
				     }else{
					?>
                    <a onclick="javascript:addRowse();" href="javascript:;"><i class="glyphicon glyphicon-plus"></i>جديد</a>
                    <?php
					 }
					 ?>
            </th>
            <th></th>
            <th><?=$total?></th>
            <th></th>
        </tr>
        </tfoot>
   </table>
</div>