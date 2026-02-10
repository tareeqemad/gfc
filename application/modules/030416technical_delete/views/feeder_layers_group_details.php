<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 05/01/16
 * Time: 01:29 م
 */
$count=0;

$MODULE_NAME= 'technical';
$TB_NAME= 'electricity_layers';
$group_create_url=base_url("$MODULE_NAME/$TB_NAME/group_create");
$group_delete_url=base_url("$MODULE_NAME/$TB_NAME/group_delete");

?>

<div class="tb_container">
    <table class="table" id="group_details_tb" data-container="container">
        <thead>
        <tr>
            <th style="width: 2%">#</th>
            <th style="width: 550px">المجموعة</th>
            <th style="width: 70px">حذف</th>
            <th></th>
        </tr>
        </thead>

        <tbody>

        <?php if(count($details) <= 0) {  // ادخال ?>
            <tr>
                <td><i class="glyphicon glyphicon-sort" /></i></td>
                <td>
                    <input type="hidden" name="g_ser[]" value="0" />
                    <input class="form-control" name="group_id_name[]" readonly id="txt_group_id<?=$count?>" />
                    <input  type="hidden" name="group_id[]" id="h_txt_group_id<?= $count ?>" />
                </td>
                <td><i class="glyphicon glyphicon-remove remove_group"></i></td>
                <td></td>
            </tr>

        <?php
        }else if(count($details) > 0) { // تعديل
            $count = -1;
            foreach($details as $row) {
                ?>
                <tr>
                    <td><?=++$count+1?></td>
                    <td>
                        <input type="hidden" name="g_ser[]" value="<?=$row['G_SER']?>" />
                        <input name="group_id_name[]" value="<?=$row['GROUP_ID_NAME']?>" class="form-control" readonly id="txt_group_id<?=$count?>" />
                        <input  type="hidden" name="group_id[]" value="<?=$row['GROUP_ID']?>" id="h_txt_group_id<?= $count ?>" >
                    </td>
                    <td><?=(HaveAccess($group_delete_url)?true:false)?'<i class="glyphicon glyphicon-remove delete_group"></i>':''?></td>
                    <td></td>
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
                <?php if ( HaveAccess($group_create_url) ) { ?>
                    <a onclick="javascript:group_addRow();" href="javascript:;"><i class="glyphicon glyphicon-plus"></i>جديد</a>
                <?php } ?>
            </th>
            <th></th>
            <th></th>
        </tr>
        </tfoot>
    </table>
</div>

<script>
    if (typeof initFunctions == 'function') {
        initFunctions();
    }
</script>
