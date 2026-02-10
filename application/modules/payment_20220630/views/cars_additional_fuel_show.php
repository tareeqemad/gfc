<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 29/01/15
 * Time: 12:14 م
 */
$create_url = base_url('payment/cars_additional_fuel/create');
$get_url = base_url('payment/cars_additional_fuel/get');

$balance_url = base_url('payment/cars_coupon_fuel/public_balance_get');

if (!isset($result))
    $result = array();
$HaveRs = count($result) > 0;
$rs = $HaveRs ? $result[0] : $result;

$car_select_url = base_url('payment/cars/public_select_car/');
$adopt_action = $HaveRs ? ($rs['CARS_ADDITIONAL_FUEL'] == 2 ? 'adopt_fin' :($rs['CARS_ADDITIONAL_FUEL'] == 3 ? 'adopt_branch' : ($rs['CARS_ADDITIONAL_FUEL'] == 4 ? 'adopt_manager2' : ($rs['CARS_ADDITIONAL_FUEL'] == 5 ? 'adopt_manager3' : ($rs['CARS_ADDITIONAL_FUEL'] == 6 ? 'adopt_manager4' : 'adopt'))))) : 'adopt';
$adopt_url = base_url("payment/cars_additional_fuel/{$adopt_action}/");


?>
<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>

        <ul>
            <?php if (HaveAccess($create_url)): ?>
                <li><a href="<?= $create_url ?>"><i class="glyphicon glyphicon-plus"></i>جديد </a> </li><?php endif; ?>
            <li><a onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a>
            </li>

        </ul>

    </div>

    <div class="form-body">

        <div id="msg_container"></div>


        <div id="container">
            <form class="form-horizontal" id="cars_additional_fuel_form" method="post"
                  action="<?= base_url('payment/cars_additional_fuel/') . ($HaveRs ? '/edit' : '/create') ?>"
                  role="form" novalidate="novalidate">
                <div class="modal-body inline_form">

                    <div class="form-group">

                        <label class="col-sm-1 control-label"> رقم الطلب </label>

                        <div class="col-sm-1">
                            <input type="text" readonly name="cars_additional_fuel_id"
                                   value="<?= $HaveRs ? $rs['CARS_ADDITIONAL_FUEL_ID'] : '' ?>"
                                   id="txt_cars_additional_fuel_id" class="form-control">
                            <input type="hidden" name="cars_additional_fuel"
                                   value="<?= $HaveRs ? $rs['CARS_ADDITIONAL_FUEL'] : '' ?>">
                        </div>

                        <label class="col-sm-1 control-label"> الفرع</label>

                        <div class="col-sm-1">
                            <select type="text" name="branch_id" id="dp_branch_id" class="form-control">
                                <?php foreach ($branches as $row) : ?>
                                    <option <?= $HaveRs && $rs['BRANCH_ID'] == $row['NO'] ? 'selected' : '' ?>
                                        value="<?= $row['NO'] ?>"><?= $row['NAME'] ?></option>
                                <?php endforeach; ?>
                            </select>

                        </div>


                    </div>
                    <div class="form-group">
                        <label class="col-sm-1 control-label"> البيان</label>

                        <div class="col-sm-5">
                            <input type="text" name="declaration" value="<?= $HaveRs ? $rs['DECLARATION'] : '' ?>"
                                   data-val="true" data-val-required="حقل مطلوب" id="txt_declaration"
                                   class="form-control">


                        </div>
                    </div>
                    <hr>
                    <div style="clear: both;">
                        <?php echo modules::run('payment/cars_additional_fuel/public_get_cars_additional_fuel', (count($rs)) ? $rs['CARS_ADDITIONAL_FUEL_ID'] : 0, (count($rs)) ? $rs['CARS_ADDITIONAL_FUEL'] : -1, $can_edit); ?>
                    </div>

                </div>
                <div class="modal-footer">
                    <?php if (!$HaveRs || ($HaveRs && $rs['CARS_ADDITIONAL_FUEL'] == 1)): ?>
                        <button type="submit" data-action="submit" class="btn btn-primary">حفظ البيانات</button>
                    <?php endif; ?>

                  <!--  <?php /*if (($HaveRs && $rs['CARS_ADDITIONAL_FUEL'] >= 2 && $rs['CARS_ADDITIONAL_FUEL'] < 7)): */?>
                        <button type="button" onclick="javascript:save_cars_additional_fuel();" class="btn btn-default">
                            حفظ البيانات
                        </button>
                    --><?php /*endif; */?>

                    <?php if ($HaveRs && $rs['CARS_ADDITIONAL_FUEL'] >= 1 && $rs['CARS_ADDITIONAL_FUEL'] < 7): ?>
                        <button type="button" onclick="javascript:adopt_cars_additional_fuel();"
                                class="btn btn-danger">اعتماد
                        </button>
                    <?php endif; ?>
                </div>
            </form>


        </div>

    </div>

</div>
<?php

$today = date('d/m/Y');
$scripts = <<<SCRIPT

<script>


    reBind();

    function reBind(){
        $('input[name="car_num_name[]"]').click(function(){

                  _showReport('$car_select_url/'+$(this).attr('id')+'/');
            });

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

    function adopt_cars_additional_fuel(){

        var form = $('#cars_additional_fuel_form');

        form.attr('action','$adopt_url');

        ajax_insert_update(form,function(data){


            success_msg('رسالة','تم حفظ البيانات بنجاح ..');
            get_to_link('{$get_url}/'+data);

        },"html");
    }

    function show_car_balance(a){

        _showReport('{$balance_url}?file_id='+$(a).closest('tr').find('input[name="car_num[]"]').val()+'&date={$today}&ser=0');
    }

 function save_cars_additional_fuel(){

        var form = $('#cars_additional_fuel_form');

        form.attr('action','$adopt_url/1/true');

        ajax_insert_update(form,function(data){


            success_msg('رسالة','تم حفظ البيانات بنجاح ..');
            get_to_link('{$get_url}/'+data);

        },"html");
    }

</script>

SCRIPT;

sec_scripts($scripts);



?>
