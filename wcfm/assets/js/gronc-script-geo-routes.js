jQuery(document).ready(function($) {



  $.ajax({
    type: "POST",
    url: wcfm_params.ajax_url,
    data: {
      action: 'wcfm_ajax_controller',
      controller: 'gronc-geo-routes',
      task: 'get-map-address'
    }
  })
  .done( function (res) {

      var resObj = JSON.parse( res );

      if( resObj.data ) {
        console.log('Response inside inital ajax controller: ', resObj);

        var latLng = { lat: 4.2105, lng: 101.9758 };
        //  var routeMap = new GRONMap( latLng );
      }

  } )
  .fail( function ( err ) {
    console.error( "Error in AJAX", err )
  } )


  $('#gronc-geo-routes-admin-settings-form').on( 'submit', function( e ) {
    e.preventDefault();

    $.ajax({
      type: "POST",
      url: wcfm_params.ajax_url,
      data: {
        action: 'wcfm_ajax_controller',
        controller: 'gronc-geo-routes',
        task: 'save-admin-settings',
        google_map_api_key: $('#gron-google-map-api-key').val()
      }
    })
    .done( function( res ) {

    } )
    .fail( function( err ) {

    } )

  })

} );
