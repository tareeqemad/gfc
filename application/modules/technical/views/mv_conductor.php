<?php
/**
 * Created by PhpStorm.
 * User: abarakat
 * Date: 23/05/18
 * Time: 10:29 ص
 */
$count = 0;
?>

<div class="tb_container">
    <table class="table" id="toolsTbl" action="mv_conductor" data-container="container">
        <thead>
        <tr id="before_after">
            <th colspan="5" id="before">Before Installation</th>
            <th colspan="6" id="after">Load Flow Results</th>
        </tr>
        <tr>
            <th style="width: 2%">#</th>
            <th style="width: 200px">Cross section</th>
            <th style="width: 8%;direction: ltr;">Length (m)</th>
            <th style="width: 7%;direction: ltr;">Average Load (A)</th>
            <th style="width: 8%;direction: ltr;">V (LL) (KV)</th>
            <th style="width: 7%">Loss-KW</th>
            <th style="width: 8%;direction: ltr;">Loss-KVar</th>
            <th style="width: 8%;direction: ltr;"> Voltage (LL) of Weakest Node (KV)</th>
            <th style="width: 8%;direction: ltr;">Vd%</th>
            <th></th>
            <th style="width: 50px;"></th>
        </tr>
        </thead>

        <tbody>

        <?php if (count($details) <= 0) { ?>
            <tr>
                <td><?= $count + 1 ?><input name="mc_ser[]" value="0" type="hidden"/></td>
                <td>
                    <select name="mc_b_cross_section[]"
                            class="form-control valid">
                        <option></option>
                        <?php foreach ($items as $row) : ?>
                            <option value="<?= $row['CLASSID'] ?>"
                                    data-type="<?= $row['TYPE'] ?>"
                            ><?= $row['CLASS_ID_NAME'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td><input name="mc_b_length[]" class="form-control"/></td>
                <td><input name="joints[]" class="form-control"
                           id="joints_<?= $count ?>"/></td>
                <td><input name="mc_b_avg_load[]" class="form-control"/>
                </td>
                <td><input name="mc_loss[]" style="direction: ltr" class="form-control"/></td>
                <td><input name="mc_loss_var[]" class="form-control" style="direction: ltr"/></td>
                <td><input name="mc_vw_weakest_node[]" class="form-control" style="direction: ltr"/></td>
                <td><input name="mc_vd[]" class="form-control" readonly style="direction: ltr"/></td>
                <td></td>
                <td><a href="javascript:;"
                       onclick="javascript:deleteTransformData(this,0,'<?= base_url('technical/TransformersInstallation/delete') ?>');"><i
                                class="icon icon-trash delete-action"></i> </a></td>

            </tr>

            <?php
        } else if (count($details) > 0) { // تعديل
            $count = 0;
            foreach ($details as $row) {
                ?>
                <tr>
                    <td><?= $count + 1 ?><input name="mc_ser[]" value="<?= $row['SER'] ?>" type="hidden"/></td>
                    <td>
                        <select name="mc_b_cross_section[]"
                                data-val="true"
                                data-val-required="required"
                                class="form-control valid">
                            <option></option>
                            <?php foreach ($items as $r) : ?>
                                <option value="<?= $r['CLASSID'] ?>"
                                        data-type="<?= $r['TYPE'] ?>"
                                    <?= $row['B_CROSS_SECTION'] == $r['CLASSID'] ? 'selected' : '' ?>
                                ><?= $r['CLASS_ID_NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td><input name="mc_b_length[]"
                               data-val="true"
                               value="<?= $row['B_LENGTH'] ?>"
                               data-val-required="required"
                               class="form-control"/></td>
                    <td><input name="joints[]"
                               data-val="true"
                               value="<?= $row['B_AVG_LOAD'] ?>"
                               data-val-required="required"
                               class="form-control"
                               id="joints_<?= $count ?>"/></td>
                    <td><input name="mc_b_avg_load[]"
                               data-val="true"
                               value="<?= $row['B_V_LL_KV'] ?>"
                               data-val-required="required"
                               class="form-control"/>
                    </td>

                    <td><input name="mc_loss[]" value="<?= $row['LOSS'] ?>" style="direction: ltr"
                               class="form-control"/></td>
                    <td><input name="mc_loss_var[]" value="<?= $row['LOSS_VAR'] ?>" class="form-control"
                               style="direction: ltr"/></td>
                    <td><input name="mc_vw_weakest_node[]" value="<?= $row['VW_WEAKEST_NODE'] ?>" class="form-control"
                               style="direction: ltr"/></td>
                    <td><input name="mc_vd[]" value="<?= $row['VD'] ?>" class="form-control" readonly
                               style="direction: ltr"/></td>

                    <td></td>
                    <td data-action="delete"><a href="javascript:;"
                                                onclick="javascript:deleteTransformData(this,<?= $row['SER'] ?>,'<?= base_url('technical/TransformersInstallation/delete') ?>');"><i
                                    class="icon icon-trash delete-action"></i> </a></td>

                </tr>
                <?php

                $count = $count + 1;
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
            <th colspan="11"></th>

        </tr>
        </tfoot>
    </table>
</div>

