<?php
/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 13/03/19
 * Time: 01:39 م
 */
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="viewport" content="initial-scale=1, maximum-scale=1,user-scalable=no">
<title>Customers Gedco Gis Map</title>

<link rel="stylesheet" href="https://js.arcgis.com/3.27/esri/css/esri.css">

<link rel="stylesheet" href="slider.css"/>

<style>
    html, body {
        height: 100%;
        width: 100%;
        margin: 0;
        padding: 0;
        font-size: 0.90em;
        font-family: Verdana, Helvetica, sans-serif;
        color: #282a2c;
    }

    #map {
        height: 100%;
        width: 100%;
    }



</style>

<script src="https://js.arcgis.com/3.27/"></script>

<script>


    require([
        "esri/InfoTemplate",
        "esri/map",
        "esri/layers/FeatureLayer",
        "esri/tasks/query",
        "dojo/domReady!"
    ],
        function(
            InfoTemplate,
            Map,
            FeatureLayer,
            Query
            ) {

            var map = new Map("map", {
                basemap: "hybrid",
                center: [34.45, 31.48],
                zoom: 13
            });
            map.on("load", initOperationalLayer);
            function initOperationalLayer() {
                var infoTemplate = new InfoTemplate("Attributes", "${*}");
                /****************************************************************
                 * Add feature layer - A FeatureLayer at minimum should point
                 * to a URL to a feature service or point to a feature collection
                 * object.
                 ***************************************************************/

                // Carbon storage of MV_OUTDOOR_SWITCHES,TRANSFORMERS,M_V_NETWORK,ROOMS_NEW,GAZA_STRIP_NEIGHBORHOODS in Gaza Strip.
                var LV_POLES = new FeatureLayer("https://10.200.10.7:6443/arcgis/rest/services/GIS_GEDCO/Gaza_customers/FeatureServer/0",
                    {
                        mode: FeatureLayer.MODE_ONDEMAND,
                        outFields: ["*"],
                        infoTemplate: infoTemplate
                    });
                var CUSTOMERS = new FeatureLayer("https://10.200.10.7:6443/arcgis/rest/services/GIS_GEDCO/Gaza_customers/FeatureServer/1",
                    {
                        mode: FeatureLayer.MODE_ONDEMAND,
                        outFields: ["*"],
                        infoTemplate: infoTemplate
                    });
              var CUSTOMERS_CABLE  = new FeatureLayer("https://10.200.10.7:6443/arcgis/rest/services/GIS_GEDCO/Gaza_customers/FeatureServer/2",
                    {
                        mode: FeatureLayer.MODE_ONDEMAND,
                        outFields: ["*"],
                        infoTemplate: infoTemplate
                    });
                var LV_NETWORK = new FeatureLayer("https://10.200.10.7:6443/arcgis/rest/services/GIS_GEDCO/Gaza_customers/FeatureServer/3",
                    {
                        mode: FeatureLayer.MODE_ONDEMAND,
                        outFields: ["*"],
                        infoTemplate: infoTemplate
                    });
                var TRANSFORMERS = new FeatureLayer("https://10.200.10.7:6443/arcgis/rest/services/GIS_GEDCO/Gaza_customers/FeatureServer/4",
                    {
                        mode: FeatureLayer.MODE_ONDEMAND,
                        outFields: ["*"],
                        infoTemplate: infoTemplate
                    });
					var REGIONS = new FeatureLayer("https://10.200.10.7:6443/arcgis/rest/services/GIS_GEDCO/Gaza_customers/FeatureServer/5",
                    {
                        mode: FeatureLayer.MODE_ONDEMAND,
                        outFields: ["*"],
                        infoTemplate: infoTemplate
                    });

   // map.addLayers([GAZA_STRIP_NEIGHBORHOODS,ROOMS_NEW,M_V_NETWORK,TRANSFORMERS,MV_OUTDOOR_SWITCHES/*MV_OUTDOOR_SWITCHES,TRANSFORMERS,M_V_NETWORK,ROOMS_NEW,GAZA_STRIP_NEIGHBORHOODS*/]);

                map.addLayer(REGIONS);
                map.addLayer(TRANSFORMERS);
                map.addLayer(LV_NETWORK);
                map.addLayer(CUSTOMERS_CABLE);
                map.addLayer(CUSTOMERS);
				map.addLayer(LV_POLES);



                

                

                

                


            }


        });
		
		   window.onload = function() {
    document.addEventListener("contextmenu", function(e){
      e.preventDefault();
    }, false);
    document.addEventListener("keydown", function(e) {
    //document.onkeydown = function(e) {
      // "I" key
      if (e.ctrlKey && e.shiftKey && e.keyCode == 73) {
        disabledEvent(e);
      }
      // "J" key
      if (e.ctrlKey && e.shiftKey && e.keyCode == 74) {
        disabledEvent(e);
      }
      // "S" key + macOS
      if (e.keyCode == 83 && (navigator.platform.match("Mac") ? e.metaKey : e.ctrlKey)) {
        disabledEvent(e);
      }
      // "U" key
      if (e.ctrlKey && e.keyCode == 85) {
        disabledEvent(e);
      }
      // "F12" key
      if (event.keyCode == 123) {
        disabledEvent(e);
      }
    }, false);
    function disabledEvent(e){
      if (e.stopPropagation){
        e.stopPropagation();
      } else if (window.event){
        window.event.cancelBubble = true;
      }
      e.preventDefault();
      return false;
    }
  };
</script>
</head>

<body oncontextmenu="return false;">
<div id="map"></div>
</body>

</html>






