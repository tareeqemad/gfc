<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 06/06/18
 * Time: 01:07 م
 */
class indicate_target extends MY_Controller{

    var $MODEL_NAME= 'indicator_model';
    var $PAGE_URL= 'indicator/indicate_target/get_page_target';
    var $PAGE_DISPLAY_URL= 'indicator/indicate_target/get_page_display_target';

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



    function target()
    {


        $data['title']='ادخال المستهدف';
        $data['content']='indicate_target';
        $data['help']=$this->help;
        $this->_look_ups($data);
        $this->load->view('template/template',$data);




    }

/***************************************************************************************************************************************/

    function get_page_target($page = 1)
    {

        $from_month=date("Ym");
       $data['page_rows'] = $this->{$this->MODEL_NAME}->indecator_info_tb_get($this->p_sector,$this->p_txt_for_month,$this->p_entry_way);
        /*echo var_dump($this->{$this->MODEL_NAME}->indecator_info_tb_get($this->p_sector,$from_month));
         die;*/
        $this->_look_ups($data);


       $this->load->view('indicator_target_page', $data);



    }
    /********************************************************************************************************************************/
    function get_page_display_target($page = 1)
    {


        $from_month=date("Ym");
        //$data['page_rows'] = $this->{$this->MODEL_NAME}->indecator_info_tb_branch_get($this->p_sector,$this->from_month,$this->p_entry_way);
        /*echo var_dump($this->{$this->MODEL_NAME}->indecator_info_tb_get($this->p_sector,$from_month));
         die;*/
        //$this->_look_ups($data);
if($this->p_sector==0)
 $this->p_sector='';
// $this->p_branch='';
 //$this->p_adopts='';
            $data['page_rows'] = $this->{$this->MODEL_NAME}->indecator_info_tb_branch_get($this->p_sector,$this->p_class,$this->p_txt_for_month,$this->p_branch,$this->p_adopts,1);

            $data['branch']=$this->p_branch;
            /*echo var_dump($this->{$this->MODEL_NAME}->indecator_info_tb_get($this->p_sector,$from_month));
             die;*/
            //$data['branch']=$this->user->branch;
            $this->_look_ups($data);


           // $this->load->view('indicator_display_page', $data);
        $this->load->view('indicator_display_page', $data);



    }
	/***************************************************************************************************************************************/
	function tahseel_target_index()
    {


        $data['title']='مستهدف التحصيل';
        $data['content']='indicate_tahseel_index';
        $data['help']=$this->help;
        //$data['year_indicator']=$this->year;
        $this->_look_ups($data);
        $this->load->view('template/template',$data);




    }
	/*********************************************************************************************************************************/
	function create_tahseel_target()
    {



 if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            for ($i = 0; $i < count($this->p_indecator_ser); $i++)
            {

  

                     if($this->p_SER[$i]==0)
                     {
                         $x=$this->{$this->MODEL_NAME}->create_target_tahseel($this->tahseel_postedData('create',null,$this->p_indecator_ser[$i],$this->p_txt_percent[$i],$this->p_val,$this->p_for_month));

                         if (intval($x) <= 0) {
                             $this->print_error('لم يتم الحفظ' . '<br>' . $x);
                         }


                     }
                     

                else
                {
                    $x=$this->{$this->MODEL_NAME}->edit_target_tahseel($this->tahseel_postedData('edit',$this->p_SER[$i],$this->p_indecator_ser[$i],$this->p_txt_percent[$i],$this->p_val,$this->p_for_month));

                    if (intval($x) <= 0) {
                        $this->print_error('لم يتم الحفظ' . '<br>' . $x);
                    }


                }

                

                



              

            }
            echo intval($x);
        }
        else {
		 $result=array();
		  $data['action'] = 'index';
		  
         $data['help']=$this->help;
         $data['isCreate']= 1;
		$for_month=date("Ym",strtotime("-1 month"));
        $data['title']='مستهدف التحصيل';
		 $data['page_rows'] = $this->{$this->MODEL_NAME}->get_tahseel($for_month);
        $data['content']='indicate_tahseel_target';
        $data['help']=$this->help;
        $this->_look_ups($data);
        $this->load->view('template/template',$data);
           
        }


    }
/*************************************************************************************************************************************/
function tahseel_target($for_month)
    {



 if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            for ($i = 0; $i < count($this->p_indecator_ser); $i++)
            {

  

                     if($this->p_SER[$i]==0)
                     {
                         $x=$this->{$this->MODEL_NAME}->create_target_tahseel($this->tahseel_postedData('create',null,$this->p_indecator_ser[$i],$this->p_txt_percent[$i],$this->p_val,$this->p_for_month));

                         if (intval($x) <= 0) {
                             $this->print_error('لم يتم الحفظ' . '<br>' . $x);
                         }


                     }
                     

                else
                {
                    $x=$this->{$this->MODEL_NAME}->edit_target_tahseel($this->tahseel_postedData('edit',$this->p_SER[$i],$this->p_indecator_ser[$i],$this->p_txt_percent[$i],$this->p_val,$this->p_for_month));

                    if (intval($x) <= 0) {
                        $this->print_error('لم يتم الحفظ' . '<br>' . $x);
                    }


                }

                

                



              

            }
            echo intval($x);
        }
        else {
		 $result=array();
		  $data['action'] = 'index';
		  
         $data['help']=$this->help;
         $data['isCreate']= 0;
	
        $data['title']='مستهدف التحصيل';

		 $data['page_rows'] = $this->{$this->MODEL_NAME}->get_tahseel($for_month);

        $data['content']='indicate_tahseel_target';
        $data['help']=$this->help;
        $this->_look_ups($data);
        $this->load->view('template/template',$data);
           
        }


    }
/***************************************************************************************************************************************/

    function public_get_page_tahseel_res($page = 1)
    {
        $this->load->library('pagination');
        $where_sql = "";

    
      $where_sql .= isset($this->p_year) && $this->p_year!= null ? " AND  TO_CHAR(TO_DATE(TO_CHAR(FOR_MONTH),'YYYYMM'),'YYYY')  ={$this->p_year}  " :" AND  TO_CHAR(TO_DATE(TO_CHAR(FOR_MONTH),'YYYYMM'),'YYYY')  =  ".date("Y");
      
      $where_sql .= isset($this->p_for_month) && $this->p_for_month != null ? " AND  X.FOR_MONTH  ={$this->p_for_month}  " : '';

        $config['base_url'] = base_url($this->PAGE_URL);
        $count_rs = $this->get_table_count('TAHSEL_VALUES_TB', $where_sql);
        $config['use_page_numbers'] = TRUE;
        $config['total_rows'] = count($count_rs) ? $count_rs[0]['NUM_ROWS'] : 0;
        $config['per_page'] = $this->page_size;
        $config['num_links'] = 20;
        $config['cur_page'] = $page;

        $config['full_tag_open'] = '<div class="pagination-container"><ul class="pagination">';
        $config['full_tag_close'] = '</ul></div>';
        $config['first_tag_open'] = $config['last_tag_open'] = $config['next_tag_open'] = $config['prev_tag_open'] = $config['num_tag_open'] = '<li>';
        $config['first_tag_close'] = $config['last_tag_close'] = $config['next_tag_close'] = $config['prev_tag_close'] = $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><span><b>';
        $config['cur_tag_close'] = "</b></span></li>";

        $this->pagination->initialize($config);

        $offset = (((($page) - 1) * $config['per_page']));
        $row = ((($page) * $config['per_page']));
		
        $data['page_rows']=$this->{$this->MODEL_NAME}->get_list_tahseel($where_sql, $offset, $row);
        $data['offset'] = $offset + 1;
        $data['page'] = $page;
        $this->_look_ups($data);
        $this->load->view('indicate_tahseel_page', $data);


    }
	/********************************************************************************************************************************/
	function save_all_target_tahseel()
	{
	 if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            for ($i = 0; $i < count($this->p_indecator_ser); $i++)
            {

  

                     if($this->p_SER[$i]==0)
                     {
                         $x=$this->{$this->MODEL_NAME}->create_target_tahseel($this->tahseel_postedData('create',null,$this->p_indecator_ser[$i],$this->p_txt_percent[$i],$this->p_val,$this->p_for_month));

                         if (intval($x) <= 0) {
                             $this->print_error('لم يتم الحفظ' . '<br>' . $x);
                         }


                     }
                else
                {
                    $x=$this->{$this->MODEL_NAME}->edit_target_tahseel($this->tahseel_postedData('edit',$this->p_SER[$i],$this->p_indecator_ser[$i],$this->p_txt_percent[$i],$this->p_val,$this->p_for_month));

                    if (intval($x) <= 0) {
                        $this->print_error('لم يتم الحفظ' . '<br>' . $x);
                    }


                }

                

               

                

                



              

            }
            echo intval($x);
        }
	}
	/*********************************************************************************************************************************/
	 function public_get_page_tahseel($for_month = 0,$isCreate)
    {


        //$data['page_rows']=	array();
if($for_month == 0)
$for_month=date("Ym",strtotime("-1 month"));


           $data['isCreate']=$isCreate;
           $data['page_rows'] = $this->{$this->MODEL_NAME}->get_tahseel($for_month);

           
            $this->_look_ups($data);


            $this->load->view('tahseel_val', $data);
        






    }
    /********************************************************************************************************************************/
    function get_page_display_branch($page = 1)
    {


        $from_month=date("Ym");
        if($this->p_branch<>0)
        {
            if($this->p_sector==0)
                $this->p_sector='';
            $data['page_rows'] = $this->{$this->MODEL_NAME}->indecator_info_tb_branch_get($this->p_sector,$this->p_txt_for_month,$this->p_branch,$this->p_adopts,'');

            $data['branch']=$this->p_branch;
            /*echo var_dump($this->{$this->MODEL_NAME}->indecator_info_tb_get($this->p_sector,$from_month));
             die;*/
            //$data['branch']=$this->user->branch;
            $this->_look_ups($data);


            $this->load->view('indicator_branch_display_all', $data);
        }
        else
        {
            if($this->p_sector==0)
                $this->p_sector='';
            $data['page_rows'] = $this->{$this->MODEL_NAME}->indecator_info_tb_branch_get($this->p_sector,$this->p_txt_for_month,$this->p_branch,$this->p_adopts,'');

            $data['branch']=$this->p_branch;
            /*echo var_dump($this->{$this->MODEL_NAME}->indecator_info_tb_get($this->p_sector,$from_month));
             die;*/
            //$data['branch']=$this->user->branch;
            $this->_look_ups($data);


            $this->load->view('indicator_display_all_page', $data);

        }






    }
    /********************************************************************************************************************************/
    function get_page_display($page = 1)
    {


        $from_month=date("Ym");
        if($this->p_branch<>0)
        {
            if($this->p_sector==0)
                $this->p_sector='';
        $data['page_rows'] = $this->{$this->MODEL_NAME}->indecator_info_tb_branch_get($this->p_sector,$this->p_class,$this->p_txt_for_month,$this->p_branch,$this->p_adopts,1);

        $data['branch']=$this->p_branch;
        /*echo var_dump($this->{$this->MODEL_NAME}->indecator_info_tb_get($this->p_sector,$from_month));
         die;*/
        //$data['branch']=$this->user->branch;
        $this->_look_ups($data);


        $this->load->view('indicator_branch_display', $data);
        }
        else
        {
            if($this->p_sector==0)
                $this->p_sector='';
            $data['page_rows'] = $this->{$this->MODEL_NAME}->indecator_info_tb_branch_get($this->p_sector,$this->p_class,$this->p_txt_for_month,$this->p_branch,$this->p_adopts,1);

            $data['branch']=$this->p_branch;
            /*echo var_dump($this->{$this->MODEL_NAME}->indecator_info_tb_get($this->p_sector,$from_month));
             die;*/
            //$data['branch']=$this->user->branch;
            $this->_look_ups($data);


            $this->load->view('indicator_display_page', $data);

        }



    }
	 /********************************************************************************************************************************/
    function public_get_data_indicator_info($page = 1)
    {


        $from_month=date("Ym");
        if($this->p_branch<>0)
        {
            if($this->p_sector==0)
                $this->p_sector='';
        $data['page_rows'] = $this->{$this->MODEL_NAME}->indecator_info_tb_branch_get($this->p_sector,$this->p_class,$this->p_txt_for_month,$this->p_branch,$this->p_adopts,1);

        $data['branch']=$this->p_branch;
        /*echo var_dump($this->{$this->MODEL_NAME}->indecator_info_tb_get($this->p_sector,$from_month));
         die;*/
        //$data['branch']=$this->user->branch;
        $this->_look_ups($data);


        $this->load->view('indicator_data_branch_info', $data);
        }
      /*else
        {
            if($this->p_sector==0)
                $this->p_sector='';
            $data['page_rows'] = $this->{$this->MODEL_NAME}->indecator_info_tb_branch_get($this->p_sector,$this->p_class,$this->p_txt_for_month,$this->p_branch,$this->p_adopts,1);

            $data['branch']=$this->p_branch;
         
            $this->_look_ups($data);


            $this->load->view('indicator_display_page', $data);

        }*/



    }
    /*******************************************************************************************************************************/
    /*******************************************************************************************************************************/
    function public_get_sector($sector=0){

        echo  modules::run($this->PAGE_URL);
      /* $sector = $this->input->post('sector')?$this->input->post('sector'):$sector;

        $arr = $this->{$this->MODEL_NAME}->indecator_info_tb_get($sector,date("Ym"));



        echo json_encode($arr);*/





    }
    /*******************************************************************************************************************************/
    function public_get_display_sector($sector=0){

        echo  modules::run($this->PAGE_DISPLAY_URL);
        /* $sector = $this->input->post('sector')?$this->input->post('sector'):$sector;

          $arr = $this->{$this->MODEL_NAME}->indecator_info_tb_get($sector,date("Ym"));



          echo json_encode($arr);*/





    }
    /*******************************************************************************************************************************/
    function save_all_target()
    {
        $x=0;

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            for ($i = 0; $i < count($this->p_indecator_ser); $i++)
            {
               // if ($this->p_ser_plan[$i] <= 0 and $this->p_status[$i]!='' and $this->p_is_end[$i] and $this->p_persant[$i]!='' ) {

                     if($this->p_north_seq[$i]==0)
                     {
                         $x=$this->{$this->MODEL_NAME}->create_target_branch($this->_postedData('create',null,$this->p_indecator_ser[$i],3,$this->p_for_month,$this->p_txt_north[$i],$this->p_txt_t_persant[$i]));

                         if (intval($x) <= 0) {
                             $this->print_error('لم يتم الحفظ' . '<br>' . $x);
                         }


                     }
                else
                {
                    $x=$this->{$this->MODEL_NAME}->edit_target_branch($this->_postedData('edit',$this->p_north_seq[$i],$this->p_indecator_ser[$i],3,$this->p_for_month,$this->p_txt_north[$i],$this->p_txt_t_persant[$i]));

                    if (intval($x) <= 0) {
                        $this->print_error('لم يتم الحفظ' . '<br>' . $x);
                    }


                }

                if($this->p_gaza_seq[$i]==0)
                {
                    $x=$this->{$this->MODEL_NAME}->create_target_branch($this->_postedData('create',null,$this->p_indecator_ser[$i],2,$this->p_for_month,$this->p_txt_gaza[$i],$this->p_txt_t_persant[$i]));

                    if (intval($x) <= 0) {
                        $this->print_error('لم يتم الحفظ' . '<br>' . $x);
                    }


                }
                else
                {
                    $x=$this->{$this->MODEL_NAME}->edit_target_branch($this->_postedData('edit',$this->p_gaza_seq[$i],$this->p_indecator_ser[$i],2,$this->p_for_month,$this->p_txt_gaza[$i],$this->p_txt_t_persant[$i]));

                    if (intval($x) <= 0) {
                        $this->print_error('لم يتم الحفظ' . '<br>' . $x);
                    }


                }

                if($this->p_middle_seq[$i]==0)
                {
                    $x=$this->{$this->MODEL_NAME}->create_target_branch($this->_postedData('create',null,$this->p_indecator_ser[$i],4,$this->p_for_month,$this->p_txt_middle[$i],$this->p_txt_t_persant[$i]));

                    if (intval($x) <= 0) {
                        $this->print_error('لم يتم الحفظ' . '<br>' . $x);
                    }


                }
                else
                {
                    $x=$this->{$this->MODEL_NAME}->edit_target_branch($this->_postedData('edit',$this->p_middle_seq[$i],$this->p_indecator_ser[$i],4,$this->p_for_month,$this->p_txt_middle[$i],$this->p_txt_t_persant[$i]));

                    if (intval($x) <= 0) {
                        $this->print_error('لم يتم الحفظ' . '<br>' . $x);
                    }


                }

                if($this->p_khan_seq[$i]==0)
                {
                    $x=$this->{$this->MODEL_NAME}->create_target_branch($this->_postedData('create',null,$this->p_indecator_ser[$i],6,$this->p_for_month,$this->p_txt_khan[$i],$this->p_txt_t_persant[$i]));

                    if (intval($x) <= 0) {
                        $this->print_error('لم يتم الحفظ' . '<br>' . $x);
                    }


                }
                else
                {
                    $x=$this->{$this->MODEL_NAME}->edit_target_branch($this->_postedData('edit',$this->p_khan_seq[$i],$this->p_indecator_ser[$i],6,$this->p_for_month,$this->p_txt_khan[$i],$this->p_txt_t_persant[$i]));

                    if (intval($x) <= 0) {
                        $this->print_error('لم يتم الحفظ' . '<br>' . $x);
                    }


                }

                if($this->p_rafa_seq[$i]==0)
                {
                    $x=$this->{$this->MODEL_NAME}->create_target_branch($this->_postedData('create',null,$this->p_indecator_ser[$i],7,$this->p_for_month,$this->p_txt_rafa[$i],$this->p_txt_t_persant[$i]));

                    if (intval($x) <= 0) {
                        $this->print_error('لم يتم الحفظ' . '<br>' . $x);
                    }


                }
                else
                {
                    $x=$this->{$this->MODEL_NAME}->edit_target_branch($this->_postedData('edit',$this->p_rafa_seq[$i],$this->p_indecator_ser[$i],7,$this->p_for_month,$this->p_txt_rafa[$i],$this->p_txt_t_persant[$i]));

                    if (intval($x) <= 0) {
                        $this->print_error('لم يتم الحفظ' . '<br>' . $x);
                    }


                }



               // }

               /* else if($this->p_ser_plan[$i] > 0 and $this->p_status[$i]!='' and $this->p_is_end[$i] and $this->p_persant[$i]!='' ) {


                    $x=$this->{$this->MODEL_NAME}->edit_target_branch($this->_postedData($this->p_ser[$i],$this->p_ser_plan[$i], $this->p_status[$i], $this->p_persant[$i], $this->p_notes[$i], $this->p_is_end[$i],
                        'edit'));

                    if (intval($x) <= 0) {
                        $this->print_error('لم يتم الحفظ' . '<br>' . $x);
                    }



                }*/

            }
            echo intval($x);
        }

    }
    /********************************************************************************************************************************/
    function adopt()
    {

        if ($_SERVER['REQUEST_METHOD'] == 'POST' and ($this->p_sector != '' ||$this->p_sector != 0) ) {
           $res = $this->{$this->MODEL_NAME}->adopt($this->p_sector,$this->p_txt_for_month,$this->p_entry_way,$this->p_adopt,$this->user->id);

            if (intval($res) <= 0) {
                echo intval($res);
            }
            else
                echo intval($res);

        } else
            echo "لم يتم ارسال رقم القطاع";
    }
    /********************************************************************************************************************************/
    function unadopt()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' and ($this->p_sector != '' ||$this->p_sector != 0)) {

            $res = $this->{$this->MODEL_NAME}->adopt($this->p_sector,$this->p_txt_for_month,$this->p_entry_way,$this->p_adopt,$this->user->id);

            if (intval($res) <= 0) {
                echo intval($res);
            }
            else
                echo intval($res);

        } else
            echo "لم يتم ارسال رقم القطاع";
    }
    /********************************************************************************************************************************/
    function public_get_is_adopt()
    {

        $result = $this->{$this->MODEL_NAME}->indecator_target_tb_is_adopt($this->p_sector,$this->p_txt_for_month,$this->p_entry_way);
        $this->return_json($result);

    }
    /*********************************************************************************************************************************/
    function compare_indicator()
    {
        $data['title']='مقارنة أداء المقرات';
        $data['content']='indicate_show';
        $data['help']=$this->help;
        $this->_look_ups($data);
        $this->load->view('template/template',$data);

    }
    /*******************************************************************************************************************************/
    function display_indicator()
    {
        $data['title']='نتائج مؤشرات اداء المقرات';
        //if($this->user->branch== 1)
        $data['content']='indecatior_branch_show';

        /*else
        $data['content']='indecatior_branchs_show';
        */
        //$data['branch']=$this->branch;
        $data['help']=$this->help;
        $this->_look_ups($data);
        $this->load->view('template/template',$data);

    }
	/*******************************************************************************************************************************/
    function data_indicator_info()
    {
        $data['title']='معلومات عن المؤشرات';
        //if($this->user->branch== 1)
        $data['content']='data_indicator_info_show';

        /*else
        $data['content']='indecatior_branchs_show';
        */
        //$data['branch']=$this->branch;
        $data['help']=$this->help;
        $this->_look_ups($data);
        $this->load->view('template/template',$data);

    }
    /*********************************************************************************************************************************/

    /*********************************************************************************************************************************/
    function show_indicator_branch()
    {
        $data['title']='عرض مؤشرات الأداء';
        //if($this->user->branch== 1)
        //$data['content']='indecatior_branch_show';
        $data['content']='show_indicator_branch';
        /*else
        $data['content']='indecatior_branchs_show';
        */
        //$data['branch']=$this->branch;
        $data['help']=$this->help;
        $this->_look_ups($data);
        $this->load->view('template/template',$data);

    }
	 /*********************************************************************************************************************************/

    function display_gedco_result()
    {
        $data['title']='نتائج مؤشرات أداء الشركة';
        //if($this->user->branch== 1)
        $data['content']='indecatior_gedco_show';

        /*else
        $data['content']='indecatior_branchs_show';
        */
        //$data['branch']=$this->branch;
        $data['help']=$this->help;
        $this->_look_ups($data);
        $this->load->view('template/template',$data);

    }
    /***********************************************************************************************************************************/
    /********************************************************************************************************************************/
    function public_get_page_gedco($page = 1)
    {


        $this_from_month=date("Ym");

            if($this->p_sector==0)
                $this->p_sector='';
				 $sector= isset($this->p_sector) && $this->p_sector != null ? $this->p_sector : "";
				 $class= isset($this->p_class) && $this->p_class != null ? $this->p_class : "";
                 $for_month= isset($this->p_txt_for_month) && $this->p_txt_for_month != null ? $this->p_txt_for_month : $this_from_month;
				 $branch= isset($this->p_branch) && $this->p_branch != null ? $this->p_branch : '';
				 $adopts= isset($this->p_adopts) && $this->p_adopts != null ? $this->p_adopts : '';
            $data['page_rows'] = $this->{$this->MODEL_NAME}->indecator_info_tb_branch_get($sector,$class,$for_month,$branch,$adopts,1);

            $data['branch']=$branch;
            /*echo var_dump($this->{$this->MODEL_NAME}->indecator_info_tb_get($this->p_sector,$from_month));
             die;*/
            //$data['branch']=$this->user->branch;
            $this->_look_ups($data);


            $this->load->view('indicator_gedco_page_display', $data);





    }
    /*******************************************************************************************************************************/
    /*******************************************************************************************************************************/
    function _look_ups(&$data){
        add_css('datepicker3.css');
        add_js('moment.js');
        add_js('bootstrap-datetimepicker.js');
        add_js('bootstrap.min.js');
        add_js('select2.min.js');
        add_css('select2_metro_rtl.css');
        add_css('combotree.css');
        add_css('font-awesome.min.css');
        add_js('jquery.hotkeys.js');

        add_css('jquery.dataTables.css');

        $data['sector'] = $this->constant_details_model->get_list(223);
        $data['enter_way'] = $this->constant_details_model->get_list(226);
        $data['found_target'] = $this->constant_details_model->get_list(227);
        $data['adopt_const'] = $this->constant_details_model->get_list(235);
        $data['branches'] = $this->gcc_branches_model->get_all();
       /* if ($this->user->branch==1)
        {


            $data['branches'] = $this->gcc_branches_model->get_all();
        }
        else
        {

            $data['branches'] =$this->gcc_branches_model->user_branch($this->user->id);

        }*/


    }





    /*****************************************************************************************************************************/
    function _postedData($type=null,$t_ser,$t_indecator_ser,$t_branch,$t_month,$t_value,$t_persant){


        $result = array(
            array('name'=>'T_SER','value'=>$t_ser ,'type'=>'','length'=>-1),
            array('name'=>'T_INDECATOR_SER','value'=>$t_indecator_ser,'type'=>'','length'=>-1),
            array('name'=>'T_BRANCH','value'=>$t_branch,'type'=>'','length'=>-1),
            array('name'=>'T_MONTH','value'=>$t_month,'type'=>'','length'=>-1),
            array('name'=>'T_VALUE','value'=>$t_value,'type'=>'','length'=>-1),
            array('name'=>'T_PERSANT','value'=>$t_persant,'type'=>'','length'=>-1),
            array('name'=>'T_USER_ID','value'=>$this->user->id,'type'=>'','length'=>-1),





        );






        if($type=='create'){
            array_shift($result);
        }

        return $result;




    }

 /*****************************************************************************************************************************/
    function tahseel_postedData($type=null,$SER,$indecator_ser,$percent,$val,$for_month){



        $result = array(
            array('name'=>'SER','value'=>$SER ,'type'=>'','length'=>-1),
            array('name'=>'INDECATOR_SER','value'=>$indecator_ser,'type'=>'','length'=>-1),
            array('name'=>'PERCENT','value'=>$percent,'type'=>'','length'=>-1),
            array('name'=>'VAL','value'=>$val,'type'=>'','length'=>-1),
            array('name'=>'FOR_MONTH','value'=>$for_month,'type'=>'','length'=>-1),
            array('name'=>'T_USER_ID','value'=>$this->user->id,'type'=>'','length'=>-1),





        );






        if($type=='create'){
            array_shift($result);
        }

        return $result;




    }

}

?>
