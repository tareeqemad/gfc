<?php
/**
 * Created by PhpStorm.
 * User: telbawab
 * Date: 18/10/2022
 * Time: 12:40 ص
 */
$MODULE_NAME = 'internal_jobs';
$TB_NAME = 'job_ads';
$TB_NAME_REQUEST = 'job_ads_request';
$get_url = base_url("$MODULE_NAME/$TB_NAME/get");
$edit_url = base_url("$MODULE_NAME/$TB_NAME/edit");
$join_in_job_url = base_url("$MODULE_NAME/$TB_NAME_REQUEST/create");;
?>
<style>
    .accordion--custom .accordion-header {
        display: flex; /* make flex element */
        align-items: center; /* aligning child items */
        column-gap: 1rem; /* adding gap between items in row */
        padding-left: 1rem;
    }

    /* small udjustments */
    .accordion--custom .accordion-header .accordion-button {
        padding-left: 0;
        background: none;
    }

    .accordion--custom .accordion-button:not(.collapsed) {
        box-shadow: none;
    }
</style>
<div class="row">
    <div class="col-md-6" id="accordion_list">
        <div class="panel-group1" id="accordion11" role="tablist">
            <?php foreach ($page_rows as $row) { ?>
                <div class="card overflow-hidden mb-2 border-0">
                    <div class="accordion-header" id="heading6">
                        <input class="form-check-input checkboxes" type="checkbox" id="checkboxNoLabel"
                               value="<?= $row['SER'] ?>"> &nbsp; &nbsp; &nbsp;
                        <a class="accordion-toggle panel-heading1 collapsed" data-bs-toggle="collapse"
                           data-bs-parent="#accordion11" href="#collapseFour_<?= $row['SER'] ?>" aria-expanded="false"
                            <?php if (HaveAccess($edit_url)) { ?>
                                ondblclick="javascript:get_to_link('<?= base_url("internal_jobs/job_ads/get/{$row['SER']}") ?>')"
                            <?php } ?>
                        >
                            <?= $row['REC_SORT'] ?>-<?= $row['ADS_NAME'] ?>
                            &nbsp; &nbsp;
                            تاريخ نهاية التقديم
                            <?= $row['DEADLINE'] ?>
                        </a>
                    </div>





                    <div id="collapseFour_<?= $row['SER'] ?>" class="panel-collapse collapse" role="tabpanel" aria-expanded="false">
                        <div class="panel-body">
                            <?= $row['ADS_DESCRIPTION'] ?>
                            <?= modules::run("$MODULE_NAME/$TB_NAME/indexUpload", $row['ATTACHMENT_SER'], 'JOB_ADS_TB'); ?>
                        </div>
                    </div>
                </div>

            <?php } ?>
        </div>
    </div>
    <div class="col-md-6">
        <?php if (HaveAccess($join_in_job_url)) { ?>
            <div class="flex-shrink-0">
                <!--<button type="button" class="btn btn-success" id="btn_join"
                        onclick="CreateRequest(<?= $this->user->emp_no ?>)">
                    <i class="icon ion-ios-rocket"></i>
                    تقديم الطلب
                </button>-->

            </div>
            <hr>
        <?php } ?>
        <div class="row">
            <div class="card-border">
                <div class="card-body">
                    <p class="text-danger text-center">
                        <i class="fa fa-bullhorn"></i>
                        إعلان داخلي لوظيفة مدير في "القطاع المالي والإداري "ضمن المرحلة الثانية
                    </p>
                    <p class="text-primary">
                        تعلن شركة توزيع كهرباء محافظات غزة لموظفيها عن حاجتها لشغل وظيفة (مدير) وذلك للعمل مديراً لإحدى
                        شواغر القطاع المالي والاداري التالية:
                    </p>
                    <p class="text-primary">
                        1. دائرة الحسابات – إدارة الشؤون المالية.
                    </p>
                    <p class="text-primary">
                        2. دائرة الشؤون المالية والإدارية – فروع.
                    </p>
                    <p class="text-primary">
                        3. دائرة الخدمات العامة – إدارة اللوازم والخدمات العامة.
                    </p>
                    <p class="text-primary">
                        4. دائرة مكتب مدير عام الشركة – الإدارة العامة.
                    </p>
                    <p class="text-danger">
                        ملاحظة:
                    </p>
                    <p class="text-primary">
                        1. يطلب من كافة المتقدمين تقديم ملف انجاز جديد، حيث تُعتبر الوظيفة من اعلانات مرحلة جديدة، على
                        أن يغطي ملف الانجاز آخر ثلاث سنوات، يتم تجهيزه "كعرض تقديمي" (PowerPoint) لعرضه أمام اللجنة،
                        وتقديم خطة تطويرية للدائرة المتقدم لها تتناسب وتنسجم مع الخطة التشغيلية والاستراتيجية للشركة.
                    </p>

                    <p class="text-primary">
                        2. يحق لجميع الموظفين التقدم للتسابق على وظيفة واحدة بحد أقصى من وظائف المرحلة الثانية في حال
                        توافرت الشروط المطلوبة بغض النظر عن مشاركته في المرحلة الأولى.
                    </p>

                    <div class="text-center text-primary text-decoration-underline">
                        إدارة الموارد البشرية
                    </div>
                </div>


                <!--  <i class="fas fa-bullhorn"></i>
                  <h4 class="text-center text-danger text-decoration-underline">البنود التوضيحية للتقدم الى
                      الوظيفة</h4>
                  <ul>
                      <li><h5>يتم تقديم الطلب عبر النظام الإداري الموحد خلال الفترة المسموحة والمحددة في الإعلان، حيت
                              لن يتم قبول أي طلب بخلاف ذلك.</h5></li>
                      <li><h5>يحق للموظف التقدم لأي وظيفة من الوظائف المعلن عنها طالما ان الشروط متوفرة لديه، على أن
                              يكون التقديم لوظيفتين بحد أقصى.</h5></li>
                      <li><h5>سيتم استقبال الطلبات لجميع الوظائف في نفس الوقت بالتزامن، بينما اجراء المسابقات الخاصة
                              بكل وظيفة سيتم وفقاً للجدول الزمني المرفق.</h5></li>
                      <li><h5>يلتزم المتقدم بإرفاق سيرة ذاتية عند التقدم للوظيفة وارفاقها عبر النظام الإداري.</h5>
                      </li>
                      <li><h5>يلتزم المتقدم باصطحاب خطته التطويرية الخاصة بالوظيفة المرشح لها، عند مقابلة لجنة
                              المسابقات، على ان تكون شاملة الإنجازات المحققة خلال التكليف.</h5></li>
                      <li><h5>يشترط عند التقدم ألا يقل متوسط تقييم الأداء لآخر ثلاث سنوات عن تقدير جيد جداً.</h5></li>
                      <li><h5>التنافس متاح لجميع العاملين ممن تطبق عليهم الشروط، ويراعى أن تتناسب الخبرة المطلوبة مع
                              طبيعة الموقع الوظيفي المتنافس عليه.</h5></li>
                      <li><h5>يتم منح الموظف المكلف درجة واحدة عن كل سنة قضاها في التكليف وبحد أقصى (خمس درجات).</h5>
                      </li>
                      <li><h5>يشترط للفوز بالوظيفة حصول المتسابق على معدل (75%) كحد أدنى.</h5></li>
                      <li><h5>يخضع الفائز في المسابقة لفترة اختبار تجريبية لمدة (6) شهور ويتقاضى خلالها الفرق المالي
                              بين وضعه الحالي ووضعه حسب الدرجة والامتيازات المالية المترتبة عليها.</h5></li>
                  </ul>
                  <div class="text-center text-primary text-decoration-underline">
                      إدارة الموارد البشرية
                  </div> -->

            </div>
        </div>
    </div>
</div>

<!-- Extra-large  modal -->
<div class="modal fade" id="Extra">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content modal-content-demo">
            <div class="modal-header">
                <h6 class="modal-title">
                    الجدول الزمنـي لإجراء المسابقات على الشواغر الوظيفية
                </h6>
                <button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span
                            aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="page_tb">
                            <thead class="table-light">
                            <tr>
                                <th>م</th>
                                <th>المسمى الوظيفي</th>
                                <th>شهر
                                    أكتوبر
                                    2022
                                </th>
                                <th>شهر
                                    نوفمبر
                                    2022
                                </th>
                                <th>شهر
                                    ديسمبر
                                    2022
                                </th>
                                <th>شهر يناير
                                    2023
                                </th>
                                <th>شهر
                                    فبراير
                                    2023
                                </th>
                                <th>
                                    شهر
                                    مارس
                                    2023

                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>1</td>
                                <td>دائرة العناية بالزبائن ( فروع)</td>
                                <td class="text-center text-success"><i class="fa fa-check"></i></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>دائرة التفتيش ومراقبة الاستهلاك ( فروع)</td>
                                <td class="text-center text-success"><i class="fa fa-check"></i></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td> الدائرة الفنية ( فروع)</td>
                                <td class="text-center text-success"><i class="fa fa-check"></i></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>4</td>
                                <td> دائرة الشؤون المالية والادارية (فروع)</td>
                                <td></td>
                                <td class="text-center text-success"><i class="fa fa-check"></i></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>5</td>
                                <td> دائرة شؤون الموظفين</td>
                                <td></td>
                                <td class="text-center text-success"><i class="fa fa-check"></i></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>

                            <tr>
                                <td>6</td>
                                <td> دائرة الرقابة التجارية</td>
                                <td></td>
                                <td class="text-center text-success"><i class="fa fa-check"></i></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>7</td>
                                <td>ادارة اللوازم والخدمات</td>
                                <td></td>
                                <td></td>
                                <td class="text-center text-success"><i class="fa fa-check"></i></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>8</td>
                                <td>مدير فرع</td>
                                <td></td>
                                <td></td>
                                <td class="text-center text-success"><i class="fa fa-check"></i></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>9</td>
                                <td>وحدة العلاقات العامة والاعلام</td>
                                <td></td>
                                <td></td>
                                <td class="text-center text-success"><i class="fa fa-check"></i></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>10</td>
                                <td>دائرة الخزينة</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td class="text-center text-success"><i class="fa fa-check"></i></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>11</td>
                                <td>دائرة الفوترة والتحصيل</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td class="text-center text-success"><i class="fa fa-check"></i></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>12</td>
                                <td>وحدة التفتيش والاستهلاك</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td class="text-center text-success"><i class="fa fa-check"></i></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>13</td>
                                <td>دائرة الخدمات</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td class="text-center text-success"><i class="fa fa-check"></i></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>14</td>
                                <td> دائرة الحسابات العامة</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td class="text-center text-success"><i class="fa fa-check"></i></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>15</td>
                                <td>دائرة التخطيط واعداد المشاريع</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td class="text-center text-success"><i class="fa fa-check"></i></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>16</td>
                                <td>دائرة البرمجة وقواعد البيانات</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td class="text-center text-success"><i class="fa fa-check"></i></td>
                            </tr>
                            <tr>
                                <td>17</td>
                                <td>دائرة التشغيل والدعم الفني</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td class="text-center text-success"><i class="fa fa-check"></i></td>
                            </tr>
                            <tr>
                                <td>18</td>
                                <td> دائرة مكتب رئاسة هيئة المديرين</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td class="text-center text-success"><i class="fa fa-check"></i></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button class="btn ripple btn-secondary" data-bs-dismiss="modal" type="button">اغلاق</button>
            </div>
        </div>
    </div>
</div>
<!--End Extra-large modal -->
<?php echo $this->pagination->create_links(); ?>
<script>
    if (typeof initFunctions == 'function') {
        initFunctions();
    }
    if (typeof ajax_pager == 'function') {
        ajax_pager();
    }
</script>



