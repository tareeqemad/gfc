<?php
/**
 * Created by PhpStorm.
 * User: ljasser
 * Date: 10/07/19
 * Time: 10:19 ص
 */

$count = 0;

if (!isset($result))
    $result = array();
$HaveRs = count($result) > 0;
$rs = $HaveRs ? $result[0] : $result;
?>
<div class="tbl_container">
    <table class="table" id="REC_DISTbl" data-container="container">
        <thead>
        <tr>

            <th  >#</th>
            <th>رقم السند </th>
            <th>  تاريخ السند </th>
            <th>   نوع السند</th>
            <th> بيان السند</th>
            <th> المبلغ </th>
            <th hidden=""> الارشفة الالكترونية </th >

        </tr>
        </thead>

        <?php  if(count($rows) <= 0) : ?>
            <tr>
                <td><?=$count++?></td>

                <td><input type="text" data-val="true" name="re_dis_ser[]"
                           id="txt_RE_DIS_SER_<?=$count?>"  class="form-control"></td>



                <td><input type="text"  data-type="date" data-date-format="DD/MM/YYYY"
                           name="re_dis_date[]" id="txt_RE_DIS_DATE_<?=$count?>"  class="form-control"></td>

                <td><select type="text"data-val="true" name="type[]" id="dp_type_<?=$count?>"  class="form-control">

                        <?php foreach ($TYPE as $rows) : ?>
                            <option value="<?= $rows['CON_NO'] ?>"><?= $rows['CON_NAME'] ?></option>
                        <?php endforeach; ?>
                   </td>




                <td><input type="text"  name="body[]"data-val="true" id="txt_BODY_<?=$count?>"
                           class="form-control"></td>


                <td><input type="text"   name="amount[]"   id="txt_AMOUNT_<?=$count?>" class="form-control"></td>
                <td hidden=""><input type="text"  name="electronic_archive[]"
                           id="txt_ELECTRONIC_ARCHIVE_<?=$count?>" class="form-control"></td>
            </tr>

        <?php else:  ?>


        <tr>
            <td><?=$count++?></td>

            <td><input type="text" data-val="true" name="re_dis_ser[]"
                       id="txt_RE_DIS_SER_<?=$count?>"  value="<?= $HaveRs ? $rs['RE_DIS_SER'] : "" ?>"
                       class="form-control"></td>



            <td><input type="text"  data-type="date" data-date-format="DD/MM/YYYY"
                       value="<?= $HaveRs ? $rs['RE_DIS_DATE'] : "" ?>"
                       name="re_dis_date[]" id="txt_RE_DIS_DATE_<?=$count?>"  class="form-control"></td>

            <td><select type="text"data-val="true" name="type[]" id="dp_type_<?=$count?>"  class="form-control">

                    <?php foreach ($TYPE as $rows) : ?>
                        <option value="<?= $rows['CON_NO'] ?>"><?= $rows['CON_NAME'] ?></option>
                    <?php endforeach; ?>
            </td>




            <td><input type="text"  name="body[]"  value="<?= $HaveRs ? $rs['BODY'] : "" ?>"
                       data-val="true" id="txt_BODY_<?=$count?>"
                       class="form-control"></td>


            <td><input type="text"   name="amount[]"  value="<?= $HaveRs ? $rs['AMOUNT'] : "" ?>"
                       id="txt_AMOUNT_<?=$count?>" class="form-control"></td>
            <td hidden=""><input type="text"  name="electronic_archive[]" value="<?= $HaveRs ? $rs['ELECTRONIC_ARCHIVE'] : "" ?>"
                       id="txt_ELECTRONIC_ARCHIVE_<?=$count?>" class="form-control"></td>
        </tr>
        <?php endif; ?>




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