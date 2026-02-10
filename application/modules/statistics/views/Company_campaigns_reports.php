<?php
/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 28/03/22
 * Time: 09:10 ص
 */

$MODULE_NAME= 'statistics';
$TB_NAME= 'Company_campaigns';
$first_stage_url = base_url("$MODULE_NAME/$TB_NAME/public_first_stage");
$second_stage_url = base_url("$MODULE_NAME/$TB_NAME/public_second_stage");
$third_stage_url = base_url("$MODULE_NAME/$TB_NAME/public_third_stage");
$fourth_stage_url = base_url("$MODULE_NAME/$TB_NAME/public_fourth_stage");
$fifth_stage_url = base_url("$MODULE_NAME/$TB_NAME/public_fifth_stage");
$third_stage_chart_url = base_url("$MODULE_NAME/$TB_NAME/public_third_stage_chart");
$report_url = base_url("JsperReport/showreport?sys=statistics/campaigns");
$report_url_trad = base_url("JsperReport/showreport?sys=online_rep/bills");
$date_attr= " data-type='date' data-date-format='DD/MM/YYYY' data-val='true' data-val-regex-pattern='".date_format_exp()."' data-val-regex='Error' ";

?>
<div class="row">
    <div class="toolbar">
        <div class="caption"><?= $title ?></div>
        <ul></ul>
    </div>

    <div class="form-body">

        <div id="msg_container"></div>

        <div id="container">

            <ul id="tabs" class="nav nav-tabs tabs">
                <li id="1" class="active"><a href="#tab1" data-toggle="tab">حملات رمضان 2022</a></li>
                <li id="4"><a href="#tab4" data-toggle="tab">حملة نقاطي</a></li>
                <li id="2"><a href="#tab2" data-toggle="tab">حملة سدد شهري واكسب فوري </a></li>
                <li id="3"><a href="#tab3" data-toggle="tab">رسم بياني</a></li>
                <li id="5"><a href="#tab5" data-toggle="tab">كشف حساب</a></li>
            </ul>

            <div id="myTabContent" class="tab-content">

                <div class="tab-pane fade in active" id="tab1">
                    <div id="container_tab1">

                    </div>
                </div>

                <div class="tab-pane fade" id="tab2">
                    <div id="container_tab2">

                    </div>
                </div>

                <div class="tab-pane fade" id="tab3">
                    <div class="form-group col-sm-12">

                        <div class="form-group col-sm-2">
                            <label class="control-label"> من تاريخ</label>
                            <div>
                                <input type="text" data-val-required="حقل مطلوب" data-type="date" data-date-format="DD/MM/YYYY" value="<?=date('d/m/Y')?>" name="txt_from_date_3" id="txt_from_date_3" class="form-control valid">
                            </div>
                        </div>

                        <div class="form-group col-sm-2">
                            <label class="control-label"> الى تاريخ</label>
                            <div>
                                <input type="text" data-val-required="حقل مطلوب" data-type="date" data-date-format="DD/MM/YYYY" value="<?=date('d/m/Y')?>" name="txt_to_date_3" id="txt_to_date_3" class="form-control valid">
                            </div>
                        </div>

                        <div class="form-group col-sm-2">
                            <label class="control-label">نوع الحملة</label>
                            <div>
                                <select name="campaign_type" id="dp_campaign_type" class="form-control " required>
                                    <option value="">_________</option>
                                    <?php foreach($campaign_type as $row) :?>
                                        <option value="<?=$row['CON_NO']?>"><?=$row['CON_NAME']?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group col-sm-2">
                            <label class="control-label" style="color: white">_</label>
                            <div>
                                <button type="button" onclick="javascript:get_data_campaigns();"  class="btn btn-success">عرض</button>
                            </div>
                        </div>

                    </div>

                    <div id="container_tab3">

                    </div>
                </div>

                <div class="tab-pane fade" id="tab4">
                    <div id="container_tab4">

                    </div>
                </div>

                <div class="tab-pane fade" id="tab5">
                    <div id="container_tab5">

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<?php
$scripts = <<<SCRIPT
<script>

	var tabs_loaded= {1:0, 2:0, 3:0 ,4:0 ,5:0};
     function clear_form(){
        clearForm_any('.row');
         $('.sel2').select2('val','');
    }
    
    $("ul.tabs").on('click', 'li' ,function(event) {
        var tabId = this.id;
		if(tabs_loaded[tabId]==0){
			getData(tabId);
		}
    });
    
    getData(1);
    
    function getData(type){
        var value = {date_1 : $('#txt_from_date_3').val() , date_2 : $('#txt_to_date_3').val() , campaign_type : $('#dp_campaign_type').val()};
		if(type == 1){
            get_dataWithOutLoading('{$first_stage_url}','' ,function(data){
                $('#container_tab1').html(data);
                	 
            },'html');
        }else if(type == 2) {
            get_dataWithOutLoading('{$second_stage_url}','' ,function(data){
                $('#container_tab2').html(data);
                $('.sel2').select2();
            },'html');
            
        }else if(type == 3) {
		    get_data('{$third_stage_url}',value ,function(data){
                $('#container_tab3').html(data);
                $("#chart_campaigns").empty();
                Campaigns_chart(value);
              },'html');
		    
        }else if(type == 4) {
		    get_dataWithOutLoading('{$fourth_stage_url}','' ,function(data){
                $('#container_tab4').html(data);
                $('.sel2').select2();
              },'html');
              
        }else if(type == 5) {
		    get_dataWithOutLoading('{$fifth_stage_url}','' ,function(data){
                $('#container_tab5').html(data);
                $('.sel2').select2();
              },'html');
        }
		tabs_loaded[type]= 1;
    }
    
    function get_data_campaigns() {
        var date1 =$('#txt_from_date_3').val();
        var date2 =$('#txt_to_date_3').val();
       
        if (date1 == '' || date2 == '' ) {
            danger_msg('رسالة','يجب كتابة التاريخ من و الى ..');
            return;
        }
            getData(3);
    }
    
    function ret_radio(rd) {
        if(rd){
            return 1;
        }else {
            return '';
        }
    }

    function print_report_totals_1() {
        var rep_name_pdf = 'sadad_pay_monthly_totals_pdf';

        var from_date = $('#txt_from_date').val();
        var to_date = $('#txt_to_date').val();
        var branch_id = $('#dp_branch_id').val();
        var repayment_id = $('#dp_repayment').val();
        var subscribe_no = $('#txt_subscribe_no_1').val();
        var bill = $('#txt_bill').val();
        var rep_type = $('input[name=rep_type_id]:checked').val();
                
        var rep_url = '{$report_url}&report_type='+rep_type+'&report='+rep_name_pdf+'&p_date_from='+from_date+'&p_date_to='+to_date+'&p_branch='+branch_id
        +'&p_sub_no='+subscribe_no+'&p_pay_type='+repayment_id+'&p_bill_month='+bill;
        _showReport(rep_url); 
    }    
    
    function print_report_details_1() {
        var rep_name_xls = 'sadad_pay_monthly_details_xls';
        var rep_name_pdf = 'sadad_pay_monthly_details_pdf';

        var from_date = $('#txt_from_date').val();
        var to_date = $('#txt_to_date').val();
        var branch_id = $('#dp_branch_id').val();
        var repayment_id = $('#dp_repayment').val();
        var subscribe_no = $('#txt_subscribe_no_1').val();
        var bill = $('#txt_bill').val();
        var rep_type = $('input[name=rep_type_id]:checked').val();

        if(rep_type == 'xls'){
            var rep_url = '{$report_url}&report_type='+rep_type+'&report='+rep_name_xls+'&p_date_from='+from_date+'&p_date_to='+to_date+'&p_branch='+branch_id
            +'&p_sub_no='+subscribe_no+'&p_pay_type='+repayment_id+'&p_bill_month='+bill;
        }else{
            var rep_url = '{$report_url}&report_type='+rep_type+'&report='+rep_name_pdf+'&p_date_from='+from_date+'&p_date_to='+to_date+'&p_branch='+branch_id
            +'&p_sub_no='+subscribe_no+'&p_pay_type='+repayment_id+'&p_bill_month='+bill;
        }
        _showReport(rep_url); 
    }

    function print_report_2() {

        var rep_name_pdf = 'campaigns_details_pdf';
        var rep_name_xls = 'campaigns_details_xls';
        
        var from_date = $('#txt_from_date_2').val();
        var to_date = $('#txt_to_date_2').val();
        var branch_id = $('#dp_branch_id_2').val();
        var campaign_type = $('#dp_campaign_type').val();
        var repayment_type = $('#dp_repayment_type').val();
        var identification_no = $('#txt_identification_no').val();
        var subscribe_no = $('#txt_subscribe_no').val();
        var subscribe_no_disc = $('#txt_subscribe_no_disc').val();
        var month = $('#txt_month').val();
        var discount = $('#txt_discount').val();
        var finish_status = $('#dp_finish_status').val();
        var posting_status = $('#dp_posting_status').val();
        var rep_type = $('input[name=rep_type_id_2]:checked').val();
        var payment_method = $('#dp_payment_method').val();
        
        var op_count_1 ,op_count_2;
        if (payment_method == 1){
            op_count_1 = 1; op_count_2 = '';
        }else if (payment_method == 2){
            op_count_1 = ''; op_count_2 = 1;  
        }else {
            op_count_1 = ''; op_count_2 = '';  
        }

        if(rep_type == 'xls'){
            var rep_url = '{$report_url}&report_type='+rep_type+'&report='+rep_name_xls+'&p_date_from='+from_date+'&p_date_to='+to_date+'&p_branch='+branch_id
            +'&p_emp_type='+campaign_type+'&p_pay_type='+repayment_type+'&p_order_id_no='+identification_no
            +'&p_parent_subscriber='+subscribe_no+'&p_grant_subscriber='+subscribe_no_disc+'&p_month='+month+'&p_pay_status='+discount+'&p_finish_status='+finish_status+'&p_posting_status='+posting_status+'&p_count_months_1='+op_count_1+'&p_count_months_2='+op_count_2;
        }else {
            var rep_url = '{$report_url}&report='+rep_name_pdf+'&p_date_from='+from_date+'&p_date_to='+to_date+'&p_branch='+branch_id
            +'&p_emp_type='+campaign_type+'&p_pay_type='+repayment_type+'&p_order_id_no='+identification_no
            +'&p_parent_subscriber='+subscribe_no+'&p_grant_subscriber='+subscribe_no_disc+'&p_month='+month+'&p_pay_status='+discount+'&p_finish_status='+finish_status+'&p_posting_status='+posting_status+'&p_count_months_1='+op_count_1+'&p_count_months_2='+op_count_2;
        }
        
        if (branch_id == '' && campaign_type == '' ) {
            danger_msg('رسالة','يجب اختيار المقر او نوع الحملة ..');
            return;
        }

        if (discount < 0 || discount > 100 ) {
            warning_msg('رسالة','يجب ان تكون نسبة الخصم من 0 الى 100 ..');
            return;
        }
        _showReport(rep_url);
    }
   
    function print_report_3() {

        var rep_name = 'campaigns_totals_pdf';
        
        var from_date = $('#txt_from_date_2').val();
        var to_date = $('#txt_to_date_2').val();
        var branch_id = $('#dp_branch_id_2').val();
        var campaign_type = $('#dp_campaign_type').val();
        var repayment_type = $('#dp_repayment_type').val();
        var identification_no = $('#txt_identification_no').val();
        var subscribe_no = $('#txt_subscribe_no').val();
        var subscribe_no_disc = $('#txt_subscribe_no_disc').val();
        var month = $('#txt_month').val();
        var discount = $('#txt_discount').val();
        var finish_status = $('#dp_finish_status').val();
        var posting_status = $('#dp_posting_status').val();
        var rep_type = $('input[name=rep_type_id_2]:checked').val();
        var conditions_type = $('#dp_conditions_type').val();
        var payment_method = $('#dp_payment_method').val();
        
        var op_count_1 ,op_count_2;
        if (payment_method == 1){
            op_count_1 = 1; op_count_2 = '';
        }else if (payment_method == 2){
            op_count_1 = ''; op_count_2 = 1;  
        }else {
            op_count_1 = ''; op_count_2 = '';  
        }
        
        var op_1 ,op_2 ,op_3 ,op_4 ,op_5;
        if (conditions_type == 1){
            op_1 = 1; op_2 = ''; op_3 = ''; op_4 = ''; op_5 = '';
        }else if (conditions_type == 2){
            op_1 = ''; op_2 = 1 ; op_3 = ''; op_4 = ''; op_5 = '';
        }else if (conditions_type == 3) {
            op_1 = ''; op_2 = ''; op_3 = 1; op_4 = ''; op_5 = '';
        }else if (conditions_type == 4) {
            op_1 = ''; op_2 = ''; op_3 = ''; op_4 = 1; op_5 = '';
        }else if (conditions_type == 5) {
            op_1 = ''; op_2 = ''; op_3 = ''; op_4 = ''; op_5 = 1;
        }else {
            op_1 = ''; op_2 = ''; op_3 = ''; op_4 = ''; op_5 = '';
        }
        
        var rep_url = '{$report_url}&report_type='+rep_type+'&report='+rep_name+'&p_date_from='+from_date+'&p_date_to='+to_date+'&p_branch='+branch_id
        +'&p_emp_type='+campaign_type+'&p_pay_type='+repayment_type+'&p_order_id_no='+identification_no
        +'&p_parent_subscriber='+subscribe_no+'&p_grant_subscriber='+subscribe_no_disc+'&p_month='+month+'&p_pay_status='+discount+'&p_finish_status='+finish_status+'&p_posting_status='+posting_status
        +'&p_op_1='+op_1+'&p_op_2='+op_2+'&p_op_3='+op_3+'&p_op_4='+op_4+'&p_op_5='+op_5+'&p_count_months_1='+op_count_1+'&p_count_months_2='+op_count_2;

        _showReport(rep_url);
    }
    
    function getReportUrl() {

        var id=$('#dp_rep_id').val();
        var branch_id = $('#dp_branch_id_3').val();
        var sub_no = $('#txt_sub_no').val();
        var sadad_type = $('#dp_sadad_type').val();
        var sadad_type1 = $('#dp_sadad_type1').val();
        var has_rem = $('#dp_has_rem').val();
        var points_sign = $('#dp_points_sign').val();
        var points = $('#txt_points').val();
        var cost_sign = $('#dp_cost_sign').val();
        var cost = $('#txt_cost').val();
        var type = $('#dp_type').val();
        var point_type = $('#dp_point_type').val();
        var billc_type = $('#dp_billc_type').val();
        
        var subsciber_status = $('#dp_subsciber_status').val();
        var sub_exception = $('#dp_status').val();
        var rep_type = $('input[name=rep_type_id]:checked').val();
        var repUrl;

        if(subsciber_status == 255){
            var is_not_active = 255;
            var is_active = '';
        }else if(subsciber_status == 1){  
            var is_active = 1;
            var is_not_active = '';
        }else{
            var is_active = '';
            var is_not_active = '';
        }

        if (id == 0) {
            warning_msg('رسالة','يجب اختيار اسم التقرير  ..');
            return;
        }
        
        if (branch_id == '') {
            danger_msg('رسالة','يجب اختيار المقر  ..');
            return;
        }

        switch(id) {
            case "1":
                repUrl = '{$report_url}&report_type='+rep_type+'&report=points_campaign_details_'+rep_type+'&p_branch='+branch_id+'&p_sub_no='+sub_no+'&p_sadad_type='+sadad_type+'&p_sadad_type1='+sadad_type1+'&p_has_rem='+has_rem
                    +'&p_points_sign='+points_sign+'&p_points='+points+'&p_cost_sign='+cost_sign+'&p_cost='+cost+'&p_type='+type+'&p_bill_type='+billc_type
                    +'&p_is_active='+is_active+'&p_is_not_active='+is_not_active;
                break;
                
            case "2":
                repUrl = '{$report_url}&report_type='+rep_type+'&report=points_campaign_totals_'+rep_type+'&p_branch='+branch_id+'&p_sub_no='+sub_no+'&p_sadad_type='+sadad_type+'&p_sadad_type1='+sadad_type1+'&p_has_rem='+has_rem
                    +'&p_points_sign='+points_sign+'&p_points='+points+'&p_cost_sign='+cost_sign+'&p_cost='+cost+'&p_type='+type+'&p_bill_type='+billc_type
                    +'&p_is_active='+is_active+'&p_is_not_active='+is_not_active;
                break;
                                    
            case "3":  
                if (sub_no == '' || sub_no <= 0 ) {
                    danger_msg('رسالة','يجب ادخال رقم الاشتراك  ..');
                    return;
                }
                repUrl = '{$report_url}&report_type='+rep_type+'&report=sub_points_campaign_details_'+rep_type+'&p_branch='+branch_id+'&p_sub_no='+sub_no+'&p_point_type='+point_type+'&p_billc_type='+billc_type
                    +'&p_is_active='+is_active+'&p_is_not_active='+is_not_active+'&p_status='+status
                    +'&p_points_sign='+points_sign+'&p_points='+points;
                break;
        }
        return repUrl;
    }

    function print_report_4(){
        var rep_url = getReportUrl();
        _showReport(rep_url);
    }

    function print_report_5() {
    
        var id=$('#dp_rep_id').val();
        var sub_no = $('#txt_sub_no').val();
        
        if (id == 0) {
            warning_msg('رسالة','يجب اختيار اسم التقرير  ..');
            return;
        }
        
        if (sub_no == '' || sub_no <= 0 ) {
            danger_msg('رسالة','يجب ادخال رقم الاشتراك  ..');
            return;
        }
        
        var rep_type = $('input[name=rep_type_id]:checked').val();
        
        var repUrl = '{$report_url}&report_type='+rep_type+'&report=points_payments_on_bill_'+rep_type+'&p_sub_no='+sub_no;
        _showReport(repUrl); 
    }
    
    function print_report_6() {
        var rep_name = 'record_account';
        var from_date = $('#txt_from_date_4').val();
        var to_date = $('#txt_to_date_4').val();
        var sub_no = $('#txt_sub_no_1').val();
        
        if (sub_no == '' || sub_no <= 0 ) {
            danger_msg('رسالة','يجب ادخال رقم الاشتراك  ..');
            return;
        }
        var rep_type = $('input[name=rep_type_id]:checked').val();
        
        var rep_url = '{$report_url_trad}&report_type='+rep_type+'&report='+rep_name+'&p_date_from='+from_date+'&p_date_to='+to_date+'&p_sub_no='+sub_no;
        _showReport(rep_url); 
    }
    
    function Campaigns_chart(values){
        $.ajax({
            url:"{$third_stage_chart_url}",
            method:"POST",
            data:values,
            dataType:"JSON",
            success:function(data)
            {
                $("#chart_campaigns").empty();
                $("#chart_campaigns").remove();
                $("div.signals").append('<div id="chart_campaigns" style="width: 900px; height: 500px; margin: 0 auto"  height="150"></canvas>');
                drawCampaigns_chart(data);
            }
        })
    }

    function drawCampaigns_chart(chart_data){
        var Campaigns_chart =  Morris.Bar({
            barGap:4,
            barSizeRatio:0.55,
            element: 'chart_campaigns',
            data: JSON.parse(JSON.stringify(chart_data)),
            barColors: ["#4e73df", '#02c58d',"#fc5454", "#a0d0e0"],
            xkey: 'BRANCH_NAME',
            ykeys: ['COUNT_SUBSCRIBER','SUM_PAID_VALUE'],
            labels: ['عدد المشتركين','إجمالي المدفوع'],
            hideHover: 'auto',
            resize: true
        });
        Campaigns_chart.redraw();
        $(window).trigger('resize');
    }

</script>
SCRIPT;
sec_scripts($scripts);
?>
