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
  <link href="<?= base_url() ?>assets/css/esri/main.css" rel="stylesheet">
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
	 /*
	 var customBasemap = new ArcGISTiledMapServiceLayer(
    "https://gisweb.gedco.ps:6443/arcgis/rest/services/gaza/GAZA_2015/ImageServer");
    map.addLayer(customBasemap);*/
	   /* var scalebar = new Scalebar({
          map: map,
          // "dual" displays both miles and kilometers
          // "english" is the default, which displays miles
          // use "metric" for kilometers
          scalebarUnit: "dual"
        });
		
		*/
	  var home = new HomeButton({
        map: map
      }, "HomeButton");
      home.startup();
	  
 var search = new Search({
        enableButtonMode: true, //this enables the search widget to display as a single button
        enableLabel:true,
        enableInfoWindow:true,
        showInfoWindowOnSelect: true,
		enableSuggestions:true,
        map: map
      }, "search");
        var  MV_LBS  = new FeatureLayer("https://gisweb.gedco.ps:6443/arcgis/rest/services/GIS_GEDCO/s/FeatureServer/0", {//new FeatureLayer("https://gisweb.gedco.ps:6443/arcgis/rest/services/GIS_GEDCO/s/MapServer/0", {
            mode: FeatureLayer.MODE_ONDEMAND,
            outFields: ["*"],
            infoTemplate: new InfoTemplate("MV_LBS", "${*}")
            // infoTemplate: new InfoTemplate("Parcels", "Owner name: ${OBJECTID_1}</br>Parcel ID: ${DISTRICTID}</br>Site address: ${DISTRICTID}")

        });
        // if('<?php echo $this->user->branch; ?>' != '1')
        //MV_OUTDOOR_SWITCHES.setDefinitionExpression("GOVERNORATE_NAME = '<?php echo $branch?>'");
//////////////////////////////////////////////////////////////////////////////////////////////////////////
      var  MV_OUTDOOR_SWITCHES  = new FeatureLayer("https://gisweb.gedco.ps:6443/arcgis/rest/services/GIS_GEDCO/s/FeatureServer/1", {//new FeatureLayer("https://gisweb.gedco.ps:6443/arcgis/rest/services/GIS_GEDCO/s/MapServer/1", {
        mode: FeatureLayer.MODE_ONDEMAND,
        outFields: ["*"],
		  infoTemplate: new InfoTemplate("MV_OUTDOOR_SWITCHES", "${*}")
		 // infoTemplate: new InfoTemplate("Parcels", "Owner name: ${OBJECTID_1}</br>Parcel ID: ${DISTRICTID}</br>Site address: ${DISTRICTID}")
          
      });
	  // if('<?php echo $this->user->branch; ?>' != '1')
	  //MV_OUTDOOR_SWITCHES.setDefinitionExpression("GOVERNORATE_NAME = '<?php echo $branch?>'");
//////////////////////////////////////////////////////////////////////////////////////////////////////////	  
      var  TRANSFORMERS  = new FeatureLayer("https://gisweb.gedco.ps:6443/arcgis/rest/services/GIS_GEDCO/s/FeatureServer/2", {//new FeatureLayer("https://gisweb.gedco.ps:6443/arcgis/rest/services/GIS_GEDCO/s/MapServer/2", {
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
	  
	  var  MV_POLES1  = new FeatureLayer("https://gisweb.gedco.ps:6443/arcgis/rest/services/GIS_GEDCO/s/FeatureServer/3", {//new FeatureLayer("https://gisweb.gedco.ps:6443/arcgis/rest/services/GIS_GEDCO/s/MapServer/3", {
        mode: FeatureLayer.MODE_ONDEMAND,
        outFields: ["*"],
		
		  infoTemplate: new InfoTemplate("MV_POLES1", "${*}")
		 // infoTemplate: new InfoTemplate("Parcels", "Owner name: ${OBJECTID_1}</br>Parcel ID: ${DISTRICTID}</br>Site address: ${DISTRICTID}")
          
      });
	  // if('<?php echo $this->user->branch; ?>' != '1')
	  //MV_POLES1.setDefinitionExpression("GOVERNORATE_NAME = '<?php echo $branch?>'");
	  //////////////////////////////////////////////////////////////////
	  var  M_V_NETWORK   = new FeatureLayer("https://gisweb.gedco.ps:6443/arcgis/rest/services/GIS_GEDCO/s/FeatureServer/4", {//new FeatureLayer("https://gisweb.gedco.ps:6443/arcgis/rest/services/GIS_GEDCO/s/MapServer/4", {
        mode: FeatureLayer.MODE_ONDEMAND,
        outFields: ["*"],
		 infoTemplate: new InfoTemplate("M_V_NETWORK", "${*}")
		 // infoTemplate: new InfoTemplate("Parcels", "Owner name: ${OBJECTID_1}</br>Parcel ID: ${DISTRICTID}</br>Site address: ${DISTRICTID}")
          
      });
	   //if('<?php echo $this->user->branch; ?>' != '1')
	   //M_V_NETWORK.setDefinitionExpression("GOVERNORAT = '<?php echo $branch?>'");
	   //////////////////////////////////////////////////////////////////
	  var  ROOMS_NEW   = new FeatureLayer("https://gisweb.gedco.ps:6443/arcgis/rest/services/GIS_GEDCO/s/FeatureServer/5", {//new FeatureLayer("https://gisweb.gedco.ps:6443/arcgis/rest/services/GIS_GEDCO/s/MapServer/5", {
        mode: FeatureLayer.MODE_ONDEMAND,
        outFields: ["*"],
		 infoTemplate: new InfoTemplate("ROOMS_NEW", "${*}")
		 // infoTemplate: new InfoTemplate("Parcels", "Owner name: ${OBJECTID_1}</br>Parcel ID: ${DISTRICTID}</br>Site address: ${DISTRICTID}")
          
      });
	   //if('<?php echo $this->user->branch; ?>' != '1')
	   //M_V_NETWORK.setDefinitionExpression("GOVERNORAT = '<?php echo $branch?>'");
	  //////////////////////////////////////////////////////////////////////////////////////////////////
      search.on("load", function () {
      var sources = search.get("sources");

        sources.push({
        featureLayer:  MV_OUTDOOR_SWITCHES  ,
		outFields: ["*"],
        searchFields: ["OBJECTID_1"],
        displayField: "OBJECTID_1",
        exactMatch: false,
        //outFields: ["OBJECTID_1", "ID", "SWITCH_MATERIAL_ID"],
        name: "MV_OUTDOOR_SWITCHES",
        placeholder: "MV_OUTDOOR_SWITCHES OBJECTID",
		//enableSuggestions: true,
        maxResults: 10000,
        maxSuggestions: 10000,

        //Create an InfoTemplate and include three fields
        infoTemplate: new InfoTemplate(" MV_OUTDOOR_SWITCHES  Search",
          "${*}"
        ),
        
		  minCharacters: 0
      });
sources.push({
        featureLayer: TRANSFORMERS ,
        searchFields: ["TRANSFORMER_NAME_AR","TRANS_MATERIAL_ID"],
        displayField: "TRANSFORMER_NAME_AR",
        exactMatch: false,
        name: " TRANSFORMERS",
        outFields: ["*"],
        placeholder: "TRANSFORMERS NAME OR MATERIAL ID ",
		// enableSuggestions: true,
        maxResults: 10000,
        maxSuggestions: 10000,
		  /*infoTemplate: new InfoTemplate("TRANSFORMERS", "<a target='_blank' href=<?php echo $get_transformer_url?>/${OBJECTID}>OBJECTID =${OBJECTID}</a></br>TR_RATING_KVA =${TR_RATING_KVA}"
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
		 // infoTemplate: new InfoTemplate("Parcels", "Owner name: ${OBJECTID_1}</br>Parcel ID: ${DISTRICTID}</br>Site address: ${DISTRICTID}")
         

        //Create an InfoTemplate
*/
        infoTemplate: new InfoTemplate("TRANSFORMERS  information",
          "${*}"
        ),

       
        minCharacters: 0
      });

	  sources.push({
        featureLayer: M_V_NETWORK ,
        searchFields: ["OBJECTID"],
        displayField: "OBJECTID",
        exactMatch: false,
        name: "M_V_NETWORK",
        outFields: ["*"],
        placeholder: "M_V_NETWORK OBJECTID",
		//enableSuggestions: true,
        maxResults: 10000,
        maxSuggestions: 10000,


        //Create an InfoTemplate

        infoTemplate: new InfoTemplate("M_V_NETWORK  information",
          "${*}"
        ),

       
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
        //enableSuggestions: true,
        maxResults: 10000,
        maxSuggestions: 10000,

        //Create an InfoTemplate

        infoTemplate: new InfoTemplate("MV_POLES1  information",
          "${*}"
        ),

       
        minCharacters: 0
      });
	  ////////////////////
	  	  sources.push({
        featureLayer: ROOMS_NEW ,
        searchFields: ["OBJECTID"],
        displayField: "OBJECTID",
        exactMatch: false,
        name: "ROOMS_NEW",
        outFields: ["*"],
        placeholder: "ROOMS_NEW OBJECTID",
        //enableSuggestions: true,
        maxResults: 10000,
        maxSuggestions: 10000,

        //Create an InfoTemplate

        infoTemplate: new InfoTemplate("ROOMS_NEW  information",
          "${*}"
        ),

       
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
	//alert(2);
}
           myWidget = new LayerList({
           map:map,
           layers:GedcoLayers
        }, "GedcoLayersDiv");
		
        myWidget.startup();
		}
		
      var snapManager = map.enableSnapping({
          snapKey: has("mac") ? keys.META : keys.CTRL
        });
	
	var measurement = new Measurement({
          map: map
        }, dom.byId("measurementDiv"));
        measurement.startup();
		//measurement.hideTool("location");
    //measurement.hideTool("distance");
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
	 if($(this).prop("checked") == true){
	 currentLayer.show();
	 //console.log('show'+currentLayer);
	 }
	 else
	 {
	 currentLayer.hide();
	//  console.log('hide'+currentLayer);
	 }
	break;
	}
	
    };
	

/* handle the event */ });


//console.log(event.name);

/* handle event */ });

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
	
        <div data-dojo-type="dijit/layout/ContentPane"
           data-dojo-props="title:'Gedco Measurement'" >
        <div id="measurementDiv"></div>
		<span style="font-size:smaller;padding:5px 5px;">Press <b>CTRL</b> to enable snapping.</span>
      </div>
	
    </div>
	

  </div>
  
    

 

  <div id="map" 
       data-dojo-type="dijit/layout/ContentPane"
       data-dojo-props="region:'center'"
       style="overflow:hidden;">
	  
<div id="search"></div> 
 
  
 </div>
 
 <!--<div id="HomeButton"></div>-->
 
 </div>


</body>

</html>
