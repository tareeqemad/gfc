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
$update_data_url = base_url("$MODULE_NAME/$TB_NAME/update_data");
$adopt_url = base_url("$MODULE_NAME/$TB_NAME/adopt");

  $rs =   $get_data[0] ;
$result_fi =   $result_fi[0] ;

  $index_request_url = base_url("$MODULE_NAME/$TB_NAME/index ");
$gfc_domain = gh_gfc_domain();
$report_url = base_url("JsperReport/showreport?sys=hr/salaries");
$create = base_url("servicelength/service_length/Create_Form");
$report_sn = report_sn();
$WORK_PERIOD_DAY=explode('/',$rs['WORK_PERIOD'])[0];
$WORK_PERIOD_MONTH=explode('/',$rs['WORK_PERIOD'])[1];
$WORK_PERIOD_YEAR=explode('/',$rs['WORK_PERIOD'])[2];

$FEX_DATE_DAY=explode('/',$rs['FEX_DATE_PERIOD'])[0];
$FEX_DATE_MONTH=explode('/',$rs['FEX_DATE_PERIOD'])[1];
$FEX_DATE_YEAR=explode('/',$rs['FEX_DATE_PERIOD'])[2];


$EXCLUDED_TERM_DAY=explode('/',$rs['EXCLUDED_TERM'])[0];
$EXCLUDED_TERM_MONTH=explode('/',$rs['EXCLUDED_TERM'])[1];
$EXCLUDED_TERM_YEAR=explode('/',$rs['EXCLUDED_TERM'])[2];


$SERVICE_TOTAL_PERIOD_DAY=explode('/',$rs['SERVICE_TOTAL_PERIOD'])[0];
$SERVICE_TOTAL_PERIOD_MONTH=explode('/',$rs['SERVICE_TOTAL_PERIOD'])[1];
$SERVICE_TOTAL_PERIOD_YEAR=explode('/',$rs['SERVICE_TOTAL_PERIOD'])[2];
$salary_36_month1=$month_36;
$salary_5years=$years5;


$vac_days=0;

$vac_months=0;

$vac_years=0;
$vac_total=0;




$EMP_RET_WORK=explode('/',$result_fi['EMP_RET_WORK']);
$EMP_RETIREMENT_DAY=explode('/',$result_fi['EMP_RETIREMENT'])[0];
$EMP_RETIREMENT_MONTH=explode('/',$result_fi['EMP_RETIREMENT'])[1];
$EMP_RETIREMENT_YEAR=explode('/',$result_fi['EMP_RETIREMENT'])[2];



$EMP_WORK_C_DATE_BE_DAY=explode('/',$result_fi['EMP_WORK_C_DATE_BE'])[0];
$EMP_WORK_C_DATE_BE_MONTH=explode('/',$result_fi['EMP_WORK_C_DATE_BE'])[1];
$EMP_WORK_C_DATE_BE_YEAR=explode('/',$result_fi['EMP_WORK_C_DATE_BE'])[2];


$EMP_WORK_C_DATE_AF_DAY=explode('/',$result_fi['EMP_WORK_C_DATE_AF'])[0];
$EMP_WORK_C_DATE_AF_MONTH=explode('/',$result_fi['EMP_WORK_C_DATE_AF'])[1];
$EMP_WORK_C_DATE_AF_YEAR=explode('/',$result_fi['EMP_WORK_C_DATE_AF'])[2];

$EMP_WORK_TOTAL_DAY=explode('/',$result_fi['EMP_WORK_TOTAL'])[0];
$EMP_WORK_TOTAL_MONTH=explode('/',$result_fi['EMP_WORK_TOTAL'])[1];
$EMP_WORK_TOTAL_YEAR=explode('/',$result_fi['EMP_WORK_TOTAL'])[2];


$EMP_REST_TO_DATE_60_DAY=explode('/',$result_fi['EMP_REST_TO_DATE_60'])[0];
$EMP_REST_TO_DATE_60_MONTH=explode('/',$result_fi['EMP_REST_TO_DATE_60'])[1];
$EMP_REST_TO_DATE_60_YEAR=explode('/',$result_fi['EMP_REST_TO_DATE_60'])[2];


$EMP_YEAR_BOUNS_DAY=explode('/',$result_fi['EMP_YEAR_BOUNS'])[0];
$EMP_YEAR_BOUNS_MONTH=explode('/',$result_fi['EMP_YEAR_BOUNS'])[1];
$EMP_YEAR_BOUNS_YEAR=explode('/',$result_fi['EMP_YEAR_BOUNS'])[2];
 ?>

<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
    <div class="left-content">
        <span class="main-content-title mg-b-0 mg-b-lg-1">استمارة حصر الخدمة </span>
    </div>
    <div class="justify-content-center mt-2">
        <ol class="breadcrumb">
            <li class="breadcrumb-item tx-15"><a href="javascript:void(0);">النظام الاداري</a></li>
            <li class="breadcrumb-item active" aria-current="page">استمارة حصر الخدمة</li>
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
                <form id="Service_length_form1" method="post" action="<?=$create?>" role="form"
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
                                        <label class="control-label">رقم الطلب</label>
                                        <div>
                                            <input type="number" value="<?= $rs['SER']; ?>" name="ser"
                                                   id="txt_ser" readonly class="form-control ">
                                        </div>
                                    </div>

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
                                        <label class="control-label">العلاوات الدورية</label>
                                        <div>
                                            <input type="text" value="<?=$rs['HIRE_YEARS']?>" name="remain_bonuses"
                                                   id="txt_hire_years" readonly class="form-control ">

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
                                                   id="txt_end_date"   class="form-control " onchange="date_changed()">

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
                                 </div>
                            </div>
                        </div>
                    </div>
                    <hr>

                   <div class="row">

                    <div class="panel panel-primary col-md-6" >
                        <div class="panel-body">
                            <h5 class="text-on-pannel text-primary"><strong class="text-uppercase">بيانات مدة الخدمة
                                </strong></h5>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">

                                        <table class="table" id="page_tb" data-container="container" style="background: antiquewhite;">
                                            <thead>
                                            <tr>
                                                <td colspan="2"></td>
                                            <tr>
                                                <th style="width: 15%;">مدة الخدمة</th>
                                                <th style="width: 10%;">يوم</th>
                                                <th style="width: 10%;">شهر</th>
                                                <th style="width: 10%;">سنة</th>


                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td class="row">
                                                    <div class="form-group col-md-6">
                                                        <label class="control-label">من </label>
                                                        <input type="text"
                                                               value="<?= $rs['FEX_DATE']; ?>" <?= $date_attr ?>
                                                               name="txt_from_date" id="txt_from_date"
                                                               class="form-control">

                                                    </div>

                                                    <div class="form-group col-md-6">

                                                        <label class="control-label">حتى </label>
                                                        <input type="text"
                                                               readonly
                                                               value="<?= $rs['WORK_END_DATE']; ?>" <?= $date_attr ?>
                                                               name="txt_to_date" id="txt_to_date"
                                                               class="form-control">
                                                    </div>
                                                </td>

                                                <td  >

                                                    <div class="form-group col-md-12">

                                                        <input type="number"
                                                               value="<?= $WORK_PERIOD_DAY; ?>"
                                                               name="WORK_PERIOD_DAY" id="WORK_PERIOD_DAY"
                                                               class="form-control">
                                                    </div>

                                                </td>

                                                <td>


                                                    <div class="form-group col-md-12">

                                                        <input type="number"
                                                               value="<?= $WORK_PERIOD_MONTH; ?>"
                                                               name="WORK_PERIOD_MONTH" id="WORK_PERIOD_MONTH"
                                                               class="form-control">
                                                    </div>
                                                </td>

                                                <td  >
                                                    <div class="form-group col-md-12">


                                                        <input type="number"
                                                               value="<?= $WORK_PERIOD_YEAR; ?>"
                                                               name="WORK_PERIOD_YEAR" id="WORK_PERIOD_YEAR"
                                                               class="form-control">
                                                    </div>   </td>
                                            </tr>
                                            <tr>
                                                <td >


                                                    <div class="form-group  ">

                                                        <label class="control-label">مدة الخدمة السابقة على إنتفاعه بالقانون </label>

                                                    </div>
                                                </td>

                                                <td  >

                                                    <div class="form-group col-md-12">

                                                        <input type="number"
                                                               value="<?= $FEX_DATE_DAY ?>"
                                                               name="FEX_DATE_DAY" id="FEX_DATE_DAY"
                                                               class="form-control">
                                                    </div>

                                                </td>

                                                <td>


                                                    <div class="form-group col-md-12">

                                                        <input type="number"
                                                               value="<?= $FEX_DATE_MONTH ?>"
                                                               name="FEX_DATE_MONTH" id="FEX_DATE_MONTH"
                                                               class="form-control">
                                                    </div>
                                                </td>

                                                <td  >
                                                    <div class="form-group col-md-12">


                                                        <input type="number"
                                                               value="<?= $FEX_DATE_YEAR ?>"
                                                               name="FEX_DATE_YEAR" id="FEX_DATE_YEAR"
                                                               class="form-control">
                                                    </div>   </td>
                                            </tr>



                                            <tr>
                                                <td >


                                                    <div class="form-group col-md-12">

                                                        <label class="control-label">مدة مستبعدة </label>

                                                    </div>
                                                </td>

                                                <td  >

                                                    <div class="form-group col-md-12">

                                                        <input type="number"
                                                               value="<?= $EXCLUDED_TERM_DAY; ?>"
                                                               name="EXCLUDED_TERM_DAY" id="EXCLUDED_TERM_DAY"
                                                               class="form-control">
                                                    </div>

                                                </td>

                                                <td>


                                                    <div class="form-group col-md-12">

                                                        <input type="number"
                                                               value="<?= $EXCLUDED_TERM_MONTH; ?>"
                                                               name="EXCLUDED_TERM_MONTH" id="EXCLUDED_TERM_MONTH"
                                                               class="form-control">
                                                    </div>
                                                </td>

                                                <td  >
                                                    <div class="form-group col-md-12">


                                                        <input type="number"
                                                               value="<?= $EXCLUDED_TERM_YEAR; ?>"
                                                               name="EXCLUDED_TERM_YEAR" id="EXCLUDED_TERM_YEAR"
                                                               class="form-control">
                                                    </div>   </td>
                                            </tr>

                                            <tr>
                                                <td >


                                                    <div class="form-group col-md-12">

                                                        <label class="control-label">صافي مدة الخدمة </label>

                                                    </div>
                                                </td>

                                                <td  >

                                                    <div class="form-group col-md-12">

                                                        <input type="number"
                                                               value="<?= $SERVICE_TOTAL_PERIOD_DAY; ?>"
                                                               name="SERVICE_TOTAL_PERIOD_DAY" id="SERVICE_TOTAL_PERIOD_DAY"
                                                               class="form-control">
                                                    </div>

                                                </td>

                                                <td>


                                                    <div class="form-group col-md-12">

                                                        <input type="number"
                                                               value="<?= $SERVICE_TOTAL_PERIOD_MONTH; ?>"
                                                               name="SERVICE_TOTAL_PERIOD_MONTH" id="SERVICE_TOTAL_PERIOD_MONTH"
                                                               class="form-control">
                                                    </div>
                                                </td>

                                                <td  >
                                                    <div class="form-group col-md-12">


                                                        <input type="number"
                                                               value="<?= $SERVICE_TOTAL_PERIOD_YEAR; ?>"
                                                               name="SERVICE_TOTAL_PERIOD_YEAR" id="SERVICE_TOTAL_PERIOD_YEAR"
                                                               class="form-control">
                                                    </div>   </td>
                                            </tr>


                                            </tbody></table>
                                        <button type="button" onclick='javascript:calculate();' id="btn_adopt_start_2" class="btn btn-success">
                                            <i class="fa fa-check"></i>احتساب </button>

                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>

                     <div class="panel panel-primary col-md-6">
                        <div class="panel-body">
                            <h5 class="text-on-pannel text-primary"><strong class="text-uppercase">المرتب الخاضع للتأمين والمعاشات
                                </strong></h5>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class=" ">
                                        <div class="row">

                                        <div class="form-group col-md-4">
                                            <label class="control-label">الاساسي </label>
                                            <input readonly type="number"
                                                   value="<?= $rs['BASIC_SALARY']; ?>"
                                                   name="txt_basic_salary" id="txt_basic_salary"
                                                   class="form-control">
                                        </div>

                                        <div class="form-group  col-md-4">
                                            <label class="control-label">نسبة طبيعة العمل</label>
                                            <input readonly type="text" value="<?= $rs['WORK_RATIO']; ?>"
                                                   name="txt_work_ratio_1" id="txt_work_ratio_1"
                                                   class="form-control ">
                                        </div>


                                        <div class="form-group  col-md-4">
                                            <label class="control-label">تكملة 1</label>
                                            <input readonly type="number" value="<?= $rs['JOB_ALLOWNCE_PCT']; ?>"
                                                   name="t1" id="txt_t1"
                                                   class="form-control ">
                                        </div>
                                        </div>
                                        <div class="row">

                                        <div class="form-group  col-md-4">
                                            <label class="control-label">تكملة 2</label>
                                            <input readonly type="number" value="<?= $rs['JOB_ALLOWNCE_PCT_EXTRA']; ?>"
                                                   name="t2" id="txt_t2"
                                                   class="form-control ">
                                        </div>

                                        <div class="form-group  col-md-4">
                                            <label class="control-label">علاوة ترقية</label>
                                            <input readonly type="number" value="<?= $rs['PROMOTION_BONUS']; ?>"
                                                   name="promotion_bonus" id="txt_promotion_bonus"
                                                   class="form-control ">
                                        </div>


                                        <div class="form-group  col-md-4">
                                            <label class="control-label">علاوة غلاء المعيشة</label>
                                            <input readonly type="number" value="<?= $rs['EXPENSIVE_LIFE']; ?>"
                                                   name="expensive_life" id="txt_expensive_life"
                                                   class="form-control ">
                                        </div>

                                        </div>
                                        <div class="row">

                                        <div class="form-group  col-md-4">
                                            <label class="control-label">علاوة المخاطرة</label>
                                            <input readonly type="text" value="<?= $rs['BOUNS_RISK']; ?>"
                                                   name="txt_bonus_risk" id="txt_bonus_risk"
                                                   class="form-control ">
                                        </div>

                                        <div class="form-group  col-md-4">
                                            <label class="control-label">إجمالي الراتب الخاضع للتأمين</label>
                                            <input readonly type="number" value="<?= $rs['SALARY_TO_INSURANCE']; ?>"
                                                   name="salary_insurance" id="txt_salary_insurance"
                                                   class="form-control ">
                                        </div>
                                        <div class="form-group  col-md-4">
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

                    </div>
                </div>
                    <div class="panel panel-primary">
                        <div class="panel-body">
                            <h5 class="text-on-pannel text-primary"><strong class="text-uppercase">
                                    مدة الاجازة الدراسية والعتيادية بدون راتب
                                </strong></h5>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">

                                        <table class="table" id="page_tb_v" data-container="container" style="background: antiquewhite;">
                                            <thead>
                                            <tr>
                                                <th style="width: 30%;">من تاريخ</th>
                                                <th style="width: 30%;">الى تاريخ</th>
                                                <th style="width: 30%;">مدة الاجازة</th>
                                                <th hidden="hidden" style="width: 10%;">يوم</th>
                                                <th hidden="hidden" style="width: 10%;">شهر</th>
                                                <th hidden="hidden" style="width: 10%;">سنة</th>
                                                <th hidden="hidden" style="width: 10%;">هل اشترك عنها</th>


                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr style="display: none">
                                                <td  >
                                                    <div class="form-group col-md-12">
                                                         <input type="text"
                                                               value="<?= $rs['FROM_DATE_V0']; ?>" <?= $date_attr ?>
                                                               name="txt_from_date_v[]" id="txt_from_date_v"
                                                               class="form-control" onchange="calculate1(0)">

                                                    </div>


                                                </td>

                                                <td  >

                                                    <div class="form-group col-md-12">

                                                         <input type="text"
                                                               value="<?= $rs['TO_DATE_V0']; ?>" <?= $date_attr ?>
                                                               name="txt_to_date_v[]" id="txt_to_date_v"
                                                               class="form-control" onchange="calculate1(0)">
                                                    </div>

                                                </td>

                                                <td>


                                                    <div class="form-group col-md-12">

                                                        <input type="number"
                                                               value="<?= $rs['DAY_V0']; ?>"
                                                               name="txt_day_v0" id="txt_day_v"
                                                               class="form-control">
                                                    </div>
                                                </td>
                                                <td>


                                                    <div class="form-group col-md-12">

                                                        <input type="number"
                                                               value="<?= $rs['MONTH_V']; ?>"
                                                               name="txt_month_v0" id="txt_month_v"
                                                               class="form-control">
                                                    </div>
                                                </td>

                                                <td  >
                                                    <div class="form-group col-md-12">


                                                        <input type="number"
                                                               value="<?= $rs['YEAR_V']; ?>"
                                                               name="txt_year_v0" id="txt_year_v"
                                                               class="form-control">
                                                    </div>   </td>


                                                <td  >
                                                    <div class="form-group col-md-12">


                                                        <input  type="number"
                                                               value="<?= $rs['SUBSC_V0']; ?>"
                                                               name="txt_subsc_v0" id="subsc_v"
                                                               class="form-control">
                                                    </div>   </td>
                                            </tr>
<?php
$count_vacation=-1;
$total_vacation_days=0;
foreach($VACATION as $vac) {
    $count_vacation++;
    $total_vacation_days+=  $vac['VAC_DURATION'];
?>
    <tr>
        <td  >
            <div class="form-group col-md-12">
                <input type="text"
                       value="<?= $vac['VAC_DATE']; ?>" <?= $date_attr ?>
                       name="txt_from_date_v[]" id="txt_from_date_v<?=$count_vacation?>"
                       class="form-control" onchange="calculate1(0)">

            </div>


        </td>

        <td  >

            <div class="form-group col-md-12">

                <input type="text"
                       value="<?= $vac['VAC_END_DATE']; ?>" <?= $date_attr ?>
                       name="txt_to_date_v[]" id="txt_to_date_v<?=$count_vacation?>"
                       class="form-control" onchange="calculate1(0)">
            </div>

        </td>
        <td>


            <div class="form-group col-md-12">

                <input type="number"
                       value="<?= $vac['VAC_DURATION']; ?>"
                       name="txt_day_v[]" id="txt_day_v<?=$count_vacation?>"
                       class="form-control">
            </div>
        </td>
        <td hidden="hidden">


            <div class="form-group col-md-12">

                <input type="number"
                       value="<?= $rs['DAY_V0']; ?>"
                       name="txt_day_v[]" id="txt_day_v<?=$count_vacation?>"
                       class="form-control">
            </div>
        </td>
        <td hidden="hidden">


            <div class="form-group col-md-12">

                <input type="number"
                       value="<?= $rs['MONTH_V']; ?>"
                       name="txt_month_v[]" id="txt_month_v<?=$count_vacation?>"
                       class="form-control">
            </div>
        </td>

        <td hidden="hidden" >
            <div class="form-group col-md-12">


                <input type="number"
                       value="<?= $rs['YEAR_V']; ?>"
                       name="txt_year_v[]" id="txt_year_v<?=$count_vacation?>"
                       class="form-control">
            </div>   </td>


        <td hidden="hidden"  >
            <div class="form-group col-md-12">


                <input  type="number"
                        value="<?= $rs['SUBSC_V0']; ?>"
                        name="txt_subsc_v[]" id="subsc_v<?=$count_vacation?>"
                        class="form-control">
            </div>   </td>
    </tr>

                                            <?php }?>

                                            <?php
                                            $vac_days = $total_vacation_days;
                                            $vac_years = intval($vac_days / 365);
                                            $vac_days = $vac_days % 365;

                                            $vac_months = intval($vac_days / 30);
                                            $vac_days = $vac_days % 30;
                                            $vac_total=$total_vacation_days;

                                            ?>
                                            </tbody>
                                        <tfoot>
                                        <tr>
                                            <td colspan="5">

                                                <a onclick=" Add();" href="javascript:;"><i class="glyphicon glyphicon-plus">
                                                    </i>جديد</a>


                                            </td>
                                            <td colspan="5">

                                                <a onclick=" calculate_vacation();" href="javascript:;" class="btn btn-success"><i class="fa fa-check">
                                                    </i>احتساب</a>


                                            </td>

                                        </tr>
                                        </tfoot></table>


                                    </div>

                        </div>
                    </div>
                    <br>




                        </div>     </div>
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
                   <div class="row">

                    <div class="panel panel-primary col-md-6" >
                        <div class="panel-body"style=" background: #d7e5fa;">
                            <h5 class="text-on-pannel text-primary"><strong class="text-uppercase">اجمالي الراتب الأساسي ل 36 شهر قبل التقاعد
                                </strong></h5>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="">
                                    <div class="row">
                                        <div class="form-group  col-md-3">
                                            <label class="control-label"> الراتب الأساسي </label>
                                            <input readonly type="number"
                                                   style="direction: ltr;text-align: end;"
                                                   value="<?= $result_fi['BASIC_SALARY']; ?>"
                                                   name="BASIC_SALARY" id="txt_basic_salary"
                                                   class="form-control ">
                                        </div>

                                        <div class="form-group  col-md-4">
                                            <label class="control-label"> علاوة المهنة </label>
                                            <input readonly type="number"
                                                   style="direction: ltr;text-align: end;"
                                                   value="<?= $result_fi['PERIODICAL_SALARY']; ?>"
                                                   name="promotion_bonus" id="txt_promotion_bonus"
                                                   class="form-control ">
                                        </div>

                                        <div class="form-group  col-md-4">
                                            <label class="control-label"> علاوة غلاء المعيشة</label>
                                            <input readonly type="number" style="direction: ltr;text-align: end;"
                                                   value="<?= $result_fi['BOUNS_SALARY']; ?>"
                                                   name="expensive_life" id="txt_expensive_life"
                                                   class="form-control ">
                                        </div>
                                    </div>
                                        <div class="row">

                                        <div class="form-group  col-md-3">
                                            <label class="control-label"> علاوة شخصية</label>
                                            <input readonly type="number"
                                                   style="direction: ltr;text-align: end;"
                                                   value="<?= $result_fi['PERSONAL_BOUNS']; ?>"
                                                   name="salary_insurance" id="txt_salary_insurance"
                                                   class="form-control ">
                                        </div>

                                        <div class="form-group col-md-4">
                                            <label class="control-label">نسبة المعاش قبل 01/09/2006 </label>
                                            <input readonly type="number"
                                                   style="direction: ltr;text-align: end;"
                                                   value="<?= $result_fi['SALARY_2_5_RATE']; ?>"
                                                   name="SALARY_2_5_RATE" id="txt_salary_2_5_rate"
                                                   class="form-control">
                                        </div>

                                        <div class="form-group  col-md-4">
                                            <label class="control-label">نسبة المعاش بعد 01/09/2006</label>
                                            <input readonly type="text"
                                                   style="direction: ltr;text-align: end;"
                                                   value="<?= $result_fi['SALARY_2_RATE']; ?>"
                                                   name="SALARY_2_RATE" id="txt_salary_2_rate"
                                                   class="form-control ">
                                        </div>

                                        </div>
                                        <div class="row">

                                        <div class="form-group  col-md-5">
                                            <label class="control-label">اجمالي نسبة المعاش</label>
                                            <input readonly type="number"
                                                   style="direction: ltr;text-align: end;"
                                                   value="<?= $result_fi['TOTAL_SALARY_RATE']; ?>"
                                                   name="TOTAL_SALARY_RATE" id="txt_total_salary_rate"
                                                   class="form-control ">
                                        </div>


                                        <div class="form-group  col-md-5">
                                            <label class="control-label">خصم التأمين الصحي</label>
                                            <input readonly type="text"
                                                   style="direction: ltr;text-align: end;"
                                                   value="<?= $result_fi['HEALTH_INSURANCE_DISCOUNT']; ?>"
                                                   name="notes" id="txt_notes"
                                                   class="form-control ">
                                        </div>
                                        </div>



                                    </div>

                                </div>
                            </div>

                        </div>

                    </div>
                    <div class="panel panel-primary col-md-6" >
                        <div class="panel-body" style=" background: antiquewhite;">
                            <h5 class="text-on-pannel text-primary"><strong class="text-uppercase">تفاصيل الراتب التقاعدي ل 36 شهر قبل تاريخ التقاعد
                                </strong></h5>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class=" ">
                                        <div class=" row">

                                        <div class="form-group col-md-5">
                                            <label class="control-label">اجمالي راتب الموظف </label>
                                            <input readonly type="number"
                                                   style="direction: ltr;text-align: end;"
                                                   value="<?= $result_fi['TOTAL_EMP_SALARY_60']; ?>"
                                                   name="TOTAL_EMP_SALARY_60" id="txt_total_emp_salary_60"
                                                   class="form-control">
                                        </div>

                                        <div class="form-group  col-md-5">
                                            <label class="control-label">اجمالي الراتب التقاعدي </label>
                                            <input readonly type="text"
                                                   style="direction: ltr;text-align: end;"
                                                   value="<?= $result_fi['TOTAL_SALARY_RETIREMENT_5']; ?>"
                                                   name="TOTAL_SALARY_RETIREMENT_5" id="txt_total_salary_retirement_5"
                                                   class="form-control ">
                                        </div>
                                        </div>
                                        <div class=" row">

                                        <div class="form-group  col-md-5">
                                            <label class="control-label">الفرق بين الراتب الحالي والراتب التقاعدي</label>
                                            <input readonly type="number"
                                                   style="direction: ltr;text-align: end;"
                                                   value="<?= $result_fi['SALARY_DIFF']; ?>"
                                                   name="SALARY_DIFF" id="txt_salary_diff"
                                                   class="form-control ">
                                        </div>
                                        <div class="form-group  col-md-5">
                                            <label class="control-label">الاجمالي</label>
                                            <input readonly type="number"
                                                   style="direction: ltr;text-align: end;"
                                                   value="<?= $result_fi['TOTAL_SALARY_RETIREMENT']; ?>"
                                                   name="TOTAL_SALARY_RETIREMENT" id="txt_total_salary_retirement"
                                                   class="form-control ">
                                        </div>
                                        </div>

                                        <div class=" row">

                                        <div class="form-group  col-md-5">
                                            <label class="control-label">صافي الراتب التقاعدي</label>
                                            <input readonly type="number"
                                                   style="direction: ltr;text-align: end;"
                                                   value="<?= $result_fi['NET_SALARY_RETIREMENT']; ?>"
                                                   name="NET_SALARY_RETIREMENT" id="txt_net_salary_retirement"
                                                   class="form-control ">
                                        </div>

                                        </div>








                                    </div>

                                </div>
                            </div>

                        </div>

                    </div>
                   </div>
                    <div class="modal-footer">

                        <button hidden="hidden" type="button" data-action="submit" id="sava_btn" class="btn btn-primary" onclick="Create()">حفظ البيانات</button>
                      <?php if($result_fi['ADOPT']==1){?>
                        <button   type="button" data-action="submit" id="adopt_2" class="btn btn-primary" onclick="Adopt(2)">اعتماد الادارية</button>
                        <?php }?>

                        <?php if($result_fi['ADOPT']==2){?>

                        <button   type="button" data-action="submit" id="adopt_3" class="btn btn-secondary" onclick="Adopt(3)">اعتماد المالية</button>
                        <?php }?>
                        <?php if($result_fi['ADOPT']==3){?>

                        <button   type="button" data-action="submit" id="adopt_4" class="btn btn-success" onclick="Adopt(4)">اعتماد الرقابة</button>
                        <?php }?>
                    </div>

                    <div hidden="hidden" class="modal-footer">

                        <a  href="<?=base_url('servicelength/service_length/CreateFi/'.$rs['NO'])?>" data-action="submit" class="btn btn-primary"  ">الاستمارة المالية</a>
                    </div>


                </form>
            </div></div></div></div><!------------------------->

    <!--start myModal     -->
     <!--End myModal  -->

</div>
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
                        <form id="<?= $TABLE_NAME; ?>_form36" name="<?= $TABLE_NAME; ?>_form3"  class="horizontal-form <?= $TABLE_NAME; ?>_form3" method="post"  action="<?=$addRecord_legal_low;?>" novalidate="novalidate" accept-charset="UTF-16LE" enctype="multipart/form-data">
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
                                        <td hidden="hidden"><input class="form-control" type="number" value="<?=$rows['SER']?>"id="SER<?=$count?>" name="SER_36[]"/></td>
                                        <td ><input style="width: 44px"  type="checkbox" class="group-checkable" data-set="#checkbox .checkboxes" name="checkbox[]" id="checkbox<?=$count?>" value="<?=$count?>" /></td>
                                        <td><input class="form-control" type="number" value="<?=$rows['FOR_MONTH']?>"id="FOR_MONTH_36_<?=$count?>" name="FOR_MONTH_36[]"/></td>
                                        <td><input READONLY class="form-control" type="number" value="<?=$rows['BASIC_SAL_C']?>" id="BASIC_SAL_C<?=$count?>" name="BASIC_SAL_C_36[]"/></td>
                                        <td><input class="form-control" type="number" value="<?=$rows['BASIC_SAL']?>" id="BASIC_SAL_36<?=$count?>" name="BASIC_SAL_36[]"/></td>
                                        <td><input READONLY class="form-control" type="number" value="<?=$rows['PROFESSION_BONUS_C']?>" id="PROFESSION_BONUS_C_36<?=$count?>" name="PROFESSION_BONUS_C_36[]"/></td>
                                        <td><input class="form-control" type="number" value="<?=$rows['PROFESSION_BONUS']?>" id="PROFESSION_BONUS_36<?=$count?>" name="PROFESSION_BONUS_36[]"/></td>
                                        <td><input READONLY class="form-control" type="number" value="<?=$rows['COST_LIVING_C']?>" id="COST_LIVING_C_36<?=$count?>" name="COST_LIVING_C_36[]"/> </td>
                                        <td><input class="form-control" type="number" value="<?=$rows['COST_LIVING']?>" id="COST_LIVING_36<?=$count?>" name="COST_LIVING_36[]"/> </td>
                                        <td><input READONLY class="form-control" type="number" value="<?=$rows['PROMOTION_BONUS_C']?>" id="PROMOTION_BONUS_C_36<?=$count?>" name="PROMOTION_BONUS_C_36[]"/></td>
                                        <td><input class="form-control" type="number" value="<?=$rows['PROMOTION_BONUS']?>" id="PROMOTION_BONUS_36<?=$count?>" name="PROMOTION_BONUS_36[]"/></td>
                                        <td><input READONLY class="form-control" type="number" value="<?=$rows['JOB_ALLOWNCE_PCT_EXTRA_C']?>" id="JOB_ALLOWNCE_PCT_EXTRA_C_36<?=$count?>" name="JOB_ALLOWNCE_PCT_EXTRA_C_36[]"/></td>
                                        <td><input class="form-control" type="number" value="<?=$rows['JOB_ALLOWNCE_PCT_EXTRA']?>" id="JOB_ALLOWNCE_PCT_EXTRA_36<?=$count?>" name="JOB_ALLOWNCE_PCT_EXTRA_36[]"/></td>
                                        <td><input READONLY class="form-control" type="number" value="<?=$rows['COMOANY_ALTERNATIVE_C']?>" id="COMOANY_ALTERNATIVE_C_36<?=$count?>" name="COMOANY_ALTERNATIVE_C_36[]"/></td>
                                        <td><input class="form-control" type="number" value="<?=$rows['COMOANY_ALTERNATIVE']?>" id="COMOANY_ALTERNATIVE_36<?=$count?>" name="COMOANY_ALTERNATIVE_36[]"/></td>
                                        <td><input READONLY class="form-control" type="number" value="<?=$rows['SPECIALIZATION_BONUS_C']?>" id="SPECIALIZATION_BONUS_C_36<?=$count?>" name="SPECIALIZATION_BONUS_C_36[]"/></td>
                                        <td><input class="form-control" type="number" value="<?=$rows['SPECIALIZATION_BONUS']?>" id="SPECIALIZATION_BONUS_36<?=$count?>" name="SPECIALIZATION_BONUS_36[]"/></td>

                                        <td><input  style="background-color: antiquewhite"   class="form-control" type="text"   value="<?=$rows['DEGREE_NAME']?>" id="NEW_DEGREE_36<?=$count?>" name="NEW_DEGREE_36[]"/></td>
                                        <td><input  style="background-color: antiquewhite"  class="form-control" type="number" value="<?=$rows['PER_ALLOW']?>" id="PER_ALLOW_IN_36<?=$count?>" name="PER_ALLOW_IN_36[]"/></td>


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
                                    <td COLSPAN="2"><input readonly style="text-align: end;" class="form-control" type="number" value="<?=round(($BASIC_SAL/36),2)?>" id="BASIC_SAL<?=$count?>" name="BASIC_SAL_36[]"/></td>
                                    <td COLSPAN="2"><input readonly style="text-align: end;" class="form-control" type="number" value="<?=round($PROFESSION_BONUS/36,2)?>" id="PROFESSION_BONUS<?=$count?>" name="PROFESSION_BONUS_36[]"/></td>
                                    <td COLSPAN="2"><input readonly style="text-align: end;" class="form-control" type="number" value="<?=round($COST_LIVING,2)?>" id="COST_LIVING<?=$count?>" name="COST_LIVING_36[]"/> </td>
                                    <td COLSPAN="2"><input readonly style="text-align: end;" class="form-control" type="number" value="<?=round($PROMOTION_BONUS/36,2)?>" id="PROMOTION_BONUS<?=$count?>" name="PROMOTION_BONUS_36[]"/></td>
                                    <td COLSPAN="2"><input readonly style="text-align: end;" class="form-control" type="number" value="<?=round($JOB_ALLOWNCE_PCT_EXTRA/36,2)?>" id="JOB_ALLOWNCE_PCT_EXTRA<?=$count?>" name="JOB_ALLOWNCE_PCT_EXTRA_36[]"/></td>
                                    <td COLSPAN="2"><input readonly style="text-align: end;" class="form-control" type="number" value="<?=round($COMOANY_ALTERNATIVE/36,2)?>" id="COMOANY_ALTERNATIVE<?=$count?>" name="COMOANY_ALTERNATIVE_36[]"/></td>
                                    <td COLSPAN="2"><input readonly style="text-align: end;" class="form-control" type="number" value="<?=round($SPECIALIZATION_BONUS/36,2)?>" id="SPECIALIZATION_BONUS<?=$count?>" name="SPECIALIZATION_BONUS_36[]"/></td>



                                </tr>
                                <tr>
                                    <td   COLSPAN="15"><input style="text-align: center;" class="form-control" type="number" value="<?=$TOTAL?>" id="TOTAL_36<?=$count?>" name="TOTAL_36[]"/></td>

                                </tr>
                                </tbody>

                            </table>
                        </form>       <!--end: Datatable -->
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-clean btn-bold btn-upper btn-font-sm" data-dismiss="modal">إلغاء</button>
                <button   type="button" class="btn btn-warning"  id="addRecordLow" onclick="update_data_36('_form36')">حفظ البيانات</button>
                <button type="button" class="btn btn-success"  id="addRecordLow" onclick="print_report('pdf','authority_salary_calc_')">طباعةpdf</button>
                <button type="button" class="btn btn-secondary"  id="addRecordLow" onclick="print_report('xls','authority_salary_calc_')">طباعةxls</button>
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
                                    <th style="width: 3%"># </th>
                                    <th style="">الشهر </th>
                                    <th style="">الراتب الأساسي </th>
                                     <th style="">علاوة المهنة </th>
                                     <th style="">غلاء المعيشة </th>
                                     <th style="">الترقية </th>
                                     <th style="">علاوة المخاطرة </th>
                                     <th style="">علاوة التخصص </th>
                                     <th style="">بدلات ثابتة </th>
                                    <th style="background-color: antiquewhite">الدرجة </th>
                                    <th style="background-color: antiquewhite">الأقدمية </th>
                                    <th hidden="hidden" style="background-color: antiquewhite">علاوة الترقية </th>

                                </tr>
                                </thead>
                                <tbody>
                                <?php $count=1;
                                $SUM_BASIC_SAL=0;
                                $SUM_PROFESSION_BONUS=0;
                                $SUM_COST_LIVING=0;
                                $SUM_PROMOTION_BONUS=0;
                                $SUM_RISK_PREMIUM=0;
                                $SUM_SPECIALIZATION_BONUS=0;
                                $SUM_SUPERVISORY_BONUS=0;
                                foreach($salary_5years as $rows) :
                                    if($rows['IS_CALC']==1){
                                    $SUM_BASIC_SAL+=$rows['BASIC_SAL'];
                                    $SUM_PROFESSION_BONUS+=$rows['PROFESSION_BONUS'];
                                    $SUM_COST_LIVING+=$rows['COST_LIVING'];
                                    $SUM_PROMOTION_BONUS+=$rows['PROMOTION_BONUS'];
                                    $SUM_RISK_PREMIUM+=$rows['RISK_PREMIUM'];
                                    $SUM_SPECIALIZATION_BONUS+=$rows['SPECIALIZATION_BONUS'];
                                    $SUM_SUPERVISORY_BONUS+=$rows['SUPERVISORY_BONUS'];
                                        $TOTAL=0;}
                                    ?>
                                    <tr <?=$rows['IS_CALC']==1?'style="background-color: #e29fa5"':''?>>
                                         <td hidden="hidden"><input class="checkbox" type="number" value="<?=$rows['SER']?>"id="SER<?=$count?>" name="SER[]"/></td>
                                        <td ><input readonly style="width: 44px" type="number" class="form-control"   value="<?=$count?>" /></td>
                                        <td ><input style="width: 44px"  type="checkbox" class="group-checkable" data-set="#checkbox .checkboxes" name="checkbox_5[]" id="checkbox<?=$count?>" value="<?=$count?>" /></td>
                                        <td><input class="form-control" type="number" value="<?=$rows['FOR_MONTH']?>"id="FOR_MONTH<?=$count?>" name="FOR_MONTH_5[]"/></td>
                                        <td><input READONLY class="form-control" type="number" value="<?=$rows['BASIC_SAL']?>" id="BASIC_SAL_C<?=$count?>" name="BASIC_SAL[]"/></td>
                                         <td><input READONLY class="form-control" type="number" value="<?=$rows['PROFESSION_BONUS']?>" id="PROFESSION_BONUS_C<?=$count?>" name="PROFESSION_BONUS[]"/></td>
                                         <td><input READONLY class="form-control" type="number" value="<?=$rows['COST_LIVING']?>" id="COST_LIVING_C<?=$count?>" name="COST_LIVING[]"/> </td>
                                         <td><input READONLY class="form-control" type="number" value="<?=$rows['PROMOTION_BONUS']?>" id="PROMOTION_BONUS_C<?=$count?>" name="PROMOTION_BONUS[]"/></td>
                                         <td><input READONLY class="form-control" type="number" value="<?=$rows['RISK_PREMIUM']?>" id="RISK_PREMIUM_C<?=$count?>" name="RISK_PREMIUM[]"/></td>
                                         <td><input READONLY class="form-control" type="number" value="<?=$rows['SPECIALIZATION_BONUS']?>" id="SPECIALIZATION_BONUS_C<?=$count?>" name="SPECIALIZATION_BONUS_C[]"/></td>
                                         <td><input readonly class="form-control" type="number" value="<?=$rows['SUPERVISORY_BONUS']?>" id="SUPERVISORY_BONUS_C<?=$count?>" name="SUPERVISORY_BONUS_C[]"/></td>


                                        <td><input  style="background-color: antiquewhite"   class="form-control" type="text"   value="<?=$rows['DEGREE_NAME']?>" id="NEW_DEGREE<?=$count?>" name="NEW_DEGREE[]"/></td>
                                        <td><input  style="background-color: antiquewhite"  class="form-control" type="number" value="<?=$rows['PER_ALLOW']?>" id="PER_ALLOW_IN<?=$count?>" name="PER_ALLOW_IN[]"/></td>



                                    </tr>

                                    <?php $count++;  endforeach;
                                $TOTAL= $SUM_BASIC_SAL+
                                    $SUM_PROFESSION_BONUS+
                                    $SUM_COST_LIVING+
                                    $SUM_PROMOTION_BONUS+
                                    $SUM_RISK_PREMIUM+
                                    $SUM_SPECIALIZATION_BONUS+
                                    $SUM_SUPERVISORY_BONUS;?>
                                <tr style="background-color: antiquewhite">
                                    <td hidden="hidden"><input class="checkbox" type="number" value="<?=$rows['SER']?>"id="SER<?=$count?>" name="SER[]"/></td>
                                     <td colspan="3"><input READONLY class="form-control" type="text" value=" الاجمالي "id="FOR_MONTH<?=$count?>" name="FOR_MONTH[]"/></td>
                                    <td><input READONLY class="form-control" type="number" value="<?=$SUM_BASIC_SAL?>" id="BASIC_SAL_C<?=$count?>" name="BASIC_SAL[]"/></td>
                                    <td><input READONLY class="form-control" type="number" value="<?=$SUM_PROFESSION_BONUS?>" id="PROFESSION_BONUS_C<?=$count?>" name="PROFESSION_BONUS[]"/></td>
                                    <td><input READONLY class="form-control" type="number" value="<?=$SUM_COST_LIVING?>" id="COST_LIVING_C<?=$count?>" name="COST_LIVING[]"/> </td>
                                    <td><input READONLY class="form-control" type="number" value="<?=$SUM_PROMOTION_BONUS?>" id="PROMOTION_BONUS_C<?=$count?>" name="PROMOTION_BONUS[]"/></td>
                                    <td><input READONLY class="form-control" type="number" value="<?=$SUM_RISK_PREMIUM?>" id="RISK_PREMIUM_C<?=$count?>" name="RISK_PREMIUM[]"/></td>
                                    <td><input READONLY class="form-control" type="number" value="<?=$SUM_SPECIALIZATION_BONUS?>" id="SPECIALIZATION_BONUS_C<?=$count?>" name="SPECIALIZATION_BONUS_C[]"/></td>
                                    <td><input readonly class="form-control" type="number" value="<?=$SUM_SUPERVISORY_BONUS?>" id="SUPERVISORY_BONUS_C<?=$count?>" name="SUPERVISORY_BONUS_C[]"/></td>


                                    <td colspan="2"><input  style="background-color: antiquewhite"   class="form-control" type="text"   value="<?=$TOTAL?> " id="NEW_DEGREE<?=$count?>" name="NEW_DEGREE[]"/></td>



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
                <button type="button" class="btn btn-success"  id="addRecordLow" onclick="print_report('pdf','gedco_salary_calc_')">طباعةpdf</button>
                <button type="button" class="btn btn-secondary"  id="addRecordLow" onclick="print_report('xls','gedco_salary_calc_')">طباعةxls</button>
            </div>
        </div>
    </div>
</div>

<?php
$current_date = date('d/m/Y');


?>


 //<script type="text/javascript">

  var count3=0;
// Add1222();

 function Add1(){
     count3 = count3+1;
     var rows=<?=$VACATION?>;

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
 function Add(){
     count3 = count3+1;
     var rows=<?=$VACATION?>;

     for (let i = 0; i < <?=count($VACATION)?>; i++) {

         var html = '<tr> ' +
             '<td><div class="form-group col-md-12"><input onchange="calculate1(' + count3 + ')"  type="text" value= "<?=$current_date?>" <?=$date_attr1?> name="txt_from_date_v[]" id="txt_from_date_v' + count3 + '" class="form-control month"></div></td>';
         html += '<td><div class="form-group col-md-12"><input type="text"  onchange="calculate1(' + count3 + ')"   value="<?=$current_date?>" <?=$date_attr1?> name="txt_to_date_v[]" id="txt_to_date_v' + count3 + '" class="form-control month" ></div></td>';
         html += '<td><div class="form-group col-md-12"> <input type="number"  value=" "name="txt_day_v' + count3 + '" id="txt_day_v' + count3 + '"  class="form-control"> </div>    </td>';
         html += '<td><div class="form-group col-md-12"><input type="number"  value=" " name="txt_month_v' + count3 + '" id="txt_month_v' + count3 + '" class="form-control"> </div>   </td>';
         html += '<td><div class="form-group col-md-12"><input type="number"  value=" " name="txt_year_v' + count3 + '" id="txt_year_v' + count3 + '"   class="form-control"> </div>   </td>';
         html += '<td><div class="form-group col-md-12"><input type="number"  value=" " name="txt_subsc_v' + count3 + '" id="subsc_v' + count3 + '"    class="form-control"> </div>   </td></tr>';



     }

console.log(rows);
    $('#page_tb_v tbody').append(html);

   //  reBind(1);

 }


  function updateValue(e,id) {
       document.getElementById(id).value=e.target.value;
  }
 function calculate(){

     showLoading();
     var day4=0;
     var month4=0;
     var year4=0;
     var age=0;
     var year_s=0;

     var vac_day=<?=$vac_days?>;
     var vac_month=<?=$vac_months?>;
     var vac_year=<?=$vac_years?>;
     $('#EXCLUDED_TERM_DAY').val(vac_day);
     $('#EXCLUDED_TERM_MONTH').val(vac_month);
     $('#EXCLUDED_TERM_YEAR').val(vac_year);
     var gender=<?=$rs['SEX']?>;
     $.ajax({
         url: '<?= base_url("servicelength/service_length/calculate_/")?>'+$('#txt_dob').val().replaceAll('/','-')+'/'+$('#txt_to_date').val().replaceAll('/','-')
     }).then(function(data) {
         HideLoading();

         if(data.length ==0){}
         else{

           age=data[0].YEAR_;
console.log(age)

         }
     });


     $.ajax({
         url: '<?= base_url("servicelength/service_length/calculate_/")?>'+$('#txt_from_date').val().replaceAll('/','-')+'/'+$('#txt_to_date').val().replaceAll('/','-')
     }).then(function(data) {
         HideLoading();

         if(data.length ==0){}
         else{

             $('#WORK_PERIOD_DAY').val(data[0].DAY_);
             $('#WORK_PERIOD_MONTH').val(data[0].MONTH_);
             $('#WORK_PERIOD_YEAR').val(data[0].YEAR_);
             year_s=data[0].YEAR_;
         }
     });


     $.ajax({
         url: '<?= base_url("servicelength/service_length/calculate_/")?>'+$('#txt_fex_date').val().replaceAll('/','-')+'/'+$('#txt_to_date').val().replaceAll('/','-')
     }).then(function(data) {
         HideLoading();

         if(data.length ==0){}else{

             $('#FEX_DATE_DAY').val(data[0].DAY_);
             $('#FEX_DATE_MONTH').val(data[0].MONTH_);
             $('#FEX_DATE_YEAR').val(data[0].YEAR_);
             day4+=parseInt(data[0].DAY_);
             month4+=parseInt(data[0].MONTH_);
             year4+=parseInt(data[0].YEAR_);
         /*    day4+=parseInt(data[0].DAY_);
             month4+=parseInt(data[0].MONTH_);
             year4+=parseInt(data[0].YEAR_);*/
          }
     });
setTimeout(function (){
   var vac_total_days=<?= $vac_total?>;

console.log(vac_total_days);
    var total_days=(month4*30)+(year4*365)+day4;
    console.log(total_days);
    console.log(total_days-vac_total_days);
var diff_of_days=total_days-vac_total_days;
var diff_day=(diff_of_days%365)%30;
var diff_month=(diff_of_days%365)/30;
var diff_year=(diff_of_days/365);


    $('#SERVICE_TOTAL_PERIOD_DAY').val(parseInt(diff_day));
    $('#SERVICE_TOTAL_PERIOD_MONTH').val(parseInt(diff_month));
    $('#SERVICE_TOTAL_PERIOD_YEAR').val(parseInt(diff_year));


    console.log(year_s+' age '+age );
    year_s=$('#SERVICE_TOTAL_PERIOD_YEAR').val();


   /*  $("#sava_btn").prop('hidden',false)
    $("#sava_btn").removeAttr('style')*/
    if(gender == 1) {
        if (year_s >= 20 && age >= 55  ) {

         //   $("#sava_btn").prop('hidden', false)
          //  $("#sava_btn").css("display", "block");
            Create();

        } else if (year_s >= 25 && age >= 50  ) {
           // $("#sava_btn").css("display", "block");
            Create();

        } else {
            $("#sava_btn").css("display", "none");
            alert('الموظف لا يطابق الشروط');

        }


    }else  if(gender == 2){
        if (year_s >= 20 && age >= 50  ) {

          //  $("#sava_btn").prop('hidden', false)
            //$("#sava_btn").css("display", "block");
            Create();


        } else if (year_s >= 15 && age >= 55  ) {
          //  $("#sava_btn").prop('hidden', false)

          //  $("#sava_btn").css("display", "block");
            Create();

        } else {
            $("#sava_btn").css("display", "none");
alert('الموظف لا يطابق الشروط');
        }




    }



},1000)

 }
 function Create(){
      var form = $('#Service_length_form1');
     console.log(form.valid());
     ajax_insert_update(form,function(data){

         success_msg('رسالة','تم حفظ البيانات بنجاح ..');
       //  get_to_link(window.location);
           get_to_link( window.location.href );

         //  $('#{$TB_NAME}_form .modal-body').html(data);
     },"html");


/*
     $.post( '<?=$create?>', $('#Service_Length_form').serializeArray(), function( data ) {

         $('#content_items').html(data);
         success_msg('رسالة','تم حفظ البيانات بنجاح ..');

     }, "html");*/
     $( "#content_items" ).removeClass( "hidden" );
     $( "#content_show" ).addClass("hidden" );



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
  var sum_days=0,sum_months=0,sum_years=0;

   function calculate_vacation(){



      for (let ii = 0; ii <= <?=$count_vacation?>; ii++) {
          $.ajax({
              url: '<?= base_url("servicelength/service_length/calculate_1/")?>'+$('#txt_from_date_v'+ii).val().replaceAll('/','-')+'/'+$('#txt_to_date_v'+ii).val().replaceAll('/','-')
          }).then(function(data) {
              HideLoading();
              setTimeout(function (){
                  console.log( ii +"" + data[0].DAY_);

                  if(data.length ==0){


                  }else {
                      sum_days+=parseInt(data[0].DAY_);
                      sum_months+=parseInt(data[0].MONTH_);
                      sum_years+=parseInt(data[0].YEAR_);
                          $('#txt_day_v' + ii).val(parseInt(data[0].DAY_));
                          $('#txt_month_v' + ii).val(parseInt(data[0].MONTH_));
                          $('#txt_year_v' + ii).val(parseInt(data[0].YEAR_));

                  }




              },12);


                      console.log(sum_days+"   "+sum_months+"   "+sum_years);



          });
          //do stuff
      }


  }

  function date_changed(){
       $('#txt_to_date').val($('#txt_end_date').val());

  }

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
           var inputs = document.getElementsByName('checkbox_5[]');
           var NEW_DEGREE = document.getElementsByName('NEW_DEGREE[]');
      var PER_ALLOW = document.getElementsByName('PER_ALLOW_IN[]');
      var FOR_MONTH_5 = document.getElementsByName('FOR_MONTH_5[]');
var  TXT_EMP_NO=document.getElementById('txt_emp_no').value;

      for (var i = 0; i < inputs.length; i++) {
          console.log()
              if(inputs[i].checked){
                  console.log(inputs[i].value);
                  console.log(NEW_DEGREE[i].value);
                  console.log(PER_ALLOW[i].value);

              $.post('<?=$update_data_url?>',{EMP_NO:TXT_EMP_NO, FOR_MONTH:FOR_MONTH_5[i].value, NEW_DEGREE: NEW_DEGREE[i].value, PER_ALLOW_IN: PER_ALLOW[i].value }, function(r){
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
          /*     $.post('<?=$update_data_url?>',$('#'+form).serialize(), function(r){
                  $(r).each(function(index, element) {
                      if(element['status'] == 1){
                          success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                          get_to_link( window.location.href );
                      }
                      else{
                          danger_msg('تحذير..',element['o_msgtxt']);

                      }
                  })
              },'json');*/
          }



  }

  function  update_data_36(form){
      var inputs = document.querySelectorAll('.group-checkable');
      var BASIC_SAL = document.getElementsByName('BASIC_SAL_36[]');
      var PROFESSION_BONUS = document.getElementsByName('PROFESSION_BONUS_36[]');
      var COST_LIVING = document.getElementsByName('COST_LIVING_36[]');
      var  FOR_MONTH=document.getElementsByName('FOR_MONTH_36[]') ;
      var  PROMOTION_BONUS=document.getElementsByName('PROMOTION_BONUS_36[]') ;
      var  NEW_DEGREE=document.getElementsByName('NEW_DEGREE_36[]') ;
      var  PER_ALLOW_IN=document.getElementsByName('PER_ALLOW_IN_36[]') ;
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

  function modal_dissmiss(name){

     $('#'+name).modal('hide');

 }

  function  print_report(rep_type,name){


       var repUrl = '<?=$report_url?>'+'&report_type='+rep_type+'&report='+name+rep_type+'&p_emp_id='+$('#txt_emp_no').val();
      _showReport(repUrl);

  }





  function Adopt(number) {
      var  TXT_EMP_NO=document.getElementById('txt_emp_no').value;

      $.post('<?=$adopt_url?>',{EMP_NO:TXT_EMP_NO, ADOPT:number }, function(r){
          $(r).each(function(index, element) {
              if(element['status'] == 1){
                  success_msg('رسالة','تم حفظ البيانات بنجاح ..');
                   get_to_link( window.location.href );
              }
              else{
                  danger_msg('تحذير..',element['o_msgtxt']);

              }
          })
      },'json');
  }
  //</script>


