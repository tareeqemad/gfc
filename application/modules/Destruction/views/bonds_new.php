<?php
/**
 * Created by PhpStorm.
 * User: ljasser
 * Date: 09/07/19
 * Time: 08:43 ص
 */
$count = 0;/*
if (!isset($result))
    $result = array();
$HaveRs = count($result) > 1;
$rs = $HaveRs ? $result[0] : '';

var_dump($details);
die;
*/
?>
<div class="tbl_container">
    <table class="table" id="carsTbl" data-container="container">
        <thead>
        <tr>
            <th  >#</th>
            <th>رقم القيد</th>
            <th> تاريخ القيد </th>
            <th> نوع القيد </th>
            <th>بيان القيد</th>

            <th>الأرشفة الالكترونية<th>
        </tr>
        </thead>
        <tbody>
        <?php if(@$page > 1): ?>
            <tr>
                <td colspan="11" id="page-<?= @$page ?>" class="page-sector" data-page="<?= @$page ?>"></td>
            </tr>
        <?php endif; ?>

        <?php foreach($details as $row) :?>
            <tr  >
                <td><?= $count ?></td>
                <td><input type="text"  name="bond_ser[]" value="<?= $row['BOND_SER']?>"
                           id="txt_BOND_SER" class="form-control"></td>




                <td><input type="text"  data-type="date" data-date-format="DD/MM/YYYY"
                           name="bond_date[]" value="<?= $row['BOND_DATE']?>"
                           id="txt_BOND_DATE"class="form-control ltr"></td>


                <td><select type="text" name="bond_type[]" id="dp_bond_type" class="form-control">

                        <?php foreach ($BOND_TYPE as $rows) : ?>
                            <option value="<?= $rows['CON_NO'] ?>" <?= $row['BOND_TYPE'] == $rows['CON_NO'] ? "selected" : "" ?> ><?= $rows['CON_NAME'] ?></option>
                        <?php endforeach; ?>
                    </select></td>


                <td><input type="text"  value="<?= $row['BOND_BODY']?>" name="bond_body[]" id="txt_BOND_BODY" class="form-control"></td>

                <td><input type="text"  value="<?= $row['AMOUNT']?>"  name="amount[]"   id="txt_AMOUNT" class="form-control"></td>

                <td><input type="text"  value="<?= $row['ELECTRONIC_ARCHIVE']?>"  name="electronic_archive[]"
                           id="txt_ELECTRONIC_ARCHIVE" class="form-control"></td>
            </tr>
            <?php $count++ ?>
            </tr>
        <?php endforeach;?>


        <?php $count++ ?>
        </tr>


        <?php ?>





        </tbody>
    </table>
</div>


<script>
    if (typeof initFunctions == 'function') {
        initFunctions();
    }
    if (typeof ajax_pager == 'function') {
        ajax_pager();
    }



</script>