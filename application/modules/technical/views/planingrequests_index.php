<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 5/28/2017
 * Time: 9:14 AM
 */

?>

<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>

    </div>

    <div class="form-body">
        <fieldset>
            <legend>بحـث</legend>

            <div class="modal-body inline_form">

                <div class="form-group  col-sm-1">
                    <label class="control-label">رقم الطلب </label>

                    <div>
                        <input type="text" id="requestId" class="form-control"/>
                    </div>
                </div>

            </div>

            <div class="modal-footer">

                <button type="button" onclick="javascript:do_search();" class="btn btn-success">بحث</button>

                <button type="button" onclick="javascript:clearForm_any($('fieldset'));do_search();"
                        class="btn btn-default">تفريغ الحقول
                </button>

            </div>
        </fieldset>

        <div id="msg_container">

            <table class="table table-striped table-bordered table-hover" id="tbl">
                <thead>
                <tr>

                    <th style="width:10%">رقم الطلب</th>
                    <th style="width:15%">مقدم الطلب</th>
                    <th style="width:15%">اسم المشترك</th>
                    <th style="width:10%">نوع الخدمة</th>
                    <th style="width:20%">الخدمة/نوع العداد</th>
                    <th style="width:7%">رقم الملف</th>
                    <th style="width:10%">المنطقة الصغرى</th>
                    <th style="width:7%">رقم الجوال</th>
                    <th style="width:10%">خيارات</th>
                </tr>
                </thead>
                <tbody>

                </tbody>

            </table>
        </div>

    </div>

</div>




<?php

$service_url = $_SERVER['SERVER_NAME'];

$scripts = <<<SCRIPT

<script>

function do_search(){

    get_data('http://{$service_url}/Trading/Groups/service_work_day_result_tec/work_day_service/'+$('#requestId').val(),{},function(data){

    $('#tbl tbody').html('');

    $(data).each(function(i){


       $('#tbl tbody').append('<tr><td>'+data[0]['REQUEST_APP_SERIAL']+'</td>'+
                              '<td>'+data[0]['APPLICANT_NAME']+'</td>'+
                              '<td>'+data[0]['SUBSCRIBER_NAME']+'</td>'+
                              '<td>'+data[0]['SERVICE_TYPE_NAME']+'</td>'+
                              '<td>'+data[0]['SERVICE_ID_NAME']+'</td>'+
                              '<td>'+data[0]['FILE_NO']+'</td>'+
                              '<td>'+data[0]['JUNIOR_REGION_NAME']+'</td>'+
                              '<td>'+data[0]['JAWWAL']+'</td>'+
                              '<td><a href="javascript:;" onclick="javascript:create_request('+data[0]['REQUEST_APP_SERIAL']+');">تحويل لطلب فني</a></td></tr>');

    });

    });

}

function create_request(id){

                 get_data('',{id:id},function(data){

                            window.location = 'get/'+data+'/index';
                        });


}

</script>
SCRIPT;

sec_scripts($scripts);



?>
