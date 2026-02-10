<?php
/**
 * Created by PhpStorm.
 * User: abarakat
 * Date: 4/17/2019
 * Time: 9:02 AM
 */
?>

    <div class="row">
        <div class="toolbar">

            <div class="caption"><?= $title ?> </div>


        </div>

        <div class="form-body">


            <fieldset>
                <legend></legend>
                <ul class="report-menu">

                    <?php if (HaveAccess(base_url('settings/DataMigration/changeIncomeCheck'))): ?>
                        <li><a class="btn blue" href="javascript:"
                               onclick="$('#change_check_number_modal').modal();"> معالجة اخطاء
                                الشيكات الواردة </a></li>
                    <?php endif; ?>

                    <?php if (HaveAccess(base_url('settings/DataMigration/changeOutcomeCheck'))): ?>
                        <li><a class="btn blue" href="javascript:"
                               onclick="$('#change_out_check_number_modal').modal();"> معالجة اخطاء
                                الشيكات الصادرة </a></li>
                    <?php endif; ?>

                </ul>
            </fieldset>


        </div>

    </div>

<?php if (HaveAccess(base_url('settings/DataMigration/changeIncomeCheck'))): ?>
    <div id="change_check_number_modal" class="modal fade modal-fullscreen">
        <div class="modal-dialog">
            <div class="modal-content" style=" height:100%;">

                <form id="change_check_number_form"
                      action="<?= base_url('settings/DataMigration/changeIncomeCheck') ?>">
                    <div class="modal-body" style="height:90%;">

                        <div class="form-group">
                            <input type="text"
                                   placeholder="رقم الشيك"
                                   class="form-control"
                                   data-val="true"
                                   name="old_check"
                                   data-val-required="حقل مطلوب">
                        </div>

                        <div class="form-group">
                            <input type="text"
                                   placeholder="رقم سند القبض"
                                   class="form-control"
                                   data-val="true"
                                   name="voucher_id"
                                   data-val-required="حقل مطلوب">
                        </div>

                        <div class="form-group">
                            <input type="text"
                                   placeholder=" رقم الشيك الجديد"
                                   class="form-control"
                                   data-val="true"
                                   name="new_check"
                                   data-val-required="حقل مطلوب">
                        </div>


                        <div class="form-group">
                            <input type="text"
                                   placeholder=" تاريخ الاستحقاق الجديد"
                                   class="form-control"
                                   data-type="date"
                                   data-date-format="DD/MM/YYYY"
                                   name="new_date" >
                        </div>



                    </div>
                    <div class="modal-footer">
                        <button type="button" onclick="changeCheckNumber()" class="btn btn-danger"
                        ">حفظ</button>
                        <button class="btn" data-dismiss="modal" aria-hidden="true">إغلاق</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php if (HaveAccess(base_url('settings/DataMigration/changeOutcomeCheck'))): ?>
    <div id="change_out_check_number_modal" class="modal fade modal-fullscreen">
        <div class="modal-dialog">
            <div class="modal-content" style=" height:100%;">

                <form id="change_out_check_number_form"
                      action="<?= base_url('settings/DataMigration/changeOutcomeCheck') ?>">
                    <div class="modal-body" style="height:90%;">

                        <div class="form-group">
                            <input type="text"
                                   placeholder="رقم الشيك"
                                   class="form-control"
                                   data-val="true"
                                   name="old_check"
                                   data-val-required="حقل مطلوب">
                        </div>

                        <div class="form-group">
                            <input type="text"
                                   placeholder="رقم سند الصرف"
                                   class="form-control"
                                   data-val="true"
                                   name="payment_id"
                                   data-val-required="حقل مطلوب">
                        </div>

                        <div class="form-group">
                            <input type="text"
                                   placeholder=" رقم الشيك الجديد"
                                   class="form-control"
                                   data-val="true"
                                   name="new_check"
                                   data-val-required="حقل مطلوب">
                        </div>


                    </div>
                    <div class="modal-footer">
                        <button type="button" onclick="changeOutCheckNumber()" class="btn btn-danger"
                        ">حفظ</button>
                        <button class="btn" data-dismiss="modal" aria-hidden="true">إغلاق</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endif; ?>