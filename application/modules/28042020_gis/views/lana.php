<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="initial-scale=1, maximum-scale=1,user-scalable=no">
    <title>Gedco Map Tool Box</title>
	<!--
   <link href="<?= base_url() ?>assets/css/esri/init3.30/claro.css" rel="stylesheet">
   <link href="<?= base_url() ?>assets/css/esri/init3.30/esri.css" rel="stylesheet">
   -->
  
	<link rel="stylesheet" href="https://js.arcgis.com/3.30/dijit/themes/claro/claro.css">
    <link rel="stylesheet" href="https://js.arcgis.com/3.30/esri/css/esri.css">
	
    <style>
      html, body {
        height: 100%;
        width: 100%;
        margin: 0;
      }

      body {
        background-color: #fff;
        overflow: hidden;
        font-family: Helvetica, san-serif;
      }

      #templatePickerPane {
        width: 225px;
        overflow: hidden;
      }

      #panelHeader {
        background-color: #92A661;
        border-bottom: solid 1px #92A860;
        color: #FFF;
        font-size: 18px;
        height: 24px;
        line-height: 22px;
        margin: 0;
        overflow: hidden;
        padding: 10px 10px 10px 10px;
      }

      #map {
        margin-right: 5px;
        padding: 0;
      }

      .esriEditor .templatePicker {
        padding-bottom: 5px;
        padding-top: 5px;
        height: 500px;
        border-radius: 0px 0px 4px 4px;
        border: solid 1px #92A661;
      }

      .dj_ie .infowindow .window .top .right .user .content, .dj_ie .simpleInfoWindow .content {
        position: relative;
      }
	   #search {
         display: block;
         position: absolute;
         z-index: 2;
         top: 20px;
         left: 74px;
      }
    </style>
	<script src="<?= base_url() ?>assets/js/esrijs/init3.30/init.js"></script>

    <!-- <script src="https://js.arcgis.com/3.30/"></script> -->
    <script>
      var map;

      require([
        "esri/config",
        "esri/map",
		"esri/dijit/Search",
        "esri/SnappingManager",
        "esri/dijit/editing/Editor",
        "esri/layers/FeatureLayer",
        "esri/tasks/GeometryService",
        "esri/toolbars/draw",
        "dojo/keys",
        "dojo/parser",
        "dojo/_base/array",
        "dojo/i18n!esri/nls/jsapi",
        "dijit/layout/BorderContainer",
        "dijit/layout/ContentPane",
        "dojo/domReady!"
      ], function (
        esriConfig, Map,Search,SnappingManager, Editor, FeatureLayer, GeometryService,
        Draw, keys, parser, arrayUtils, i18n
        ) {

        parser.parse();

        //snapping is enabled for this sample - change the tooltip to reflect this
        i18n.toolbars.draw.start += "<br/>Press <b>CTRL</b> to enable snapping";
        i18n.toolbars.draw.addPoint += "<br/>Press <b>CTRL</b> to enable snapping";

        //This sample requires a proxy page to handle communications with the ArcGIS Server services. You will need to
        //replace the url below with the location of a proxy on your machine. See the 'Using the proxy page' help topic
        //for details on setting up a proxy page.
        esriConfig.defaults.io.proxyUrl = "/proxy/";

        //This service is for development and testing purposes only. We recommend that you create your own geometry service for use within your applications
        esriConfig.defaults.geometryService = new GeometryService("https://gisweb.gedco.ps:6443/arcgis/rest/services/Utilities/Geometry/GeometryServer");

        map = new Map("map", {
          basemap: "hybrid",
          center: [34.45, 31.48],
          zoom: 13
        });
		 var search = new Search({
        enableButtonMode: true, //this enables the search widget to display as a single button
        enableLabel: true,
        enableInfoWindow: true,
        showInfoWindowOnSelect: false,
		enableSuggestions: true,
        map: map
      }, "search");

        map.on("layers-add-result", initEditing);

        var MV_OUTDOOR_SWITCHES = new FeatureLayer("https://gisweb.gedco.ps:6443/arcgis/rest/services/GIS_GEDCO/s/FeatureServer/0", {
          mode: FeatureLayer.MODE_ONDEMAND,
          outFields: ["*"]
        });
        var TRANSFORMERS = new FeatureLayer("https://gisweb.gedco.ps:6443/arcgis/rest/services/GIS_GEDCO/s/FeatureServer/1",{
          mode: FeatureLayer.MODE_ONDEMAND,
          outFields: ["*"]
        });
        var MV_POLES1 = new FeatureLayer("https://gisweb.gedco.ps:6443/arcgis/rest/services/GIS_GEDCO/s/FeatureServer/2", {
          mode: FeatureLayer.MODE_ONDEMAND,
          outFields: ["*"]
        });
        var M_V_NETWORK = new FeatureLayer("https://gisweb.gedco.ps:6443/arcgis/rest/services/GIS_GEDCO/s/FeatureServer/3", {
          mode: FeatureLayer.MODE_ONDEMAND,
          outFields: ["*"]
        });
		search.on("load", function () {
      var sources = search.get("sources");

        sources.push({
        featureLayer:  MV_OUTDOOR_SWITCHES  ,
		outFields: ["*"],
        searchFields: ["OBJECTID_1"],
        displayField: "OBJECTID_1",
        exactMatch: false,
        outFields: ["OBJECTID_1", "ID", "SWITCH_MATERIAL_ID"],
        name: "MV_OUTDOOR_SWITCHES",
        placeholder: "MV_OUTDOOR_SWITCHES OBJECTID",
        maxResults: 6,
        maxSuggestions: 6,
        enableSuggestions: true,
        minCharacters: 0
      });
sources.push({
        featureLayer: TRANSFORMERS ,
        searchFields: ["TRANSFORMER_NAME_AR","TRANS_MATERIAL_ID"],
        displayField: "TRANSFORMER_NAME_AR",
        exactMatch: false,
        name: " TRANSFORMERS ",
        outFields: ["*"],
        placeholder: " TRANSFORMERS NAME OR MATERIAL ID ",
        maxResults: 6,
        maxSuggestions: 6,
        enableSuggestions: true,
        minCharacters: 0
      });

	  sources.push({
        featureLayer: M_V_NETWORK ,
        searchFields: ["OBJECTID_1"],
        displayField: "OBJECTID_1",
        exactMatch: false,
        name: "M_V_NETWORK",
        outFields: ["*"],
        placeholder: "M_V_NETWORK OBJECTID",
        maxResults: 6,
        maxSuggestions: 6,


        enableSuggestions: true,
        minCharacters: 0
      });
	  
	  
	  
	  	  sources.push({
        featureLayer: MV_POLES1 ,
        searchFields: ["POLE_MATERIAL_ID"],
        displayField: "POLE_MATERIAL_ID",
        exactMatch: false,
        name: "MV_POLES1",
        outFields: ["*"],
        placeholder: "MV_POLES1 POLE_MATERIAL_ID",
        maxResults: 6,
        maxSuggestions: 6,

        

        enableSuggestions: true,
        minCharacters: 0
      });
      //Set the sources above to the search widget
      search.set("sources", sources);
});
      search.startup();
		
          map.addLayers([/*M_V_NETWORK,MV_POLES1,*/TRANSFORMERS/*,MV_OUTDOOR_SWITCHES/*MV_OUTDOOR_SWITCHES,TRANSFORMERS,M_V_NETWORK,ROOMS_NEW,GAZA_STRIP_NEIGHBORHOODS*/]);
	
        map.infoWindow.resize(400, 300);

        function initEditing (event) {
          var featureLayerInfos = arrayUtils.map(event.layers, function (layer) {
            return {
              "featureLayer": layer.layer
            };
          });

          var settings = {
            map: map,
            layerInfos: featureLayerInfos
          };
          var params = {
            settings: settings
          };
          var editorWidget = new Editor(params, 'editorDiv');
          editorWidget.startup();

          //snapping defaults to Cmd key in Mac & Ctrl in PC.
          //specify "snapKey" option only if you want a different key combination for snapping
          map.enableSnapping();
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
  
  <body class="claro" oncontextmenu="return false;">
    <div id="mainWindow" data-dojo-type="dijit/layout/BorderContainer" data-dojo-props="design:'headline',gutters:false" style="width:100%; height:100%;">
      <div id="map" data-dojo-type="dijit/layout/ContentPane" data-dojo-props="region:'center'">
	  <div id="search"></div>
      </div>
      <div data-dojo-type="dijit/layout/ContentPane" id="templatePickerPane" data-dojo-props="region:'left'">
        <div id="panelHeader">
          Gedco Map Tool Box
        </div>
        <div style="padding:10px;" id="editorDiv">
        </div>
      </div>
    </div>
  </body>

</html>
 