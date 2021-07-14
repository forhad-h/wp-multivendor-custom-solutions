jQuery(document).ready(function($) {



  $.ajax({
    type: "POST",
    url: wcfm_params.ajax_url,
    data: {
      action: 'wcfm_ajax_controller',
      controller: 'gronc-geo-routes',
      task: 'get-map-pointer'
    }
  })
  .done( function (res) {

      console.log(res)

      if( res == null ) throw new Error('NULL found!')

      var resObj = JSON.parse( res );

/*      if( resObj.data ) {
        console.log('Response inside inital ajax controller: ', resObj);

        var latLng = { lat: 4.2105, lng: 101.9758 };
        //  var routeMap = new GRONMap( latLng );
      }*/

  } )
  .fail( function ( err ) {
    console.error( "Error in AJAX", err )
  } )

} );
