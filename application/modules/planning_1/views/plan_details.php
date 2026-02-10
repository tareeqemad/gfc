<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 20/11/17
 * Time: 10:18 ص
 */
$MODULE_NAME= 'planning';
$TB_NAME= 'planning';
$isCreate =isset($details) && count($details)  > 0 ?false:true;
$delete_url_details= base_url("$MODULE_NAME/$TB_NAME/delete_details");
$count=0;
//var_dump($details);
?>


    <div class="tb_container">
        <table class="table" id="details_tb1" data-container="container" style="width:50%" align="center">
            <thead>
            <tr>
                <th >المقر</th>
                <th >الحصة</th>
                <?php if ( HaveAccess($delete_url_details) && (!$isCreate )) : ?>
                <th >حذف</th>
                <?php endif; ?>
            </tr>
            </thead>

            <tbody>
            <?php if(count($details) <= 0) {  // ادخال ?>
<tr>
                <td>

                   <select name="branch[]" id="dp_branch_<?=$count?>" data-curr="false" class="form-control">
                        <option  value="">-</option>
                        <?php  foreach ($branches as $row1) : ?>
                            <option  value="<?= $row1['NO'] ?>" ><?= $row1['NAME'] ?></option>
                        <?php endforeach; ?>
                    </select>

                </td>
                <td>
                    <input class="form-control" name="part[]" id="txt_part_<?=$count?>" data-val="false" data-val-required="required" />
                    <input  type="hidden" name="activity_no[]"  id="h_txt_activity_no_<?= $count ?>" data-val="false" data-val-required="required" >
                    <input type="hidden" name="ser1[]" value="0"  />

                </td>
                <?php if ( HaveAccess($delete_url_details) && (!$isCreate )) : ?>
                <td>


                </td>
                <?php endif; ?>
    </tr>
<?php
}else if(count($details) > 0) { // تعديل
                $count = -1;
            foreach($details as $row) {
                ++$count+1


                ?>

                <tr>
                    <td>

                        <select name="branch[]" id="dp_branch_<?=$count?>" data-curr="false" class="form-control">
                            <option  value="">-</option>
                            <?php  foreach ($branches as $row1) : ?>
                                <option  value="<?= $row1['NO'] ?>" <?PHP if ($row1['NO']==$row['BRANCH']){ echo " selected"; } ?>><?= $row1['NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>

                    </td>
                    <td>
                        <input class="form-control" name="part[]" value="<?=$row['PART']?>" id="txt_part_<?=$count?>" data-val="false" data-val-required="required" />
                        <input  type="hidden" name="activity_no[]" value="<?=$row['ACTIVITY_NO']?>" id="h_txt_activity_no_<?= $count ?>" data-val="false" data-val-required="required" >
                        <input type="hidden" name="ser1[]" value="<?=$row['SEQ1']?>" />
                      </td>
                <?php if ( HaveAccess($delete_url_details) && (!$isCreate )) : ?>
                    <td>
                        <a onclick="javascript:delete_row_del('<?=$row['SEQ1']?>','<?=$row['BRANCH_NAME']?>');"  href='javascript:;'><i class='glyphicon glyphicon-remove'></i></a>

                    </td>
                <?php endif; ?>

                </tr>
            <?php

            }
        }

            ?>
</tbody>

            <tfoot>
            <tr>
                <th>
                    الاجمالي الكلي لحصص المقرات
                </th>
                <th id="total_part_branches"></th>
                <?php if ( HaveAccess($delete_url_details) && (!$isCreate )) : ?>
                    <th></th>
                <?php endif; ?>
            </tr>
            <tr>
                <th>
                    <a onclick="javascript:add_row(this,'input:text,input:hidden[name^=ser],textarea,select',false);" onfocus="javascript:add_row(this,'input:text,input:hidden[name^=ser],textarea,select',false);" href="javascript:;"><i class="glyphicon glyphicon-plus"></i>جديد</a>
                </th>
                <th></th>
                <?php if ( HaveAccess($delete_url_details) && (!$isCreate )) : ?>
                    <th></th>
                <?php endif; ?>
            </tr>

            </tfoot>
        </table></div>
