<?php
/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 13/03/19
 * Time: 01:39 م
 */
$branch=1;
/*
$branch=0;
if($this->user->branch==3)
$branch='North';
else if($this->user->branch==2)
$branch='Gaza';
 else if($this->user->branch==4)
$branch='Middle';
 else if($this->user->branch==6)
$branch='Khan Younis';
 else if($this->user->branch==7)
$branch='Rafah';
*/
$MODULE_NAME='gis';
$TRANS_TB_NAME="transformer_controller";
$get_transformer_url =base_url("$MODULE_NAME/$TRANS_TB_NAME/get_transformer_info");

?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<meta name="viewport" content="initial-scale=1, maximum-scale=1,user-scalable=no">
<title>Gedco Gis MAP</title>
<!--
  <link href="<?= base_url() ?>assets/css/esri/claro.css" rel="stylesheet">
  <link href="<?= base_url() ?>assets/css/esri/esri.css" rel="stylesheet">
  -->

<link rel="stylesheet" href="https://js.arcgis.com/3.27/dijit/themes/claro/claro.css">
<link rel="stylesheet" href="https://js.arcgis.com/3.27/esri/css/esri.css">

<!--
<link rel="stylesheet" href="https://js.arcgis.com/3.28/esri/themes/calcite/dijit/calcite.css">
<link rel="stylesheet" href="https://js.arcgis.com/3.28/esri/themes/calcite/esri/esri.css">
-->
<style>
    html, body {
        height: 97%;
        width: 98%;
        margin: 1%;
    }
    #map {
        height: 100%;
        width: 100%;
        margin: 0;
        padding: 0;
    }
    #rightPane {
        width: 20%;
    }

    #legendPane {
        border: solid #97DCF2 1px;
    }
    #search {
        display: block;
        position: absolute;
        z-index: 2;
        top: 20px;
        left: 100px;
    }
    #HomeButton {
        display: block;
        position: absolute;
        z-index: 2;
        top: 20px;
        left: 70px;
    }
    .serverLink{
        display: none;
    }
    .resLink{
        display: none;
    }
</style>
<script src="<?= base_url() ?>assets/js/esrijs/init.js"></script>
<script src="<?= base_url() ?>assets/js/esrijs/jquery-1.12.4.min.js"></script>
<!--
  <script src="https://js.arcgis.com/3.27/"></script>
  <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
  -->

<script>


var map;
var myWidget;
var legendDijit;
require([
    "dojo/dom",
    "dojo/keys",
    "esri/sniff",
    "esri/map","esri/layers/ArcGISTiledMapServiceLayer","esri/dijit/Search","esri/dijit/HomeButton","esri/dijit/Scalebar", "esri/layers/FeatureLayer","esri/InfoTemplate", "esri/dijit/Legend","esri/tasks/GeometryService","esri/dijit/Measurement",
    "dojo/_base/array", "dojo/parser",
    "dijit/layout/BorderContainer",
    "dijit/layout/ContentPane",
    "dijit/layout/AccordionContainer",
    "esri/dijit/LayerList",
    "esri/arcgis/utils",

    "dojo/query",

    "esri/IdentityManager",


    "dojo/domReady!"
], function(
    dom,keys,has,
    Map,ArcGISTiledMapServiceLayer,Search,HomeButton,Scalebar,FeatureLayer,InfoTemplate, Legend,GeometryService,Measurement,
    arrayUtils, parser,arcgisUtils,
    query,
    LayerList
    ) {
    parser.parse();
    esriConfig.defaults.io.proxyUrl = "/proxy/";
    esriConfig.defaults.io.alwaysUseProxy = false;
    esriConfig.defaults.geometryService = new GeometryService("https://gisweb.gedco.ps:6443/arcgis/rest/services/Utilities/Geometry/GeometryServer");
    map = new Map("map", {
        basemap:"hybrid",
        center: [34.45, 31.48],
        zoom: 13//14
    });

    var  MV_LBS  = new FeatureLayer("https://gisweb.gedco.ps:6443/arcgis/rest/services/GIS_GEDCO/s/MapServer/0", {
        mode: FeatureLayer.MODE_ONDEMAND,
        outFields: ["*"],
        infoTemplate: new InfoTemplate("MV_LBS", "${*}")
        // infoTemplate: new InfoTemplate("Parcels", "Owner name: ${OBJECTID_1}</br>Parcel ID: ${DISTRICTID}</br>Site address: ${DISTRICTID}")

    });
    // if('<?php echo $this->user->branch; ?>' != '1')
    //MV_OUTDOOR_SWITCHES.setDefinitionExpression("GOVERNORATE_NAME = '<?php echo $branch?>'");
//////////////////////////////////////////////////////////////////////////////////////////////////////////
    var  MV_OUTDOOR_SWITCHES  = new FeatureLayer("https://gisweb.gedco.ps:6443/arcgis/rest/services/GIS_GEDCO/s/MapServer/1", {
        mode: FeatureLayer.MODE_ONDEMAND,
        outFields: ["*"],
        infoTemplate: new InfoTemplate("MV_OUTDOOR_SWITCHES", "${*}")
        // infoTemplate: new InfoTemplate("Parcels", "Owner name: ${OBJECTID_1}</br>Parcel ID: ${DISTRICTID}</br>Site address: ${DISTRICTID}")

    });
    // if('<?php echo $this->user->branch; ?>' != '1')
    //MV_OUTDOOR_SWITCHES.setDefinitionExpression("GOVERNORATE_NAME = '<?php echo $branch?>'");
//////////////////////////////////////////////////////////////////////////////////////////////////////////
    var  TRANSFORMERS  = new FeatureLayer("https://gisweb.gedco.ps:6443/arcgis/rest/services/GIS_GEDCO/s/MapServer/2", {
        mode: FeatureLayer.MODE_ONDEMAND,
        outFields: ["*"]/*,

         infoTemplate: new InfoTemplate("TRANSFORMERS", "<a target='_blank' href=<?php echo $get_transformer_url?>/${OBJECTID}>OBJECTID =${OBJECTID}</a></br>TR_RATING_KVA =${TR_RATING_KVA}"
         +"</br>POLE_MATERIAL_ID = ${POLE_MATERIAL_ID}"
         +"</br>TRANS_MATERIAL_ID = ${TRANS_MATERIAL_ID}"
         +"</br>TRANSFORMER_NAME_AR = ${TRANSFORMER_NAME_AR}"
         +"</br>GOVERNORATE_NAME = ${GOVERNORATE_NAME}"
         +"</br>MAX_LOAD_PERCENTAGE = ${MAX_LOAD_PERCENTAGE}"
         +"</br>HOUSEHOLD_PERCENTAGE = ${HOUSEHOLD_PERCENTAGE}"
         +"</br>COMMERCIAL_PERCENTAGE = ${COMMERCIAL_PERCENTAGE}"
         +"</br>INDUSTRIAL_PERCENTAGE = ${INDUSTRIAL_PERCENTAGE}"
         +"</br>AGRICULTURE_PERCENTAGE = ${AGRICULTURE_PERCENTAGE}"
         +"</br>INTITUTION_PERCENTAGE = ${INTITUTION_PERCENTAGE}"
         +"</br>IMPEDANCE_PERCENTAGE = ${IMPEDANCE_PERCENTAGE}"
         +"</br>X_R_RATIO = ${X_R_RATIO}"
         +"</br>STREET_NAME = ${STREET_NAME}"
         +"</br>DISTRICT_NO = ${DISTRICT_NO}"
         +"</br>DOCUMENTATION_DATE = ${DOCUMENTATION_DATE}"
         +"</br>NOTES = ${NOTES}"
         +"</br>FEEDER_PLAN_8_8 = ${FEEDER_PLAN_8_8}"
         +"</br>FEEDER_PLAN_6_12 = ${FEEDER_PLAN_6_12}"
         +"</br>FEEDER_PLAN_4_16 = ${FEEDER_PLAN_4_16}"
         +"</br>MUNICIPALITY = ${MUNICIPALITY}"
         +"</br>COMPLETED = ${COMPLETED}")
         */,
        infoTemplate: new InfoTemplate("TRANSFORMERS", "${*}")
        // infoTemplate: new InfoTemplate("Parcels", "Owner name: ${OBJECTID_1}</br>Parcel ID: ${DISTRICTID}</br>Site address: ${DISTRICTID}")


        // infoTemplate: new InfoTemplate("Parcels", "Owner name: ${OBJECTID_1}</br>Parcel ID: ${DISTRICTID}</br>Site address: ${DISTRICTID}")

    });

    //if('<?php echo $this->user->branch; ?>' != '1')
    // TRANSFORMERS.setDefinitionExpression("GOVERNORATE_NAME = '<?php echo $branch?>'");
    ///////////////////////////////////////////////////////////////////////////////////////////////////////

    var  MV_POLES1  = new FeatureLayer("https://gisweb.gedco.ps:6443/arcgis/rest/services/GIS_GEDCO/s/MapServer/3", {
        mode: FeatureLayer.MODE_ONDEMAND,
        outFields: ["*"],

        infoTemplate: new InfoTemplate("MV_POLES1", "${*}")
        // infoTemplate: new InfoTemplate("Parcels", "Owner name: ${OBJECTID_1}</br>Parcel ID: ${DISTRICTID}</br>Site address: ${DISTRICTID}")

    });
    // if('<?php echo $this->user->branch; ?>' != '1')
    //MV_POLES1.setDefinitionExpression("GOVERNORATE_NAME = '<?php echo $branch?>'");
    //////////////////////////////////////////////////////////////////
    var  M_V_NETWORK   = new FeatureLayer("https://gisweb.gedco.ps:6443/arcgis/rest/services/GIS_GEDCO/s/MapServer/4", {
        mode: FeatureLayer.MODE_ONDEMAND,
        outFields: ["*"],
        infoTemplate: new InfoTemplate("M_V_NETWORK", "${*}")
        // infoTemplate: new InfoTemplate("Parcels", "Owner name: ${OBJECTID_1}</br>Parcel ID: ${DISTRICTID}</br>Site address: ${DISTRICTID}")

    });
    //if('<?php echo $this->user->branch; ?>' != '1')
    //M_V_NETWORK.setDefinitionExpression("GOVERNORAT = '<?php echo $branch?>'");
    //////////////////////////////////////////////////////////////////
    var  ROOMS_NEW   = new FeatureLayer("https://gisweb.gedco.ps:6443/arcgis/rest/services/GIS_GEDCO/s/MapServer/5", {
        mode: FeatureLayer.MODE_ONDEMAND,
        outFields: ["*"],
        infoTemplate: new InfoTemplate("ROOMS_NEW", "${*}")
        // infoTemplate: new InfoTemplate("Parcels", "Owner name: ${OBJECTID_1}</br>Parcel ID: ${DISTRICTID}</br>Site address: ${DISTRICTID}")

    });
    //if('<?php echo $this->user->branch; ?>' != '1')
    //M_V_NETWORK.setDefinitionExpression("GOVERNORAT = '<?php echo $branch?>'");
    //////////////////////////////////////////////////////////////////////////////////////////////////
    // the var 'event' is available, and is the normalized object
    map.addLayers([ROOMS_NEW,M_V_NETWORK,MV_POLES1,TRANSFORMERS,MV_OUTDOOR_SWITCHES,MV_LBS/*MV_OUTDOOR_SWITCHES,TRANSFORMERS,M_V_NETWORK,ROOMS_NEW,GAZA_STRIP_NEIGHBORHOODS*/]);
    // require(["dijit/form/CheckBox","dojo/on",'dojo/query',"dojo/domReady!"], function(CheckBox,on,query){
//alert();
//ondijitclick:_onClick=function() {alert(1);}
    //on('#c', "click", function(e){
    //alert(1);
    // handle the event
    // });
//})
});




//https://stackoverflow.com/questions/24595675/dijit-form-button-onclick-event

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

<!-- <body class="claro calcite"> -->
<body class="claro" oncontextmenu="return false;">
<!-- <div id="HomeButton"></div> -->

<div id="content"
     data-dojo-type="dijit/layout/BorderContainer"
     data-dojo-props="design:'headline', gutters:true"
     style="width: 100%; height: 100%; margin: 0;">







    <div id="map"
         data-dojo-type="dijit/layout/ContentPane"
         data-dojo-props="region:'center'"
         style="overflow:hidden;">




    </div>

    <!--<div id="HomeButton"></div>-->

</div>


</body>

</html>
