<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 18/03/18
 * Time: 09:52 ص
 */

if($this->session->userdata('db_instance') == 'GFCARCH'){
    $sys_year='2015';
}elseif($this->session->userdata('db_instance') == 'GFCTRANS'){
    $sys_year='2016';
}else{
    $sys_year='2017';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= isset($title) ? $title : ''; ?></title>
    <link href="<?= base_url() ?>assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= base_url() ?>assets/css/bootstrap-theme.min.css" rel="stylesheet">
    <link href="<?= base_url() ?>assets/css/bootstrap-rtl.min.css" rel="stylesheet">

    <link href="<?= base_url() ?>assets/css/font-awesome.min.css" rel="stylesheet">
    <link href="<?= base_url() ?>assets/css/font-awesome2.min.css" rel="stylesheet">

    <link href="<?= base_url() ?>assets/css/button.css" rel="stylesheet">
    <link href="<?= base_url() ?>assets/css/stylesheets.css" rel="stylesheet">
    <link href="<?= base_url() ?>assets/css/toastr.css" rel="stylesheet">
    <link href="<?= base_url() ?>assets/css/jquery.calculadora.css" rel="stylesheet">

    <!--    <link href="<? /*= base_url() */ ?>assets/css/jquery.jscrollpane.css" rel="stylesheet">-->
    <?= put_headers('css') ?>
    <?php if ($sys_year == 2015) : ?>
        <style>
            .navbar-nav li.first {
                background-color: #f0ad4e;
            }
        </style>
    <?php endif; ?>

    <?php if ($sys_year == 2016) : ?>
        <style>
            .navbar-nav li.first {
                background-color: #902cd0;
            }
        </style>
    <?php endif; ?>
</head>
<body>

<noscript>
    <div
        style="width:100%; height:100%; top:0px; left:0px; position:fixed; display:block; opacity:0.85; background-color:#fff; z-index:99; text-align:center;">
        <h1 style="margin-top: 200px">المتصفح الخاص بك لا يدعم الجافا سكربت</h1>
    </div>
</noscript>



<div class="container">

    <!-- main content begin -->
    <div class="main-content">
        <?php
        if (isset($content)) $this->load->view($content);
        ?>
    </div>
    <!-- / end main content -->
    <!-- footer begin -->
    <div class="footer"></div>
    <!-- / end footer -->
</div>

<script src="<?= base_url() ?>assets/js/jquery-1.11.1.min.js"></script>
<script src="<?= base_url() ?>assets/js/jquery-ui.min.js"></script>

<script src="<?= base_url() ?>assets/js/bootstrap.min.js"></script>

<script src="<?= base_url() ?>assets/js/jqueryval.min.js"></script>
<script src="<?= base_url() ?>assets/js/jquery.easyui.min.js"></script>
<script src="<?= base_url() ?>assets/js/toastr.min.js"></script>
<script src="<?= base_url() ?>assets/js/jquery.hotkeys.js"></script>

<!--<script src="<? /*= base_url()*/ ?>assets/js/jquery.jscrollpane.min.js"></script>-->
<script src="<?= base_url() ?>assets/js/app.js"></script>

<script type="text/javascript" src="<?= base_url() ?>assets/js/export/tableExport.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/js/export/jquery.base64.js"></script>
<!--<script type="text/javascript" src="<? /*= base_url()*/ ?>assets/js/jquery.maskMoney.js"></script>-->
<script type="text/javascript" src="<?= base_url() ?>assets/js/bootbox.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/js/jquery.calculadora.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/js/jquery.mask.js"></script>


<script>
    var _base_url = '<?= base_url('/') ?>';
</script>

<script src="<?= base_url() ?>assets/js/functions.js"></script>

<script src="<?= base_url() ?>assets/js/ajax.js"></script>

<?= put_headers('js') ?>



<script>
    // mkilani - check_session
    var _check_timer = setInterval(function () {
        get_dataWithOutLoading('<?=base_url("settings/sys_sessions/public_check_session")?>', {}, function (data) {
            if (data != 1) {
                $('#check_session').modal();
                clearInterval(_check_timer);
            }
        }, 'html');
    }, 30000);


    if ($.fn.datetimepicker) {
        $('input[data-type="date"]').datetimepicker({
            pickTime: false

        });
        $('input[data-type="datetime"]').datetimepicker({
            pickTime: true

        });
    }

    /*var areYouReallySure = false;
     function areYouSure() {
     if(allowPrompt){
     if (!areYouReallySure && true) {
     areYouReallySure = true;
     var confMessage = "***************************************\n\n W A I T !!! \n\nBefore leaving our site, follow CodexWorld for getting regular updates on Programming and Web Development.\n\n\nCLICK THE *CANCEL* BUTTON RIGHT NOW\n\n***************************************";
     return confMessage;
     }
     }else{
     allowPrompt = true;
     }
     }

     var allowPrompt = true;
     window.onbeforeunload = areYouSure;*/
    //$('input[data-type="decimal"]').calculadora({decimals: 2, useCommaAsDecimalMark: false});
    //$('input[data-type="currency"]').maskMoney();
</script>

<!--<div class="gs_loading" >

    <img src="<? /*= base_url('assets/images/loading.gif') */ ?>" alt=""/>
    <span>
        جاري التحميل ...
    </span>

</div>-->

<div class="loader">Loading&#8230;

</div>
<div id="report" class="modal fade modal-fullscreen">
    <div class="modal-dialog" style="width: 900px; height: 600px;">
        <div class="modal-content" style=" height:100%;">

            <div class="modal-body" style="height:90%;">
            </div>
            <div class="modal-footer">
                <button type="button" onclick="javascript:reloadIframeOfReport()" class="btn btn-danger"
                ">تحديث</button>
                <button class="btn" data-dismiss="modal" aria-hidden="true">إغلاق</button>
            </div>
        </div>
    </div>
</div>

<div id="top_modal" class="modal fade modal-fullscreen">
    <div class="modal-dialog" style="width: 900px; height: 600px;">
        <div class="modal-content" style=" height:100%;">

            <div class="modal-body" style="height:90%;">
            </div>
            <div class="modal-footer">
                <button type="button" onclick="javascript:reloadIframeOfReport()" class="btn btn-danger"
                ">تحديث</button>
                <button class="btn" data-dismiss="modal" aria-hidden="true">إغلاق</button>
            </div>
        </div>
    </div>
</div>

<!-- mkilani -->
<div class="modal fade" id="check_session">
    <div class="modal-dialog" style="width: 500px">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                        class="sr-only">Close</span></button>
                <h4 style="color: #ff0000" class="modal-title">تنبيه </h4>
            </div>
            <div class="modal-body">
            <span>
                تم انتهاء وقت الجلسة، يجب اعادة
            </span>
                <a target="_blank" href="<?= base_url('login') ?>">تسجيل الدخول</a>
            </div>
            <div class="modal-footer">

                <button type="button" class="btn btn-default" data-dismiss="modal">إغلاق</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="hints-model">
    <div class="modal-dialog" style="width: 500px">
        <div class="modal-content">

            <div class="modal-body"><span id="hints-content"></span></div>
        </div>
    </div>
</div>


</body>
</html>