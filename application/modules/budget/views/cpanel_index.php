<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 9/6/14
 * Time: 8:09 AM
 */

?>


<div class="row cpanel">

    <div class="form-body">
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="dashboard-stat green">
                <div class="visual">
                    <i class="icon icon-shekel"></i>
                </div>
                <div class="details">
                    <div class="number">
                        <?= n_format($budget_a_rev_statistic ) ?>
                    </div>
                    <div class="desc">
                        إجمالي الإيردات المعتمدة                    </div>
                </div>

            </div>
        </div>

        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="dashboard-stat red">
                <div class="visual">
                    <i class="icon icon-shekel"></i>
                </div>
                <div class="details">
                    <div class="number">
                        <?= n_format($budget_a_exp_statistic) ?>
                    </div>
                    <div class="desc">
                        إجمالي النفقات المعتمدة                    </div>
                </div>

            </div>
        </div>


        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="dashboard-stat blue">
                <div class="visual">
                    <i class="icon icon-shekel"></i>
                </div>
                <div class="details">
                    <div class="number">
                        <?=n_format($budget_rev_statistic)  ?>
                    </div>
                    <div class="desc">
                        إجمالي الإيرادات الغير معتمدة                    </div>
                </div>

            </div>
        </div>

        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <div class="dashboard-stat yellow">
                <div class="visual">
                    <i class="icon icon-shekel"></i>
                </div>
                <div class="details">
                    <div class="number">
                        <?=  n_format($budget_exp_statistic) ?>
                    </div>
                    <div class="desc">
                        إجمالي النفقات الغير معتمدة                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

<div id="ctl21_statistics" class="row">
    <div class="col-md-6 col-sm-6">
        <!-- BEGIN PORTLET-->
        <div class="portlet box purple">
            <div class="portlet-title">
                <div class="caption"><i class="icon icon-bar-chart"></i> إحصاءات موازنات سابقة - فائض و عجز</div>

            </div>
            <div class="portlet-body">
                <div id="site_statistics_loading">
                    <img src="<?= base_url('assets/images/loading.gif') ?>" width="48px" alt="loading"/>
                </div>
                <div id="site_statistics_content">
                    <div id="site_statistics" class="chart"></div>
                </div>
            </div>
        </div>
        <!-- END PORTLET-->
    </div>

    <div class="col-md-6 col-sm-6">
        <!-- BEGIN PORTLET-->
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption"><i class="icon icon-bar-chart"></i> إحصاءات موازنات - حساب المقر فائض و عجز</div>

            </div>
            <div class="portlet-body" id="portlet-body_branch">
                <div id="site_statistics_loading">

                </div>
                <div id="site_statistics_content">
                    <div id="site_statistics_branch" class="chart"></div>
                </div>
            </div>
        </div>
        <!-- END PORTLET-->
    </div>

</div>
<!-- END DASHBOARD STATS -->
<?php

$budget_statistic_data='[';
foreach($budget_statistic as $row ):
    $budget_statistic_data .="[{$row['YYEAR']},". ($row['ACTUAL_VALUE'] < 0?$row['ACTUAL_VALUE']   :$row['ACTUAL_VALUE'])."],";
endforeach;
$budget_statistic_data .=']';

$budget_statistic_data_branch='var data_branch=[];';
foreach($budget_statistic_branch as $row ):
    $budget_statistic_data_branch .="  data_branch.push({
            label: '<span class=\'statistic\'>{$row['NAME']}<br/>". ($row['ACTUAL_VALUE'] < 0?$row['ACTUAL_VALUE']   :$row['ACTUAL_VALUE'])."</span>' ,
            data: ". ($row['ACTUAL_VALUE'] < 0?$row['ACTUAL_VALUE'] *-1 :$row['ACTUAL_VALUE'])."
        });";
endforeach;



$scripts = <<<SCRIPT

<script>
    var data=[];


    data[0] = {
        label: ' إحصاءات موازنات سابقة',
        data:$budget_statistic_data
    };
    if ($('#site_statistics').size() != 0) {

        $('#site_statistics_loading').hide();
        $('#site_statistics_content').show();

        var plot_statistics = $.plot($("#site_statistics"), data, {
            series: {
                lines: {
                    show: true,
                    lineWidth: 2,
                    fill: true,
                    fillColor: {
                        colors: [{
                            opacity: 0.05
                        }, {
                            opacity: 0.01
                        }
                        ]
                    }
                },
                points: {
                    show: true
                },
                shadowSize: 2
            },
            grid: {
                hoverable: true,
                clickable: true,
                tickColor: "#eee",
                borderWidth: 0
            },
            colors: ["#d12610", "#37b7f3", "#52e136"],
            xaxis: {
                ticks: 12,
                tickDecimals: 0
            },
            yaxis: {
                ticks: 20,
                tickDecimals: 0
            }
        });


    }

    $budget_statistic_data_branch



 $.plot($("#site_statistics_branch"), data_branch, {
            series: {
                pie: {
                    show: true,
                    combine: {
                        color: '#999',
                        threshold: 0.001
                    }
                }
            },
            legend: {
                show: false
            }
        });



</script>

SCRIPT;

sec_scripts($scripts);



?>
