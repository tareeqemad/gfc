<?php
/**
 * Created by PhpStorm.
 * User: ljasser
 * Date: 17/09/19
 * Time: 09:47 ص
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
            <th>رقم المستند</th>
            <th>تاريخ المستند</th>
            <th>نسخة المستند</th>
            <th>بيان المستند</th>
            <th>المبلغ</th>
            <th hidden="">الارشفة الالكترونية</th>

        </tr>
        </thead>

        <tbody>

        <?php  if(count($rows) <= 0) : ?>
            <tr>
                <td><?=$count++?></td>

                <td><input type="text" data-val="true"
                           name="document_number[]" id="txt_document_number<?=$count?>"   class="form-control"></td>
                <td><input type="text"  data-type="date" data-date-format="DD/MM/YYYY"
                           name="document_date[]"
                           id="txt_document_date_<?=$count?>"class="form-control ltr"></td>


                <td><select type="text" name="copy_the_document[]"  id="dp_copy_the_document_<?=$count?>" class="form-control">
                        <?php foreach ($copy_the_document as $rows) : ?>
                            <option value="<?= $rows['CON_NO'] ?>"><?= $rows['CON_NAME'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>












                <td><input type="text" name="document_statement[]" id="txt_document_statement_<?=$count?>" class="form-control"></td>



                <td><input type="text"   name="amount[]"   id="txt_AMOUNT_<?=$count?>" class="form-control"></td>
                <td hidden=""><input type="text"  name="electronic_archive[]"
                                     id="txt_ELECTRONIC_ARCHIVE_<?=$count?>" class="form-control"></td>

            </tr>



        <?php else:  ?>

            <tr>
                <td><?=$count++?></td>

                <td><input type="text" data-val="true"
                           name="document_number[]" id="txt_document_number_<?=$count?>"
                           value="<?= $HaveRs ? $rs['DOCUMENT_NUMBER'] : "" ?>"
                           class="form-control"></td>

                <td><input type="text"  data-type="date" data-date-format="DD/MM/YYYY"
                           name="document_date[]" value="<?= $HaveRs ? $rs['DOCUMENT_DATE'] : "" ?>"
                           id="txt_document_date_<?=$count?>"class="form-control ltr"></td>



                <td><select type="text" name="copy_the_document[]"  value="<?= $HaveRs ? $rs['COPY_THE_DOCUMENT'] : "" ?>"
                            id="dp_copy_the_document_<?=$count?>" class="form-control">
                        <?php foreach ($copy_the_document as $rows) : ?>
                            <option value="<?= $rows['CON_NO'] ?>"><?= $rows['CON_NAME'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>





                <td><input type="text" name="document_statement[]" value="<?= $HaveRs ? $rs['DOCUMENT_STATEMENT'] : "" ?>"
                           id="txt_document_statement_<?=$count?>" class="form-control"></td>







                <td><input type="text"   name="amount[]" value="<?= $HaveRs ? $rs['AMOUNT'] : "" ?>"
                           id="txt_AMOUNT_<?=$count?>" class="form-control"></td>

                <td hidden=""><input type="text"  name="electronic_archive[]" value="<?= $HaveRs ? $rs['ELECTRONIC_ARCHIVE'] : "" ?>"
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