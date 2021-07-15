jQuery(document).ready(function($) {



  $.ajax({
    type: "POST",
    url: wcfm_params.ajax_url,
    data: {
      action: 'wcfm_ajax_controller',
      controller: 'gronc-geo-routes',
      task: 'get-map-locations'
    }
  })
  .done( function (res) {

      var resObj = JSON.parse( res );

      if( resObj.data ) {

        //console.log('Response inside inital ajax controller: ', resObj);

        var lat = resObj.data.store.lat;
        var lng = resObj.data.store.lng;
        var address = resObj.data.store.address;

        var baseLocation = {
          address: address,
          lat: lat,
          lng: lng
        };

        var othersLocation = resObj.data.order

        if(othersLocation.length > 0 ) {
          new GRONMap( baseLocation, othersLocation );
        }else {
          new GRONMap( baseLocation )
        }

      }

  } )
  .fail( function ( err ) {
    console.error( "Error in AJAX", err )
  } )

} );
