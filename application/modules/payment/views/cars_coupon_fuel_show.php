<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 29/01/15
 * Time: 12:14 م
 */
$create_url = base_url('payment/cars_coupon_fuel/create');
$cancel_url = base_url('payment/cars_coupon_fuel/cancel');
$get_url = base_url('payment/cars_coupon_fuel/get');
$back_url = base_url('payment/cars_coupon_fuel/');

if (!isset($result))
    $result = array();
$HaveRs = count($result) > 0;
$rs = $HaveRs ? $result[0] : $result;

?>
<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>

        <ul>
            <?php if (HaveAccess($create_url)): ?>
                <li><a href="<?= $create_url ?>"><i class="glyphicon glyphicon-plus"></i>جديد </a> </li><?php endif; ?>
            <?php if (HaveAccess($back_url)): ?>
                <li><a href="<?= $back_url ?>"><i class="icon icon-reply"></i> </a> </li><?php endif; ?>
            <li><a onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a>
            </li>

        </ul>

    </div>

    <div class="form-body">

        <div id="msg_container"></div>


        <div id="container">
            <form class="form-horizontal" id="cars_coupon_fuel_form" method="post"
                  action="<?= base_url('payment/cars_coupon_fuel/') . ($HaveRs ? '/edit' : '/create') ?>"
                  role="form" novalidate="novalidate">
                <div class="modal-body inline_form">

                    <div class="col-md-9">


                        <div class="form-group">


                            <label class="col-sm-1 control-label"> المورد </label>

                            <div class="col-sm-4">
                                <select name="supplier"
                                        data-val="true"
                                        data-val-required="حقل مطلوب"
                                        class="form-control">
                                    <option></option>
                                    <?php foreach ($suppliers as $row) : ?>
                                        <option <?= $HaveRs ? ($rs['SUPPLIER'] == $row['CON_NO'] ? 'selected' : '') : '' ?>
                                                value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>


                        </div>

                        <div class="form-group">


                            <label class="col-sm-1 control-label"> م. </label>

                            <div class="col-sm-1">
                                <input type="text" readonly name="ser"
                                       value="<?= $HaveRs ? $rs['SER'] : '' ?>"
                                       id="ser" class="form-control">
                            </div>


                            <label class="col-sm-1 control-label">المستلم</label>

                            <div class="col-sm-2">

                                <input type="hidden" name="emp_id" id="h_emp_id"
                                       value="<?= $HaveRs ? $rs['EMP_ID'] : '' ?>">
                                <input type="text" readonly id="emp_id" value="<?= $HaveRs ? $rs['EMP_ID_NAME'] : '' ?>"
                                       class="form-control">

                            </div>
                        </div>
                        <div class="form-group">

                            <label class="col-sm-1 control-label"> رقم الكوبون </label>

                            <div class="col-sm-1">
                                <input type="text" name="cars_coupon_fuel_id"
                                       value="<?= $HaveRs ? $rs['COUPON_FUEL_ID'] : '' ?>"
                                       readonly
                                       id="txt_cars_coupon_fuel_id" class="form-control">
                            </div>


                            <label class="col-sm-1 control-label">اسم الألية</label>

                            <div class="col-sm-2">

                                <input type="hidden" name="car_file_id" id="h_car_file_id"
                                       value="<?= $HaveRs ? $rs['CAR_FILE_ID'] : '' ?>">
                                <input type="text" readonly id="car_file_id" value="<?= $HaveRs ? $rs['OWNER'] : '' ?>"
                                       class="form-control">

                            </div>


                        </div>


                        <div class="form-group">

                            <label class="col-sm-1 control-label">نوع الوقود</label>

                            <div class="col-sm-1">

                                <select id="fuel_type_car_file_id" name="fuel_type" class="form-control" readonly>
                                    <option></option>
                                    <?php foreach ($fuel_type as $row): ?>
                                        <option value="<?= $row['CON_NO'] ?>" <?= $HaveRs && $row['CON_NO'] == $rs['FUEL_TYPE'] ? 'selected'  : ''   ?>><?= $row['CON_NAME'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
							
							
                            <label class="col-sm-1 control-label">متابعة العداد</label>

                            <div class="col-sm-1">

                                <select id="car_case_id" name="car_case" class="form-control" readonly>
                                    <option></option>
                                    <?php foreach ($car_case as $row): ?>
                                        <option value="<?= $row['CON_NO'] ?>" <?= $HaveRs && $row['CON_NO'] == $rs['CAR_CASE'] ? 'selected' : '' ?>><?= $row['CON_NAME'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                        </div>

                        <div class="form-group">


                            <label class="col-sm-1 control-label">الكمية </label>

                            <div class="col-sm-1">
                                <input type="text" name="coupon_fuel_amount"
                                       value="<?= $HaveRs ? $rs['COUPON_FUEL_AMOUNT'] : '' ?>"
                                       id="coupon_fuel_amount" class="form-control">
                            </div>


                            <label class="col-sm-1 control-label">تاريخ الكوبون</label>

                            <div class="col-sm-1">

                                <input type="text" name="coupon_fuel_date"
                                       value="<?= $HaveRs ? $rs['COUPON_FUEL_DATE'] : date('d/m/Y') ?>"
                                       data-type="date" data-date-format="DD/MM/YYYY"
                                       readonly
                                       data-val-regex="التاريخ غير صحيح!"
                                       data-val-regex-pattern="<?= date_format_exp() ?>"
                                       id="coupon_fuel_date" class="form-control">

                            </div>


                        </div>


                        <div class="form-group">
                            <label class="col-sm-1 control-label"> البيان</label>

                            <div class="col-sm-5">
                                <input type="text" name="hints" value="<?= $HaveRs ? $rs['HINTS'] : '' ?>"
                                       data-val="true" data-val-required="حقل مطلوب" id="hints"
                                       class="form-control">


                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-1 control-label"> قراءة العداد</label>

                            <div class="col-sm-3">
                                <input type="text" name="metar_count" value="<?= $HaveRs ? $rs['METAR_COUNT'] : '' ?>"
                                       data-val="true" data-val-required="حقل مطلوب" id="metar_count"
                                       class="form-control">


                            </div>
                        </div>
                    </div>
                    <div class="col-md-3" id="balance_div">

                    </div>

                </div>
                <div class="modal-footer">
                    <?php if (!$HaveRs /*|| ($HaveRs && $rs['COUPON_FUEL_CASE'] == 1)*/): ?>
                        <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                    <?php endif; ?>

                    <?php if (($HaveRs && $rs['COUPON_FUEL_CASE'] == 1 && HaveAccess($cancel_url))): ?>
                        <button type="button" onclick="javascript:cars_coupon_cancel(<?= $rs['SER'] ?>);"
                                class="btn btn-danger">الغاء الكوبون
                        </button>
                    <?php endif; ?>
                    <?php if (($HaveRs && $rs['COUPON_FUEL_CASE'] == 1)): ?>
                        <button type="button"
                                onclick="javascript:_showReport('<?= base_url("JsperReport/showreport?sys=financial&report=coupon&id={$rs['SER']}") ?>');"
                                class="btn btn-default"> طباعة
                        </button>

                        <!-- <button type="button"
                                onclick="javascript:_showReport('<? /* base_url("JsperReport/showreport?sys=financial&report=coupon_1&id={$rs['SER']}") */ ?>');"
                                class="btn btn-default"> الحلو
                        </button>-->

                    <?php endif; ?>

                </div>
            </form>


        </div>

    </div>

</div>
<?php

$car_select_url = base_url('payment/cars/public_select_car/');
$customer_url = base_url('payment/customers/public_index');
$balance_url = base_url('payment/cars_coupon_fuel/public_balance');
$scripts = <<<SCRIPT

<script>


    reBind();

    function reBind(){
            $('#car_file_id').click(function(){

                  _showReport('$car_select_url/'+$(this).attr('id')+'/');
            });

            $('#emp_id').click(function(){

                _showReport('$customer_url/'+$(this).attr('id')+'/3');

            });
            
            $('#fuel_type_car_file_id option').hide();
			$('#car_case_id option').hide();

    }


    function cars_coupon_cancel(id){

         if( confirm('هل تريد الغاء الكوبون')){
            get_data('$cancel_url',{ser:id},function(data){
                    success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                    get_to_link('{$get_url}/'+data);
            },'html');

         }
    }

    if('{$HaveRs}' == '1') afterSelect();
    function afterSelect(){

            get_data('$balance_url',{file_id:$('#h_car_file_id').val(),date:$('#coupon_fuel_date').val(),ser:$('#ser').val()},function(data){
                   $('#balance_div').html(data);
            },'html');
            
            var selectedFuel = $('#fuel_type_car_file_id').val();
           
            $('#fuel_type_car_file_id option').hide();
            if(parseInt(selectedFuel) == 1){    
                $('#fuel_type_car_file_id option[value="1"],#fuel_type_car_file_id option[value="3"]').show();
            }else {
                $('#fuel_type_car_file_id option[value="2"],#fuel_type_car_file_id option[value="4"]').show();
            }
			$('#fuel_type_car_file_id option').hide();

    }

    $('button[data-action="submit"]').click(function(e){

        e.preventDefault();

        var form = $(this).closest('form');

        var isCreate = form.attr('action').indexOf('create') >= 0;

        ajax_insert_update(form,function(data){


            success_msg('رسالة','تم حفظ البيانات بنجاح ..');
            get_to_link('{$get_url}/'+data);

        },"html");

    });

</script>

SCRIPT;

sec_scripts($scripts);


?>
