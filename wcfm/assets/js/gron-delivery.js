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
  } );

  // Regist timings from to submit
  var formElm = $('#gron-shop-timing-form');
  formElm.on( 'submit', function( event ) {
    event.preventDefault();
  })

  var data = {};
  var singleTimingFields = $('.is_active, .start_time, .end_time');

  singleTimingFields.on( 'change', function() {
    var parentElm = $(this).closest( '.gron_single_titming' );

    var is_active = parentElm.find('.is_active').is(':checked');
    var day_name = parentElm.find('.day_name').text();
    var start_time = parentElm.find('.start_time').val();
    var end_time = parentElm.find('.end_time').val();

    data[day_name] = {
      is_active: is_active,
      day_name: day_name,
      start_time: start_time,
      end_time: end_time
    };

  })

  // Save timings data
  var submitBtn = $('#gron-shop-timings-save-button');
  submitBtn.on('click', function() {

    $.ajax({
      type: "POST",
      url: wcfm_params.ajax_url,
      data: {
        action: 'wcfm_ajax_controller',
        controller: 'gron-delivery',
        task: 'update-shop-timings',
        data: data
      }
    })
    .done( function (res) {

        //var resObj = JSON.parse( res );

        console.log( res );

        //if( resObj.data ) {}

    } )
    .fail( function ( err ) {
      console.error( "Error in AJAX: ", err )
    } )

  });


} );
