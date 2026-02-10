<?php
/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 15/08/23
 * Time: 14:55
 */

$MODULE_NAME= 'hr';
$TB_NAME= 'Meeting_lecturer';

$post_2_url= base_url("$MODULE_NAME/$TB_NAME/edit_schedule_work");
?>

<form class="form-vertical" id="<?=$TB_NAME?>_form_2" method="post" role="form" action="<?=$post_2_url?>" novalidate="novalidate">

    <div class="row">
        <?php if (( isset($can_edit)?$can_edit:false)) : ?>
            <input type="hidden" value="<?=$HaveRs?$rs['SER']:''?>" name="ser" id="h_ser" />
        <?php endif; ?>

        <div class="table-responsive">
            <table class="table table-bordered " id="schedule_work" data-container="container">
                <thead class="table-secondary">
                <tr style="text-align: center">
                    <th style="width: 5%">م</th>
                    <th style="width: 20%">البند</th>
                    <th style="width: 15%">جهة التنسيب</th>
                    <th style="width: 10%">التصنيف</th>
                    <th style="width: 20%">ملاحظات</th>
                    <th style="width: 5%">الاجراء</th>
                </tr>
                </thead>

                <tbody>

                <?php if (count($master_det_data_s) > 0) { // تعديل
                    $count_s = -1;

                    foreach ($master_det_data_s as $row) {
                        $count_s++;
                        $d_count_s++;
                        ?>

                        <tr>
                            <td>
                                <input type="hidden" name="ser_s[]" id="ser_s<?= $count_s ?>" class="form-control" value="<?= $row['SER_S'] ?>" style="text-align: center" readonly>
                                <input name="txt_ser_s[]" id="txt_ser_s<?= $d_count_s ?>" class="form-control" value="<?= $d_count_s ?>" style="text-align: center" readonly>
                            </td>

                            <td>
                                <textarea name="item_no_s[]" rows="4" class="form-control" id="item_no_s<?= $count_s ?>"  ><?= $row['ITEM_NO'] ?></textarea>
                            </td>

                            <td>
                                <input name="placement_party[]" class="form-control" id="placement_party<?= $count_s ?>" value="<?= $row['PLACEMENT_PARTY'] ?>"  style="text-align: center" />
                            </td>

                            <td>
                                <input name="category[]" class="form-control month" id="category<?= $count_s ?>" value="<?= $row['CATEGORY'] ?>"style="text-align: center" />
                            </td>

                            <td>
                                <textarea name="notes[]" rows="4" class="form-control" id="notes<?= $count_s ?>"  ><?= $row['NOTES'] ?></textarea>
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
                    <?php if ( count($master_det_data_s) >= 0 ) { ?>
                        <th>
                            <a onclick="javascript:addRow_schedule_work();" href="javascript:;"><i class="glyphicon glyphicon-plus"></i>جديد</a>
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

        <?php if ( HaveAccess($post_2_url) && ( $rs['ADOPT']==1 and isset($can_edit)?$can_edit:false)  ) : ?>
            <button type="submit" data-action="submit" class="btn btn-primary me-2">حفظ البيانات</button>
        <?php endif; ?>

    </div>

</form>