jQuery(document).ready(function($) {

  $.ajax({
    type: "POST",
    url: wcfm_params.ajax_url,
    data: {
      action: 'wcfm_ajax_controller',
      controller: 'gron-geo-routes',
      task: 'get-map-locations'
    }
  })
  .done( function (res) {

    if( res ) {
      var resObj = JSON.parse( res );

      if( resObj.data ) {

        var lat = resObj.data.store.lat;
        var lng = resObj.data.store.lng;
        var storeAddress = resObj.data.store.address;
        var orderAddresses = resObj.data.order;
        var orderAddresses = resObj.data.order

        var baseLocation = {
          address: storeAddress,
          lat: lat,
          lng: lng
        };

        if(Object.keys(orderAddresses).length > 0 ) {

          var noticeElm = $('#gron-map-settings-notice');
          noticeElm.css( 'display', 'none' );

          new GRONMap( baseLocation, orderAddresses );
        }else {
          new GRONMap( baseLocation )
        }

      }
    }

  } )
  .fail( function ( err ) {
    console.error( "Error in AJAX: ", err )
  } )

} );
