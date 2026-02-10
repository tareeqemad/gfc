<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 08/02/15
 * Time: 10:47 ص
 */

?>

    <div class="modal-body inline_form">

        <div class="form-group col-sm-1">
            <label class="control-label">رقم الطلب</label>
            <div><?=$serv_data['R_NO']?></div>
        </div>

        <div class="form-group col-sm-1">
            <label class="control-label"> الفرع</label>
            <div><?=$serv_data['R_BRANCH_NAME']?></div>
        </div>

        <div class="form-group col-sm-2">
            <label class="control-label">تاريخ الطلب </label>
            <div><?=$serv_data['R_DATE']?></div>
        </div>

        <div class="form-group col-sm-1">
            <label class="control-label">نوع الطلب </label>
            <div><?=$serv_data['R_TYPE_NAME']?></div>
        </div>

        <div class="form-group col-sm-5">
            <label class="control-label">البيان  </label>
            <div><?=$serv_data['R_NOTE']?></div>
        </div>

        <div style="clear: both"></div>

        <?=modules::run("stores/stores_payment_request/public_serv_get_details", $serv_data['R_NO'] )?>

    </div>
