<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 29/01/15
 * Time: 12:14 م
 */
$create_url = base_url('payment/cars_fuel_transfer/create');
$cancel_url = base_url('payment/cars_fuel_transfer/cancel');
$get_url = base_url('payment/cars_fuel_transfer/get');
$back_url = base_url('payment/cars_fuel_transfer/');

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
            <form class="form-horizontal" id="cars_fuel_transfer_form" method="post"
                  action="<?= base_url('payment/cars_fuel_transfer/') . ($HaveRs ? '/edit' : '/create') ?>"
                  role="form" novalidate="novalidate">
                <div class="modal-body inline_form">

                    <div class="col-md-9">
                        <div class="form-group">


                            <label class="col-sm-1 control-label"> م. </label>

                            <div class="col-sm-1">
                                <input type="text" readonly name="ser"
                                       value="<?= $HaveRs ? $rs['SER'] : '' ?>"
                                       id="ser" class="form-control">
                            </div>


                        </div>
                        <div class="form-group">

                            <label class="col-sm-1 control-label">من سيارة</label>

                            <div class="col-sm-3">

                                <input type="hidden" name="from_file_id" id="h_from_file_id"
                                       value="<?= $HaveRs ? $rs['FROM_FILE_ID'] : '' ?>">
                                <input type="text" readonly id="from_file_id"
                                       value="<?= $HaveRs ? $rs['FROM_FILE_ID_NAME'] : '' ?>"
                                       class="form-control">

                            </div>

                            <label class="col-sm-1 control-label">الي سيارة </label>

                            <div class="col-sm-3">

                                <input type="hidden" name="to_file_id" id="h_to_file_id"
                                       value="<?= $HaveRs ? $rs['TO_FILE_ID'] : '' ?>">
                                <input type="text" readonly id="to_file_id"
                                       value="<?= $HaveRs ? $rs['TO_FILE_ID_NAME'] : '' ?>"
                                       class="form-control">

                            </div>


                        </div>


                        <div class="form-group">


                            <label class="col-sm-1 control-label">الكمية </label>

                            <div class="col-sm-1">
                                <input type="text" name="the_count"
                                       data-val="true" data-val-required="حقل مطلوب"
                                       value="<?= $HaveRs ? $rs['THE_COUNT'] : '' ?>"
                                       id="the_count" class="form-control">
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
                    </div>
                    <div class="col-md-3" id="balance_div">

                    </div>

                </div>
                <div class="modal-footer">
                    <?php if (!$HaveRs /*|| ($HaveRs && $rs['COUPON_FUEL_CASE'] == 1)*/): ?>
                        <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                    <?php endif; ?>

                    <?php if (($HaveRs && $rs['STATUS'] == 1 && HaveAccess($cancel_url))): ?>
                        <button type="button" onclick="javascript:cars_coupon_cancel(<?= $rs['SER'] ?>);"
                                class="btn btn-danger">الغاء السند
                        </button>
                    <?php endif; ?>


                </div>
            </form>


        </div>

    </div>

</div>
<?php

$car_select_url = base_url('payment/cars/public_select_car/');
$customer_url = base_url('payment/customers/public_index');
$balance_url = base_url('payment/cars_fuel_transfer/public_balance');
$current_date = date('d/m/Y');
$scripts = <<<SCRIPT

<script>


    reBind();

    function reBind(){
            $('#from_file_id,#to_file_id').click(function(){

                  _showReport('$car_select_url/'+$(this).attr('id')+'/');
            });



    }


    function cars_coupon_cancel(id){

         if( confirm('هل تريد الغاء السند')){
            get_data('$cancel_url',{ser:id},function(data){
                    success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                    get_to_link('{$get_url}/'+data);
            },'html');

         }
    }

    if('{$HaveRs}' == '1') afterSelect();
    function afterSelect(){

            get_data('/gfc/payment/cars_coupon_fuel/public_balance',{file_id:$('#h_from_file_id').val(),date:'{$current_date}',ser:$('#ser').val()},function(data){
                   $('#balance_div').html(data);
            },'html');

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
