<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 24/04/19
 * Time: 10:51 ص
 */

$MODULE_NAME = 'payment';
$TB_NAME = 'carMovements';
$back_url = base_url("$MODULE_NAME/$TB_NAME");
$create_url = base_url("$MODULE_NAME/$TB_NAME/create_details");
$creates_url = base_url("$MODULE_NAME/$TB_NAME/create");


if (!isset($result))
    $result = array();
$HaveRs = count($result) > 0;

$rs = $HaveRs ? $result[0] : $result;

$post_url = base_url("payment/carMovements/" . ($action == 'index' ? 'create' : $action));


?>


<script type="text/javascript"
        src="//maps.google.com/maps/api/js?libraries=geometry&language=ar&sensor=true&key=AIzaSyB3V-XUnQii5hBErN1ntpmHOXiT9lFbY08"></script>


<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>

        <ul>
            <?php if (HaveAccess($creates_url)): ?>
                <li><a href="<?= $creates_url ?>"><i class="glyphicon glyphicon-plus"></i>جديد</a> </li><?php endif; ?>
            <li><a href="<?= $back_url ?>"><i class="icon icon-reply"></i> </a></li>
        </ul>

    </div>

</div>


<div class="form-body">

    <div id="container">
        <form class="form-horizontal" id="<?= $TB_NAME ?>_form" method="post" action="<?= $post_url ?>" role="form"
              novalidate="novalidate">
            <div class="modal-body inline_form">

                <fieldset class="field_set">
                    <legend>بيانات الحركة</legend>
                    <div class="form-group">

                        <label class="col-sm-1 control-label">رقم المسلسل</label>
                        <div class="col-sm-2">
                            <input type="text" readonly name="ser" id="txt_SER" class="form-control"
                                   value="<?= $HaveRs ? $rs['SER'] : "" ?>">


                        </div>

                    </div>


                    <div class="form-group">

                        <label class="col-sm-1 control-label">صاحب العهدة </label>
                        <div class="col-sm-2">

                            <input type="hidden"
                                   value="<?= $HaveRs ? $rs['CAR_ID'] : "" ?>"
                                   name="car_id" id="h_txt_car_id_name"
                                   class="form-control">

                            <input type="text"
                                   data-val-required="حقل مطلوب"
                                   data-val="true"
								   name="car_owner"
                                   readonly
                                   value="<?= $HaveRs ? $rs['CAR_ID_NAME'] : "" ?>"
                                   id="txt_car_id_name"
                                   class="form-control">

                        </div>









                        <label class="col-sm-1 control-label"> السائق</label>
                        <div class="col-sm-2">
                            <input type="hidden"
                                   value="<?php echo @$rs['DRIVER_ID']; ?>"
                                   name="driver_id"
                                   id="h_txt_driver_name" class="form-control">

                            <input type="text"
                                   data-val-required="حقل مطلوب"
                                   data-val="true"
                                   value="<?= $HaveRs ? $rs['DRIVER_ID_NAME'] : "" ?>"
                                   readonly
                                   id="txt_driver_name"
                                   class="form-control">
                        </div>

                    </div>

                    <div class="form-group">
                        <label class="col-sm-1 control-label">نوع الحركة</label>
                        <div class="col-sm-2">

                            <select
                                    data-val-required="حقل مطلوب"
                                    data-val="true"
                                    name="movment_type"
                                    id="dp_movement_type"
                                    class="form-control">
                                <option></option>
                                <?php foreach ($movement_type as $row) : ?>
                                    <option value="<?= $row['CON_NO'] ?>" <?= $HaveRs ? ($rs['MOVMENT_TYPE'] == $row['CON_NO'] ? "selected" : "") : "" ?> ><?= $row['CON_NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>

                        </div>

                        <label class="col-sm-1 control-label">تاريخ الحركة</label>
                        <div class="col-sm-2">
                            <input type="text" data-val-required="حقل مطلوب" data-type="date"
                                   data-date-format="DD/MM/YYYY" name="the_date"
                                   value="<?= $HaveRs ? $rs['THE_DATE'] : "" ?>"
                                   id="txt_the_date" class="form-control">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-1 control-label">ملاحظات</label>
                        <div class="col-sm-9">
                            <textarea data-val-required="حقل مطلوب"
                                class="form-control" name="notes" rows="7"
                                      id="txt_notes"><?= $HaveRs ? $rs['NOTES'] : "" ?></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                    </div>


                </fieldset>


                <hr/>


                <?php if ($HaveRs) { ?>


                    <fieldset class="field_set">
                        <legend>تفاصيل حركة السيارة</legend>

                        <br>

                        <div class="modal-footer">
                            <button id="modal_movment_det" type="button" class="btn btn-primary">اضافة حركة</button>
                        </div>


                        <div class="details" id="movements_details_container">
                            <?php echo modules::run('payment/carMovements/public_list_car_movements_det', $HaveRs ? $rs['SER'] : ""); ?>

                        </div>

                    </fieldset>

                    <hr/>

                <?php } ?>


            </div>

        </form>


    </div>

</div>

</div>


<div class="modal fade" id="myModal">
    <div id="myModal" role="dialog">
        <div class="modal-dialog">

            <div id="SSS" class="modal-content">
                <div class="modal-header">
                    <h3 id="title" class="modal-title">اضافة حركة جديدة</h3>
                </div>
                <div class="modal-body">
                    <div class="modal-body">
                        <form id="<?= $TB_NAME ?>_form" method="post" action="<?= $create_url ?>">

                            <div class="row">
                                <!-------------------------------------الغرض من الحركة----------------------------------------->
                                <div class="form-group col-sm-4">
                                    <label class="control-label">الغرض من الحركة </label>
                                    <div>

                                        <input type="hidden"
                                               name="car_mov_id"
                                               value="<?= $HaveRs ? $rs['SER'] : "" ?>"
                                               id="txt_car_mov_id">

                                        <select
                                                data-val-required="حقل مطلوب"
                                                data-val="true"
                                                name="purpose_type"
                                                id="txt_purpose_type"
                                                class="form-control">
                                            <option></option>
                                            <?php foreach ($movement_purpose as $row) : ?>
                                                <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                            <?php endforeach; ?>
                                        </select>


                                    </div>


                                </div>
                            </div>

                            <div class="row">
                                <!-------------------------وقت المغادرة المتوقع---------------------->
                                <div class="form-group col-sm-4">
                                    <label class="control-label"> وقت المغادرة المتوقع </label>
                                    <div>
                                        <input type="text"
                                               placeholder="24:00"
                                               name="expected_leave_time"
                                               id="txt_expected_leave_time"
                                               data-val-required="حقل مطلوب"
                                               data-val="true"
                                               class="form-control">
                                    </div>
                                </div>
                                <!---------------------------------وقت الوصول المتوقع----------------------------->
                                <div class="form-group col-sm-4">
                                    <label class="control-label">وقت الوصول المتوقع</label>
                                    <div>
                                        <input type="text"
                                               placeholder="24:00"
                                               name="expected_arrival_time"
                                               id="txt_expected_arrival_time"
                                               data-val-required="حقل مطلوب"
                                               data-val="true"
                                               class="form-control">
                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                <!----------------------------عنوان الانطلاق--------------------------------->
                                <div class="form-group col-sm-12">
                                    <label class="control-label">عنوان الانطلاق</label>
                                    <div>
                                        <input type="text"
                                               name="from_address"
                                               id="txt_from_address"
                                               data-val-required="حقل مطلوب"
                                               data-val="true"
                                               class="form-control ">
                                    </div>
                                </div>
                            </div>
                            <div class="row">

                                <!----------------------------------عنوان الهدف---------------------------->
                                <div class="form-group col-sm-12">
                                    <label class="control-label">عنوان الهدف</label>
                                    <div>
                                        <input type="text"
                                               name="to_address"
                                               id="txt_to_address"
                                               data-val-required="حقل مطلوب"
                                               data-val="true"
                                               class="form-control">
                                    </div>
                                </div>

                            </div>

                            <div class="row">
                                <!-------------------------المحلاحظات--------------------------->
                                <div class="form-group col-sm-12">
                                    <label class="control-label">الملاحظات</label>
                                    <div>
                                        <input type="text"
                                               name="notes"
                                               id="txt_notes_"
                                               class="form-control ">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <!------------------------------موقع الانهاء المحدد مسبقا------------------------------------------------->
                                <div class="form-group col-sm-4">
                                    <label class="control-label">موقع الانطلاق </label>
                                    <div>
                                        <input type="hidden"
                                               name="predefine_start_gps" id="txt_predefine_start_gps"
                                               class="form-control ">
                                        <button type="button" class="btn green"
                                                onclick="_showReport('<?= base_url('maps') ?>/public_map_location/txt_predefine_start_gps');">
                                            <i class="icon icon-map-marker"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="form-group col-sm-4">
                                    <label class="control-label">موقع الهدف </label>
                                    <div>
                                        <input type="hidden" name="predefine_finished_gps"
                                               id="txt_predefine_finished_gps"
                                               class="form-control ">
                                        <button type="button" class="btn green"
                                                onclick="_showReport('<?= base_url('maps') ?>/public_map_location/txt_predefine_finished_gps');">
                                            <i class="icon icon-map-marker"></i>
                                        </button>
                                    </div>
                                </div>

                            </div>


                            <!---------------------------------------------------------->
                            <div class="modal-footer">
                                <button type="submit" id="add_mov_det" data-action="submit" class="btn btn-primary">حفظ
                                    البيانات
                                </button>
                                <button type="button" class="btn btn-danger" data-dismiss="modal">اغلاق</button>

                            </div>


                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php


$scripts = <<<SCRIPT

<script>



</script>

SCRIPT;

sec_scripts($scripts);
?>
