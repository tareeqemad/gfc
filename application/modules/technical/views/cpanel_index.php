<?php
/**
 * Created by PhpStorm.
 * User: abarakat
 * Date: 17/04/16
 * Time: 10:29 ص
 */

?>


    <div>

        <div class="widget col-md-4">
            <div class="widget-header"><i class="icon icon-bookmark"></i>

                <h3>قائمة مختصرة</h3>
            </div>
            <!-- /widget-header -->
            <div class="widget-content">
                <div class="shortcuts">

                    <a href="<?= base_url('technical/Requests/create') ?>"> إضافة طلب</a>
                    <a href="<?= base_url('technical/WorkOrder') ?>"> عرض أوامر العمل </a>
                    <a href="<?= base_url('technical/WorkOrderAssignment/create') ?>"> إضافة تكليف</a>
                    <a href="<?= base_url('technical/electricity_layers/index') ?>"> توزيع الأحمال</a>

                    <a href="<?= base_url('technical/branches_teams/index') ?>"> فرق العمل(الفنية) </a>
                    <a href="<?= base_url('projects/projects/archive') ?>"> أرشيف المشاريع </a>
                    <a href="<?= base_url('projects/adapter') ?>"> إدارة المحولات </a>
                    <a href="<?= base_url('stores/stores_payment_request/index') ?>"> طلب صرف مخزني </a>


                </div>
                <!-- /shortcuts -->
            </div>
            <!-- /widget-content -->
        </div>

        <div class="widget col-md-4">
            <div class="widget-header"><i class="icon icon-list-alt"></i>

                <h3>إحصاءات </h3>
            </div>
            <!-- /widget-header -->
            <div class="widget-content">
                <div id="big_stats" class="cf">
                    <div class="stat"><i class=""> الطلبات الفنية</i> <span
                            class="value"><?= $all_info[2]['THE_COUNT'] ?></span></div>
                    <!-- .stat -->

                    <div class="stat"><i class="">أوامر العمل</i> <span
                            class="value"><?= $all_info[0]['THE_COUNT'] ?></span></div>
                    <!-- .stat -->

                    <div class="stat"><i class="">تكليف العمل</i> <span
                            class="value"><?= $all_info[1]['THE_COUNT'] ?></span></div>
                    <!-- .stat -->


                </div>

            </div>
            <!-- /widget-content -->
        </div>

        <hr>


        <div class="col-md-4">
            <div class="portlet box green">
                <div class="portlet-title">
                    <div class="caption"><i class="icon icon-bar-chart"></i> إحصاءات الطلبات الفنية</div>

                </div>
                <div class="portlet-body" id="portlet-body_branch">
                    <div id="site_statistics_loading">

                    </div>

                    <div id="request_counts-chart-source" class="chart" data-colors="true">

                    </div>
                </div>
            </div>


        </div>

        <div class="col-md-4">
            <div class="portlet box red">
                <div class="portlet-title">
                    <div class="caption"><i class="icon icon-bar-chart"></i> إحصاءات أوامر العمل</div>

                </div>
                <div class="portlet-body" id="portlet-body_branch">
                    <div id="site_statistics_loading">

                    </div>

                    <div id="work_order-chart-source" class="chart" data-colors="true">

                    </div>
                </div>
            </div>


        </div>

        <div class="col-md-4">
            <div class="portlet box blue">
                <div class="portlet-title">
                    <div class="caption"><i class="icon icon-bar-chart"></i> إحصاءات تكاليف العمل</div>

                </div>
                <div class="portlet-body" id="portlet-body_branch">
                    <div id="site_statistics_loading">

                    </div>

                    <div id="work_order_asss-chart-source" class="chart" data-colors="true">

                    </div>
                </div>
            </div>


        </div>
        <hr>
        <div class="col-md-12">
            <div class="portlet box blue">
                <div class="portlet-title">
                    <div class="caption"><i class="icon icon-bar-chart"></i>   المخطط الزمني</div>

                </div>
                <div id='calendar' ></div>
            </div>
        </div>
    </div>

    <div class="clearfix"></div>


<?php


$data_counts_st = 'var request_counts=[];';
foreach ($count_requests_tb as $row):
    $data_counts_st .= "  request_counts.push({
            label: '{$row['REQUEST_TYPE_NAME']} ({$row['THE_COUNT']}) ' ,
            data: " . ($row['THE_COUNT']) . "
        });";
endforeach;


$data_counts_st .= 'var work_order_counts=[];';
foreach ($count_work_order_requests as $row):
    $data_counts_st .= "  work_order_counts.push({
            label: '{$row['REQUEST_TYPE_NAME']} ({$row['THE_COUNT']}) ' ,
            data: " . ($row['THE_COUNT']) . "
        });";
endforeach;

$data_counts_st .= 'var work_order_asss=[];';
foreach ($count_work_order_asss_requests as $row):
    $data_counts_st .= "  work_order_asss.push({
            label: '{$row['REQUEST_TYPE_NAME']} ({$row['THE_COUNT']}) ' ,
            data: " . ($row['THE_COUNT']) . "
        });";
endforeach;

$tec = base_url('technical/WorkOrderAssignment/get/');
$WorkOrderAssignment_rows ='var events_row = [];';
foreach ($WorkOrderAssignment as $row):
    $WorkOrderAssignment_rows .= "  events_row.push({
            title: '{$row['TITLE']}  ' ,
            start:  '" . ($row['TIME_OUT_C']) . "' ,
                url: '{$tec}/{$row['WORK_ORDER_ASSIGNMENT_ID']}/index',
            allDay:false
        });";
endforeach;
$script = <<<SCRIPT
    <script>

       $data_counts_st
$WorkOrderAssignment_rows
 $.plot($("#request_counts-chart-source"), request_counts, {
            series: {
        pie: {
        innerRadius: 0.5,
            show: true
        }
    }
        });


        $.plot($("#work_order-chart-source"), work_order_counts, {
            series: {
        pie: {
        innerRadius: 0.5,
            show: true
        }
    }
        });

$.plot($("#work_order_asss-chart-source"), work_order_asss, {
            series: {
        pie: {
        innerRadius: 0.5,
            show: true
        }
    }
        });



		$('#calendar').fullCalendar({
			theme: true,
			header: {
				left: 'prev,next today',
				center: 'title',
				right: 'month,agendaWeek,agendaDay'
			},
			//defaultDate: '2016-05-12',

			events:  events_row
		});


    </script>
SCRIPT;


sec_scripts($script);


?>