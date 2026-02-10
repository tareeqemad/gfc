<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 06/06/18
 * Time: 01:07 م
 */
class indicate_charts extends MY_Controller{

    var $MODEL_NAME= 'indicator_model';


    function  __construct(){
        parent::__construct();
        $this->load->model($this->MODEL_NAME);
        $this->load->model('settings/constant_details_model');
        $this->load->model('settings/gcc_branches_model');
        $this->load->model('settings/Gcc_structure_model');
        $this->year= $this->budget_year;
        $this->ser=$this->input->post('ser');

    }
    /******************************************************************************************************************************************************/
    /*                                                             Manage Indicator                                                                      */
    /******************************************************************************************************************************************************/



    function index()
    {

        //$data['title']=' ادارة الجريدة اليومية لعام '.$this->year;
        $data['title']='عرض المؤشرات';
        $data['content']='indicator_charts_index';
        $data['title_statistic']='التحصيل';
        $data['help']=$this->help;
        add_css('components-md-rtl.css');
        add_js('flot/jquery.flot.js');
        add_js('flot/jquery.flot.resize.js');
        add_js('flot/jquery.flot.pie.js');
        add_js('flot/jquery.flot.stack.js');
        add_js('flot/jquery.flot.crosshair.js');
        add_js('flot/jquery.canvasjs.min.js');
        add_js('flot/canvasjs.min.js');
        add_js('jshashtable-2.1.js');
        add_js('echart/echarts.min.js');

        add_js('metronic.js');
        add_css('datepicker3.css');
        add_js('bootstrap.min.js');


        add_js('jquery.formatNumber-0.1.1.min.js');




        $this->load->view('template/template',$data);
       // $this->_look_ups($data);

       // $this->load->view('template/template', $data);

    }
/***************************************************************************************************************************************/
    function public_indecator_data_tb_get(){


        $for_month =isset($this->p_form_month) && $this->p_form_month != null ?$this->p_form_month: date("Ym")-1;
        $to_month =isset($this->p_to_month) && $this->p_to_month != null ?$this->p_to_month: date("Ym")-1;
        $indecatior_id = isset($this->p_indecatior_id) && $this->p_indecatior_id != null ?$this->p_indecatior_id: '';


       $arr1 = $this->{$this->MODEL_NAME}->indecator_data_tb_get(1,$for_month,$to_month);//عدد الاشتراكات المكانيكي
        $arr2 = $this->{$this->MODEL_NAME}->indecator_data_tb_get(2,$for_month,$to_month);// عدد الاشتراكات مسبق الدفع
        $arr3 = $this->{$this->MODEL_NAME}->indecator_data_tb_get(3,$for_month,$to_month);// عدد الاشتراكات التجاري
      $arr4 = $this->{$this->MODEL_NAME}->indecator_data_tb_get(4,$for_month,$to_month);// عدد الاشتراكات المنزلي
      $arr5 = $this->{$this->MODEL_NAME}->indecator_data_tb_get(5,$for_month,$to_month); // قيمة الفاتورة الشهرية
       // $arr6 = $this->{$this->MODEL_NAME}->indecator_data_tb_get(15,$for_month-1,$to_month-1); // التحصيل من المتاخرات
        $arr7 = $this->{$this->MODEL_NAME}->indecator_data_tb_get(7,$for_month,$to_month); // عدد الاشتركات المركب
       $arr8 = $this->{$this->MODEL_NAME}->indecator_data_tb_get(8,$for_month,$to_month); // عدد الاشتركات 1 فاز
       $arr9 = $this->{$this->MODEL_NAME}->indecator_data_tb_get(9,$for_month,$to_month); // عدد الاشتركات 3 فاز
     //   $arr10 = $this->{$this->MODEL_NAME}->indecator_data_tb_get(10,$for_month,$to_month); // قيمة التحصيلات الحقيقية
        $arr11 = $this->{$this->MODEL_NAME}->indecator_data_tb_get(16,$for_month,$to_month); // قيمة التحصيلات المستهدفة
       $arr12 = $this->{$this->MODEL_NAME}->indecator_data_tb_get(18,$for_month,$to_month); //  القراءات الصفرية

///
         $arr13 = $this->{$this->MODEL_NAME}->indecator_data_tb_get(19,$for_month,$to_month);  // اشتركات غير مرتبطة بالية السداد
        $arr14 = $this->{$this->MODEL_NAME}->indecator_data_tb_get(20,$for_month,$to_month); // اشتركات غير ملتزم بسداد ميكانيكي 3 اشهر
       $arr15 = $this->{$this->MODEL_NAME}->indecator_data_tb_get(21,$for_month,$to_month);// اشتركات غير ملتزم بشحن مسبق دفع 3 اشهر

     $arr16 = $this->{$this->MODEL_NAME}->indecator_data_tb_get(10,$for_month,$to_month); // التحصيل النقدي
       $arr17 = $this->{$this->MODEL_NAME}->indecator_data_tb_get(11,$for_month,$to_month); // التحصيل غير نقدي
    $arr18 = $this->{$this->MODEL_NAME}->indecator_data_tb_get(12,$for_month,$to_month); // تحصيل التفتيش
     $arr19 = $this->{$this->MODEL_NAME}->indecator_data_tb_get(13,$for_month,$to_month); // تحصيل الخدمات
       $arr20 = $this->{$this->MODEL_NAME}->indecator_data_tb_get(14,$for_month,$to_month); // تصنيفات اخرى

        $arr6=array();
        //$arr1=array();
        //$arr2=array();
      //  $arr3=array();
       // $arr4=array();
        //$arr5=array();
        //$arr7=array();
       // $arr8=array();
      //  $arr9=array();
        $arr10=array();
      //  $arr11=array();
        //$arr12=array();
       // $arr13=array();
     //  $arr14=array();
     //   $arr15=array();
      //  $arr16=array();
      //  $arr17=array();
       // $arr18=array();
       // $arr19=array();
     //   $arr20=array();









        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode(array($arr1,$arr2,$arr3,$arr4,$arr5,$arr6,$arr7,$arr8,$arr9,$arr10,$arr11,$arr12,$arr13,$arr14,$arr15,$arr16,$arr17,$arr18,$arr19,$arr20)));






    }
    /********************************************************************************************************************************/
    function _look_ups(&$data){
        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');

        add_js('select2.min.js');
        add_css('select2_metro_rtl.css');
        add_css('combotree.css');
        add_css('font-awesome.min.css');
        add_js('jquery.hotkeys.js');

        add_css('jquery.dataTables.css');



    }









}

?>
