<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
if($this->session->userdata('db_instance') == 'T'){
    $sys_year= '2022';
}else{
    $sys_year='?';
    echo "<div style='margin: 50px'>";
    echo 'Error at Conn DB SysYear <br>';
    echo 'يجب تسجيل خروجك من أرشيف النظام الموحد قبل الدخول للنظام الموحد للسنة الحالية <br>';
    echo "<a href='https://gs.gedco.ps/archive/login/logout' > لتسجيل الخروج اضغط هنا </a>";
    echo "</div>";
    die;
}
$base_url = base_url('/welcome');
?>
<!doctype html>
<html lang="en" dir="rtl">
<head>
        <!-- META DATA -->
        <meta charset="UTF-8">
        <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <!-- FAVICON -->
        <link rel="shortcut icon" type="image/x-icon" href="<?= base_url() ?>assets-n/images/brand/favicon.ico"/>
        <!-- TITLE -->
        <title><?= isset($title) ? $title : 'النظام الفني'; ?></title>
        <!-- BOOTSTRAP CSS -->
        <link id="style" href="<?= base_url() ?>assets-n/plugins/bootstrap/css/bootstrap.rtl.min.css" rel="stylesheet" />
        <!-- STYLE CSS -->
        <link href="<?= base_url() ?>assets-n/css/style.css" rel="stylesheet" />
        <link href="<?= base_url() ?>assets-n/css/skin-modes.css" rel="stylesheet" />
        <!--- FONT-ICONS CSS -->
        <link href="<?= base_url() ?>assets-n/css/icons.css" rel="stylesheet" />
        <!--toastr CSS-->
        <link href="<?= base_url() ?>assets-n/js/toastr/build/toastr.min.css" rel="stylesheet" type="text/css"/>
        <!-- timepicker  && bootstrap-datepicker css -->
        <link rel="stylesheet" href="<?= base_url() ?>assets-n/css/datepicker3.css" type="text/css"/>
        <!-- CUSTOM CSS-->
        <link rel="stylesheet" href="<?= base_url() ?>assets-n/css/custom.css" type="text/css"/>
        <link rel="stylesheet" type="text/css" href="<?= base_url() ?>assets-n/plugins/toastify-js/src/toastify.css">

        <?= put_headers('css') ?>
        <?php if (isset($css_file)) : ?>
            <link href="<?= base_url($css_file); ?>" rel="stylesheet">
        <?php endif; ?>
</head>

<body class="rtl app horizontal-hover dark-menu color-header center-logo">

<!-- GLOBAL-LOADER -->
<div id="global-loader">
    <img src="<?= base_url() ?>assets-n/images/loader.svg" class="loader-img" alt="Loader">
</div>
<!-- /GLOBAL-LOADER -->

<!-- PAGE -->
<div class="page">
    <div class="page-main">

        <!-- app-Header -->
        <div class="app-header header sticky">
            <div class="container-fluid main-container">
                <div class="d-flex">
                    <a aria-label="Hide Sidebar" class="app-sidebar__toggle" data-bs-toggle="sidebar" href="#"></a>
                    <!-- sidebar-toggle-->
                    <a class="logo-horizontal" href="javascript:void(0);">
                        <img src="<?= base_url() ?>assets-n/images/brand/gedco-logo.png" class="header-brand-img desktop-logo" alt="logo">
                        <img src="<?= base_url() ?>assets-n/images/brand/gedco-logo.png" class="header-brand-img light-logo1"
                             alt="logo">
                    </a>
                    <!-- LOGO -->
                    <div class="main-header-center ms-3 d-none d-xl-block">
                        <div class="text-white py-1 text-bold h4">
                            <i class="icon icon-layers"></i>
                            النظام الموحد
                        </div>
                    </div>
                    <div class="d-flex order-lg-2 ms-auto header-right-icons">

                        <button class="navbar-toggler navresponsive-toggler d-md-none ms-auto" type="button"
                                data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent-4"
                                aria-controls="navbarSupportedContent-4" aria-expanded="false"
                                aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon fe fe-more-vertical"></span>
                        </button>
                        <div class="navbar navbar-collapse responsive-navbar p-0">
                            <div class="collapse navbar-collapse" id="navbarSupportedContent-4">
                                <div class="d-flex order-lg-2">
                                    <div class="dropdown  d-flex">
                                        <a class="nav-link icon theme-layout nav-link-bg layout-setting">
                                                <span class="dark-layout">
													<svg xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" viewBox="0 0 24 24"><path d="M22.0482178,13.2746582c-0.1265259-0.2453003-0.4279175-0.3416138-0.6732178-0.2150879C20.1774902,13.6793823,18.8483887,14.0019531,17.5,14c-0.8480835-0.0005493-1.6913452-0.1279297-2.50177-0.3779297c-4.4887085-1.3847046-7.0050049-6.1460571-5.6203003-10.6347656c0.0320435-0.1033325,0.0296021-0.2142944-0.0068359-0.3161621C9.2781372,2.411377,8.9921875,2.2761841,8.7324219,2.3691406C4.6903076,3.800293,1.9915771,7.626709,2,11.9146729C2.0109863,17.4956055,6.5440674,22.0109863,12.125,22c4.9342651,0.0131226,9.1534424-3.5461426,9.9716797-8.4121094C22.1149292,13.4810181,22.0979614,13.3710327,22.0482178,13.2746582z M16.0877075,20.0958252c-4.5321045,2.1853027-9.9776611,0.2828979-12.1630249-4.2492065S3.6417236,5.8689575,8.1738281,3.6835938C8.0586548,4.2776489,8.0004272,4.8814087,8,5.4865723C7.9962769,10.7369385,12.2495728,14.9962769,17.5,15c1.1619263,0.0023193,2.3140869-0.2119751,3.3974609-0.6318359C20.1879272,16.8778076,18.4368896,18.9630127,16.0877075,20.0958252z"/></svg>
												</span>
                                            <span class="light-layout">
													<svg xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" viewBox="0 0 24 24"><path d="M6.3427734,16.9501953l-1.4140625,1.4140625c-0.09375,0.09375-0.1463623,0.2208862-0.1464233,0.3534546c0,0.276123,0.2238159,0.5,0.499939,0.500061c0.1326294,0.0001831,0.2598877-0.0525513,0.3535156-0.1464844l1.4140015-1.4140625c0.0024414-0.0023804,0.0047607-0.0047607,0.0071411-0.0071411c0.1932373-0.1971436,0.1900635-0.5137329-0.0071411-0.7069702C6.8526001,16.7498169,6.5360718,16.7529907,6.3427734,16.9501953z M6.3427734,7.0498047c0.0936279,0.0939331,0.2208862,0.1466675,0.3535156,0.1464844c0.1325684,0,0.2597046-0.0526733,0.3534546-0.1464233c0.1952515-0.1952515,0.1953125-0.5118408,0.000061-0.7070923L5.6356812,4.9287109c-0.1943359-0.1904907-0.5054321-0.1904907-0.6998291,0C4.7386475,5.1220093,4.7354736,5.4385376,4.9287109,5.6357422L6.3427734,7.0498047z M12,5h0.0006104C12.2765503,4.9998169,12.5001831,4.776001,12.5,4.5v-2C12.5,2.223877,12.276123,2,12,2s-0.5,0.223877-0.5,0.5v2.0006104C11.5001831,4.7765503,11.723999,5.0001831,12,5z M17.3037109,7.1962891c0.1326294,0.0001831,0.2598877-0.0525513,0.3535156-0.1464844l1.4140625-1.4141235c0.0023804-0.0023193,0.0047607-0.0046997,0.0070801-0.0070801c0.1932983-0.1972046,0.1900635-0.5137329-0.0070801-0.7070312c-0.1972046-0.1932373-0.5137329-0.1900635-0.7070312,0.0071411l-1.4140625,1.4140625c-0.09375,0.09375-0.1463623,0.2208862-0.1464233,0.3534546C16.803772,6.9723511,17.0275879,7.196228,17.3037109,7.1962891z M5,12c0-0.276123-0.223877-0.5-0.5-0.5h-2C2.223877,11.5,2,11.723877,2,12s0.223877,0.5,0.5,0.5h2C4.776123,12.5,5,12.276123,5,12z M17.6572266,16.9502563c-0.0023804-0.0023804-0.0046997-0.0047607-0.0070801-0.0070801c-0.1972046-0.1932983-0.5137329-0.1901245-0.7070312,0.0070801c-0.1932373,0.1971436-0.1901245,0.5136719,0.0070801,0.7069702l1.4140625,1.4140625c0.0936279,0.0939331,0.2208252,0.1466675,0.3534546,0.1464844c0.1325684,0,0.2597046-0.0526733,0.3534546-0.1463623c0.1953125-0.1952515,0.1953125-0.5118408,0.0001221-0.7070923L17.6572266,16.9502563z M12,19c-0.276123,0-0.5,0.223877-0.5,0.5v2.0005493C11.5001831,21.7765503,11.723999,22.0001831,12,22h0.0006104c0.2759399-0.0001831,0.4995728-0.223999,0.4993896-0.5v-2C12.5,19.223877,12.276123,19,12,19z M21.5,11.5h-2c-0.276123,0-0.5,0.223877-0.5,0.5s0.223877,0.5,0.5,0.5h2c0.276123,0,0.5-0.223877,0.5-0.5S21.776123,11.5,21.5,11.5z M12,6.5c-3.0375366,0-5.5,2.4624634-5.5,5.5s2.4624634,5.5,5.5,5.5c3.0360107-0.0037842,5.4962158-2.4639893,5.5-5.5C17.5,8.9624634,15.0375366,6.5,12,6.5z M12,16.5c-2.4852905,0-4.5-2.0147095-4.5-4.5S9.5147095,7.5,12,7.5c2.4841309,0.0026855,4.4973145,2.0158691,4.5,4.5C16.5,14.4852905,14.4852905,16.5,12,16.5z"/></svg>
												</span>
                                        </a>
                                    </div>
                                    <!-- Theme-Layout -->
                                    <div class="dropdown d-md-flex">
                                        <a class="nav-link icon full-screen-link nav-link-bg">
                                            <svg xmlns="http://www.w3.org/2000/svg"  enable-background="new 0 0 24 24" viewBox="0 0 24 24"><path d="M8.5,21H3v-5.5C3,15.223877,2.776123,15,2.5,15S2,15.223877,2,15.5v6.0005493C2.0001831,21.7765503,2.223999,22.0001831,2.5,22h6C8.776123,22,9,21.776123,9,21.5S8.776123,21,8.5,21z M8.5,2H2.4993896C2.2234497,2.0001831,1.9998169,2.223999,2,2.5v6.0005493C2.0001831,8.7765503,2.223999,9.0001831,2.5,9h0.0006104C2.7765503,8.9998169,3.0001831,8.776001,3,8.5V3h5.5C8.776123,3,9,2.776123,9,2.5S8.776123,2,8.5,2z M21.5,15c-0.276123,0-0.5,0.223877-0.5,0.5V21h-5.5c-0.276123,0-0.5,0.223877-0.5,0.5s0.223877,0.5,0.5,0.5h6.0006104C21.7765503,21.9998169,22.0001831,21.776001,22,21.5v-6C22,15.223877,21.776123,15,21.5,15z M21.5,2h-6C15.223877,2,15,2.223877,15,2.5S15.223877,3,15.5,3H21v5.5005493C21.0001831,8.7765503,21.223999,9.0001831,21.5,9h0.0006104C21.7765503,8.9998169,22.0001831,8.776001,22,8.5V2.4993896C21.9998169,2.2234497,21.776001,1.9998169,21.5,2z"/></svg>
                                        </a>
                                    </div>
                                    <!-- FULL-SCREEN -->
                                    <div class="dropdown d-md-flex notifications">
                                        <a class="nav-link icon" data-bs-toggle="dropdown" title="الأنظمة">
                                            <i class="icon icon-list"></i>
                                            <span class="pulse">
                                            </span>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                            <div class="drop-heading border-bottom">
                                                <div class="d-flex">
                                                    <h6 class="mt-1 mb-0 fs-15 text-dark text-primary">الأنظمة</h6>
                                                </div>
                                            </div>
                                            <div class="notifications-menu ps3 overflow-hidden">
                                                <?= get_systems_temp1() ?>
                                                <a class="dropdown-item" href="https://gs.gedco.ps/Commercial/">
                                                    <div class="notification-each d-flex">
                                                        <div class='me-3 notifyimg  bg-primary brround'>
                                                            <i class='ion-folder'></i>
                                                        </div>
                                                        <div>
                                                            <span class="notification-label mb-1 fs-15">النظام التجاري الجديد</span>
                                                        </div>
                                                    </div>
                                                </a>
                                                <a class="dropdown-item" href="https://gs.gedco.ps/Trading/">
                                                    <div class="notification-each d-flex">
                                                        <div class='me-3 notifyimg  bg-primary brround'>
                                                            <i class='ion-folder'></i>
                                                        </div>
                                                        <div>
                                                            <span class="notification-label mb-1 fs-15">النظام التجاري </span>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- NOTIFICATIONS -->
                                    <div class="dropdown d-md-flex message">
                                        <a href="javascript:void(0);" class="nav-link icon text-center" data-bs-toggle="dropdown" aria-expanded="false">
                                            <svg xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" viewBox="0 0 24 24"><path d="M17.4541016,11H6.5458984c-0.276123,0-0.5,0.223877-0.5,0.5s0.223877,0.5,0.5,0.5h10.9082031c0.276123,0,0.5-0.223877,0.5-0.5S17.7302246,11,17.4541016,11z M19.5,2h-15C3.119812,2.0012817,2.0012817,3.119812,2,4.5v11c0.0012817,1.380188,1.119812,2.4987183,2.5,2.5h12.7930298l3.8534546,3.8535156C21.2402344,21.9473267,21.3673706,22,21.5,22c0.276123,0,0.5-0.223877,0.5-0.5v-17C21.9987183,3.119812,20.880188,2.0012817,19.5,2z M21,20.2929688l-3.1464844-3.1464844C17.7597656,17.0526733,17.6326294,17,17.5,17h-13c-0.828064-0.0009155-1.4990845-0.671936-1.5-1.5v-11C3.0009155,3.671936,3.671936,3.0009155,4.5,3h15c0.828064,0.0009155,1.4990845,0.671936,1.5,1.5V20.2929688z M17.4541016,8H6.5458984c-0.276123,0-0.5,0.223877-0.5,0.5s0.223877,0.5,0.5,0.5h10.9082031c0.276123,0,0.5-0.223877,0.5-0.5S17.7302246,8,17.4541016,8z"/></svg>
                                            <span class="pulse-danger"></span>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow" data-bs-popper="none">
                                            <div class="drop-heading border-bottom">
                                                <div class="d-flex">
                                                    <h6 class="mt-1 mb-0 fs-15 text-dark">المراسلات</h6>
                                                    <div class="ms-auto">
                                                        <span class="xm-title badge bg-secondary text-white fw-normal fs-11 badge-pill"> <a href="javascript:void(0);" class="showall-text text-white">تفريغ</a> </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="message-menu ps2 overflow-hidden">
                                                <a class="dropdown-item d-flex" href="chat.html">
                                                    <span class="avatar avatar-md brround me-3 align-self-center cover-image"></span>
                                                    <div class="wd-90p">
                                                        <div class="d-flex">
                                                            <h5 class="mb-1">Hawaii Hilton</h5>
                                                            <small class="text-muted ms-auto text-end"> 11.07 am </small>
                                                        </div>
                                                        <span class="fs-12 text-muted">Wanted to submit project by tomorrow....</span>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Messages-->
                                    <div class="dropdown d-md-flex profile-1">
                                        <a href="#" data-bs-toggle="dropdown" class="nav-link pe-2 leading-none d-flex animate">
												<span>
													<img src="<?= base_url() ?>assets-n/images/users/img.png" alt="profile-user"
                                                         class="avatar  profile-user brround cover-image">
												</span>
                                            <div class="text-center p-1 d-flex d-lg-none-max">
                                                <h6 class="mb-0" id="profile-heading"><?= get_curr_user()->fullname ?><i class="user-angle ms-1 fa fa-angle-down "></i></h6>
                                            </div>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                            <a class="dropdown-item text-primary" href="<?= base_url('/settings/users/profile') ?>">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-inner-icn" enable-background="new 0 0 24 24" viewBox="0 0 24 24"><path d="M14.6650391,13.3672485C16.6381226,12.3842773,17.9974365,10.3535767,18,8c0-3.3137207-2.6862793-6-6-6S6,4.6862793,6,8c0,2.3545532,1.3595581,4.3865967,3.3334961,5.3690186c-3.6583862,1.0119019-6.5859375,4.0562134-7.2387695,8.0479736c-0.0002441,0.0013428-0.0004272,0.0026855-0.0006714,0.0040283c-0.0447388,0.272583,0.1399536,0.5297852,0.4125366,0.5745239c0.272522,0.0446777,0.5297241-0.1400146,0.5744629-0.4125366c0.624939-3.8344727,3.6308594-6.8403931,7.465332-7.465332c4.9257812-0.8027954,9.5697632,2.5395508,10.3725586,7.465332C20.9594727,21.8233643,21.1673584,21.9995117,21.4111328,22c0.0281372,0.0001831,0.0562134-0.0021362,0.0839844-0.0068359h0.0001831c0.2723389-0.0458984,0.4558716-0.303833,0.4099731-0.5761719C21.2677002,17.5184937,18.411377,14.3986206,14.6650391,13.3672485z M12,13c-2.7614136,0-5-2.2385864-5-5s2.2385864-5,5-5c2.7600708,0.0032349,4.9967651,2.2399292,5,5C17,10.7614136,14.7614136,13,12,13z"/></svg>
                                                بياناتي
                                            </a>
                                            <a class="dropdown-item text-danger" href="<?= base_url('/login/logout') ?>">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-inner-icn" enable-background="new 0 0 24 24" viewBox="0 0 24 24"><path d="M10.6523438,16.140625c-0.09375,0.09375-0.1464233,0.2208862-0.1464233,0.3534546c0,0.276123,0.2238159,0.5,0.499939,0.500061c0.1326294,0.0001221,0.2598267-0.0525513,0.3534546-0.1464844l4.4941406-4.4941406c0.000061-0.000061,0.0001221-0.000061,0.0001831-0.0001221c0.1951294-0.1952515,0.1950684-0.5117188-0.0001831-0.7068481L11.359314,7.1524048c-0.1937256-0.1871338-0.5009155-0.1871338-0.6947021,0c-0.1986084,0.1918335-0.2041016,0.5083618-0.0122681,0.7069702L14.2930298,11.5H2.5C2.223877,11.5,2,11.723877,2,12s0.223877,0.5,0.5,0.5h11.7930298L10.6523438,16.140625z M16.4199829,3.0454102C11.4741821,0.5905762,5.4748535,2.6099243,3.0200195,7.5556641C2.8970337,7.8029175,2.9978027,8.1030884,3.2450562,8.2260742C3.4923706,8.3490601,3.7925415,8.248291,3.9155273,8.0010376c0.8737793-1.7612305,2.300354-3.1878052,4.0615845-4.0615845C12.428833,1.730835,17.828064,3.5492554,20.0366821,8.0010376c2.2085571,4.4517212,0.3901367,9.8509521-4.0615845,12.0595703c-4.4517212,2.2085571-9.8510132,0.3901367-12.0595703-4.0615845c-0.1229858-0.2473145-0.4231567-0.3480835-0.6704102-0.2250977c-0.2473145,0.1229858-0.3480835,0.4230957-0.2250977,0.6704102c1.6773682,3.4109497,5.1530762,5.5667114,8.9541016,5.5537109c3.7976685,0.0003662,7.2676392-2.1509399,8.9560547-5.5526733C23.3850098,11.4996338,21.3657227,5.5002441,16.4199829,3.0454102z"/></svg>
                                                تسجيل خروج
                                            </a>
                                        </div>
                                    </div>
                                    <!-- Profile -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /app-Header -->

        <!--APP-SIDEBAR-->
        <div class="sticky">
            <div class="app-sidebar__overlay" data-bs-toggle="sidebar"></div>
            <div class="app-sidebar">
                <div class="side-header">
                    <a class="header-brand1" href="javascript:void(0);">
                        <img src="<?= base_url() ?>assets-n/images/brand/gedco-logo.png" class="header-brand-img desktop-logo" alt="logo">
                        <img src="<?= base_url() ?>assets-n/images/brand/gedco-logo.png" class="header-brand-img toggle-logo" alt="logo">
                        <img src="<?= base_url() ?>assets-n/images/brand/gedco-logo.png" class="header-brand-img light-logo" alt="logo">
                        <img src="<?= base_url() ?>assets-n/images/brand/gedco-logo.png" class="header-brand-img light-logo1" alt="logo">
                    </a><!-- LOGO -->
                </div>
                <div class="main-sidemenu">
                    <div class="slide-left disabled" id="slide-left"><svg xmlns="http://www.w3.org/2000/svg"
                                                                          fill="#7b8191" width="24" height="24" viewBox="0 0 24 24">
                            <path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z" />
                        </svg>
                    </div>
                    <ul class="side-menu">
                        <li class="slide">
                            <a class="side-menu__item has-link" data-bs-toggle="slide" href="<?= base_url('Cpanel') ?>">
                                <i class="side-menu__icon fa fa-home"></i>
                                <span class="side-menu__label">الرئيسية</span>
                            </a>
                        </li>
                        <?= modules::run('settings/Sysmenus/public_get_menu1', 1) ?>
                        <?= modules::run('settings/Sysmenus/public_get_setting', 1) ?>
                    </ul>
                    <div class="slide-right" id="slide-right"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191"
                                                                   width="24" height="24" viewBox="0 0 24 24">
                            <path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>
        <!--/APP-SIDEBAR-->

        <!--app-content open-->
        <div class="app-content main-content mt-0">
            <div class="side-app">
                <!-- CONTAINER -->
                <div class="main-container container-fluid">
                    <?php if (isset($content)) $this->load->view($content); ?>
                </div>
            </div>
        </div>
        <!-- CONTAINER END -->
    </div>

     <!-- /Start Modal -->
    <div id="report" class="modal fade modal-fullscreen" style="z-index: 9999;">
        <div class="modal-dialog modal-xl" style="width: 90%; height: 90%;">
            <div class="modal-content" style=" height:100%;">

                <div class="modal-body" style="height:90%;">
                </div>
                <div class="modal-footer">
                    <div class="text-right row" id="div_modal_footer" style="width: 70%" >
                    </div>
                    <button type="button" onclick="reloadIframeOfReport()" class="btn btn-primary">
                        تحديث</button>
                    <button class="btn ripple btn-secondary" data-bs-dismiss="modal" type="button">اغلاق</button>
                </div>
            </div>
        </div>
    </div>
    <div id="top_modal" class="modal fade modal-fullscreen" style="z-index: 9999;">
        <div class="modal-dialog modal-xl" style="width: 90%; height: 90%;">
            <div class="modal-content" style=" height:100%;">

                <div class="modal-body" style="height:90%;">
                </div>
                <div class="modal-footer">
                    <div class="text-right row" id="div_modal_footer" style="width: 70%" >
                    </div>
                    <button type="button" onclick="reloadIframeOfReport()" class="btn btn-primary">
                        تحديث</button>
                    <button class="btn ripple btn-secondary" data-bs-dismiss="modal" type="button">اغلاق</button>
                </div>
            </div>
        </div>
    </div>

    <!-- mkilani -->
    <div class="modal fade"  id="check_session" tabindex="-1" role="dialog" >
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">تنبيه</h5>
                    <button  class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                  <span class="text-danger">
                        تم انتهاء وقت الجلسة، يجب اعادة
                  </span>
                    <a target="_blank" href="<?= base_url('login') ?>">تسجيل الدخول</a>
                </div>
                <div class="modal-footer">
                    <button  class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
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

    <!--//End modal-->

    <!-- FOOTER -->
    <footer class="footer">
        <div class="container">
            <div class="row align-items-center flex-row-reverse">
                <div class="col-md-12 col-sm-12 text-center">
                    شركة توزيع كهرباء غزة © <?=date('Y')?> <a href="javascript:void(0);" class="text-primary">النظام الموحد</a>.ادارة الحاسوب <span class="fa fa-heart text-danger"></span>
                </div>
            </div>
        </div>
    </footer>
    <!-- FOOTER END -->
</div>

<!-- BACK-TO-TOP -->
<a href="#top" id="back-to-top"><i class="fa fa-long-arrow-up"></i></a>
<div class="gs-loading"><!-- loading js --></div>
<!-- JQUERY JS -->
<script src="<?= base_url() ?>assets-n/js/jquery.min.js"></script>
<!-- BOOTSTRAP JS -->
<script src="<?= base_url() ?>assets-n/plugins/bootstrap/js/popper.min.js"></script>
<script src="<?= base_url() ?>assets-n/plugins/bootstrap/js/bootstrap.min.js"></script>
<!-- SIDE-MENU JS-->
<script src="<?= base_url() ?>assets-n/plugins/sidemenu/sidemenu.js"></script>
<!-- INTERNAL SELECT2 JS -->
<script src="<?= base_url() ?>assets-n/plugins/select2/select2.full.min.js"></script>
<!-- INTERNAL DATA-TABLES JS-->
<script src="<?= base_url() ?>assets-n/plugins/datatable/js/jquery.dataTables.min.js"></script>
<script src="<?= base_url() ?>assets-n/plugins/datatable/js/dataTables.bootstrap5.js"></script>
<script src="<?= base_url() ?>assets-n/plugins/datatable/dataTables.responsive.min.js"></script>
<!-- PERFECT SCROLLBAR JS-->
<script src="<?= base_url() ?>assets-n/plugins/p-scroll/perfect-scrollbar.js"></script>
<script src="<?= base_url() ?>assets-n/plugins/p-scroll/pscroll.js"></script>
<!-- STICKY JS -->
<script src="<?= base_url() ?>assets-n/js/sticky.js"></script>
<!-- Moment js -->
<script src="<?= base_url() ?>assets-n/js/moment.js"></script>
<script src="<?= base_url() ?>assets-n/js/bootstrap-datetimepicker.js" type="text/javascript"></script>
<script src="<?= base_url() ?>assets-n/js/jqueryval.min.js" type="text/javascript"></script>
<script src="<?= base_url() ?>assets-n/js/jquery.hotkeys.js"></script>
<script src="<?= base_url() ?>assets-n/js/export/tableExport.js"></script>
<script src="<?= base_url() ?>assets-n/js/export/jquery.base64.js"></script>
<script src="<?= base_url() ?>assets-n/js/jquery.mask.js"></script>
<!-- toastr plugin -->
<script src="<?= base_url() ?>assets-n/js/toastr/build/toastr.min.js"></script>
<!-- jquery.tree JS -->
<script src="<?= base_url() ?>assets-n/js/jquery.tree.js"></script>
<!-- Start import table xls in html ---->
<script src="<?= base_url(); ?>assets-n/js/xlsx.full.min.js"></script>
<!-- End import table xls in html---->
<script>
    var _base_url = '<?= base_url('/') ?>';
</script>
<script type="text/javascript" src="<?= base_url() ?>assets-n/plugins/toastify-js/src/toastify.js"></script>

<!-- CUSTOM JS -->
<script src="<?= base_url() ?>assets-n/js/custom.js"></script>
<!-- General Function JS -->
<script src="<?= base_url() ?>assets-n/js/function.js"></script>
<?= put_headers('js') ?>
<script>
    $(document).on({
        ajaxStart: function () {
            if (!$('body').hasClass("processing")) {
                $('body').addClass("loading");
            }
        },
        ajaxStop: function () {
            if (!$('body').hasClass("processing")) {
                $('body').removeClass("loading");
            }
        }
    });

    $('a[href="[removed];"]').click(function(e){
        e.preventDefault();
    })
    if ($.fn.datetimepicker) {
        $('input[data-type="date"]').datetimepicker({
            pickTime: false

        });
    }
</script>
<?php if (isset($js_file)) : ?>
    <script src="<?= base_url($js_file); ?>"></script>
<?php endif; ?>
</body>
</html>