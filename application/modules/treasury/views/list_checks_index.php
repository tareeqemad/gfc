<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 14/10/14
 * Time: 09:35 ص
 */
$create_url=base_url('treasury/checks_processing/create');
$delete_url=base_url('treasury/checks_processing/delete');
?>

<?= AntiForgeryToken() ?>
<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>

        <ul>

        </ul>

    </div>

    <div class="form-body">

        <div id="msg_container"></div>

        <fieldset>
            <legend>بحـث</legend>
            <div class="modal-body inline_form">
                <div class="form-group col-sm-1">
                    <label class="control-label">رقم الشيك</label>
                    <div class="">
                        <input type="text" id="txt_check_id"  class="form-control "/>


                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label">البنك</label>
                    <div class="">
                        <input type="text"   id="txt_voucher_date_from" class="form-control "/>


                    </div>
                </div>
            </div>
            <div class="modal-footer">

                <button type="button" onclick="javascript:search_data();" class="btn btn-success"> إستعلام</button>

                <button type="button" onclick="javascript:clearForm_any($('fieldset'));search_data();" class="btn btn-default"> تفريغ الحقول</button>
            </div>
        </fieldset>

        <div id="container">
            <?php echo modules::run('treasury/checks_processing/get_checks_page',$page); ?>
        </div>

    </div>

</div>
