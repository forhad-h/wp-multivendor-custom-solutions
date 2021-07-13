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
