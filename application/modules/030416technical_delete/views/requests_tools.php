<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 09/07/15
 * Time: 01:23 م
 */

$count=0;


?>

<div class="tb_container">
    <table class="table" id="toolsTbl" data-container="container">
        <thead>
        <tr>
            <th style="width: 2%">#</th>
            <th style="width: 8%">رقم الصنف</th>
            <th style="width: 25%">اسم الصنف</th>
            <th style="width: 8%">الكمية المستلمة</th>
            <th style="width: 50px;"></th>
        </tr>
        </thead>

        <tbody>

        <?php if(count($details) <= 0) {  // ادخال ?>
            <tr>
                <td><i class="glyphicon glyphicon-sort" /></i></td>
                <td>
                    <input type="hidden" name="tl_ser[]" value="0" />

                    <input  type="text" class="form-control" name="class_id[]"  id="h_txt_class_id_<?= $count ?>" >
                </td>
                <td>
                    <input name="class_name[]" readonly   class="form-control"  id="txt_class_id_<?=$count?>" />
                </td>
                <td>
                    <input name="class_count[]" class="form-control" id="txt_class_count_<?=$count?>" />
                </td>

                <td data-action="delete"  class="align-right"></td>
            </tr>

        <?php
        }else if(count($details) > 0) { // تعديل
            $count = -1;
            foreach($details as $row) {
                ?>
                <tr>
                    <td><?=++$count+1?></td>
                    <td>
                        <input type="hidden" name="tl_ser[]" value="<?=$row['SER']?>" />
                        <input  type="text" class="form-control" name="class_id[]" value="<?=$row['CLASS_ID']?>" id="h_txt_class_id_<?= $count ?>" >
                    </td>

                    <td>
                        <input name="class_name[]" readonly value="<?=$row['CLASS_ID_NAME']?>" class="form-control"  id="txt_class_id_<?=$count?>" />
                    </td>
                    <td>
                        <input name="class_count[]" class="form-control" id="txt_class_count_<?=$count?>" value="<?=$row['CLASS_COUNT']?>" />
                    </td>
                    <td data-action="delete" class="align-right">
                        <?php if ( $can_edit && HaveAccess(base_url('technical/Requests/delete_tools')) && $count > 0) : ?>
                            <a href="javascript:;" onclick="javascript:delete_details(this,<?= $row['SER'] ?>,'<?= base_url('technical/Requests/delete_tools') ?>');"><i class="icon icon-trash delete-action"></i> </a>
                        <?php endif; ?>
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
            <th colspan="3" class="align-right">
                <?php if (count($details) <=0 || ( (isset($can_edit)?$can_edit:false)  )) { ?>
                    <a onclick="javascript:add_row(this,'input',false);" href="javascript:;"><i class="glyphicon glyphicon-plus"></i>جديد</a>
                <?php } ?>
            </th>


        </tr>
        </tfoot>
    </table>
</div>
