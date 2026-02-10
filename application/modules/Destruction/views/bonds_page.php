<?php
/**
 * Created by PhpStorm.
 * User: ljasser
 * Date: 09/07/19
 * Time: 08:43 ص
 */



$count = 0;

if (!isset($result))
    $result = array();
$HaveRs = count($result) > 0;
$rs = $HaveRs ? $result[0] : $result;

?>
<div class="tbl_container">
    <table class="table" id="carsTbl" data-container="container">
        <thead>
        <tr>
            <th  >#</th>
            <th>رقم القيد</th>
            <th> نوع القيد </th>
            <th>بيان القيد</th>
            <th> تاريخ القيد </th>
            <th>المبلغ </th>
            <th hidden="">الارشفة الالكترونية  </th>

        </tr>
        </thead>

        <tbody>

        <?php  if(count($rows) <= 0) : ?>
            <tr>
                <td><?=$count++?></td>

                <td><input type="text" data-val="true"
                    name="bond_ser[]" id="txt_bond_ser_<?=$count?>"   class="form-control"></td>

                <td><select type="text" name="bond_type[]"  id="dp_bond_type_<?=$count?>" class="form-control">
                        <?php foreach ($BOND_TYPE as $rows) : ?>
                            <option value="<?= $rows['CON_NO'] ?>"><?= $rows['CON_NAME'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>




                <td><input type="text" name="bond_body[]" id="txt_BOND_BODY_<?=$count?>" class="form-control"></td>

                <td><input type="text"  data-type="date" data-date-format="DD/MM/YYYY"
                           name="bond_date[]"
                           id="txt_BOND_DATE_<?=$count?>"class="form-control ltr"></td>





                <td><input type="text"   name="amount[]"   id="txt_AMOUNT_<?=$count?>" class="form-control"></td>
                <td hidden=""><input type="text"  name="electronic_archive[]"
                           id="txt_ELECTRONIC_ARCHIVE_<?=$count?>" class="form-control"></td>

            </tr>
        


        <?php else:  ?>

            <tr>
                <td><?=$count++?></td>

                <td><input type="text" data-val="true"
                           name="bond_ser[]" id="txt_bond_ser_<?=$count?>"
                           value="<?= $HaveRs ? $rs['BOND_SER'] : "" ?>"
                           class="form-control"></td>





                <td><select type="text" name="bond_type[]"  value="<?= $HaveRs ? $rs['BOND_TYPE'] : "" ?>"
                            id="dp_bond_type_<?=$count?>" class="form-control">
                        <?php foreach ($BOND_TYPE as $rows) : ?>
                            <option value="<?= $rows['CON_NO'] ?>"><?= $rows['CON_NAME'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>




                <td><input type="text" name="bond_body[]" value="<?= $HaveRs ? $rs['BOND_BODY'] : "" ?>"
                           id="txt_BOND_BODY_<?=$count?>" class="form-control"></td>

                <td><input type="text"  data-type="date" data-date-format="DD/MM/YYYY"
                           name="bond_date[]" value="<?= $HaveRs ? $rs['BOND_DATE'] : "" ?>"
                           id="txt_BOND_DATE_<?=$count?>"class="form-control ltr"></td>





                <td><input type="text"   name="amount[]" value="<?= $HaveRs ? $rs['AMOUNT'] : "" ?>"
                           id="txt_AMOUNT_<?=$count?>" class="form-control"></td>
                <td hidden=""><input type="text"  name="electronic_archive[]" value="<?= $HaveRs ? $rs['ELECTRONIC_ARCHIVE'] : "" ?>" hidden=""
                           id="txt_ELECTRONIC_ARCHIVE_<?=$count?>" class="form-control"></td>



            </tr>

        <?php endif; ?>

        </tbody>



        </tr>




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