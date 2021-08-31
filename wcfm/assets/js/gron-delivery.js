jQuery(document).ready(function($) {

  var animationTime = 300;

  $('.gron_modal').hide();
  $('.gron_backdrop').hide();

  $('.gron_modal_trigger_button').on( 'click', function( e ) {
    e.preventDefault();

    var target = $(this).attr('data-target');
    $(target).fadeIn( animationTime );
    $('.gron_backdrop').fadeIn( animationTime );
  });

  $('.gron_backdrop').on( 'click', function( e ) {
    $(this).fadeOut( animationTime );
    $('.gron_modal').fadeOut( animationTime );
  } )


  $.ajax({
    type: "POST",
    url: wcfm_params.ajax_url,
    data: {
      action: 'wcfm_ajax_controller',
      controller: 'gron-delivery',
      task: 'update-shop-timings',
      data: ''
    }
  })
  .done( function (res) {

      //var resObj = JSON.parse( res );

      console.log( "Working...", res );

/*      if( resObj.data ) {


      }*/

  } )
  .fail( function ( err ) {
    console.error( "Error in AJAX: ", err )
  } )


} );
