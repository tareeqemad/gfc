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
    <table class="table" id="eltTable" action="existing_load_transformer" data-container="container">
        <thead>

        <tr>
            <th>#</th>
            <th style="width: 150px">Transformer Name</th>
            <th style="width: 80px">KVA Rating</th>
            <th style="width: 80px">MV Feeder Name</th>
            <th></th>
            <th style="width: 80px;direction: ltr;">Maximum Phase Loading Before Load Transfer%</th>
            <th></th>
            <th style="width: 50px;"></th>
        </tr>
        </thead>

        <tbody>

        <?php if (count($details) <= 0) { // ادخال ?>
            <tr data-root="true">
                <td>
                    <?= $count + 1 ?>
                    <input name="tbd_ser[]" value="0" type="hidden"/>
                </td>
                <td><input name="tbd_name[]" data-val="true" data-val-required="required" class="form-control"/></td>
                <td>
                    <select name="tbd_kva_rating[]" data-val="true" data-val-required="required" class="form-control">
                        <option></option>
                        <?php foreach ($POWER_ADAPTER as $r) : ?>
                            <option value="<?= $r['CON_NAME'] ?>"><?= $r['CON_NAME'] ?></option>
                        <?php endforeach; ?>
                    </select>

                </td>
                <td>
                    <select name="tbd_mv_feeder_name[]"
                            data-val-required="required"
                            data-val="true"
                            class="form-control">
                        <option></option>
                        <?php foreach ($feeder_name as $row) : ?>
                            <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td>
                    <table class="table" data-allow-count="6">
                        <thead>
                        <tr>
                            <th style="width: 150px;direction: ltr;">LV CB Direction</th>
                            <th style="width: 150px;direction: ltr;">Cross Section</th>
                            <th style="width: 80px;direction: ltr;">Length (m)</th>
                            <th style="width: 80px;direction: ltr;">R (A)</th>
                            <th style="width: 80px;direction: ltr;">S (A)</th>
                            <th style="width: 80px;direction: ltr;">T (A)</th>
                            <th style="width: 80px;direction: ltr;">N (A)</th>
                            <th style="width: 80px;direction: ltr;">RS (V)</th>
                            <th style="width: 80px;direction: ltr;">ST (V)</th>
                            <th style="width: 80px;direction: ltr;">RT (V)</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td style="width: 145px">
                                <input name="tbd_d_ser[1][]" value="0"
                                       type="hidden"/>
                                <select name="tbd_lv_cb_direction[1][]"
                                        data-val-required="required"
                                        data-val="true"
                                        class="form-control">
                                    <option></option>
                                    <?php foreach ($lv_cb_direction as $row) : ?>
                                        <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td style="width: 150px">
                                <select name="tbd_cross_sections[1][]"
                                        data-val-required="required"
                                        data-val="true"
                                        class="form-control">
                                    <option></option>
                                    <?php foreach ($items_lower as $row) : ?>
                                        <option value="<?= $row['CLASSID'] ?>"
                                                data-type="<?= $row['TYPE'] ?>"
                                        ><?= $row['CLASS_ID_NAME'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td><input name="tbd_length[1][]" data-val="true" data-val-required="required"
                                       class="form-control"/></td>
                            <td><input name="tbd_r[1][]" data-val="true" data-val-required="required"
                                       class="form-control"/></td>

                            <td><input name="tbd_s[1][]" data-val="true" data-val-required="required"
                                       class="form-control"/></td>
                            <td><input name="tbd_t[1][]" data-val="true" data-val-required="required"
                                       class="form-control"/></td>
                            <td><input name="tbd_n[1][]" data-val="true" data-val-required="required"
                                       class="form-control"/></td>
                            <td><input name="tbd_rs[1][]" data-val="true" data-val-required="required"
                                       class="form-control"/></td>
                            <td><input name="tbd_st[1][]" data-val="true" data-val-required="required"
                                       class="form-control"/></td>
                            <td><input name="tbd_rt[1][]" data-val="true" data-val-required="required"
                                       class="form-control"/></td>

                            <td data-action="delete"><a href="javascript:;"
                                                        onclick="javascript:deleteTransformData(this,0,'');"><i
                                            class="icon icon-trash delete-action"></i> </a></td>

                        </tr>
                        </tbody>
                        <tfoot>
                        <tr>

                            <th>
                                <a onclick="javascript:add_row(this,'input,td[data-expected]',false);"
                                   href="javascript:;"><i
                                            class="glyphicon glyphicon-plus"></i>جديد</a>

                            </th>
                            <th colspan="11"></th>

                        </tr>
                        </tfoot>
                    </table>
                </td>
                <td data-expected="true"></td>
                <td></td>
                <td>
                    <a href="javascript:;"
                       onclick="javascript:deleteTransformData(this,0,'');"><i
                                class="icon icon-trash delete-action"></i> </a></td>
            </tr>

            <?php
        } else if (count($details) > 0) { // تعديل

            foreach ($details as $row) {
                ?>

                <tr data-root="true">
                    <td> <?= $count + 1 ?><input name="tbd_ser[]" value="<?= $row['SER'] ?>" type="hidden"/></td>
                    <td><input name="tbd_name[]" data-val="true" data-val-required="required"
                               value="<?= $row['NAME'] ?>" class="form-control"/></td>
                    <td>
                        <select name="tbd_kva_rating[]" data-val="true" data-val-required="required"
                                class="form-control">
                            <option></option>
                            <?php foreach ($POWER_ADAPTER as $r) : ?>
                                <option <?= $row['KVA_RATING'] == $r['CON_NAME'] ? "selected" : "" ?>
                                        value="<?= $r['CON_NAME'] ?>"><?= $r['CON_NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>

                    </td>
                    <td>
                        <select name="tbd_mv_feeder_name[]"
                                data-val-required="required"
                                data-val="true"
                                class="form-control">
                            <option></option>
                            <?php foreach ($feeder_name as $rw) : ?>
                                <option
                                    <?= $row['MV_FEEDER_NAME'] == $rw['CON_NO'] ? 'selected' : '' ?>
                                        value="<?= $rw['CON_NO'] ?>"
                                ><?= $rw['CON_NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td>
                        <table class="table" action="existingblm_details_tb" data-allow-count="6">
                            <thead>
                            <tr>
                                <th style="width: 150px;direction: ltr;">LV CB Direction</th>
                                <th style="width: 150px;direction: ltr;">Cross Section</th>
                                <th style="width: 80px;direction: ltr;">Length (m)</th>
                                <th style="width: 80px;direction: ltr;">R (A)</th>
                                <th style="width: 80px;direction: ltr;">S (A)</th>
                                <th style="width: 80px;direction: ltr;">T (A)</th>
                                <th style="width: 80px;direction: ltr;">N (A)</th>
                                <th style="width: 80px;direction: ltr;">RS (V)</th>
                                <th style="width: 80px;direction: ltr;">ST (V)</th>
                                <th style="width: 80px;direction: ltr;">RT (V)</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>

                            <?php if (count($row['details']) <= 0): ?>

                                <tr>
                                    <td style="width: 145px">
                                        <input name="tbd_d_ser[1][]" value="0"
                                               type="hidden"/>
                                        <select name="tbd_lv_cb_direction[1][]"
                                                data-val-required="required"
                                                data-val="true"
                                                class="form-control">
                                            <option></option>
                                            <?php foreach ($lv_cb_direction as $r) : ?>
                                                <option value="<?= $r['CON_NO'] ?>"><?= $r['CON_NAME'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                    <td style="width: 150px">
                                        <select name="tbd_cross_sections[1][]"
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
                                    <td><input name="tbd_length[1][]" data-val="true" data-val-required="required"
                                               class="form-control"/></td>
                                    <td><input name="tbd_r[1][]" data-val="true" data-val-required="required"
                                               class="form-control"/></td>
                                    <td><input name="tbd_s[1][]" data-val="true" data-val-required="required"
                                               class="form-control"/></td>
                                    <td><input name="tbd_t[1][]" data-val="true" data-val-required="required"
                                               class="form-control"/></td>
                                    <td><input name="tbd_n[1][]" data-val="true" data-val-required="required"
                                               class="form-control"/></td>
                                    <td><input name="tbd_rs[1][]" data-val="true" data-val-required="required"
                                               class="form-control"/></td>
                                    <td><input name="tbd_st[1][]" data-val="true" data-val-required="required"
                                               class="form-control"/></td>
                                    <td><input name="tbd_rt[1][]" data-val="true" data-val-required="required"
                                               class="form-control"/></td>
                                    <td data-action="delete"><a href="javascript:;"
                                                                onclick="javascript:deleteTransformData(this,0,'');"><i
                                                    class="icon icon-trash delete-action"></i> </a></td>
                                </tr>
                            <?php endif; ?>
                            <?php foreach ($row['details'] as $dRow) : ?>
                                <tr>
                                    <td style="width: 145px">
                                        <input name="tbd_d_ser[<?= $DetailsCount ?>][]" value="<?= $dRow['SER'] ?>"
                                               type="hidden"/>

                                        <select name="tbd_lv_cb_direction[<?= $DetailsCount ?>][]"
                                                data-val-required="required"
                                                data-val="true"
                                                class="form-control">
                                            <option></option>
                                            <?php foreach ($lv_cb_direction as $rw) : ?>
                                                <option <?= $dRow['LV_CB_DIRECTION'] == $rw['CON_NO'] ? 'selected' : '' ?>
                                                        value="<?= $rw['CON_NO'] ?>"><?= $rw['CON_NAME'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                    <td style="width: 150px">
                                        <select name="tbd_cross_sections[<?= $DetailsCount ?>][]"
                                                data-val-required="required"
                                                data-val="true"
                                                class="form-control">
                                            <option></option>
                                            <?php foreach ($items_lower as $rw) : ?>
                                                <option
                                                    <?= $dRow['CROSS_SECTION'] == $rw['CLASSID'] ? 'selected' : '' ?>
                                                        value="<?= $rw['CLASSID'] ?>"
                                                        data-type="<?= $rw['TYPE'] ?>"
                                                ><?= $rw['CLASS_ID_NAME'] ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </td>
                                    <td><input name="tbd_length[<?= $DetailsCount ?>][]" value="<?= $dRow['LENGTH'] ?>"
                                               data-val="true"
                                               data-val-required="required"
                                               class="form-control"/></td>
                                    <td><input name="tbd_r[<?= $DetailsCount ?>][]" value="<?= $dRow['R'] ?>"
                                               data-val="true"
                                               data-val-required="required"
                                               class="form-control"/></td>
                                    <td><input name="tbd_s[<?= $DetailsCount ?>][]" value="<?= $dRow['S'] ?>"
                                               data-val="true"
                                               data-val-required="required"
                                               class="form-control"/></td>
                                    <td><input name="tbd_t[<?= $DetailsCount ?>][]" value="<?= $dRow['T'] ?>"
                                               data-val="true"
                                               data-val-required="required"
                                               class="form-control"/></td>
                                    <td><input name="tbd_n[<?= $DetailsCount ?>][]" value="<?= $dRow['N'] ?>"
                                               data-val="true"
                                               data-val-required="required"
                                               class="form-control"/></td>
                                    <td><input name="tbd_rs[<?= $DetailsCount ?>][]" value="<?= $dRow['RS'] ?>"
                                               data-val="true"
                                               data-val-required="required"
                                               class="form-control"/></td>
                                    <td><input name="tbd_st[<?= $DetailsCount ?>][]" value="<?= $dRow['ST'] ?>"
                                               data-val="true"
                                               data-val-required="required"
                                               class="form-control"/></td>
                                    <td><input name="tbd_rt[<?= $DetailsCount ?>][]" value="<?= $dRow['RT'] ?>"
                                               data-val="true"
                                               data-val-required="required"
                                               class="form-control"/></td>
                                    <td data-action="delete"><a href="javascript:;"
                                                                onclick="javascript:deleteTransformData(this,<?= $dRow['SER'] ?>,'<?= base_url('technical/TransformersInstallation/delete') ?>');"><i
                                                    class="icon icon-trash delete-action"></i> </a></td>
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
                                <th colspan="11"></th>

                            </tr>
                            </tfoot>
                        </table>
                    </td>
                    <td data-expected="true"></td>
                    <td></td>
                    <td data-action="delete"><a href="javascript:;"
                                                onclick="javascript:deleteTransformData(this,<?= $row['SER'] ?>,'<?= base_url('technical/TransformersInstallation/delete') ?>');"><i
                                    class="icon icon-trash delete-action"></i> </a></td>
                </tr>

                <?php
                $DetailsCount++;
                $count++;
            }

        }
        ?>

        </tbody>
        <tfoot>
        <tr>
            <th></th>
            <th>
                <?php if (count($details) <= 0 || ((isset($can_edit) ? $can_edit : false))) { ?>
                    <a onclick="javascript:add_row(this,'input,select',true);" href="javascript:;"><i
                                class="glyphicon glyphicon-plus"></i>جديد</a>
                <?php } ?>
            </th>
            <th colspan="15"></th>

        </tr>
        </tfoot>
    </table>
</div>

