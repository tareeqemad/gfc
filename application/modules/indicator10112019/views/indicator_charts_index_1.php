<?php
/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 06/06/18
 * Time: 01:10 م
 */
$MODULE_NAME= 'indicator';
$TB_NAME= 'indicate_charts';
$post_url= base_url("$MODULE_NAME/$TB_NAME/get_page");

echo AntiForgeryToken();
?>
<style>
    .center {
        margin: auto;
        width: 50%;

    }
</style>

    <div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>

        <ul>
            <li><a  onclick="<?= $help ?>" href="javascript:;" class="help"><i class="icon icon-question-circle"></i></a> </li>

        </ul>

    </div>



        <div class="form-body">
            <div class="modal-body inline_form">
            </div>

            <form class="form-vertical" id="<?=$TB_NAME?>_form" method="post" action="<?=$post_url?>" role="form" novalidate="novalidate">
                <div class="modal-body inline_form hidden">

                    <div class="form-group col-sm-1 hidden">
                        <label class="col-sm-1 control-label">نوع التقرير</label>
                        <div>
                            <select name="report_type" data-val="true"  data-val-required="حقل مطلوب"  id="dp_report_type" class="form-control">
                                <option></option>
                                <?php foreach($branches as $row) :?>
                                    <option value="<?= $row['NO'] ?>" ><?= $row['NAME'] ?></option>


                                <?php endforeach; ?>

                            </select>

                            <span class="field-validation-valid" data-valmsg-for="report_type" data-valmsg-replace="true"></span>




                        </div>
                    </div>

                    <div class="form-group col-sm-1">
                        <label class="col-sm-1 control-label">دورية التقرير</label>
                        <div>
                            <select name="report_cycle" data-val="true"  data-val-required="حقل مطلوب"  id="dp_report_cycle" class="form-control">
                                <option></option>
                                <?php foreach($branches as $row) :?>
                                    <option value="<?= $row['NO'] ?>" ><?= $row['NAME'] ?></option>


                                <?php endforeach; ?>

                            </select>

                            <span class="field-validation-valid" data-valmsg-for="report_cycle" data-valmsg-replace="true"></span>




                        </div>
                    </div>
                    <div class="form-group col-sm-2">
                        <label class="col-sm-2 control-label">الفرع</label>
                        <div>

                            <select name="branch_follow_id" data-val="true"  data-val-required="حقل مطلوب"  id="dp_branch_follow_id" class="form-control">
                                <option></option>
                                <?php foreach($branches_follow as $row) :?>
                                    <option value="<?= $row['NO'] ?>" <?PHP if ($row['NO']==$branch_follow_id){ echo " selected"; } ?> ><?= $row['NAME'] ?></option>


                                <?php endforeach; ?>

                            </select>
                            <span class="field-validation-valid" data-valmsg-for="branch_follow_id" data-valmsg-replace="true"></span>


                        </div>
                    </div>

                    <div class="form-group col-sm-2">
                        <label class="col-sm-2 control-label">الادارة</label>
                        <div>

                            <select name="manage_follow_id" data-val="true"  data-val-required="حقل مطلوب"  id="dp_manage_follow_id" class="form-control">
                                <option></option>
                                <?php foreach($b_follow as $row) :?>
                                    <option value="<?= $row['ST_ID'] ?>" <?PHP if ($row['ST_ID']==$manage_select_follow){ echo " selected"; } ?>><?= $row['ST_NAME'] ?></option>


                                <?php endforeach; ?>


                            </select>
                            <span class="field-validation-valid" data-valmsg-for="manage_follow_id" data-valmsg-replace="true"></span>


                        </div>
                    </div>

                    <div class="form-group col-sm-2">
                        <label class="col-sm-2 control-label">الدائرة</label>
                        <div>

                            <select name="cycle_follow_id" id="dp_cycle_follow_id" class="form-control">

                                <option></option>
                                <?php foreach($cycle_follow as $row) :?>
                                    <option value="<?= $row['ST_ID'] ?>" <?PHP if ($row['ST_ID']==$cycle_select_follow){ echo " selected"; } ?>><?= $row['ST_NAME'] ?></option>


                                <?php endforeach; ?>
                            </select>

                        </div>
                    </div>
                    <div class="form-group col-sm-1">
                        <label class="col-sm-1 control-label">المؤشر</label>
                        <div>
                            <select name="report_cycle" data-val="true"  data-val-required="حقل مطلوب"  id="dp_report_cycle" class="form-control">
                                <option></option>
                                <?php foreach($branches as $row) :?>
                                    <option value="<?= $row['NO'] ?>" ><?= $row['NAME'] ?></option>


                                <?php endforeach; ?>

                            </select>

                            <span class="field-validation-valid" data-valmsg-for="report_cycle" data-valmsg-replace="true"></span>




                        </div>
                    </div>

                    <div class="form-group col-sm-1">
                        <label class="col-sm-1 control-label">القطاع</label>
                        <div>
                            <select name="report_cycle" data-val="true"  data-val-required="حقل مطلوب"  id="dp_report_cycle" class="form-control">
                                <option></option>
                                <?php foreach($branches as $row) :?>
                                    <option value="<?= $row['NO'] ?>" ><?= $row['NAME'] ?></option>


                                <?php endforeach; ?>

                            </select>

                            <span class="field-validation-valid" data-valmsg-for="report_cycle" data-valmsg-replace="true"></span>




                        </div>
                    </div>

                </div>

                <div class="modal-footer hidden">

                    <button type="button" onclick="javascript:search();" class="btn btn-success"> إستعلام</button>

                    <button type="button" onclick="javascript:clear_form();"  class="btn btn-default"> تفريغ الحقول</button>


                </div>
                <div id="msg_container"></div>
                <div class="col-md-12">
                    <div class="portlet box blue">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="icon icon-bar-chart"></i>البيانات الأساسية
                            </div>
                            <div class="tools">
                                <a href="#portlet-config" data-toggle="modal" class="config">
                                </a>
                                <a href="javascript:;" class="reload">
                                </a>
                            </div>
                        </div>
                        <div class="portlet-body">
<!--
                            <i class="icon icon-square" style="color: red;"></i>اجمالي عدد المشتركين
                            <br/>
                            <i class="icon icon-square"  style="color: blue;"></i>قيمة الفاتورة الشهري
                            ة الكلية
-->
<div class="row">
    <div class="col-md-6">
        <div class="portlet box yellow">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon icon-pie-chart"></i>المشتركين ونوع الاشتراك
                </div>
                <div class="tools">
                    <a href="#portlet-config" data-toggle="modal" class="config">
                    </a>
                    <a href="javascript:;" class="reload">
                    </a>
                </div>
            </div>

            <div class="portlet-body">


                <div class="row">



                    <div class="col-md-12">
                        <div class="portlet box red">
                            <div class="portlet-title">
                                <div class="caption">
                                    <i class="icon icon-pie-chart"></i>اجمالي عدد المشتركين.
                                </div>
                                <div class="tools">
                                    <a href="#portlet-config" data-toggle="modal" class="config">
                                    </a>
                                    <a href="javascript:;" class="reload">
                                    </a>
                                </div>
                            </div>
                            <div class="portlet-body">

                                <!--
<i class="icon icon-square" style="color: red;"></i>اجمالي عدد المشتركين
                                <br/>
                                <i class="icon icon-square"  style="color: blue;"></i>قيمة الفاتورة الشهرية الكلية
-->
                                <div id="container1" class="chart">


                                </div>
                            </div>


                        </div>
                    </div>



                </div>
            </div>


        </div>
    </div>
    <div class="col-md-6">
        <div class="portlet box yellow">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon icon-pie-chart"></i>قيمة الفاتورة الشهرية
                </div>
                <div class="tools">
                    <a href="javascript:;" class="collapse">
                    </a>
                    <a href="#portlet-config" data-toggle="modal" class="config">
                    </a>
                    <a href="javascript:;" class="reload">
                    </a>
                    <a href="javascript:;" class="remove">
                    </a>
                </div>
            </div>

            <div class="portlet-body">


                <div class="row">



                    <div class="col-md-12">
                        <div class="portlet box red">
                            <div class="portlet-title">
                                <div class="caption">
                                    <i class="icon icon-pie-chart"></i>قيمة الفاتورة الشهرية الكلية
                                </div>
                                <div class="tools">
                                    <a href="javascript:;" class="collapse">
                                    </a>
                                    <a href="#portlet-config" data-toggle="modal" class="config">
                                    </a>
                                    <a href="javascript:;" class="reload">
                                    </a>
                                    <a href="javascript:;" class="remove">
                                    </a>
                                </div>
                            </div>
                            <div class="portlet-body">

                                <!--
<i class="icon icon-square" style="color: red;"></i>اجمالي عدد المشتركين
                                <br/>
                                <i class="icon icon-square"  style="color: blue;"></i>قيمة الفاتورة الشهرية الكلية
-->
                                <div id="container4" class="chart"></div>


                                </div>
                            </div>


                        </div>
                    </div>



                </div>
            </div>


        </div>
    </div>


</div>
                                                 <div class="row">


                          <div class="col-md-6">
                              <div class="portlet box blue">
                                  <div class="portlet-title">
                                      <div class="caption">
                                          <i class="icon icon-pie-chart"></i>اجمالي التحصيل والايراد
                                      </div>
                                      <div class="tools">
                                          <a href="javascript:;" class="collapse">
                                          </a>
                                          <a href="#portlet-config" data-toggle="modal" class="config">
                                          </a>
                                          <a href="javascript:;" class="reload">
                                          </a>
                                          <a href="javascript:;" class="remove">
                                          </a>
                                      </div>
                                  </div>

                                  <div class="portlet-body">
<!--
                                      <i class="icon icon-square" style="color: burlywood;"></i>اجمالي تحصيل المقرات
                                      <br/>
                                      <i class="icon icon-square"  style="color: blueviolet;"></i>اجمالي تحصيل  مقر الشمال
                                      <br/>
                                      <i class="icon icon-square"  style="color: brown;"></i>اجمالي تحصيل  مقر غزة
                                      <br/>
                                      <i class="icon icon-square"  style="color: #b0383c;"></i>اجمالي تحصيل  مقر الوسطى
                                      <br/>
                                      <i class="icon icon-square"  style="color: #b07cc6;"></i>اجمالي تحصيل  مقر خانيونس
                                      <br/>
                                      <i class="icon icon-square"  style="color: #b41616;"></i>اجمالي تحصيل  مقر رفح
                                       -->
                                      <div class="row">

                                          <div class="col-md-6">
                                              <div class="portlet box red">
                                                  <div class="portlet-title">
                                                      <div class="caption">
                                                          <i class="icon icon-pie-chart"></i>نسبة تحصيل المقر من الفاتورة
                                                      </div>
                                                      <div class="tools">
                                                          <a href="javascript:;" class="collapse">
                                                          </a>
                                                          <a href="#portlet-config" data-toggle="modal" class="config">
                                                          </a>
                                                          <a href="javascript:;" class="reload">
                                                          </a>
                                                          <a href="javascript:;" class="remove">
                                                          </a>
                                                      </div>

                                                  </div>
                                                  <div class="portlet-body">

                                                      <!--
          <i class="icon icon-square" style="color: red;"></i>اجمالي عدد المشتركين
          <br/>
          <i class="icon icon-square"  style="color: blue;"></i>قيمة الفاتورة الشهرية الكلية
          -->

                                                      <div id="pie_chart_1" class="chart">



                                                      </div>

                                                  </div>

                                              </div>
                                          </div>
                                          <div class="col-md-6">
                                              <div class="portlet box red">
                                                  <div class="portlet-title">
                                                      <div class="caption">
                                                          <i class="icon icon-pie-chart"></i>نسبة تحصيل المقر من المستهدف
                                                      </div>
                                                      <div class="tools">
                                                          <a href="javascript:;" class="collapse">
                                                          </a>
                                                          <a href="#portlet-config" data-toggle="modal" class="config">
                                                          </a>
                                                          <a href="javascript:;" class="reload">
                                                          </a>
                                                          <a href="javascript:;" class="remove">
                                                          </a>
                                                      </div>
                                                  </div>
                                                  <div class="portlet-body">

                                                      <!--
          <i class="icon icon-square" style="color: red;"></i>اجمالي عدد المشتركين
                                                      <br/>
                                                      <i class="icon icon-square"  style="color: blue;"></i>قيمة الفاتورة الشهرية الكلية
          -->
                                                      <div id="pie_persent_goal_chart_2" class="chart">


                                                      </div>
                                                  </div>


                                                          </div>
                                                      </div>


                                                  </div>
                                              </div>


                                          </div>
                                      </div>
                                                     <div class="col-md-6">
                                                         <div class="portlet box green">
                                                             <div class="portlet-title">
                                                                 <div class="caption">
                                                                     <i class="icon icon-pie-chart"></i>النشاط التشغيلي التجاري
                                                                 </div>
                                                                 <div class="tools">
                                                                     <a href="javascript:;" class="collapse">
                                                                     </a>
                                                                     <a href="#portlet-config" data-toggle="modal" class="config">
                                                                     </a>
                                                                     <a href="javascript:;" class="reload">
                                                                     </a>
                                                                     <a href="javascript:;" class="remove">
                                                                     </a>
                                                                 </div>
                                                             </div>

                                                             <div class="portlet-body">
                                                                 <div class="row hidden">
                                                                     <div class="col-md-4">
                                                                         <i class="icon icon-square" style="color: burlywood;"></i> الاشتراكات غير الملتزمة بآلية سداد في الات
                                                                         <br/>
                                                                         <i class="icon icon-square"  style="color: blueviolet;"></i> الاشتراكات غير الملتزمة بآلية سداد    الشمال
                                                                         <br/>
                                                                         <i class="icon icon-square"  style="color: brown;"></i> الاشتراكات غير الملتزمة بآلية سداد    غزة
                                                                         <br/>
                                                                         <i class="icon icon-square"  style="color: #b0383c;"></i> الاشتراكات غير الملتزمة بآلية سداد    الوسطى
                                                                         <br/>
                                                                         <i class="icon icon-square"  style="color: #b07cc6;"></i> الاشتراكات غير الملتزمة بآلية سداد    خانيونس
                                                                         <br/>
                                                                         <i class="icon icon-square"  style="color: #b41616;"></i> الاشتراكات غير الملتزمة بآلية سداد   رفح


                                                                     </div>
                                                                     <div class="col-md-4">
                                                                         <i class="icon icon-square" style="color: burlywood;"></i> الاشتراكات مسبق دفع الغير ملتزمة المعالجة في الات
                                                                         <br/>
                                                                         <i class="icon icon-square"  style="color: blueviolet;"></i> الاشتراكات مسبق دفع الغير ملتزمة المعالجة    الشمال
                                                                         <br/>
                                                                         <i class="icon icon-square"  style="color: brown;"></i> الاشتراكات مسبق دفع الغير ملتزمة المعالجة   غزة
                                                                         <br/>
                                                                         <i class="icon icon-square"  style="color: #b0383c;"></i> الاشتراكات مسبق دفع الغير ملتزمة المعالجة    الوسطى
                                                                         <br/>
                                                                         <i class="icon icon-square"  style="color: #b07cc6;"></i> الاشتراكات مسبق دفع الغير ملتزمة المعالجة   خانيونس
                                                                         <br/>
                                                                         <i class="icon icon-square"  style="color: #b41616;"></i> الاشتراكات مسبق دفع الغير ملتزمة المعالجة  رفح


                                                                     </div>
                                                                     <div class="col-md-4">
                                                                         <i class="icon icon-square" style="color: burlywood;"></i> نسبة اشتراكات السداد الآلي الغير مسددة المعالجة في الات
                                                                         <br/>
                                                                         <i class="icon icon-square"  style="color: blueviolet;"></i> نسبة اشتراكات السداد الآلي الغير مسددة المعالجة    الشمال
                                                                         <br/>
                                                                         <i class="icon icon-square"  style="color: brown;"></i> نسبة اشتراكات السداد الآلي الغير مسددة المعالجة   غزة
                                                                         <br/>
                                                                         <i class="icon icon-square"  style="color: #b0383c;"></i> نسبة اشتراكات السداد الآلي الغير مسددة المعالجة    الوسطى
                                                                         <br/>
                                                                         <i class="icon icon-square"  style="color: #b07cc6;"></i> نسبة اشتراكات السداد الآلي الغير مسددة المعالجة   خانيونس
                                                                         <br/>
                                                                         <i class="icon icon-square"  style="color: #b41616;"></i> نسبة اشتراكات السداد الآلي الغير مسددة المعالجة  رفح


                                                                     </div>



                                                                 </div>


                                                                 <div class="row">
<!--
                                                                     <div class="col-md-6">
                                                                         <div class="portlet box purple">
                                                                             <div class="portlet-title">
                                                                                 <div class="caption">
                                                                                     <i class="icon icon-pie-chart"></i>الاشتراكات غير الملتزمة بآلية سداد
                                                                                 </div>
                                                                                 <div class="tools">
                                                                                     <a href="#portlet-config" data-toggle="modal" class="config">
                                                                                     </a>
                                                                                     <a href="javascript:;" class="reload">
                                                                                     </a>
                                                                                 </div>
                                                                             </div>
                                                                             <div class="portlet-body">

                                                                                 <!--
                                     <i class="icon icon-square" style="color: red;"></i>اجمالي عدد المشتركين
                                     <br/>
                                     <i class="icon icon-square"  style="color: blue;"></i>قيمة الفاتورة الشهرية الكلية
                                     -->
                                                                     <!--
                                                                                 <div id="pie_chart_6" class="chart">



                                                                                 </div>

                                                                             </div>

                                                                         </div>
                                                                     </div>
                                                                     <div class="col-md-6">
                                                                         <div class="portlet box purple">
                                                                             <div class="portlet-title">
                                                                                 <div class="caption">
                                                                                     <i class="icon icon-pie-chart"></i>معالجة مسبق الدفع الغير ملتزم
                                                                                 </div>
                                                                                 <div class="tools">
                                                                                     <a href="#portlet-config" data-toggle="modal" class="config">
                                                                                     </a>
                                                                                     <a href="javascript:;" class="reload">
                                                                                     </a>
                                                                                 </div>
                                                                             </div>
                                                                             <div class="portlet-body">

                                                                                 <!--
                                     <i class="icon icon-square" style="color: red;"></i>اجمالي عدد المشتركين
                                                                                 <br/>
                                                                                 <i class="icon icon-square"  style="color: blue;"></i>قيمة الفاتورة الشهرية الكلية
                                     -->
                                                                           <!--      <div id="pie_chart_88" class="chart">


                                                                                 </div>
                                                                             </div>


                                                                         </div>
                                                                     </div>
                                                         -->
                                                                     <!--

                                                                     <div class="col-md-12">
                                                                         <div class="portlet box purple">
                                                                             <div class="portlet-title">
                                                                                 <div class="caption">
                                                                                     <i class="icon icon-pie-chart"></i>امعالجة اشتراكات السداد الآلي
                                                                                 </div>
                                                                                 <div class="tools">
                                                                                     <a href="#portlet-config" data-toggle="modal" class="config">
                                                                                     </a>
                                                                                     <a href="javascript:;" class="reload">
                                                                                     </a>
                                                                                 </div>
                                                                             </div>
                                                                             <div class="portlet-body">

                                                                                 <!--
                                     <i class="icon icon-square" style="color: red;"></i>اجمالي عدد المشتركين
                                     <br/>
                                     <i class="icon icon-square"  style="color: blue;"></i>قيمة الفاتورة الشهرية الكلية
                                     -->
                                                                            <!--
                                                                             <div id="pie_chart_8" class="chart">
                                                                                    <div id="container" style="height: 1000%"></div>



                                                                                 </div>

                                                                             </div> -->

                                                                         </div>
                                                                     </div>
                                                                     <div class="col-md-12">
                                                                         <div class="portlet box purple">
                                                                             <div class="portlet-title">
                                                                                 <div class="caption">
                                                                                     <i class="icon icon-bar-chart"></i>معالجة الاشتركات غير ملتزمة بالسداد
                                                                                 </div>
                                                                                 <div class="tools">
                                                                                     <a href="#portlet-config" data-toggle="modal" class="config">
                                                                                     </a>
                                                                                     <a href="javascript:;" class="reload">
                                                                                     </a>
                                                                                 </div>
                                                                             </div>
                                                                             <div class="portlet-body">

                                                                                 <!--
                                     <i class="icon icon-square" style="color: red;"></i>اجمالي عدد المشتركين
                                     <br/>
                                     <i class="icon icon-square"  style="color: blue;"></i>قيمة الفاتورة الشهرية الكلية
                                     -->

                                                                                     <div id="container" style="height: 600%"></div>



                                                                                 </div>

                                                                             </div>

                                                                         </div>
                                                                     </div>
                                                                 <div class="col-md-12">
                                                                     <div class="portlet box purple">
                                                                         <div class="portlet-title">
                                                                             <div class="caption">
                                                                                 <i class="icon icon-pie-chart"></i>امعالجة اشتراكات السداد الآلي
                                                                             </div>
                                                                             <div class="tools">
                                                                                 <a href="#portlet-config" data-toggle="modal" class="config">
                                                                                 </a>
                                                                                 <a href="javascript:;" class="reload">
                                                                                 </a>
                                                                             </div>
                                                                         </div>
                                                                         <div class="portlet-body">

                                                                             <!--
                                 <i class="icon icon-square" style="color: red;"></i>اجمالي عدد المشتركين
                                 <br/>
                                 <i class="icon icon-square"  style="color: blue;"></i>قيمة الفاتورة الشهرية الكلية
                                 -->

                                                                             <div id="container3" style="height: 600%"></div>



                                                                         </div>

                                                                     </div>

                                                                 </div>
                                                             </div>
                                                                 </div>
                                                             </div>


                                                         </div>
                                                     </div>
                                      </div>
                                  </div>
                              </div>

                          </div>
                      </div>

                        </div>


                    </div>
                </div>


            </form>



        </div>

    </div>
<?php


$script =<<<SCRIPT
   <script>
  var data = [];
            var series = Math.floor(Math.random() * 10) + 1;
            series = series < 5 ? 5 : series;

            for (var i = 0; i < series; i++) {
                data[i] = {
                    label: "Series" + (i + 1),
                    data: Math.floor(Math.random() * 100) + 1
                };
            }
            var data1 = [];
            var series1 = Math.floor(Math.random() * 20) + 1;
            series1 = series1 < 6 ? 6 : series1;

            for (var i = 0; i < series1; i++) {
                data1[i] = {
                    label: "Series" + (i + 1),
                    data: Math.floor(Math.random() * 100)/100 + 1
                };
            }
 if ($('#pie_chart_1').size() !== 0) {
                $.plot($("#pie_chart_1"), data, {
                    series: {
                        pie: { //innerRadius: 0.5,
                            show: true,
                            radius: 1,
                            label: {
                                show: true,
                                radius: 1,
                                formatter: function(label, series) {
                                    return '<div style="font-size:8pt;text-align:center;padding:2px;color:white;">' + label + '<br/>' + Math.round(series.percent) + '%</div>';
                                },
                                background: {
                                    opacity: 0.8,
                                threshold: 0.1
                                }
                            }
                        }
                    },
                    grid: {
                        hoverable: true,
                        clickable: true
                    },
                    legend: {
                        show: true
                    }
                });
            }

if ($('#pie_persent_goal_chart_2').size() !== 0) {
                $.plot($("#pie_persent_goal_chart_2"), data1, {
                    series: {
                        pie: {
                        //innerRadius: 0.5,
                            show: true,
                            radius: 1,
                            label: {
                                show: true,
                                radius: 1,
                                formatter: function(label, series1) {
                                    return '<div style="font-size:8pt;text-align:center;padding:2px;color:white;">' + label + '<br/>' + Math.round(series1.percent) + '%</div>';
                                },
                                background: {
                                    opacity: 0.8,
                                threshold: 0.1
                                }
                            }
                        }
                    },
                    grid: {
                        hoverable: true,
                        clickable: true
                    },
                    legend: {
                        show: true
                    }
                });
            }



     // GRAPH 6
            if ($('#pie_chart_6').size() !== 0) {
                $.plot($("#pie_chart_6"), data, {
                    series: {
                        pie: {
                            show: true,
                            radius: 1,
                            label: {
                                show: true,
                                radius: 2 / 3,
                                formatter: function(label, series) {
                                    return '<div style="font-size:8pt;text-align:center;padding:2px;color:white;">' + label + '<br/>' + Math.round(series.percent) + '%</div>';
                                },
                                threshold: 0.1
                            }
                        }
                    },
                    legend: {
                        show: false
                    }
                    ,
                    grid: {
                        hoverable: true,
                        clickable: true
                    }
                });
            }

            // GRAPH 7
            if ($('#pie_chart_7').size() !== 0) {
                $.plot($("#pie_chart_6"), data, {
                    series: {
                        pie: {
                            show: true,
                            radius: 1,
                            label: {
                                show: true,
                                radius: 2 / 3,
                                formatter: function(label, series) {
                                    return '<div style="font-size:8pt;text-align:center;padding:2px;color:white;">' + label + '<br/>' + Math.round(series.percent) + '%</div>';
                                },
                                threshold: 0.1
                            }
                        }
                    },
                    legend: {
                        show: false
                    }
                    ,
                    grid: {
                        hoverable: true,
                        clickable: true
                    }
                });
            }
             // GRAPH 8
            if ($('#pie_chart_8').size() !== 0) {
                $.plot($("#pie_chart_8"), data, {
                    series: {
                        pie: {
                            show: true,
                            radius: 300,
                            label: {
                                show: true,
                                formatter: function(label, series) {
                                    return '<div style="font-size:8pt;text-align:center;padding:2px;color:white;">' + label + '<br/>' + Math.round(series.percent) + '%</div>';
                                },
                                threshold: 0.1
                            }
                        }
                    },
                    legend: {
                        show: false
                    },
                    grid: {
                        hoverable: true,
                        clickable: true
                    }
                });
            }
             // GRAPH 7
            if ($('#pie_chart_88').size() !== 0) {
                $.plot($("#pie_chart_88"), data, {
                    series: {
                        pie: {
                            show: true,
                            radius: 1,
                            label: {
                                show: true,
                                radius: 2 / 3,
                                formatter: function(label, series) {
                                    return '<div style="font-size:8pt;text-align:center;padding:2px;color:white;">' + label + '<br/>' + Math.round(series.percent) + '%</div>';
                                },
                                threshold: 0.1
                            }
                        }
                    },
                    legend: {
                        show: false
                    }
                    ,
                    grid: {
                        hoverable: true,
                        clickable: true
                    }
                });
            }


           var dom = document.getElementById("container");
var myChart = echarts.init(dom);
var app = {};
option = null;
var posList = [
    'left', 'right', 'top', 'bottom',
    'inside',
    'insideTop', 'insideLeft', 'insideRight', 'insideBottom',
    'insideTopLeft', 'insideTopRight', 'insideBottomLeft', 'insideBottomRight'
];

app.configParameters = {
    rotate: {
        min: -90,
        max: 90
    },
    align: {
        options: {
            left: 'left',
            center: 'center',
            right: 'right'
        }
    },
    verticalAlign: {
        options: {
            top: 'top',
            middle: 'middle',
            bottom: 'bottom'
        }
    },
    position: {
        options: echarts.util.reduce(posList, function (map, pos) {
            map[pos] = pos;
            return map;
        }, {})
    },
    distance: {
        min: 0,
        max: 100
    }
};

app.config = {
    rotate: 90,
    align: 'left',
    verticalAlign: 'middle',
    position: 'insideBottom',
    distance: 15,
    onChange: function () {
        var labelOption = {
            normal: {
                rotate: app.config.rotate,
                align: app.config.align,
                verticalAlign: app.config.verticalAlign,
                position: app.config.position,
                distance: app.config.distance
            }
        };
        myChart.setOption({
            series: [{
                label: labelOption
            }, {
                label: labelOption
            }, {
                label: labelOption
            }, {
                label: labelOption
            }]
        });
    }
};


var labelOption = {
    normal: {
        show: true,
        position: app.config.position,
        distance: app.config.distance,
        align: app.config.align,
        verticalAlign: app.config.verticalAlign,
        rotate: app.config.rotate,
        formatter: '{c}  {name|{a}}',
        fontSize: 16,
        rich: {
            name: {
                textBorderColor: '#fff'
            }
        }
    }
};

option = {
    color: ['#003366', '#006699', '#4cabce', '#e5323e'],
    tooltip: {
        trigger: 'axis',
        axisPointer: {
            type: 'shadow'
        }
    },
    legend: {
       data: ['عدد الاشتراكات مسبق دفع الغير ملتزمة  المعالجة','عدد الاشتراكات غير الملتزمة بآلية سد اد']
    },
    toolbox: {
        show: true,
        orient: 'vertical',
        left: 'right',
        top: 'center',
        feature: {
            mark: {show: true},
            dataView: {show: true, readOnly: false},
            magicType: {show: true, type: ['line', 'bar', 'stack', 'tiled']},
            restore: {show: true},
            saveAsImage: {show: true}
        }
    },
    calculable: true,
    xAxis: [
        {
            type: 'category',
            axisTick: {show: false},
            data: ['الشمال', 'غزة', 'الوسطى', 'خانيونس', 'رفح']
        }
    ],
    yAxis: [
        {
            type: 'value'
        }
    ],
    series: [
        {
            name: 'عدد الاشتراكات مسبق دفع الغير ملتزمة  المعالجة',
            type: 'bar',
            barGap: 0,
            label: labelOption,
            data: [320, 332, 301, 334, 390]
        },
        {
            name: 'عدد الاشتراكات غير الملتزمة بآلية سد اد',
            type: 'bar',
            label: labelOption,
            data: [220, 182, 191, 234, 290]
        }
    ]
};;
if (option && typeof option === "object") {
    myChart.setOption(option, true);
}
//////
var dom1 = document.getElementById("container1");
var myChart2 = echarts.init(dom1);
var app = {};
option = null;
app.title = '折柱混合';

option = {
    tooltip: {
        trigger: 'axis',
        axisPointer: {
            type: 'cross',
            crossStyle: {
                color: '#999'
            }
        }
    },
    toolbox: {
        feature: {
            dataView: {show: true, readOnly: false},
            magicType: {show: true, type: ['line', 'bar']},
            restore: {show: true},
            saveAsImage: {show: true}
        }
    },
    legend: {
        data:['蒸发量','降水量','平均温度']
    },
    xAxis: [
        {
            type: 'category',
            data: ['1月','2月','3月','4月','5月','6月','7月','8月','9月','10月','11月','12月'],
            axisPointer: {
                type: 'shadow'
            }
        }
    ],
    yAxis: [
        {
            type: 'value',
            name: '水量',
            min: 0,
            max: 250,
            interval: 50,
            axisLabel: {
                formatter: '{value} ml'
            }
        },
        {
            type: 'value',
            name: '温度',
            min: 0,
            max: 25,
            interval: 5,
            axisLabel: {
                formatter: '{value} °C'
            }
        }
    ],
    series: [
        {
            name:'蒸发量',
            type:'bar',
            data:[2.0, 4.9, 7.0, 23.2, 25.6, 76.7, 135.6, 162.2, 32.6, 20.0, 6.4, 3.3]
        },
        {
            name:'降水量',
            type:'bar',
            data:[2.6, 5.9, 9.0, 26.4, 28.7, 70.7, 175.6, 182.2, 48.7, 18.8, 6.0, 2.3]
        },
        {
            name:'平均温度',
            type:'line',
            yAxisIndex: 1,
            data:[2.0, 2.2, 3.3, 4.5, 6.3, 10.2, 20.3, 23.4, 23.0, 16.5, 12.0, 6.2]
        }
    ]
};
;
if (option && typeof option === "object") {
    myChart2.setOption(option, true);
}
/////////////
var dom = document.getElementById("container3");
var myChart = echarts.init(dom);
var app = {};
option = null;
option = {
    title : {
        text: 'نسبة اشتراكات السداد الآلي الغير مسددة المعالجة',
        subtext: 'نسبة اشتراكات السداد الآلي الغير مسددة المعالجة',
        x:'center'
    },
    tooltip : {
        trigger: 'item',
        formatter: "{a} <br/>{b} : {c} ({d}%)"
    },
    legend: {
        orient: 'vertical',
        left: 'left',
        data: ['الشمال', 'غزة', 'الوسطى', 'خانيونس', 'رفح']
    },
    series : [
        {
            name: 'نسبة اشتراكات السداد الآلي الغير مسددة المعالجة',
            type: 'pie',
            radius : '55%',
            center: ['50%', '60%'],
            data:[
                {value:335, name:'الشمال'},
                {value:310, name:'غزة'},
                {value:234, name:'الوسطى'},
                {value:135, name:'خانيونس'},
                {value:1548, name:'رفح'}
            ],
            itemStyle: {
                emphasis: {
                    shadowBlur: 10,
                    shadowOffsetX: 0,
                    shadowColor: 'rgba(0, 0, 0, 0.5)'
                }
            }
        }
    ]
};
;
if (option && typeof option === "object") {
    myChart.setOption(option, true);
}

//////////////////
var dom = document.getElementById("container4");
var myChart = echarts.init(dom);
var app = {};
option = null;
option = {
    title: {
        text: 'Step Line'
    },
    tooltip: {
        trigger: 'axis'
    },
    legend: {
        data:['Step Start', 'Step Middle', 'Step End']
    },
    grid: {
        left: '3%',
        right: '4%',
        bottom: '3%',
        containLabel: true
    },
    toolbox: {
        feature: {
            saveAsImage: {}
        }
    },
    xAxis: {
        type: 'category',
        data: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']
    },
    yAxis: {
        type: 'value'
    },
    series: [
        {
            name:'Step Start',
            type:'line',
            step: 'start',
            data:[120, 132, 101, 134, 90, 230, 210]
        },
        {
            name:'Step Middle',
            type:'line',
            step: 'middle',
            data:[220, 282, 201, 234, 290, 430, 410]
        },
        {
            name:'Step End',
            type:'line',
            step: 'end',
            data:[450, 432, 401, 454, 590, 530, 510]
        }
    ]
};
;
if (option && typeof option === "object") {
    myChart.setOption(option, true);
}
//////////////
var dom = document.getElementById("container9");
var myChart = echarts.init(dom);
var app = {};
option = null;
option = {


    title: {
        text: 'Customized Pie',
        left: 'center',
        top: 20,
        textStyle: {
            color: '#ccc'
        }
    },

    tooltip : {
        trigger: 'item',
        formatter: "{a} <br/>{b} : {c} ({d}%)"
    },

    visualMap: {
        show: false,
        min: 80,
        max: 600,
        inRange: {
            colorLightness: [0, 1]
        }
    },
    series : [
        {
            name:'访问来源',
            type:'pie',
            radius : '55%',
            center: ['50%', '50%'],
            data:[
                {value:335, name:'直接访问'},
                {value:310, name:'邮件营销'},
                {value:274, name:'联盟广告'},
                {value:235, name:'视频广告'},
                {value:400, name:'搜索引擎'}
            ].sort(function (a, b) { return a.value - b.value; }),
            roseType: 'radius',
            label: {
                normal: {
                    textStyle: {
                        color: 'rgba(0, 0, 0, 0.3)'
                    }
                }
            },
            labelLine: {
                normal: {
                    lineStyle: {
                        color: 'rgba(255, 255, 255, 0.3)'
                    },
                    smooth: 0.2,
                    length: 10,
                    length2: 20
                }
            },
            itemStyle: {
                normal: {
                    color: '#c23531',
                    shadowBlur: 200,
                    shadowColor: 'rgba(0, 0, 0, 0.5)'
                }
            },

            animationType: 'scale',
            animationEasing: 'elasticOut',
            animationDelay: function (idx) {
                return Math.random() * 200;
            }
        }
    ]
};;
if (option && typeof option === "object") {
    myChart.setOption(option, true);
}
///////
var dom = document.getElementById("container10");
var myChart = echarts.init(dom);
var app = {};
option = null;
option = {


    title: {
        text: 'Customized Pie',
        left: 'center',
        top: 20,
        textStyle: {
            color: '#ccc'
        }
    },

    tooltip : {
        trigger: 'item',
        formatter: "{a} <br/>{b} : {c} ({d}%)"
    },

    visualMap: {
        show: false,
        min: 80,
        max: 600,
        inRange: {
            colorLightness: [0, 1]
        }
    },
    series : [
        {
            name:'访问来源',
            type:'pie',
            radius : '55%',
            center: ['50%', '50%'],
            data:[
                {value:335, name:'直接访问'},
                {value:310, name:'邮件营销'},
                {value:274, name:'联盟广告'},
                {value:235, name:'视频广告'},
                {value:400, name:'搜索引擎'}
            ].sort(function (a, b) { return a.value - b.value; }),
            roseType: 'radius',
            label: {
                normal: {
                    textStyle: {
                        color: 'rgba(0, 0, 0, 0.3)'
                    }
                }
            },
            labelLine: {
                normal: {
                    lineStyle: {
                        color: 'rgba(255, 255, 255, 0.3)'
                    },
                    smooth: 0.2,
                    length: 10,
                    length2: 20
                }
            },
            itemStyle: {
                normal: {
                    color: '#c23531',
                    shadowBlur: 200,
                    shadowColor: 'rgba(0, 0, 0, 0.5)'
                }
            },

            animationType: 'scale',
            animationEasing: 'elasticOut',
            animationDelay: function (idx) {
                return Math.random() * 200;
            }
        }
    ]
};;
if (option && typeof option === "object") {
    myChart.setOption(option, true);
}
</script>


SCRIPT;


sec_scripts($script);


?>