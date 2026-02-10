<?php
/**
 * Created by PhpStorm.
 * User: abarakat
 * Date: 23/05/18
 * Time: 10:47 ص
 */
$count = 0;
$DetailsCount = 1;
?>

<div class="tb_container">
    <table class="table" id="aeltTbl" action="after_existing_load_transformer" data-container="container">
        <thead>

        <tr>
            <th>#</th>
            <th>Transformer Name</th>
            <th>KVA Rating</th>
            <th>MV Feeder Name</th>

            <th></th>
            <th style="width: 80px;direction: ltr;">Maximum Phase Loading After Load Transfer%</th>

            <th></th>
            <th style="width: 50px;"></th>
        </tr>
        </thead>

        <tbody>

        <?php if (count($details) <= 0) { // ادخال ?>


            <?php
        } else if (count($details) > 0) { // تعديل
            $count = -1;
            foreach ($details as $row) {
                ?>
                <tr data-root="true">
                    <td><input name="tad_ser[]" value="<?= $row['SER'] ?>" type="hidden"/></td>
                    <td><input name="tad_name[]" value="<?= $row['NAME'] ?>" data-val="true"
                               data-val-required="required" class="form-control"/></td>
                    <td>
                        <select name="tad_kva_rating[]" data-val="true" data-val-required="required"
                                class="form-control">
                            <?php foreach ($POWER_ADAPTER as $r) : ?>
                                <option <?= $row['KVA_RATING'] == $r['CON_NAME'] ? "selected" : "" ?>
                                        value="<?= $r['CON_NAME'] ?>"><?= $r['CON_NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td>
                        <select name="tad_mv_feeder_name[]"
                                data-val-required="required"
                                data-val="true"
                                class="form-control">
                            <option></option>
                            <?php foreach ($feeder_name as $r) : ?>
                                <option
                                    <?= $row['MV_FEEDER_NAME'] == $r['CON_NO'] ? 'selected' : '' ?>
                                        value="<?= $r['CON_NO'] ?>"><?= $r['CON_NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td>
                        <table class="table">
                            <thead>
                            <tr>
                                <th style="width: 150px;direction: ltr;">LV CB Direction</th>
                                <th style="width: 150px;direction: ltr;">Cross Section</th>
                                <th style="width: 80px;direction: ltr;">Length (m)</th>
                                <th style="width: 80px;direction: ltr;">R (A)</th>
                                <th style="width: 80px;direction: ltr;">S (A)</th>
                                <th style="width: 80px;direction: ltr;">T (A)</th>
                                <th style="width: 80px;direction: ltr;">N (A)</th>

                            </tr>
                            </thead>
                            <tbody>
                            <?php if (count($row['details']) <= 0): ?>
                                <tr>
                                    <td style="width: 145px">
                                        <input name="tad_d_ser[<?= $DetailsCount ?>][]" value="0" type="hidden"/>
                                        <select name="tad_lv_cb_direction[<?= $DetailsCount ?>][]"
                                                data-val-required="required"
                                                data-val="true"
                                                class="form-control">
                                            <?php foreach ($lv_cb_direction as $r) : ?>
                                                <option value="<?= $r['CON_NO'] ?>"><?= $r['CON_NAME'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                    <td style="width: 150px">
                                        <select name="tad_cross_sections[<?= $DetailsCount ?>][]"
                                                data-val-required="required"
                                                data-val="true"
                                                class="form-control">
                                            <option></option>
                                            <?php foreach ($items_lower as $r) : ?>
                                                <option value="<?= $r['CLASSID'] ?>"
                                                        data-type="<?= $r['TYPE'] ?>"
                                                ><?= $r['CLASS_ID_NAME'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                    <td><input name="tad_length[<?= $DetailsCount ?>][]" data-val="true" data-val-required="required"
                                               class="form-control"/></td>
                                    <td><input name="tad_r[<?= $DetailsCount ?>][]" data-val="true" data-val-required="required"
                                               class="form-control"/></td>

                                    <td><input name="tad_s[<?= $DetailsCount ?>][]" data-val="true" data-val-required="required"
                                               class="form-control"/></td>
                                    <td><input name="tad_t[<?= $DetailsCount ?>][]" data-val="true" data-val-required="required"
                                               class="form-control"/></td>
                                    <td><input name="tad_n[<?= $DetailsCount ?>][]" data-val="true" data-val-required="required"
                                               class="form-control"/></td>


                                </tr>
                            <?php endif; ?>
                            <?php foreach ($row['details'] as $dRow) : ?>
                                <tr>
                                    <td style="width: 145px">
                                        <input name="tad_d_ser[<?= $DetailsCount ?>][]" value="<?= $dRow['SER'] ?>" type="hidden"/>
                                        <select name="tad_lv_cb_direction[<?= $DetailsCount ?>][]"
                                                data-val-required="required"
                                                data-val="true"
                                                class="form-control">
                                            <?php foreach ($lv_cb_direction as $r) : ?>
                                                <option <?= $dRow['LV_CD_DIRECTION'] == $r['CON_NO'] ? 'selected' : '' ?>
                                                        value="<?= $r['CON_NO'] ?>"><?= $r['CON_NAME'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                    <td style="width: 150px">
                                        <select name="tad_cross_sections[<?= $DetailsCount ?>][]"
                                                data-val-required="required"
                                                data-val="true"
                                                class="form-control">
                                            <option></option>
                                            <?php foreach ($items_lower as $r) : ?>
                                                <option
                                                    <?= $dRow['CROSS_SECTION'] == $r['CLASSID'] ? 'selected' : '' ?>
                                                        value="<?= $r['CLASSID'] ?>"
                                                        data-type="<?= $r['TYPE'] ?>"
                                                ><?= $r['CLASS_ID_NAME'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                    <td><input name="tad_length[<?= $DetailsCount ?>][]" value="<?= $dRow['LENGTH'] ?>" data-val="true"
                                               data-val-required="required"
                                               class="form-control"/></td>
                                    <td><input name="tad_r[<?= $DetailsCount ?>][]" value="<?= $dRow['R'] ?>" data-val="true"
                                               data-val-required="required"
                                               class="form-control"/></td>

                                    <td><input name="tad_s[<?= $DetailsCount ?>][]" value="<?= $dRow['S'] ?>" data-val="true"
                                               data-val-required="required"
                                               class="form-control"/></td>
                                    <td><input name="tad_t[<?= $DetailsCount ?>][]" value="<?= $dRow['T'] ?>" data-val="true"
                                               data-val-required="required"
                                               class="form-control"/></td>
                                    <td><input name="tad_n[<?= $DetailsCount ?>][]" value="<?= $dRow['N'] ?>" data-val="true"
                                               data-val-required="required"
                                               class="form-control"/></td>


                                </tr>


                            <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                            <tr>

                                <th>
                                    <a onclick="javascript:add_row(this,'input,td[data-expected]',false);"
                                       href="javascript:;"><i
                                                class="glyphicon glyphicon-plus"></i>جديد</a>

                                </th>
                                <th colspan="8"></th>

                            </tr>
                            </tfoot>
                        </table>
                    </td>
                    <td data-expected="true"></td>
                    <td></td>
                    <td data-action="delete"></td>
                </tr>
                <?php
                $DetailsCount++;
            }
        }
        ?>

        </tbody>

    </table>
</div>

