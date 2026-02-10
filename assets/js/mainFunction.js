var url = "http://gedco.ps:5280/bosh";
var wsURL = "ws://gedco.ps:5280/ws";
var transports = ['websocket', 'bosh'];

var jid = "gps@gedco.ps";
var password = 'gps$2016#';


var client;

function initConntection() {

    transports = ['websocket'];


    client = XMPP.createClient({
        jid: jid,
        password: password,
        wsURL: wsURL,
        boshURL: url,
        transports: transports
    });

    client.on('*', function (name, data) {


        if (name == 'raw:incoming' && data.indexOf('message') >= 0) {

            var e = $(data);

            var message = {from: e.attr("from"), body: e.text().replace('Offline Storage', '')};

            try {
                var obj = jQuery.parseJSON(message.body);
                console.log(message.body);
                moveMarker(obj.data);
            } catch (e) {

            }


        }
    });


    client.on('session:started', function () {
        client.enableCarbons();
        client.getRoster(function (err, resp) {
            client.updateCaps();
            client.sendPresence({
                caps: client.disco.caps
            });
        });
    });


    client.connect();


}


initConntection();

function getMarkerByID(id) {
    var marker = null;


    map.eachLayer(function(l){
        if(l instanceof  L.Marker) {

            if (l.options.id == id) {
                marker = l;


            } else marker = null;
        }
    })




    return marker;


}

function moveMarker(msg) {

    console.log(msg);

    var directionsService = new google.maps.DirectionsService();
    var gpsData = msg.split('|');

    var marker = getMarkerByID(gpsData[2]);

    if (marker != null) {
//delayed so you can see it move
        var prevPosn = marker.getLatLng();

        setTimeout(function () {


            var newLatLng = new L.LatLng(gpsData[0],  gpsData[1]);
            marker.setLatLng(newLatLng);


            var LeafIcon = L.Icon.extend({
                options: {
                    shadowUrl: '',

                }
            });

            var icon = new LeafIcon({iconUrl: '/gfc/assets/js/dot-large-blue.png'});

            marker.setIcon(icon);
            var path = [new L.LatLng(gpsData[0], gpsData[1]), new L.LatLng(prevPosn.lat, prevPosn.lng)];



            var polyline = new L.Polyline(path, {
                color: '#0e8dce',
                weight:3,
                opacity: 1,
                smoothFactor: 1
            });
            polyline.addTo(map);


        }, 100);



    } else {
        if (typeof markerNotFound == 'function') {

            markerNotFound(gpsData);
        }


    }

}

var storedLocations = [];

function restTrackingData() {

    clearMap();

    storedLocations = [];

    var marker = getMarkerByID('#track');
    if(marker) map.removeLayer(marker);
}


function clearMap() {
    map.eachLayer(function(l){
        if(l._path != undefined ) {

            map.removeLayer(l);
        }
    });



}


function startHRout(data, driver_id, itemLocation = null, indexOfLocation = 0) {


    var msg = '';

    if (itemLocation == null) {

        storedLocations = [];

        for (var ix = 0; ix < data.length; ix++) {

            var item = data[ix];
            msg = item.LONGITUDE + '|' + item.LATITUDE + '|' +  item.USER_NAME;

            if (ix == 0) {
                moveMarker(msg);
            }

            storedLocations.push(msg);
        }

    } else {

        msg = itemLocation;

    }


    if (msg == '' || msg == null) return;


    var gpsData = msg.split('|');
    var marker = getMarkerByID(gpsData[2]);

    if(marker == null) marker =  markerNotFound(gpsData);
    var prevPosn = marker.getLatLng();


    var newLatLng = new L.LatLng(gpsData[0],  gpsData[1]);
    marker.setLatLng(newLatLng);
    marker.bindPopup(gpsData[2]).openPopup();


    var LeafIcon = L.Icon.extend({
        options: {
            shadowUrl: '',

        }
    });

    var icon = new LeafIcon({iconUrl: '/gfc/assets/js/dot-large-red.png'});

    marker.setIcon(icon);
    var path = [new L.LatLng(gpsData[0], gpsData[1]), new L.LatLng(prevPosn.lat, prevPosn.lng)];



    var polyline = new L.Polyline(path, {
        color: '#0e8dce',
        weight:3,
        opacity: 1,
        smoothFactor: 1
    });
    polyline.addTo(map);

    setTimeout(function () {

        indexOfLocation = indexOfLocation + 1;
        console.log(indexOfLocation);
        startHRout([], null, storedLocations[indexOfLocation], indexOfLocation)

    }, 100);


}


function startHRout_live(data, itemLocation = null, indexOfLocation = 0) {

    var msg = '';

    if (itemLocation == null) {

        storedLocations = [];

        for (var ix = 0; ix < data.length; ix++) {

            var item = data[ix];
            msg = item.LONGITUDE + '|' + item.LATITUDE  + '|' +  item.USER_NAME;

            if (ix == 0) {
                moveMarker(msg);
            }

            storedLocations.push(msg);
        }

    } else {

        msg = itemLocation;

    }


    if (msg == '' || msg == null) return;

    var gpsData = msg.split('|');
    var marker = getMarkerByID(gpsData[2]);

    if (marker == null) marker = markerNotFound(gpsData);

    marker.bindPopup(gpsData[2]).openPopup();

    var LeafIcon = L.Icon.extend({
        options: {
            shadowUrl: '',

        }
    });

    var icon = new LeafIcon({iconUrl: '/gfc/assets/js/dot-large-blue.png'});

    marker.setIcon(icon);



}



function markerNotFound(gpsData) {
    console.log(gpsData);


    var LeafIcon = L.Icon.extend({
        options: {
            shadowUrl: '',

        }
    });

    var icon = new LeafIcon({iconUrl: '/gfc/assets/js/dot-large-blue.png'});


    return L.marker([gpsData[0], gpsData[1]], {icon: icon , id: gpsData[2]})
        .addTo(map)
        .openPopup();


}