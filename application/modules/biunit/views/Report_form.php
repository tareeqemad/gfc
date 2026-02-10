<html>
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Inter', sans-serif;
            direction: rtl;
            background-color: rgba(0,0,0,.075)
        }
        .formbold-main-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 48px;
        }

        .formbold-form-wrapper {
            margin: 0 auto;
            max-width: 1500px;
            width: 100%;
            background: white;
            padding: 40px;
            border-radius: 14px;
            box-shadow: -1px 0px 25px -5px rgba(59,59,59,0.72);
            -webkit-box-shadow: -1px 0px 25px -5px rgba(59,59,59,0.72);
            -moz-box-shadow: -1px 0px 25px -5px rgba(59,59,59,0.72);
        }

        .formbold-form-img {
            margin-bottom: 45px;
        }

        .formbold-input-group {
            margin-bottom: 18px;
        }

        .formbold-form-select {
            width: 100%;
            padding: 12px 22px;
            border-radius: 5px;
            border: 1px solid #dde3ec;
            background: #ffffff;
            font-size: 16px;
            color: #536387;
            outline: none;
            resize: none;
        }

        .formbold-input-radio-wrapper {
            margin-bottom: 25px;
        }
        .formbold-radio-flex {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        .formbold-radio-label {
            font-size: 14px;
            line-height: 24px;
            color: #07074d;
            position: relative;
            padding-left: 25px;
            cursor: pointer;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }
        .formbold-input-radio {
            position: absolute;
            opacity: 0;
            cursor: pointer;
        }
        .formbold-radio-checkmark {
            position: absolute;
            top: -1px;
            left: 0;
            height: 18px;
            width: 18px;
            background-color: #ffffff;
            border: 1px solid #dde3ec;
            border-radius: 50%;
        }
        .formbold-radio-label
        .formbold-input-radio:checked
        ~ .formbold-radio-checkmark {
            background-color: #6a64f1;
        }
        .formbold-radio-checkmark:after {
            content: '';
            position: absolute;
            display: none;
        }

        .formbold-radio-label
        .formbold-input-radio:checked
        ~ .formbold-radio-checkmark:after {
            display: block;
        }

        .formbold-radio-label .formbold-radio-checkmark:after {
            top: 50%;
            left: 50%;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #ffffff;
            transform: translate(-50%, -50%);
        }

        .formbold-form-input {
            width: 100%;
            padding: 13px 22px;
            border-radius: 5px;
            border: 1px solid #dde3ec;
            background: #ffffff;
            font-weight: 500;
            font-size: 16px;
            color: #07074d;
            outline: none;
            resize: none;
        }
        .formbold-form-input::placeholder {
            color: #536387;
        }
        .formbold-form-input:focus {
            border-color: #6a64f1;
            box-shadow: 0px 3px 8px rgba(0, 0, 0, 0.05);
        }
        .formbold-form-label {
            color: #07074d;
            font-size: 17px;
            font-weight: 550;
            line-height: 24px;
            display: block;
            margin-bottom: 10px;
            text-align: right;
        }

        .formbold-btn {
            text-align: center;
            width: 100%;
            font-size: 18px;
            font-weight: 600;
            border-radius: 5px;
            padding: 14px 25px;
            border: none;
            font-weight: 500;
            background-color: rgb(4, 100, 252);
            color: white;
            cursor: pointer;
            margin-top: 25px;
        }
        .formbold-btn:hover {
            box-shadow: 0px 3px 8px rgba(0, 0, 0, 0.05);
        }
        .alert-secondary {
            color: #000000;
            font-weight:700;
            font-size: 17px;
            height: 37px;
            padding: 5px 20px;
        }
        .btn-primary, .btn-secondary{
            font-weight: bold;
        }
        .nav-item a{
            font-weight: bold;
        }
        .tab-pane{
            min-height: 400px;
        }
        .form-head{
            background-color: rgba(222,219,219,0.17);
            width: 100%;
            height: 80px;
            padding: 20px 20px;
            margin-bottom: 20px;
            border: 1px solid #dee2e6;
            border-radius: 3px;
        }
        .invalid-feedback {
            text-align: right;
        }
    </style>

</head>
<body data-new-gr-c-s-check-loaded="14.1114.0" data-gr-ext-installed="">

<div class="formbold-main-wrapper">
    <!-- Author: FormBold Team -->
    <!-- Learn More: https://formbold.com -->
    <div class="formbold-form-wrapper" style="border: 1px solid #bec0c2">
        <div class="row">
            <div class="col-lg-1">
               <img style="width: 70px;height: 75px" src="<?=base_url('assets/images/bi-imgs/logo2.png');?>" alt="">
            </div>
            <div class="col-lg-11" style="text-align: center;color: #050000;font-weight: 600;">
               <h4 style="text-align: center;margin-bottom: 10px;font-weight: 600; padding-top: 10px">نموذج التقرير الشهري لمتابعة الفروع - شركة توزيع كهرباء محافظات غزة </h4>
                <h5 style="font-weight: 600;">تطوير وحدة ذكاء الأعمال ودعم القرار</h5>

            </div>

        </div>


        <form id="form_s" class="needs-validation" action="<?=base_url('biunit/Report/create') ?>" method="POST"  onsubmit="return validateForm()" style="margin-top: 20px" novalidate>
            <?php if($_GET['msg']==2 || $_GET['msg']==1 || $msg>0){ ?>
                <input type="hidden" value="<?=$_GET['msg']?>" id="txt_flag" name="flag">
                <div class="alert alert-danger text-center" role="alert">
                    تم تعبئة نموذج التقرير الشهري للفروع...
                </div>
            <?php }else{
                $date = substr($_GET['month'],0,4).'/'.substr($_GET['month'],4,2).'/01';
                ?>
                <h6 style="text-align: center; color: #d51304; font-weight: 600;">النموذج لمتابعة الفروع عن دورة <?= date('Ym', strtotime($date."first day of -1 month")) ?>  من  الفترة <?= date('Y/m/d', strtotime($date."first day of this month")) ?> الى <?=  date('Y/m/d',strtotime($date."last day of this month"))?>
                </h6>
                <input type="hidden" value="0" id="txt_flag" name="flag">
            <div class="form-head row">
                    <div class="form-group col-4 row">
                        <label for="age" class="formbold-form-label col-3" style="padding: 5px 10px;text-align: left" >الفرع: </label>
                         <select class="form-control col-9" name="branch_id" id="branch_id" style="height: 38px" >
                             <option value="<?= $branch_id ?>" selected >
                                 <?php  if($branch_id==2)
                                      echo 'غزة';
                                 elseif ($branch_id==3)
                                     echo 'الشمال';
                                  elseif ($branch_id==4)
                                      echo 'الوسطى';
                                  elseif ($branch_id==6)
                                      echo 'خانيونس';
                                  elseif ($branch_id==7)
                                      echo 'رفح';
                                     ?>
                             </option>
                         </select>

                    </div>
                    <div class="form-group col-4 row">
                        <label for="age" class="formbold-form-label col-4" style="padding: 5px 10px;text-align: left" >الدورة: </label>
                        <input type="text" class="form-control col-8" name="period" id="period" value="<?= date('Ym', strtotime($date."first day of -1 month")) ?>" style="height: 38px" disabled>
                    </div>
                    <div class="form-group col-4 row">
                        <label for="age" class="formbold-form-label col-4" style="padding: 5px 10px;text-align: left" >اسم المستخدم: </label>
                        <input type="text" class="form-control col-8" name="user_name" value="<?= get_curr_user()->fullname ?>" style="height: 38px" disabled>
                    </div>


                <input type="hidden" name="month"  value="<?= $_GET['month'] ?>">
                <input type="hidden" name="user_id" id="user_id" value="<?= get_curr_user()->username ?>">

            </div>


            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="contact-tab" data-toggle="tab" href="#page3" role="tab" aria-controls="contact" aria-selected="false">النشاط المالي</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="contact-tab" data-toggle="tab" href="#page4" role="tab" aria-controls="contact" aria-selected="false"> أعمال مركز العناية بالزبائن</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="contact-tab" data-toggle="tab" href="#page6" role="tab" aria-controls="contact" aria-selected="false">المشتركين حسب نوع العداد وآلية السداد </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="contact-tab" data-toggle="tab" href="#page5" role="tab" aria-controls="contact" aria-selected="false">أعمال التفتيش</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="page2-tab" data-toggle="tab" href="#page2" role="tab" aria-controls="page2" aria-selected="false"> أعمال المشاريع الفنية</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="page1-tab" data-toggle="tab" href="#page1" role="tab" aria-controls="page1" aria-selected="true">مؤشرات أخرى</a>
                </li>

            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="page3" role="tabpanel" aria-labelledby="page3-tab"><br>
                    <div class="row">
                        <div class="form-group col-6">
                            <label for="age" class="formbold-form-label">  اجمالي الإيرادات النقدية (شيكل)<span class="text-danger">*</span>  </label>
                            <input type="text" name="indicator[13]" id="ind_13" placeholder="قيمة" class="form-control" pattern="^\₪\d{1,3}(,\d{3})*(\.\d+)?$" data-type="currency" required>
                            <div class="invalid-feedback">
                                هذا الحقل مطلوب
                            </div>
                        </div>
                        <div class="form-group col-6">
                            <label for="age" class="formbold-form-label">  اجمالي النفقات النقدية (شيكل)<span class="text-danger">*</span>  </label>
                            <input type="text" name="indicator[14]" id="ind_14" placeholder="قيمة" class="form-control" pattern="^\₪\d{1,3}(,\d{3})*(\.\d+)?$" data-type="currency" required>
                            <div class="invalid-feedback">
                                هذا الحقل مطلوب
                            </div>
                        </div>
                        <div class="form-group col-6">
                            <label for="age" class="formbold-form-label">  اجمالي الفاتورة الشهرية (شيكل)<span class="text-danger">*</span>  </label>
                            <input type="text" name="indicator[15]"id="ind_15" placeholder="قيمة" class="form-control" pattern="^\₪\d{1,3}(,\d{3})*(\.\d+)?$" data-type="currency" required>
                            <div class="invalid-feedback">
                                هذا الحقل مطلوب
                            </div>
                        </div>
                        <div class="form-group col-6">
                            <label for="age" class="formbold-form-label">  اجمالي الفاتورة الشهرية بدون حكومة وبلديات (شيكل)<span class="text-danger">*</span>  </label>
                            <input type="text" name="indicator[16]" id="ind_16" placeholder="قيمة" class="form-control" pattern="^\₪\d{1,3}(,\d{3})*(\.\d+)?$" data-type="currency" required>
                            <div class="invalid-feedback">
                                هذا الحقل مطلوب
                            </div>
                        </div>
                        <div class="form-group col-6">
                            <label for="age" class="formbold-form-label">   التحصيل من فاتورة المبيعات (شيكل)<span class="text-danger">*</span>  </label>
                            <input type="text" name="indicator[17]" id="ind_17" placeholder="قيمة" class="form-control" pattern="^\₪\d{1,3}(,\d{3})*(\.\d+)?$" data-type="currency" required>
                            <div class="invalid-feedback">
                                هذا الحقل مطلوب
                            </div>
                        </div>
                        <div class="form-group col-6">
                            <label for="age" class="formbold-form-label"> اجمالي التحصيل من المتأخرات (شيكل)<span class="text-danger">*</span>  </label>
                            <input type="text" name="indicator[18]" id="ind_18" placeholder="قيمة" class="form-control" pattern="^\₪\d{1,3}(,\d{3})*(\.\d+)?$" data-type="currency" required>
                            <div class="invalid-feedback">
                                هذا الحقل مطلوب
                            </div>
                        </div>
                        <div class="form-group col-6">
                            <label for="age" class="formbold-form-label"> نسبة اجمالي التحصيل من المتأخرات من الفاتورة الشهرية (نسبة)<span class="text-danger">*</span>  </label>
                            <input type="text" name="indicator[19]" id="ind_19" min="0" max="100" step="0.01" id="age" placeholder="نسبة" class="form-control"  required>
                            <div class="invalid-feedback">
                                هذا الحقل مطلوب
                            </div>
                        </div>
                        <div class="form-group col-6">
                            <label for="age" class="formbold-form-label">  حالات المتابعة الاجمالية للاشتراكات الغير ملتزمة شهريا ً (عدد)<span class="text-danger">*</span>  </label>
                            <input type="number" name="indicator[20]" id="ind_20"  placeholder="عدد" class="form-control" required>
                            <div class="invalid-feedback">
                                هذا الحقل مطلوب
                            </div>
                        </div>
                        <div class="form-group col-6">
                            <label for="age" class="formbold-form-label">   القراءات الصفرية (عدد)<span class="text-danger">*</span>  </label>
                            <input type="number" name="indicator[21]" id="ind_21"  placeholder="عدد" class="form-control" required>
                            <div class="invalid-feedback">
                                هذا الحقل مطلوب
                            </div>
                        </div>
                        <div class="form-group col-6">
                            <label for="age" class="formbold-form-label">   القراءات الصفرية الدائمة (عدد)<span class="text-danger">*</span>  </label>
                            <input type="number" name="indicator[22]" id="ind_22" placeholder="عدد" class="form-control" required>
                            <div class="invalid-feedback">
                                هذا الحقل مطلوب
                            </div>
                        </div>
                        <div class="form-group col-6">
                            <label for="age" class="formbold-form-label">  نسبة المبالغ المحصلة من المبالغ المرسلة للبنوك (نسبة)<span class="text-danger">*</span>  </label>
                            <input type="number" name="indicator[23]" id="ind_23" min="0" max="100" step="0.01" placeholder="نسبة" class="form-control" required>
                            <div class="invalid-feedback">
                                هذا الحقل مطلوب
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="page4" role="tabpanel" aria-labelledby="page4-tab"><br>
                    <div class="row">
                        <div class="form-group col-6">
                            <label for="age" class="formbold-form-label">  المعاملات المقدمة في مراكز العناية (عدد)<span class="text-danger">*</span>  </label>
                            <input type="number" name="indicator[24]" id="ind_24" placeholder="عدد" class="form-control" required>
                            <div class="invalid-feedback">
                                هذا الحقل مطلوب
                            </div>
                        </div>
                        <div class="form-group col-6">
                            <label for="age" class="formbold-form-label"> المعاملات المنجزة " من المقدم بذات الشهر" (عدد)<span class="text-danger">*</span>  </label>
                            <input type="number" name="indicator[25]" id="ind_25" placeholder="عدد" class="form-control" required>
                            <div class="invalid-feedback">
                                هذا الحقل مطلوب
                            </div>
                        </div>
                        <div class="form-group col-6">
                            <label for="age" class="formbold-form-label">  المعاملات المنجزة " من المقدم بذات الشهر" وتم توثيقها GIS (عدد)<span class="text-danger">*</span>  </label>
                            <input type="number" name="indicator[26]" id="ind_26" placeholder="عدد" class="form-control" required>
                            <div class="invalid-feedback">
                                هذا الحقل مطلوب
                            </div>
                        </div>
                        <div class="form-group col-6">
                            <label for="age" class="formbold-form-label">  المعاملات العالقة " غير منجزة" (عدد)<span class="text-danger">*</span>  </label>
                            <input type="number" name="indicator[27]" id="ind_27" placeholder="عدد" class="form-control" required>
                            <div class="invalid-feedback">
                                هذا الحقل مطلوب
                            </div>
                        </div>
                        <div class="form-group col-6">
                            <label for="age" class="formbold-form-label"> ايراد خدمات المشتركين (شيكل)<span class="text-danger">*</span>  </label>
                            <input type="text" name="indicator[28]" id="ind_28"  placeholder="قيمة" class="form-control" pattern="^\₪\d{1,3}(,\d{3})*(\.\d+)?$" data-type="currency" required>
                            <div class="invalid-feedback">
                                هذا الحقل مطلوب
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="page6" role="tabpanel" aria-labelledby="page6-tab"><br>
                    <div class="alert alert-secondary text-center" role="alert">
                        عداد ميكانيكي
                    </div>
                    <div class="row">
                        <label for="age" class="formbold-form-label col-2"> عداد ميكانيكي سداد آلي ملتزم<span class="text-danger">*</span> </label>
                        <div class="form-group col-5">
                            <input type="number" name="indicator[40]" id="ind_40" placeholder="عدد المشتركين الذين تم الارسال لهم للبنك وتم التحصيل ولو جزئياً لهذه الدورة" class="form-control " required>
                        </div>
                        <div class="form-group col-5">
                            <input type="text" name="indicator[41]" id="ind_41" pattern="^\₪\d{1,3}(,\d{3})*(\.\d+)?$" data-type="currency" placeholder="قيمة التحصيل من الفواتير المرسلة للبنك وتم تحصيلها (كاملة او جزئياً) لهذه الدورة" class="form-control"  required>
                        </div>

                        <label for="age" class="formbold-form-label col-2"> عداد ميكانيكي سداد آلي غير ملتزم<span class="text-danger">*</span> </label>
                        <div class="form-group col-5">
                            <input type="number" name="indicator[42]" id="ind_42" placeholder="عدد المشتركين الذين تم الارسال لهم للبنك و لم يتم التحصيل بتاتاً لهذا الدورة" class="form-control " required>
                        </div>
                        <div class="form-group col-5">
                            <input type="text" name="indicator[43]" id="ind_43" pattern="^\₪\d{1,3}(,\d{3})*(\.\d+)?$" data-type="currency"  placeholder="قيمة فواتير المشتركين المرسلة للبنك ولم يتم  تحصيلها بتاتاً لهذه الدورة" class="form-control" required>
                        </div>
                        <label for="age" class="formbold-form-label col-2"> عداد ميكانيكي سداد مركزي<span class="text-danger">*</span> </label>
                        <div class="form-group col-5">
                            <input type="number" name="indicator[44]" id="ind_44" placeholder="عدد المشتركين التي يتم سداد فواتيرهم  في هذه الدورة بشكل مركزي" class="form-control " required>
                        </div>
                        <div class="form-group col-5">
                            <input type="text" name="indicator[45]" id="ind_45" pattern="^\₪\d{1,3}(,\d{3})*(\.\d+)?$" data-type="currency"  placeholder="قيمة فواتير المشتركين ذوي السداد المركزي في هذه الدورة" class="form-control"  required>
                        </div>
                        <label for="age" class="formbold-form-label col-2"> عداد ميكانيكي سداد ميداني<span class="text-danger">*</span> </label>
                        <div class="form-group col-5">
                            <input type="number" name="indicator[46]" id="ind_46" id="age" placeholder="عدد المشتركين التي يتم سداد فواتيرهم في هذه الدورة بشكل ميداني في هذه الدورة" class="form-control " required>
                        </div>
                        <div class="form-group col-5">
                            <input type="text" name="indicator[47]" id="ind_47" pattern="^\₪\d{1,3}(,\d{3})*(\.\d+)?$" data-type="currency"  placeholder="قيمة فواتير المشتركين ذوي السداد الميداني" class="form-control"  required>
                        </div>
                        <label for="age" class="formbold-form-label col-2"> عداد ميكانيكي بدون آلية سداد<span class="text-danger">*</span> </label>
                        <div class="form-group col-10">
                            <input type="number" name="indicator[48]" id="ind_48"  placeholder="عدد المشتركين بدون آلية سداد باستثناء الاشتراكات التي بدون آلية سداد ولكنها ملتزمة (مثلا اشتراكات موظفي الشركة، اشتراكات الخدمات التي يتم توزيع مبالغها...الخ)" class="form-control "  required>
                        </div>


                    </div>
                    <div class="alert alert-secondary text-center" role="alert">
                        عداد مسبق الدفع
                    </div>
                    <div class="row">
                        <label for="age" class="formbold-form-label col-2">  عداد مسبق الدفع ملتزم بالشحن<span class="text-danger">*</span> </label>
                        <div class="form-group col-5">
                            <input type="number" name="indicator[49]" id="ind_49" placeholder="عدد المشتركين الملتزمين بالشحن، أي شحن شحنة واحدة عالاقل خلال الدورة" class="form-control "  required>
                        </div>
                        <div class="form-group col-5">
                            <input type="text" name="indicator[50]" id="ind_50" pattern="^\₪\d{1,3}(,\d{3})*(\.\d+)?$" data-type="currency"  placeholder="قيمة مدفوعات مشتركي عدادات هولي خلال الدورة" class="form-control"  required>
                        </div>

                        <label for="age" class="formbold-form-label col-2"> عداد مسبق الدفع غير ملتزم بالشحن<span class="text-danger">*</span> </label>
                        <div class="form-group col-5">
                            <input type="number" name="indicator[51]" id="ind_51" placeholder="عدد المشتركين الغير ملتزمين بالشحن، أي لم يشحن أي شحنة خلال الدورة" class="form-control "  required>
                        </div>
                        <div class="form-group col-5">
                            <input type="text" name="indicator[52]" id="ind_52" pattern="^\₪\d{1,3}(,\d{3})*(\.\d+)?$" data-type="currency"  placeholder="قيمة ديون مشتركي عدادات هولي ولم يشحنو أي شحنة خلال الدورة" class="form-control" required>
                        </div>
                    </div>
                    <div class="alert alert-secondary text-center" role="alert">
                        عداد ذكي
                    </div>
                    <div class="row">
                        <label for="age" class="formbold-form-label col-2">  عداد ذكي سداد آلي ملتزم<span class="text-danger">*</span> </label>
                        <div class="form-group col-5">
                            <input type="number" name="indicator[53]" id="ind_53" placeholder="عدد المشتركين الذين تم الارسال لهم للبنك وتم التحصيل ولو جزئياً لهذه الدورة" class="form-control " required>
                        </div>
                        <div class="form-group col-5">
                            <input type="text" name="indicator[54]" id="ind_54" pattern="^\₪\d{1,3}(,\d{3})*(\.\d+)?$" data-type="currency"  placeholder="قيمة التحصيل من الفواتير المرسلة للبنك وتم تحصيلها (كاملة او جزئياً) لهذه الدورة" class="form-control" required>
                        </div>
                        <label for="age" class="formbold-form-label col-2"> عداد ذكي سداد آلي غير ملتزم<span class="text-danger">*</span> </label>
                        <div class="form-group col-5">
                            <input type="number" name="indicator[55]" id="ind_55" placeholder="عدد المشتركين الذين تم الارسال لهم للبنك و لم يتم التحصيل بتاتاً لهذا الدورة" class="form-control " required>
                        </div>
                        <div class="form-group col-5">
                            <input type="text" name="indicator[56]" id="ind_56" pattern="^\₪\d{1,3}(,\d{3})*(\.\d+)?$" data-type="currency"  placeholder="قيمة فواتير المشتركين المرسلة للبنك ولم يتم  تحصيلها بتاتاً لهذه الدورة" class="form-control" required>
                        </div>
                        <label for="age" class="formbold-form-label col-2"> عداد ذكي مسبق الدفع ملتزم بالشحن<span class="text-danger">*</span> </label>
                        <div class="form-group col-5">
                            <input type="number" name="indicator[57]" id="ind_57" placeholder="عدد المشتركين الملتزمين بالشحن، أي شحن شحنة واحدة عالاقل خلال الدورة" class="form-control " required>
                        </div>
                        <div class="form-group col-5">
                            <input type="text" name="indicator[58]" id="ind_58" pattern="^\₪\d{1,3}(,\d{3})*(\.\d+)?$" data-type="currency"  placeholder="قيمة مدفوعات مشتركي العدادات الذكية مسبقة الدفع خلال الدورة" class="form-control" required>
                        </div>
                        <label for="age" class="formbold-form-label col-2"> عداد ذكي مسبق الدفع غير ملتزم بالشحن<span class="text-danger">*</span> </label>
                        <div class="form-group col-5">
                            <input type="number" name="indicator[59]" id="ind_59" placeholder="عدد المشتركين الغير ملتزمين بالشحن، أي لم يشحن أي شحنة خلال الدورة" class="form-control " required>
                        </div>
                        <div class="form-group col-5">
                            <input type="text" name="indicator[60]" id="ind_60" pattern="^\₪\d{1,3}(,\d{3})*(\.\d+)?$" data-type="currency"  placeholder="قيمة ديون مشتركي العدادات الذكية مسبقة الدفع ولم يشحنو أي شحنة خلال الدورة" class="form-control" required>
                        </div>

                    </div>

                </div>
                <div class="tab-pane fade" id="page5" role="tabpanel" aria-labelledby="page5-tab"><br>
                    <div class="row">

                        <div class="form-group col-6">
                            <label for="age" class="formbold-form-label"> القضايا المضبوطة الجديدة (قضية)<span class="text-danger">*</span>  </label>
                            <input type="number" name="indicator[29]" id="ind_29" placeholder="عدد" class="form-control" required>
                            <div class="invalid-feedback">
                                هذا الحقل مطلوب
                            </div>
                        </div>
                        <div class="form-group col-6">
                            <label for="age" class="formbold-form-label"> القضايا المضبوطة الجديدة والتي تم توثيقها على GIS (قضية)<span class="text-danger">*</span>  </label>
                            <input type="number" name="indicator[30]"  id="ind_30" placeholder="عدد" class="form-control" required>
                            <div class="invalid-feedback">
                                هذا الحقل مطلوب
                            </div>
                        </div>
                        <div class="form-group col-6">
                            <label for="age" class="formbold-form-label"> ضبط استهلاك (من القضايا المضوبطة) (KWH)<span class="text-danger">*</span>  </label>
                            <input type="number" name="indicator[31]" id="ind_31" step="0.01"  placeholder="قيمة" class="form-control" required>
                            <div class="invalid-feedback">
                                هذا الحقل مطلوب
                            </div>
                        </div>
                        <div class="form-group col-6">
                            <label for="age" class="formbold-form-label"> القضايا المحلولة (شهري)<span class="text-danger">*</span>  </label>
                            <input type="number" name="indicator[32]" id="ind_32" placeholder="عدد" class="form-control" required>
                            <div class="invalid-feedback">
                                هذا الحقل مطلوب
                            </div>
                        </div>
                        <div class="form-group col-6">
                            <label for="age" class="formbold-form-label"> القضايا العالقة بدون اشتراك (عدد تراكمي)<span class="text-danger">*</span>  </label>
                            <input type="number" name="indicator[33]" id="ind_33" placeholder="عدد" class="form-control" required>
                            <div class="invalid-feedback">
                                هذا الحقل مطلوب
                            </div>
                        </div>
                        <div class="form-group col-6">
                            <label for="age" class="formbold-form-label"> القضايا العالقة لها اشتراك (عدد تراكمي)<span class="text-danger">*</span>  </label>
                            <input type="number" name="indicator[34]"  id="ind_34" placeholder="عدد" class="form-control" required>
                            <div class="invalid-feedback">
                                هذا الحقل مطلوب
                            </div>
                        </div>
                        <div class="form-group col-6">
                            <label for="age" class="formbold-form-label"> الربطات الغير شرعية (عدد تراكمي)<span class="text-danger">*</span>  </label>
                            <input type="number" name="indicator[35]" id="ind_35" placeholder="عدد" class="form-control" required>
                            <div class="invalid-feedback">
                                هذا الحقل مطلوب
                            </div>
                        </div>
                        <div class="form-group col-6">
                            <label for="age" class="formbold-form-label"> عدد محولات عليها لوحات مراقبة (عدد)<span class="text-danger">*</span>  </label>
                            <input type="number" name="indicator[36]"  id="ind_36" placeholder="عدد" class="form-control" required>
                            <div class="invalid-feedback">
                                هذا الحقل مطلوب
                            </div>
                        </div>
                        <div class="form-group col-6">
                            <label for="age" class="formbold-form-label"> عدد منتفعي خدمة الحي الذكي "2 امبير" (عدد)<span class="text-danger">*</span>  </label>
                            <input type="number" name="indicator[37]"  id="ind_37" placeholder="عدد" class="form-control" required>
                            <div class="invalid-feedback">
                                هذا الحقل مطلوب
                            </div>
                        </div>
                        <div class="form-group col-6">
                            <label for="age" class="formbold-form-label"> معاملات خدماتية "التفتيش" (عدد)<span class="text-danger">*</span>  </label>
                            <input type="number" name="indicator[38]" id="ind_38" placeholder="عدد" class="form-control" required>
                            <div class="invalid-feedback">
                                هذا الحقل مطلوب
                            </div>
                        </div>
                        <div class="form-group col-6">
                            <label for="age" class="formbold-form-label"> نسبة الفاقد الكهربائي (نسبة)<span class="text-danger">*</span> </label>
                            <input type="number" name="indicator[39]" id="ind_39" min="0" max="100" step="0.01" placeholder="نسبة" class="form-control" required>
                            <div class="invalid-feedback">
                                هذا الحقل مطلوب
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="page2" role="tabpanel" aria-labelledby="page2-tab"><br>
                    <div class="row">
                        <div class="form-group col-6">
                            <label for="age" class="formbold-form-label"> مشاريع التأهيل (شيكل)<span class="text-danger">*</span>  </label>
                            <input type="text" name="indicator[3]" id="ind_3" placeholder="قيمة" class="form-control" pattern="^\₪\d{1,3}(,\d{3})*(\.\d+)?$" data-type="currency" required>
                            <div class="invalid-feedback">
                                هذا الحقل مطلوب
                            </div>
                        </div>
                        <div class="form-group col-6">
                            <label for="age" class="formbold-form-label">   صيانة الشبكة الطارئة (شيكل)<span class="text-danger">*</span>  </label>
                            <input type="text" name="indicator[4]" id="ind_4" placeholder="قيمة" class="form-control" pattern="^\₪\d{1,3}(,\d{3})*(\.\d+)?$" data-type="currency" required>
                            <div class="invalid-feedback">
                                هذا الحقل مطلوب
                            </div>
                        </div>

                    <div class="form-group col-6">
                        <label for="age" class="formbold-form-label">  صيانة الشبكة الوقائية (الكفاءة الفنية) (شيكل)<span class="text-danger">*</span>  </label>
                        <input type="text" name="indicator[5]" id="ind_5" placeholder="قيمة" class="form-control" pattern="^\₪\d{1,3}(,\d{3})*(\.\d+)?$" data-type="currency" required>
                        <div class="invalid-feedback">
                            هذا الحقل مطلوب
                        </div>
                    </div>
                    <div class="form-group col-6">
                        <label for="age" class="formbold-form-label"> مشاريع إعادة الإعمار (شيكل)<span class="text-danger">*</span>  </label>
                        <input type="text" name="indicator[6]" id="ind_6" placeholder="قيمة" class="form-control" pattern="^\₪\d{1,3}(,\d{3})*(\.\d+)?$" data-type="currency" required>
                        <div class="invalid-feedback">
                            هذا الحقل مطلوب
                        </div>
                    </div>
                    <div class="form-group col-6">
                        <label for="age" class="formbold-form-label"> المشاريع التطويرية (شيكل)<span class="text-danger">*</span>  </label>
                        <input type="text" name="indicator[7]" id="ind_7" placeholder="قيمة" class="form-control" pattern="^\₪\d{1,3}(,\d{3})*(\.\d+)?$" data-type="currency" required>
                        <div class="invalid-feedback">
                            هذا الحقل مطلوب
                        </div>
                    </div>
                    <div class="form-group col-6">
                        <label for="age" class="formbold-form-label"> تصميم مشاريع (عدد)<span class="text-danger">*</span>  </label>
                        <input type="number" name="indicator[8]" id="ind_8" placeholder="قيمة" class="form-control" required>
                        <div class="invalid-feedback">
                            هذا الحقل مطلوب
                        </div>
                    </div>
                    <div class="form-group col-6">
                        <label for="age" class="formbold-form-label"> المشاريع مدفوعة التكاليف (شيكل)<span class="text-danger">*</span>  </label>
                        <input type="text" name="indicator[9]" id="ind_9" placeholder="قيمة" class="form-control" pattern="^\₪\d{1,3}(,\d{3})*(\.\d+)?$" data-type="currency" required>
                        <div class="invalid-feedback">
                            هذا الحقل مطلوب
                        </div>
                    </div>
                    <div class="form-group col-6">
                        <label for="age" class="formbold-form-label"> نسبة عدد المشاريع الموثقة على GIS الى اجمالي عدد كافة المشاريع (نسبة)<span class="text-danger">*</span>  </label>
                        <input type="number" min="0" max="100" step="0.01" name="indicator[10]" id="ind_10" placeholder="قيمة" class="form-control" required>
                        <div class="invalid-feedback">
                            هذا الحقل مطلوب
                        </div>
                    </div>
                    <div class="form-group col-6">
                        <label for="age" class="formbold-form-label"> محولات محترقة (محول)<span class="text-danger">*</span> </label>
                        <input type="number" name="indicator[11]" id="ind_11" placeholder="عدد" class="form-control" required>
                        <div class="invalid-feedback">
                            هذا الحقل مطلوب
                        </div>
                    </div>
                    <div class="form-group col-6">
                        <label for="age" class="formbold-form-label"> عدد إشارات الصيانة المعالجة (اشارة)<span class="text-danger">*</span></label>
                        <input type="number" name="indicator[12]" id="ind_12" placeholder="عدد" class="form-control" required>
                        <div class="invalid-feedback">
                            هذا الحقل مطلوب
                        </div>
                    </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="page1" role="tabpanel" aria-labelledby="page1-tab"><br>
                    <div class="form-group">
                        <label for="age" class="formbold-form-label"> متوسط العجز في الطاقة (ميجا وات)<span class="text-danger">*</span> </label>
                        <input type="number" name="indicator[1]" id="ind_1" step="0.01"  placeholder="قيمة" class="form-control col-6" required>
                        <div class="invalid-feedback">
                            هذا الحقل مطلوب
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="age" class="formbold-form-label">  التقدم في تركيب العدادات الذكية (عدد تراكمي)<span class="text-danger">*</span> </label>
                        <input type="number" name="indicator[2]" id="ind_2" placeholder="عدد"  class="form-control col-6" required>
                        <div class="invalid-feedback">
                            هذا الحقل مطلوب
                        </div>
                    </div>




                </div>
            </div>

<br>
            <input type="submit" id="btn_send" value="ارسال" class="btn btn-primary col-sm-2" style="float: right;">
            <?php } ?>
            <a href="<?= base_url('Biunit/index')?>" class="btn btn-secondary col-sm-2" style="float: right; margin-right: 10px">عودة</a>

        </form>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script src="<?= base_url() ?>assets/js/jquery-1.11.1.min.js"></script>
<script src="<?= base_url() ?>assets/js/jquery-ui.min.js"></script>

<script src="<?= base_url() ?>assets/js/bootstrap.min.js"></script>

<script src="<?= base_url() ?>assets/js/jqueryval.min.js"></script>
<script src="<?= base_url() ?>assets/js/jquery.easyui.min.js"></script>
<script src="<?= base_url() ?>assets/js/toastr.min.js"></script>
<script src="<?= base_url() ?>assets/js/jquery.hotkeys.js"></script>

<!--<script src="<? /*= base_url()*/ ?>assets/js/jquery.jscrollpane.min.js"></script>-->
<script src="<?= base_url() ?>assets/js/app.js"></script>

<script type="text/javascript" src="<?= base_url() ?>assets/js/export/tableExport.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/js/export/jquery.base64.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/js/bootbox.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/js/jquery.calculadora.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/js/jquery.mask.js"></script>
<script>
    var _base_url = '/gfc/';
</script>

<script src="<?= base_url() ?>assets/js/functions.js"></script>
<script src="<?= base_url() ?>assets/js/ajax.js"></script>

<script type="text/javascript" src="<?= base_url() ?>assets/js/moment.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/js/bootstrap-datetimepicker.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/js/jquery.dataTables.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/js/sweetalert2/sweetalert2.all.min.js"></script>
<script type="text/javascript" src="<?= base_url() ?>assets/js/select2.min.js"></script>

<script>

    var btn__= '';
    // var branch = '';

    $('#btn_send').click( function() {
        btn__ = $(this);
        // btn__ =$('#btn_send');

        var user_mail = $('#user_id').val() + '@gedco.ps';
        var sub = 'نموذج التقرير الشهري لمتابعة مؤشرات أداء فرع ' + $('#branch_id :selected').text() + ' عن دورة ' + $('#period').val();

        var text = 'نموذج التقرير الشهري لمتابعة مؤشرات أداء فرع ' + $('#branch_id :selected').text() + ' عن دورة ' + $('#period').val();
        text += '\n\n';
        text += 'النشاط المالي';
        text += '\n';
        text += ' اجمالي الإيرادات النقدية' + '  ' + $('#ind_13').val();
        text += '\n';
        text += ' اجمالي النفقات النقدية' + '  ' + $('#ind_14').val();
        text += '\n';
        text += '  اجمالي الفاتورة الشهرية' + '  ' + $('#ind_15').val();
        text += '\n';
        text += '  اجمالي الفاتورة الشهرية بدون حكومة وبلديات' + '  ' + $('#ind_16').val();
        text += '\n';
        text += '  التحصيل من فاتورة المبيعات' + '  ' + $('#ind_17').val();
        text += '\n';
        text += '  اجمالي التحصيل من المتأخرات' + '  ' + $('#ind_18').val();
        text += '\n';
        text += '  نسبة اجمالي التحصيل من المتأخرات من الفاتورة الشهرية' + '  ' + $('#ind_19').val();
        text += '\n';
        text += '  حالات المتابعة الاجمالية للاشتراكات الغير ملتزمة شهريا ' + '  ' + $('#ind_20').val();
        text += '\n';
        text += '  القراءات الصفرية' + '  ' + $('#ind_21').val();
        text += '\n';
        text += '  القراءات الصفرية الدائمة' + '  ' + $('#ind_22').val();
        text += '\n';
        text += ' نسبة المبالغ المحصلة من المبالغ المرسلة للبنوك' + '  ' + $('#ind_23').val();
        text += '\n\n';


        text += 'أعمال مركز العناية بالزبائن';
        text += '\n';
        text += 'المعاملات المقدمة في مراكز العناية' + '  ' + $('#ind_24').val();
        text += '\n';
        text += ' المعاملات المنجزة من المقدم بذات الشهر' + '  ' + $('#ind_25').val();
        text += '\n\n';
        text += 'المعاملات المنجزة من المقدم بذات الشهر وتم توثيقها GIS' + '  ' + $('#ind_26').val();
        text += '\n\n';
        text += 'المعاملات العالقة غير منجزة' + '  ' + $('#ind_27').val();
        text += '\n\n';
        text += 'ايراد خدمات المشتركين' + '  ' + $('#ind_28').val();
        text += '\n\n';

        text += ' المشتركين حسب توع العداد وآلية السداد';
        text += '\n';
        text += 'عداد ميكانيكي سداد آلي ملتزم_عدد' + '  ' + $('#ind_40').val();
        text += '\n\n';
        text += 'عداد ميكانيكي سداد آلي ملتزم_قيمة' + '  ' + $('#ind_41').val();
        text += '\n\n';
        text += 'عداد ميكانيكي سداد آلي غير ملتزم_عدد' + '  ' + $('#ind_42').val();
        text += '\n\n';
        text += 'عداد ميكانيكي سداد آلي غير ملتزم_قيمة' + '  ' + $('#ind_43').val();
        text += '\n';
        text += 'عداد ميكانيكي سداد مركزي_عدد' + '  ' + $('#ind_44').val();
        text += '\n';
        text += 'عداد ميكانيكي سداد مركزي_قيمة' + '  ' + $('#ind_45').val();
        text += '\n';
        text += 'عداد ميكانيكي سداد ميداني_عدد' + '  ' + $('#ind_46').val();
        text += '\n';
        text += 'عداد ميكانيكي سداد ميداني_قيمة' + '  ' + $('#ind_47').val();
        text += '\n\n';
        text += 'عداد ميكانيكي بدون آلية سداد_عدد' + '  ' + $('#ind_48').val();
        text += '\n\n';
        text += 'عداد مسبق الدفع ملتزم بالشحن_عدد' + '  ' + $('#ind_49').val();
        text += '\n\n';
        text += 'عداد مسبق الدفع ملتزم بالشحن_قيمة' + '  ' + $('#ind_50').val();
        text += '\n\n';
        text += 'عداد مسبق الدفع غير ملتزم بالشحن_عدد' + '  ' + $('#ind_51').val();
        text += '\n\n';
        text += 'عداد مسبق الدفع غير ملتزم بالشحن_قيمة' + '  ' + $('#ind_52').val();
        text += '\n';
        text += 'عداد ذكي سداد آلي ملتزم_عدد' + '  ' + $('#ind_53').val();
        text += '\n';
        text += 'عداد ذكي سداد آلي ملتزم_قيمة' + '  ' + $('#ind_54').val();
        text += '\n';
        text += 'عداد ذكي سداد آلي غير ملتزم_عدد' + '  ' + $('#ind_55').val();
        text += '\n\n';
        text += 'عداد ذكي سداد آلي غير ملتزم_قيمة' + '  ' + $('#ind_56').val();
        text += '\n\n';
        text += 'عداد ذكي مسبق الدفع ملتزم بالشحن_عدد' + '  ' + $('#ind_57').val();
        text += '\n';
        text += 'عداد ذكي مسبق الدفع ملتزم بالشحن_قيمة' + '  ' + $('#ind_58').val();
        text += '\n';
        text += 'عداد ذكي مسبق الدفع غير ملتزم بالشحن_عدد' + '  ' + $('#ind_59').val();
        text += '\n';
        text += 'عداد ذكي مسبق الدفع غير ملتزم بالشحن_قيمة' + '  ' + $('#ind_60').val();
        text += '\n\n';

        text += 'أعمال التفتيش';
        text += '\n';
        text += 'القضايا المضبوطة الجديدة' + '  ' + $('#ind_29').val();
        text += '\n';
        text += 'القضايا المضبوطة الجديدة والتي تم توثيقها على GIS';
        text += ' ' + $('#ind_30').val();
        text += '\n';
        text += ' ضبط استهلاك من القضايا المضوبطة' + '  ' + $('#ind_31').val();
        text += '\n';
        text += 'القضايا المحلولة' + '  ' + $('#ind_32').val();
        text += '\n';
        text += 'القضايا العالقة بدون اشتراك' + '  ' + $('#ind_33').val();
        text += '\n';
        text += 'القضايا العالقة لها اشتراك' + '  ' + $('#ind_34').val();
        text += '\n';
        text += 'الربطات الغير شرعية' + '  ' + $('#ind_35').val();
        text += '\n';
        text += 'عدد محولات عليها لوحات مراقبة' + '  ' + $('#ind_36').val();
        text += '\n';
        text += 'عدد منتفعي خدمة الحي الذكي "2 امبير"' + '  ' + $('#ind_37').val();
        text += '\n';
        text += 'معاملات خدماتية "التفتيش"' + '  ' + $('#ind_38').val();
        text += '\n';
        text += 'نسبة الفاقد الكهربائي' + '  ' + $('#ind_39').val();
        text += '\n\n';

        text += 'أعمال المشاريع الفنية ';
        text += '\n';
        text += 'مشاريع التأهيل' + '  ' + $('#ind_3').val();
        text += '\n';
        text += 'صيانة الشبكة الطارئة' + '  ' + $('#ind_4').val();
        text += '\n';
        text += 'صيانة الشبكة الوقائية' + '  ' + $('#ind_5').val();
        text += '\n';
        text += 'مشاريع إعادة الإعمار' + '  ' + $('#ind_6').val();
        text += '\n';
        text += 'المشاريع التطويرية' + '  ' + $('#ind_7').val();
        text += '\n';
        text += 'تصميم مشاريع' + '  ' + $('#ind_8').val();
        text += '\n';
        text += 'المشاريع مدفوعة التكاليف' + '  ' + $('#ind_9').val();
        text += '\n';
        text += 'نسبة عدد المشاريع الموثقة على GIS الى اجمالي عدد كافة المشاريع' + '  ' + $('#ind_10').val();
        text += '\n';
        text += 'محولات محترقة' + '  ' + $('#ind_11').val();
        text += '\n';
        text += 'عدد إشارات الصيانة المعالجة' + '  ' + $('#ind_12').val();
        text += '\n\n';

        text += 'مؤشرات أخرى';
        text += '\n';
        text += 'متوسط العجز في الطاقة' + '  ' + $('#ind_1').val();
        text += '\n';
        text += 'التقدم في تركيب العدادات الذكية' + '  ' + $('#ind_2').val();
        text += '\n';

        if ($("#form_s").valid()) {
            _send_mail(btn__, user_mail, sub, text);
            _send_mail(btn__, 'msabah@gedco.ps', sub, text);
        }
    });



    $(function () {
            $('#myTab li:first-child a').tab('show')

    })

    $(document).ready(function(){
        var verify_send = $('#txt_flag').val();
        if(verify_send == 1){
            send();
        }
            var current = new Date();
            current.setMonth(current.getMonth()-1);
            current.setDate(1);
         //   document.getElementById("todayDate").value = current.toLocaleString('en-CA',{dateStyle:"short"})+"";
           // document.getElementById("month").value = current.toLocaleString('en-CA',{dateStyle:"short"})+"";

        });

    $("input[data-type='currency']").on({
            keyup: function() {
                formatCurrency($(this));
            },
            blur: function() {
                formatCurrency($(this), "blur");
            }
        });

    function formatNumber(n) {
            // format number 1000000 to 1,234,567
            return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",")
        }

    function formatCurrency(input, blur) {
            // appends $ to value, validates decimal side
            // and puts cursor back in right position.

            // get input value
            var input_val = input.val();

            // don't validate empty input
            if (input_val === "") { return; }

            // original length
            var original_len = input_val.length;

            // initial caret position
            var caret_pos = input.prop("selectionStart");

            // check for decimal
            if (input_val.indexOf(".") >= 0) {

                // get position of first decimal
                // this prevents multiple decimals from
                // being entered
                var decimal_pos = input_val.indexOf(".");

                // split number by decimal point
                var left_side = input_val.substring(0, decimal_pos);
                var right_side = input_val.substring(decimal_pos);

                // add commas to left side of number
                left_side = formatNumber(left_side);

                // validate right side
                right_side = formatNumber(right_side);

                // On blur make sure 2 numbers after decimal
                if (blur === "blur") {
                    right_side += "00";
                }

                // Limit decimal to only 2 digits
                right_side = right_side.substring(0, 2);

                // join number by .
                input_val = "₪" + left_side + "." + right_side;

            } else {
                // no decimal entered
                // add commas to number
                // remove all non-digits
                input_val = formatNumber(input_val);
                input_val = "₪" + input_val;

                // final formatting
                if (blur === "blur") {
                    input_val += ".00";
                }
            }

            // send updated string to input
            input.val(input_val);

            // put caret back in the right position
            var updated_len = input_val.length;
            caret_pos = updated_len - original_len + caret_pos;
            input[0].setSelectionRange(caret_pos, caret_pos);
        }

    (function () {
            'use strict'

            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            var forms = document.querySelectorAll('.needs-validation')

            // Loop over them and prevent submission
            Array.prototype.slice.call(forms)
                .forEach(function (form) {
                    form.addEventListener('submit', function (event) {
                        if (!form.checkValidity()) {
                            event.preventDefault()
                            event.stopPropagation()
                        }

                        form.classList.add('was-validated')
                    }, false)
                })
        })()
    function validateForm() {
            var x = document.forms["myForm"]["indicator"].value;
            if (x == "") {
                alert("Name must be filled out");
                return false;
            }
    }
</script>

</body>
</html>