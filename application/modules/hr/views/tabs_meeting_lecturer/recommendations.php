<?php
/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 15/08/23
 * Time: 14:55
 */

$MODULE_NAME= 'hr';
$TB_NAME= 'Meeting_lecturer';
$adopt_url= base_url("$MODULE_NAME/$TB_NAME/adopt_");
$post_3_url= base_url("$MODULE_NAME/$TB_NAME/edit_recommendations");
?>

<form class="form-vertical" id="<?=$TB_NAME?>_form_3" method="post" role="form" action="<?=$post_3_url?>" novalidate="novalidate">

    <div class="row">
        <?php if (( isset($can_edit)?$can_edit:false)) : ?>
            <input type="hidden" value="<?=$HaveRs?$rs['SER']:''?>" name="ser" id="h_ser" />
        <?php endif; ?>

        <div class="table-responsive">
            <table class="table table-bordered " id="recommendations" data-container="container">
                <thead class="table-warning">
                <tr style="text-align: center">
                    <th style="width: 5%">م</th>
                    <th style="width: 20%">البند</th>
                    <th style="width: 40%">الحيثيات و النقاش</th>
                    <th style="width: 30%">القرار</th>
                    <th style="width: 5%">الاجراء</th>
                </tr>
                </thead>

                <tbody>

                <?php if (count($master_det_data_r) > 0) { // تعديل
                    $count_r = -1;

                    foreach ($master_det_data_r as $row) {
                        $count_r++;
                        $d_count_r++;
                        ?>

                        <tr>
                            <td>
                                <input type="hidden" name="ser_r[]" id="ser_r<?= $count_r ?>" class="form-control" value="<?= $row['SER_R'] ?>" style="text-align: center" readonly>
                                <input name="txt_ser_r[]" id="txt_ser_r<?= $d_count_r ?>" class="form-control" value="<?= $d_count_r ?>" style="text-align: center" readonly>
                            </td>

                            <td>
                                <input name="item_no[]" class="form-control" id="item_no<?= $count_r ?>" value="<?= $row['ITEM_NO'] ?>"  style="text-align: center" />
                            </td>

                            <td>
                                <textarea name="rationale_discussion[]" rows="10" class="form-control" id="rationale_discussion<?= $count_r ?>"  ><?= $row['RATIONALE_DISCUSSION'] ?></textarea>
                            </td>

                            <td>
                                <textarea name="decision[]" rows="10" class="form-control" id="decision<?= $count_r ?>"  ><?= $row['DECISION'] ?></textarea>
                            </td>

                            <td></td>
                        </tr>
                        <?php
                    }
                }
                ?>

                </tbody>
                <tfoot>
                <tr>
                    <?php if ( count($master_det_data_r) >= 0 ) { ?>
                        <th>
                            <a onclick="javascript:addRow_recommendations();" href="javascript:;"><i class="glyphicon glyphicon-plus"></i>جديد</a>
                        </th>
                    <?php } ?>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
                </tfoot>
            </table>
        </div>

    </div>

    <div class="modal-footer">


        <?php if ( HaveAccess($post_3_url) &&  ( $rs['ADOPT']==1 and isset($can_edit)?$can_edit:false)  ) : ?>
            <button type="submit" data-action="submit" class="btn btn-primary me-2">حفظ البيانات</button>
        <?php endif; ?>

        <?php if (HaveAccess($adopt_url.'10') and $rs['ADOPT']==1) : ?>
            <button type="button" id="btn_adopt_10" onclick='javascript:adopt_(10);' class="btn btn-success">اعتماد المدخل</button>
        <?php endif; ?>
    </div>

</form>