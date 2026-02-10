<?php
/**
 * Created by PhpStorm.
 * User: telbawab
 * Date: 27/01/2019
 * Time: 11:11 ص
 */
$MODULE_NAME = 'servicelength';
$TB_NAME = "Service_length";
$date_attr = "data-type='date' data-date-format='DD/MM/YYYY' data-val='true'  data-val-regex-pattern='" . date_format_exp() . "' data-val-regex='Error' ";
$date_attr1 = 'data-type="date" data-date-format="DD/MM/YYYY" data-val="true" data-val-regex-pattern="' . date_format_exp() . '" data-val-regex="Error" ';

  $rs =   $get_data[0] ;
$result=$result[0];
$salary_36_month1=$month_36;
$salary_5years=$years5;

 $index_request_url = base_url("$MODULE_NAME/$TB_NAME/index ");
 $update_data_url = base_url("$MODULE_NAME/$TB_NAME/update_data");
$gfc_domain = gh_gfc_domain();
$report_url = base_url("JsperReport/showreport?sys=hr/hr_retirement_discharge");
$report_sn = report_sn();
$EMP_RET_WORK=explode('/',$result['EMP_RET_WORK']);
 $EMP_RETIREMENT_DAY=explode('/',$result['EMP_RETIREMENT'])[0];
$EMP_RETIREMENT_MONTH=explode('/',$result['EMP_RETIREMENT'])[1];
$EMP_RETIREMENT_YEAR=explode('/',$result['EMP_RETIREMENT'])[2];



$EMP_WORK_C_DATE_BE_DAY=explode('/',$result['EMP_WORK_C_DATE_BE'])[0];
$EMP_WORK_C_DATE_BE_MONTH=explode('/',$result['EMP_WORK_C_DATE_BE'])[1];
$EMP_WORK_C_DATE_BE_YEAR=explode('/',$result['EMP_WORK_C_DATE_BE'])[2];


$EMP_WORK_C_DATE_AF_DAY=explode('/',$result['EMP_WORK_C_DATE_AF'])[0];
$EMP_WORK_C_DATE_AF_MONTH=explode('/',$result['EMP_WORK_C_DATE_AF'])[1];
$EMP_WORK_C_DATE_AF_YEAR=explode('/',$result['EMP_WORK_C_DATE_AF'])[2];

$EMP_WORK_TOTAL_DAY=explode('/',$result['EMP_WORK_TOTAL'])[0];
$EMP_WORK_TOTAL_MONTH=explode('/',$result['EMP_WORK_TOTAL'])[1];
$EMP_WORK_TOTAL_YEAR=explode('/',$result['EMP_WORK_TOTAL'])[2];


$EMP_REST_TO_DATE_60_DAY=explode('/',$result['EMP_REST_TO_DATE_60'])[0];
$EMP_REST_TO_DATE_60_MONTH=explode('/',$result['EMP_REST_TO_DATE_60'])[1];
$EMP_REST_TO_DATE_60_YEAR=explode('/',$result['EMP_REST_TO_DATE_60'])[2];


$EMP_YEAR_BOUNS_DAY=explode('/',$result['EMP_YEAR_BOUNS'])[0];
$EMP_YEAR_BOUNS_MONTH=explode('/',$result['EMP_YEAR_BOUNS'])[1];
$EMP_YEAR_BOUNS_YEAR=explode('/',$result['EMP_YEAR_BOUNS'])[2];

  ?>

<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
    <div class="left-content">
        <span class="main-content-title mg-b-0 mg-b-lg-1">استمارة حصر الخدمة (المالي) </span>
    </div>
    <div class="justify-content-center mt-2">
        <ol class="breadcrumb">
            <li class="breadcrumb-item tx-15"><a href="javascript:void(0);">النظام الاداري</a></li>
            <li class="breadcrumb-item active" aria-current="page">استمارة حصر الخدمة(المالي)</li>
        </ol>
    </div>
</div>
<!-- /breadcrumb -->
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header align-items-center d-flex py-3">
                <div class="mb-0 flex-grow-1 card-title">
                    <?= $title ?>
                </div>
                <div class="flex-shrink-0">
                    <a class="btn btn-secondary" href="<?= $index_request_url ?>">
                        <i class="fa fa-inbox"></i>
                        متابعة طلبات خلو الطرف
                    </a>
                </div>
            </div><!-- end card header -->
            <div class="card-body">
                <form id="<?= $TB_NAME ?>_form" method="post" action="" role="form"
                      novalidate="novalidate">
                    <div class="panel panel-primary">
                        <div class="panel-body">
                            <h5 class="text-on-pannel text-primary"><strong class="text-uppercase">بيانات
                                    الموظف</strong></h5>
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-2">

                                    </div>

                                </div>
                                <br>
                                <div class="row">
                                    <!--start no -->
                                    <div class="form-group  col-sm-1">
                                        <label class="control-label">الرقم الوظيفي</label>
                                        <div>
                                            <input type="number" value="<?= $rs['NO']; ?>" name="emp_no"
                                                   id="txt_emp_no" readonly class="form-control ">
                                        </div>
                                    </div>
                                    <!--end no -->
                                    <!--start name -->
                                    <div class="form-group  col-sm-2">
                                        <label class="control-label"> الموظف </label>
                                        <div>
                                            <input type="text" value="<?= $rs['NAME']; ?>" name="emp_name"
                                                   id="txt_emp_name" readonly class="form-control">
                                        </div>
                                    </div>


                                    <div class="form-group  col-sm-2">
                                        <label class="control-label"> تاريخ الميلاد </label>
                                        <div>
                                            <input type="text" value="<?= $rs['BIRTH_DATE']; ?>" name="txt_dob"
                                                   id="txt_dob" readonly class="form-control">
                                        </div>
                                    </div>
                                    <!--end name -->
                                    <!--start id -->
                                    <div class="form-group  col-sm-2">
                                        <label class="control-label"> رقم الهوية </label>
                                        <div>
                                            <input type="number" value="<?= $rs['ID']; ?>" name="emp_id"
                                                   id="txt_emp_id" readonly class="form-control ">
                                        </div>
                                    </div>
                                    <!--end id -->
                                    <!--start job -->
                                    <div class="form-group  col-sm-2">
                                        <label class="control-label">الوظيفة</label>
                                        <div>
                                            <input type="text" value="<?= $rs['W_NO_ADMIN_NAME'] ?>" name="emp_job"
                                                   id="txt_emp_job" readonly class="form-control">

                                        </div>
                                    </div>
                                    <!--end job -->
                                    <!--start branch-->
                                    <div class="form-group  col-sm-1">
                                        <label class="control-label">الفرع</label>
                                        <div>
                                            <input type="text" value="<?= $rs['BRANCH_NAME']; ?>" name="branch"
                                                   id="txt_branch" readonly class="form-control ">
                                            <input type="hidden" value="<?= $rs['BRANCH']; ?>" name="h_branch"
                                                   id="txt_h_branch" readonly class="form-control ">
                                        </div>
                                    </div>
                                    <div class="form-group  col-sm-1">
                                        <label class="control-label">الفئة</label>
                                        <div>
                                            <input type="text" value="<?= $rs['SP_NO_NAME']; ?>" name="sp_no"
                                                   id="txt_sp_no" readonly class="form-control ">

                                        </div>
                                    </div>

                                    <div class="form-group  col-sm-2">
                                        <label class="control-label">الدرجة</label>
                                        <div>
                                            <input type="text" value="<?= $rs['DEGREE_NAME']; ?>" name="degree"
                                                   id="txt_degree" readonly class="form-control ">

                                        </div>
                                    </div>

                                    <div class="form-group  col-sm-2">
                                        <label class="control-label">التدرج</label>
                                        <div>
                                            <input type="text" value="<?= $rs['KAD_NO_NAME']; ?>" name="kad_no"
                                                   id="txt_kad_no" readonly class="form-control ">

                                        </div>
                                    </div>

                                    <div class="form-group  col-sm-2">
                                        <label class="control-label">العلاوات الفعلية</label>
                                        <div>
                                            <input type="text" value="<?=$rs['PERIODICAL_ALLOWNCES']?>" name="actual_bonuses"
                                                   id="txt_actual_bonuses" readonly class="form-control ">

                                        </div>
                                    </div>

                                    <div class="form-group  col-sm-2">
                                        <label class="control-label">العلاوات المتبقية</label>
                                        <div>
                                            <input type="text" value="" name="remain_bonuses"
                                                   id="txt_remain_bonuses" readonly class="form-control ">

                                        </div>
                                    </div>

                                    <div class="form-group  col-sm-2">
                                        <label class="control-label">نسبة طبيعة العمل</label>
                                        <div>
                                            <input type="text" value="<?= $rs['RATE_WORK']; ?>" name="work_ratio"
                                                   id="txt_work_ratio" readonly class="form-control ">

                                        </div>
                                    </div>


                                    <div class="form-group  col-sm-1">
                                        <label class="control-label">تاريخ بدء العمل</label>
                                        <div>
                                            <input type="text" value="<?= $rs['HIRE_DATE']; ?>" name="hire_date"
                                                   id="txt_hire_date" readonly class="form-control ">

                                        </div>
                                    </div>

                                    <div class="form-group  col-sm-2">
                                        <label class="control-label">تاريخ الانتفاع بقانون التأمين والمعاشات</label>
                                        <div>
                                            <input type="text" value="<?= $rs['FEX_DATE']; ?>" name="fex_date"
                                                   id="txt_fex_date" readonly class="form-control ">

                                        </div>
                                    </div>

                                    <div class="form-group  col-sm-2">
                                        <label class="control-label">تاريخ إنتهاء الخدمة</label>
                                        <div>
                                            <input type="text" value="<?= $rs['WORK_END_DATE']; ?>" <?=$date_attr ?> name="txt_end_date"
                                                   id="txt_end_date"   class="form-control ">

                                        </div>
                                    </div>

                                    <div class="form-group  col-sm-2">
                                        <label class="control-label">سبب إنتهاء الخدمة</label>
                                        <div>

                                            <select name="end_service_reason" id="end_service_reason" class="form-control sel2">
                                                <option value="">_________</option>
                                                <?php foreach ($end_service_reason as $row) : ?>
                                                    <option value="<?= $row['CON_NO'] ?>" <?=$rs['WORK_END_RESON']==$row['CON_NO']?'selected':''?> ><?= $row['CON_NO'] . ': ' . $row['CON_NAME'] ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <!--end branch -->

                                </div>
                            </div>
                            <div class="row pull-right">
                                <div style="clear: both;">
                                    <?php echo modules::run('attachments/attachment/index', $rs['ID_VACANCY'], 'EMP_VACANCY_TB'); ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="panel panel-primary">
                        <div class="panel-body">
                            <h5 class="text-on-pannel text-danger">
                                <strong class="text-uppercase">
                                    بيانات التقاعد                                </strong></h5>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">

                                        <table class="table" id="page_tb_v1" data-container="container" style="background: #d7e5fa;">
                                            <thead>
                                            <tr>
                                                <th style="width: 10%;">عمر الموظف</th>
                                                <th style="width: 10%;">يوم</th>
                                                <th style="width: 10%;">شهر</th>
                                                <th style="width: 10%;">سنة</th>


                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td  >
                                                    <div class="form-group col-md-12">

                                                        <label class="control-label">عمر الموظف في </label>

                                                        <input readonly type="text"
                                                               value="<?= $rs['WORK_END_DATE']; ?>" <?= $date_attr ?>
                                                               name="txt_from_date_v0" id="txt_emp_age"
                                                               class="form-control">

                                                    </div>


                                                </td>


                                                <td>


                                                    <div class="form-group col-md-12">

                                                        <input readonly type="number"
                                                               value="<?= $EMP_RET_WORK[0]; ?>"
                                                               name="txt_emp_age_day" id="txt_emp_age_day"
                                                               class="form-control">
                                                    </div>
                                                </td>
                                                <td>


                                                    <div class="form-group col-md-12">

                                                        <input readonly type="number"
                                                               value="<?= $EMP_RET_WORK[1]; ?>"
                                                               name="txt_emp_age_month" id="txt_emp_age_month"
                                                               class="form-control">
                                                    </div>
                                                </td>

                                                <td  >
                                                    <div class="form-group col-md-12">


                                                        <input readonly type="number"
                                                               value="<?= $EMP_RET_WORK[2] ?>"
                                                               name="txt_emp_age_year" id="txt_emp_age_year"
                                                               class="form-control">
                                                    </div>   </td>


                                            </tr>
                                            <tr>
                                                <td colspan="5" style="background: #d7e5fa;">

                                                    <label class="control-label" >سنوات الخدمة </label>



                                                </td>





                                            </tr>
                                            <tr>
                                                <td  >
                                                    <div class="form-group col-md-12">

                                                        <label class="control-label">سنوات الخدمة حتى بلوغ سن 60 </label>



                                                    </div>


                                                </td>


                                                <td>


                                                    <div class="form-group col-md-12">

                                                        <input READONLY type="number"
                                                               value="<?= $EMP_RETIREMENT_DAY; ?>"
                                                               name="txt_until_60_day" id="txt_until_60_day"
                                                               class="form-control">
                                                    </div>
                                                </td>
                                                <td>


                                                    <div class="form-group col-md-12">

                                                        <input READONLY type="number"
                                                               value="<?= $EMP_RETIREMENT_MONTH; ?>"
                                                               name="txt_until_60_month" id="txt_until_60_month"
                                                               class="form-control">
                                                    </div>
                                                </td>

                                                <td  >
                                                    <div class="form-group col-md-12">


                                                        <input READONLY type="number"
                                                               value="<?= $EMP_RETIREMENT_YEAR; ?>"
                                                               name="txt_until_60_year" id="txt_until_60_year"
                                                               class="form-control">
                                                    </div>   </td>


                                            </tr>
                                            <tr>
                                                <td  >
                                                    <div class="form-group col-md-12">

                                                        <label class="control-label">سنوات الخدمة حتى 31/08/2006 </label>


                                                    </div>


                                                </td>


                                                <td>


                                                    <div class="form-group col-md-12">

                                                        <input READONLY type="number"
                                                               value="<?= $EMP_WORK_C_DATE_BE_DAY; ?>"
                                                               name="txt_until_60_day" id="txt_until_60_day"
                                                               class="form-control">
                                                    </div>
                                                </td>
                                                <td>


                                                    <div class="form-group col-md-12">

                                                        <input READONLY type="number"
                                                               value="<?= $EMP_WORK_C_DATE_BE_MONTH; ?>"
                                                               name="txt_until_60_month" id="txt_until_60_month"
                                                               class="form-control">
                                                    </div>
                                                </td>

                                                <td  >
                                                    <div class="form-group col-md-12">


                                                        <input READONLY type="number"
                                                               value="<?= $EMP_WORK_C_DATE_BE_YEAR; ?>"
                                                               name="txt_until_60_year" id="txt_until_60_year"
                                                               class="form-control">
                                                    </div>   </td>


                                            </tr>

                                            <tr>
                                                <td  >
                                                    <div class="form-group col-md-12">

                                                        <label class="control-label">سنوات الخدمة من 01/09/2006 حتى <?= $rs['WORK_END_DATE'];?> </label>


                                                    </div>


                                                </td>


                                                <td>


                                                    <div class="form-group col-md-12">

                                                        <input READONLY type="number"
                                                               value="<?= $EMP_WORK_C_DATE_AF_DAY; ?>"
                                                               name="txt_until_60_day" id="txt_until_60_day"
                                                               class="form-control">
                                                    </div>
                                                </td>
                                                <td>


                                                    <div class="form-group col-md-12">

                                                        <input READONLY type="number"
                                                               value="<?= $EMP_WORK_C_DATE_AF_MONTH; ?>"
                                                               name="txt_until_60_month" id="txt_until_60_month"
                                                               class="form-control">
                                                    </div>
                                                </td>

                                                <td  >
                                                    <div class="form-group col-md-12">


                                                        <input READONLY type="number"
                                                               value="<?= $EMP_WORK_C_DATE_AF_YEAR; ?>"
                                                               name="txt_until_60_year" id="txt_until_60_year"
                                                               class="form-control">
                                                    </div>   </td>


                                            </tr>
                                            <tr>
                                                <td  >
                                                    <div class="form-group col-md-12">

                                                        <label class="control-label">اجمالي مدة الخدمة  </label>



                                                    </div>


                                                </td>


                                                <td>


                                                    <div class="form-group col-md-12">
                                                        <input READONLY type="number"  value="<?= $EMP_WORK_TOTAL_DAY; ?>"
                                                               name="txt_until_60_day" id="txt_until_60_day"
                                                               class="form-control">
                                                    </div>
                                                </td>
                                                <td>


                                                    <div class="form-group col-md-12">

                                                        <input READONLY type="number"
                                                               value="<?= $EMP_WORK_TOTAL_MONTH; ?>"
                                                               name="txt_until_60_month" id="txt_until_60_month"
                                                               class="form-control">
                                                    </div>
                                                </td>

                                                <td  >
                                                    <div class="form-group col-md-12">


                                                        <input READONLY type="number"
                                                               value="<?= $EMP_WORK_TOTAL_YEAR; ?>"
                                                               name="txt_until_60_year" id="txt_until_60_year"
                                                               class="form-control">
                                                    </div>   </td>


                                            </tr>
                                            <tr>
                                                <td  >
                                                    <div class="form-group col-md-12">

                                                        <label class="control-label">المدة المتبقية من تاريخ التقاعد المدخل حتى التقاعد على 60 عام  </label>



                                                    </div>


                                                </td>


                                                <td>


                                                    <div class="form-group col-md-12">

                                                        <input READONLY type="number"
                                                               value="<?= $EMP_REST_TO_DATE_60_DAY; ?>"
                                                               name="txt_until_60_day" id="txt_until_60_day"
                                                               class="form-control">
                                                    </div>
                                                </td>
                                                <td>


                                                    <div class="form-group col-md-12">

                                                        <input READONLY type="number"
                                                               value="<?= $EMP_REST_TO_DATE_60_MONTH; ?>"
                                                               name="txt_until_60_month" id="txt_until_60_month"
                                                               class="form-control">
                                                    </div>
                                                </td>

                                                <td  >
                                                    <div class="form-group col-md-12">


                                                        <input READONLY type="number"
                                                               value="<?= $EMP_REST_TO_DATE_60_YEAR; ?>"
                                                               name="txt_until_60_year" id="txt_until_60_year"
                                                               class="form-control">
                                                    </div>   </td>


                                            </tr>
                                            <tr>
                                                <td  >
                                                    <div class="form-group col-md-12">

                                                        <label class="control-label">المدة التي سيتم احتساب مكافأة تعويض بحيث تكون اقل من  5 سنوات لحين بلوغ 60  </label>



                                                    </div>


                                                </td>


                                                <td>


                                                    <div class="form-group col-md-12">

                                                        <input READONLY type="number"
                                                               value="<?= $EMP_YEAR_BOUNS_DAY; ?>"
                                                               name="txt_until_60_day" id="txt_until_60_day"
                                                               class="form-control">
                                                    </div>
                                                </td>
                                                <td>


                                                    <div class="form-group col-md-12">

                                                        <input READONLY type="number"
                                                               value="<?= $EMP_YEAR_BOUNS_MONTH; ?>"
                                                               name="txt_until_60_month" id="txt_until_60_month"
                                                               class="form-control">
                                                    </div>
                                                </td>

                                                <td  >
                                                    <div class="form-group col-md-12">


                                                        <input READONLY type="number"
                                                               value="<?= $EMP_YEAR_BOUNS_YEAR; ?>"
                                                               name="txt_until_60_year" id="txt_until_60_year"
                                                               class="form-control">
                                                    </div>   </td>


                                            </tr>
                                            <tr>
                                                <td colspan="2"></td>
                                                <td  ><input class="btn btn-secondary" type="button" value="36 شهر قبل" onclick="showModal()"></td>
                                            <td  ><input class="btn btn-secondary" type="button" value="5 سنوات بعد" onclick="showModal2()"></td></tr>
                                            </tbody>



                                        </table>


                                    </div>

                                </div>
                            </div>
                            <br>






                        </div></div>
                    <div class="panel panel-primary">
                        <div class="panel-body">
                            <h5 class="text-on-pannel text-primary"><strong class="text-uppercase">المرتب الخاضع للتأمين والمعاشات
                                </strong></h5>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">

                                        <div class="form-group col-md-2">
                                            <label class="control-label">الاساسي </label>
                                            <input readonly type="number"
                                                   value="<?= $rs['BASIC_SALARY']; ?>"
                                                   name="txt_basic_salary" id="txt_basic_salary"
                                                   class="form-control">
                                        </div>

                                        <div class="form-group  col-md-3">
                                            <label class="control-label">نسبة طبيعة العمل</label>
                                            <input readonly type="text" value="<?= $rs['WORK_RATIO']; ?>"
                                                   name="txt_work_ratio_1" id="txt_work_ratio_1"
                                                   class="form-control ">
                                        </div>


                                        <div class="form-group  col-md-2">
                                            <label class="control-label">تكملة 1</label>
                                            <input readonly type="number" value="<?= $rs['JOB_ALLOWNCE_PCT']; ?>"
                                                   name="job_allownce_pct" id="txt_job_allownce_pct"
                                                   class="form-control ">
                                        </div>


                                        <div class="form-group  col-md-2">
                                            <label class="control-label">تكملة 2</label>
                                            <input readonly type="number" value="<?= $rs['JOB_ALLOWNCE_PCT_EXTRA']; ?>"
                                                   name="JOB_ALLOWNCE_PCT_EXTRA" id="txt_job_allownce_pct_extra"
                                                   class="form-control ">
                                        </div>
                                        <div class="form-group  col-md-2">
                                            <label class="control-label">علاوة ترقية</label>
                                            <input readonly type="number" value="<?= $rs['PROMOTION_BONUS']; ?>"
                                                   name="promotion_bonus" id="txt_promotion_bonus"
                                                   class="form-control ">
                                        </div>


                                        <div class="form-group  col-md-2">
                                            <label class="control-label">علاوة غلاء المعيشة</label>
                                            <input readonly type="number" value="<?= $rs['EXPENSIVE_LIFE']; ?>"
                                                   name="expensive_life" id="txt_expensive_life"
                                                   class="form-control ">
                                        </div>




                                        <div class="form-group  col-md-4">
                                            <label class="control-label">إجمالي الراتب الخاضع للتأمين</label>
                                            <input readonly type="number" value="<?= $rs['SALARY_TO_INSURANCE']; ?>"
                                                   name="salary_insurance" id="txt_salary_insurance"
                                                   class="form-control ">
                                        </div>

                                        <div class="form-group  col-md-3">
                                            <label class="control-label">ملاحظات</label>
                                            <input type="text" value="<?= $rs['NOTES']; ?>"
                                                   name="notes" id="txt_notes"
                                                   class="form-control ">
                                        </div>




                                    </div>

                                </div>
                            </div>

                        </div>

                    </div>
                    <div class="panel panel-primary">
                        <div class="panel-body">
                            <h5 class="text-on-pannel text-primary"><strong class="text-uppercase">الراتب التقاعدي
                                </strong></h5>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="form-group  col-md-3">
                                            <label class="control-label">اجمالي الراتب الأساسي ل 36 شهر قبل التقاعد</label>
                                            <input readonly type="number"
                                                   style="direction: ltr;text-align: end;"
                                                   value="<?= $result['BASIC_SALARY']; ?>"
                                                   name="BASIC_SALARY" id="txt_basic_salary"
                                                   class="form-control ">
                                        </div>

                                        <div class="form-group  col-md-3">
                                            <label class="control-label">اجمالي علاوة المهنة ل 36 شهر قبل التقاعد</label>
                                            <input readonly type="number"
                                                   style="direction: ltr;text-align: end;"
                                                   value="<?= $result['PERIODICAL_SALARY']; ?>"
                                                   name="promotion_bonus" id="txt_promotion_bonus"
                                                   class="form-control ">
                                        </div>

                                        <div class="form-group  col-md-2">
                                            <label class="control-label">اجمالي علاوة غلاء المعيشة</label>
                                            <input readonly type="number" style="direction: ltr;text-align: end;"
                                                   value="<?= $result['BOUNS_SALARY']; ?>"
                                                   name="expensive_life" id="txt_expensive_life"
                                                   class="form-control ">
                                        </div>


                                        <div class="form-group  col-md-4">
                                            <label class="control-label">إجمالي علاوة شخصية</label>
                                            <input readonly type="number"
                                                   style="direction: ltr;text-align: end;"
                                                   value="<?= $result['PERSONAL_BOUNS']; ?>"
                                                   name="salary_insurance" id="txt_salary_insurance"
                                                   class="form-control ">
                                        </div>

                                        <div class="form-group col-md-2">
                                            <label class="control-label">نسبة المعاش قبل 01/09/2006 </label>
                                            <input readonly type="number"
                                                   style="direction: ltr;text-align: end;"
                                                   value="<?= $result['SALARY_2_5_RATE']; ?>"
                                                   name="SALARY_2_5_RATE" id="txt_salary_2_5_rate"
                                                   class="form-control">
                                        </div>

                                        <div class="form-group  col-md-3">
                                            <label class="control-label">نسبة المعاش بعد 01/09/2006</label>
                                            <input readonly type="text"
                                                   style="direction: ltr;text-align: end;"
                                                   value="<?= $result['SALARY_2_RATE']; ?>"
                                                   name="SALARY_2_RATE" id="txt_salary_2_rate"
                                                   class="form-control ">
                                        </div>


                                        <div class="form-group  col-md-2">
                                            <label class="control-label">اجمالي نسبة المعاش</label>
                                            <input readonly type="number"
                                                   style="direction: ltr;text-align: end;"
                                                   value="<?= $result['TOTAL_SALARY_RATE']; ?>"
                                                   name="TOTAL_SALARY_RATE" id="txt_total_salary_rate"
                                                   class="form-control ">
                                        </div>









                                        <div class="form-group  col-md-3">
                                            <label class="control-label">خصم التأمين الصحي</label>
                                            <input readonly type="text"
                                                   style="direction: ltr;text-align: end;"
                                                   value="<?= $result['HEALTH_INSURANCE_DISCOUNT']; ?>"
                                                   name="notes" id="txt_notes"
                                                   class="form-control ">
                                        </div>




                                    </div>

                                </div>
                            </div>

                        </div>

                    </div>
                    <div class="panel panel-primary">
                        <div class="panel-body">
                            <h5 class="text-on-pannel text-primary"><strong class="text-uppercase">الفروقات المالية المتعلقة بالراتب والمكافئة للموظف
                                </strong></h5>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">

                                        <div class="form-group col-md-5">
                                            <label class="control-label">اجمالي راتب الموظف </label>
                                            <label class="control-label">(حتى سن 60 بحد اقصى اخر 5 سنوات حتى تاريخ التقاعد الفعلي) </label>
                                            <input readonly type="number"
                                                   style="direction: ltr;text-align: end;"
                                                   value="<?= $result['TOTAL_EMP_SALARY_60']; ?>"
                                                   name="TOTAL_EMP_SALARY_60" id="txt_total_emp_salary_60"
                                                   class="form-control">
                                        </div>

                                        <div class="form-group  col-md-3">
                                            <label class="control-label">اجمالي الراتب التقاعدي ( بحد اقصى 5 سنوات )</label>
                                            <input readonly type="text"
                                                   style="direction: ltr;text-align: end;"
                                                   value="<?= $result['TOTAL_SALARY_RETIREMENT_5']; ?>"
                                                   name="TOTAL_SALARY_RETIREMENT_5" id="txt_total_salary_retirement_5"
                                                   class="form-control ">
                                        </div>


                                        <div class="form-group  col-md-2">
                                            <label class="control-label">الفرق بين الراتب الحالي والراتب التقاعدي</label>
                                            <input readonly type="number"
                                                   style="direction: ltr;text-align: end;"
                                                   value="<?= $result['SALARY_DIFF']; ?>"
                                                   name="SALARY_DIFF" id="txt_salary_diff"
                                                   class="form-control ">
                                        </div>

         <div class="form-group  col-md-2">
                                            <label class="control-label">صافي الراتب التقاعدي</label>
                                            <input readonly type="number"
                                                   style="direction: ltr;text-align: end;"
                                                   value="<?= $result['NET_SALARY_RETIREMENT']; ?>"
                                                   name="NET_SALARY_RETIREMENT" id="txt_net_salary_retirement"
                                                   class="form-control ">
                                        </div>
         <div class="form-group  col-md-2">
                                            <label class="control-label">الاجمالي</label>
                                            <input readonly type="number"
                                                   style="direction: ltr;text-align: end;"
                                                   value="<?= $result['TOTAL_SALARY_RETIREMENT']; ?>"
                                                   name="TOTAL_SALARY_RETIREMENT" id="txt_total_salary_retirement"
                                                   class="form-control ">
                                        </div>









                                    </div>

                                </div>
                            </div>

                        </div>

                    </div>
                    <div class="panel panel-primary">
                        <div class="panel-body">
                            <h5 class="text-on-pannel text-primary"><strong class="text-uppercase">الامتيازات الاخرى المفترض منحها للموظف
                                </strong></h5>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">

                                        <div class="form-group col-md-5">
                                            <label class="control-label">منحة 200 كيلو حتى سن 60 </label>
                                             <input   type="number"
                                                   style="direction: ltr;text-align: end;"
                                                   value="<?= $result['SALARY_2_5_RATE']; ?>"
                                                   name="txt_200k" id="txt_200k"
                                                   class="form-control">
                                        </div>










                                    </div>

                                </div>
                            </div>

                        </div>

                    </div>


                    <div class="modal-footer">

                        <button type="button" data-action="submit" class="btn btn-primary" onclick="Create()">حفظ البيانات</button>
                    </div>
                    <div class="modal-footer">

                        <a href="<?=base_url('servicelength/service_length/get/'.$rs['NO'])?>" data-action="submit" class="btn btn-primary"  >الاستمارة الادارية</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!------------------------->

    <!--start myModal     -->
    <div id="Modal1" class="modal fade <?= $TABLE_NAME; ?>Modal1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content" style="min-height: 590px;">
                <div class="modal-header">
                    <h5 class="modal-title">
                        36 شهر                        </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body modal-body-fit">
                    <div class="kt-portlet kt-portlet--mobile">



                        <div class="kt-portlet__body <?= $TABLE_NAME; ?>_ListIns3">
                            <!--begin: Datatable -->
                            <form id="<?= $TABLE_NAME; ?>_form" name="<?= $TABLE_NAME; ?>_form3"  class="horizontal-form <?= $TABLE_NAME; ?>_form3" method="post"  action="<?=$addRecord_legal_low;?>" novalidate="novalidate" accept-charset="UTF-16LE" enctype="multipart/form-data">
                                <input hidden="hidden" class="form-control" type="number" value="<?=$rs['NO']?>"id="EMP_NO_" name="EMP_NO_"/>
                                <input hidden="hidden" class="form-control" type="number" value="36"id="MONTHS_36" name="MONTHS_36"/>

                                <table class="table " id="kt_table_4">
                                    <thead>
                                    <tr>
                                        <th style="width:5%"># </th>
                                        <th style="width:10%">الشهر </th>
                                        <th style="width:10%">الراتب الأساسي </th>
                                        <th style="width:10%">الراتب الأساسي </th>
                                        <th style="width:10%">علاوة المهنة </th>
                                        <th style="width:10%">علاوة المهنة </th>
                                        <th style="width:10%">غلاء المعيشة </th>
                                        <th style="width:10%">غلاء المعيشة </th>
                                        <th style="width:10%">الترقية </th>
                                        <th style="width:10%">الترقية </th>
                                        <th style="width:10%">تكملة1 </th>
                                        <th style="width:10%">تكملة1 </th>
                                        <th style="width:10%">تكملة2 </th>
                                        <th style="width:10%">تكملة2 </th>
                                        <th style="width:10%">علاوة التخصص </th>
                                        <th style="width:10%">علاوة التخصص </th>
                                        <th style="background-color: antiquewhite">الدرجة </th>
                                        <th style="background-color: antiquewhite">الأقدمية </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $count=0;
                                    $BASIC_SAL=0;
                                    $PROFESSION_BONUS=0;
                                     $COST_LIVING=0;
                                    $PROMOTION_BONUS=0;
                                    $JOB_ALLOWNCE_PCT_EXTRA=0;
                                    $COMOANY_ALTERNATIVE=0;
                                    $SPECIALIZATION_BONUS=0;
                                    $TOTAL=0;
                                    foreach($month_36 as $rows) :
                                        $BASIC_SAL+= round($rows['BASIC_SAL'],2) ;
                                        $PROFESSION_BONUS+= round($rows['PROFESSION_BONUS'],2);
                                        $COST_LIVING= round($rows['COST_LIVING'],2);
                                        $PROMOTION_BONUS+= round($rows['PROMOTION_BONUS'],2);
                                        $JOB_ALLOWNCE_PCT_EXTRA+= round($rows['JOB_ALLOWNCE_PCT_EXTRA'],2);
                                        $COMOANY_ALTERNATIVE+= round($rows['COMOANY_ALTERNATIVE'],2);
                                        $SPECIALIZATION_BONUS+= round($rows['SPECIALIZATION_BONUS'],2); ?>
                                        <tr>
                                            <td hidden="hidden"><input class="form-control" type="number" value="<?=$rows['SER']?>"id="SER<?=$count?>" name="SER[]"/></td>
                                            <td ><input style="width: 44px"  type="checkbox" class="group-checkable" data-set="#checkbox .checkboxes" name="checkbox[]" id="checkbox<?=$count?>" value="<?=$count?>" /></td>
                                            <td><input class="form-control" type="number" value="<?=$rows['FOR_MONTH']?>"id="FOR_MONTH_36_<?=$count?>" name="FOR_MONTH_36[]"/></td>
                                            <td><input READONLY class="form-control" type="number" value="<?=$rows['BASIC_SAL_C']?>" id="BASIC_SAL_C<?=$count?>" name="BASIC_SAL_C[]"/></td>
                                            <td><input class="form-control" type="number" value="<?=$rows['BASIC_SAL']?>" id="BASIC_SAL<?=$count?>" name="BASIC_SAL[]"/></td>
                                            <td><input READONLY class="form-control" type="number" value="<?=$rows['PROFESSION_BONUS_C']?>" id="PROFESSION_BONUS_C<?=$count?>" name="PROFESSION_BONUS_C[]"/></td>
                                            <td><input class="form-control" type="number" value="<?=$rows['PROFESSION_BONUS']?>" id="PROFESSION_BONUS<?=$count?>" name="PROFESSION_BONUS[]"/></td>
                                            <td><input READONLY class="form-control" type="number" value="<?=$rows['COST_LIVING_C']?>" id="COST_LIVING_C<?=$count?>" name="COST_LIVING_C[]"/> </td>
                                            <td><input class="form-control" type="number" value="<?=$rows['COST_LIVING']?>" id="COST_LIVING<?=$count?>" name="COST_LIVING[]"/> </td>
                                            <td><input READONLY class="form-control" type="number" value="<?=$rows['PROMOTION_BONUS_C']?>" id="PROMOTION_BONUS_C<?=$count?>" name="PROMOTION_BONUS_C[]"/></td>
                                            <td><input class="form-control" type="number" value="<?=$rows['PROMOTION_BONUS']?>" id="PROMOTION_BONUS<?=$count?>" name="PROMOTION_BONUS[]"/></td>
                                            <td><input READONLY class="form-control" type="number" value="<?=$rows['JOB_ALLOWNCE_PCT_EXTRA_C']?>" id="JOB_ALLOWNCE_PCT_EXTRA_C<?=$count?>" name="JOB_ALLOWNCE_PCT_EXTRA_C[]"/></td>
                                            <td><input class="form-control" type="number" value="<?=$rows['JOB_ALLOWNCE_PCT_EXTRA']?>" id="JOB_ALLOWNCE_PCT_EXTRA<?=$count?>" name="JOB_ALLOWNCE_PCT_EXTRA[]"/></td>
                                            <td><input READONLY class="form-control" type="number" value="<?=$rows['COMOANY_ALTERNATIVE_C']?>" id="COMOANY_ALTERNATIVE_C<?=$count?>" name="COMOANY_ALTERNATIVE_C[]"/></td>
                                            <td><input class="form-control" type="number" value="<?=$rows['COMOANY_ALTERNATIVE']?>" id="COMOANY_ALTERNATIVE<?=$count?>" name="COMOANY_ALTERNATIVE[]"/></td>
                                            <td><input READONLY class="form-control" type="number" value="<?=$rows['SPECIALIZATION_BONUS_C']?>" id="SPECIALIZATION_BONUS_C<?=$count?>" name="SPECIALIZATION_BONUS_C[]"/></td>
                                            <td><input class="form-control" type="number" value="<?=$rows['SPECIALIZATION_BONUS']?>" id="SPECIALIZATION_BONUS<?=$count?>" name="SPECIALIZATION_BONUS[]"/></td>

                                            <td><input  style="background-color: antiquewhite"   class="form-control" type="text"   value="<?=$rows['DEGREE_NAME']?>" id="NEW_DEGREE<?=$count?>" name="NEW_DEGREE[]"/></td>
                                            <td><input  style="background-color: antiquewhite"  class="form-control" type="number" value="<?=$rows['PER_ALLOW']?>" id="PER_ALLOW_IN<?=$count?>" name="PER_ALLOW_IN[]"/></td>


                                        </tr>

                                        <?php $count++;  endforeach;
                                        $TOTAL=($BASIC_SAL/36)+
                                            ($PROFESSION_BONUS/36)+
                                 //   $COST_LIVING+
                                            ( $PROMOTION_BONUS/36)+
                                            ($JOB_ALLOWNCE_PCT_EXTRA/36)+
                                            ($COMOANY_ALTERNATIVE/36)+
                                            ( $SPECIALIZATION_BONUS/36);


                                        $COST_LIVING=(16.52/100)*($BASIC_SAL/36);
                                    $TOTAL+=$COST_LIVING;
                                    $TOTAL=round($TOTAL,2)
                                        ?>


                                    <tr style="background-color: #9a6e3a">
                                        <td hidden="hidden"><input readonly class="form-control" type="number" value="<?=$rows['SER']?>"id="SER<?=$count?>" name="SER[]"/></td>
                                        <td  COLSPAN="2" ><input readonly class="form-control" type="text" value=" المتوسط الشهري "id="FOR_MONTH<?=$count?>" name="FOR_MONTH[]"/></td>
                                         <td COLSPAN="2"><input readonly style="text-align: end;" class="form-control" type="number" value="<?=round(($BASIC_SAL/36),2)?>" id="BASIC_SAL<?=$count?>" name="BASIC_SAL[]"/></td>
                                         <td COLSPAN="2"><input readonly style="text-align: end;" class="form-control" type="number" value="<?=round($PROFESSION_BONUS/36,2)?>" id="PROFESSION_BONUS<?=$count?>" name="PROFESSION_BONUS[]"/></td>
                                         <td COLSPAN="2"><input readonly style="text-align: end;" class="form-control" type="number" value="<?=round($COST_LIVING,2)?>" id="COST_LIVING<?=$count?>" name="COST_LIVING[]"/> </td>
                                         <td COLSPAN="2"><input readonly style="text-align: end;" class="form-control" type="number" value="<?=round($PROMOTION_BONUS/36,2)?>" id="PROMOTION_BONUS<?=$count?>" name="PROMOTION_BONUS[]"/></td>
                                         <td COLSPAN="2"><input readonly style="text-align: end;" class="form-control" type="number" value="<?=round($JOB_ALLOWNCE_PCT_EXTRA/36,2)?>" id="JOB_ALLOWNCE_PCT_EXTRA<?=$count?>" name="JOB_ALLOWNCE_PCT_EXTRA[]"/></td>
                                         <td COLSPAN="2"><input readonly style="text-align: end;" class="form-control" type="number" value="<?=round($COMOANY_ALTERNATIVE/36,2)?>" id="COMOANY_ALTERNATIVE<?=$count?>" name="COMOANY_ALTERNATIVE[]"/></td>
                                        <td COLSPAN="2"><input readonly style="text-align: end;" class="form-control" type="number" value="<?=round($SPECIALIZATION_BONUS/36,2)?>" id="SPECIALIZATION_BONUS<?=$count?>" name="SPECIALIZATION_BONUS[]"/></td>



                                    </tr>
                                    <tr>
                                        <td   COLSPAN="15"><input style="text-align: center;" class="form-control" type="number" value="<?=$TOTAL?>" id="TOTAL<?=$count?>" name="TOTAL[]"/></td>

                                    </tr>
                                    </tbody>

                                </table>
                            </form>       <!--end: Datatable -->
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-clean btn-bold btn-upper btn-font-sm" data-dismiss="modal">إلغاء</button>
                    <button   type="button" class="btn btn-warning"  id="addRecordLow" onclick="update_data('_form')">حفظ البيانات</button>
                </div>
            </div>
        </div>
    </div>
    <div id="Modal2" class="modal fade <?= $TABLE_NAME; ?>Modal2" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content" style="min-height: 590px;">
                <div class="modal-header">
                    <h5 class="modal-title">
                        5 سنوات                        </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body modal-body-fit">
                    <div class="kt-portlet kt-portlet--mobile">



                        <div class="kt-portlet__body <?= $TABLE_NAME; ?>_ListIns3">
                            <!--begin: Datatable -->
                            <form id="<?= $TABLE_NAME; ?>_form2" name="<?= $TABLE_NAME; ?>_form3"  class="horizontal-form <?= $TABLE_NAME; ?>_form2" method="post"  action="<?=$addRecord_legal_low;?>" novalidate="novalidate" accept-charset="UTF-16LE" enctype="multipart/form-data">

                                <table class="table " id="kt_table_4">
                                    <input hidden="hidden" class="form-control" type="number" value="<?=$rs['NO']?>"id="EMP_NO_" name="EMP_NO_"/>

                                    <thead>
                                    <tr>
                                        <th style=""># </th>
                                         <th style="">الشهر </th>
                                        <th style="">الراتب الأساسي </th>
                                        <th style="">علاوة المهنة </th>
                                        <th style="">غلاء المعيشة </th>
                                        <th style="">الترقية </th>
                                        <th style="">علاوة المخاطرة </th>
                                        <th style="">علاوة التخصص </th>
                                        <th style="">بدلات ثابتة </th>
                                        <th style=""> تكملة </th>
                                        <th style="background-color: #9a3a42">المجموع </th>

                                        <th style="background-color: antiquewhite">الدرجة </th>
                                        <th style="background-color: antiquewhite">الأقدمية </th>
                                        <th hidden="hidden" style="background-color: antiquewhite">علاوة الترقية </th>

                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php $count=1;
                                    $SUM_BASIC_SAL=0;
                                    $SUM_PROFESSION_BONUS=0;
                                    $SUM_PROMOTION_BONUS=0;

                                    $SUM_COST_LIVING=0;
                                    $SUM_RISK_PREMIUM=0;
                                    $SUM_SPECIALIZATION_BONUS=0;
                                    $SUM_SUPERVISORY_BONUS=0;
                                    $SUM_COMOANY_ALTERNATIVE=0;
                                    $TOTAL=0;
                                    $TOTAL1=0;
                                    foreach($salary_5years as $rows) :
                                        if($rows['IS_CALC']==1){
                                            $SUM_BASIC_SAL+=$rows['BASIC_SAL'];
                                            $SUM_PROFESSION_BONUS+=$rows['PROFESSION_BONUS'];
                                            $SUM_COST_LIVING+=$rows['COST_LIVING'];
                                            $SUM_PROMOTION_BONUS+=$rows['PROMOTION_BONUS'];
                                            $SUM_RISK_PREMIUM+=$rows['RISK_PREMIUM'];
                                            $SUM_SPECIALIZATION_BONUS+=$rows['SPECIALIZATION_BONUS'];
                                            $SUM_SUPERVISORY_BONUS+=$rows['SUPERVISORY_BONUS'];
                                            $SUM_COMOANY_ALTERNATIVE+=$rows['COMOANY_ALTERNATIVE'];


                                            $TOTAL1= $rows['BASIC_SAL']+
                                                $rows['PROFESSION_BONUS']+
                                                $rows['COST_LIVING']+
                                                $rows['PROMOTION_BONUS']+
                                                $rows['RISK_PREMIUM']+
                                                $rows['SPECIALIZATION_BONUS']+
                                                $rows['COMOANY_ALTERNATIVE']+
                                                $rows['SUPERVISORY_BONUS'];







                                        }
                                        ?>
                                        <tr <?=$rows['IS_CALC']==1?'style="background-color: #9a3a42"':''?>>
                                            <td hidden="hidden"><input class="checkbox" type="number" value="<?=$rows['SER']?>"id="SER<?=$count?>" name="SER[]"/></td>
                                            <td ><input readonly style="width: 44px" type="number" class="form-control"   value="<?=$count?>" /></td>
                                             <td><input class="form-control" type="number" value="<?=$rows['FOR_MONTH']?>"id="FOR_MONTH<?=$count?>" name="FOR_MONTH_5[]"/></td>
                                            <td><input READONLY class="form-control" type="number" value="<?=$rows['BASIC_SAL']?>" id="BASIC_SAL_C<?=$count?>" name="BASIC_SAL[]"/></td>
                                            <td><input READONLY class="form-control" type="number" value="<?=$rows['PROFESSION_BONUS']?>" id="PROFESSION_BONUS_C<?=$count?>" name="PROFESSION_BONUS[]"/></td>
                                            <td><input READONLY class="form-control" type="number" value="<?=$rows['COST_LIVING']?>" id="COST_LIVING_C<?=$count?>" name="COST_LIVING[]"/> </td>
                                            <td><input READONLY class="form-control" type="number" value="<?=$rows['PROMOTION_BONUS']?>" id="PROMOTION_BONUS_C<?=$count?>" name="PROMOTION_BONUS[]"/></td>
                                            <td><input READONLY class="form-control" type="number" value="<?=$rows['RISK_PREMIUM']?>" id="RISK_PREMIUM_C<?=$count?>" name="RISK_PREMIUM[]"/></td>
                                            <td><input READONLY class="form-control" type="number" value="<?=$rows['SPECIALIZATION_BONUS']?>" id="SPECIALIZATION_BONUS_C<?=$count?>" name="SPECIALIZATION_BONUS_C[]"/></td>
                                            <td><input readonly class="form-control" type="number" value="<?=$rows['SUPERVISORY_BONUS']?>" id="SUPERVISORY_BONUS_C<?=$count?>" name="SUPERVISORY_BONUS_C[]"/></td>
                                            <td><input readonly class="form-control" type="number" value="<?=$rows['COMOANY_ALTERNATIVE']?>" id="COMOANY_ALTERNATIVE<?=$count?>" name="COMOANY_ALTERNATIVE[]"/></td>
                                            <td><input readonly style="background-color: #9a3a42" class="form-control" type="number" value="<?=$TOTAL1?>" id="SUPERVISORY_BONUS_C<?=$count?>" name="SUPERVISORY_BONUS_C[]"/></td>


                                            <td><input  style="background-color: antiquewhite"   class="form-control" type="text"   value="<?=$rows['DEGREE_NAME']?>" id="NEW_DEGREE<?=$count?>" name="NEW_DEGREE[]"/></td>
                                            <td><input  style="background-color: antiquewhite"  class="form-control" type="number" value="<?=$rows['PER_ALLOW']?>" id="PER_ALLOW_IN<?=$count?>" name="PER_ALLOW_IN[]"/></td>



                                        </tr>

                                        <?php $count++;   endforeach; $TOTAL= $SUM_BASIC_SAL+
                                    $SUM_PROFESSION_BONUS+
                                    $SUM_COST_LIVING+
                                    $SUM_PROMOTION_BONUS+
                                    $SUM_RISK_PREMIUM+
                                    $SUM_SPECIALIZATION_BONUS+
                                        $SUM_COMOANY_ALTERNATIVE+
                                    $SUM_SUPERVISORY_BONUS;?>
                                    <tr style="background-color: antiquewhite">
                                        <td hidden="hidden"><input class="checkbox" type="number" value="<?=$rows['SER']?>"id="SER<?=$count?>" name="SER[]"/></td>
                                        <td colspan="2"><input READONLY class="form-control" type="text" value=" الاجمالي "id="FOR_MONTH<?=$count?>" name="FOR_MONTH[]"/></td>
                                        <td><input READONLY class="form-control" type="number" value="<?=$SUM_BASIC_SAL?>" id="BASIC_SAL_C<?=$count?>" name="BASIC_SAL[]"/></td>
                                        <td><input READONLY class="form-control" type="number" value="<?=$SUM_PROFESSION_BONUS?>" id="PROFESSION_BONUS_C<?=$count?>" name="PROFESSION_BONUS[]"/></td>
                                        <td><input READONLY class="form-control" type="number" value="<?=$SUM_COST_LIVING?>" id="COST_LIVING_C<?=$count?>" name="COST_LIVING[]"/> </td>
                                        <td><input READONLY class="form-control" type="number" value="<?=$SUM_PROMOTION_BONUS?>" id="PROMOTION_BONUS_C<?=$count?>" name="PROMOTION_BONUS[]"/></td>
                                        <td><input READONLY class="form-control" type="number" value="<?=$SUM_RISK_PREMIUM?>" id="RISK_PREMIUM_C<?=$count?>" name="RISK_PREMIUM[]"/></td>
                                        <td><input READONLY class="form-control" type="number" value="<?=$SUM_SPECIALIZATION_BONUS?>" id="SPECIALIZATION_BONUS_C<?=$count?>" name="SPECIALIZATION_BONUS_C[]"/></td>
                                        <td><input readonly class="form-control" type="number" value="<?=$SUM_SUPERVISORY_BONUS?>" id="SUPERVISORY_BONUS_C<?=$count?>" name="SUPERVISORY_BONUS_C[]"/></td>
                                        <td><input readonly class="form-control" type="number" value="<?=$SUM_COMOANY_ALTERNATIVE?>" id="SUPERVISORY_BONUS_C<?=$count?>" name="SUPERVISORY_BONUS_C[]"/></td>


                                        <td colspan="3"><input  style="background-color: antiquewhite"   class="form-control" type="text"   value="<?=$TOTAL?> " id="NEW_DEGREE<?=$count?>" name="NEW_DEGREE[]"/></td>



                                    </tr>

                                    </tbody>

                                </table>
                            </form>       <!--end: Datatable -->
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-clean btn-bold btn-upper btn-font-sm" onclick="modal_dissmiss('Modal2')" data-dismiss="modal">إلغاء</button>
                    <button type="button" class="btn btn-warning"  id="addRecordLow" onclick="update_data('_form2')">حفظ البيانات</button>
                </div>
            </div>
        </div>
    </div>

    <!--End myModal  -->

</div>

<?php
$current_date = date('d/m/Y');?>


 //<script type="text/javascript">
 console.log(1)
 var count3=0;
function Add(){
count3 = count3+1;

var html = '<tr> ' +
           '<td><div class="form-group col-md-12"><input onchange="calculate1('+count3+')"  type="text" value= "<?=$current_date?>" <?=$date_attr1?> name="txt_from_date_v[]" id="txt_from_date_v'+count3+'" class="form-control month"></div></td>';
    html+='<td><div class="form-group col-md-12"><input type="text"  onchange="calculate1('+count3+')"   value="<?=$current_date?>" <?=$date_attr1?> name="txt_to_date_v[]" id="txt_to_date_v'+count3+'" class="form-control month" ></div></td>';
    html+='<td><div class="form-group col-md-12"> <input type="number"  value=" "name="txt_day_v'+count3+'" id="txt_day_v'+count3+'"  class="form-control"> </div>    </td>';
    html+='<td><div class="form-group col-md-12"><input type="number"  value=" " name="txt_month_v'+count3+'" id="txt_month_v'+count3+'" class="form-control"> </div>   </td>';
    html+='<td><div class="form-group col-md-12"><input type="number"  value=" " name="txt_year_v'+count3+'" id="txt_year_v'+count3+'"   class="form-control"> </div>   </td>';
    html+='<td><div class="form-group col-md-12"><input type="number"  value=" " name="txt_subsc_v'+count3+'" id="subsc_v'+count3+'"    class="form-control"> </div>   </td></tr>';


 $('#page_tb_v tbody').append(html);




    reBind(1);

  }

 function calculate(){

     showLoading();
     var day4=0;
     var month4=0;
     var year4=0;

     $.ajax({
         url: '<?= base_url("servicelength/service_length/calculate_/")?>'+$('#txt_from_date').val().replaceAll('/','-')+'/'+$('#txt_to_date').val().replaceAll('/','-')
     }).then(function(data) {
         HideLoading();

         if(data.length ==0){}else{

             $('#txt_day1').val(data[0].DAY_);
             $('#txt_month1').val(data[0].MONTH_);
             $('#txt_year1').val(data[0].YEAR_);
             day4+=parseInt(data[0].DAY_);
             month4+=parseInt(data[0].MONTH_);
             year4+=parseInt(data[0].YEAR_);
         }
     });

     console.log($('#txt_from_date').val())
     console.log($('#txt_fex_date').val())

     $.ajax({
         url: '<?= base_url("servicelength/service_length/calculate_/")?>'+$('#txt_fex_date').val().replaceAll('/','-')+'/'+$('#txt_to_date').val().replaceAll('/','-')
     }).then(function(data) {
         HideLoading();

         if(data.length ==0){}else{

             $('#txt_day2').val(data[0].DAY_);
             $('#txt_month2').val(data[0].MONTH_);
             $('#txt_year2').val(data[0].YEAR_);
             day4+=parseInt(data[0].DAY_);
             month4+=parseInt(data[0].MONTH_);
             year4+=parseInt(data[0].YEAR_);
          }
     });
setTimeout(function (){

    console.log(day4)


    $('#txt_day4').val(day4);
    $('#txt_month4').val(month4);
    $('#txt_year4').val(year4);


},1000)

 }
 function calculate1(s) {
     var to_date = $('#txt_to_date_v'+s).val();
    var from_date = $('#txt_from_date_v'+s).val();
    console.log(to_date)
    if (to_date!=undefined > 0 && from_date!=undefined) {
        $.ajax({
            url: '<?= base_url("servicelength/service_length/calculate_/")?>' + from_date.replaceAll('/', '-') + '/' + to_date.replaceAll('/', '-')
        }).then(function (data) {
            HideLoading();

            if (data.length == 0) {
            } else {

                $('#txt_day_v'+s).val(data[0].DAY_);
                $('#txt_month_v'+s).val(data[0].MONTH_);
                $('#txt_year_v'+s).val(data[0].YEAR_);

                console.log('s',data)
            }
        });
    }

 }


 function reBind(s){
    console.log(count3);
     $('#txt_to_date_v'+count3).datetimepicker({
         formatDate: 'dd/mm/yyyy',
         pickTime: false
     });

     $('#txt_from_date_v'+count3).datetimepicker({
         formatDate: 'dd/mm/yyyy',
         pickTime: false
     });
     if(s==undefined){s=0;}
     if(s){




 }}
 function showModal(){


     var html = '<tr> ' +
         '<td><div class="form-group col-md-12"><input onchange="calculate1('+count3+')"  type="text" value= "<?=$current_date?>" <?=$date_attr1?> name="txt_from_date_v[]" id="txt_from_date_v'+count3+'" class="form-control month"></div></td>';
     html+='<td><div class="form-group col-md-12"><input type="text"  onchange="calculate1('+count3+')"   value="<?=$current_date?>" <?=$date_attr1?> name="txt_to_date_v[]" id="txt_to_date_v'+count3+'" class="form-control month" ></div></td>';
     html+='<td><div class="form-group col-md-12"> <input type="number"  value=" "name="txt_day_v'+count3+'" id="txt_day_v'+count3+'"  class="form-control"> </div>    </td>';
     html+='<td><div class="form-group col-md-12"><input type="number"  value=" " name="txt_month_v'+count3+'" id="txt_month_v'+count3+'" class="form-control"> </div>   </td>';
     html+='<td><div class="form-group col-md-12"><input type="number"  value=" " name="txt_year_v'+count3+'" id="txt_year_v'+count3+'"   class="form-control"> </div>   </td>';
     html+='<td><div class="form-group col-md-12"><input type="number"  value=" " name="txt_subsc_v'+count3+'" id="subsc_v'+count3+'"    class="form-control"> </div>   </td></tr>';


     // $('#kt_table_4 tbody').append(html);



     $('#Modal1').modal('show');
 }


 function showModal2(){

     $('#Modal2').modal('show');
 }

 function  update_data(form){
     var inputs = document.querySelectorAll('.group-checkable');
     var BASIC_SAL = document.getElementsByName('BASIC_SAL[]');
     var PROFESSION_BONUS = document.getElementsByName('PROFESSION_BONUS[]');
     var COST_LIVING = document.getElementsByName('COST_LIVING[]');
     var  FOR_MONTH=document.getElementsByName('FOR_MONTH_36[]') ;
     var  PROMOTION_BONUS=document.getElementsByName('PROMOTION_BONUS[]') ;
     var  NEW_DEGREE=document.getElementsByName('NEW_DEGREE[]') ;
     var  PER_ALLOW_IN=document.getElementsByName('PER_ALLOW_IN[]') ;
     var  TXT_EMP_NO=document.getElementById('txt_emp_no').value;

     for (var i = 0; i < inputs.length; i++) {
         if(inputs[i].checked){


             $.post('<?=$update_data_url?>',{EMP_NO:TXT_EMP_NO, MONTHS_36:36, FOR_MONTH:FOR_MONTH[i].value, BASIC_SAL: BASIC_SAL[i].value,
                 PROFESSION_BONUS: PROFESSION_BONUS[i].value ,COST_LIVING:COST_LIVING[i].value,PROMOTION_BONUS:PROMOTION_BONUS[i].value,NEW_DEGREE:NEW_DEGREE[i].value,PER_ALLOW_IN:PER_ALLOW_IN[i].value} ,function(r){
                 $(r).each(function(index, element) {
                     if(element['status'] == 1){
                         success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                         get_to_link( window.location.href );
                     }
                     else{
                         danger_msg('تحذير..',element['o_msgtxt']);

                     }
                 })
             },'json');}

     }



 }

 //</script>


