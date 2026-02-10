<?php
/**
 * Created by PhpStorm.
 * User: ashikhali
 * Date: 08/09/19
 * Time: 01:44 م
 */

class indecator_permissions extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('settings/constant_details_model');
        $this->load->model('settings/gcc_branches_model');
        $data['branches'] = $this->gcc_branches_model->get_all();


    }


    function public_index()
    {
        $data['title']='ادارة صلاحيات نظم المعلومات والمؤشرات';
        $data['content']='indecator_permissions_index';
        $data['help']=$this->help;
        $this->load->view('template/template',$data);

    }

}