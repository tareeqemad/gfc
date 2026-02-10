<html>
<head>
    <title>وحدة ذكاء الأعمال</title>
    <link rel="stylesheet" href="<?=base_url('assets/css/jquery.paginate.css')?>" />
    <link href="<?=base_url('assets/css/font-awesome.min.css')?>" rel="stylesheet">
    <style>

        .post-item{
            margin-bottom: 8px;
        }
        .post-item h4{
            color: #316899;
            text-align: justify;
        }

        .p-header{
            height: 20px;

        }
        ul, li {
            display: inline;
            padding: 0;
        }

        /********** works **********/

        section {
            /*padding: 20px 0;*/
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
           /* margin: 10px 0 0 0;*/
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

        [data-aos][data-aos][data-aos-delay="200"].aos-animate, body[data-aos-delay="200"] [data-aos].aos-animate {
            transition-delay: .2s;
        }
        [data-aos^=fade][data-aos^=fade].aos-animate {
            opacity: 1;
            transform: translateZ(0);
        }
        .values .box {
            padding-bottom: 25px;
            margin-left: 15px;
            box-shadow: 0px 0 5px rgb(1 41 112 / 8%);
            text-align: center;
            transition: 0.3s;
            margin-bottom: 20px;
            height: 280px;
            /*height: 293px;*/
        }

        .values .box img {
            padding: 30px 50px;
           /* transition: 0.5s;
            transform: scale(1.1);*/
        }
        .img-fluid {
            max-width: 100%;
            height: auto;
        }
        .img-fluid2 {
            max-width: 101.5%;
            height: auto;
        }
        .values .box:hover {
            box-shadow: 0px 0 30px rgba(1, 41, 112, 0.08);
        }
        .values .box:hover img {
          /*  transform: scale(1);*/
        }
        .values .box h3 {
            font-size: 16px;
            color: #012970;
            font-weight: 500;
            margin: 0px;
            width: 90%;
        }

        .values .box:hover a{
            text-decoration: none;
            /*font-size: 14px;
            position: absolute;
            top: 193px;*/
        }

        h1, h2, h3, h4, h5, h6 {
            line-height: 1.5;
        }
        .post-item h4{
            font-size: 16px;
        }
        p {
            font-size: 12px;
            margin-top: 0;
            margin-bottom: 1rem;
        }

        .sidebar{
            padding: 15px;
            /*margin: 40px 15px 0px 15px;*/
            box-shadow: 0px 0 5px rgb(1 41 112 / 8%);

        }

        .sidebar-header p {
            /* margin: 10px 0 0 0;*/
            padding: 0;
            font-size: 20px;
            line-height: 38px;
            font-weight: 600;
            color: #012970;
        }
/**********************************************/
        .counts {
            padding: 40px 0;
        }
        .counts .count-box {
            display: flex;
            align-items: center;
            padding: 30px;
            margin-bottom: 10px;
            width: 100%;
            background: #fff;
            box-shadow: 0px 0 30px rgb(1 41 112 / 8%);
        }
        .counts .col-lg-3, .counts .col-md-6{
            padding-left: 12px;
        }
        .counts .count-box i {
            font-size: 60px;
            line-height: 0;
            margin-left: 20px;
            color: #4154f1;
        }
        .counts .count-box span {
            font-size: 28px;
            display: block;
            font-weight: 600;
            color: #0b198f;
            text-align: right;
            font-family: 'Tajawal',sans-serif;
        }
        .counts .count-box p {
            padding: 0;
            margin: 0;
            font-size: 18px;
            font-weight: 600;
            line-height: 2;
        }

        .g-4, .gy-4 {
            --bs-gutter-y: 1.5rem;
        }
        @media (max-width: 992px) {
            .col-lg-3 {
                flex: 0 0 auto;
                width: 25%;
            }
        }

            @media (max-width: 768px) {
                .col-md-6 {
                    flex: 0 0 auto;
                    width: 50%;
                }

            }


        /*--------------------------------------------------------------
        # Recent Blog Posts
        --------------------------------------------------------------*/
                .recent-blog-posts .row{
                    margin-bottom: 10px;
                }

                .recent-blog-posts .post-box {
                    box-shadow: 0px 0 30px rgba(1, 41, 112, 0.08);
                    transition: 0.3s;
                    width: 270px;
                    height: 200px;
                    overflow: hidden;
                    padding: 0px 30px;
                    border-radius: 8px;
                    position: relative;
                    display: flex;
                    flex-direction: column;
                }
                .recent-blog-posts .post-box .post-img {
                    height: 160px;
                    overflow: hidden;
                    margin: -30px -30px 15px -30px;
                    position: relative;
                }
                .recent-blog-posts .post-box .post-img img {
                    transition: 0.5s;
                }
                .recent-blog-posts .post-box .detail {
                    overflow: hidden;
                    margin: -30px -30px 15px -30px;
                    position: relative;
                }

                .recent-blog-posts .post-box .post-title {
                    font-size: 16px;
                    text-align: center;
                    color: #012970;
                    font-weight: 700;
                    margin-bottom: 18px;
                    position: relative;
                    transition: 0.3s;
                }
                .recent-blog-posts .post-box:hover .post-title {
                    color: #4154f1;
                }
                .recent-blog-posts .post-box:hover .post-img img {
                    transform: rotate(6deg) scale(1.2);
                }

    </style>
</head>
<body>




<main id="main"  style="font-family: Tajawal;">
    <?php if ($is_admin){?>
        <a href="<?=base_url('Biunit/show_indicators') ?>" class="btn btn-secondary" style="float: left; margin-top: 10px;"  data-bs-title="الإعدادات" >
            <i class="icon icon-cog" style="font-size: 20px"></i>
        </a><br>
    <?php }?>


    <!-- ======= Statistics Section ======= -->
    <section id="counts" class="counts" style="margin-right: 10px">
        <div class="container" data-aos="fade-up" style="direction: rtl">
            <div class="row gy-4">
                <?php $color_arr = array("0ba7e8","f50b03","22b20a","ec8633","22b20a","ec8633","0ba7e8","f50b03");
                      $i=0;
                ?>
                <?php foreach ($indicators as $key=>$row ){
                    if($row['ACTIVE']==1){
                    ?>

                <div class="col-lg-3 col-md-6">
                    <div class="count-box">

                        <i class="icon <?= $row['ICON']?>" style="color: <?= '#'.$color_arr[$i]?>"></i>
                        <div >
                            <p><?= $row['INDICATOR_TITLE'] ?></p>
                            <span data-purecounter-start="0" data-purecounter-end="5172349" data-purecounter-duration="1" class="purecounter"><?= $row['VALU'] ?></span>

                        </div>
                    </div>
                </div>
                <?php ++$i;
                      }
                      if($i==count($color_arr)){
                          $i=0;
                      }
                }?>


        </div>
    </section><!-- End Statistics Section -->

    <div class="row">
        <div class="col-lg-9 entries">
            <!-- ======= Works Section ======= -->
            <section id="values" class="values" style="margin-right: 10px">
                <div class="container" data-aos="fade-up">
                    <header class="section-header">
                        <p>منظومات ذكاء الأعمال ودعم القرار</p>
                    </header>
                    <div class="">
                        <div style="width: 20%" class="col-lg-3" data-aos="fade-up" data-aos-delay="200">
                            <div class="box" style="direction: rtl; border-bottom: 3px solid #f68c09;border-radius: 5px;"><a href="http://gedcobi.com/edss/public/login">
                                    <img src="<?=base_url('assets/images/bi-imgs/edss.png');?>" class="img-fluid" alt="">
                                    <h3> منظومـة دعم ومتابعة القرارات eDSS </h3></a>

                            </div>
                        </div>
                        <div style="width: 20%" class="col-lg-3" data-aos="fade-up" data-aos-delay="400">
                            <div class="box" style="direction: rtl; border-bottom: 3px solid rgba(241,241,9,0.91) ;border-radius: 5px;"><a href="<?= base_url('/biunit/Da3em/index')?>">
                                    <img src="<?=base_url('assets/images/bi-imgs/dam3.png');?>" class="img-fluid" alt="">
                                    <h3 style="direction: rtl">منصـة داعـم لذكاء الأعمـال </h3></a>
                            </div>
                        </div>
                        <div style="width: 20%" class="col-lg-3" data-aos="fade-up" data-aos-delay="600">
                            <div class="box last" style="direction: rtl; border-bottom: 3px solid #08da4e ;border-radius: 5px;"><a href="<?= base_url('Biunit/smartGrid')?>">
                                    <img src="<?=base_url('assets/images/bi-imgs/smart.png');?>"  class="img-fluid" alt="">
                                    <h3> الحلول الفنية ودعـم الشبكة الذكية </h3></a>
                            </div>
                        </div>
                        <div style="width: 20%" class="col-lg-3" data-aos="fade-up" data-aos-delay="400">
                            <div class="box" style="direction: rtl; border-bottom: 3px solid #2db6fa  ;border-radius: 5px;"><a href="">
                                    <img src="<?=base_url('assets/images/bi-imgs/report.png');?>" class="img-fluid" alt="">
                                    <h3 style="direction: rtl">التقارير والاحصائيات </h3></a>
                            </div>
                        </div>
<!--                        <div style="width: 20%" class="col-lg-3" data-aos="fade-up" data-aos-delay="400">-->
<!--                            <div class="box" style="direction: rtl; border-bottom: 3px solid  #ef0404  ;border-radius: 5px;"><a href="--><?php //= base_url('/biunit/info/index')?><!--">-->
<!--                                    <img src="--><?php //=base_url('assets/images/bi-imgs/doc.png');?><!--" class="img-fluid" alt="">-->
<!--                                    <h3 style="direction: rtl">نظام المعلومات والوثائق </h3></a>-->
<!--                            </div>-->
<!--                        </div>-->

                    </div>



                </div>
            </section><!-- End Values Section -->
        </div><!-- End blog entries list -->

        <div class="col-lg-3">
            <!-- ======= Works Section ======= -->
            <section id="values" class="values" style="margin-left: 10px">
                <div class="container" data-aos="fade-up">
                    <header class="section-header">
                        <p>آخر الأعمال</p>
                    </header>
                    <div class="sidebar">
                        <div class="sidebar-item recent-posts">
                            <?php foreach ($news as $key=>$row ){ ?>
                            <div class="post-item clearfix">
                                <?php if( $row['REPORT_LINK'] > 0){ //REPORT_LINK contain Period date, then want to convert to month date (month=period+1)..
                                    $date = substr($row['REPORT_LINK'],0,4).'/'.substr($row['REPORT_LINK'],4,2).'/01';
                                    $month = date('Ym', strtotime($date."first day of +1 month"));
                                    ?>
                                    <a href="<?=base_url('Biunit/report_form?month='.$month) ?>"><h4> <?= $row['TITLE'] ?> </h4></a>
                                <?php }else{ ?>
                                    <h4> <?= $row['TITLE'] ?> </h4>
                                <?php } ?>
                                <p><?= $row['T_DATE'] ?></p>
                            </div>
                          <?php } ?>


                        </div><!-- End sidebar recent posts-->
                    </div><!-- End sidebar -->
                </div>
            </section><!-- End Values Section -->






        </div ><!-- End blog sidebar -->

    </div>



</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@srexi/purecounterjs/dist/purecounter_vanilla.js"></script>
<script src="http://code.jquery.com/jquery-1.12.4.min.js"></script>
<script src="<?=base_url('assets/js/jquery.paginate.js')?>"></script>
<script>
    //call paginate
    $('#example').paginate();
</script>
</body>
</html>