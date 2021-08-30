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

} );
