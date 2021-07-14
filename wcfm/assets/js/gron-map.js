
function GRONMap( latLng ) {

    this.map;
    this.bounds;

    this.lat = latLng.lat;
    this.lng = latLng.lng;
    // delay between directions requests
    this.delay = 100;

    // index to get next address
    this.nextAddress = 0;

    // Start/Finish icons
    this.icons = {
      start: new google.maps.MarkerImage(
       // URL
       'start.png',
       // (width,height)
       new google.maps.Size( 44, 32 ),
       // The origin point (x,y)
       new google.maps.Point( 0, 0 ),
       // The anchor point (x,y)
       new google.maps.Point( 22, 32 )
      ),
      end: new google.maps.MarkerImage(
       // URL
       'end.png',
       // (width,height)
       new google.maps.Size( 44, 32 ),
       // The origin point (x,y)
       new google.maps.Point( 0, 0 ),
       // The anchor point (x,y)
       new google.maps.Point( 22, 32 )
      )
    };

    // addresses
    this.addresses = [
     ['243, Dornacherstrasse', '26, Mattenstrasse'],
     ['243, Dornacherstrasse', 'Gründenstraße 40, Muttenz'],
     ['243, Dornacherstrasse', 'holeestrasse 133, Basel'],
     ['243, Dornacherstrasse', 'Leonhardstr 6, Basel']
    ];

    this.initialize();

    // Function to call the next Geocode operation when the reply comes back
    //this.theNext();

}

GRONMap.prototype.initialize = function initialize() {

  var directionsDisplay = new google.maps.DirectionsRenderer();

  var center = new google.maps.LatLng( this.lat, this.lng );

  var mapOptions = {
    zoom: 7,
    center: center
  }
  this.map = new google.maps.Map(document.getElementById('map'), mapOptions);

  this.bounds = new google.maps.LatLngBounds();

  directionsDisplay.setMap( this.map );

}

GRONMap.prototype.calcRoute = function calcRoute(start, end, next) {
  var directionsService = new google.maps.DirectionsService();

  var request = {
    origin: start,
    destination: end,
    travelMode: 'BICYCLING'
  };

  var _this = this;

  directionsService.route(request,
    function(result, status) {
      if (status == 'OK') {

        var directionsDisplay = new google.maps.DirectionsRenderer( {suppressMarkers: true} );
        directionsDisplay.setMap( _this.map );
        directionsDisplay.setDirections(result);

        // combine the bounds of the responses
        _this.bounds.union(result.routes[0].bounds);
        // zoom and center the map to show all the routes
        _this.map.fitBounds(_this.bounds);

        const route = result.routes[0];
        const summaryPanel = document.getElementById("directions-panel");
        summaryPanel.innerHTML = "";

        // For each route, display summary information.
        for (let i = 0; i < route.legs.length; i++) {
          const routeSegment = i + 1;
          summaryPanel.innerHTML +=
            "<b>Route Segment: " + routeSegment + "</b><br>";
          summaryPanel.innerHTML += route.legs[i].start_address + " to ";
          summaryPanel.innerHTML += route.legs[i].end_address + "<br>";
          summaryPanel.innerHTML += route.legs[i].distance.text + "<br><br>";
        }


        var leg = result.routes[ 0 ].legs[ 0 ];
        _this.makeMarker( leg.start_location, _this.icons.start, "title" );
        _this.makeMarker( leg.end_location, _this.icons.end, 'title' );

      }
      // ====== Decode the error status ======
      else {
        // === if we were sending the requests to fast, try this one again and increase the delay
        if (status == google.maps.GeocoderStatus.OVER_QUERY_LIMIT) {
          _this.nextAddress--;
          _this.delay += 100;
          document.getElementById('delay').innerHTML = "delay between requests=" + _this.delay;
        } else {
          var reason = "Code " + status;
          var msg = 'start="' + start + ' end="' + end + '"" error=' + reason + '(delay=' + _this.delay + 'ms)<br>';
          document.getElementById("messages").innerHTML += msg;
        }
      }
      next();
    });
}

GRONMap.prototype.makeMarker = function makeMarker( position, icon, title ) {
 new google.maps.Marker({
  position: position,
  map: this.map,
  icon: icon,
  title: title
 });
}

GRONMap.prototype.theNext = function theNext() {
  if (this.nextAddress < this.addresses.length) {
    setTimeout( this.calcRoute.bind( this, this.addresses[this.nextAddress][0], this.addresses[this.nextAddress][1], this.theNext.bind(this, map) ), this.delay);
    this.nextAddress++;
  } else {
    // We're done. Show map bounds
    this.map.fitBounds(this.bounds);
  }
}
