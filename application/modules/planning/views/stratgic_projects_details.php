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
            <th >عام الخطة</th>
            <th >التكلفة السنوية</th>
			<th >الإيراد السنوي</th>
        </tr>
        </thead>

        <tbody>
    <?php if(count($details) > 0) { // تعديل
            $str_count = -1;
            foreach($details as $row) {
                ++$str_count+1


                ?>

                <tr>
                   <td>
                        <input class="form-control" name="stratgic_year[]" value="<?=$row['YEAR']?>" id="txt_stratgic_year_<?=$str_count?>" data-val="false" data-val-required="required" />
                        <input type="hidden" name="stratigic_seq[]" id="stratigic_seq<?= $str_count ?>" value="<?=$row['SEQ']?>" />
                    </td>
					
                    <td>
                        <input class="form-control" name="stratigic_price_det[]" value="<?=$row['TOTAL_PRICE_BUDGET']?>" id="txt_stratigic_price_det_<?=$str_count?>" data-val="false" data-val-required="required" />                      
                    </td>
					<td>
                        <input class="form-control" name="stratigic_income_det[]" value="<?=$row['INCOME']?>" id="txt_stratigic_income_det_<?=$str_count?>" data-val="false" data-val-required="required" />
                    </td>


                </tr>
            <?php

            }
        }

        ?>
        </tbody>

        <tfoot>
        <tr>
            <th>
            الإجمالي
            </th>
            <th id="total_price_calc"></th>
			<th id="total_incomes_calc"></th>
          
        </tr>
          

        </tfoot>
    </table></div>







