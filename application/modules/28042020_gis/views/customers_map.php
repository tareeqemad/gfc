<?php
/**
 * Created by PhpStorm.
 * User: lanakh
 * Date: 19/09/19
 * Time: 11:03 ص
 */

?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<meta name="viewport" content="initial-scale=1, maximum-scale=1,user-scalable=no">
<title>Customers Gedco Gis Map</title>
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
        left: 74px;
    }

</style>

<script src="https://js.arcgis.com/3.27/"></script>
<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>

<script>
var map;
var myWidget;
var legendDijit;
require([
    "esri/map","esri/dijit/Search", "esri/layers/FeatureLayer","esri/InfoTemplate", "esri/dijit/Legend",
    "dojo/_base/array", "dojo/parser",
    "dijit/layout/BorderContainer", "dijit/layout/ContentPane",
    "dijit/layout/AccordionContainer",    "esri/dijit/LayerList",    "esri/arcgis/utils",
    "dojo/query",




    "dojo/domReady!"
], function(
    Map,Search,FeatureLayer,InfoTemplate, Legend,
    arrayUtils, parser,arcgisUtils,
    query,
    LayerList
    ) {
    parser.parse();

    map = new Map("map", {
        basemap:"hybrid",
        center: [34.45, 31.48],
        zoom: 13
    });

    var search = new Search({
        enableButtonMode: true, //this enables the search widget to display as a single button
        enableLabel: false,
        enableInfoWindow: true,
        showInfoWindowOnSelect: false,
        map: map
    }, "search");
    var  LV_POLES  = new FeatureLayer("https://10.200.10.7:6443/arcgis/rest/services/GIS_GEDCO/Gaza_customers/FeatureServer/0", {
        mode: FeatureLayer.MODE_ONDEMAND,
        outFields: ["*"],
        infoTemplate: new InfoTemplate("LV_POLES", "${*}")
        // infoTemplate: new InfoTemplate("Parcels", "Owner name: ${OBJECTID_1}</br>Parcel ID: ${DISTRICTID}</br>Site address: ${DISTRICTID}")

    });
    var  CUSTOMERS   = new FeatureLayer("https://10.200.10.7:6443/arcgis/rest/services/GIS_GEDCO/Gaza_customers/FeatureServer/1", {
        mode: FeatureLayer.MODE_ONDEMAND,
        outFields: ["*"],

        infoTemplate: new InfoTemplate("CUSTOMERS", "${*}")
        // infoTemplate: new InfoTemplate("Parcels", "Owner name: ${OBJECTID_1}</br>Parcel ID: ${DISTRICTID}</br>Site address: ${DISTRICTID}")

    });

    var  CUSTOMERS_CABLE   = new FeatureLayer("https://10.200.10.7:6443/arcgis/rest/services/GIS_GEDCO/Gaza_customers/FeatureServer/2", {
        mode: FeatureLayer.MODE_ONDEMAND,
        outFields: ["*"],
        infoTemplate: new InfoTemplate("CUSTOMERS_CABLE", "${*}")
        // infoTemplate: new InfoTemplate("Parcels", "Owner name: ${OBJECTID_1}</br>Parcel ID: ${DISTRICTID}</br>Site address: ${DISTRICTID}")

    });
    var  LV_NETWORK   = new FeatureLayer("https://10.200.10.7:6443/arcgis/rest/services/GIS_GEDCO/Gaza_customers/FeatureServer/3", {
        mode: FeatureLayer.MODE_ONDEMAND,
        outFields: ["*"],

        infoTemplate: new InfoTemplate("LV_NETWORK", "${*}")
        // infoTemplate: new InfoTemplate("Parcels", "Owner name: ${OBJECTID_1}</br>Parcel ID: ${DISTRICTID}</br>Site address: ${DISTRICTID}")

    });

    var  TRANSFORMERS  = new FeatureLayer("https://10.200.10.7:6443/arcgis/rest/services/GIS_GEDCO/Gaza_customers/FeatureServer/4", {
        mode: FeatureLayer.MODE_ONDEMAND,
        outFields: ["*"],

        infoTemplate: new InfoTemplate("TRANSFORMERS", "${*}")
        // infoTemplate: new InfoTemplate("Parcels", "Owner name: ${OBJECTID_1}</br>Parcel ID: ${DISTRICTID}</br>Site address: ${DISTRICTID}")

    });
    var  Regions  = new FeatureLayer("https://10.200.10.7:6443/arcgis/rest/services/GIS_GEDCO/Gaza_customers/FeatureServer/5", {
        mode: FeatureLayer.MODE_ONDEMAND,
        outFields: ["*"],

        infoTemplate: new InfoTemplate("Regions", "${*}")
        // infoTemplate: new InfoTemplate("Parcels", "Owner name: ${OBJECTID_1}</br>Parcel ID: ${DISTRICTID}</br>Site address: ${DISTRICTID}")

    });
    search.on("load", function () {
        var sources = search.get("sources");

        sources.push({
            featureLayer:  LV_POLES  ,
            outFields: ["*"],
            searchFields: ["OBJECTID"],
            displayField: "OBJECTID",
            exactMatch: false,
            outFields: ["*"],
            name: "LV_POLES",
            placeholder: "LV_POLES OBJECTID",
            maxResults: 6,
            maxSuggestions: 6,

            //Create an InfoTemplate and include three fields
            infoTemplate: new InfoTemplate("LV_POLES  Search",
                "${*}"
            ),
            enableSuggestions: true,
            minCharacters: 0
        });
        sources.push({
            featureLayer: CUSTOMERS ,
            searchFields: ["OBJECTID "],
            displayField: "OBJECTID ",
            exactMatch: false,
            name: " CUSTOMERS ",
            outFields: ["*"],
            placeholder: "CUSTOMERS OBJECTID ",
            maxResults: 6,
            maxSuggestions: 6,

            //Create an InfoTemplate

            infoTemplate: new InfoTemplate("CUSTOMERS  information",
                "${*}"
            ),

            enableSuggestions: true,
            minCharacters: 0
        });

        sources.push({
            featureLayer: CUSTOMERS_CABLE ,
            searchFields: ["OBJECTID"],
            displayField: "OBJECTID",
            exactMatch: false,
            name: "CUSTOMERS_CABLE",
            outFields: ["*"],
            placeholder: "CUSTOMERS_CABLE OBJECTID",
            maxResults: 6,
            maxSuggestions: 6,

            //Create an InfoTemplate

            infoTemplate: new InfoTemplate(" CUSTOMERS_CABLE  information",
                "${*}"
            ),

            enableSuggestions: true,
            minCharacters: 0
        });

        sources.push({
            featureLayer: LV_NETWORK ,
            searchFields: ["OBJECTID"],
            displayField: "OBJECTID",
            exactMatch: false,
            name: "LV_NETWORK",
            outFields: ["*"],
            placeholder: "LV_NETWORK OBJECTID",
            maxResults: 6,
            maxSuggestions: 6,

            //Create an InfoTemplate

            infoTemplate: new InfoTemplate("LV_NETWORK  information",
                "${*}"
            ),

            enableSuggestions: true,
            minCharacters: 0
        });

        sources.push({
            featureLayer: TRANSFORMERS  ,
            searchFields: ["OBJECTID"],
            displayField: "OBJECTID",
            exactMatch: false,
            name: "TRANSFORMERS ",
            outFields: ["*"],
            placeholder: "TRANSFORMERS  OBJECTID",
            maxResults: 6,
            maxSuggestions: 6,

            //Create an InfoTemplate

            infoTemplate: new InfoTemplate("TRANSFORMERS   information",
                "${*}"
            ),

            enableSuggestions: true,
            minCharacters: 0
        });

        sources.push({
            featureLayer: Regions  ,
            searchFields: ["OBJECTID_1"],
            displayField: "OBJECTID_1",
            exactMatch: false,
            name: "Regions ",
            outFields: ["*"],
            placeholder: "Regions  OBJECTID",
            maxResults: 6,
            maxSuggestions: 6,

            //Create an InfoTemplate

            infoTemplate: new InfoTemplate("Regions   information",
                "${*}"
            ),

            enableSuggestions: true,
            minCharacters: 0
        });
        //Set the sources above to the search widget
        search.set("sources", sources);
    });
    search.startup();
    //add the legend
    map.on("layers-add-result", function (evt) {

        var layerInfo = arrayUtils.map(evt.layers, function (layer, index) {
            /*var $log = $( "#legendDiv" )
             var str = '<input type="checkbox" name="layers[]" value="'+layer.layer.name+'" checked>'+layer.layer.name+'<br>';
             var html = $.parseHTML( str );
             $log.append( html);
             console.log(layer.layer);*/
            return {layer:layer.layer, title:layer.layer.name};
        });


        if (layerInfo.length > 0) {
            //	 legendDijit = dijit.byId("legendDiv");
            //	alert(legendDijit);
            /*if (legendDijit ) {
             legendDijit.destroy();
             legendDijit = null;
             alert(1);
             }*/
            legendDijit = new Legend({
                map: map,
                layerInfos: layerInfo
            }, "legendDiv");

            legendDijit.startup();

        }
        var GedcoLayers = arrayUtils.map(evt.layers, function (layer,index) {
            var $log = $( "#GedcoLayersDiv" )
            var str = '<input type="checkbox" class="CheckLayer" name="layers[]" value="'+layer.layer.name+'" data-dojo-type="dijit/form/CheckBox"  checked>'+layer.layer.name+'<br>';
            var html = $.parseHTML( str );
            $log.append( html);

            //return { title:html};


            //console.log(layer);
            return { featureLayer: layer };
        });
        if (GedcoLayers.length > 0) {
            myWidget = dijit.byId("GedcoLayersDiv");

            if (myWidget ) {
                myWidget.destroy();
                myWidget = null;
                alert(2);
            }
            myWidget = new LayerList({
                map:map,
                layers:GedcoLayers
            }, "GedcoLayersDiv");

            myWidget.startup();
        }

    });
    dojo.connect(map, 'onLoad', function (results) {

    });
    dojo.connect("onclick", function(event){
        dojo.query("input[name='layers[]']").onclick(function(e){

            var layers = map.getLayersVisibleAtScale(map.getScale());
            for (i = 0;  i < layers.length; i++) {
                FindLayer = layers[i];
                if($(this).val()== FindLayer.name)
                {
                    currentLayer=FindLayer;

                    break;
                }

            };


            if($(this).prop("checked") == true){

                // alert("Checkbox is checked.");
                var jsLang = $(this).val();
                //  console.log( MV_OUTDOOR_SWITCHES );

                switch (jsLang) {
                    case '.LV_POLES':
                    {
                        currentLayer.show();

                        //alert(currentLayer.name);
                        //map.addLayers([ MV_OUTDOOR_SWITCHES ]);

                    }
                        break;
                    case 'CUSTOMERS':
                    {
                        currentLayer.show();

                        //alert(currentLayer.name);
                        //map.addLayers([ TRANSFORMERS ]);
                    }
                        break;
                    case 'CUSTOMERS_CABLE':
                        currentLayer.show();
                        break;
                    case 'TRANSFORMERS':
                        currentLayer.show();
                        break;
                     case 'LV_NETWORK':
                        currentLayer.show();
                        break;
						  case '.احياء':
                        currentLayer.show();
                        break;
                    default:
                        alert('Nobody sucks!');
                }
//outputs "prototype sucks! mootools sucks! dojo sucks!"




//map.addLayers($(this).val());
            }

            else if($(this).prop("checked") == false){
                var jsLang = $(this).val();
                switch (jsLang) {
                    case '.LV_POLES':
                    {
                        currentLayer.hide();
                        //alert(currentLayer.name);
                        //map.removeLayer( MV_OUTDOOR_SWITCHES );
                    }
                        break;
                    case 'CUSTOMERS':
                    {
                        currentLayer.hide();
//alert(currentLayer.name);
                        //map.removeLayer( TRANSFORMERS );
                    }
                        break;
                    case 'CUSTOMERS_CABLE':
                        currentLayer.hide();
                        break;
                    case 'TRANSFORMERS':
                        currentLayer.hide();
                        break;
                    case 'LV_NETWORK':
                        currentLayer.hide();
                        break;
						 case '.احياء':
                        currentLayer.hide();
                        break;
                    default:
                        alert('Nobody sucks!');
                }
//map.removeLayer($(this).val());
                //   alert("Checkbox is unchecked.");

            }/* handle the event */ });


//console.log(event.name);

        /* handle event */ });

    // the var 'event' is available, and is the normalized object
	
    map.addLayers([Regions,TRANSFORMERS,LV_NETWORK,CUSTOMERS_CABLE,CUSTOMERS,LV_POLES/*,ROOMS_NEW,M_V_NETWORK,TRANSFORMERS,MV_OUTDOOR_SWITCHES*//*MV_OUTDOOR_SWITCHES,TRANSFORMERS,M_V_NETWORK,ROOMS_NEW,GAZA_STRIP_NEIGHBORHOODS*/]);
    // require(["dijit/form/CheckBox","dojo/on",'dojo/query',"dojo/domReady!"], function(CheckBox,on,query){
//alert();
//ondijitclick:_onClick=function() {alert(1);}
    //on('#c', "click", function(e){
    //alert(1);
    // handle the event
    // });
//})
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

//https://stackoverflow.com/questions/24595675/dijit-form-button-onclick-event


</script>
</head>

<!-- <body class="claro calcite"> -->
<body class="claro" oncontextmenu="return false;">
<div id="content"
     data-dojo-type="dijit/layout/BorderContainer"
     data-dojo-props="design:'headline', gutters:true"
     style="width: 100%; height: 100%; margin: 0;">

    <div id="rightPane"
         data-dojo-type="dijit/layout/ContentPane"
         data-dojo-props="region:'right'">

        <div data-dojo-type="dijit/layout/AccordionContainer">
            <div data-dojo-type="dijit/layout/ContentPane" id="legendPane"
                 data-dojo-props="title:'Legend', selected:true">
                <div id="legendDiv"></div>
            </div>
            <div data-dojo-type="dijit/layout/ContentPane"
                 data-dojo-props="title:'Gedco Layers'">
                <div id="GedcoLayersDiv"></div>
            </div>
            <!--
              <div data-dojo-type="dijit/layout/ContentPane" id="SeatchPane"
                data-dojo-props="title:'Search'">
             <div id="search10"></div>
           </div>
           -->
        </div>

    </div>





    <div id="map"
         data-dojo-type="dijit/layout/ContentPane"
         data-dojo-props="region:'center'"
         style="overflow:hidden;">
        <div id="search">
        </div>
    </div>

</div>


</body>

</html>
