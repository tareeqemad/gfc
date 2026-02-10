<html>
<head>
    <title>الاعدادات</title>
    <link rel="stylesheet" href="<?=base_url('assets/css/jquery.paginate.css')?>" />
    <link href="<?=base_url('assets/css/font-awesome.min.css')?>" rel="stylesheet">
    <style>
        .nav-link{
            margin-left: 15px;
            font-size: 18px;
        }
        .nav-link .active{

        }
        .p-header{
            height: 20px;

        }
        ul, li {
            display: inline;
            padding: 0;
        }

        .breadcrumb-item{
            font-size: 16px;
            line-height: 2;
        }

        /********** works **********/

        section {
            padding: 20px 0;
            overflow: hidden;
        }
        .section-header {
            text-align: center;
            /* padding-bottom: 40px;*/
        }
        .section-header h2 {
            font-size: 13px;
            letter-spacing: 1px;
            font-weight: 700;
            margin: 0;
            color: #4154f1;
            text-transform: uppercase;
        }
        .section-header p {
             margin: 10px 0 10px 0;
            padding: 0;
            font-size: 20px;
            line-height: 38px;
            font-weight: 600;
            color: #012970;
        }
        section .container{
            /* width: 95%;*/
            padding-right: var(--bs-gutter-x,.75rem);
            padding-left: var(--bs-gutter-x,.75rem);
            margin-right: auto;
            margin-left: auto;
        }
        h1, h2, h3, h4, h5, h6 {
            line-height: 1.5;
        }
        p {
            font-size: 12px;
            margin-top: 0;
            margin-bottom: 1rem;
        }

        .sidebar-header p {
            /* margin: 10px 0 0 0;*/
            padding: 0;
            font-size: 20px;
            line-height: 38px;
            font-weight: 600;
            color: #012970;
        }


        .g-4, .gy-4 {
            --bs-gutter-y: 1.5rem;
        }
        @media (min-width: 992px)
            .col-lg-3 {
                flex: 0 0 auto;
                width: 25%;
            }
            @media (min-width: 768px)
                .col-md-6 {
                    flex: 0 0 auto;
                    width: 50%;
                }



    </style>
</head>
<body>




<main id="main" style="font-family: Tajawal;">

    <nav  aria-label="breadcrumb">
        <ol class="breadcrumb" style="padding-right: 12px">
            <li class="breadcrumb-item"><a href="<?= base_url('Biunit/index')?>">الرئيسية</a></li> <i style="margin: 0px 5px;padding-top:5px; " class="icon icon-chevron-left"></i>
            <li class="breadcrumb-item active" aria-current="page">الحلول الفنية ودعم الشبكة الذكية</li>
        </ol>
    </nav>

    <div class="container-fluid" data-aos="fade-up">

        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">متابعة أعمال منظومة العدادات</a>
                <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">متابعة أعمال منظومة السكادا</a>
                <a class="nav-item nav-link" id="nav-contact-tab" data-toggle="tab" href="#nav-contact" role="tab" aria-controls="nav-contact" aria-selected="false">منظومة أعمال منظومة GIS</a>
            </div>
        </nav>
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade active in" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                <iframe style="min-height: 800px;width: 100%;border: solid 1px rgb(215,217,222);"
                        src="https://da3em.gedco.ps/analytics/powerbi/%D9%84%D9%88%D8%AD%D8%A7%D8%AA%20%D8%A7%D9%84%D8%AA%D9%82%D8%A7%D8%B1%D9%8A%D8%B1/%D9%85%D8%AA%D8%A7%D8%A8%D8%B9%D8%A9%20%D8%AA%D8%B1%D9%83%D9%8A%D8%A8%D8%A7%D8%AA%20%D9%85%D9%86%D8%B8%D9%88%D9%85%D8%A9%20%D8%A7%D9%84%D8%B9%D8%AF%D8%A7%D8%AF%D8%AA?rs:embed=true"></iframe>
            </div>
            <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                <iframe style="min-height: 800px;width: 100%;border: solid 1px rgb(215,217,222);"
                        src="https://da3em.gedco.ps/analytics/powerbi/%D9%84%D9%88%D8%AD%D8%A7%D8%AA%20%D8%A7%D9%84%D8%AA%D9%82%D8%A7%D8%B1%D9%8A%D8%B1/%D9%85%D8%AA%D8%A7%D8%A8%D8%B9%D8%A9%20%D8%AA%D8%B1%D9%83%D9%8A%D8%A8%D8%A7%D8%AA%20%D9%85%D9%86%D8%B8%D9%88%D9%85%D8%A9%20%D8%A7%D9%84%D8%B3%D9%83%D8%A7%D8%AF%D8%A7?rs:embed=true"></iframe>
            </div>
            <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">
                <iframe style="min-height: 800px;width: 100%;border: solid 1px rgb(215,217,222);"
                        src="https://da3em.gedco.ps/analytics/powerbi/%D9%84%D9%88%D8%AD%D8%A7%D8%AA%20%D8%A7%D9%84%D8%AA%D9%82%D8%A7%D8%B1%D9%8A%D8%B1/%D9%85%D8%AA%D8%A7%D8%A8%D8%B9%D8%A9%20%D8%B7%D8%A8%D9%82%D8%A7%D8%AA%20%D9%88%D8%B3%D8%AC%D9%84%D8%A7%D8%AA%20%D9%85%D9%86%D8%B8%D9%88%D9%85%D8%A9%20%D8%A7%D9%84%D8%AC%D9%8A%20%D8%A3%D9%8A%20%D8%A7%D8%B3?rs:embed=true"></iframe>
            </div>
        </div>



    </div>

</main>

</body>
</html>