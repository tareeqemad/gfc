<div class="card card-custom wave wave-animate p-6 mb-8">
    <div class="card-body">
        <!--begin::Heading-->
        <h2 class="text-dark mb-8"> نظـام المعلـومات والوثـائـق </h2>
        <!--end::Heading-->
        <!--begin::Content-->
        <h4 class="font-weight-bold text-dark mb-4">
            وحدة ذكاء الأعمال ودعم القرار
        </h4>
        <div class="text-dark-70 line-height-lg mb-8">
            <p>يضم كافة الأنظمة واللوائح وقرارات تنظيم الأعمال، بحيث يحتوي على بعض القرارات من منظومة القرارات الالكترونية (eDSS) على حسب درجة السرية، إضافة الى الأنظمة واللوائح التنظيمية في الشركة. </p>
        </div>
        <!--end::Content-->
    </div>
</div>

<div class="row ">
    <div class="col-lg-12">
        <!--begin::Callout-->
        <div class="card card-custom p-5">
            <div class="card-body">
                <div class="row">
                    <?php $color_arr = array("primary","info","danger","success");
                    $i=0;
                    ?>
                    <?php foreach ($categories as $key=>$row ){ ?>
                    <div class="col-xl-6">
                        <!--begin::Stats Widget 13-->
                        <a href="<?=base_url('/biunit/info/show?category_id='.$row['ID']) ?>" class= "<?= 'card card-custom bg-'.$color_arr[$i].' bg-hover-state-'.$color_arr[$i].' card-stretch card-stretch gutter-b' ?>" >
                            <!--begin::Body-->
                            <div class="card-body" style="padding-top: 10px;">
                                <div class="text-inverse-danger font-weight-bolder font-size-h5 mb-2 mt-5" style="font-size: 90px !important;margin: 0px !important;line-height: 1;">59</div>
                                <div class="font-weight-bold text-inverse-danger font-size-sm" style="font-size: 17px;"> <?= $row['CATEGORY_NAME'] ?>
                                </div>
                            </div>
                            <!--end::Body-->
                        </a>
                        <!--end::Stats Widget 13-->
                    </div>
                    <?php ++$i;

                        if($i==count($color_arr)){
                            $i=0;
                        }
                      } ?>

                </div>

            </div>
        </div>
        <!--end::Callout-->
    </div>
</div>