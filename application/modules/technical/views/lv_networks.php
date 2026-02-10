<?php
/**
 * Created by PhpStorm.
 * User: abarakat
 * Date: 23/05/18
 * Time: 10:47 ص
 */
$count = 0;
?>

<div class="tb_container">
    <table class="table" id="lvnTable" action="lv_networks" data-container="container">
        <thead>

        <tr id="before_after">
            <th colspan="6" id="before">Before Mitigation</th>
            <th colspan="6" id="after">After Mitigation</th>
        </tr>

        <tr>
            <th>#</th>
            <th>Transformer Name</th>
            <th>KW Loss</th>
            <th>Kvar Loss</th>
            <th>Voltage (LL) of Weakest Node (V</th>
            <th>Vd%</th>
            <th>KW Loss</th>
            <th>Kvar Loss</th>
            <th>Voltage (LL) of Weakest Node (V</th>
            <th>Vd%</th>
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
                <tr>
                    <td><input name="tld_ser[]" value="<?= $row['SER'] ?>" type="hidden"/></td>
                    <td><input name="tld_name[]" value="<?= $row['NAME'] ?>" data-val="true"  readonly 
                               data-val-required="required"
                               class="form-control"/></td>
                    <td><input name="tld_kw_loss[]" value="<?= $row['KW_LOSS'] ?>"  <?= $row['IS_NEW'] == 1 ? 'readonly' : '' ?> class="form-control"/>
                    </td>
                    <td><input name="tld_kva_loss[]" value="<?= $row['KVA_LOSS'] ?>"  <?= $row['IS_NEW'] == 1 ? 'readonly' : '' ?> class="form-control"/>
                    </td>
                    <td><input name="tld_llv[]" <?= $row['IS_NEW'] == 1 ? 'readonly' : '' ?> value="<?= $row['LLV'] ?>" class="form-control"/></td>
                    <td><input name="tld_vd[]" value="<?= $row['VD'] ?>" readonly data-val="true"
                               data-val-required="required" class="form-control"/>
                    </td>
                    <td><input name="tlad_kw_loss[]" value="<?= $row['AD_KW_LOSS'] ?>" data-val="true"
                               data-val-required="required" class="form-control"/>
                    </td>
                    <td><input name="tlad_kva_loss[]" value="<?= $row['ADD_KVA_LOSS'] ?>" data-val="true"
                               data-val-required="required" class="form-control"/>
                    </td>
                    <td><input name="tlad_llv[]" value="<?= $row['AD_LLV'] ?>" data-val="true"
                               data-val-required="required" class="form-control"/></td>
                    <td><input name="tlad_vd[]" value="<?= $row['AD_VD'] ?>" readonly data-val="true"
                               data-val-required="required" class="form-control"/>
                    </td>
                    <td></td>
                    <td>

                    </td>
                </tr>

                <?php
            }
        }
        ?>

        </tbody>

    </table>
</div>

