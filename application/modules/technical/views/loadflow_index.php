<?php
/**
 * Created by PhpStorm.
 * User: abarakat
 * Date: 10/12/17
 * Time: 09:30 ص
 */

$delete_url = base_url('technical/LoadFlow/delete');
$get_url = base_url('technical/LoadFlow/get_id');
$edit_url = base_url('technical/LoadFlow/edit');
$create_url = base_url('technical/LoadFlow/create');
$get_page_url = base_url('technical/LoadFlow/get_page');
$report_url = base_url("JsperReport/showreport?sys=technical/loadFlow");

?>

<div class="row">
    <div class="toolbar">

        <div class="caption"><?= $title ?></div>

        <ul>
            <?php if (HaveAccess($create_url)): ?>
                <li><a href="<?= $create_url . '/' ?>"><i class="glyphicon glyphicon-plus"></i>جديد </a>
                </li><?php endif; ?>

            <!--     <li><a href="#">بحث</a> </li>-->
        </ul>

    </div>

    <div class="form-body">
        <fieldset>
            <legend>بحـث</legend>

            <div class="modal-body inline_form">


                <div class="form-group col-sm-1">
                    <label class="control-label"> Project Tec</label>

                    <div>
                        <input type="text" name="tec_num" id="tec_num" class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label"> From date</label>

                    <div>
                        <input type="text" data-type="date" data-date-format="DD/MM/YYYY" name="from_date"
                               id="txt_from_date" class="form-control">
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label"> To date</label>

                    <div>
                        <input type="text" data-type="date" data-date-format="DD/MM/YYYY" name="t_date" id="txt_to_date"
                               class="form-control">
                    </div>
                </div>


            </div>

            <div class="modal-footer">

                <input type="radio"  name="rep_type_id" value="pdf" checked="checked">
                <sub><i class="fa fa-file-pdf-o" style="font-size:28px;color:#e2574c"></i></sub>
                &nbsp;&nbsp;&nbsp;&nbsp;
                <input type="radio"  name="rep_type_id" value="xls">
                <sub><i class="fa fa-file-excel-o" style="font-size:28px;color:#1d7044"></i></sub>
                &nbsp;&nbsp;&nbsp;&nbsp;
                <input type="radio"  name="rep_type_id" value="doc">
                <sub><i class="fa fa-file-word-o" style="font-size:28px;color:#2a5696"></i></sub>
                &nbsp;&nbsp;&nbsp;&nbsp;
                <button type="button" id="rep_master" onclick="javascript:print_report('1','','');" class="btn btn-primary">Show Report</button>
                <button type="button" onclick="javascript:do_search();" class="btn btn-success">Search</button>

                <button type="button" onclick="javascript:clearForm_any($('fieldset'));do_search();"
                        class="btn btn-default"> Reset
                </button>

            </div>
        </fieldset>

        <div id="msg_container"></div>

        <div id="container">

            <?php echo modules::run('technical/LoadFlow/get_page', $page, $action); ?>

        </div>

    </div>

</div>




<?php
$scripts = <<<SCRIPT

<script type="text/javascript">

        if (typeof initFunctions == 'function') {
            initFunctions();
        }
        if (typeof ajax_pager == 'function') {
            ajax_pager();
        }

        function getReportUrl(rep_id,ser,study_type){
            var from_date = $('#txt_from_date').val();
            var to_date = $('#txt_to_date').val();
            var rep_type = $('input[name=rep_type_id]:checked').val();
            if(rep_type ==null) {rep_type='pdf';}

            var repUrl;

            if(rep_id == 1) {
                repUrl = '{$report_url}&report_type='+rep_type+'&report=load_flow_measure_master&p_ser='+ser+'&p_from_date='+from_date+'&p_to_date='+to_date+'&p_rep_type='+rep_type+'';
            }else {
                    switch(study_type) {
                        case "1":
                            repUrl = '{$report_url}&report_type='+rep_type+'&report=load_flow_measure_details_MV_R&p_ser='+ser+'&p_rep_type='+rep_type+'';
                            break;
                        case "2":
                            repUrl = '{$report_url}&report_type='+rep_type+'&report=load_flow_measure_details_LV_R&p_ser='+ser+'&p_rep_type='+rep_type+'';
                            break;
                        case "3":
                            repUrl = '{$report_url}&report_type='+rep_type+'&report=load_flow_measure_details_MV_P&p_ser='+ser+'&p_rep_type='+rep_type+'';
                            break;
                        case "4":
                            repUrl = '{$report_url}&report_type='+rep_type+'&report=load_flow_measure_details_LV_P&p_ser='+ser+'&p_rep_type='+rep_type+'';
                            break;
                    }
            }
            return repUrl;
        }

        function print_report(rep_id,ser,study_type){

            var rep_url = getReportUrl(rep_id,ser,study_type);
            _showReport(rep_url);

        }

       function delete_loadflow(a,id,url){
        if(confirm('هل تريد حذف البند ؟!')){

            get_data(url,{id:id},function(data){
                if(data == '1'){
                    restInputs($(a).closest('tr'));
                }else{
                    danger_msg( 'تحذير','فشل في العملية');
                }
            },'html');
        }
        
    }
    
    
      function reBind(){

    ajax_pager({from_date:$('#txt_from_date').val() , to_date:$('#txt_to_date').val() , tec_num: $('#tec_num').val() });

    }

    function LoadingData(){

    ajax_pager_data('#projectTbl > tbody',{from_date:$('#txt_from_date').val() , to_date:$('#txt_to_date').val() , tec_num: $('#tec_num').val()});

    }


   function do_search(){

        get_data('{$get_page_url}',{page: 1,from_date:$('#txt_from_date').val() , to_date:$('#txt_to_date').val() , tec_num: $('#tec_num').val() },function(data){
            $('#container').html(data);

            reBind();

        },'html');
    }
    

</script>

SCRIPT;

sec_scripts($scripts);



?>
