<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 9/27/14
 * Time: 11:23 AM
 */
class Reports extends MY_Controller
{

    function __construct()
    {
        parent::__construct();

    }
 function index()
    {

        //http://itdev:801/gfc.aspx?
        $url = 'https://itdev.gedco.ps/gfc.aspx?data=';
        $url = $url . '&type='.$_GET['type'];
        $url = $url . '&report='.$_GET['report'];

        $params = isset($_GET['params']) ? $_GET['params'] : array();
        if ($params)
            for ($i = 0; $i < count($params); $i++) {
                $url = $url . '&params[]='.$params[$i];
            }

        redirect($url);
    }
    function indexx()
    {

        //http://itdev:801/gfc.aspx?
        $url = 'http://itdev:801/gfc.aspx?data=';
        $url = $url . '&type='.$_GET['type'];
        $url = $url . '&report='.$_GET['report'];

        $params = isset($_GET['params']) ? $_GET['params'] : array();
        if ($params)
            for ($i = 0; $i < count($params); $i++) {
                $url = $url . '&params[]='.$params[$i];
            }

        redirect($url);
    }

    function _indexold()
    {


        $current_user = $this->session->userdata('user_data');
        $db_pass = 'A' . substr(md5($current_user->db_pwd), 3);


        $username = 'GFC_PAK';//$current_user->username;
        $password = 'GFC_PAK';//$db_pass;
        $instance = "GFC";

        // mkilani- change DB..
        $db_ins = $this->session->userdata('db_instance');
        if ($db_ins and strlen($db_ins) > 0) {
            $instance = $db_ins;

        }
        $folder = get_report_folder();//$instance == 'T'?'':( $instance == 'GFCTRANS'? '_trans' :'_arch');

        echo $folder . ' ' . $db_ins;

        $rpt_file = $_GET['report'] . '.rpt';
        $pdf_file = 'temp' . (date('d-m-y-h-i-s')) . rand();

        $params = isset($_GET['params']) ? $_GET['params'] : array();
        $export_type = isset($_GET['type']) ? $_GET['type'] : 31;

        $delimiter = isset($_REQUEST['delimiter']) ? $_REQUEST['delimiter'] : ',';

        $pdf_path = "C:/wamp/www/gfc/reports_files{$folder}/";

        $report_path = "C:/wamp/www/gfc/reports_files{$folder}/";

        if (file_exists($pdf_path . $pdf_file))
            if (!unlink($pdf_path . $pdf_file))
                return "Failed to clear temp.  please try again";

        try {

            $crapp = new COM('Crystalruntime.Application') or die('no com');
            $creport = $crapp->OpenReport($report_path . $rpt_file, 1);
            $crapp->LogOnServer('crdb_oracle.dll', $instance, '', $username, $password);
            $creport->EnableParameterPrompting = false;
            $creport->DiscardSavedData;

            //$creport->RecordSelectionFormula = ' {EMPLOYEES_TB.NO} = 1361 ';


            if ($params)
                for ($i = 0; $i < count($params); $i++) {
                    $creport->ParameterFields($i + 1)->AddCurrentValue($params[$i]);
                }


            $creport->ExportOptions->DiskFileName = $pdf_path . $pdf_file;
            $creport->ExportOptions->FormatType = $export_type; // PDF
            $creport->ExportOptions->DestinationType = 1; // Save to disk
            $creport->Export(false);
            $creport->ReadRecords();


            unset($creport, $crapp, $rpt_parms, $rpt_values, $report_path, $username, $password, $instance, $rpt_parms, $rpt_values, $delimiter, $rpt_folder);


            $extension = (intval($export_type) == 31) ? 'pdf' : (intval($export_type) == 36 ? 'xls' : 'pdf');

            if (file_exists($pdf_path . $pdf_file)) {

                header("Content-type: application/{$extension};charset=utf-8");
                header('Content-Disposition: inline; filename="' . $_GET['report'] . '.' . $extension . '"');
                header('Content-Transfer-Encoding: binary');
                header('Content-Length: ' . filesize($pdf_path . $pdf_file));
                header('Accept-Ranges: bytes');

                @readfile($pdf_path . $pdf_file);

            } else return "Failed to export";


        } catch (Exception $e) {
            echo '<p>' . $e->getMessage() . '</p><pre>' . $e->getTraceAsString() . '</pre>';
            echo 'حدث خطأ';

        }

        if (file_exists($pdf_path . $pdf_file))
            unlink($pdf_path . $pdf_file);


    }
}