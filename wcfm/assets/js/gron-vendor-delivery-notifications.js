jQuery(document).ready( function($) {

  var userId = $( '#current-user-id' ).val();
  userId = parseInt( userId );

  var data = {
    user_id: userId,
    get_for: 'vendor',
  }
  // Render pending list
  data.status = 'pending';
  gron_delivery_notifications_ajax_request( $, data );

  // Render accepted list
  data.status = 'accepted';
  gron_delivery_notifications_ajax_request( $, data );

  // Subscribe to delivery-boy channel
  var channel = gronPusher.subscribe('vendor');

  channel.bind( 'new-order', function( payload ) {

    var vendorId = parseInt( payload.vendor_id );

    if( vendorId === userId ) {

      data.order_id = payload.order_id;
      data.status = 'pending';

      gron_delivery_notifications_ajax_request( $, data, 'partial' );

    }

  } );

  // On new-order event
  channel.bind( 'delivery-accepted', function( payload ) {

    var vendorId = parseInt( payload.vendor_id );

    if( vendorId === userId ) {

      var tableElm = $('#gron-dr-pending-table');

      var status = payload.status_msg + ' <a href="' + payload.accepted_by.link + '">' + payload.accepted_by.name + '</a>'

      tableElm
      .find('tr[data-order-id=' + payload.order_id + ']')
      .addClass('accepted')
      .find('.status')
      .html( status );

    }

  });

  // On delivery-rejected event
  channel.bind( 'delivery-rejected', function( payload ) {

    var vendorId = parseInt( payload.vendor_id );

    if( vendorId === userId ) {

      var tableElm = $('#gron-dr-pending-table');
      var status = payload.status_msg;

      tableElm
      .find('tr[data-order-id=' + payload.order_id + ']')
      .removeClass('accepted')
      .find('.status')
      .html( status );

    }

  });

  // On lock-accepted-delivery event
  channel.bind( 'lock-accepted-delivery', function( payload ) {

    var vendorId = parseInt( payload.vendor_id );

    if( vendorId === userId ) {

      // Refresh pending lists
      data.status = 'pending';
      gron_delivery_notifications_ajax_request( $, data );

      // Refresh accepted lists
      data.status = 'accepted';
      gron_delivery_notifications_ajax_request( $, data );

    }

  });

  // On no-one-accepted event
  channel.bind( 'no-one-accepted', function( payload ) {
    var boyAndVendorIds = Object.values( payload.boy_and_vendor_ids );

    if( $.inArray( userId, boyAndVendorIds ) != -1 ) {

      // Refresh pending list
      data.status = 'pending';
      gron_delivery_notifications_ajax_request( $, data );

    }

  });

} );


function gron_delivery_notifications_ajax_request( $, data, render = 'all' ) {

  var tableElm = $('#gron-dr-' + data.status + '-table');
  var rowElm = $( '#gron-dr-' + data.status + '-row-template' );

  var tableWrapperElm = $('#gron-dr-' + data.status + ' .gron_table_wrapper');
  var tablePreloaderElm = $('#gron-dr-' + data.status + ' .gron_table_preloader');

  var tabWrapperElm = $( '.gron_tab_wrap' );


  $.ajax({
    url: gron.siteURL + '/wp-json/gron/v1/delivery-notifications',
    type: 'GET',
    beforeSend: function( xhr ) {
      xhr.setRequestHeader( 'X-WP-Nonce', gron.nonce );
    },
    data: data
  })
  .done( function( res ) {

    if( res ) {

      var tableBodyElm = tableElm.find('tbody');

      if( render !== 'partial' ) {
        tableBodyElm.empty();
      }

      $.each(res, function(index, item) {

        // clone the row
        var rowClonedElm = rowElm.clone();

        // Fill with response data

        // Set the entry id
        rowClonedElm.attr( 'data-dn-id', item.dn_id );

        // Set order ID
        rowClonedElm.attr( 'data-order-id', item.order_id );

        // If the delivery accepted
        if( item.is_accepted && item.status === 'pending' ) {
          // Make sure we are in pending list
          rowClonedElm.addClass('accepted');
        }

        // If not accepted by anyone, only then show the delivered button
        // Otherwise remove it
        if( item.is_accepted || !!+item.boy_id ) {
          rowClonedElm.find('#delivered-btn').remove();
        }

        // set order ID
        rowClonedElm.find( '.order' )
        .find('a')
        .text( "#" + item.order_id );

        // set order link
        rowClonedElm.find( '.order' )
        .find('a')
        .attr( 'href', item.order_link );

        // set delivery day
        rowClonedElm.find( '.delivery_day' )
        .text( item.delivery_day );

        // set delivery day
        rowClonedElm.find( '.delivery_time' )
        .text( item.delivery_time );

        // set accepted by name
        rowClonedElm.find( '.accepted_by' )
        .find('a')
        .text( item.accepted_by_name );

        // set accepted by link
        rowClonedElm.find( '.accepted_by' )
        .find('a')
        .attr( 'href', item.accepted_by_link );

        // set status
        var status = item.status_msg ? item.status_msg : item.status;

        if( item.is_accepted ) {
          status = item.status_msg + ' <a href="' + item.accepted_by.link + '">' + item.accepted_by.name + '</a>'
        }

        rowClonedElm.find( '.status' )
        .html( status );

        // Set the availability timer
        // Reference - https://www.jqueryscript.net/time-clock/Minimal-Stopwatch-Timer-Plugin-For-jQuery.html
        var availability_time = item.availability_time;

        rowClonedElm.find( '.timer' ).timer({
            action: 'start',
            duration: availability_time ? availability_time : 1,
            countdown: true,
            callback: function(){}
        });

        // Remove the ID
        rowClonedElm.removeAttr('id');
        tableBodyElm.prepend( rowClonedElm );

      });

      // Adjust the tab wrapper height, default generated by WCFM
      // to perfectly get the tableElm.height() need to place it after all rendering operations
      if( tableElm.height() > 0 ) {
        tabWrapperElm.css( 'height', tableElm.height() + 191 + 'px' );
      }

    }

  } )
  .fail( function( err ) {
    console.log( err.responseText );
  } )
  .always( function() {

    tableWrapperElm.css( 'height', 'auto' );
    tablePreloaderElm.fadeOut( 300 );

  });
}
