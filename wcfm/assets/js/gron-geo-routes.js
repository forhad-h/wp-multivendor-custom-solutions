jQuery(document).ready(function($) {

  var baseLocation = {};
  var orderAddresses = {};
  var travelModeElm = $('#gron-route-travel-mode');

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
        var hasMapApiKey = resObj.data.has_api_key;

        var noticeElm = $('#gron-map-settings-notice');

        orderAddresses = resObj.data.order;

        baseLocation = {
          address: storeAddress,
          lat: lat,
          lng: lng
        };

        if( !hasMapApiKey || !storeAddress || !lat || !lng ) {
          noticeElm.show();
        }

        if(Object.keys(orderAddresses).length > 0 ) {
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

  travelModeElm.on( 'change', function() {

    var travelMode = $(this).val();
    var summaryPanel = document.querySelector("#gron-route-details-panel ul");
    summaryPanel.innerHTML = '';

    new GRONMap( baseLocation, orderAddresses, travelMode );

  })

} );
