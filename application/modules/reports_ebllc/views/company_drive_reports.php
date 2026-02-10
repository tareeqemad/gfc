<?php
/**
 * Created by PhpStorm.
 * User: fmamluk
 * Date: 28/03/22
 * Time: 09:10 ص
 */

$MODULE_NAME= 'reports_ebllc';
$TB_NAME= 'Company_drive';
$first_stage_url = base_url("$MODULE_NAME/$TB_NAME/public_first_stage");
$first_stage_chart_url = base_url("$MODULE_NAME/$TB_NAME/public_first_stage_chart");
$second_stage_url = base_url("$MODULE_NAME/$TB_NAME/public_second_stage");
$second_stage_chart_url = base_url("$MODULE_NAME/$TB_NAME/public_second_stage_chart");
$third_stage_url = base_url("$MODULE_NAME/$TB_NAME/public_third_stage");
$third_stage_chart_url = base_url("$MODULE_NAME/$TB_NAME/public_third_stage_chart");
$fourth_stage_url = base_url("$MODULE_NAME/$TB_NAME/public_fourth_stage");
$fourth_stage_chart_url = base_url("$MODULE_NAME/$TB_NAME/public_fourth_stage_chart");
$fifth_stage_url = base_url("$MODULE_NAME/$TB_NAME/public_fifth_stage");
$fifth_stage_chart_url = base_url("$MODULE_NAME/$TB_NAME/public_fifth_stage_chart");
$sixth_stage_url = base_url("$MODULE_NAME/$TB_NAME/public_sixth_stage");
$report_url = base_url("JsperReport/showreport")."?sys=Trading/dexcen";
$report_url_2 = base_url("JsperReport/showreport")."?sys=statistics/campaigns/campaign_2023";

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
                <li id="1"  class="active" ><a href="#tab1" data-toggle="tab">الخصومات الشهرية</a></li>
                <li id="2"><a href="#tab2" data-toggle="tab">تجزئة الاشتراكات العائلية</a></li>
                <li id="3"><a href="#tab3" data-toggle="tab">التحول للقسط الثابت</a></li>
                <li id="4"><a href="#tab4" data-toggle="tab">أهلنا ومانسيناكم</a></li>
                <li id="5"><a href="#tab5" data-toggle="tab">الإنتساب عبر التطبيق</a></li>
<!--                <li id="6"><a href="#tab6" data-toggle="tab">الإجماليات</a></li>-->
            </ul>

            <div id="myTabContent" class="tab-content">

                <div class="tab-pane fade in active" id="tab1">
                    <fieldset>
                        <legend>معايير البحث</legend>
                        <div class="form-group col-sm-12">

                            <div class="form-group col-sm-1">
                                <label class="control-label"> من تاريخ</label>
                                <div>
                                    <input type="text" data-val-required="حقل مطلوب" data-type="date" data-date-format="DD/MM/YYYY" value="06/04/2023" name="txt_from_date_1" id="txt_from_date_1" class="form-control valid">
                                </div>
                            </div>

                            <div class="form-group col-sm-1">
                                <label class="control-label"> الى تاريخ</label>
                                <div>
                                    <input type="text" data-val-required="حقل مطلوب" data-type="date" data-date-format="DD/MM/YYYY" value="<?=date('d/m/Y')?>" name="txt_to_date_1" id="txt_to_date_1" class="form-control valid">
                                </div>
                            </div>

                                <div class="form-group col-md-2">
                                    <label class="control-label"> المقر</label>
                                    <select name="branch_id_1" id="dp_branch_id_1" class="form-control sel2">
                                        <option value="">_______</option>
                                        <?php foreach ($branches as $row) : ?>
                                            <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>


                            <div class="form-group col-sm-8">
                                <label class="control-label" style="color: white">_</label>
                                <div>
                                    <button type="button" onclick="javascript:get_data_campaigns_first();"  class="btn btn-danger">عرض</button>
                                    <button type="button" onclick="javascript:get_report_first_details_pdf();"  class="btn btn-primary">التقرير التفصيلي pdf</button>
                                    <button type="button" onclick="javascript:get_report_first_details_xls();"  class="btn btn-success">التقرير التفصيلي xls</button>
                                    <button type="button" onclick="javascript:get_report_first_total_dis();"  class="btn btn-info">التقرير الإجمالي حسب الحملة</button>
                                    <button type="button" onclick="javascript:get_report_first_total_dis_2();"  class="btn btn-warning">التقرير الإجمالي حسب الحملة - مختصر</button>
                                    <button type="button" onclick="javascript:get_report_first_total_bran();"  class="btn btn-danger">التقرير الإجمالي حسب المقر</button>
                                </div>
                            </div>

                        </div>
                    </fieldset>
                    <div id="container_tab1">

                    </div>
                </div>

                <div class="tab-pane" id="tab2">
                    <fieldset>
                        <legend>معايير البحث</legend>

                        <div class="form-group col-sm-12">

                            <div class="form-group col-sm-1">
                                <label class="control-label"> من تاريخ</label>
                                <div>
                                    <input type="text" data-val-required="حقل مطلوب" data-type="date" data-date-format="DD/MM/YYYY" value="06/04/2023" name="txt_from_date_2" id="txt_from_date_2" class="form-control valid">
                                </div>
                            </div>

                            <div class="form-group col-sm-1">
                                <label class="control-label"> الى تاريخ</label>
                                <div>
                                    <input type="text" data-val-required="حقل مطلوب" data-type="date" data-date-format="DD/MM/YYYY" value="<?=date('d/m/Y')?>" name="txt_to_date_2" id="txt_to_date_2" class="form-control valid">
                                </div>
                            </div>

                            <div class="form-group col-md-2">
                                <label class="control-label"> المقر</label>
                                <select name="branch_id_2" id="dp_branch_id_2" class="form-control sel2">
                                    <option value="">_______</option>
                                    <?php foreach ($branches as $row) : ?>
                                        <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group col-sm-8">
                                <label class="control-label" style="color: white">_</label>
                                <div>
                                    <button type="button" onclick="javascript:get_data_campaigns_second();"  class="btn btn-danger">عرض</button>
                                    <button type="button" onclick="javascript:get_report_second_details_pdf();"  class="btn btn-primary">التقرير التفصيلي pdf</button>
                                    <button type="button" onclick="javascript:get_report_second_details_xls();"  class="btn btn-success">التقرير التفصيلي xls</button>
                                </div>
                            </div>

                        </div>
                    </fieldset>
                    <div id="container_tab2">

                    </div>
                </div>

                <div class="tab-pane" id="tab3">
                    <fieldset>
                        <legend>معايير البحث</legend>

                        <div class="form-group col-sm-12">

                            <div class="form-group col-sm-1">
                                <label class="control-label"> من تاريخ</label>
                                <div>
                                    <input type="text" data-val-required="حقل مطلوب" data-type="date" data-date-format="DD/MM/YYYY" value="06/04/2023" name="txt_from_date_3" id="txt_from_date_3" class="form-control valid">
                                </div>
                            </div>

                            <div class="form-group col-sm-1">
                                <label class="control-label"> الى تاريخ</label>
                                <div>
                                    <input type="text" data-val-required="حقل مطلوب" data-type="date" data-date-format="DD/MM/YYYY" value="<?=date('d/m/Y')?>" name="txt_to_date_3" id="txt_to_date_3" class="form-control valid">
                                </div>
                            </div>

                            <div class="form-group col-md-2">
                                <label class="control-label"> المقر</label>
                                <select name="branch_id_3" id="dp_branch_id_3" class="form-control sel2">
                                    <option value="">_______</option>
                                    <?php foreach ($branches as $row) : ?>
                                        <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group col-sm-8">
                                <label class="control-label" style="color: white">_</label>
                                <div>
                                    <button type="button" onclick="javascript:get_data_campaigns_third();"  class="btn btn-danger">عرض</button>
                                    <button type="button" onclick="javascript:get_report_third_details_pdf();"  class="btn btn-primary">التقرير التفصيلي pdf</button>
                                    <button type="button" onclick="javascript:get_report_third_details_xls();"  class="btn btn-success">التقرير التفصيلي xls</button>
                                </div>
                            </div>

                        </div>
                    </fieldset>
                    <div id="container_tab3">

                    </div>
                </div>

                <div class="tab-pane" id="tab4">
                    <fieldset>
                        <legend>معايير البحث</legend>

                        <div class="form-group col-sm-12">

                            <div class="form-group col-sm-1">
                                <label class="control-label"> من تاريخ</label>
                                <div>
                                    <input type="text" data-val-required="حقل مطلوب" data-type="date" data-date-format="DD/MM/YYYY" value="06/04/2023" name="txt_from_date_4" id="txt_from_date_4" class="form-control valid">
                                </div>
                            </div>

                            <div class="form-group col-sm-1">
                                <label class="control-label"> الى تاريخ</label>
                                <div>
                                    <input type="text" data-val-required="حقل مطلوب" data-type="date" data-date-format="DD/MM/YYYY" value="<?=date('d/m/Y')?>" name="txt_to_date_4" id="txt_to_date_4" class="form-control valid">
                                </div>
                            </div>

                            <div class="form-group col-md-2">
                                <label class="control-label"> المقر</label>
                                <select name="branch_id_4" id="dp_branch_id_4" class="form-control sel2">
                                    <option value="">_______</option>
                                    <?php foreach ($branches as $row) : ?>
                                        <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group col-sm-8">
                                <label class="control-label" style="color: white">_</label>
                                <div>
                                    <button type="button" onclick="javascript:get_data_campaigns_fourth();"  class="btn btn-danger">عرض</button>
                                    <button type="button" onclick="javascript:get_report_fourth_details_pdf();"  class="btn btn-primary">التقرير التفصيلي pdf</button>
                                    <button type="button" onclick="javascript:get_report_fourth_details_xls();"  class="btn btn-success">التقرير التفصيلي xls</button>
                                </div>
                            </div>

                        </div>
                    </fieldset>
                    <div id="container_tab4">

                    </div>
                </div>

                <div class="tab-pane" id="tab5">
                    <fieldset>
                        <legend>معايير البحث</legend>

                        <div class="form-group col-sm-12">

                            <div class="form-group col-sm-1">
                                <label class="control-label"> من تاريخ</label>
                                <div>
                                    <input type="text" data-val-required="حقل مطلوب" data-type="date" data-date-format="DD/MM/YYYY" value="06/04/2023" name="txt_from_date_5" id="txt_from_date_5" class="form-control valid">
                                </div>
                            </div>

                            <div class="form-group col-sm-1">
                                <label class="control-label"> الى تاريخ</label>
                                <div>
                                    <input type="text" data-val-required="حقل مطلوب" data-type="date" data-date-format="DD/MM/YYYY" value="<?=date('d/m/Y')?>" name="txt_to_date_5" id="txt_to_date_5" class="form-control valid">
                                </div>
                            </div>

                            <div class="form-group col-md-2">
                                <label class="control-label"> المقر</label>
                                <select name="branch_id_5" id="dp_branch_id_5" class="form-control sel2">
                                    <option value="">_______</option>
                                    <?php foreach ($branches as $row) : ?>
                                        <option value="<?= $row['CON_NO'] ?>"><?= $row['CON_NAME'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group col-sm-8">
                                <label class="control-label" style="color: white">_</label>
                                <div>
                                    <button type="button" onclick="javascript:get_data_campaigns_fifth();"  class="btn btn-danger">عرض</button>
                                    <button type="button" onclick="javascript:get_report_fifth_details_pdf();"  class="btn btn-primary">التقرير التفصيلي pdf</button>
                                    <button type="button" onclick="javascript:get_report_fifth_details_xls();"  class="btn btn-success">التقرير التفصيلي xls</button>
                                </div>
                            </div>

                        </div>
                    </fieldset>
                    <div id="container_tab5">

                    </div>
                </div>

                <div class="tab-pane" id="tab6">
                    <label class="control-label" style="color: white">_</label>
                    <div class="col-md-2"></div>
                    <div class="col-md-2"></div>
                    <div class="col-md-4">
                        <button type="button" onclick="javascript:get_data_campaigns_sixth();"  class="btn btn-success btn-block"> عرض التقرير الاجمالي</button>
                    </div>
                    <div class="col-md-4"></div>
                 </div>

                </div>

            </div>
        </div>
    </div>
</div>
<?php
$scripts = <<<SCRIPT
<script>
    $('.sel2').select2();
    $('#div_discount').hide();
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

    $('#dp_campaign_type_2').on('change',function(){
        var id = $(this).val();
        if(id == 1){
            $('#div_discount').show();
        }else{
            $('#div_discount').hide();
            $('#dp_discount_type_2').select2('val','');
        }
    });

    getData(1);
    
    function getData(type){
        var value_1 = {date_1 : $('#txt_from_date_1').val() , date_2 : $('#txt_to_date_1').val() };
        var value_2 = {date_1 : $('#txt_from_date_2').val() , date_2 : $('#txt_to_date_2').val() };
        var value_3 = {date_1 : $('#txt_from_date_3').val() , date_2 : $('#txt_to_date_3').val() };
        var value_4 = {date_1 : $('#txt_from_date_4').val() , date_2 : $('#txt_to_date_4').val() };
        var value_5 = {date_1 : $('#txt_from_date_5').val() , date_2 : $('#txt_to_date_5').val() };
        
		if(type == 1){
            get_data('{$first_stage_url}',value_1 ,function(data){
                if ($('#dp_discount_type').val() == '' ) {
                    return;
                }
                $('#container_tab1').html(data);
                $("#chart_campaigns_first").empty();
                Campaigns_chart_first(value_1);
             },'html');
        }else if(type == 2) {
        get_data('{$second_stage_url}',value_2 ,function(data){
                $('#container_tab2').html(data);
                $("#chart_campaigns_second").empty();
                Campaigns_chart_second(value_2);
             },'html');
        }else if(type == 3) {
        get_data('{$third_stage_url}',value_3 ,function(data){
                $('#container_tab3').html(data);
                $("#chart_campaigns_third").empty();
                Campaigns_chart_third(value_3);
             },'html');
        }else if(type == 4) {
        get_data('{$fourth_stage_url}',value_4 ,function(data){
                $('#container_tab4').html(data);
                $("#chart_campaigns_fourth").empty();
                Campaigns_chart_fourth(value_4);
             },'html');
        }else if(type == 5) {
        get_data('{$fifth_stage_url}',value_5 ,function(data){
            /*if ($('#dp_campaign_type_2').val() == '' ) {
                return;
            }*/
            $('#container_tab5').html(data);
            $("#chart_campaigns_fifth5").empty();
            Campaigns_chart_fifth(value_5);
             },'html');
        }
        
		tabs_loaded[type]= 1;
    }
    
    function get_data_campaigns_first() {
        var date1 =$('#txt_from_date_1').val();
        var date2 =$('#txt_to_date_1').val();
        getData(1);
    }
    
    function get_data_campaigns_second() {
        var date1 =$('#txt_from_date_2').val();
        var date2 =$('#txt_to_date_2').val();
       
        if (date1 == '' || date2 == '' ) {
            danger_msg('رسالة','يجب ادخال التاريخ من و الى ..');
            return;
        }
            getData(2);
    }
    
    function get_data_campaigns_third() {
        var date1 =$('#txt_from_date_3').val();
        var date2 =$('#txt_to_date_3').val();
       
        if (date1 == '' || date2 == '' ) {
            danger_msg('رسالة','يجب ادخال التاريخ من و الى ..');
            return;
        }
            getData(3);
    }  
    
    function get_data_campaigns_fourth() {
        var date1 =$('#txt_from_date_4').val();
        var date2 =$('#txt_to_date_4').val();
       
        if (date1 == '' || date2 == '' ) {
            danger_msg('رسالة','يجب ادخال التاريخ من و الى ..');
            return;
        }
            getData(4);
    }  
    
    function get_data_campaigns_fifth() {
        var date1 =$('#txt_from_date_5').val();
        var date2 =$('#txt_to_date_5').val();
        var campaign_type_2 =$('#dp_campaign_type_2').val();
        var discount_type_2 =$('#dp_discount_type_2').val();
        if (date1 == '' || date2 == '' ) {
            danger_msg('رسالة','يجب ادخال التاريخ من و الى ..');
            return;
        }
        /*if (campaign_type_2 == '') {
            danger_msg('رسالة','يجب اختيار نوع الحملة ..');
            return;
        }
        
        if ( campaign_type_2 == 1 && discount_type_2 == '') {
            danger_msg('رسالة','يجب اختيار نوع الخصم ..');
            return;
        }*/
            getData(5);
    }  

    function get_data_campaigns_sixth() {
        _showReport('{$report_url}&report_type=pdf&report=target_sub_smart_pdf&p_branch='+4+'&p_sub='+47100636+'&p_from_min_smart_mon='+202301+'&p_to_min_smart_mon='+202301);

    }  
    
    function Campaigns_chart_first(values){
        $.ajax({
            url:"{$first_stage_chart_url}",
            method:"POST",
            data:values,
            dataType:"JSON",
            success:function(data)
            {
                $("#chart_campaigns_first").empty();
                $("#chart_campaigns_first").remove();
                $("div.signals_first").append('<div id="chart_campaigns_first" style="width: 900px; height: 500px; margin: 0 auto"  height="150"></canvas>');
                drawCampaigns_chart_first(data);
            }
        })
    }
    
    function drawCampaigns_chart_first(chart_data){
        var Campaigns_chart_1 =  Morris.Bar({
            barGap:4,
            barSizeRatio:0.55,
            element: 'chart_campaigns_first',
            data: JSON.parse(JSON.stringify(chart_data)),
            barColors: ["#4e73df", '#02c58d',"#fc5454", "#a0d0e0"],
            xkey: 'BRANCH_NAME',
            ykeys: ['CNT_SUB','INSTANT_PAY','ACT_PAID','QEST_VAL','SUM_ALL_QEST1','SUM_ALL_QEST'],
            labels: ['عدد المشتركين','المبلغ الفوري','المبلغ المحصل النقدي','قيمة القسط','المتبقي من المجدول','المجدول '],
            hideHover: 'auto',
            resize: true
        });
        Campaigns_chart_1.redraw();
        $(window).trigger('resize');
    }
   
    function Campaigns_chart_second(values){
        $.ajax({
            url:"{$second_stage_chart_url}",
            method:"POST",
            data:values,
            dataType:"JSON",
            success:function(data)
            {
                $("#chart_campaigns_second").empty();
                $("#chart_campaigns_second").remove();
                $("div.signals_second").append('<div id="chart_campaigns_second" style="width: 900px; height: 500px; margin: 0 auto"  height="150"></canvas>');
                drawCampaigns_chart_second(data);
            }
        })
    }

    function drawCampaigns_chart_second(chart_data){
        var Campaigns_chart_2 =  Morris.Bar({
            barGap:4,
            barSizeRatio:0.55,
            element: 'chart_campaigns_second',
            data: JSON.parse(JSON.stringify(chart_data)),
            barColors: ["#4e73df", '#02c58d',"#fc5454", "#a0d0e0"],
            xkey: 'BRANCH_NAME',
            ykeys: ['REQUEST_NO','EXC'],
            labels: ['عدد الطلبات','المنفذ'],
            hideHover: 'auto',
            resize: true
        });
        Campaigns_chart_2.redraw();
        $(window).trigger('resize');
    }

    function Campaigns_chart_third(values){
        $.ajax({
            url:"{$third_stage_chart_url}",
            method:"POST",
            data:values,
            dataType:"JSON",
            success:function(data)
            {
                $("#chart_campaigns_third").empty();
                $("#chart_campaigns_third").remove();
                $("div.signals_third").append('<div id="chart_campaigns_third" style="width: 900px; height: 500px; margin: 0 auto"  height="150"></canvas>');
                drawCampaigns_chart_third(data);
            }
        })
    }

    function drawCampaigns_chart_third(chart_data){
        var Campaigns_chart_3 =  Morris.Bar({
            barGap:4,
            barSizeRatio:0.55,
            element: 'chart_campaigns_third',
            data: JSON.parse(JSON.stringify(chart_data)),
            barColors: ["#4e73df", '#02c58d',"#fc5454", "#a0d0e0"],
            xkey: 'BRANCH_NAME',
            ykeys: ['COUNT_SUB','INSTALL_VALUE','DISCOUNT_VALUE','SUM_PAID'],
            labels: ['عدد المشتركين','مجموع الأقساط','مجموع الإعفاء','المدفوع'],
            hideHover: 'auto',
            resize: true
        });
        Campaigns_chart_3.redraw();
        $(window).trigger('resize');
    }

    function Campaigns_chart_fourth(values){
        $.ajax({
            url:"{$fourth_stage_chart_url}",
            method:"POST",
            data:values,
            dataType:"JSON",
            success:function(data)
            {
                $("#chart_campaigns_fourth").empty();
                $("#chart_campaigns_fourth").remove();
                $("div.signals_fourth").append('<div id="chart_campaigns_fourth" style="width: 900px; height: 500px; margin: 0 auto"  height="150"></canvas>');
                drawCampaigns_chart_fourth(data);
            }
        })
    }

    function drawCampaigns_chart_fourth(chart_data){
        var Campaigns_chart_4 =  Morris.Bar({
            barGap:4,
            barSizeRatio:0.55,
            element: 'chart_campaigns_fourth',
            data: JSON.parse(JSON.stringify(chart_data)),
            barColors: ["#4e73df", '#02c58d',"#fc5454", "#a0d0e0"],
            xkey: 'BRANCH_NAME',
            ykeys: ['COUNT_SUB'],
            labels: ['عدد المشتركين'],
            hideHover: 'auto',
            resize: true
        });
        Campaigns_chart_4.redraw();
        $(window).trigger('resize');
    }
    
    function Campaigns_chart_fifth(values){
        $.ajax({
            url:"{$fifth_stage_chart_url}",
            method:"POST",
            data:values,
            dataType:"JSON",
            success:function(data)
            {
                $("#chart_campaigns_fifth").empty();
                $("#chart_campaigns_fifth").remove();
                $("div.signals_fifth").append('<div id="chart_campaigns_fifth" style="width: 900px; height: 500px; margin: 0 auto"  height="150"></canvas>');
                drawCampaigns_chart_fifth(data);
            }
        })
    }

    function drawCampaigns_chart_fifth(chart_data){

        var Campaigns_chart_5 =  Morris.Bar({
            barGap:4,
            barSizeRatio:0.55,
            element: 'chart_campaigns_fifth',
            data: JSON.parse(JSON.stringify(chart_data)),
            barColors: ["#4e73df", '#02c58d',"#fc5454", "#a0d0e0"],
            xkey: 'BRANCH_NAME',
            ykeys: ['COUNT_SUB'],
            labels: ['عدد المشتركين'],
            hideHover: 'auto',
            resize: true
        });
        Campaigns_chart_5.redraw();
        $(window).trigger('resize');
    }
    
    function get_report_first_details_pdf() {
        var branch_id =$('#dp_branch_id_1').val();
        var from_date_1 =$('#txt_from_date_1').val();
        var to_date_1 =$('#txt_to_date_1').val();
        _showReport('{$report_url_2}&report_type=pdf&report=request_campaigns_details_2023_pdf&p_branch_id='+branch_id+'&p_discount_type='+''+'&p_for_month='+''+'&p_file_no='+''+'&p_date_from='+from_date_1+'&p_date_to='+to_date_1);
    }
    
    function get_report_first_details_xls() {
        var branch_id =$('#dp_branch_id_1').val();
        var from_date_1 =$('#txt_from_date_1').val();
        var to_date_1 =$('#txt_to_date_1').val();
        _showReport('{$report_url_2}&report_type=xls&report=request_campaigns_details_2023_pdf&p_branch_id='+branch_id+'&p_discount_type='+''+'&p_for_month='+''+'&p_file_no='+''+'&p_date_from='+from_date_1+'&p_date_to='+to_date_1);
    }
     
    function get_report_first_total_dis() {
        _showReport('{$report_url_2}&report_type=pdf&report=request_campaigns_totals_2023_pdf');

    }
    
    function get_report_first_total_dis_2() {
        _showReport('{$report_url_2}&report_type=pdf&report=request_campaigns_totals_2023_2_pdf');

    }
    
    function get_report_first_total_bran() {
        _showReport('{$report_url_2}&report_type=pdf&report=request_campaigns_branch_totals_2023_pdf');
    } 
    
    function get_report_second_details_pdf() {
        var branch_id_2 =$('#dp_branch_id_2').val();
        var from_date_2 =$('#txt_from_date_2').val();
        var to_date_2 =$('#txt_to_date_2').val();
        _showReport('{$report_url_2}&report_type=pdf&report=family_sub_split_details_2023_pdf&p_branch='+branch_id_2+'&p_date_from='+from_date_2+'&p_date_to='+to_date_2);
    }
    
    function get_report_second_details_xls() {
        var branch_id_2 =$('#dp_branch_id_2').val();
        var from_date_2 =$('#txt_from_date_2').val();
        var to_date_2 =$('#txt_to_date_2').val();
        _showReport('{$report_url_2}&report_type=xls&report=family_sub_split_details_2023_pdf&p_branch='+branch_id_2+'&p_date_from='+from_date_2+'&p_date_to='+to_date_2);
    }
    
    function get_report_third_details_pdf() {
        var branch_id_3 =$('#dp_branch_id_3').val();
        var from_date_3 =$('#txt_from_date_3').val();
        var to_date_3 =$('#txt_to_date_3').val();
        _showReport('{$report_url_2}&report_type=pdf&report=fixed_installment_details_2023_pdf&p_branch='+branch_id_3+'&p_date_from='+from_date_3+'&p_date_to='+to_date_3);
    }
    
    function get_report_third_details_xls() {
        var branch_id_3 =$('#dp_branch_id_3').val();
        var from_date_3 =$('#txt_from_date_3').val();
        var to_date_3 =$('#txt_to_date_3').val();
        _showReport('{$report_url_2}&report_type=xls&report=fixed_installment_details_2023_pdf&p_branch='+branch_id_3+'&p_date_from='+from_date_3+'&p_date_to='+to_date_3);
    }

    function get_report_fourth_details_pdf() {
        var branch_id_4 =$('#dp_branch_id_4').val();
        var from_date_4 =$('#txt_from_date_4').val();
        var to_date_4 =$('#txt_to_date_4').val();
        _showReport('{$report_url_2}&report_type=pdf&report=our_family_details_2023_pdf&p_branch='+branch_id_4+'&p_date_from='+from_date_4+'&p_date_to='+to_date_4);
    }
    
    function get_report_fourth_details_xls() {
        var branch_id_4 =$('#dp_branch_id_4').val();
        var from_date_4 =$('#txt_from_date_4').val();
        var to_date_4 =$('#txt_to_date_4').val();
        _showReport('{$report_url_2}&report_type=xls&report=our_family_details_2023_pdf&p_branch='+branch_id_4+'&p_date_from='+from_date_4+'&p_date_to='+to_date_4);
    }
    
    function get_report_fifth_details_pdf() {
        var branch_id_5 =$('#dp_branch_id_5').val();
        var from_date_5 =$('#txt_from_date_5').val();
        var to_date_5 =$('#txt_to_date_5').val();
        _showReport('{$report_url_2}&report_type=pdf&report=join_by_app_details_2023_pdf&p_branch='+branch_id_5+'&p_date_from='+from_date_5+'&p_date_to='+to_date_5);
    }
    
    function get_report_fifth_details_xls() {
        var branch_id_5 =$('#dp_branch_id_5').val();
        var from_date_5 =$('#txt_from_date_5').val();
        var to_date_5 =$('#txt_to_date_5').val();
        _showReport('{$report_url_2}&report_type=xls&report=join_by_app_details_2023_pdf&p_branch='+branch_id_5+'&p_date_from='+from_date_5+'&p_date_to='+to_date_5);
    }
    
</script>
SCRIPT;
sec_scripts($scripts);
?>
