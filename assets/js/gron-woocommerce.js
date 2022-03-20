!( function($) {

  /* Default collection type selection */
  var deliverToHomeRadioElm = $('input[value=deliver_to_home]');

  deliverToHomeRadioElm.prop( 'checked', true );
  deliverToHomeRadioElm.next( 'label' ).addClass( 'active' );

  /* Collection Type */
  var collectionTypeLabelElms = $('.gron_collection_type .woocommerce-input-wrapper label');

  collectionTypeLabelElms.on( 'click', function() {
    $(this).siblings().removeClass( 'active' );
    $(this).addClass( 'active' );
  });

  //

  var locationFieldElm = $('#wcfmmp_user_location');

  locationFieldElm.on( 'blur', gronWC_tjH2Bn_getLatLng.bind( $(this), 0 ) )

} )(jQuery)

function gronWC_tjH2Bn_getLatLng( nextTry ) {

  var maxTry = 20;
  nextTry++;

  setTimeout( function() {

    var inputLatVal = jQuery('#wcfmmp_user_location_lat').val();
    var inputLngVal = jQuery('#wcfmmp_user_location_lng').val();

    if( nextTry > maxTry ) return;

    if( !inputLatVal || !inputLngVal ) {
      gronWC_tjH2Bn_getLatLng( nextTry );
    }else {

      var vendorsInfo = gronWC.vendors_info;
      var directionsService = new google.maps.DirectionsService();
      var start = new google.maps.LatLng( inputLatVal, inputLngVal );

      for( var i = 0; i < vendorsInfo.length; i++ ) {

        if( vendorsInfo[i].store_latitude === null || vendorsInfo[i].store_longitude === null ) continue;

        var end = new google.maps.LatLng( vendorsInfo[i].store_latitude, vendorsInfo[i].store_longitude );

        var request = {
          origin: start,
          destination: end,
          travelMode: 'DRIVING'
        };

        directionsService.route( request, function( result, status ) {

          if( status === 'OK' ) {

            var route = result.routes[0];
            var distance = route.legs[0].distance.text;

            if( distance ) {
              
              // Start all UI changes from here...
              console.log( 'Distance: ',  distance );

            }

          }

        } )

      }


    }

  }, 101);

}
