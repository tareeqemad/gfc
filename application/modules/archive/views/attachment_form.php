<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 23/10/14
 * Time: 11:33 ص
 */

//echo $id.'klkkl';
?>

    <div class='form-group'>يمكن رفع عدد غير محدود من الملفات</div>
    <div class='form-group'>الحد الاقصى المسموح به لكل ملف هو 300 ميجا بايت</div>
    <div class='form-group'>الملفات المسموح رفعها هي word | excel | pdf | zip | rar </div>
    <form id='upload_file' action="<?=base_url("archive/archive/upload_file/$id")?>" method='post' accept-charset='utf-8' enctype='multipart/form-data'>
        <div class='form-group'>
            <label class='col-sm-2 control-label'>اختر الملف</label>
            <div class='col-sm-7'><input type='file' name='file' value=''  /></div>
            <div class='col-sm-3'><button type='submit' data-action='submit' class='btn btn-primary'><i class='glyphicon glyphicon-upload'></i>رفع</button></div>
        </div>
        <div class='form-group'>
            <label class='col-sm-12 control-label'><div id='msg'></div></label>
        </div>
        <div class='form-group'>
            <div class='col-sm-12' id='progress' style='display: none'><progress></progress></div>
        </div>
    </form>

<script type="text/javascript" src="<?=base_url("assets/js/ajax_upload_file.js")?>"></script>



