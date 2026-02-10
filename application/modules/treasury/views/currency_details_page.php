<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 08/09/14
 * Time: 01:43 م
 */

/*
 * <td><div class='col-sm-8'>
        <input type='number' data-val='true' data-val-required='حقل مطلوب' name='curr_value[]' id='txt_curr_value' value='{$row['CURR_VALUE']}' class='form-control' maxlength='8'
        data-val-regex-pattern='[19]{1,8}' data-val-regex='ادخل ارقام فقط !!'  dir='ltr'>
        <span class='field-validation-valid' data-valmsg-for='curr_value' data-valmsg-replace='true'></span>
    </div></td>
*/

?>
<div class="tbl_container">
<table class="table" id="currency_details_tb" data-container="container">
    <thead>
    <tr>
        <th style="width: 7%" >م</th>
        <th style="width: 15%">العملة الرئيسية</th>
        <th style="width: 15%" >العملة</th>
        <th style="width: 15%" >القيمة</th>
        <th style="width: 20%">التاريخ</th>
        <th style="width: 20%">اخر تحديث</th>
        <th style="width: 7%">الغاء</th>
    </tr>
    </thead>

    <tbody>
    <?php
    $count=0;
    foreach ($get_list as $row){
        $count++;
        echo "
                            <tr data-id='{$count}'>
                                <td><div class='col-sm-3'>$count</div></td>
                                <td><div class='col-sm-7'>
                                    <span>{$row['MASTER_CURR_NAME']}</span>
                                    <input type='hidden' class='master_curr' name='master_curr[]' id='txt_master_curr' value='{$row['MASTER_CURR']}' >
                                </div></td>

                                <td><div class='col-sm-7'>
                                    <span>{$row['CURR_NAME']}</span>
                                    <input type='hidden' class='curr_id' name='curr_id[]' id='txt_curr_id' value='{$row['CURR_ID']}' >
                                </div></td>
                                <td><div class='col-sm-9'>
                                    <input type='number' data-val='true' name='curr_value[]' id='txt_curr_value' value='{$row['CURR_VALUE']}' class='form-control' maxlength='8' dir='ltr'>
                                    <input type='hidden' class='prev_curr_value' value='{$row['CURR_VALUE']}' >
                                </div></td>
                                <td><div class='col-sm-12'>{$row['DATE_BY_USER']}</div></td>
                                <td><div class='col-sm-12'>{$row['ENTRY_DATE']}</div></td>
                                <td><div class='col-sm-2'>
                                <a href='javascript:;' onclick='javascript:currency_details_hide({$count});'>
                                <i class='glyphicon glyphicon-remove''></i>
                                </a></div></td>
                            </tr>
                        ";
    }
    ?>
    </tbody>

</table>
</div>

