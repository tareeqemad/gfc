<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 9/23/14
 * Time: 10:33 AM
 */

// mkilani- get ar desc of month
if (!function_exists('months')) {
    function months($no = 0, $name = null)
    {
        switch ($no) {
            case 1:
                $name = 'يناير';
                break;
            case 2:
                $name = 'فبراير';
                break;
            case 3:
                $name = 'مارس';
                break;
            case 4:
                $name = 'ابريل';
                break;
            case 5:
                $name = 'مايو';
                break;
            case 6:
                $name = 'يونيو';
                break;
            case 7:
                $name = 'يوليو';
                break;
            case 8:
                $name = 'اغسطس';
                break;
            case 9:
                $name = 'سبتمبر';
                break;
            case 10:
                $name = 'اكتوبر';
                break;
            case 11:
                $name = 'نوفمبر';
                break;
            case 12:
                $name = 'ديسمبر';
                break;
            default:
                $name = '';
        }
        return $name;
    }
}

// mkilani- get desc of record case
if (!function_exists('record_case')) {
    function record_case($no = 0, $name = null)
    {
        switch ($no) {
            case 1:
                $name = 'مدخل غير معتمد';
                break;
            case 2:
                $name = 'اعتماد الدائرة';
                break;
            case 3:
                $name = 'اعتماد الادارة / الفرع';
                break;
            case 4:
                $name = 'اعتماد الادارة المالية/الموازنة العامة';
                break;
            case 5:
                $name = 'اعتماد مدير عام الشركة';
                break;
            case 6:
                $name = 'اعتماد مجلس الادارة';
                break;
            default:
                $name = '';
        }
        return $name;
    }
}

// mkilani- encryption & decryption record case
if (!function_exists('encryption_case')) {
    function encryption_case($n, $do = 0)
    {
        $n = (int) $n;
        if ($do == 1) { // تشفير
            return rand(10, 99) . ($n - 1) . rand(100, 999);
        } else { // فك تشفير
            return substr($n, -4, 1) + 1;
        }
    }
}

// mkilani- get first & last name of user
if (!function_exists('get_user_name')) {
    function get_user_name($id = 0)
    {
        $CI = get_my_instance();
        $row = $CI->get_user_info($id);
        if (count($row) == 1) {
            $name = $row[0]['USER_NAME'];
            $name = explode(' ', trim($name));
            $name = $name[0] . ' ' . array_pop($name);
        } else
            $name = '';
        return $name;
    }
}

if (!function_exists('get_short_user_name')) {
    function get_short_user_name($name)
    {

        $name = $name;
        $name = explode(' ', trim($name));
        $name = $name[0] . ' ' . array_pop($name);

        return $name;
    }
}


// mkilani- generate password
if (!function_exists('generate_pass')) {
    function generate_pass()
    {
        return random_string('alnum', rand(2, 4)) . rand(0, 9) . substr(str_shuffle('@#$%.-_'), 0, rand(1, 2)) . random_string('alnum', rand(2, 4));
    }
}

// mkilani- check identity number
if (!function_exists('check_identity')) {
    function check_identity($id)
    {
        if (preg_match('/^[0-9]{9}$/', $id)) {
            $id = str_split($id);
            $res = 0;
            for ($i = 1; $i < 9; $i++) {
                if ($i % 2 == 0) {
                    $total = $id[$i - 1] * 2;
                    if ($total >= 10)
                        $total = array_sum(str_split($total));
                } else {
                    $total = $id[$i - 1];
                }
                $res += $total;
            }

            //$last= 10- $res%10 ;
            $last = $res % 10;
            if ($last != 0)
                $last = 10 - $last;

            if ($last == $id[8])
                return true;
            else
                return false;
        } else
            return false;
    }
}


// mkilani- add % to char, for search in DB
if (!function_exists('add_percent_sign')) {
    function add_percent_sign($char)
    {
        return '%' . str_replace(' ', '%', trim($char)) . '%';
    }
}

// mkilani- sn for reports
if (!function_exists('report_sn')) {
    function report_sn()
    {
        return 'SN:' . date('ymd') . get_curr_user()->id;
    }
}

// mkilani- gfc domain name
if (!function_exists('gh_gfc_domain')) {
    function gh_gfc_domain()
    {
        return 'https://gs.gedco.ps'; // https://gs.gedco.ps
    }
}

// mkilani- itdev reports url
if (!function_exists('gh_itdev_rep_url')) {
    function gh_itdev_rep_url()
    {
        return 'https://itdev.gedco.ps'; // OLD-202010: http://itdev:801
    }
}

// mkilani- print systems list in the template
if (!function_exists('get_systems')) {
    function get_systems()
    {
        $systems = array (
            array("ID"=>"1", "NAME"=>"النظام المالي"),
            array("ID"=>"2", "NAME"=>"النظام الفني"),
            array("ID"=>"3", "NAME"=>"النظام الإداري"),
            array("ID"=>"8", "NAME"=>"نظام التخطيط"),
            array("ID"=>"7", "NAME"=>"النظام القانوني"),
            array("ID"=>"6", "NAME"=>"نظام الشبكة الذكية"),
            array("ID"=>"10", "NAME"=>"نظام التدريب"),
            array("ID"=>"9", "NAME"=>"النظام الفني الجديد"),
            array("ID"=>"11", "NAME"=>"الخدمات الإلكترونية"),
            array("ID"=>"15", "NAME"=>"نظام المهام والمراسلات"),
			array("ID"=>"16", "NAME"=>"نظام المتابعة والمعلومات"),
        );

        $base_url = base_url('/welcome');

        $template = "<li><a class='system_list' href='$base_url/csystem'> الأنظمة </a><ul>";

        foreach ($systems as $row){
            $template = $template . "<li><a href=\"{$base_url}/setSystem/{$row['ID']}\">{$row['NAME']}</a></li>";
        }

        return $template.'</ul></li>';
    }
}


// mkilani- print systems list in the template
if (!function_exists('get_systems_temp1')) {
    function get_systems_temp1()
    {
        $systems = array (
            array("ID"=>"1", "NAME"=>"النظام المالي"),
            array("ID"=>"2", "NAME"=>"النظام الفني"),
            array("ID"=>"3", "NAME"=>"النظام الإداري"),
            array("ID"=>"8", "NAME"=>"نظام التخطيط"),
            array("ID"=>"7", "NAME"=>"النظام القانوني"),
            array("ID"=>"6", "NAME"=>"نظام الــ GIS"),
            array("ID"=>"10", "NAME"=>"نظام التدريب"),
            array("ID"=>"9", "NAME"=>"النظام الفني الجديد"),
            array("ID"=>"11", "NAME"=>"الخدمات الإلكترونية"),
            array("ID"=>"15", "NAME"=>"نظام المهام والمراسلات"),
        );

        $base_url = base_url('/welcome');
        $template = "";
        foreach ($systems as $row) {
            $template = $template . "<a class='dropdown-item' href=\"{$base_url}/setSystem/{$row['ID']}\">
              <div class='notification-each d-flex'>
                        <div class='me-3 notifyimg  bg-primary brround'>
                        <i class='ion-folder'></i>
                      </div>
                	<div>
					   <span class='notification-label mb-1 fs-15'>{$row['NAME']}</span>
				   </div>
                </div>
            </a>";
        }
        return $template;
    }
}

// mkilani- char_to_time
if (!function_exists('char_to_time')) {
    function char_to_time($date = '')
    {
        return strtotime(str_replace('/', '-', $date));
    }
}

// mkilani- get records for special emps to user
if (!function_exists('in_special_emps')) {
    function in_special_emps($emp_no, $col)
    {
        if ($emp_no==1274){ // سماهر الزهارنة
            return " and $col in (29, 31, 48, 194, 197, 603, 608, 613, 624, 646, 719, 786, 832, 946, 967, 981, 1081, 1131, 1164, 1165, 1172, 1198, 1258, 1274, 1280, 1292, 1293, 1361, 1442, 1483, 1484, 1487, 1489, 1498, 1499, 1504, 1507, 1520, 1525, 1529, 1530, 1531, 1534, 1536, 1538, 1543, 1544, 1545, 1546, 1549, 1550) ";
        }else{
            return '';
        }
    }
}

if (!function_exists('date_format_exp')) {
    function date_format_exp()
    {
        return '^(0[1-9]{1}|(1|2)\d{1}|(30|31))\/(0[1-9]{1}|1[0-2]{1})\/\d{4}';
    }
}

if (!function_exists('datetime_format_exp')) {
    function datetime_format_exp()
    {
        return '^(0[1-9]{1}|(1|2)\d{1}|(30|31))\/(0[1-9]{1}|1[0-2]{1})\/\d{4}\s(0[1-9]{1}|1[0-2]{1}):(0[1-9]{1}|[0-5]{1}[1-9])';
    }
}
if (!function_exists('float_format_exp')) {
    function float_format_exp()
    {
        return '^\$?([0-9]{1,3},([0-9]{3},)*[0-9]{3}|[0-9]+)(.[0-9]+)?$';
    }
}


if (!function_exists('f_c_source')) {
    function f_c_source($type, $id)
    {

        switch ($type) {
            case 3:
                return base_url("treasury/income_voucher/get/{$id}");
                break;
            case 2:
                return base_url("treasury/income_voucher/get/{$id}");
                break;
            case 7:
                return base_url("payment/financial_payment/get/{$id}/archive");
                break;
            case 10:
                return base_url("treasury/convert_cash/get/{$id}/index");
                break;
            case 11:
                return base_url("treasury/convert_cash_bank/get/{$id}/index");
                break;
            case 12:
                return base_url("financial/financial_mqasah/get/{$id}/index?type=12");
                break;
            case 13:
                return base_url("treasury/checks_processing/get/{$id}/index");
                break;
            case 13:
                return base_url("treasury/checks_processing/get/{$id}/index");
                break;
            case 14:
                return base_url("payment/out_checks_processing/get/{$id}/index");
                break;
            case 16:
                return base_url("stores/invoice_class_input/get_id/{$id}/edit/4");
                break;
            case 17:
                return base_url("stores/stores_class_transport/get/{$id}/edit");
                break;
            case 18:
                return base_url("stores/stores_class_output/get/{$id}/edit");
                break;
            case 19:
                return base_url("stores/stores_class_return/get/{$id}/edit");
                break;
            case 20:
                return base_url("financial/expense_bill/get/{$id}/index/");
                break;
            case 21:
                return base_url("stores/stores_class_input/get_chain_id/{$id}/edit/1/2");
                break;
            case 22:
                return base_url("financial/warranty/get/{$id}/edit");
                break;

        }

    }
}

if (!function_exists('replace_d_dsh')) {
    function replace_d_dsh($str)
    {

        $rs = '';

        for ($i = 0; $i <= strlen($str); $i++)
            $rs = $rs . '&nbsp;';


        return $rs;

    }
}
if (!function_exists('check_credit')) {
    function check_credit($val)
    {

        $credit = floatval($val);

        if ($credit == 0)
            return '0.00';

        return ($credit < 0) ? ' د ' . n_format($credit * -1) : ' م ' . n_format($credit);


    }
}

if (!function_exists('n_format')) {
    function n_format($val)
    {


        return number_format($val, 2, '.', ',');


    }
}

if (!function_exists('remove_decimal')) {
    function remove_decimal($val)
    {


        return str_replace(',', '', $val);


    }
}

if (!function_exists('notes_url')) {
    function notes_url()
    {


        return base_url('settings/notes/public_create');


    }
}


if (!function_exists('clear_url')) {
    function clear_url($url)
    {

        $url = preg_replace('/\s+/', '_', $url);
        $url = preg_replace('/[(]/', '_', $url);
        $url = preg_replace('/[)]/', '_', $url);
        $url = preg_replace('/[+%,()*&!@#^<>?]-/', '_', $url);
        $url = preg_replace('/[?+]/', '', $url);
        $url = preg_replace('/[,+]/', '', $url);
        return $url;


    }
}


if (!function_exists('f_payment_case')) {
    function f_payment_case($case)
    {
        switch ($case) {

            case 1:
                return 'الخزينة';
            case 2:
                return 'الإدارة المالية';
            case 3:
                return 'الرقابة';

            case 0:
                return 'ملغي';

            default:
                if ($case >= 4)
                    return 'منجز';
                else return 'إعداد';
        }
    }
}

if (!function_exists('project_case')) {
    function project_case($case,$code = null)
    {
        switch ($case) {
            case -1:
                $name = 'إعداد';
                break;
            case 1:
                $name = 'إعداد';
                break;
            case 2:
                $name = 'المدير الفني';
                break;
            case 3:
                $name = strpos(strtolower($code), 'isc') !== false ? 'السكادا' : 'التخطيط';
                break;
            case 4:
                $name = strpos(strtolower($code), 'isc') !== false ? 'مدير التحكم و التشغيل' : 'مدير التخطيط';
                break;
            case 5:
                $name = 'لجنة المساهمة';
                break;
            case 6:
                $name = 'مدير المقر';
                break;
            case 7:
                $name = 'الشئون القانونية';
                break;
            case 8:
                $name = 'الصيانة';
                break;
            case 9:
                $name = 'مدير الصيانة';
                break;
            case 10:
                $name = 'الإدارة الفنية';
                break;
            case 11:
                $name = 'الحسابات';
                break;
            case 15:
                $name = 'مغلق';
                break;

            default:
                $name = '';
        }
        return $name;
    }
}

if (!function_exists('budget_project_case')) {
    function budget_project_case($case)
    {
        switch ($case) {
            case -1:
                $name = 'إعداد';
                break;
            case 1:
                $name = 'إعداد';
                break;
            case 2:
                $name = 'المدير الفني';
                break;
            case 3:
                $name = 'مدير المقر';
                break;
            case 4:
                $name = 'إعتماد الجهة المختصة';
                break;


            default:
                $name = '';
        }
        return $name;
    }
}

if (!function_exists('check_reports')) {
    function check_reports($checkid, $curr_id, $bank_id = 1)
    {

        $report = 'PALESTAIN_CHECK_FORMS';

        if ($bank_id == 1) {
            if ((intval($checkid) > 30014500 && $curr_id == 1) || (intval($checkid) > 20001675 && $curr_id == 2) || (intval($checkid) >= 10000546 && $curr_id == 4) || (intval($checkid) >= 50000296 && $curr_id == 3)) {
                 if (intval($checkid) > 30020000) 
		 { 
			return 'palestine_bank_check_2019_form';
                 } 
		else 
		 { 
			if($curr_id == 2 && intval($checkid) > 20002401)
				return 'palestine_bank_check_2021_form'; 
			else if($curr_id == 3 && intval($checkid) > 50000321)
				return 'palestine_bank_check_2021_form'; 
			else if($curr_id == 4 && intval($checkid) > 10000582)
				return 'palestine_bank_check_2021_form'; 
			else
			return 'palestine_bank_check'; 
                 }
            }
        } else if ($bank_id == 3) {

            if ((intval($checkid) > 30000340 && $curr_id == 1)) {
                return 'form_watany_bank';
            }elseif ($curr_id==2){
                return 'form_watany_bank';
            }
        } else if ($bank_id == 4 or $bank_id == 19) {

            return 'palestine_islamic_bank_check';

        } else if ($bank_id == 5) {

            return 'palestine_production_bank_check';

        } else if ($bank_id == 8) {

            return 'arab_islamic_bank_check';
        }

        return $report;

    }
}

if (!function_exists('sort_dir')) {
    function sort_dir($col, $str)
    {
        if (strpos(strtolower($str), strtolower($col)) !== false) {
            if (endsWith(trim(strtolower($str)), "desc") === true)
                return 'desc';
            else if (endsWith(trim(strtolower($str)), "asc") === true)
                return 'asc';
            else '';
        }
    }
}

function endsWith($haystack, $needle)
{
    // search forward starting from end minus needle length characters
    return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== FALSE);
}

function get_report_folder()
{
    $db_ins = get_my_instance()->database_name;

    //echo $db_ins;

    if ($db_ins and strlen($db_ins) > 1) {
        return $db_ins == 'GFC' ? '' : ($db_ins == 'GFCTRANS' ? '_trans' : '_arch');

        switch ($db_ins) {
            case 'HR':
                return '_hr';
            case 'GFCTRANS':
                return '_trans';
            case 'GFCARCH':
                return '_arch';
            default:
                return '';
        }
    }

    return '';
}


function unique_multidim_array($array, $key)
{
    $temp_array = array();
    $i = 0;
    $key_array = array();

    foreach ($array as $val) {
        if (!in_array($val[$key], $key_array)) {
            $key_array[$i] = $val[$key];
            $temp_array[$i] = $val;
        }
        $i++;
    }
    return $temp_array;
}


/*tareq */
function getDatesFromRange($start, $end) {
    $interval = new DateInterval('P1D'); // PT5M 5 min
    $realEnd = new DateTime($end);
    $realEnd->add($interval);
    $period = new DatePeriod(
        new DateTime($start),
        $interval,
        $realEnd
    );
    foreach($period as $date) {
        $array[] = $date->format('Y-m-d');
    }
    return $array;
}

if(!function_exists('dd'))
{
    function dd( $result )
    {
        echo '<pre>'; print_r($result); die();
    }
}


function convertNumberToArabicWords($number) {
    $ones = [
        "", "واحد", "اثنان", "ثلاثة", "أربعة", "خمسة", "ستة", "سبعة", "ثمانية", "تسعة"
    ];

    $tens = [
        "", "عشرة", "عشرون", "ثلاثون", "أربعون", "خمسون", "ستون", "سبعون", "ثمانون", "تسعون"
    ];

    $teens = [
        "عشرة", "أحد عشر", "اثنا عشر", "ثلاثة عشر", "أربعة عشر", "خمسة عشر",
        "ستة عشر", "سبعة عشر", "ثمانية عشر", "تسعة عشر"
    ];

    $hundreds = [
        "", "مئة", "مئتان", "ثلاثمئة", "أربعمئة", "خمسمئة", "ستمئة", "سبعمئة", "ثمانمئة", "تسعمئة"
    ];

    $thousands = [
        "", "ألف", "ألفان", "ثلاثة آلاف", "أربعة آلاف", "خمسة آلاف",
        "ستة آلاف", "سبعة آلاف", "ثمانية آلاف", "تسعة آلاف"
    ];

    if ($number == 0) {
        return "صفر شيكل";
    }

    // تقسيم الرقم إلى جزء صحيح وعشري
    $parts = explode(".", number_format($number, 2, ".", ""));
    $integerPart = (int)$parts[0];
    $decimalPart = (int)$parts[1];

    $words = [];

    // معالجة الآلاف
    if ($integerPart >= 1000) {
        $thousandsIndex = (int) ($integerPart / 1000);
        $words[] = $thousands[$thousandsIndex];
        $integerPart %= 1000;
    }

    // معالجة المئات
    if ($integerPart >= 100) {
        $hundredIndex = (int) ($integerPart / 100);
        $words[] = $hundreds[$hundredIndex];
        $integerPart %= 100;
    }

    // معالجة الأعداد بين 10 و 19
    if ($integerPart >= 10 && $integerPart <= 19) {
        $words[] = $teens[$integerPart - 10];
        $integerPart = 0;
    }

    // معالجة العشرات
    if ($integerPart >= 20) {
        $tensIndex = (int) ($integerPart / 10);
        $words[] = $tens[$tensIndex];
        $integerPart %= 10;
    }

    // معالجة الآحاد
    if ($integerPart > 0) {
        $words[] = $ones[$integerPart];
    }

    // ترتيب الكلمات بشكل صحيح
    $result = implode(" و", array_filter($words)) . " شيكل";

    // معالجة الكسور العشرية (الأغورة)
    if ($decimalPart > 0) {
        $decimalWords = [];
        if ($decimalPart >= 10 && $decimalPart <= 19) {
            $decimalWords[] = $teens[$decimalPart - 10];
        } else {
            if ($decimalPart >= 20) {
                $decimalWords[] = $tens[(int)($decimalPart / 10)];
            }
            if ($decimalPart % 10 > 0) {
                $decimalWords[] = $ones[$decimalPart % 10];
            }
        }

        $result .= " و" . implode(" و", array_filter($decimalWords)) . " أغورة";
    }

    return $result;
}
