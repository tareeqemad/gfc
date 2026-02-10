<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: tekrayem
 * Date: 03/12/14
 * Time: 11:21 ص
 */
$count1 = 0;
$delete_groups_url=base_url('stores/receipt_class_input_group/delete');
?>

<div class="tbl_container">
    <table class="table" id="receipt_class_input_groupTbl" data-container="container">
        <thead>
        <tr>
            <th   > #</th>
            <th > الرقم الوظيفي </th>
            <th > رقم الهوية</th>
            <th >اسم العضو</th>
            <th >ملاحظات العضو </th>
            <th >حضور؟</th>
        </tr>
        </thead>
        <tbody>

        <?php  if(count($rec_groups) <= 0) : ?>
            <tr>
                <td ><?= ($count1+1) ?>

                    <input type="hidden" name="h_group_ser[]" id="h_group_ser_<?= $count1 ?>"  value="0" class="form-control col-sm-3">


                </td>
                <td>

                    <input type="text" name="emp_no[]" data-val="true"  data-val-required="حقل مطلوب"   id="emp_no_<?= $count1 ?>"  class="form-control col-sm-8">

                </td>
                <td>

                    <input type="text" name="group_person_id[]" data-val="true"  data-val-required="حقل مطلوب"   id="group_person_id_<?= $count1 ?>"  class="form-control col-sm-8">

                </td>
                <td><input type="text" name="group_person_date[]"  data-val="true"   id="group_person_date_<?= $count1 ?>"  data-val-required="حقل مطلوب"   data-val-regex="المدخل غير صحيح!"   class="form-control"></td>
                <td><input type="text" name="member_note[]"  data-val="true"   id="member_note_<?= $count1 ?>"   class="form-control"></td>

                <td><input type="checkbox" name="status[<?= $count1 ?>]" value="1"  checked   id="status_<?= $count1 ?>"    class="form-control">
                </td>
            </tr>
        <?php else: $count1 = -1; ?>
        <?php endif; ?>

        <?php foreach($rec_groups as $row1) :?>
            <?php $count1++; ?>
            <tr>
                <td><?=( $count1+1) ?>
                    <input type="hidden" id="h_group_ser_<?= $count1 ?>" name="h_group_ser[]" value="<?= $row1['SER'] ?>">


                </td>
                <td>

                    <input type="text" name="emp_no[]" data-val="true"  value="<?= $row1['EMP_NO'] ?>" data-val-required="حقل مطلوب"   id="emp_no_<?= $count1 ?>"  class="form-control col-sm-8">

                </td>
                <td>
                    <input type="text" name="group_person_id[]"  value="<?= $row1['GROUP_PERSON_ID'] ?>"  id="group_person_id_<?= $count1 ?>"   class="form-control col-sm-8">
                     </td>
                <td><input  type="text"  name="group_person_date[]"  data-val="true"   id="group_person_date_<?= $count1 ?>"  value="<?= $row1['GROUP_PERSON_DATE'] ?>"  class="form-control"></td>
                <td><input  type="text"  name="member_note[]"  data-val="true"   id="member_note_<?= $count1 ?>"  value="<?= $row1['MEMBER_NOTE'] ?>"  class="form-control"></td>

                <td><input type="checkbox" name="status[<?= $count1 ?>]" <?php if($row1['STATUS']==1) echo "checked"; ?> value="<?php if($row1['STATUS']==1) echo "1"; else echo "2"; ?>"     id="status_<?= $count1 ?>"   class="form-control">
                </td>

             <!--   <td><?php  //if( HaveAccess($delete_groups_url)):  ?>

                        <a onclick="javascript:receipt_class_input_group_tb_delete(this,<?= $row1['SER'] ?>);" href="javascript:;"><i class="icon icon-trash delete-action"></i> </a>

                    <?php// endif; ?></td>-->
            </tr>
        <?php endforeach;?>

        </tbody>
        <tfoot>
        <tr>
            <th  class="align-right" colspan="6">
                <?php if (count($rec_groups) <=0 || $action=='edit' ) ://if (count($chains_details) <=0 || ( isset($can_edit)?$can_edit:false)) : ?>
                    <a onclick="javascript:addRowGroup();" onfocus="javascript:addRowGroup();" href="javascript:;"><i class="glyphicon glyphicon-plus"></i>جديد</a>
                <?php endif; ?>
            </th>



        </tr></tfoot>
    </table>

</div>


<script>
    if (typeof initFunctions == 'function') {
        initFunctions();
    }


</script>