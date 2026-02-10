<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 09/07/15
 * Time: 11:22 ص
 */

$count=0;
$details= $details_2;

?>

<div class="tb_container">
    <table class="table" id="details_tb_2" data-container="container">
        <thead>
        <tr>
            <th style="width: 2%">#</th>
            <th style="width: 15%">نوع الآلية</th>
            <th style="width: 15%">العدد</th>
            <th style="width: 60%"> وصف الاحتياج </th>
        </tr>
        </thead>

        <tbody>

        <?php if(count($details) <= 0) {  // ادخال ?>
            <tr>
                <td><i class="glyphicon glyphicon-sort" /></i></td>
                <td>
                    <input type="hidden" name="c_ser[]" value="0" />
                    <select name="car_id[]" class="form-control" id="txt_car_id<?=$count?>" /><option></option></select>
                </td>
                <td>
                    <input name="car_count[]" class="form-control" id="txt_car_count<?=$count?>" />
                </td>
                <td>
                    <input name="need_description[]" class="form-control" id="txt_need_description<?=$count?>" />
                </td>
            </tr>

        <?php
        }else if(count($details) > 0) { // تعديل
            $count = -1;
            foreach($details as $row) {
                ?>
                <tr>
                    <td><?=++$count+1?></td>
                    <td>
                        <input type="hidden" name="c_ser[]" value="<?=$row['C_SER']?>" />
                        <select name="car_id[]" class="form-control" id="txt_car_id<?=$count?>" data-val="<?=$row['CAR_ID']?>" /><option></option></select>
                    </td>
                    <td>
                        <input name="car_count[]" class="form-control" id="txt_car_count<?=$count?>" value="<?=$row['CAR_COUNT']?>" />
                    </td>
                    <td>
                        <input name="need_description[]" class="form-control" id="txt_need_description<?=$count?>" value="<?=$row['NEED_DESCRIPTION']?>" />
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
                <?php if (count($details) <=0 || ( (isset($can_edit)?$can_edit:false) and  $adopt==1 )) { ?>
                    <a onclick="javascript:addRow_2();" href="javascript:;"><i class="glyphicon glyphicon-plus"></i>جديد</a>
                <?php } ?>
            </th>
            <th></th>
            <th></th>
        </tr>
        </tfoot>
    </table>
</div>
