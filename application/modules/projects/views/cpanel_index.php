<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 9/6/14
 * Time: 8:09 AM
 */

?>

<div class="row">


</div>

<div class="row cpanel">
    <div class="form-group col-md-12">
        <div class="portlet box purple">
            <div class="portlet-title">
                <div class="caption">توزيع المحولات علي الخريطة</div>

            </div>

            <div id="map" style="height: 600px;"></div>
        </div>
    </div>

    <div class="form-group col-md-6" style="display: none;">
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption"><i class="icon icon-bar-chart"></i>إحصاءات المشاريع</div>

            </div>
            <div id="site_statistics_content">
                <div id="site_statistics" class="chart"></div>
            </div>
        </div>
    </div>

    <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true"></script>

    <?php

    $project_statistic_data='[';
    foreach($project_set as $row ):
        $project_statistic_data .="['{$row['ACCOUNT_DATE']}',{$row['C']}],";
    endforeach;
    $project_statistic_data .=']';

    $adapters_str = '';
    foreach($adapters as $row){
        if($row['GIS_X'] != '')
        $adapters_str .= "[{$row['GIS_Y']}, {$row['GIS_X']}],";
    }

    $map = '';
    foreach($adapters as $row){

        if($row['GIS_X'] != '') {
            $icon = $row['STATUS'] == 1? base_url()."assets/images/green-dot.png" :  base_url()."assets/images/red-dot.png" ;
            $map .= "map.addMarker({lat: {$row['GIS_Y']},lng: {$row['GIS_X']},title : '{$row['ADAPTER_NAME']}' , icon : '$icon' }); ";
        }
    }

    $edit_script =<<<SCRIPT
 <script type="text/javascript">
    var map;
    var lat;
    var lng;

    $(document).ready(function(){

        map = new GMaps({
            div: '#map',
            lat:  31.418313945065947,
            lng:  34.349312730972315,
            zoom:12
        });

        path = [{$adapters_str}[-1, -1]];

        path = path

       /* $.each(path,function(index,val){
        map.addMarker({
            lat:lat > 0 ? lat :val[0] ,
            lng:lng > 0? lng : val[1],
            title : ''
            dragend: function(e) {
              // e.latLng

            }
        });
        });*/

        $map




    });
var geocoder = new google.maps.Geocoder();
    function geocodePosition(pos) {
  geocoder.geocode({
    latLng: pos
  }, function(responses) {
    if (responses && responses.length > 0) {
      console.log('',responses[0]);
    } else {
      console.log('','Cannot determine address at this location.');
    }
  });
}


 var data=[];


    data[0] = {
        label: 'إحصاءات المشاريع',
        data:$project_statistic_data
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

</script>
SCRIPT;




    sec_scripts($edit_script);

    ?>


</div>