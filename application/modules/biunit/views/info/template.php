<!DOCTYPE html>
<!--
Template Name: Metronic - Bootstrap 4 HTML, React, Angular 9 & VueJS Admin Dashboard Theme
Author: KeenThemes
Website: http://www.keenthemes.com/
Contact: support@keenthemes.com
Follow: www.twitter.com/keenthemes
Dribbble: www.dribbble.com/keenthemes
Like: www.facebook.com/keenthemes
Purchase: https://1.envato.market/EA4JP
Renew Support: https://1.envato.market/EA4JP
License: You must have a valid license purchased only from themeforest(the above link) in order to legally use the theme for your project.
-->
<html direction="rtl" dir="rtl" style="direction: rtl">
<!--begin::Head-->

<head>
    <!-- <base href=""> -->
<meta charset="utf-8" />
<title>  نظام المعلومات والوثائق </title>
<meta name="description" content="Page with empty content" />
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

<!--begin::Fonts-->
<!-- <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Cairo&family=Poppins:300,400,500,600,700" /> -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Cairo&display=swap" rel="stylesheet">
<!--end::Fonts-->

<!--begin::Page Vendors Styles(used by this page)-->
<!--end::Page Vendors Styles-->

    <!--begin::Global Theme Styles(used by all pages)-->
    <link href="<?= base_url("assets/info/plugins/global/plugins.bundle.rtl.css") ?>" rel="stylesheet" type="text/css" />
    <link href="<?= base_url("assets/info/plugins/custom/prismjs/prismjs.bundle.rtl.css") ?>" rel="stylesheet" type="text/css" />
    <link href="<?= base_url('assets/info/plugins/custom/datatables/datatables.bundle.rtl.css') ?>" rel="stylesheet" type="text/css" />
    <link href="<?= base_url("assets/info/css/style.bundle.rtl.css") ?>" rel="stylesheet" type="text/css" />
    <!--end::Global Theme Styles-->

    <!--begin::Layout Themes(used by all pages)-->

    <link href="<?= base_url("assets/info/css/themes/layout/header/base/light.rtl.css") ?>" rel="stylesheet" type="text/css" />
    <link href="<?= base_url("assets/info/css/themes/layout/header/menu/light.rtl.css") ?>" rel="stylesheet" type="text/css" />
    <link href="<?= base_url("assets/info/css/themes/layout/brand/dark.rtl.css") ?>" rel="stylesheet" type="text/css" />
    <link href="<?= base_url("assets/info/css/themes/layout/aside/dark.rtl.css") ?>" rel="stylesheet" type="text/css" />
    <link href="<?= base_url("assets/info/css/style.css?v=10") ?>" rel="stylesheet" type="text/css" />
    <link href="<?=base_url('assets/da3em/vendor/multiselect/css/select2.min.css')?>" rel="stylesheet" />



    <!--end::Layout Themes-->
<style>
	.dash_table_fo_content_body {
    border-top-left-radius: .42rem;
		border-bottom-left-radius: .42rem;
		border-top-right-radius: .42rem;
		border-bottom-right-radius: .42rem;
		border-bottom: 0;
		letter-spacing: 0px;
		font-weight: 600;
		font-size: .9rem;
		width: 100%;
		margin: auto;
	}

	.dash_table_fo_content_body .dash_table_item {
    padding-top: 1rem !important;
		padding-bottom: 1rem !important;
		padding: .75rem;
	}


	.dash_table_fo_content_head {
    border-top-left-radius: .42rem;
		border-bottom-left-radius: .42rem;
		border-top-right-radius: .42rem;
		border-bottom-right-radius: .42rem;
		background-color: #f3f6f9;
		border-bottom: 0;
		letter-spacing: 0px;
		font-weight: 600;
		color: #b5b5c3 !important;
		font-size: .9rem;
		width: 100%;
		margin: auto;
	}

	.dash_table_fo_content_head .dash_table_item {
    padding-top: 2rem !important;
		padding-bottom: 2rem !important;
		padding: .75rem;
	}

	@media screen and (max-width: 786px) {
    .dash_table_fo_content_body .dash_table_item {
        padding-top: 6px !important;
			padding-bottom: 0px !important;
			padding: 0.5rem;
		}

		.dash_table_fo_content_body {
        margin-bottom: 25px !important;
		}
	}
    .select2-dropdown--below{
       position: relative;
    }
    .select2-selection--single{
        height: 40px!important;
        border-color: #e4e6ef!important;
    }
    .select2-container--default[dir="rtl"] .select2-selection--single .select2-selection__arrow {
        top: 9px;
        left: -5px!important;
    }
    .hide_cl{
        display: none;
    }
    .show_cl{
        display: flex;
    }


</style>

<style>
body,
    html {
    font-family: 'Cairo', Helvetica, "sans-serif" !important;
    }

    .tooltip {
    font-family: 'Cairo', Helvetica, "sans-serif" !important;
    }
</style>
</head>
<!--end::Head-->

<!--begin::Body-->

<body id="kt_body" class="header-fixed header-mobile-fixed subheader-enabled subheader-fixed subheader-mobile-fixed aside-enabled aside-fixed aside-minimize-hoverable page-loading">

    <!--begin::Main-->
    <!--begin::Header Mobile-->
    <div id="kt_header_mobile" class="header-mobile align-items-center  header-mobile-fixed ">
    <!--begin::Logo-->
    <a href="index.html">
        <!--<img alt="Logo" src="" />
   -->
        شركة توزيع الكهرباء
</a>
    <!--end::Logo-->

    <!--begin::Toolbar-->
    <div class=" d-flex align-items-center">
        <!--begin::Aside Mobile Toggle-->
        <button class="btn p-0 burger-icon burger-icon-right" id="kt_aside_mobile_toggle">
            <span></span>
        </button>
        <!--end::Aside Mobile Toggle-->

        <!--begin::Header Menu Mobile Toggle-->
        <!-- <button class="btn p-0 burger-icon ml-4" id="kt_header_mobile_toggle">
            <span></span>
        </button> -->
        <!--end::Header Menu Mobile Toggle-->

        <!--begin::Topbar Mobile Toggle-->
        <button class="btn btn-hover-text-primary p-0 ml-2" id="kt_header_mobile_topbar_toggle">
            <span class="svg-icon svg-icon-xl">
                <!--begin::Svg Icon | path:assets/media/svg/icons/General/User.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <polygon points="0 0 24 0 24 24 0 24" />
                        <path d="M12,11 C9.790861,11 8,9.209139 8,7 C8,4.790861 9.790861,3 12,3 C14.209139,3 16,4.790861 16,7 C16,9.209139 14.209139,11 12,11 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" />
                        <path d="M3.00065168,20.1992055 C3.38825852,15.4265159 7.26191235,13 11.9833413,13 C16.7712164,13 20.7048837,15.2931929 20.9979143,20.2 C21.0095879,20.3954741 20.9979143,21 20.2466999,21 C16.541124,21 11.0347247,21 3.72750223,21 C3.47671215,21 2.97953825,20.45918 3.00065168,20.1992055 Z" fill="#000000" fill-rule="nonzero" />
                    </g>
                </svg>
                <!--end::Svg Icon--></span> </button>
        <!--end::Topbar Mobile Toggle-->
    </div>
    <!--end::Toolbar-->
</div>    <!--end::Header Mobile-->
    <div class="d-flex flex-column flex-root">
        <!--begin::Page-->
        <div class="d-flex flex-row flex-column-fluid page">

            <!--begin::Aside-->

<div class="aside aside-left  aside-fixed  d-flex flex-column flex-row-auto" id="kt_aside">
    <!--begin::Brand-->
    <div class="brand flex-column-auto " id="kt_brand">
        <!--begin::Logo-->
        <a href="" class="brand-logo">
           <!-- <img alt="Logo" src="" />
            -->
            نظام المعلومات والوثائق
</a>
        <!--end::Logo-->

        <!--begin::Toggle-->
        <button class="brand-toggle btn btn-sm px-0" id="kt_aside_toggle">
            <span class="svg-icon svg-icon svg-icon-xl">
                <!--begin::Svg Icon | path:assets/media/svg/icons/Navigation/Angle-double-left.svg-->
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <polygon points="0 0 24 0 24 24 0 24" />
                        <path d="M5.29288961,6.70710318 C4.90236532,6.31657888 4.90236532,5.68341391 5.29288961,5.29288961 C5.68341391,4.90236532 6.31657888,4.90236532 6.70710318,5.29288961 L12.7071032,11.2928896 C13.0856821,11.6714686 13.0989277,12.281055 12.7371505,12.675721 L7.23715054,18.675721 C6.86395813,19.08284 6.23139076,19.1103429 5.82427177,18.7371505 C5.41715278,18.3639581 5.38964985,17.7313908 5.76284226,17.3242718 L10.6158586,12.0300721 L5.29288961,6.70710318 Z" fill="#000000" fill-rule="nonzero" transform="translate(8.999997, 11.999999) scale(-1, 1) translate(-8.999997, -11.999999) " />
                        <path d="M10.7071009,15.7071068 C10.3165766,16.0976311 9.68341162,16.0976311 9.29288733,15.7071068 C8.90236304,15.3165825 8.90236304,14.6834175 9.29288733,14.2928932 L15.2928873,8.29289322 C15.6714663,7.91431428 16.2810527,7.90106866 16.6757187,8.26284586 L22.6757187,13.7628459 C23.0828377,14.1360383 23.1103407,14.7686056 22.7371482,15.1757246 C22.3639558,15.5828436 21.7313885,15.6103465 21.3242695,15.2371541 L16.0300699,10.3841378 L10.7071009,15.7071068 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" transform="translate(15.999997, 11.999999) scale(-1, 1) rotate(-270.000000) translate(-15.999997, -11.999999) " />
                    </g>
                </svg>
                <!--end::Svg Icon--></span> </button>
        <!--end::Toolbar-->
    </div>
    <!--end::Brand-->
    <?php
    if (isset($sideMenu)) $this->load->view($sideMenu);
    ?>


    <!-- Button trigger modal-->
</div>

<!-- Modal-->

            <!--end::Aside-->

            <!--begin::Wrapper-->
            <div class="d-flex flex-column flex-row-fluid wrapper" id="kt_wrapper">
                <!--begin::Header-->
                <div id="kt_header" class="header  header-fixed ">
                    <!--begin::Container-->
                    <div class=" container-fluid  d-flex align-items-stretch justify-content-between">
                        <!--begin::Header Menu Wrapper-->
                        <div class="header-menu-wrapper header-menu-wrapper-left" id="kt_header_menu_wrapper">
                            <!--begin::Header Menu-->
                            <div id="kt_header_menu" class="header-menu header-menu-mobile  header-menu-layout-default ">
                                <!--begin::Header Nav-->
                                <ul class="menu-nav d-flex align-items-center">
                                    <span class="text-muted">نظام المعلومات والوثائق</span>
                                </ul>
                                <!--end::Header Nav-->
                            </div>
                            <!--end::Header Menu-->
                        </div>
                        <!--end::Header Menu Wrapper-->

                        <!--begin::Topbar-->
                        <div class="topbar">
                            <!--begin::User-->
                            <div class="topbar-item">
                                <div class="btn btn-icon btn-icon-mobile w-auto btn-clean d-flex align-items-center btn-lg px-2" id="kt_quick_user_toggle">
                                    <span class="symbol symbol-lg-35 symbol-25 mr-2">
                                        <span class="symbol-label font-size-h5 font-weight-bold">
                                            <i class="icon-2x text-dark-50 flaticon2-user"></i>
                                        </span>
                                    </span>
                                    <span class="text-muted font-weight-bold font-size-base d-none d-md-inline mr-1">مرحباً ، </span>
                                    <span class="text-dark-50 font-weight-bolder font-size-base d-none d-md-inline mr-3"><?= get_curr_user()->fullname ?></span>

                                </div>
                            </div>
                            <!--end::User-->
                        </div>
                        <!--end::Topbar-->
                    </div>
                    <!--end::Container-->
                </div>
                <!--end::Header-->

                <!--begin::Content-->
                <div class="content  d-flex flex-column flex-column-fluid" id="kt_content">
                    <!--begin::Subheader-->
                    <div class="hideOnMobile">
                        <div class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
                            <div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
                                <!--begin::Info-->
                                <div class="d-flex align-items-center flex-wrap mr-1">
                                    <!--begin::Mobile Toggle-->
                                    <div id="ShowTocItemsMenu" style="display: none;">
                                        <button class="burger-icon burger-icon-right mr-4 d-inline-block d-lg-none" id="kt_subheader_mobile_toggle">
                                            <span></span>
                                        </button>
                                    </div>
                                    <!--end::Mobile Toggle-->


                                    <!--begin::Page Heading-->
                                    <div class="d-flex align-items-baseline flex-wrap mr-5">
                                        <!--begin::Page Title-->
                                        <h5 class="text-dark font-weight-bold my-1 mr-5 titleHasSpans">
                                                                                    </h5>
                                        <!--end::Page Title-->

                                        <!--begin::Breadcrumb-->
                                        <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
                                                                                    </ul>
                                        <!--end::Breadcrumb-->
                                    </div>
                                    <!--end::Page Heading-->
                                </div>
                                <!--end::Info-->

                                <!--begin::Toolbar-->

                                <!--end::Toolbar-->
                            </div>
                        </div>
                    </div>
                    <!--end::Subheader-->

                    <!--begin::Entry-->
                    <div class="d-flex flex-column-fluid">
                        <!--begin::Container-->
                        <div class=" container ">
                            <?php
                            if (isset($content)) $this->load->view($content);
                            ?>
                        </div>
                        <!--end::Container-->
                    </div>
                    <!--end::Entry-->
                </div>
                <!--end::Content-->

                <!--begin::Footer-->
                <div class="footer bg-white py-4 d-flex flex-lg-column " id="kt_footer">
    <!--begin::Container-->
    <div class=" container-fluid  d-flex flex-column  align-items-center justify-content-between">
        <!--begin::Copyright-->
        <div class="text-dark order-2 order-md-1">
            <span class="text-muted font-weight-bold mr-2"><?= date('Y') ?>&copy;</span>
            <a  style="text-align: center" target="_blank" class="text-dark-75 text-hover-primary">    وحدة ذكاء الأعمال ودعم القرار   </a>
        </div>
        <!--end::Copyright-->

        <!--begin::Nav-->
        <div class="nav nav-dark">
        </div>
        <!--end::Nav-->
    </div>
    <!--end::Container-->
</div>
<style>
li.menu-section.sec_new {
    margin: 5px !important;
    }

    li.menu-section.sec_new h4.menu-text {
    font-size: 16px !important;
        color: #fff !important;
    }


</style>                <!--end::Footer-->
            </div>
            <!--end::Wrapper-->
        </div>
        <!--end::Page-->

<div class="modal fade" id="Main_CommitteMembersModel" tabindex="-1" role="dialog" aria-labelledby="Main_CommitteMembersModelLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="Main_CommitteMembersModelLabel">اختر اللجنة</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group mb-4">
                        <!-- <label>الرجاء اختيار اللجنة <span class="text-danger">*</span></label> -->
                        <select class="form-control selectpicker" id="Main_CommitteeSelector" name="Main_CommitteeSelector" title="اختر اللجنة المراد عرض اعضاءها" data-size="7" data-live-search="true">
                        </select>
                    </div>
                </form>
            </div>
            <!-- <div class="modal-footer">
                <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary font-weight-bold">اعرض الاعضاء</button>
            </div> -->
        </div>
    </div>
</div>
<div class="modal fade" id="Main_TOCSelectorModel" tabindex="-1" role="dialog" aria-labelledby="Main_TOCSelectorModelLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="Main_TOCSelectorModelLabel">اختر اللجنة</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group mb-4">
                        <label>أختر اللجنة <span class="text-danger">*</span></label>
                        <select class="form-control selectpicker" id="Main_TOCCommitteeSelector" name="Main_TOCCommitteeSelector" title="اختر اللجنة" data-size="7" data-live-search="true">
                        </select>
                    </div>
                    <div class="form-group mb-4">
                        <label>أدخل رقم جدول الاعمال للعرض:<span class="text-danger">*</span></label>
                        <input type="text" class="form-control datatable-input" id="Main_TocNumber" name="Main_TocNumber" autocomplete="off" />
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="BtnNavigateToTOC" class="btn btn-primary font-weight-bold">عرض</button>
            </div>
        </div>
    </div>
</div>
    </div>
    <!--end::Main-->





    <!-- begin::User Panel-->
    <div id="kt_quick_user" class="offcanvas offcanvas-right p-10">
        <!--begin::Header-->
        <div class="offcanvas-header d-flex align-items-center justify-content-between pb-5">
            <h3 class="font-weight-bold m-0">
                <small class="text-muted font-size-sm ml-2"></small>
            </h3>
            <a href="#" class="btn btn-xs btn-icon btn-light btn-hover-primary" id="kt_quick_user_close">
                <i class="ki ki-close icon-xs text-muted"></i>
            </a>
        </div>
        <!--end::Header-->

        <!--begin::Content-->
        <div class="offcanvas-content pr-5 mr-n5">
            <!--begin::Header-->
            <div class="d-flex align-items-center mt-5">
                <div class="d-flex flex-column">
                    <div class="navi mt-2">
                        <a href="<?=base_url('Biunit/index')?>" class="btn btn-sm btn-light-primary font-weight-bolder py-2 px-5">بوابة ذكاء الأعمال ودعم القرار</a>
                    </div>
                </div>
            </div>
            <!--end::Header-->
            <!--begin::Separator-->
            <div class="separator separator-dashed mt-8 mb-5"></div>
            <!--end::Separator-->

            <!--begin::Nav-->
            <div class="navi navi-spacer-x-0 p-0">
					<!--begin::Item
<a href="https://gedcobi.com/edss/public/profile/edite" class="navi-item">
						<div class="navi-link">
							<div class="symbol symbol-40 bg-light mr-3">
								<div class="symbol-label">
									<span class="svg-icon svg-icon-md svg-icon-success">
										<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
											<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
												<rect x="0" y="0" width="24" height="24"></rect>
												<path d="M13.2070325,4 C13.0721672,4.47683179 13,4.97998812 13,5.5 C13,8.53756612 15.4624339,11 18.5,11 C19.0200119,11 19.5231682,10.9278328 20,10.7929675 L20,17 C20,18.6568542 18.6568542,20 17,20 L7,20 C5.34314575,20 4,18.6568542 4,17 L4,7 C4,5.34314575 5.34314575,4 7,4 L13.2070325,4 Z" fill="#000000"></path>
												<circle fill="#000000" opacity="0.3" cx="18.5" cy="5.5" r="2.5"></circle>
											</g>
										</svg>
									</span>
								</div>
							</div>
							<div class="navi-text">
								<div class="font-weight-bold">ملفي الشخصي</div>
								<div class="text-muted"> تعديل معلومات الملف الشخصي وطرق التواصل
<span class="label label-light-danger label-inline font-weight-bold">تعديل</span></div>
							</div>
						</div>
					</a>
															end::Svg Icon-->

            </div>
            <!--end::Nav-->

            <!--begin::Separator-->
            <!--end::Separator-->

            <!--begin::Notifications-->

            <!--end::Notifications-->
        </div>
        <!--end::Content-->
    </div>
    <!-- end::User Panel-->


    <!--begin::Scrolltop-->
    <div id="kt_scrolltop" class="scrolltop">
        <span class="svg-icon">
            <!--begin::Svg Icon | path:assets/media/svg/icons/Navigation/Up-2.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                    <polygon points="0 0 24 0 24 24 0 24" />
                    <rect fill="#000000" opacity="0.3" x="11" y="10" width="2" height="10" rx="1" />
                    <path d="M6.70710678,12.7071068 C6.31658249,13.0976311 5.68341751,13.0976311 5.29289322,12.7071068 C4.90236893,12.3165825 4.90236893,11.6834175 5.29289322,11.2928932 L11.2928932,5.29289322 C11.6714722,4.91431428 12.2810586,4.90106866 12.6757246,5.26284586 L18.6757246,10.7628459 C19.0828436,11.1360383 19.1103465,11.7686056 18.7371541,12.1757246 C18.3639617,12.5828436 17.7313944,12.6103465 17.3242754,12.2371541 L12.0300757,7.38413782 L6.70710678,12.7071068 Z" fill="#000000" fill-rule="nonzero" />
                </g>
            </svg>
            <!--end::Svg Icon--></span></div>
    <!--end::Scrolltop-->


    <!--begin::Global Config(global config for global JS scripts)-->
    <script>
        var KTAppSettings = {
    "breakpoints": {
        "sm": 576,
                "md": 768,
                "lg": 992,
                "xl": 1200,
                "xxl": 1400
            },
            "colors": {
        "theme": {
            "base": {
                "white": "#ffffff",
                        "primary": "#3699FF",
                        "secondary": "#E5EAEE",
                        "success": "#1BC5BD",
                        "info": "#8950FC",
                        "warning": "#FFA800",
                        "danger": "#F64E60",
                        "light": "#E4E6EF",
                        "dark": "#181C32"
                    },
                    "light": {
                "white": "#ffffff",
                        "primary": "#E1F0FF",
                        "secondary": "#EBEDF3",
                        "success": "#C9F7F5",
                        "info": "#EEE5FF",
                        "warning": "#FFF4DE",
                        "danger": "#FFE2E5",
                        "light": "#F3F6F9",
                        "dark": "#D6D6E0"
                    },
                    "inverse": {
                "white": "#ffffff",
                        "primary": "#ffffff",
                        "secondary": "#3F4254",
                        "success": "#ffffff",
                        "info": "#ffffff",
                        "warning": "#ffffff",
                        "danger": "#ffffff",
                        "light": "#464E5F",
                        "dark": "#ffffff"
                    }
                },
                "gray": {
            "gray-100": "#F3F6F9",
                    "gray-200": "#EBEDF3",
                    "gray-300": "#E4E6EF",
                    "gray-400": "#D1D3E0",
                    "gray-500": "#B5B5C3",
                    "gray-600": "#7E8299",
                    "gray-700": "#5E6278",
                    "gray-800": "#3F4254",
                    "gray-900": "#181C32"
                }
            },
            "font-family": "Poppins"
        };
    </script>
    <!--end::Global Config-->


    <script src="<?=base_url('assets/da3em/vendor/multiselect/js/jquery.min.js')?>"></script>
    <script src="<?=base_url('assets/da3em/vendor/multiselect/js/popper.js')?>"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/js/select2.min.js"></script>
    <script src="<?=base_url('assets/da3em/vendor/multiselect/js/main.js')?>"></script>

    <!--begin::Global Theme Bundle(used by all pages)-->
    <script src="https://gedcobi.com/edss/public/assets/plugins/global/plugins.bundle.min.js"></script>
<script src="https://gedcobi.com/edss/public/assets/plugins/custom/prismjs/prismjs.bundle.js"></script>
<script src="https://gedcobi.com/edss/public/assets/js/scripts.bundle.js"></script>    <!--end::Global Theme Bundle-->

    <!--begin::Page Vendors(used by this page)-->
    <!-- <script src="//maps.google.com/maps/api/js?key=AIzaSyBTGnKT7dt597vo9QgeQ7BFhvSRP4eiMSM"></script> -->
    <!-- <script src="assets/plugins/custom/gmaps/gmaps.js"></script> -->
    <!--end::Page Vendors-->

    <!--begin::Page Scripts(used by this page)-->
    <script src="<?php echo base_url("assets/info/js/pages/widgets.js") ?>"></script>
    <script src="<?php echo base_url("assets/info/plugins/custom/datatables/datatables.bundle.js") ?>"></script>
    <script src="<?php echo base_url("assets/info/js/pages/crud/datatables/advanced/multiple-controls.js") ?>"></script>
    <script src="<?php echo base_url("assets/js/functions.js") ?>"></script>

    <div id="report" class="modal fade modal-fullscreen" style="z-index: 9999;">
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

<style>
    .menu-item-active a.menu-link{
    background-color: #12121c !important;
    }

    .modal-dialog {
        max-width: 1000px;
    }


</style>

    <!--end::Page Scripts-->
</body>
<!--end::Body-->

</html>
