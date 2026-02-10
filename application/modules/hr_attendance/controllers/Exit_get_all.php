<?php

class Exit_get_all extends MY_Controller{
    function  __construct(){
        parent::__construct();
        $this->load->model('root/rmodel');
        $this->rmodel->package = 'HR_ATTENDANCE_PKG';
    }

    function public_get(){
		// TO_CHAR(dd,'dd/mm/yyyy HH24:mi:ss') 

        $offset=0;
        $row=100;
        $where_sql="
         
        ";
        $data['page_rows'] = $this->rmodel->getList('EXIT_PERMISSION_GET_TB', $where_sql, $offset , $row );
        echo "<pre>";
        print_r( $data['page_rows']  );

    }

}

?>