<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: Ahmed Barakat
 * Date: 31/05/15
 * Time: 10:10 ص
 */
?>

<div id="map" style="height: 490px;"></div>

<script type="text/javascript"
        src="https://maps.google.com/maps/api/js?language=ar&sensor=true&key=AIzaSyA0qdwV2hXgZOr-TfvZDWLa-Uzt-5aRXFs"></script>
<!--
<script type="text/javascript"
        src="https://maps.google.com/maps/api/js?language=ar&sensor=true&key=AIzaSyDQDC3VV5CGRaueUYpEEJ308KNx8zbG5t0"></script>
-->
<?php

if (isset($lut)) {
    $edit_script = <<<SCRIPT
 <script type="text/javascript">
    var map;
    var lat = parent.$('#$lut').val();
    var lng = parent.$('#$lng').val();

    $(document).ready(function(){

        map = new GMaps({
            div: '#map',
             lat:lat > 0 ? lat :31.378056329684597 ,
            lng:lng > 0? lng : 34.337843605224634,
            zoom:12
        });

        map.addMarker({
            lat:lat > 0 ? lat :31.378056329684597 ,
            lng:lng > 0? lng : 34.337843605224634,
            draggable: true,
            dragend: function(e) {

              parent.$('#$lng').val(e.latLng.lng());
              parent.$('#$lut').val(e.latLng.lat());
            }
        });


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
</script>
SCRIPT;

} else {

    $edit_script = <<<SCRIPT
 <script type="text/javascript">
    var map;
    var gps = parent.$('#$location').val(); 
    var lat = 0 , lng = 0;
    
    if(gps != null && gps !=''){
        
        lat = gps.split("|")[0];
        lng = gps.split("|")[1];

    }
    console.log(lat,lng);
    $(document).ready(function(){

        map = new GMaps({
            div: '#map',
             lat:lat > 0 ? lat :31.507055 ,
            lng:lng > 0? lng : 34.455634,
            zoom:16
        });

        map.addMarker({
            lat:lat > 0 ? lat :31.507055 ,
            lng:lng > 0? lng : 34.455634,
            draggable: true,
            dragend: function(e) {

              parent.$('#$location').val(e.latLng.lat() + '|'+e.latLng.lng());
               
            }
        });


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
</script>
SCRIPT;
}

sec_scripts($edit_script);

?>
