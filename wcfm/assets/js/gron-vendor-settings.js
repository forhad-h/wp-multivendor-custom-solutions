jQuery(document).ready(function($) {

  var bodyElm = $('body');

  var shopTimingNotice = $('#gron-shop-timing-notice');
  var deliverySlotNotice = $('#gron-delivery-slot-notice');

  var animSpeed = 300;

  var animationTime = 150;
  var modalElm = $('.gron_modal');
  var backdroElm = $('.gron_backdrop');

  modalElm.hide();
  backdroElm.hide();

  bodyElm.on('click', '.gron_delivery_slot_edit_button', function( e ) {

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

  bodyElm.on( 'click', '.gron_modal_trigger_button', function( e ) {

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
        controller: 'gron-vendor-settings',
        task: 'update-shop-timings',
        data: data
      }
    })
    .done( function (res) {

      if(res) {

        if( res > 0 ) {
          shopTimingNotice.fadeOut( animSpeed );
        }else {
          shopTimingNotice.fadeIn( animSpeed );
        }

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
      var slot_id = _this.attr( 'data-slot-id' );
      data.slot_id = slot_id;
      task = 'update-delivery-slot';
    }

    $.ajax({
      type: "POST",
      url: wcfm_params.ajax_url,
      data: {
        action: 'wcfm_ajax_controller',
        controller: 'gron-vendor-settings',
        task: task,
        data: data
      }
    })
    .done( function (res) {

      if(res) {

        var resObj = JSON.parse( res );

        if( isUpdate ) {

          var data = resObj.data;

          var updatedBtnElm = $('.gron_delivery_slot_edit_button[data-slot-id=' + data.slot_id + ']');
          var updatedElm = updatedBtnElm.closest('tr');

          updatedBtnElm.attr( 'data-time-from', data.raw.time_from );
          updatedBtnElm.attr( 'data-time-to', data.raw.time_to );


          updatedElm.find('.slot_time_form').text( data.formatted.time_from );
          updatedElm.find('.slot_time_to').text( data.formatted.time_to );

          return false;
        }


        var countSlots = resObj.data.count_slots;
        var info = resObj.data.info;
        var slotTableElm = $('#gron-delivery-slot-table');
        var slotTemplateElm = $('#gron-delivery-slot-template');
        var slotTemplateClonElm = slotTemplateElm.clone();

        // show/hide required notice
        if( countSlots > 0 ) {
          deliverySlotNotice.fadeOut( animSpeed );
        }else {
          deliverySlotNotice.fadeIn( animSpeed );
        }

        if( !$.isEmptyObject(info) ) {

          // append new entry
          var previousSLNo = +slotTableElm.find('tr.gron_each_slot').last().find('td').first().text();

          var editButtonElm = slotTemplateClonElm.find('.gron_delivery_slot_edit_button');


          editButtonElm.attr( 'data-slot-id', info.slot_id );
          editButtonElm.attr( 'data-time-from', info.raw.time_from );
          editButtonElm.attr( 'data-time-to', info.raw.time_to );

          slotTemplateClonElm.find('.slot_sl_no').text( previousSLNo + 1 );
          slotTemplateClonElm.find('.slot_time_form').text( info.formatted.time_from );
          slotTemplateClonElm.find('.slot_time_to').text( info.formatted.time_to );

          slotTemplateClonElm.find('.gron_delivery_slot_delete_button').attr( 'data-slot-id', info.slot_id );

          slotTemplateClonElm.attr( 'id', '' ).appendTo( slotTableElm );

        }

      }

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

  bodyElm.on( 'click', '.gron_delivery_slot_delete_button', function( event ) {
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
        controller: 'gron-vendor-settings',
        task: 'delete-delivery-slot',
        data: {
          slot_id: $(this).attr( 'data-slot-id' )
        }
      }
    })
    .done( function (res) {

      if(res) {

        var resObj = JSON.parse( res );
        var countSlots = resObj.data.count_slots;
        var slot_id = resObj.data.slot_id;

        if( countSlots > 0 ) {
          deliverySlotNotice.fadeOut( animSpeed );
        }else {
          deliverySlotNotice.fadeIn( animSpeed );
        }

        var deltedElm = $('.gron_delivery_slot_delete_button[data-slot-id=' + slot_id + ']');
        deltedElm.closest( 'tr' ).removeClass('gron_each_slot').empty().html('<td>Deleted!</td><td></td><td></td><td></td>');

      }

    } )
    .fail( function ( err ) {
      console.error( "Error in AJAX: ", err )
    } )
    .always( function() {

    });

  });

  /**
  * Update general settings
  */
  var submitBtnElm = $('#gron-general-settings-save-button');
  var formElm = $('#gron-general-settings-form');

  submitBtnElm.on( 'click', function() {

    var deliveryByMeValue = $('input[name=delivery_by_me]:checked').val();
    var broadcastTimeLimitValue = $('#time-limit').val();

    // Only accept 'yes' or 'no'
    if( $.inArray( deliveryByMeValue, [ 'yes', 'no' ] )  == -1 ) return;

    // Only accept number for broadcast time limit
    if( !$.isNumeric( broadcastTimeLimitValue ) ) return;

    $(this).val('SAVING...');
    _this = $(this);

    var data = {
      _gron_delivery_by_me: deliveryByMeValue,
      _gron_dn_broadcast_time_limit: broadcastTimeLimitValue
    }
    $.ajax({
      type: "POST",
      url: wcfm_params.ajax_url,
      data: {
        action: 'wcfm_ajax_controller',
        controller: 'gron-vendor-settings',
        task: 'update-general-settings',
        data: data
      }
    })
    .done( function (res) {
      //console.log( res );
    })
    .fail( function ( err ) {
      console.log( err );
    })
    .always( function() {
      _this.val('SAVE');
    });

  });

} );
