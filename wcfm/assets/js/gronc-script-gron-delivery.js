jQuery(document).ready(function($) {

  $('.gron_modal').hide();
  $('.gron_modal_trigger_button').on( 'click', function( e ) {
    e.preventDefault();

    var target = $(this).attr('data-target');
    $(target).show();
  });

} );
