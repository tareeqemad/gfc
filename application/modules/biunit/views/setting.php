<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>لوحة التحكم</title>
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />

    <link href="<?=base_url('assets/css/font-awesome.min.css')?>" rel="stylesheet">
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="<?= base_url('assets/biunit/css/styles.css')?>" rel="stylesheet" />

    <link href="<?=base_url('assets/da3em/css/style.css')?>" rel="stylesheet">
    <link href="<?=base_url('assets/da3em/vendor/multiselect/css/select2.min.css')?>" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.12.1/datatables.min.css"/>
    <style>
        .navbar .user{
            font-size: 16px;
            color: #000000;
        }
        .navbar .user img{
            width: 22px;
            height: 22px;
            margin-left: 5px;
        }
    </style>
</head>
<body style="direction: rtl; font-family: Tajawal;">
<div class="d-flex" id="wrapper">
    <!-- Sidebar-->
    <div class="border-end bg-dark" id="sidebar-wrapper">
        <div class="sidebar-heading border-bottom bg-dark"><img style="width: 35px;height: 35px" src="<?=base_url('assets/images/bi-imgs/logo4.png');?>" alt="">
            لوحة التحكم</div>
        <div class="list-group list-group-flush">
            <a class="list-group-item list-group-item-action list-group-item-light p-3" href="<?= base_url('Biunit/index')?>"><i class="icon icon-home"></i> الرئيسية</a>
            <a class="list-group-item list-group-item-action list-group-item-light p-3" href="<?= base_url('Biunit/show_indicators')?>"><i class="icon icon-dashboard"></i> المؤشرات الرئيسية</a>
            <a class="list-group-item list-group-item-action list-group-item-light p-3" href="<?= base_url('Biunit/show_news')?> "><i class="icon icon-list"></i>أخر الأعمال</a>
            <a class="list-group-item list-group-item-action list-group-item-light p-3" href="<?= base_url('Biunit/da3em_setting')?>"><i class="icon icon-link"></i>منصة داعم لذكاء الأعمال</a>
            <a class="list-group-item list-group-item-action list-group-item-light p-3" href="<?= base_url('Biunit/padmin')?>"><i class="icon icon-user"></i>صلاحيات الآدمن</a>
        </div>
    </div>


    <!-- Page content wrapper-->
    <div id="page-content-wrapper">
        <!-- Top navigation-->
        <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
            <div class="container-fluid">
                <div class=" navbar-collapse">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item dropdown">
                            <a class="nav-link user">
                                <img src="<?=base_url('assets/images/bi-imgs/user.png');?>" alt="">   <span style="margin-left: 5px"><?= get_curr_user()->fullname ?></span> </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <!-- Page content-->
        <div class="container-fluid" >
            <?php
                if (isset($content)) $this->load->view($content);
            ?>
        </div>


</div>
<!-- Bootstrap core JS-->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?=base_url('assets/da3em/vendor/multiselect/js/jquery.min.js')?>"></script>
    <script src="<?=base_url('assets/da3em/vendor/multiselect/js/popper.js')?>"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/js/select2.min.js"></script>
    <script src="<?=base_url('assets/da3em/vendor/multiselect/js/main.js')?>"></script>
    <script src="<?=base_url('assets/da3em/vendor/bootstrap/js/bootstrap.js') ?>"></script>

    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.12.1/datatables.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#example').DataTable({
                "bPaginate": false,
                "searching": false,
                "filter": false,
                "info": false
            });
        });
    </script>
<!-- Core theme JS-->
<script src="<?= base_url('assets/biunit/js/scripts.js')?>"></script>
</body>
</html>
