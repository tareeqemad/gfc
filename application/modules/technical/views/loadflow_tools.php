<?php
/**
 * Created by PhpStorm.
 * User: abarakat
 * Date: 12/12/17
 * Time: 09:52 ص
 */
$count = 0;


?>

<div class="tb_container">
    <table class="table" id="toolsTbl" data-container="container">
        <thead>
        <tr id="before_after">
            <th colspan="7" id="before">Before Installation</th>
            <th colspan="7" id="after">After Installation</th>
        </tr>
        <tr>
            <th style="width: 2%">#</th>
            <th style="width: 200px">Cross section</th>
            <th style="width: 8%">Length (m)</th>
            <th style="width: 7%">Average Load (A)</th>
            <th style="width: 8%">V (LL) (KV)</th>
            <th data-type="ln-connection" style="width: 8%">V (LN) (KV)</th>


            <th data-hide="3,4" style="width:  200px">Cross section</th>
            <th data-hide="3,4" style="width: 8%">Length (m)</th>
            <th style="width: 7%">Average Load (A)</th>
            <th style="width: 8%">V (LL) (KV)</th>
            <th data-type="ln-connection" style="width: 8%">V (LN) (KV)</th>

            <th></th>
            <th style="width: 50px;"></th>
        </tr>
        </thead>

        <tbody>

        <?php if (count($details) <= 0) { // ادخال ?>
            <tr>
                <td><input name="dser[]" value="0" type="hidden" class="form-control" id="dser_<?= $count ?>"/></td>
                <td>
                    <select name="cross_section[]" data-val="true" data-val-required="required"
                            id="cross_section_<?= $count ?>" class="form-control valid">
                        <option></option>
                        <?php foreach ($items as $row) : ?>
                            <option value="<?= $row['CLASSID'] ?>"
                                    data-type="<?= $row['TYPE'] ?>"
                                ><?= $row['CLASS_ID_NAME'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td><input name="length[]" data-val="true" data-val-required="required" class="form-control"
                           id="length_<?= $count ?>"/></td>
                <td><input name="period_of_service[]" data-val="true" data-val-required="required" class="form-control"
                           id="period_of_service_<?= $count ?>"/></td>
                <td><input name="joints[]" data-val="true" data-val-required="required" class="form-control"
                           id="joints_<?= $count ?>"/></td>
                <td><input name="v_ln[]" data-val="true" data-val-required="required" class="form-control"
                           id="v_ln_<?= $count ?>"/></td>
                <td data-hide="3,4">
                    <select name="across_section[]" id="across_section_<?= $count ?>"
                            data-val="true" data-val-required="required"
                            class="form-control valid">
                        <option></option>
                        <?php foreach ($items as $row) : ?>
                            <option value="<?= $row['CLASSID'] ?>"
                                    data-type="<?= $row['TYPE'] ?>"
                                ><?= $row['CLASS_ID_NAME'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td data-hide="3,4"><input name="alength[]"  data-val="true" data-val-required="required" class="form-control" id="alength_<?= $count ?>"/></td>
                <td><input name="alife_expectancy[]" class="form-control" id="alife_expectancy_<?= $count ?>"/></td>
                <td><input name="avgload[]"   style="direction: ltr" class="form-control" id="avgload_<?= $count ?>"/></td>
                <td><input name="av_ln[]"  class="form-control"
                           style="direction: ltr"
                           id="av_ln_<?= $count ?>"/></td>
                <td></td>
                <td></td>

            </tr>

        <?php
        } else if (count($details) > 0) { // تعديل
            $count = -1;
            foreach ($details as $row) {
                ?>
                <tr>
                    <td><?= ++$count + 1 ?><input name="dser[]" value="<?= $row['SER'] ?>" type="hidden"
                                                  class="form-control" id="dser_<?= $count ?>"/></td>
                    <td>
                        <select name="cross_section[]" id="cross_section_<?= $count ?>"  data-val="true" data-val-required="required" class="form-control valid">
                            <option></option>
                            <?php foreach ($items as $_row) : ?>
                                <option <?= $_row['CLASSID'] == $row['CROSS_SECTION'] ? 'selected' : '' ?>
                                    value="<?= $_row['CLASSID'] ?>"
                                    data-type="<?= $_row['TYPE'] ?>"
                                    ><?= $_row['CLASS_ID_NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td><input name="length[]" value="<?= $row['LENGTH'] ?>" data-val="true"
                               style="direction: ltr"
                               data-val-required="required" class="form-control" id="length_<?= $count ?>"/></td>
                    <td><input name="period_of_service[]" data-val="true" data-val-required="required"
                               value="<?= $row['PERIOD_OF_SERVICE'] ?>" class="form-control"
                               style="direction: ltr"
                               id="period_of_service_<?= $count ?>"/></td>
                    <td><input name="joints[]" data-val="true" data-val-required="required"
                               style="direction: ltr"
                               value="<?= $row['JOINTS'] ?>" class="form-control" id="joints_<?= $count ?>"/></td>
                    <td><input name="v_ln[]" data-val="true" data-val-required="required" value="<?= $row['V_LN'] ?>"
                               style="direction: ltr"
                               class="form-control" id="v_ln_<?= $count ?>"/></td>

                    <td data-hide="3,4">
                        <select name="across_section[]" data-val="true" data-val-required="required" id="across_section_<?= $count ?>" class="form-control valid">
                            <option></option>
                            <?php foreach ($items as $_row) : ?>
                                <option <?= $_row['CLASSID'] == $row['ACROSS_SECTION'] ? 'selected' : '' ?>
                                    value="<?= $_row['CLASSID'] ?>"
                                    data-type="<?= $_row['TYPE'] ?>"
                                    ><?= $_row['CLASS_ID_NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td data-hide="3,4"><input name="alength[]" value="<?= $row['ALENGTH'] ?>" class="form-control"
                                               style="direction: ltr"
                                               data-val="true" data-val-required="required"
                                               id="alength_<?= $count ?>"/></td>
                    <td><input name="alife_expectancy[]" value="<?= $row['ALIFE_EXPECTANCY'] ?>" class="form-control"
                               style="direction: ltr"
                               id="alife_expectancy_<?= $count ?>"/></td>
                    <td><input name="avgload[]" value="<?= $row['AVGLOAD'] ?>" class="form-control"
                               style="direction: ltr"
                               id="avgload_<?= $count ?>"/></td>
                    <td><input name="av_ln[]" data-val="true" value="<?= $row['AV_LN'] ?>" class="form-control"
                               style="direction: ltr"
                            
                               id="av_ln_<?= $count ?>"/></td>
                    <td></td>
                    <td>

                        <?php if (HaveAccess(base_url('technical/loadflow/delete_cables'))  ) : ?>
                            <a href="javascript:;" onclick="javascript:delete_details(this,<?= $row['SER'] ?>,'<?= base_url('technical/loadflow/delete_cables') ?>');"><i class="icon icon-trash delete-action"></i> </a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php
            }
        }
        ?>

        </tbody>
        <tfoot>
        <tr>
            <th></th>
            <th>
                <?php if (count($details) <= 0 || ((isset($can_edit) ? $can_edit : false))) { ?>
                    <a onclick="javascript:add_row(this,'input',false);" href="javascript:;"><i
                            class="glyphicon glyphicon-plus"></i>جديد</a>
                <?php } ?>
            </th>
            <th colspan="11"></th>

        </tr>
        </tfoot>
    </table>
</div>
