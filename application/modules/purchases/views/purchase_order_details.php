<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 23/02/15
 * Time: 01:34 م
 */
$MODULE_NAME= 'purchases';
$TB_NAME= 'purchase_order';
$get_url= base_url("$MODULE_NAME/$TB_NAME/get");
$adopt_url= base_url("$MODULE_NAME/$TB_NAME/adopt_");
$count=0;
?>

<div class="tb_container">
    <table class="table items_class_det" id="details_tb" data-container="container">
        <thead>
        <tr>
            <th style="width: 2%">#</th>
            <th style="width: 6%">رقم الصنف</th>
            <th style="width: 15%">اسم الصنف</th>
            <th style="width: 2%">رقم الفصل</th>
            <th style="width: 6%">الوحدة</th>
            <th style="width: 5%">الكمية</th>
            <th style="width: 5%">الكمية المقبولة</th>
            <th style="width: 7%">السعر الدفتري </th>
            <th style="width: 7%">سعر الوحدة غ.ش.ض</th>
            <th style="width: 7%">الاجمالي</th>
            <th style="width: 7%">ضريبة ق م</th>
            <th style="width: 7%">الاجمالي ش ض ق م</th>
            <th style="width: 7%">الرصيد</th>
            <th style="width: 15%">توصيف الصنف / ملاحظات</th>
            <?=$quote?'<th style="width: 10%">سقف التوريد</th>':''?>
            <th style="width: 5%">رتب</th>
            <th style="width: 5%">إرفاق المقاييس</th>
        </tr>
        </thead>

        <tbody>

        <?php if(count($details) <= 0) {  // ادخال ?>
        <tr>
            <td><i class="glyphicon glyphicon-sort" ></i></td>
            <td>
                <input type="hidden" name="ser[]" value="0" />
                <input class="form-control" name="class[]" id="i_txt_class_id<?=$count?>" />
                <input  type="hidden" name="class_id[]"  id="h_txt_class_id<?= $count ?>" >
            </td>
            <td>
                <input name="class_name[]" readonly data-val="true" data-val-required="required" class="form-control"  id="txt_class_id<?=$count?>" />
               </td>
            <td>
                <input  name="section_no[]" readonly class="form-control" id="txt_section_no<?=$count?>" />
            </td>
            <td>
                <input name="class_unit[]" disabled class="form-control" id="unit_name_txt_class_id<?=$count?>" />
            </td>
            <td>
                <input name="amount[]" class="form-control" id="txt_amount<?=$count?>" />
            </td>
            <td></td>
            <td>
                <input name="class_price[]" readonly class="form-control" id="class_price_txt_class_id<?=$count?>" />
            </td>
            <td>
                <input name="buy_price[]" readonly class="form-control" id="price_txt_class_id<?=$count?>" />
            </td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>
                <input name="note[]" class="form-control" id="txt_note<?=$count?>" />
            </td>
            <?=$quote?'<td></td>':''?>
            <td>
                <input name="order_colum[]" class="form-control" id="txt_order_colum<?=$count?>" />
            </td>
            <td>
               </td>
        </tr>

        <?php
        }else if(count($details) > 0) { // تعديل
            $count = -1;
            foreach($details as $row) {
        ?>
        <tr>
            <td> <?php  if($row['DISTRIBUTE_PURCHASE_ORDER_ID']>0) { ?>
                <a href="<?php echo $get_url."/".$row['DISTRIBUTE_PURCHASE_ORDER_ID']."/"."edit";?>" target="_blank"> <?=++$count+1?></a>
              <?php } else { ?> <?php echo ++$count+1 ; } ?>
                <input type="checkbox"  <?php if($row['DISTRIBUTE_PURCHASE_ORDER_ID']>0) echo "disabled"; ?> value="<?=$row['SER']; ?>"  class="checkboxes"    id="distribute_purchase_order_id_<?= $count ?>"  >

            </td>

            <td>
               <input type="hidden" name="ser[]" value="<?=$row['SER']?>" />
                <input name="class[]" value="<?=$row['CLASS_ID']?>" class="form-control"  id="i_txt_class_id<?=$count?>" />
                <input  type="hidden" name="class_id[]" value="<?=$row['CLASS_ID']?>" id="h_txt_class_id<?= $count ?>" >
            </td>

            <td>
                <input name="class_name[]" readonly value="<?=$row['CLASS_ID_NAME']?>" class="form-control"  id="txt_class_id<?=$count?>" />
               </td>
            <td>
                <input  value="<?=$row['SECTION_NO']?>" name="section_no[]" readonly class="form-control" id="txt_section_no<?=$count?>" />
            </td>

            <td>
                <input name="class_unit[]" disabled value="<?=$row['CLASS_UNIT_NAME']?>" class="form-control" id="unit_name_txt_class_id<?=$count?>" />
            </td>

            <td>
                <input name="amount[]" class="form-control" id="txt_amount<?=$count?>" value="<?=$row['AMOUNT']?>" />
            </td>
            <td>
                <input name="approved[]" readonly class="form-control" id="txt_approved<?=$count?>" value="<?=$row['APPROVED']?>" />
            </td>
            <td>
                <input name="class_price[]" readonly class="form-control" id="class_price_txt_class_id<?=$count?>" value="<?=$row['CLASS_PRICE']?>" />
            </td>
            <td>
                <input name="buy_price[]" readonly class="form-control" id="price_txt_class_id<?=$count?>" value="<?=$row['PRICE']?>" />
            </td>
            <td><?=number_format($row['APPROVED']*$row['PRICE'],2)?></td>
            <td><?=number_format($row['APPROVED']*$row['PRICE']*0.16,2)?></td>
            <td><?=number_format( ($row['APPROVED']*$row['PRICE'])+($row['APPROVED']*$row['PRICE']*0.16),2)?></td>
            <td><?=$row['CLASS_BALANCE']?></td>
            <td>
                <input name="note[]" class="form-control" id="txt_note<?=$count?>" value="<?=$row['NOTE']?>" />
            </td>
            <?=$quote?'<td><input name="order_date[]" data-type="date" data-date-format="DD/MM/YYYY" data-val="true" data-val-regex="التاريخ غير صحيح!" data-val-regex-pattern="'.date_format_exp().'"  class="form-control" id="txt_order_date'.$count.'" value="'.$row['ORDER_DATE'].'" /></td>':''?>
            <td>
                <input name="order_colum[]" class="form-control" id="txt_order_colum<?=$count?>" value="<?=$row['ORDER_COLUM']?>" />
            </td>
            <td>
<?php if(($adopt_val==10)  AND  HaveAccess($adopt_url.'15') ) $can_upload=1; else $can_upload=0;
echo modules::run('attachments/attachment/indexInline',$row['PURCHASE_ORDER_ID'],'purchase_order_sub_'.$row['SER'],$can_upload);?>
        </td>
        </tr>
        <?php
            }
        }
        ?>

        </tbody>
        <tfoot>
        <tr>
            <th></th>
            <th>
                <?php if (count($details) <=0 || ( (isset($can_edit)?$can_edit:false) and  $adopt==10 )) { ?>
                    <a onclick="javascript:addRow();" href="javascript:;"><i class="glyphicon glyphicon-plus"></i>جديد</a>
                <?php } ?>
            </th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th id="total_cost"></th>
            <th id="total_tax"></th>
            <th id="total_cost_with_tax"></th>
            <th></th>
            <th></th>
            <?=$quote?'<td></td>':''?>
            <th></th>
            <th></th>
        </tr>
        </tfoot>
    </table>
</div>
