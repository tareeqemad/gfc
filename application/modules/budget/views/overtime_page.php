<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 9/16/14
 * Time: 10:16 AM
 */

$create_url =base_url('budget/overtime/create');

$get_page_url =base_url('budget/overtime/get_page');
?>

<div class="row">

    <div class="col-md-6 col-sm-6">
        <!-- BEGIN PORTLET-->
        <div class="portlet box purple">
            <div class="portlet-title">
                <div class="caption"><i class="icon icon-bar-chart"></i>  بيانات سابقة</div>

            </div>
            <div class="portlet-body">
                <table class="table" id="overtimes_hTbl" data-container="container">
                    <thead>
                    <tr>
                        <th class="col-sm-6" >الشهر</th>
                        <th class="col-sm-3">عدد الساعات </th>
                        <th class="col-sm-3">المبلغ </th>

                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach($overtimes_h as $row) :?>
                        <tr>

                            <td>    <?= months(substr($row['MONTH'],4,6)) ?></td>
                            <td><?= $row['CALCULATED_HOURS'] ?></td>
                            <td><?= $row['CALCULATED_VAL'] ?></td>

                        </tr>
                    <?php endforeach;?>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- END PORTLET-->
    </div>
    <div class="col-md-6 col-sm-6">
        <!-- BEGIN PORTLET-->
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption"><i class="icon icon-bar-chart"></i>الوقت الإضافي </div>

            </div>
            <div class="portlet-body">
                <table class="table" id="overtimesTbl" data-container="container">
                    <thead>
                    <tr>

                        <th class="col-sm-6" >الشهر</th>
                        <th class="col-sm-3">عدد الساعات </th>
                        <th class="col-sm-3">المبلغ </th>

                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach($overtimes as $row) :?>
                        <tr>


                            <td> <input type="hidden" value="<?= $row['MONTH'] ?>" name="month[]" />
                                <?= months(substr($row['MONTH'],4,6)) ?>
                            </td>
                            <td><input type="text" class="form-control" value="<?= $row['CALCULATED_HOURS'] ?>" name="hours[]" /></td>
                            <td><input type="text" class="form-control" value="<?= $row['CALCULATED_VAL'] ?>" name="val[]" /> </td>

                        </tr>
                    <?php endforeach;?>
                    </tbody>
                </table>

                <?php if( HaveAccess($create_url,$get_page_url)): ?>  <a class="btn blue" onclick="javascript:save_overtime();" href="javascript:;"><i class="glyphicon glyphicon-saved"></i>حفظ</a>  <?php endif;?>

            </div>
        </div>
        <!-- END PORTLET-->
    </div>
</div>




<script>
    if (typeof initFunctions == 'function') {
        initFunctions();
    }
</script>