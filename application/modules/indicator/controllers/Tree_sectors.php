<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 15/09/18
 * Time: 12:48 م
 */

class tree_sectors extends MY_Controller{

    var $MODEL_NAME= 'tree_model';
    var $PAGE_URL= 'indicator/indicate_target/get_page';


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
    /*                                                             Manage tree Indicator                                                                      */
    /******************************************************************************************************************************************************/

function _get_structure($parent) {
        $result = $this->{$this->MODEL_NAME}->get_tree_sectors($parent);


        $i = 0;

        foreach($result as $key => $item)
        {


            $result[$i]['subs'] = $this->_get_structure($item['ID']);
           // var_dump($result[$i]['subs']);


            $i++;


        }


        return $result;
    }

    function index()
    {

      $this->load->helper('generate_list');

        // add_css('jquery-hor-tree.css');
        add_css('combotree.css');
        add_css('tabs.css');
        add_js('jquery.tree.js');
        add_js('tabs.js');
        $data['title']=' شجرة القطاعات و المؤشرات الرئيسية و الفرعية ';
        $data['content']='plan_tree_view';


        $resource =  $this->_get_structure(-1);

        $options = array(
            'template_head'=>'<ul>',
            'template_foot'=>'</ul>',
            'use_top_wrapper'=>false
        );

        $template = '<li ><span data-id="{ID}" ondblclick="javascript:system_menu_get(\'{ID}\');"><i class="glyphicon glyphicon-minus-sign"></i><div0 data-is-active="{IS_ACTIVE}" class="is_active"> </div0> {ID_NAME}</span>{SUBS}</li>';

        $data['tree'] = '<ul class="tree" id="plan_tree">'.generate_list($resource, $options, $template).'</ul>';
        // $data['tree'] = '<ul class="tree" id="accounts"><li class="parent_li"><span data-id="0" >شركة الكهرباء</span><ul>'.generate_list($resource, $options, $template).'</ul></li></ul>';


        $data['help']=$this->help;
$this->_look_ups($data);
        $this->load->view('template/template',$data);




    }
    /***************************************************************************************************************************************/
function create()
    {

        $result = $this->{$this->MODEL_NAME}->create($this->_postedData('create'));
        if (intval($result) <= 0) {
            $this->print_error('لم يتم الحفظ'.'<br>'.$result);
        }
        $result= '{"id":"'.$result.'"}';
        $this->return_json($result);


    }
    function get_id()
    {

        $id = $this->input->post('id');
        $result = $this->{$this->MODEL_NAME}->get($id);

        $this->output->set_content_type('application/json');
        $this->output->set_output(json_encode($result));
    }
    function edit()
    {

        $result=  $this->{$this->MODEL_NAME}->edit($this->_postedData());
        if (intval($result) <= 0) {
            $this->print_error('لم يتم الحفظ'.'<br>'.$result);
        }

        echo intval($result);

    }

    function delete()
    {

        $id = $this->input->post('id');

        if ($id ==0)
        {

            $this->print_error('!!لا يمكن حذف الشجرة');
        }

        $this->IsAuthorized();

        if (is_array($id)) {
            foreach ($id as $val) {
                $result= $this->{$this->MODEL_NAME}->delete($val);

                if (intval($result) <= 0) {
                    $this->print_error('!!لم تتم عملية الحذف'.'<br>'.$result);
                }

                echo intval($result);
            }
        } else {
            $result= $this->{$this->MODEL_NAME}->delete($id);

            if (intval($result) <= 0) {
                $this->print_error('!!لم تتم عملية الحذف'.'<br>'.$result);
            }

            echo intval($result);
        }

    }
	function _postedData($create = null)
    {

									  
		$ID = $this->input->post('menu_no');
        $ID_NAME = $this->input->post('menu_add');
        $ID_FATHER = $this->input->post('menu_parent_no');
        $STATUS = $this->input->post('status');
                
        $result = array(
		    array('name' => 'ID', 'value' => $ID, 'type' => '', 'length' => -1),
            array('name' => 'ID_NAME', 'value' => $ID_NAME, 'type' => '', 'length' => -1),
            array('name' => 'ID_FATHER', 'value' => $ID_FATHER, 'type' => '', 'length' => -1),
            array('name' => 'STATUS', 'value' => $STATUS, 'type' => '', 'length' => -1),
            array('name'=>'USER_INSERT','value'=>$this->user->id,'type'=>'','length'=>-1),

        );


        if ($create == 'create') {
            array_shift($result);
        }

        return $result;
    }
	
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

      
        $data['status'] = $this->constant_details_model->get_list(229);
       

    }
    
}

?>
