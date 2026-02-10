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
<title>Gedco Gis MAP</title>

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
                var MV_OUTDOOR_SWITCHES = new FeatureLayer("https://10.200.10.7:6443/arcgis/rest/services/GIS_GEDCO/s/FeatureServer/0",
                    {
                        mode: FeatureLayer.MODE_ONDEMAND,
                        outFields: ["*"],
                        infoTemplate: infoTemplate
                    });
                var TRANSFORMERS = new FeatureLayer("https://10.200.10.7:6443/arcgis/rest/services/GIS_GEDCO/s/FeatureServer/1",
                    {
                        mode: FeatureLayer.MODE_ONDEMAND,
                        outFields: ["*"],
                        infoTemplate: infoTemplate
                    });
					var MV_POLES1 = new FeatureLayer("https://10.200.10.7:6443/arcgis/rest/services/GIS_GEDCO/s/FeatureServer/2",
                    {
                        mode: FeatureLayer.MODE_ONDEMAND,
                        outFields: ["*"],
                        infoTemplate: infoTemplate
                    });
                var M_V_NETWORK = new FeatureLayer("https://10.200.10.7:6443/arcgis/rest/services/GIS_GEDCO/s/FeatureServer/3",
                    {
                        mode: FeatureLayer.MODE_ONDEMAND,
                        outFields: ["*"],
                        infoTemplate: infoTemplate
                    });
                var ROOMS_NEW = new FeatureLayer("https://10.200.10.7:6443/arcgis/rest/services/GIS_GEDCO/s/FeatureServer/4",
                    {
                        mode: FeatureLayer.MODE_ONDEMAND,
                        outFields: ["*"],
                        infoTemplate: infoTemplate
                    });
                

   // map.addLayers([GAZA_STRIP_NEIGHBORHOODS,ROOMS_NEW,M_V_NETWORK,TRANSFORMERS,MV_OUTDOOR_SWITCHES/*MV_OUTDOOR_SWITCHES,TRANSFORMERS,M_V_NETWORK,ROOMS_NEW,GAZA_STRIP_NEIGHBORHOODS*/]);

                map.addLayer(ROOMS_NEW);
                map.addLayer(M_V_NETWORK);
                map.addLayer(MV_POLES1);
                map.addLayer(TRANSFORMERS);
                map.addLayer(MV_OUTDOOR_SWITCHES);



                

                

                

                


            }


        });
</script>
</head>

<body>
<div id="map"></div>
</body>

</html>






