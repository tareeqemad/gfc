<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>   <?= isset($title) ? $title : ''; ?> </title>
    <meta content="" name="description">

    <meta content="" name="keywords">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <!-- Favicons -->
    <link href="<?=base_url('assets/img/favicon.png') ?>" rel="icon">
    <link href="<?=base_url('assets/img/apple-touch-icon.png') ?>" rel="apple-touch-icon">

    <link href="<?=base_url('assets/css/font-awesome.min.css')?>" rel="stylesheet">
    <link href="<?=base_url('assets/da3em/vendor/bootstrap/css/bootstrap.css')?>" rel="stylesheet">
    <!-- Template Main CSS File -->
    <link href="<?=base_url('assets/da3em/css/style.css')?>" rel="stylesheet">
    <link href="<?=base_url('assets/da3em/vendor/multiselect/css/select2.min.css')?>" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.12.1/datatables.min.css"/>

    <style>
        .nav-link{
            color: #0f4391;
        }
        .nav-link.active{
            color: #fffff7 !important;
          /*  background: radial-gradient(circle, rgba(21, 91, 154, 0.9) 0%, rgba(7, 81, 150, 0.99) 100%)!important;*/
            background: rgb(255,152,0);
            background: radial-gradient(circle, rgba(255,152,0,0.958420868347339) 0%, rgba(245,124,0,0.9304096638655462) 100%);
        }
        .nav-pills .nav-link.active, .nav-pills .show > .nav-link {
            background-color: #fffff7;
        }
        a:hover .ic-add-bg{
            color: #1e981c !important;
            background-color: inherit;
        }
        a:hover .ic-edit-bg{
            color: #0734e8 !important;
            background-color: inherit;
        }
        a:hover .ic-delete-bg{
            color: #ab1818!important;
            background-color: inherit;
        }
        a {
            color: #182373;
            text-decoration: none;
        }

        a:hover {
            color: #162cdc;
            text-decoration: none;
        }
        .header{
            height: 88px;
            box-shadow: 0 2px 20px rgb(1 41 112 / 9%);
            /*background: rgb(92,175,228);  //Blue
            background: radial-gradient(circle, rgba(92,175,228,1) 0%, rgba(40,129,227,1) 100%);*/
            background: rgb(11,88,158);
            background: radial-gradient(circle, rgba(11,88,158,0.958420868347339) 0%, rgba(11,54,99,0.9948354341736695) 100%);
            /*  background: rgb(236,196,85);    //orange
              background: radial-gradient(circle, rgba(236,196,85,1) 0%, rgba(246,128,0,0.9948354341736695) 100%);*/
            border-bottom: 1px solid #e1ecff;
            padding: 10px 0;
        }
        .header .logo span{
            color: #f9fafb;
        }
        .header .user{
            font-size: 12px;
            color: #f5f5f0;
        }
        .header .user:hover {
            color: rgb(215, 217, 222);
        }
        .header .user img{
            width: 25px;
            height: 25px;
            margin-left: 8px;
            border-radius: 5px;
        }
        .dropdown-menu{
            width: 200px;
        }
        .dropdown-menu .dropdown-item .icon{
            font-size: 18px;
        }
        .navbar .dropdown ul li {

        }
        .navbar .dropdown ul a {
            padding: 10px 20px 10px 138px;
        }
        .navbar ul {
            align-items: end;!important
        }
        .container-fluid{
            min-height: 823px;
         /*   background-color: #0a8800;*/
        }
        .card-body .feature-last .feature-icon a:hover{
         color: rgb(33, 75, 253);
        }
        .footer {
            position: relative;
            font-size: 18px;
            color: #f9fafb;
            bottom: 0;
            width: 100%;
            height: 60px;   /* Height of the footer */
            padding: 0px!important;
            box-shadow: 0 2px 20px rgb(1 41 112 / 9%);

            background: rgb(23,96,163);    /*dark blue*/
            background: radial-gradient(circle, rgba(23,96,163,0.9023984593837535) 0%, rgba(8,61,116,0.9948354341736695) 100%);
        }
        .figure-img{
            width: 100%;
            height: 65%
        }
        .figure-caption{
            font-size: 16px;
            text-align: center;
            color: #0c0c0c
        }
        @media (max-width: 1000px) {
            .header {
            height:88px;
           }
            .header .logo{
                line-height: 30px;
            }
            .header .logo span {
                font-size: 16px;
            }
            .header .logo img {
                width: 50px;
                height: 50px;
            }
            .header .user{
                font-size: 17px;
                color: #0c0c0c;
            }
            .header .user img{
                width: 22px;
                height: 22px;
                margin-left: 5px;
            }
            .navbar ul {
                display: flex;
            }
            .header .user span{
                display: none;
            }
            .navbar > .container, .navbar > .container-fluid, .navbar > .container-sm, .navbar > .container-md,
            .navbar > .container-lg, .navbar > .container-xl, .navbar > .container-xxl {
                flex-wrap: unset;
            }

        .figure-caption{
            font-size: 12px;
        }
        .figure-img{
            width: 80%;
            height: 45%
        }
            .analysis_ord span{
                display: none;
            }
        }

        @media (max-width: 800px) {

            .figure-caption{
                font-size: 10px;
            }
            .figure-img{
                width: 70%;
                height: 25%
            }
        }
        .select2-container{
            width: 100%!important;
        }
        select2-container--default, .select2-results>.select2-results__options{
           background-color: rgba(177, 173, 173, 0.2) !important;
        }
        .breadcrumb-item::before{
            padding-left: 10px;
            transform: scaleX(-1);
        }
        .tab-content{
           /* border-right: 1px solid rgba(204,207,211,0.54);*/
           /* border-left: 1px solid rgba(204,207,211,0.54);*/
            padding: 0 10px 0 10px;
         /*   border-bottom: 1px solid rgba(204,207,211,0.54);*/
            min-height: 600px;
            border-radius: 5px;
            border: 1px solid #1b69bbfd;
        }

        .btn-check:checked + .btn-outline-light{
            -webkit-box-shadow: 0px 0px 13px 2px #A1A1A1;
            box-shadow: 0px 0px 13px 2px #A1A1A1;
        }
        .btn-group > .btn {
            border-radius: 0;
            padding: 0px 2px;
            margin-left: 5px!important;
        }


    </style>
</head>

<body>

<div  class="navbar navbar-expand-lg header ">
    <div class="container container-xl d-flex align-items-center justify-content-between"  style="">

        <a href="#" class="logo d-flex align-items-center">
            <img src="<?=base_url('assets/images/bi-imgs/logo-w-3.png');?>" alt="">
            <span>منصة داعم لذكاء الأعمال - هيئة المديرين</span>
        </a>

        <div class=" navbar-collapse">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle user" href="#" id="dropdown07XL" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="<?=base_url('assets/images/bi-imgs/user.png');?>" alt="">   <span style="margin-left: 5px"><?= get_curr_user()->fullname ?></span> </a>
                    <ul class="dropdown-menu" aria-labelledby="dropdown07XL">
                        <li><a class="dropdown-item gap-2 align-items-center" href="<?=base_url('Biunit/index')?>"> <i class="icon icon-home"></i> <span>الرئيسية </span></a> </li>
                        <!--  <li><a class="dropdown-item gap-2 align-items-center" href="#"> <i class="icon icon-sign-out"></i> <span>تسجيل الخروج </span></a> </li>   -->
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>

<div class="container-fluid px-4 py-5" id="featured-3" style="">

    <?php
    if (isset($content)) $this->load->view($content);
    ?>
</div>

<div class="footer" >
    <!-- Copyright -->
    <div class="text-center p-3">
        وحـدة ذكـاء الأعمـال ودعـم القـرار
    </div>
    <!-- Copyright -->
</div>

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
</body>

</html>