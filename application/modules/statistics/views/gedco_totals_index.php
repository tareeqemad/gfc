<?php
/**
 * Created by PhpStorm.
 * User: ashikhali & telbawab & Mkilani
 * Date: 15/02/22
 * Time: 09:08 ص
 */
$MODULE_NAME= 'statistics';
$TB_NAME= 'Gedco_totals';
$first_stage_url = base_url("$MODULE_NAME/$TB_NAME/public_first_stage");
$second_stage_url = base_url("$MODULE_NAME/$TB_NAME/public_second_stage");
$second_stage_chart_url  = base_url("$MODULE_NAME/$TB_NAME/public_second_stage_chart");
$third_stage_url = base_url("$MODULE_NAME/$TB_NAME/public_third_stage");
$third_stage_chart_url = base_url("$MODULE_NAME/$TB_NAME/public_third_stage_chart");
$fourth_stage_url = base_url("$MODULE_NAME/$TB_NAME/public_fourth_stage");
$fourth_stage_chart_url  = base_url("$MODULE_NAME/$TB_NAME/public_fourth_stage_chart");
$fifth_stage_url = base_url("$MODULE_NAME/$TB_NAME/public_fifth_stage");
$fifth_stage_chart_url = base_url("$MODULE_NAME/$TB_NAME/public_fifth_stage_chart");
$sixth_stage_url = base_url("$MODULE_NAME/$TB_NAME/public_sixth_stage");
$sixth_stage_chart_url = base_url("$MODULE_NAME/$TB_NAME/public_sixth_stage_chart");
$seventh_stage_url = base_url("$MODULE_NAME/$TB_NAME/public_seventh_stage");
$report_url = base_url("JsperReport/showreport?sys=statistics");
?>
<style>
    .prints {
        display: inline;
        position: relative;
    }

    .prints:hover:after {
        background: #333;
        background: rgba(0, 0, 0, .8);
        border-radius: 5px;
        bottom: -19px;
        color: #fff;
        content: attr(h_title);
        right: 20%;
        padding: 5px 5px;
        position: absolute;
        z-index: 99;
        width: 200px;
        font-size: small;
        text-align: center;
    }

    .prints:hover:before {
        border: solid;
        border-color: #333 transparent;
        border-width: 0 6px 6px 6px;
        bottom: 9px;
        content: "";
        right: 45%;
        position: absolute;
        z-index: 99;
    }
</style>
<div class="row">
    <div class="toolbar">
        <div class="caption"><?= $title ?></div>
        <ul></ul>
    </div>
    <div class="form-body">
        <form class="form-vertical" id="<?=$TB_NAME?>_form" >
            <div class="modal-body inline_form">
                <div class="form-group col-sm-2">
                    <label class="control-label">التاريخ</label>
                    <div>
                        <input type="text" data-val-required="حقل مطلوب" data-type="date"
                               data-date-format="DD/MM/YYYY"
                               value="<?=date('d/m/Y')?>"
                               name="txt_date" id="txt_date" class="form-control valid">
                        <input type="hidden" name="today_date" id="txt_today_date" value="<?=date('d/m/Y')?>">
                        <input type="hidden" name="yesterday_date" id="txt_yesterday_date" value="<?=date('d/m/Y', strtotime(' -1 day'))?>">
                        <input type="hidden" name="date_second_format" id="txt_date_second_format" >
                    </div>
                </div>

                <div class="form-group col-sm-2">
                    <label class="control-label" style="color: white">_</label>
                    <div>
                        <button type="button" onclick="javascript:changeDate('today');" class="btn btn-success"> اليوم</button>
                        <button type="button" onclick="javascript:changeDate('yesterday');" class="btn btn-warning"> الأمس</button>
                    </div>
                </div>
                <div style="clear: both"></div>
            </div>
        </form>

        <div id="msg_container"></div>
        <div id="container">
            <ul id="tabs" class="nav nav-tabs tabs">
                <li id="1" class="active"><a href="#tab1" data-toggle="tab">الأحمال والعجز</a></li>
                <li id="2"><a href="#tab2" data-toggle="tab">اشارات الصيانة الطارئة</a></li>
                <li id="3"><a href="#tab3" data-toggle="tab">مركز الاتصالات</a></li>
                <li id="4"><a href="#tab4" data-toggle="tab">معاملات التجاري والتفتيش</a></li>
                <li id="5"><a href="#tab5" data-toggle="tab">تحصيلات التجاري والتفتيش</a></li>
                <li id="6"><a href="#tab6" data-toggle="tab">تحصيلات الفوترة والشحنات</a></li>
                <li style="display: none" id="7"><a href="#tab7" data-toggle="tab">الشكاوي</a></li>
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

                <div class="tab-pane fade" id="tab6">
                    <div id="container_tab6">

                    </div>
                </div>

                <div class="tab-pane fade" id="tab7">
                    <div id="container_tab7">

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<?php
$scripts = <<<SCRIPT
<script>

	var tabs_loaded= {1:0, 2:0, 3:0, 4:0, 5:0, 6:0, 7:0};
    
    date_second_format();

    function changeDate(day){
		tabs_loaded= {1:0, 2:0, 3:0, 4:0, 5:0, 6:0, 7:0}
        if(day == 'today'){
            $('#txt_date').val($('#txt_today_date').val());
        } else if(day == 'yesterday'){
            $('#txt_date').val($('#txt_yesterday_date').val());
        }
        date_second_format();
        var active_tab = $('ul.tabs').find('li.active');
		var tab_id= active_tab.prop('id');
		if(tab_id==1 && day == 'yesterday'){
			warning_msg('تنويه','تقرير الاحمال متاح لآخر 24 ساعة فقط');
		}else{
			getData(tab_id);
		}
    }
    
    function date_second_format(){
        var dateSplite = $('#txt_date').val().split("/");
        var year = dateSplite[2];
        var month = dateSplite[1] ;
        var day = dateSplite[0];
        var date_new_format = year+''+month+''+day;
        $('#txt_date_second_format').val(date_new_format);
    }
    
    $("#txt_date").change(function() {
        date_second_format();
        var date_differance = string_to_date($('#txt_today_date').val()).getTime() - string_to_date($(this).val()).getTime();
		tabs_loaded= {1:0, 2:0, 3:0, 4:0, 5:0, 6:0, 7:0}
        var active_tab = $('ul.tabs').find('li.active');
		var tab_id= active_tab.prop('id');
		if(tab_id==1){
			warning_msg('تنويه','تقرير الاحمال متاح لآخر 24 ساعة فقط');
		} else if ( date_differance < 0 ){ /// 
			warning_msg('تنويه','الاحصائيات لا تعمل لتواريخ بعد تاريخ اليوم');
		} else {
			getData(tab_id);
		}
    });
   
    $("ul.tabs").on('click', 'li' ,function(event) {
        var tabId = this.id;
		if(tabs_loaded[tabId]==0){
			getData(tabId);
		}
    });
	
    // when update page get data for first stage
    getData(1);
    
    function getData(type){
        var values= { date_1 : $('#txt_date').val() };
        $('#container_tab'+type).html('');
		if(type == 1){
            get_data('{$first_stage_url}',values ,function(data){
                $('#container_tab1').html(data);
            },'html');
        } else if(type == 2) {
            get_data('{$second_stage_url}',values ,function(data){
                $('#container_tab2').html(data);
                $("#chart_emergency").empty();
                 Emergency_chart(values);
            },'html');
        } else if(type == 3){
            get_data('{$third_stage_url}',values ,function(data){
                $('#container_tab3').html(data);
                $("#chart_callCenter").empty();
                 CallCenter_chart(values);
            },'html');
        }else if(type == 4){
            get_data('{$fourth_stage_url}',values ,function(data){
                $('#container_tab4').html(data);
                $("#chart_day_trading_statistics").empty();
                 Day_trading_statistics_chart(values);
            },'html');
        }else if(type == 5){
            get_data('{$fifth_stage_url}',values ,function(data){
                $('#container_tab5').html(data);
                $("#chart_day_trading_statistics2").empty();
                Day_trading_statistics2_chart(values);
            },'html');
        }else if(type == 6){
            get_data('{$sixth_stage_url}',values ,function(data){
                $('#container_tab6').html(data);
                $("#chart_day_collection").empty();
                Day_collection_chart(values);
            },'html');
         }else if(type == 7){
            get_data('{$seventh_stage_url}',values ,function(data){
                $('#container_tab7').html(data);
            },'html');
        }
		tabs_loaded[type]= 1;
    }
    
    function Emergency_chart(values){
      $.ajax({
                url:"{$second_stage_chart_url}",
                method:"POST",
                data:values,
                dataType:"JSON",
                success:function(data)
                {
                    $("#chart_emergency").empty(); // destroy chart
                    $("#chart_emergency").remove();
                    $("div.signals").append('<div id="chart_emergency" style="width: 900px; height: 500px; margin: 0 auto"  height="150"></canvas>');
                    drawEmergency_chart(data);
               }
      })
    }
   
    function drawEmergency_chart(chart_data){           
    	     var Emergency_chart =  Morris.Bar({
    	       barGap:4,
               barSizeRatio:0.55,
               element: 'chart_emergency',
               data: JSON.parse(JSON.stringify(chart_data)), //data is an array of objects
               barColors: ["#4e73df", '#02c58d',"#fc5454", "#a0d0e0"],
               xkey: 'BRANCH_NAME',
               ykeys: ['NO_SIGNAL','PROCESSING','REPEATED','ENTRY'],
               labels: ['عدد الاشارات','المعالجة','المتكررة','غير معالجة'],
               hideHover: 'auto',
               resize: true
            });
         Emergency_chart.redraw();
         $(window).trigger('resize');
    } 
    
     function CallCenter_chart(values){
      $.ajax({
                url:"{$third_stage_chart_url}",
                method:"POST",
                data:values,
                dataType:"JSON",
                success:function(data)
                {
                    $("#chart_callCenter").empty(); // destroy chart
                    $("#chart_callCenter").remove();
                    $("div.callCenter").append('<div id="chart_callCenter" style="width: 900px; height: 500px; margin: 0 auto"  height="150"></canvas>');
                    drawCallCenter_chart(data);
               }
      })
    }
    
    
      function drawCallCenter_chart(chart_data){           
    	     var CallCenter_chart =  Morris.Bar({
    	       barGap:4,
               barSizeRatio:0.55,
               element: 'chart_callCenter',
               data: JSON.parse(JSON.stringify(chart_data)), //data is an array of objects
               barColors: ["#4e73df"],
               xkey: 'TYPE_Q_NAME',
               ykeys: ['ALL_BRANCH'],
               labels: ['كل الحركات'],
               hideHover: 'auto',
               resize: true
            });
         CallCenter_chart.redraw();
         $(window).trigger('resize');
    } 
     
 
    function Day_trading_statistics_chart(values){
      $.ajax({
                url:"{$fourth_stage_chart_url}",
                method:"POST",
                data:values,
                dataType:"JSON",
                success:function(data)
                {
                    $("#chart_day_trading_statistics").empty(); // destroy chart
                    $("#chart_day_trading_statistics").remove();
                    $("div.day_trading_statistics").append('<div id="chart_day_trading_statistics" style="width: 900px; height: 500px; margin: 0 auto"  height="150"></canvas>');
                    drawTrading_statistics_chart(data);
               }
      })
    }
    
    function drawTrading_statistics_chart(chart_data){        
    	 var Trading_statistics_chart =  Morris.Bar({
    	       barGap:4,
               barSizeRatio:0.55,
               element: 'chart_day_trading_statistics',
               data: JSON.parse(JSON.stringify(chart_data)), //data is an array of objects
               barColors: ["#4e73df", '#02c58d',"#fc5454", "#a0d0e0"],
               xkey: 'TYPE_NAME',
               ykeys: ['GAZA','NORTH','MIDDLE','KHAN','RAFAH'],
               labels: ['غزة','الشمال','الوسطى','خانيونس','رفح'],
               hideHover: 'auto',
               resize: true
         });
         Trading_statistics_chart.redraw();
         $(window).trigger('resize');
    } 
    
    function Day_trading_statistics2_chart(values){
      $.ajax({
                url:"{$fifth_stage_chart_url}",
                method:"POST",
                data:values,
                dataType:"JSON",
                success:function(data)
                {
                    $("#chart_day_trading_statistics2").empty(); // destroy chart
                    $("#chart_day_trading_statistics2").remove();
                    $("div.day_trading_statistics2").append('<div id="chart_day_trading_statistics2" style="width: 900px; height: 500px; margin: 0 auto"  height="150"></canvas>');
                    drawTrading_statistics2_chart(data);
               }
      })
    }
    
    
    function drawTrading_statistics2_chart(chart_data){        
    	 var Trading_statistics2_chart =  Morris.Bar({
    	       barGap:4,
               barSizeRatio:0.55,
               element: 'chart_day_trading_statistics2',
               data: JSON.parse(JSON.stringify(chart_data)), //data is an array of objects
               barColors: ["#4e73df", '#02c58d',"#fc5454", "#a0d0e0"],
               xkey: 'TYPE_NAME',
               ykeys: ['GAZA','NORTH','MIDDLE','KHAN','RAFAH'],
               labels: ['غزة','الشمال','الوسطى','خانيونس','رفح'],
               hideHover: 'auto',
               resize: true
         });
         Trading_statistics2_chart.redraw();
         $(window).trigger('resize');
    } 
    
    function Day_collection_chart(values){
          $.ajax({
                    url:"{$sixth_stage_chart_url}",
                    method:"POST",
                    data:values,
                    dataType:"JSON",
                    success:function(data)
                    {
                        $("#chart_day_collection").empty(); // destroy chart
                        $("#chart_day_collection").remove();
                        $("div.day_collection").append('<div id="chart_day_collection" style="width: 900px; height: 500px; margin: 0 auto"  height="150"></canvas>');
                        drawCollection_chart(data);
                   }
          })
     }
     
    function drawCollection_chart(chart_data){        
    	 var Collection_chart =  Morris.Bar({
    	       barGap:4,
               barSizeRatio:0.55,
               element: 'chart_day_collection',
               data: JSON.parse(JSON.stringify(chart_data)), //data is an array of objects
               barColors: ["#4e73df", '#02c58d',"#fc5454", "#a0d0e0"],
               xkey: 'BRANCH_NAME',
               ykeys: ['CASH_COUNT','CASH_VAL','HOLLEY_COUNT','HOLLEY_VAL','DEXEN_COUNT','DEXEN_VAL'],
               labels: ['عدد الفواتير','قيمة الفواتير','عدد شحنات الهولي','قيمة شحنات الهولي<','عدد شحنات الدكسن','قيمة شحنات الدكسن'],
               hideHover: 'auto',
               resize: true
         });
         Collection_chart.redraw();
         $(window).trigger('resize');
     } 
 
	function print_report(stage, sub, dt_type){
			
		var reps_json= {
			2:{1:"emergency_maintenance_totals_mx"},
			3:{1:"call_center_totals_mx"},
			4:{1:"trading_services_totals_mx", 2:"incpection_services_totals_mx"},
			5:{1:"trading_incpection_collections_mx"},
			6:{1:"cash_collections_totals_mx", 2:"holley_collections_totals_mx", 3:"dexcen_collections_totals_mx"},
			7:{1:"complaints_totals_mx"}
			};


		var rep_name= reps_json[stage][sub];
		var date_1 = $('#txt_date').val();
		if(dt_type==2){
			date_1= $('#txt_date_second_format').val();  //////
		}
		
		var rep_url = '{$report_url}&report_type=pdf'+'&report='+rep_name+'&p_date_from='+date_1+'&p_date_to='+date_1;
		_showReport(rep_url);
	}

</script>
SCRIPT;
sec_scripts($scripts);
?>
