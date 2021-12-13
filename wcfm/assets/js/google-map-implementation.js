
function GRONMap( baseLocation, othersLocation = [], travelMode = 'DRIVING' ) {

    this.map;
    this.bounds;

    this.lat = parseFloat(baseLocation.lat);
    this.lng = parseFloat(baseLocation.lng);
    this.address = baseLocation.address;
    this.travelMode = travelMode;

    this.othersLocation = othersLocation;

    // delay between directions requests
    this.delay = 100;

    // index to get next address
    this.nextAddress = 0;

    // Start/Finish icons
    this.icons = {
      start: new google.maps.MarkerImage(
       wcfm.gronDirUri + 'wcfm/assets/images/start_marker.png',
       new google.maps.Size( 64, 64 ),
      ),
      end: new google.maps.MarkerImage(
       wcfm.gronDirUri + 'wcfm/assets/images/end_marker.png',
       new google.maps.Size( 64, 64 ),
      )
    };

    this.initialize();

    // Function to call the next Geocode operation when the reply comes back
    if(othersLocation.length > 0) {
       this.theNext();
    }

}

GRONMap.prototype.initialize = function() {

  var directionsDisplay = new google.maps.DirectionsRenderer();

  var center = new google.maps.LatLng( this.lat, this.lng );

  var mapOptions = {
    zoom: 7,
    center: center
  }
  this.map = new google.maps.Map(document.getElementById('map'), mapOptions);

  this.makeInfoWindow( center, this.icons.start, this.address );

  this.bounds = new google.maps.LatLngBounds();

  // Create an info window to share between markers.
  this.infoWindow = new google.maps.InfoWindow();

  directionsDisplay.setMap( this.map );

}

GRONMap.prototype.calcRoute = function(start, end, endAddress, next) {
  var directionsService = new google.maps.DirectionsService();

  var request = {
    origin: start,
    destination: end,
    travelMode: this.travelMode
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

        var route = result.routes[0];
        var summaryPanel = document.querySelector("#gron-route-details-panel ul");

        var info = '<div class="grons_single_info">';
        info += "<p><strong>To :</strong> " + route.legs[0].end_address + "</p>";
        info += "<p><strong>Distance: </strong>" + route.legs[0].distance.text + "</p>";
        info += "<p><strong>Duration: </strong>" + route.legs[0].duration.text + "</p>";
        info += '</div>'

        summaryPanel.innerHTML += "<li>" + info + "</li>"


        _this.makeInfoWindow( end, _this.icons.end, endAddress, info );

      }
      // ====== Decode the error status ======
      else {
        // === if we were sending the requests to fast, try this one again and increase the delay
        if (status == google.maps.GeocoderStatus.OVER_QUERY_LIMIT) {
          _this.nextAddress--;
          _this.delay += 100;
          //document.getElementById('delay').innerHTML = "delay between requests=" + _this.delay;
        } else {
          var reason = "Code " + status;
          var msg = 'start="' + start + ' end="' + end + '"" error=' + reason + '(delay=' + _this.delay + 'ms)<br>';
          //document.getElementById("messages").innerHTML += msg;
        }
      }
      next();
    });
}

GRONMap.prototype.theNext = function() {
  if (this.nextAddress < this.othersLocation.length) {

    var lat = parseFloat(this.othersLocation[this.nextAddress].lat);
    var lng = parseFloat(this.othersLocation[this.nextAddress].lng);
    var endAddress = this.othersLocation[this.nextAddress].address;

    var start = new google.maps.LatLng( this.lat, this.lng );
    var end = new google.maps.LatLng( lat, lng );

    setTimeout(
      this.calcRoute.bind(
        this,
        start,
        end,
        endAddress,
        this.theNext.bind(this, map)
      ),
      this.delay
    );

    this.nextAddress++;

  } else {
    // We're done. Show map bounds
    this.map.fitBounds(this.bounds);
  }
}


GRONMap.prototype.makeInfoWindow = function( position, icon, title, info = '' ) {

  // Create the markers.
  var marker = new google.maps.Marker({
    position: position,
    map: this.map,
    title: title,
    label: '',
    icon: icon,
    optimized: false,
  });

  // Add a click listener for each marker, and set up the info window.
  marker.addListener("click", () => {

    var content = info ? info : marker.getTitle();

    this.infoWindow.close();
    this.infoWindow.setContent( content );
    this.infoWindow.open(marker.getMap(), marker);
  });

}
