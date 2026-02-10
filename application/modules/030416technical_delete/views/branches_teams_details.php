<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 01/09/15
 * Time: 09:23 ص
 */

$count=0;

?>

<div class="tb_container">
    <table class="table" id="details_tb" data-container="container">
        <thead>
        <tr>
            <th style="width: 2%">#</th>
            <th style="width: 8%">هوية الموظف </th>
            <th style="width: 20%">اسم الموظف</th>
            <th style="width: 7%">الوظيفة</th>
            <th style="width: 7%">حذف</th>
        </tr>
        </thead>

        <tbody>

        <?php if(count($details) <= 0) {  // ادخال ?>
            <tr>
                <td><i class="glyphicon glyphicon-sort" /></i></td>
                <td>
                    <input type="hidden" name="ser[]" value="0" />
                    <input class="form-control" readonly name="customer_id[]" id="h_txt_customer_id<?=$count?>" />
                </td>
                <td>
                    <input name="customer_name[]" readonly data-val="true" data-val-required="required" class="form-control"  id="txt_customer_id<?=$count?>" />
                </td>
                <td>
                    <select name="worker_job[]" class="form-control" id="txt_worker_job<?=$count?>" /><option></option></select>
                </td>
                <td><i class="glyphicon glyphicon-remove delete_account"></i></td>
            </tr>

        <?php
        }else if(count($details) > 0) { // تعديل
            $count = -1;
            foreach($details as $row) {
                ?>
                <tr>
                    <td><?=++$count+1?></td>
                    <td>
                        <input type="hidden" name="ser[]" value="<?=$row['SER']?>" />
                        <input name="customer_id[]" readonly value="<?=$row['CUSTOMER_ID']?>" class="form-control"  id="h_txt_customer_id<?=$count?>" />
                    </td>

                    <td>
                        <input name="customer_name[]" readonly value="<?=$row['CUSTOMER_ID_NAME']?>" class="form-control"  id="txt_customer_id<?=$count?>" />
                    </td>

                    <td>
                        <select name="worker_job[]" class="form-control" id="txt_worker_job<?=$count?>" data-val="<?=$row['WORKER_JOB']?>" /><option></option></select>
                    </td>
                    <td><?=(isset($can_edit)?$can_edit:false)?'<i class="glyphicon glyphicon-remove delete_account"></i>':''?></td>
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
                    <a onclick="javascript:addRow();" href="javascript:;"><i class="glyphicon glyphicon-plus"></i>جديد</a>
                <?php } ?>
            </th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
        </tfoot>
    </table>
</div>
