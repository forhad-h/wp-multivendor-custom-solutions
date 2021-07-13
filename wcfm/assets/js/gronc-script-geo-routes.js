jQuery(document).ready(function($) {

  $.ajax({
    type: "POST",
    url: wcfm_params.ajax_url,
    data: {
      action: 'wcfm_ajax_controller',
      controller: 'gronc-geo-routes'
    }
  })
  .done( function (res) {
    console.log(res)
  } )
  .fail( function ( err ) {
    console.error( "Error in AJAX", err )
  } )


} );

  var addresses = [
    ['243, Dornacherstrasse', '26, Mattenstrasse'],
    ['243, Dornacherstrasse', 'Gründenstraße 40, Muttenz'],
    ['243, Dornacherstrasse', 'holeestrasse 133, Basel'],
    ['243, Dornacherstrasse', 'Leonhardstr 6, Basel'],
    ['243, Dornacherstrasse', 'Petersplatz 1, Basel']
  ];

  var directionsDisplay;
  var directionsService = new google.maps.DirectionsService();
  var map;
  var bounds;

  function initialize() {
    directionsDisplay = new google.maps.DirectionsRenderer();
    var basel = new google.maps.LatLng(41.850033, -87.6500523);
    var mapOptions = {
      zoom: 7,
      center: basel
    }
    map = new google.maps.Map(document.getElementById('map'), mapOptions);
    directionsDisplay.setMap(map);
    bounds = new google.maps.LatLngBounds();
  }

  // delay between directions requests
  var delay = 100;

  // Start/Finish icons
   var icons = {
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

  function calcRoute(start, end, next) {

    var request = {
      origin: start,
      destination: end,
      travelMode: 'BICYCLING'
    };
    directionsService.route(request,
      function(result, status) {
        if (status == 'OK') {

          directionsDisplay = new google.maps.DirectionsRenderer( {suppressMarkers: true} );
          directionsDisplay.setMap(map);
          directionsDisplay.setDirections(result);
          // combine the bounds of the responses
          bounds.union(result.routes[0].bounds);
          // zoom and center the map to show all the routes
          map.fitBounds(bounds);

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
          makeMarker( leg.start_location, icons.start, "title" );
          makeMarker( leg.end_location, icons.end, 'title' );

        }
        // ====== Decode the error status ======
        else {
          // === if we were sending the requests to fast, try this one again and increase the delay
          if (status == google.maps.GeocoderStatus.OVER_QUERY_LIMIT) {
            nextAddress--;
            delay += 100;
            document.getElementById('delay').innerHTML = "delay between requests=" + delay;
          } else {
            var reason = "Code " + status;
            var msg = 'start="' + start + ' end="' + end + '"" error=' + reason + '(delay=' + delay + 'ms)<br>';
            document.getElementById("messages").innerHTML += msg;
          }
        }
        next();
      });
  }

  initialize();
  // ======= Global variable to remind us what to do next
  var nextAddress = 0;

  // ======= Function to call the next Geocode operation when the reply comes back

  function theNext() {
    if (nextAddress < addresses.length) {
      setTimeout( calcRoute( addresses[nextAddress][0], addresses[nextAddress][1], theNext ), delay);
      nextAddress++;
    } else {
      // We're done. Show map bounds
      map.fitBounds(bounds);
    }
  }

  theNext();

  function makeMarker( position, icon, title ) {
   new google.maps.Marker({
    position: position,
    map: map,
    icon: icon,
    title: title
   });
  }
