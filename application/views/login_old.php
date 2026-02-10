<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: mkilani
 * Date: 07/09/14
 * Time: 09:23 ص
 */

echo AntiForgeryToken();
$TB_NAME= 'login';
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
    <link href="<?= base_url() ?>assets/css/login.css" rel="stylesheet">

</head>
<body>

<!-- header begin -->
<div class="header">
    <div class="top">
    </div>

</div>
<!-- /end header -->

<div class="container">

    <!-- main content begin -->
    <div class="main-content">
        <div class="row" id="login">
            <div class="toolbar">
                <div class="caption">دخول المستخدمين</div>
            </div>

            <div class="form-body">
                <div id="msg_container"></div>

                <div id="container">

                    <form class="form-horizontal" id="<?=$TB_NAME?>_form" method="post" action="<?=base_url("$TB_NAME/check_user")?>" role="form" novalidate="novalidate">
                        <div class="modal-body">

                            <div class="form-group">
                                <label class="col-sm-4 control-label">اسم المستخدم</label>
                                <div class="col-sm-8">
                                    <input type="text" data-val="true"  data-val-required="حقل مطلوب" name="user_id" id="txt_user_id" class="form-control" maxlength="15" data-val-regex="الاسم المسموح إنجليزي من 6 الي 15 حرف !!" data-val-regex-pattern="[a-zA-Z]{6,15}" dir="ltr" >
                                    <span class="field-validation-valid" data-valmsg-for="user_id" data-valmsg-replace="true"></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-4 control-label">كلمة المرور</label>
                                <div class="col-sm-8">
                                    <input type="password" data-val="true"  data-val-required="حقل مطلوب" name="user_pwd" id="txt_user_pwd" class="form-control" maxlength="32" data-val-regex-pattern="^.*(?=.{8,})(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[@#$%&]).*$" data-val-regex=" كلمة المرور يجب أن تكون 8 خانات و رمز و حرف كبير على الأقل "  dir="ltr" >
                                    <span class="field-validation-valid" data-valmsg-for="user_pwd" data-valmsg-replace="true"></span>
                                </div>
                            </div>

                            <div class="col-sm-10" style="text-align: center">
                                <button type="submit" data-action="submit" class="btn btn-primary">دخول</button>
                            </div>

                        </div>
                    </form>

                </div>
            </div>

        </div>
    </div>
    <!-- / end main content -->
    <!-- footer begin -->
    <div class="footer"></div>
    <!-- / end footer -->
</div>

<script src="<?= base_url()?>assets/js/jquery-1.11.1.min.js"></script>
<script src="<?= base_url()?>assets/js/jquery-ui.min.js"></script>
<script src="<?= base_url()?>assets/js/bootstrap.min.js"></script>
<script src="<?= base_url()?>assets/js/jqueryval.min.js"></script>

<?= put_headers('js') ?>

</body>
</html>



