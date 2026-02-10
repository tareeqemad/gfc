
<div class="row" style="padding: 20px;">
    <div class="notifications col-md-4">
        <div class="portlet box red">
            <div class="portlet-title">
                <div class="caption"><i class="icon icon-bar-chart"></i>جديد التنبيهات : <?= get_notify_count() ?></div>
                <?php if(get_notify_count() > 0) : ?>
                    <span class="sonar_ring red"></span>
                <?php endif; ?>
            </div>
            <div class="portlet-body" id="portlet-body_branch">

                <?php foreach($notifications as $row):?>
                    <ul>
                        <li class="alert alert-info">
                            <strong><?= $row['ENTRY_DATE'] ?></strong>
                            <a href="<?=base_url($row['URL']) ?>"><?= $row['NOTIFICATION'] ?> </a>
                        </li>
                    </ul>
                <?php endforeach;?>
            </div>
        </div>
    </div>


    <div class="col-md-4">
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption"><i class="icon icon-bar-chart"></i> إحصاءات مدخلات النظام </div>

            </div>
            <div class="portlet-body" id="portlet-body_branch">
                <div id="site_statistics_loading">

                </div>

                <div id="data_counts-chart-source" class="chart" data-colors="true">

                </div>
            </div>
        </div>


    </div>

    <div class="col-md-4">
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption"><i class="icon icon-bar-chart"></i> إحصاءات قبض الخدمات و الصرف</div>

            </div>
            <div class="portlet-body" id="portlet-body_branch">
                <div id="site_statistics_loading">

                </div>

                <div id="iv_pay_counts-chart-source" class="chart" data-colors="true">

                </div>
            </div>
        </div>


    </div>

</div>

<?php
$popup_url = base_url('hr/Conflict_interest_agree/show_endorsement');
$conflict_insert_url = base_url('hr/Conflict_interest_agree/insert');
$conflict_interest_agree=0;
if(isset($this->user->conflict_interest)){
    if($this->user->conflict_interest==1){
        $conflict_interest_agree=1;
    }
}

$data_counts_st='var data_branch=[];';
foreach($data_counts as $row ):
    $data_counts_st .="  data_branch.push({
            label: '{$row['ITEM']} - {$row['RES']}' ,
            data: ". ($row['RES'])."
        });";
endforeach;


$i_v_counts='[';
foreach($income_voucher_counts as $row ):
    $i_v_counts .="[{$row['CDATE']},". $row['C']."],";
endforeach;
$i_v_counts .=']';


$pay_counts='[';
foreach($payment_counts as $row ):
    $pay_counts .="[{$row['CDATE']},". $row['C'] ."],";
endforeach;
$pay_counts .=']';

$script =<<<SCRIPT
    <script>
    /**** Conflict_interest_agree *****/ 
    
    $( document ).ready(function() {
        if('$conflict_interest_agree'!=1){
            _showReport('$popup_url');
            $('.modal-footer').hide();
            $('.navbar-nav > li:not(.first)').hide();
        } 
    });
    
    var url_clicked= 0;
    
    function click_url(){
        url_clicked= 1;
    }
    
    function agree_ok() {
        var msg= 'لقد قرأت نظام تضارب المصالح وأقر بالتزامي به';
        if (url_clicked){
            if(confirm(msg)){
                get_data('{$conflict_insert_url}', {}, function(ret){
                    if(ret==1){
                        success_msg('رسالة','تمت الموافقة على النظام ..');
                        setTimeout(function() {
                            get_to_link(window.location.href);
                        }, 1000);
                    }else{
                        danger_msg('تحذير..',data);
                    }
                }, 'html');
            }
        }else {
            alert('يجب الاطلاع على النظام اولا..');
        }
    }
    
    /**** Conflict_interest_agree *****/ 

       $data_counts_st


 $.plot($("#data_counts-chart-source"), data_branch, {
            series: {
        pie: {
        innerRadius: 0.5,
            show: true
        }
    }
        });


    var plot =$.plot("#iv_pay_counts-chart-source", [{label: "سندات القبض", data: $i_v_counts},{ label: "سندات الصرف", data: $pay_counts }]
       , {xaxis: {
				ticks: [
					1,2,3,4,5,6,7,8,9,10,11,12
				]
			},yaxis: {
    axisLabel: "Population (multiply by 10,000)",
    axisLabelUseCanvas: true,
    axisLabelFontSizePixels: 12,
    axisLabelFontFamily: 'Verdana, Arial',
    axisLabelPadding: 3,
    tickFormatter: function (v, axis) {
        return $.formatNumber(v, { format: "#,###", locale: "us" });
    }
    },grid: {
				hoverable: true,
				clickable: true
			},

			}

    );


$("<div id='tooltip'></div>").css({
			position: "absolute",
			display: "none",
			border: "1px solid #fdd",
			padding: "2px",
			"background-color": "#fee",
			opacity: 0.80
		}).appendTo("body");

		$("#iv_pay_counts-chart-source").bind("plothover", function (event, pos, item) {

			if ($("#enablePosition:checked").length > 0) {
				var str = "(" + pos.x.toFixed(2) + ", " + pos.y.toFixed(2) + ")";
				$("#hoverdata").text(str);
			}

			if ($("#enableTooltip:checked").length > 0) {
				if (item) {
					var x = item.datapoint[0].toFixed(2),
						y = item.datapoint[1].toFixed(2);

					$("#tooltip").html(item.series.label + " of " + x + " = " + y)
						.css({top: item.pageY+5, left: item.pageX+5})
						.fadeIn(200);
				} else {
					$("#tooltip").hide();
				}
			}
		});

		$("#iv_pay_counts-chart-source").bind("plotclick", function (event, pos, item) {
			if (item) {
				$("#clickdata").text(" - click point " + item.dataIndex + " in " + item.series.label);
				plot.highlight(item.series, item.datapoint);
			}
		});

    </script>
SCRIPT;


sec_scripts($script);


?>