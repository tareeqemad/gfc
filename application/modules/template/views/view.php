<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 02/11/14
 * Time: 11:57 ص
 */

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= isset($title)?$title:''; ?></title>
    <link href="<?= base_url() ?>assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= base_url() ?>assets/css/bootstrap-theme.min.css" rel="stylesheet">
    <link href="<?= base_url() ?>assets/css/bootstrap-rtl.min.css" rel="stylesheet">
    <link href="<?= base_url() ?>assets/css/font-awesome.min.css" rel="stylesheet">
    <link href="<?= base_url() ?>assets/css/button.css" rel="stylesheet">
    <link href="<?= base_url() ?>assets/css/stylesheets.css" rel="stylesheet">

    <?= put_headers('css') ?>
</head>
<body>


<div>

    <!-- main content begin -->
    <div class="main-content">
        <?php
        if(isset($content))$this->load->view($content);
        ?>
    </div>

</div>

<div class="gs_loading" >

    <img src="<?= base_url('assets/images/loading.gif') ?>" alt=""/>
    <span>
        جاري التحميل ...
    </span>

</div>

<script src="<?= base_url()?>assets/js/jquery-1.11.1.min.js"></script>
<script src="<?= base_url()?>assets/js/jquery-ui.min.js"></script>

<script src="<?= base_url()?>assets/js/bootstrap.min.js"></script>

<script src="<?= base_url()?>assets/js/jqueryval.min.js"></script>
<script src="<?= base_url()?>assets/js/jquery.easyui.min.js"></script>
<script src="<?= base_url()?>assets/js/toastr.min.js"></script>
<script src="<?= base_url()?>assets/js/functions.js"></script>
<script src="<?= base_url()?>assets/js/ajax.js"></script>

<script src="<?= base_url()?>assets/js/app.js"></script>
<script type="text/javascript" src="<?= base_url()?>assets/js/export/tableExport.js"></script>
<script type="text/javascript" src="<?= base_url()?>assets/js/export/jquery.base64.js"></script>

<?= put_headers('js') ?>
<script type="text/javascript">
    if ($.fn.datetimepicker) {
        $('input[data-type="date"]').datetimepicker({
            pickTime: false
        });
    }
</script>
</body>
</html>
