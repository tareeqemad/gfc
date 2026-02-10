<?php
/**
 * Created by PhpStorm.
 * User: telbawab
 * Date: 27/01/2019
 * Time: 11:11 ص
 */
$MODULE_NAME = 'empvacancey';
$TB_NAME = "vacancey";
$date_attr = "data-type='date' data-date-format='DD/MM/YYYY' data-val='true'
                  data-val-regex-pattern='" . date_format_exp() . "' data-val-regex='Error' ";

$isCreate = isset($master_tb_data) && count($master_tb_data) > 0 ? false : true;
$HaveRs = (!$isCreate) ? true : false;
$rs = $isCreate ? array() : $master_tb_data[0];

$adopt_url = base_url("$MODULE_NAME/$TB_NAME/adopt_");//صلاحية الاعتمادات القديمة
$adopt_main_url = base_url("$MODULE_NAME/$TB_NAME/adopt_main_");//اعتماد الرئيسي
$adopt_sub_url = base_url("$MODULE_NAME/$TB_NAME/adopt_sub_");//اعتمادات الفرع

$get_url = base_url("$MODULE_NAME/$TB_NAME/status_create");
$index_request_url = base_url("$MODULE_NAME/$TB_NAME/index_page");
$gfc_domain = gh_gfc_domain();
$report_url = base_url("JsperReport/showreport?sys=hr/hr_retirement_discharge");
$report_sn = report_sn();
?>
<!-- PAGE-HEADER -->
<div class="page-header">
    <div>
        <h1 class="page-title">متابعة طلب خلو طرف</h1>
    </div>
    <div class="ms-auto pageheader-btn">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0);">النظام الاداري</a></li>
            <li class="breadcrumb-item active" aria-current="page">خلو الطرف</li>
        </ol>
    </div>
</div>
<!-- PAGE-HEADER END -->
<!-- Row -->
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><?= $title ?></h3>
                <div class="card-options">
                    <a class="btn btn-secondary" href="<?= $index_request_url ?>">
                        <i class="fa fa-inbox"></i>
                        متابعة طلبات خلو الطرف
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form id="<?= $TB_NAME ?>_form" method="post" action="" role="form"
                      novalidate="novalidate">

                    <div class="row mt-12">
                        <div class="col-lg-12">
                            <div class="expanel expanel-primary">
                                <div class="expanel-heading">
                                    <h3 class="expanel-title fs-20 text-bold">
                                        بيانات الموظف
                                    </h3>
                                </div>
                                <div class="expanel-body">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-2">

                                            </div>
                                            <h5 class="text-bold">نشهد نحن الموقعين أدناه بأن الموظف قد اصبح خالي
                                                الطرف
                                                لدينا//
                                                للمتابعة </h5>
                                        </div>
                                        <br>
                                        <div class="row">
                                            <!--start no -->
                                            <div class="form-group  col-sm-1">
                                                <label>الرقم الوظيفي</label>
                                                <div>
                                                    <input type="text" value="<?= $rs['EMP_NO']; ?>" name="emp_no"
                                                           id="txt_emp_no" readonly class="form-control ">
                                                </div>
                                            </div>
                                            <!--end no -->
                                            <!--start name -->
                                            <div class="form-group  col-sm-2">
                                                <label> الموظف </label>
                                                <div>
                                                    <input type="text" value="<?= $rs['EMP_NAME']; ?>" name="emp_name"
                                                           id="txt_emp_name" readonly class="form-control">
                                                </div>
                                            </div>
                                            <!--end name -->
                                            <!--start id -->
                                            <div class="form-group  col-sm-2">
                                                <label> رقم الهوية </label>
                                                <div>
                                                    <input type="text" value="<?= $rs['EMP_ID']; ?>" name="emp_id"
                                                           id="txt_emp_id" readonly class="form-control ">
                                                </div>
                                            </div>
                                            <!--end id -->
                                            <!--start job -->
                                            <div class="form-group  col-sm-2">
                                                <label>الوظيفة</label>
                                                <div>
                                                    <input type="text" value="<?= $rs['EMP_JOB'] ?>" name="emp_job"
                                                           id="txt_emp_job" readonly class="form-control">

                                                </div>
                                            </div>
                                            <!--end job -->
                                            <!--start branch-->
                                            <div class="form-group  col-sm-1">
                                                <label>الفرع</label>
                                                <div>
                                                    <input type="text" value="<?= $rs['BRANCH_NAME']; ?>" name="branch"
                                                           id="txt_branch" readonly class="form-control ">
                                                    <input type="hidden" value="<?= $rs['BRANCH']; ?>" name="h_branch"
                                                           id="txt_h_branch" readonly class="form-control ">
                                                </div>
                                            </div>
                                            <!--end branch -->
                                            <div class="form-group  col-sm-3">
                                                <label>ملاحظة الادخال</label>
                                                <div>
                                                    <input type="text" value="<?= $rs['V_NOTE']; ?>" name="v_note"
                                                           id="txt_v_note" readonly class="form-control ">

                                                </div>
                                            </div>
                                            <div class="form-group  col-sm-3">
                                                <label>السبب</label>
                                                <div>
                                                    <input type="text" value="<?= $rs['EMP_END_REASON']; ?>"
                                                           name="emp_end_reason" id="txt_emp_end_reason"
                                                           class="form-control" autocomplete="off" readonly>

                                                </div>
                                            </div>
                                            <div class="form-group  col-sm-3">
                                                <label>حالة الطلب</label>
                                                <div>
                                                    <input type="text" value="<?= $rs['ADOPT_NAME']; ?>" name="adopt"
                                                           id="txt_adopt" readonly class="form-control " disabled>

                                                </div>
                                            </div>
                                            <?php if ($rs['ADOPT'] == 2 || $rs['ADOPT'] == 10 || $rs['ADOPT'] == 11 || $rs['ADOPT'] == 12 || $rs['ADOPT'] == 13 || $rs['ADOPT'] == 20
                                                || $rs['ADOPT'] == 21 || $rs['ADOPT'] == 22 || $rs['ADOPT'] == 23
                                                || $rs['ADOPT'] == 30 || $rs['ADOPT'] == 40 || $rs['ADOPT'] == 41 || $rs['ADOPT'] == 42 || $rs['ADOPT'] == 43
                                                || $rs['ADOPT'] == 50 || $rs['ADOPT'] == 60 || $rs['ADOPT'] == 61 || $rs['ADOPT'] == 62 || $rs['ADOPT'] == 63 || $rs['ADOPT'] == 70
                                                || $rs['ADOPT'] == 80 || $rs['ADOPT'] == 90
                                            ) : ?>
                                                <div class="form-group col-sm-3">
                                                    <label>ملاحظة الاعتماد </label>
                                                    <div>
                                                        <input type="text" name="adopt_note" id="txt_adopt_note"
                                                               class="form-control" autocomplete="off"/>
                                                    </div>
                                                </div>
                                            <?php endif; ?>


                                        </div>
                                        <div class="row">
                                            <div style="clear: both;">
                                                <?php echo modules::run('attachments/attachment/index', $rs['ID_VACANCY'], 'EMP_VACANCY_TB'); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row mt-12">
                        <div class="col-lg-12">
                            <div class="expanel expanel-secondary">
                                <div class="expanel-heading">
                                    <h3 class="expanel-title fs-20 text-bold">علما بان الموظف</h3>
                                </div>
                                <div class="expanel-body">
                                    <h5 class="text-on-pannel text-primary">
                                        <strong>قد أصبح خالي الطرف لدينا و أن خدمته انتهت في شركة توزيع الكهرباء اعتبار
                                            من </strong>
                                    </h5>
                                    <div class="row">
                                        <div class="col-md-2"></div>
                                        <div class="col-md-8">
                                            <div class="row">
                                                <div class="form-group col-md-2">
                                                    <label>تاريخ </label>
                                                    <input type="text"
                                                           value="<?= $rs['EMP_END_DATE']; ?>" <?= $date_attr ?>
                                                           name="emp_end_date" id="txt_emp_end_date" readonly
                                                           class="form-control">
                                                </div>
                                                <div class="form-group  col-md-6">
                                                    <label>السبب</label>
                                                    <input type="text" value="<?= $rs['EMP_END_REASON']; ?>"
                                                           name="emp_end_reason" id="txt_emp_end_reason" readonly
                                                           class="form-control ">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-2"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <?php if ($rs['TYPE_ADOPT'] == 2 && $rs['ADOPT'] >= 2 && $rs['ADOPT'] <= 70) { ?>
                        <div class="row mt-4">
                            <div class="col-lg-8">
                                <div class="expanel expanel-warning">
                                    <div class="expanel-heading">
                                        <h3 class="expanel-title fs-20 text-bold">تنبيه</h3>
                                    </div>
                                    <div class="expanel-body">
                                        <?php if (HaveAccess($adopt_sub_url . '3') && $rs['S_ADOPT_3_'] == 0 && $rs['BRANCH'] != 1) { ?>
                                            <p>
                                                - التأكد من اسقاط العهد اللوجستية والآليات والأثاث.<br>
                                                - التأكد من اسقاط عهد الحاسوب المسجلة على الموظف. <br>
                                                - التأكد من اسقاط الماكينات والعدد الفنية.<br>
                                            </p>
                                        <?php } ?>

                                        <?php if (HaveAccess($adopt_sub_url . '4') && $rs['S_ADOPT_4_'] == 0 && $rs['BRANCH'] != 1) { ?>
                                            <p>
                                                - التأكد من عمل آلية سداد لجميع الاشتراكات المسجلة على الموظف في جميع
                                                المحافظات.<br>
                                            </p>
                                        <?php } ?>

                                        <?php if (HaveAccess($adopt_sub_url . '5') && $rs['S_ADOPT_5_'] == 0 && $rs['BRANCH'] != 1) { ?>
                                            <p>
                                                - التأكد من خلو الموظف من قضايا تتعلق في التفتيش في جميع المحافظات.<br>
                                            </p>
                                        <?php } ?>

                                        <?php if (HaveAccess($adopt_sub_url . '6') && $rs['S_ADOPT_6_'] == 0 && $rs['BRANCH'] != 1) { ?>
                                            <p>
                                                - التأكد من خلو الموظف من اي قضايا ضد الشركة والعكس ولجات التحقيق
                                                الداخلية.<br>
                                            </p>
                                        <?php } ?>
                                        <?php if ($rs['ADOPT'] == 10 && $rs['BRANCH'] != 1) { ?>
                                            <p>
                                                - اعتماد مدير الفرع لكافة إجراءات خلو الطرف.
                                            </p>
                                        <?php } ?>
                                        <?php if ($rs['ADOPT'] == 30 && $rs['BRANCH'] != 1) { ?>
                                            <p>
                                                - التأكد من سير الإجراءات حسب الأصول.
                                            </p>
                                        <?php } ?>
                                        <?php if ($rs['ADOPT'] == 50 && $rs['BRANCH'] != 1) { ?>
                                            <p>
                                                - التأكد من خلو طرف الموظف من أي مستحقات او سلف مالية وأي تسويات مالية
                                                أخرى.
                                            </p>
                                        <?php } ?>
                                        <?php if ($rs['ADOPT'] == 70 && $rs['BRANCH'] != 1) { ?>
                                            <p>
                                                - لطباعة النموذج واتمام اجراءات انهاء الخدمة
                                            </p>
                                        <?php } ?>
                                        <?php if (HaveAccess($adopt_main_url . '10') && $rs['M_ADOPT_10_'] == 0 && $rs['BRANCH'] == 1) { ?>
                                            <p>
                                                - التأكد من عمل آلية سداد لجميع الاشتراكات المسجلة على الموظف في جميع
                                                المحافظات، وأي مستحقات او سلف مالية وأي تسويات مالية أخرى بالإضافة لأي
                                                قضايا تتعلق بالتفتيش في جميع المحافظات وذلك بالتنسيق مع الجهات المختصة
                                                بالمقر الرئيسي .

                                            </p>
                                        <?php } ?>
                                        <?php if (HaveAccess($adopt_main_url . '30') && $rs['M_ADOPT_30_'] == 0 && $rs['BRANCH'] == 1) { ?>
                                            <p>
                                                - التأكد من اسقاط العهد اللوجستية والآليات والأثاث وأجهزة الحاسوب
                                                والماكينات والعدد الفنية .... الخ. <br>
                                            </p>
                                        <?php } ?>

                                        <?php if (HaveAccess($adopt_main_url . '40') && $rs['M_ADOPT_40_'] == 0 && $rs['BRANCH'] == 1) { ?>
                                            <p>

                                                - التأكد من تسليم أجهزة الحاسوب نفسها وليس غيرها.
                                            </p>
                                        <?php } ?>

                                        <?php if (HaveAccess($adopt_main_url . '50') && $rs['M_ADOPT_50_'] == 0 && $rs['BRANCH'] == 1) { ?>
                                            <p>
                                                - التأكد من خلو طرف الموظف من أي قضايا ضد الشركة والعكس ولجان التحقيق
                                                الداخلية.
                                            </p>
                                        <?php } ?>


                                        <?php if (HaveAccess($adopt_main_url . '90') && $rs['ADOPT'] == 70 && $rs['BRANCH'] == 1) { ?>
                                            <p>
                                                - لطباعة النموذج واتمام اجراءات انهاء الخدمة
                                            </p>
                                        <?php } ?>

                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <div class="row">
                        <div class="flex-shrink-0">
                            <button type="button" onclick="$('#myModal').modal('show');" class="btn btn-blue">بيانات
                                الاعتمادات
                            </button>
                            <?php if (HaveAccess($adopt_url . '2') && ($isCreate || ($rs['ADOPT'] == 1 and isset($can_edit) ? $can_edit : false))) : ?>
                                <button type="button" onclick='javascript:adopt_(2);' id="btn_adopt_start_2"
                                        class="btn btn-success">
                                    <i class="fa fa-check"></i>
                                    اعتماد المدخل
                                </button>
                            <?php endif; ?>

                            <?php if ($rs['TYPE_ADOPT'] == 1) { ?>
                                <?php if (HaveAccess($adopt_url . '10') and !$isCreate and $rs['ADOPT_10'] == 0 and $rs['ADOPT'] >= 2 and $rs['CNT_ADOPT'] < 16) : ?>
                                    <button type="button" onclick='javascript:adopt_(10);' class="btn btn-success"
                                            id="btn_adopt_10"><i class="fa fa-check"></i>
                                        اعتماد مدير المقر/الدائرة
                                    </button>
                                <?php endif; ?>
                                <?php if (HaveAccess($adopt_url . '11') and !$isCreate and $rs['ADOPT_11'] == 0 and $rs['ADOPT'] >= 2 and $rs['CNT_ADOPT'] < 16) : ?>
                                    <button type="button" onclick='javascript:adopt_(11);' class="btn btn-success"
                                            id="btn_adopt_11">
                                        <i class="fa fa-check"></i>
                                        دائرة التصميم واعداد المشروعات
                                    </button>
                                <?php endif; ?>
                                <?php if (HaveAccess($adopt_url . '12') and !$isCreate and $rs['ADOPT_12'] == 0 and $rs['ADOPT'] >= 2 and $rs['CNT_ADOPT'] < 16) : ?>
                                    <button type="button" onclick='javascript:adopt_(12);' class="btn btn-success"
                                            id="btn_adopt_12"><i class="fa fa-check"></i>
                                        دائرة الصيانة
                                    </button>
                                <?php endif; ?>
                                <?php if (HaveAccess($adopt_url . '13') and !$isCreate and $rs['ADOPT_13'] == 0 and $rs['ADOPT'] >= 2 and $rs['CNT_ADOPT'] < 16) : ?>
                                    <button type="button" onclick='javascript:adopt_(13);' class="btn btn-success"
                                            id="btn_adopt_13">دائرة تنفيذ المشروع
                                    </button>
                                <?php endif; ?>
                                <?php if (HaveAccess($adopt_url . '20') and !$isCreate and $rs['ADOPT_20'] == 0 and $rs['ADOPT'] >= 2 and $rs['CNT_ADOPT'] < 16) : ?>
                                    <button type="button" onclick='javascript:adopt_(20);' class="btn btn-success"
                                            id="btn_adopt_20">اعتماد الادارة الفنية
                                    </button>
                                <?php endif; ?>
                                <?php if (HaveAccess($adopt_url . '21') and !$isCreate and $rs['ADOPT_21'] == 0 and $rs['ADOPT'] >= 2 and $rs['CNT_ADOPT'] < 16) : ?>
                                    <button type="button" onclick='javascript:adopt_(21);' class="btn btn-success"
                                            id="btn_adopt_21">اعتماد دائرة الخدمات
                                    </button>
                                <?php endif; ?>
                                <?php if (HaveAccess($adopt_url . '30') and !$isCreate and $rs['ADOPT_30'] == 0 and $rs['ADOPT'] >= 2 and $rs['CNT_ADOPT'] < 16) : ?>
                                    <button type="button" onclick='javascript:adopt_(30);' class="btn btn-success"
                                            id="btn_adopt_30">اعتماد ادارة اللوزام والخدمات
                                    </button>
                                <?php endif; ?>
                                <?php if (HaveAccess($adopt_url . '40') and !$isCreate and $rs['ADOPT_40'] == 0 and $rs['ADOPT'] >= 2 and $rs['CNT_ADOPT'] < 16) : ?>
                                    <button type="button" onclick='javascript:adopt_(40);' class="btn btn-success"
                                            id="btn_adopt_40">اعتماد ادارة الحاسوب
                                    </button>
                                <?php endif; ?>
                                <?php if (HaveAccess($adopt_url . '41') and !$isCreate and $rs['ADOPT_41'] == 0 and $rs['ADOPT'] >= 2 and $rs['CNT_ADOPT'] < 16) : ?>
                                    <button type="button" onclick='javascript:adopt_(41);' class="btn btn-success"
                                            id="btn_adopt_41"> دائرة الفوترة والتحصيل
                                    </button>
                                <?php endif; ?>
                                <?php if (HaveAccess($adopt_url . '42') and !$isCreate and $rs['ADOPT_42'] == 0 and $rs['ADOPT'] >= 2 and $rs['CNT_ADOPT'] < 16) : ?>
                                    <button type="button" onclick='javascript:adopt_(42);' class="btn btn-success"
                                            id="btn_adopt_42">دائرة التفتيش ومراقبة الاستهلاك
                                    </button>
                                <?php endif; ?>
                                <?php if (HaveAccess($adopt_url . '43') and !$isCreate and $rs['ADOPT_43'] == 0 and $rs['ADOPT'] >= 2 and $rs['CNT_ADOPT'] < 16) : ?>
                                    <button type="button" onclick='javascript:adopt_(43);' class="btn btn-success"
                                            id="btn_adopt_43">دائرة خدمات المشتركين
                                    </button>
                                <?php endif; ?>
                                <?php if (HaveAccess($adopt_url . '50') and !$isCreate and $rs['ADOPT_50'] == 0 and $rs['ADOPT'] >= 2 and $rs['CNT_ADOPT'] < 16) : ?>
                                    <button type="button" onclick='javascript:adopt_(50);' class="btn btn-success"
                                            id="btn_adopt_50">اعتماد الادارة التجارية
                                    </button>
                                <?php endif; ?>
                                <?php if (HaveAccess($adopt_url . '60') and !$isCreate and $rs['ADOPT_60'] == 0 and $rs['ADOPT'] >= 2 and $rs['CNT_ADOPT'] < 16) : ?>
                                    <button type="button" onclick='javascript:adopt_(60);' class="btn btn-success"
                                            id="btn_adopt_60">اعتماد دائرة الشؤون القانونية
                                    </button>
                                <?php endif; ?>
                                <?php if (HaveAccess($adopt_url . '62') and !$isCreate and $rs['ADOPT_62'] == 0 and $rs['ADOPT'] >= 2 and $rs['CNT_ADOPT'] < 16) : ?>
                                    <button type="button" onclick='javascript:adopt_(62);' class="btn btn-success"
                                            id="btn_adopt_62">اعتماد دائرة الحسابات العامة
                                    </button>
                                <?php endif; ?>
                                <?php if (HaveAccess($adopt_url . '70') and !$isCreate and $rs['ADOPT_70'] == 0 and $rs['ADOPT'] >= 2 and $rs['CNT_ADOPT'] < 16) : ?>
                                    <button type="button" onclick='javascript:adopt_(70);' class="btn btn-success"
                                            id="btn_adopt_70">اعتماد الادارة المالية
                                    </button>
                                <?php endif; ?>
                                <?php if (HaveAccess($adopt_url . '80') and !$isCreate /*and $rs['ADOPT'] == 70*/ and $rs['CNT_ADOPT'] == 16) : ?>
                                    <button type="button" onclick='javascript:adopt_(80);' class="btn btn-success"
                                            id="btn_adopt_80"> اعتماد الرقابة الداخلية
                                    </button>
                                <?php endif; ?>
                                <?php if (HaveAccess($adopt_url . '90') and !$isCreate and $rs['ADOPT'] == 80 /*and $rs['CNT_ADOPT'] == 16*/) : ?>
                                    <button type="button" onclick='javascript:adopt_(90);' class="btn btn-success"
                                            id="btn_adopt_90">اعتماد الشؤون الادارية
                                    </button>
                                <?php endif; ?>

                            <?php } elseif ($rs['TYPE_ADOPT'] == 2 && $rs['BRANCH'] != 1) { ?>


                                <?php if (HaveAccess($adopt_sub_url . '3') && !$isCreate && $rs['ADOPT_2_'] == 1 && $rs['S_ADOPT_3_'] == 0 && $rs['BRANCH'] != 1) : ?>
                                    <button type="button" onclick='javascript:adoptSub_(3);' class="btn btn-success"
                                            id="btn_adopt_3">
                                        <i class="fa fa-check"></i>
                                        قسم اللوازم
                                    </button>
                                <?php endif; ?>


                                <?php if (HaveAccess($adopt_sub_url . '4') & !$isCreate && $rs['ADOPT_2_'] == 1 && $rs['S_ADOPT_4_'] == 0 && $rs['BRANCH'] != 1) : ?>
                                    <button type="button" onclick='javascript:adoptSub_(4);' class="btn btn-success"
                                            id="btn_adopt_4">
                                        <i class="fa fa-check"></i>
                                        قسم التحصيل
                                    </button>
                                <?php endif; ?>


                                <?php if (HaveAccess($adopt_sub_url . '5') && !$isCreate && $rs['ADOPT_2_'] == 1 && $rs['S_ADOPT_5_'] == 0 && $rs['BRANCH'] != 1) : ?>
                                    <button type="button" onclick='javascript:adoptSub_(5);' class="btn btn-success"
                                            id="btn_adopt_5">
                                        <i class="fa fa-check"></i>
                                        قسم التفتيش
                                    </button>
                                <?php endif; ?>


                                <?php if (HaveAccess($adopt_sub_url . '6') && !$isCreate && $rs['ADOPT_2_'] == 1 && $rs['S_ADOPT_6_'] == 0 && $rs['BRANCH'] != 1) : ?>
                                    <button type="button" onclick='javascript:adoptSub_(6);' class="btn btn-success"
                                            id="btn_adopt_6">
                                        <i class="fa fa-check"></i>
                                        المستشار القانوني
                                    </button>
                                <?php endif; ?>


                                <?php if (HaveAccess($adopt_sub_url . '10') && !$isCreate && $rs['CNT_ADOPT'] == 5 && $rs['BRANCH'] != 1) : ?>
                                    <button type="button" onclick='javascript:adoptSub_(10);' class="btn btn-success"
                                            id="btn_adopt_10">
                                        <i class="fa fa-check"></i>
                                        دائرة الشؤون المالية والادارية
                                    </button>
                                <?php endif; ?>

                                <?php if (HaveAccess($adopt_sub_url . '30') && !$isCreate && $rs['ADOPT'] == 10 && $rs['BRANCH'] != 1) : ?>
                                    <button type="button" onclick='javascript:adoptSub_(30);' class="btn btn-success"
                                            id="btn_adopt_30">
                                        <i class="fa fa-check"></i>
                                        اعتماد مدير الفرع
                                    </button>
                                <?php endif; ?>


                                <?php if (HaveAccess($adopt_sub_url . '50') && !$isCreate && $rs['ADOPT'] == 30 && $rs['BRANCH'] != 1) : ?>
                                    <button type="button" onclick='javascript:adoptSub_(50);' class="btn btn-success"
                                            id="btn_adopt_50">
                                        <i class="fa fa-check"></i>
                                        اعتماد قسم الرقابة
                                    </button>
                                <?php endif; ?>


                                <?php if (HaveAccess($adopt_sub_url . '70') && !$isCreate && $rs['ADOPT'] == 50 && $rs['BRANCH'] != 1) : ?>
                                    <button type="button" onclick='javascript:adoptSub_(70);' class="btn btn-success"
                                            id="btn_adopt_70">
                                        <i class="fa fa-check"></i>
                                        اعتماد دائرة الحسابات
                                    </button>
                                <?php endif; ?>


                                <?php if (HaveAccess($adopt_sub_url . '90') && !$isCreate && $rs['ADOPT'] == 70 && $rs['BRANCH'] != 1) : ?>
                                    <button type="button" onclick='javascript:adoptSub_(90);' class="btn btn-success"
                                            id="btn_adopt_90">
                                        <i class="fa fa-check"></i>
                                        اعتماد ادارة الموارد البشرية
                                    </button>
                                <?php endif; ?>

                            <?php } elseif ($rs['TYPE_ADOPT'] == 2 && $rs['BRANCH'] == 1) { ?>

                                <?php if (HaveAccess($adopt_main_url . '10') && !$isCreate && $rs['ADOPT_2_'] == 1 && $rs['M_ADOPT_10_'] == 0 && $rs['BRANCH'] == 1) : ?>
                                    <button type="button" onclick='javascript:adoptMain_(10);' class="btn btn-success"
                                            id="btn_adopt_10">
                                        <i class="fa fa-check"></i>
                                        اعتماد دائرة الحسابات
                                    </button>
                                <?php endif; ?>


                                <?php if (HaveAccess($adopt_main_url . '30') && !$isCreate && $rs['ADOPT_2_'] == 1 && $rs['M_ADOPT_30_'] == 0 && $rs['BRANCH'] == 1) : ?>
                                    <button type="button" onclick='javascript:adoptMain_(30);' class="btn btn-success"
                                            id="btn_adopt_30">
                                        <i class="fa fa-check"></i>
                                        اعتماد دائرة المستودعات
                                    </button>
                                <?php endif; ?>


                                <?php if (HaveAccess($adopt_main_url . '40') && !$isCreate && $rs['ADOPT_2_'] == 1 && $rs['M_ADOPT_40_'] == 0 && $rs['BRANCH'] == 1) : ?>
                                    <button type="button" onclick='javascript:adoptMain_(40);' class="btn btn-success"
                                            id="btn_adopt_40">
                                        <i class="fa fa-check"></i>
                                        اعتماد ادارة الحاسوب
                                    </button>
                                <?php endif; ?>


                                <?php if (HaveAccess($adopt_main_url . '50') && !$isCreate && $rs['ADOPT_2_'] == 1 && $rs['M_ADOPT_50_'] == 0 && $rs['BRANCH'] == 1) : ?>
                                    <button type="button" onclick='javascript:adoptMain_(50);' class="btn btn-success"
                                            id="btn_adopt_50">
                                        <i class="fa fa-check"></i>
                                        اعتماد وحدة الشئون القانونية
                                    </button>
                                <?php endif; ?>


                                <?php if (HaveAccess($adopt_main_url . '70') && !$isCreate && $rs['CNT_ADOPT'] == 5 && $rs['BRANCH'] == 1) : ?>
                                    <button type="button" onclick='javascript:adoptMain_(70);' class="btn btn-success"
                                            id="btn_adopt_70">
                                        <i class="fa fa-check"></i>
                                        اعتماد إدارة الرقابة الداخلية
                                    </button>
                                <?php endif; ?>


                                <?php if (HaveAccess($adopt_main_url . '90') && !$isCreate && $rs['ADOPT'] == 70 && $rs['BRANCH'] == 1) : ?>
                                    <button type="button" onclick='javascript:adoptMain_(90);' class="btn btn-success"
                                            id="btn_adopt_90">
                                        <i class="fa fa-check"></i>
                                        اعتماد إدارة الموارد البشرية
                                    </button>
                                <?php endif; ?>

                            <?php } else { ?>

                            <?php } ?>
                            <button type="button" id="rep_details"
                                    onclick='javascript:print_report(<?= $rs['ID_VACANCY'] ?>,<?= $rs['TYPE_ADOPT'] ?>);'
                                    class="btn btn-secondary-gradient">
                                <i class="fa fa-print"></i>
                                طباعة
                            </button>
                        </div>
                        <!------------------>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">بيانات</h5>
                <button class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="panel panel-primary">
                    <div class="tab-menu-heading border-bottom-0">
                        <div class="tabs-menu4 border-bottomo-sm">
                            <!-- Tabs -->
                            <nav class="nav d-sm-flex d-block">
                                <a class="nav-link border border-bottom-0 br-sm-5 me-2 active" data-bs-toggle="tab"
                                   href="#tab25">
                                    <i class="fa fa-cog"></i>
                                    بيانات الاعتمادات
                                </a>
                                <a class="nav-link border border-bottom-0 br-sm-5 me-2" data-bs-toggle="tab"
                                   href="#tab26">
                                    <i class="fa fa-tasks"></i>
                                    مسار الاعتماد
                                </a>
                            </nav>
                        </div>
                    </div>
                    <div class="panel-body tabs-menu-body">
                        <div class="tab-content">
                            <div class="tab-pane active " id="tab25">
                                <table class="table table-bordered" id="page_tb_1">
                                    <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>المقر/ الادارة / الوحدة</th>
                                        <th>الملاحظات</th>
                                        <th>اسم المعتمد</th>
                                        <th>تاريخ الاعتماد</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $count_res = 1;
                                    foreach ($get_adopt as $row)  : ; ?>
                                        <tr>
                                            <td><?= $count_res++ ?></td>
                                            <td><?= $row['ADOPT_NO_NAME'] ?></td>
                                            <td><?= $row['NOTE'] ?></td>
                                            <td><?= $row['ADOPT_USER_NAME'] ?></td>
                                            <td><?= $row['ADOPT_DATE'] ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane" id="tab26">
                                <table class="table  table-bordered" id="page_tb_2">
                                    <thead class="table-light">
                                    <tr>
                                        <th>م</th>
                                        <th>صلاحية الاعتماد</th>
                                        <th>المستخدم</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $count_row = 1;
                                    foreach ($path_list as $post)  : ?>
                                        <tr>
                                            <td><?= $count_row++ ?></td>
                                            <td><?= $post['NOTE'] ?></td>
                                            <td><?= $post['USER_ID_NAME'] ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">اغلاق</button>
            </div>
        </div>
    </div>
</div>
<!------------------------->


<?php
$scripts = <<<SCRIPT
<script type="text/javascript">
        
      $('button[data-action="submit"]').click(function(e){
            e.preventDefault();
            if(confirm('هل تريد الحفظ  ؟!')){
              $(this).attr('disabled','disabled');
                var form = $(this).closest('form');
                ajax_insert_update(form,function(data){
                    if(parseInt(data)>=1){
                        success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                        get_to_link(window.location.href);
                    }else{
                          danger_msg('تحذير..',data);
                    }
                },'html');
            }
            setTimeout(function() {
                $('button[data-action="submit"]').removeAttr('disabled');
            }, 3000);
       });

   
     var btn__= '';
     $('#btn_adopt_start_2,#btn_adopt_2,#btn_adopt_3,#btn_adopt_4,#btn_adopt_5,#btn_adopt_6,#btn_adopt_10,#btn_adopt_11,#btn_adopt_12,#btn_adopt_13,#btn_adopt_20,#btn_adopt_30,#btn_adopt_40,#btn_adopt_50,#btn_adopt_60,#btn_adopt_70,#btn_adopt_80,#btn_adopt_90').click( function(){
          btn__ = $(this);
     });
        
    function adopt_(no){
            var msg= 'هل تريد الاعتماد ؟!';
            if(confirm(msg)){
                var values= {id_vacancy: "{$rs['ID_VACANCY']}" , adopt_note: $('#txt_adopt_note').val() };
                get_data('{$adopt_url}'+no, values, function(ret){
                  if(ret>=1){
                        success_msg('رسالة','تم اعتماد الطلب بنجاح ..');
                        $('button').attr('disabled','disabled');
                        if(no < 90){
                            var sub= 'اعتماد طلب شهادة خلو طرف {$rs['ID_VACANCY']}';
                            var text= 'يرجى اعتماد خلو طرف  رقم {$rs['ID_VACANCY']}';
                            text+= '<br>{$rs['EMP_NAME']} - {$rs['V_NOTE']}';
                            text+= '<br>للاطلاع افتح الرابط';
                            text+= '<br>{$gfc_domain}{$get_url}/{$rs['ID_VACANCY']}';
                            _send_mail(btn__,'{$next_adopt_email}',sub,text);
                            _send_mail(btn__,'telbawab@gedco.ps',sub,text)
                            btn__ = '';
                        } 
                         setTimeout(function(){
                            get_to_link(window.location.href);
                        },2000);
                  }
                }, 'html');
            }
    }
 
    function adoptSub_(no){
        var msg= 'هل تريد الاعتماد ؟!';
            if(confirm(msg)){
                var values= {id_vacancy: "{$rs['ID_VACANCY']}" , adopt_note: $('#txt_adopt_note').val(),h_branch: $('#txt_h_branch').val() };
                get_data('{$adopt_sub_url}'+no, values, function(ret){
                    if(ret>=1){
                        success_msg('رسالة','تم اعتماد الطلب بنجاح ..');
                        $('button').attr('disabled','disabled');
                        if(no < 90){
                            var sub= 'اعتماد طلب شهادة خلو طرف {$rs['ID_VACANCY']}';
                            var text= 'يرجى اعتماد خلو طرف  رقم {$rs['ID_VACANCY']}';
                            text+= '<br>{$rs['EMP_NAME']} - {$rs['V_NOTE']}';
                            text+= '<br>للاطلاع افتح الرابط';
                            text+= '<br>{$gfc_domain}{$get_url}/{$rs['ID_VACANCY']}';
                            _send_mail(btn__,'{$next_adopt_email}',sub,text);
                            _send_mail(btn__,'telbawab@gedco.ps',sub,text)
                            btn__ = '';
                        } 
                        setTimeout(function(){
                            get_to_link(window.location.href);
                        },2000);
                    }else{
                        danger_msg('تحذير..',ret);
                    }
                }, 'html');
            }       
    }
    
    function adoptMain_(no){
        var msg= 'هل تريد الاعتماد ؟!';
            if(confirm(msg)){
                var values= {id_vacancy: "{$rs['ID_VACANCY']}" , adopt_note: $('#txt_adopt_note').val(),h_branch: $('#txt_h_branch').val() };
                get_data('{$adopt_main_url}'+no, values, function(ret){
                    if(ret==1){
                        success_msg('رسالة','تم اعتماد الطلب بنجاح ..');
                        $('button').attr('disabled','disabled');
                         if(no < 90){
                            var sub= 'اعتماد طلب شهادة خلو طرف {$rs['ID_VACANCY']}';
                            var text= 'يرجى اعتماد خلو طرف  رقم {$rs['ID_VACANCY']}';
                            text+= '<br>{$rs['EMP_NAME']} - {$rs['V_NOTE']}';
                            text+= '<br>للاطلاع افتح الرابط';
                            text+= '<br>{$gfc_domain}{$get_url}/{$rs['ID_VACANCY']}';
                            _send_mail(btn__,'{$next_adopt_email}',sub,text);
                            _send_mail(btn__,'telbawab@gedco.ps',sub,text)
                            btn__ = '';
                        } 
                        setTimeout(function(){
                            get_to_link(window.location.href);
                        },2000);
                    }else{
                        danger_msg('تحذير..',ret);
                    }
                }, 'html');
            }       
    }
    
    function print_report(id_vacancy,type_adopt){
        if(type_adopt == 2){
            type_adopt = '';
            var rep_url = '{$report_url}&report_type=pdf&report=retirement_discharge&p_id='+id_vacancy+'&p_type_adopt='+type_adopt+'&sn={$report_sn}';
            _showReport(rep_url);
        }else{
            var rep_url = '{$report_url}&report_type=pdf&report=retirement_discharge&p_id='+id_vacancy+'&p_type_adopt='+type_adopt+'&sn={$report_sn}';
            _showReport(rep_url);
        }
    }
  
</script>
SCRIPT;
sec_scripts($scripts);
?>
