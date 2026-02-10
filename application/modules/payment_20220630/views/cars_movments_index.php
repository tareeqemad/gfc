<?php
/**
 * Created by PhpStorm.
 * User: ljasser
 * Date: 24/04/19
 * Time: 10:53 ص
 */


$get_url =base_url('payment/carMovements/get_id');
$edit_url =base_url('payment/carMovements/edit');
$create_url =base_url('payment/carMovements/create');
$get_page_url = base_url('payment/cars/get_page');
$MODULE_NAME= 'payment';
$TB_NAME= 'carMovements';
$back_url=base_url("$MODULE_NAME/$TB_NAME");
$status_url=base_url("$MODULE_NAME/$TB_NAME/status");

$report_url =base_url('reports?type=31');
?>
<?= AntiForgeryToken() ?>
<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>

        <ul>
            <?php // if( HaveAccess($create_url)):  ?><li><a  href="<?= $create_url ?>"><i class="glyphicon glyphicon-plus"></i>جديد</a> </li><?php// endif; ?>
            <li><a  href="<?= $back_url ?>"><i class="icon icon-reply"></i> </a> </li>
        </ul>

    </div>

</div>

    <div class="form-body">
        <fieldset>
            <legend>بحـث</legend>
            <div class="modal-body inline_form">
                <!--------------------------رقم السيارة----------------------->
                <div class="form-group col-sm-2">
                    <label class="control-label"> رقم السيارة  </label>
                    <div>
                        <input type="text"  name="car_id" id="txt_car_id" class="form-control">
                    </div>
                </div>

                <!-------------------------اسم السائق----------------------------->
                <div class="form-group col-sm-3">
                    <label class="control-label"> اسم السائق </label>
                    <div>
                        <input type="text"  name="driver_id"   id="txt_driver_id" class="form-control " "="">
                    </div>
                </div>
                <!---------------------------تاريخ الحركة------------------------------>
                <div class="form-group col-sm-3">
                    <label class="control-label">  تاريخ الحركة </label>
                    <input type="text" data-type="date" data-date-format="DD/MM/YYYY"
                           name="the_date"
                           id="txt_the_date"
                           class="form-control">
                    </div>
                <!------------------------------------نوع الحركة-------------------------->
                <div class="form-group col-sm-3">
                    <label class="control-label"> نوع الحركة  </label>
                    <div>
                        <select
                            data-val="true"
                            name="movment_type"
                            id="txt_movment_type"
                            class="form-control">
                            <option></option>
                            <?php foreach ($movement_type as $row) : ?>
                                <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>



            </div>

            <div class="modal-footer">

                <button type="button" onclick="javascript:do_search();" class="btn btn-success"> إستعلام</button>
                <button type="button" onclick="javascript:clearForm_any($('fieldset'));do_search();" class="btn btn-default">تفريغ الحقول</button>

            </div>
        </fieldset>

        <div id="msg_container"></div>

        <div id="container">
            <?php echo modules::run('payment/carMovements/get_page',$page); ?>
        </div>

    </div>

</div>





