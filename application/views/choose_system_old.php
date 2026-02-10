<?php
/**
 * Created by PhpStorm.
 * User: abarakat
 * Date: 07/06/16
 * Time: 10:29 ص
 */

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

    <link href="<?= base_url() ?>assets/css/stylesheets.css" rel="stylesheet">


    <!--    <link href="<? /*= base_url() */ ?>assets/css/jquery.jscrollpane.css" rel="stylesheet">-->
    <?= put_headers('css') ?>

</head>
<body class="init">

<div class="systems" style="min-width: 90%">
    <div class="col-md-2">
              <img src="<?= base_url() ?>assets/images/logo-b.png"/>
    </div>
    <div class="col-md-10">
        <div class="col-sm-3">
            <a href="<?= base_url('/welcome/setSystem/1')?>">النظام المالي</a></div>
        <div class="col-sm-3">
            <a href="<?= base_url('/welcome/setSystem/2')?>">النظام الفني</a></div>
        <div class="col-sm-3">
            <a href="<?= base_url('/welcome/setSystem/3')?>">النظام الإداري</a></div>
        <div class="col-sm-3" onclick="$('#sub_sys_modal_6').modal();">
            <!--<a href="< ?= base_url('/welcome/setSystem/6')?>">منظومة الـــGIS</a></div>-->
            <a href="javascript:;" >نظام الشبكة الذكية</a>
        </div>
        <div class="col-sm-3">
            <a href="<?= base_url('/welcome/setSystem/7')?>">النظام القانوني</a></div>
        <div class="col-sm-3">
            <a href="<?= base_url('/welcome/setSystem/8')?>">نظام التخطيط</a></div>
        <div class="col-sm-3">
            <a href="<?= base_url('/welcome/setSystem/10')?>">نظام التدريب</a></div>
        <div class="col-sm-3">
            <a href="<?= base_url('/welcome/setSystem/15')?>">نظام المهام والمراسلات</a></div>
        <div class="col-sm-3">
            <a href="../../Trading/Main">النظام التجاري</a>
        </div>
		<div class="col-sm-3">
            <a href="../../Technical/Cpanel"> النظام الفني الجديد</a>
        </div>
        <div class="col-sm-3">
            <a href="../../RecordAssets/Cpanel"> نظام سجل الأصول</a>
        </div>
        <div class="col-sm-3">
            <a href="../../Commercial/cpanel">النظام التجاري الجديد </a>
        </div>
        <div class="col-sm-3">
            <a href="<?= base_url('/welcome/setSystem/11')?>">الخدمات الإلكترونية</a>
        </div>
		<div style="display:none" class="col-sm-3">
            <a href="<?= base_url('/welcome/setSystem/16')?>">التقارير والاحصائيات</a>
        </div>
		<div class="col-sm-3">
            <a href="http://da3em/analytics/powerbi">ذكاء الأعمال ودعم القرار</a></div>

    </div>
	
	
	<div class="modal fade" id="sub_sys_modal_6">
        <div class="modal-dialog" style="width: 40%">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span
                                aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                </div>

                <div class="col-sm-6">
                    <a target="_blank" href="https://scadaportal.gedco.ps"> سكادا بورتال </a>
                </div>

                <div class="col-sm-6">
                    <a target="_blank" href="https://gisportal.gedco.ps/portal/apps/webappviewer/index.html?id=d818fecc1aaa4e1985afa212ccc43b60"> شبكة الجهد المتوسط </a>
                </div>

                <div class="col-sm-6">
                    <a target="_blank" href="https://gisportal.gedco.ps/portal/apps/webappviewer/index.html?id=7dc4c723b30d4e1b81d52973cca1447e">شبكة الجهد المنخفض و المشتركين  </a>
                </div>

                <div class="col-sm-6">
                    <a target="_blank" href="https://gisportal.gedco.ps/portal/apps/dashboards/f2f1b562ed0944afaea921c6477823ea"> لوحة مراقبة الأحمال </a>
                </div>

                <div class="modal-footer">
                </div>

            </div>
        </div>
    </div>
	

</div>

<script src="<?= base_url() ?>assets/js/jquery-1.11.1.min.js"></script>
<script src="<?= base_url() ?>assets/js/jquery-ui.min.js"></script>

<script src="<?= base_url() ?>assets/js/bootstrap.min.js"></script>


<script>
    var _base_url = '<?= base_url('/') ?>';
</script>

<script src="<?= base_url() ?>assets/js/functions.js"></script>
<script>

</script>
</body>
</html>