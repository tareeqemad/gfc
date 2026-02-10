<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');


require_once  "/var/www/html/gfc/vendor/autoload.php";


//use Jaspersoft\Client\Client;

class Jsperreport extends MX_Controller
{

    private $jasper;
    private $report_path = '/reports';


    /*  public function __construct()
      {
          parent::__construct();

      $this->load->helper('jasper_helper');

          jasper_init();

      $ins = $this->session->userdata('db_instance');

          $this->jasper = new Jaspersoft\Client\Client(
              "http://10.184.3.208:8080/jasperserver",
              "u_2019",
              "u_2019",
              ""
          );

      }*/

    public function __construct()
    {
        parent::__construct();

        $this->load->helper('jasper_helper');

        jasper_init();


        $ins = $this->session->userdata('db_instance');

        /* ------------------------------------------- */
        /*check if postgresql work -- 200 work -- 500 Not --*/
        $url = 'http://reports2.gedco.com:8080/jasperserver/login.html';
   ///     $msg = reset(get_headers($url, 1));
        /* end postgresql check*/

        /*  check if jasperserver service work  */
        $host = 'reports2.gedco.com';
/****
        if(($socket =@ fsockopen($host, 8080, $errno, $errstr, 1)) and strstr($msg, '200')) {
            $serverUrl = "http://reports2.gedco.com:8080/jasperserver";
            fclose($socket);
        } else {
            $serverUrl = "http://reports.gedco.com:8080/jasperserver";
        }
***///
	// test cloud
	$serverUrl = "http://10.184.3.208:8080/jasperserver";


        $this->jasper = new Jaspersoft\Client\Client(
            "".$serverUrl."",
            "u_2019",
            "u_2019",
            ""
        );

        /* ------------------------------------------- */
    }

    public function index()
    {
    }

    public function ShowReport()
    {

        $Parameters = array();

        foreach ($_GET as $key => $value) {
            $Parameters[$key] = $value;
        }

        $Report_Name = "{$this->report_path}/{$_GET['sys']}/{$_GET['report']}";
        $Doc_Name = 'Doc_Name_To_Print';

        if (isset($_GET['report_type']) && $_GET['report_type'] == 'xls')
            $this->Print_XLSX($Doc_Name, $Report_Name, $Parameters);
        elseif (isset($_GET['report_type']) && $_GET['report_type'] == 'doc')
            $this->Print_DOCX($Doc_Name, $Report_Name, $Parameters);
        elseif (isset($_GET['report_type']) && $_GET['report_type'] == 'html')
            $this->Print_HTML($Doc_Name, $Report_Name, $Parameters);
        else
            $this->Print_PDF($Doc_Name, $Report_Name, $Parameters);
    }

    /////////////////////////////////////////////////////////////////////////////
    public function Print_PDF($Doc_Name, $Report_Name, $Parameters)
    {



        $report = $this->jasper->reportService()->runReport($Report_Name, 'pdf', null, null, $Parameters);
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Description: File Transfer');
        header('Content-Disposition: inline; filename=' . $Doc_Name . '.pdf');
        header('Content-Transfer-Encoding: binary');
        header('Content-Length: ' . strlen($report));
        header('Content-Type: application/pdf; charset=utf-8');
        echo $report;
    }

    public function Print_XLSX($Doc_Name, $Report_Name, $Parameters)
    {




        $report = $this->jasper->reportService()->runReport($Report_Name, 'xlsx', null, null, $Parameters);

        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Description: File Transfer');
        header('Content-Disposition: inline; filename=' . $Report_Name . '.xlsx');
        header('Content-Transfer-Encoding: binary');
        header('Content-Length: ' . strlen($report));
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet; charset=utf-8');
        echo $report;
    }

    //M.taqia
    public function Print_DOCX($Doc_Name, $Report_Name, $Parameters)
    {

        $report = $this->jasper->reportService()->runReport($Report_Name, 'docx', null, null, $Parameters);

        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Description: File Transfer');
        header('Content-Disposition: inline; filename=' . $Report_Name . '.docx');
        header('Content-Transfer-Encoding: binary');
        header('Content-Length: ' . strlen($report));
        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document; charset=utf-8');
        echo $report;
    }

    public function Print_HTML($Doc_Name, $Report_Name, $Parameters)
    {
        $report = $this->jasper->reportService()->runReport($Report_Name, 'html', null, null, $Parameters);
        echo $report;
    }

}

?>
