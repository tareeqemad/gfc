
<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 06/10/2022
 * Time: 10:02 AM
 */

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$get_page_url = base_url('treasury/workfield/devices_get_page');
$devices_url =base_url('treasury/workfield/public_get_devices');
$all_devices_url =base_url('treasury/workfield/public_get_all_devices');
$devices_name_url =base_url('treasury/workfield/public_get_devices_name');
$create_device_url =base_url('treasury/workfield/create_device');
$device_delivery_url = base_url('treasury/workfield/deviceDelivery');
$change_status_device_url = base_url('treasury/workfield/change_status_device');

?>

<?= AntiForgeryToken() ?>

<div class="row">
    <div class="toolbar">
        <div class="caption"><?= $title ?></div>

        <ul>
            <?php if( HaveAccess($create_device_url)):  ?>
                <li><a href="javascript:;"  onclick="javascript:showCreateModal();" > <i class="glyphicon glyphicon-plus"></i>اضافة جهاز جديد </a></li>
            <?php endif; ?>
            <?php if( HaveAccess($device_delivery_url)):  ?>
                <li><a href="javascript:;"  onclick="javascript:showModal();" > <i class="glyphicon glyphicon-plus-sign"></i>تسليم جهاز </a></li>
            <?php endif; ?>
            <?php if( HaveAccess($change_status_device_url)):  ?>
                <li><a href="javascript:;"  onclick="javascript:showDeactiviateModal();" > <i class="glyphicon glyphicon-remove"></i>اتلاف جهاز </a></li>
            <?php endif; ?>
        </ul>


    </div>

    <div class="form-body">

        <div id="msg_container"></div>
        <fieldset>
            <legend>بحـث</legend>
            <div class="modal-body inline_form">
                <div class="form-group col-sm-2">
                    <label class="control-label"> اسم المحصل </label>
                    <div class="">
                        <select name="user" id="dp_user" class="form-control select2" >
                            <option value="">----------</option>
                            <?php foreach($users as $row) :?>
                                <option value="<?=$row['NO']?>"><?=$row['NO'].': '.$row['NAME']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>


                <div class="form-group col-sm-2">
                    <label class="control-label"> رقم الجهاز </label>
                    <div class="">
                        <select name="device" id="dp_device" class="form-control select2" >
                            <option value="">----------</option>
                            <?php foreach($devices as $row) :?>
                                <option value="<?=$row['SER']?>"><?=$row['DEVICE_NO']?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label"> تاريخ الاستلام</label>
                    <div class="">
                        <input type="text" data-val-required="حقل مطلوب" data-type="date"
                               data-date-format="YYYY/MM/DD"
                               name="received_date" id="txt_received_date" class="form-control valid">
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label"> تاريخ التسليم</label>
                    <div class="">
                        <input type="text" data-val-required="حقل مطلوب" data-type="date"
                               data-date-format="YYYY/MM/DD"
                               name="delivery_date" id="txt_delivery_date" class="form-control valid">
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" onclick="javascript:do_search();" id="btn_test" class="btn btn-success"> إستعلام</button>
            </div>

        </fieldset>

        <div id="container">
            <?php //echo modules::run('treasury/workfield/devices_get_page',date('Y/m/d')); ?>
        </div>

    </div>

</div>


<div class="modal fade" id="DeviceModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title"> تسليم جهاز </h4>
            </div>
            <div>
                <div class="padding-20">
                    <form onsubmit="javascript:device_delivery(event);" >
                        <div class="form-group row mt-20">
                            <label class="control-label col-md-4">رقم المحصل</label>
                            <div class="col-md-5">
                                <select name="user_modal" id="dp_user_modal" class="form-control sel" data-allow-select2="true" >
                                    <option value="">----------</option>
                                    <?php foreach($users as $row) :?>
                                        <option value="<?=$row['NO']?>"><?=$row['NO'].': '.$row['NAME']?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="control-label col-md-4">رقم الجهاز</label>
                            <div class="col-md-5">
                                <select name="device_modal" id="dp_device_modal" class="form-control select2" >
                                    <option value="">----------</option>
                                    <?php foreach($devices as $row) :?>
                                        <option value="<?=$row['SER']?>"><?=$row['DEVICE_NO']?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <?php if( HaveAccess($device_delivery_url)):  ?>
                            <div class="modal-footer">
                                <button type="submit"  class="btn btn-primary">
                                    تسليم الجهاز
                                </button>
                            </div>
                        <?php endif; ?>

                    </form>
                </div>
            </div>

        </div> <!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<div class="modal fade" id="CreateDeviceModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title"> اضافة جهاز </h4>
            </div>
            <div>
                <div class="padding-20">
                    <form onsubmit="javascript:device_create(event);" >
                        <div class="form-group row mt-20">
                            <label class="control-label col-md-4">رقم الجهاز</label>
                            <div class="col-md-5">
                                <select name="device_no_modal" id="dp_device_no_modal" class="form-control select2" >
                                    <option value="">----------</option>
                                    <?php foreach($devices_name as $row) :?>
                                        <option value="<?=$row['DEVICE_NO']?>"><?=$row['DEVICE_NO']?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <?php if( HaveAccess($create_device_url)):  ?>
                            <div class="modal-footer">
                                <button type="submit"  class="btn btn-primary">
                                   اضافة
                                </button>
                            </div>
                        <?php endif; ?>

                    </form>
                </div>
            </div>

        </div> <!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<div class="modal fade" id="DeactiviateDeviceModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title"> اتلاف جهاز </h4>
            </div>
            <div>
                <div class="padding-20">
                    <form onsubmit="javascript:device_deactive(event);" >
                        <div class="form-group row">
                            <label class="control-label col-md-4">رقم الجهاز</label>
                            <div class="col-md-5">
                                <select name="deactive_device_modal" id="dp_deactive_device_modal" class="form-control select2" >
                                    <option value="">----------</option>
                                    <?php foreach($devices as $row) :?>
                                        <option value="<?=$row['SER']?>"><?=$row['DEVICE_NO']?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <?php if( HaveAccess($change_status_device_url)):  ?>
                            <div class="modal-footer">
                                <button type="submit"  class="btn btn-danger">
                                    تعطيل
                                </button>
                            </div>
                        <?php endif; ?>

                    </form>
                </div>
            </div>

        </div> <!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->



<?php

$scripts = <<<SCRIPT

<script>
    function do_search(){
        get_data('{$get_page_url}',{page: 1 ,received_date : $('#txt_received_date').val(), user_no : $('#dp_user').val() , 
        delivery_date : $('#txt_delivery_date').val(), device : $('#dp_device').val()
        },function(data){
            $('#container').html(data);
        },'html');
    }
    
    function showModal(){
        $("#dp_user_modal").select2("val", "0");
        $("#dp_device_modal").select2("val", "0");
        get_data('{$devices_url}',{},function(data){
                $('#dp_device_modal').html('');
                $.each(data,function(index, item)
                {
                    $('#dp_device_modal').append('<option value=' + item.SER + '>' + item.DEVICE_NO + '</option>');
                });
        });
        $('#DeviceModal').modal();
    }
    
    function showCreateModal(){
        $("#dp_device_no_modal").select2("val", "0");
        get_data('{$devices_name_url}',{},function(data){
                $('#dp_device_no_modal').html('');
                $.each(data,function(index, item)
                {
                    $('#dp_device_no_modal').append('<option value=' + item.DEVICE_NO + '>' + item.DEVICE_NO + '</option>');
                });
        });
        $('#CreateDeviceModal').modal();
    }
    
    function showDeactiviateModal(){
        $("#dp_deactive_device_modal").select2("val", "0");
        get_data('{$all_devices_url}',{},function(data){
                $('#dp_deactive_device_modal').html('');
                $.each(data,function(index, item)
                {
                    $('#dp_deactive_device_modal').append('<option value=' + item.SER + '>' + item.DEVICE_NO + '</option>');
                });
        });
        $('#DeactiviateDeviceModal').modal();
    }
    

</script>
SCRIPT;

sec_scripts($scripts);



?>

