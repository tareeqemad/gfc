<?php
$MODULE_NAME = 'contracts_services';
$TB_NAME = 'Contract_gcc';
$isCreate = isset($details) && count($details) > 0 ? false : true;
$count = 0;
///
?>
<div class="tb_container">
    <table class="table" id="details_tb1" data-container="container" align="center">
        <thead>
        <tr>
            <th style="width: 8%">رقم المسلسل</th>
            <th>البند</th>
            <th> المواصفات المتفق عليها</th>
            <th>الوحدة</th>
            <th>الكمية</th>
            <th>العملة</th>
        </tr>
        </thead>
        <tbody>
        <?php if (count($details) <= 0) {  // ادخال ?>

            <tr>
                <td>
                    <input type="text" readonly name="" id="txt_ser"
                           class="form-control">
                    <input type="hidden" name="ser_det[]" id="h_txt_ser_det<?= $count ?>"
                           data-val="false" data-val-required="required">
                    <input type="hidden" name="seq1[]" id="seq1_id[]" value="0"/>
                    <input type="hidden" name="h_count[]" id="h_count_<?= $count ?>"/>
                </td>
                <td>
                    <input type="text" name="class_name[]"
                           data-val="true"
                           id="txt_class_name_<?= $count ?>" class="form-control"/>

                </td>

                <td>
                    <input type="text" name="class_detail[]"
                           data-val="true"
                           id="txt_class_detail_<?= $count ?>" class="form-control"/>
                </td>
                <td>
                    <select name="class_unit[]" id="dp_class_unit_<?= $count ?>" class="form-control sel2">
                        <option value="0">_______</option>
                        <?php foreach ($class_unit_cons as $row) : ?>
                            <option value="<?= $row['CON_NO'] ?>">
                                <?php echo $row['CON_NAME'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td>
                    <input type="text" name="class_qty[]"
                           data-val="true"
                           id="txt_class_qty_<?= $count ?>" class="form-control"/>
                </td>
                <td>
                    <select name="curr[]" id="dp_curr_<?= $count ?>" class="form-control sel2">
                        <option value="0">_______</option>
                        <?php foreach ($curr_id_cons as $row) : ?>
                            <option value="<?= $row['CURR_ID'] ?>">
                                <?php echo $row['CURR_NAME'] ?></option>
                        <?php endforeach; ?>
                    </select>

                </td>
            </tr>
            <?php
            $count++;
        } else if (count($details) > 0) { // تعديل
            $count = -1;
            foreach ($details as $row1) {
                ++$count + 1;
                ?>
                <tr>
                    <td>
                        <input type="text" readonly name="" id="txt_ser"
                               class="form-control" value="<?= $row1['ID_DETAIL_SER'] ?>">
                        <input type="hidden" name="ser_det[]" value="<?= $row1['ID_DETAIL_SER'] ?>"
                               id="h_txt_ser_det<?= $count ?>"
                               data-val="false" data-val-required="required">
                        <input type="hidden" name="seq1[]" value="<?= $row1['ID_DETAIL_SER'] ?>"
                               id="seq1_id[]"/>
                        <input type="hidden" name="h_count[]" id="h_count_<?= $count ?>"/>
                    </td>
                    <td>
                        <input type="text" name="class_name[]"
                               value="<?= $row1['CLASS_NAME'] ?>"
                               data-val="true"
                               id="txt_class_name" class="form-control"/>
                    </td>
                    <td>
                        <input type="text" name="class_detail[]"
                               data-val="true" value="<?= $row1['CLASS_DETAIL'] ?>"
                               id="txt_class_detail" class="form-control"/>
                    </td>
                    <td>
                        <select name="class_unit[]" id="dp_class_unit_<?= $count ?>" class="form-control">
                            <option value="0">_______</option>
                            <?php foreach ($class_unit_cons as $row) : ?>
                                <option value="<?= $row['CON_NO'] ?>" <?php if ($row['CON_NO'] == ((count(@$details) > 0) ? @$row1['CLASS_UNIT'] : 0)) {
                                    echo " selected";
                                } ?> >
                                    <?php echo $row['CON_NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>

                    </td>
                    <td>
                        <input type="text" name="class_qty[]"
                               data-val="true" value="<?= $row1['CLASS_QTY'] ?>"
                               id="txt_class_qty" class="form-control"/>
                    </td>
                    <td>
                        <select name="curr[]" id="dp_curr_<?= $count ?>" class="form-control">
                            <option value="0">_______</option>
                            <?php foreach ($curr_id_cons as $row) : ?>
                                <option value="<?= $row['CURR_ID'] ?>" <?php if ($row['CURR_ID'] == ((count(@$details) > 0) ? @$row1['CURR'] : 0)) {
                                    echo " selected";
                                } ?> >
                                    <?php echo $row['CURR_NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>
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
            <th></th>
            <th></th>
        </tr>
        <tr>
            <th>
                <a onclick="javascript:add_row(this,'input:text,input:hidden[name^=seq1],select,select',false);"
                   href="javascript:;"><i class="glyphicon glyphicon-plus"></i>جديد</a>
            </th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
        </tfoot>
    </table>
</div>
