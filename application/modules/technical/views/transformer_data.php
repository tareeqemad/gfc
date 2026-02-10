<?php
/**
 * Created by PhpStorm.
 * User: abarakat
 * Date: 22/05/18
 * Time: 10:01 ص
 */
$count = 0;
$DetailsCount = 1;
?>

<div class="tb_container">
    <table class="table" action="transformer_data" id="toolsTbl" data-container="container">
        <thead>

        <tr>
            <th style="width: 2%">#</th>
            <th style="width: 200px">Transformer Name</th>
            <th style="width: 8%">KVA Rating</th>
            <th style="width: 7%">MV Feeder Name</th>

            <th></th>
            <th style="width: 8%;direction: ltr;">Expected Transformer Average Load%</th>


            <th></th>
            <th style="width: 50px;"></th>
        </tr>
        </thead>

        <tbody>

        <?php if (count($details) <= 0): // ادخال ?>
            <tr data-root="true">
                <td><input name="td_ser[]" value="0" type="hidden"/> <?= $count + 1 ?></td>
                <td><input name="td_name[]" data-val="true" data-val-required="required" class="form-control"/></td>
                <td>
                    <select name="td_kva_rating[]" data-val="true" data-val-required="required" class="form-control">
                        <option></option>
                        <?php foreach ($POWER_ADAPTER as $row) : ?>
                            <option value="<?= $row['CON_NAME'] ?>"><?= $row['CON_NAME'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td>
                    <select name="td_mv_feeder_name[]"
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
                            <th style="width: 8%">LV CB Direction</th>
                            <th data-type="ln-connection" style="width: 8%">Status</th>

                            <th data-hide="3,4">Cross Section</th>
                            <th data-hide="3,4" style="width: 8%;direction: ltr;">Length (m)</th>
                            <th style="width: 7%;direction: ltr;">Expected Load (A)</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td style="width: 142px">
                                <input name="td_d_ser[1][]"

                                       type="hidden"
                                       class="form-control"/>

                                <select name="td_lv_cb_direction[1][]"
                                        data-val-required="required"
                                        data-val="true"
                                        class="form-control">
                                    <option></option>
                                    <?php foreach ($lv_cb_direction as $row) : ?>
                                        <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td style="width: 145px">
                                <select name="td_status[1][]"
                                        data-val-required="required"
                                        data-val="true"
                                        class="form-control">
                                    <option></option>
                                    <option value="1">New</option>
                                    <option value="2">Existing</option>
                                </select>
                            </td>
                            <td>
                                <select name="td_cross_sections[1][]" data-val="true" data-val-required="required"
                                        class="form-control">
                                    <option></option>
                                    <?php foreach ($items_lower as $row) : ?>
                                        <option value="<?= $row['CLASSID'] ?>"
                                                data-type="<?= $row['TYPE'] ?>"
                                        ><?= $row['CLASS_ID_NAME'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td><input name="td_length[1][]" data-val="true" data-val-required="required"
                                       class="form-control"/></td>
                            <td><input name="td_expected_load[1][]" data-val="true" data-val-required="required"
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
                            <th colspan="4"></th>

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
        <?php else: foreach ($details as $row) : ?>

            <tr data-root="true">
                <td><?= $count + 1 ?><input name="td_ser[]" value="<?= $row['SER'] ?>" type="hidden"/></td>
                <td><input name="td_name[]" value="<?= $row['NAME'] ?>" data-val="true" data-val-required="required"
                           class="form-control"/></td>
                <td>
                    <select name="td_kva_rating[]" data-val="true" data-val-required="required" class="form-control">
                        <option></option>
                        <?php foreach ($POWER_ADAPTER as $r) : ?>

                            <option <?= $row['KVA_RATING'] == $r['CON_NAME'] ? "selected" : "" ?>
                                    value="<?= $r['CON_NAME'] ?>"><?= $r['CON_NAME'] ?></option>
                        <?php endforeach; ?>
                    </select>

                </td>
                <td>
                    <select name="td_mv_feeder_name[]"
                            data-val-required="required"
                            data-val="true"
                            class="form-control">
                        <option></option>
                        <?php foreach ($feeder_name as $r) : ?>
                            <option <?= $row['MV_FEEDER_NAME'] == $r['CON_NO'] ? 'selected' : '' ?>
                                    value="<?= $r['CON_NO'] ?>"><?= $r['CON_NAME'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td colspan="0">
                    <table class="table" data-allow-count="6">
                        <thead>
                        <tr>
                            <th style="width: 8%">LV CB Direction</th>
                            <th data-type="ln-connection" style="width: 8%">Status</th>

                            <th data-hide="3,4">Cross Section</th>
                            <th data-hide="3,4" style="width: 8%;direction: ltr;">Length (m)</th>
                            <th style="width: 7%;direction: ltr;">Expected Load (A)</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>

                        <?php if (count($row['details']) <= 0): ?>
                            <tr>
                                <td style="width: 142px">
                                    <input name="td_d_ser[1][]"

                                           type="hidden"
                                           class="form-control"/>

                                    <select name="td_lv_cb_direction[1][]"
                                            data-val-required="required"
                                            data-val="true"
                                            class="form-control">
                                        <option></option>
                                        <?php foreach ($lv_cb_direction as $r) : ?>
                                            <option value="<?= $r['CON_NO'] ?>"><?= $r['CON_NAME'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                                <td style="width: 145px">
                                    <select name="td_status[1][]"
                                            data-val-required="required"
                                            data-val="true"
                                            class="form-control">
                                        <option></option>
                                        <option value="1">New</option>
                                        <option value="2">Existing</option>
                                    </select>
                                </td>
                                <td>
                                    <select name="td_cross_sections[1][]" data-val="true" data-val-required="required"
                                            class="form-control">
                                        <option></option>
                                        <?php foreach ($items_lower as $r) : ?>
                                            <option value="<?= $r['CLASSID'] ?>"
                                                    data-type="<?= $r['TYPE'] ?>"
                                            ><?= $r['CLASS_ID_NAME'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                                <td><input name="td_length[1][]" data-val="true" data-val-required="required"
                                           class="form-control"/></td>
                                <td><input name="td_expected_load[1][]" data-val="true" data-val-required="required"
                                           class="form-control"/></td>
                                <td data-action="delete"><a href="javascript:;"
                                                            onclick="javascript:deleteTransformData(this,0,'');"><i
                                                class="icon icon-trash delete-action"></i> </a></td>
                            </tr>
                        <?php endif; ?>
                        <?php foreach ($row['details'] as $dRow) : ?>
                            <tr>
                                <td style="width: 142px">
                                    <input name="td_d_ser[<?= $DetailsCount ?>][]"
                                           value="<?= $dRow['SER'] ?>"
                                           type="hidden"
                                           class="form-control"/>
                                    <select name="td_lv_cb_direction[<?= $DetailsCount ?>][]"
                                            data-val-required="required"
                                            data-val="true"
                                            class="form-control">
                                        <option></option>
                                        <?php foreach ($lv_cb_direction as $r) : ?>
                                            <option <?= $dRow['LV_CB_DIRECTION'] == $r['CON_NO'] ? 'selected' : '' ?>
                                                    value="<?= $r['CON_NO'] ?>"><?= $r['CON_NAME'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                                <td style="width: 145px">
                                    <select name="td_status[<?= $DetailsCount ?>][]"
                                            data-val-required="required"
                                            data-val="true"
                                            class="form-control">

                                        <option></option>
                                        <option <?= $dRow['STATUS'] == 1 ? 'selected' : '' ?> value="1">New</option>
                                        <option <?= $dRow['STATUS'] == 2 ? 'selected' : '' ?> value="2">Existing
                                        </option>
                                    </select>
                                </td>
                                <td>
                                    <select name="td_cross_sections[<?= $DetailsCount ?>][]" data-val="true"
                                            data-val-required="required"
                                            class="form-control">
                                        <option></option>
                                        <?php foreach ($items_lower as $r) : ?>
                                            <option <?= $dRow['CROSS_SECTIONS'] == $r['CLASSID'] ? 'selected' : '' ?>
                                                    value="<?= $r['CLASSID'] ?>"
                                                    data-type="<?= $r['TYPE'] ?>"
                                            ><?= $r['CLASS_ID_NAME'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                                <td><input name="td_length[<?= $DetailsCount ?>][]"
                                           data-val="true"
                                           data-val-required="required"
                                           value="<?= $dRow['LENGTH'] ?>"
                                           class="form-control"/></td>
                                <td><input name="td_expected_load[<?= $DetailsCount ?>][]"
                                           data-val="true"
                                           data-val-required="required"
                                           value="<?= $dRow['EXPECTED_LOAD'] ?>"
                                           class="form-control"/></td>
                                <td data-action="delete"><a href="javascript:;"
                                                            onclick="javascript:deleteTransformData(this,0,'');"><i
                                                class="icon icon-trash delete-action"></i> </a></td>
                            </tr>


                        <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                        <tr>

                            <th>
                                <a onclick="javascript:add_row(this,'input,td[data-expected],select',false);"
                                   href="javascript:;"><i
                                            class="glyphicon glyphicon-plus"></i>جديد</a>

                            </th>
                            <th colspan="4"></th>

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
            <?php $DetailsCount++;
            $count = $count + 1; ?>
        <?php endforeach; endif; ?>

        </tbody>
        <tfoot>
        <tr>
            <th></th>
            <th>
                <?php /*if (count($details) <= 0 || ((isset($can_edit) ? $can_edit : false))) { */ ?>
                <a onclick="javascript:add_row(this,'input,select',true);" href="javascript:;"><i
                            class="glyphicon glyphicon-plus"></i>جديد</a>
                <?php /*} */ ?>
            </th>
            <th colspan="11"></th>

        </tr>
        </tfoot>
    </table>
</div>
