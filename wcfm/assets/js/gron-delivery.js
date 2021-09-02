jQuery(document).ready(function($) {

  var currentTab = $('#gron-current-tab').val();
  var tabWrap = $(".gron_tab_wrap");

  console.log( currentTab );

  if( currentTab ) {

    setTimeout( function() {

      if( currentTab === "delivery_slots" ) {
        $("#gron-delivery-slots").trigger("click");
      }

      setTimeout( function() {
        tabWrap.css('opacity', '1');
      }, 200)

    }, 1000)

  }else {

    tabWrap.css('opacity', '1');

  }

  var animationTime = 150;
  var modalElm = $('.gron_modal');
  var backdroElm = $('.gron_backdrop');

  modalElm.hide();
  backdroElm.hide();

  $('.gron_delivery_slot_edit_button').on( 'click', function( e ) {

    e.preventDefault();

    var id = $(this).attr('data-slot-id');
    var timeFrom = $(this).attr('data-time-from');
    var timeTo = $(this).attr('data-time-to');

    $('.time_from').val( timeFrom );
    $('.time_to').val( timeTo );
    $('#gron-delivery-slot-save-button').attr( 'data-slot-id', id );

  });

  $('.gron_delivery_slot_add_new_button').on( 'click', function( e ) {
    $('.time_from').val( '' );
    $('.time_to').val( '' );
    $('#gron-delivery-slot-save-button').removeAttr( 'data-slot-id' );
  })

  $('.gron_modal_trigger_button').on( 'click', function( e ) {

    e.preventDefault();

    var target = $(this).attr('data-target');
    $(target).fadeIn( animationTime );
    backdroElm.fadeIn( animationTime );

  });

  backdroElm.on( 'click', function( e ) {
    $(this).fadeOut( animationTime );
    modalElm.fadeOut( animationTime );
  } );

  // Regist timings from to submit
  var formElms = $('#gron-shop-timing-form, #gron-delivery-slots-form');
  formElms.on( 'submit', function( event ) {
    event.preventDefault();
  })

  var data = {};
  var singleTimingFields = $('.is_active, .start_time, .end_time');

  singleTimingFields.on( 'change', function() {
    var parentElm = $(this).closest( '.gron_single_titming' );

    var isActive = parentElm.find('.is_active').is(':checked');
    var dayName = parentElm.find('.day_name').text();
    var startTime = parentElm.find('.start_time').val();
    var endTime = parentElm.find('.end_time').val();

    data[dayName] = {
      is_active: isActive,
      day_name: dayName,
      start_time: startTime,
      end_time: endTime
    };

  })

  // Save timings data
  var shopTimingSaveBtnElm = $('#gron-shop-timings-save-button');
  shopTimingSaveBtnElm.on('click', function() {

    _this = $(this)

    _this.val('Saving...');

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
      if(res) {
        var hrefWithoutQuery = [location.protocol, '//', location.host, location.pathname].join('');
        window.location.href = hrefWithoutQuery;
      }
    } )
    .fail( function ( err ) {
      console.error( "Error in AJAX: ", err )
    } )
    .always( function() {
      _this.val('Save');
    });

  });

  // Insert delivery slot
  var deliverySlotSaveButton = $('#gron-delivery-slot-save-button');
  deliverySlotSaveButton.on( 'click', function() {

    _this = $(this);
    _this.val('Saving...');

    var timeFrom = $('.time_from').val();
    var timeTo = $('.time_to').val();
    var task = 'insert-delivery-slot';
    var isUpdate = +_this.attr( 'data-slot-id' ) > 0;

    var data = {
      time_from: timeFrom,
      time_to: timeTo
    };

    if( isUpdate ) {
      data.id = _this.attr( 'data-slot-id' );
      task = 'update-delivery-slot';
    }

    $.ajax({
      type: "POST",
      url: wcfm_params.ajax_url,
      data: {
        action: 'wcfm_ajax_controller',
        controller: 'gron-delivery',
        task: task,
        data: data
      }
    })
    .done( function (res) {
      var hrefWithoutQuery = [location.protocol, '//', location.host, location.pathname].join('');
      window.location.href = hrefWithoutQuery + '?tab=delivery_slots' ;
    } )
    .fail( function ( err ) {
      console.error( "Error in AJAX: ", err )
    } )
    .always( function() {
      _this.val('Save');
      backdroElm.fadeOut( animationTime );
      modalElm.fadeOut( animationTime );
    });

  });

  // delete delivery slot
  var deliverySlotDeleteBtnElms = $('.gron_delivery_slot_delete_button');

  deliverySlotDeleteBtnElms.on( 'click', function( event ) {
    event.preventDefault();

    _this = $(this);

    if( !confirm("You are deleting a slot!") ) return;

    if( _this.hasClass( 'disabled' ) ) return;
    _this.addClass('disabled');

    $.ajax({
      type: "POST",
      url: wcfm_params.ajax_url,
      data: {
        action: 'wcfm_ajax_controller',
        controller: 'gron-delivery',
        task: 'delete-delivery-slot',
        data: {
          id: $(this).attr( 'data-slot-id' )
        }
      }
    })
    .done( function (res) {
      var hrefWithoutQuery = [location.protocol, '//', location.host, location.pathname].join('');
      window.location.href = hrefWithoutQuery + '?tab=delivery_slots' ;
    } )
    .fail( function ( err ) {
      console.error( "Error in AJAX: ", err )
    } )
    .always( function() {

    });

  });

} );
