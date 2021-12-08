jQuery(document).ready( function($) {

  var userId = $( '#current-user-id' ).val();
  userId = parseInt( userId );

  var animSpeed = 300;

  var data = {
    user_id: userId,
    get_for: 'delivery_boy',
  }

  // Render pending list
  data.status = 'pending';
  gron_get_delivery_notifications( $, data );

  // Render accepted list
  data.status = 'accepted';
  gron_get_delivery_notifications( $, data );

  // Subscribe to delivery-boy channel
  var channel = gronPusher.subscribe('delivery-boy');

  // On new-order event
  channel.bind( 'new-order', function( payload ) {

    var boyId = parseInt( payload.boy_id );

    if( boyId === userId ) {

      data.order_id = payload.order_id;
      data.status = 'pending';

      gron_get_delivery_notifications( $, data, 'partial' );

    }

  } );

  // On delivery-accepted event
  channel.bind( 'delivery-accepted', function( payload ) {

    var associatedBoyIds = Object.values( payload.associated_boy_ids );

    if( $.inArray( userId, associatedBoyIds ) != -1 ) {

      var tableElm = $('#gron-dr-pending-table');
      var trElm = tableElm.find('tr[data-order-id=' + payload.order_id + ']');

      var status = payload.status_msg + ' <a href="' + payload.accepted_by.link + '">' + payload.accepted_by.name + '</a>';

      // Mark as accepted and change status
      trElm.addClass('accepted').find('.status').html( status );

      // Hide action buttons
      trElm.find('#accept-btn').fadeOut( animSpeed )
      trElm.find('#reject-btn').fadeOut( animSpeed )

    }

  });

  // On lock-accepted-delivery event
  channel.bind( 'lock-accepted-delivery', function( payload ) {

    var boyId = parseInt( payload.boy_id );

    if( boyId === userId ) {

      // Refresh pending list
      data.status = 'pending';
      gron_get_delivery_notifications( $, data );

      // Refresh accepted list
      data.status = 'accepted';
      gron_get_delivery_notifications( $, data );

    }else {

      // Refresh pending list
      data.status = 'pending';
      gron_get_delivery_notifications( $, data );

    }

  });

  // On delivery-rejected event
  channel.bind( 'delivery-rejected', function( payload ) {
    var associatedBoyIds = Object.values( payload.associated_boy_ids );

    if( $.inArray( userId, associatedBoyIds ) != -1 ) {

      var tableElm = $('#gron-dr-pending-table');
      var trElm = tableElm.find('tr[data-order-id=' + payload.order_id + ']');
      var status = payload.status_msg;

      // Mark as not accepted and update status
      trElm.removeClass('accepted').find('.status').html( status );

      // show action buttons
      trElm.find('#accept-btn').fadeIn( animSpeed )
      trElm.find('#reject-btn').fadeIn( animSpeed )

    }

  });

  // On no-one-accepted event
  channel.bind( 'no-one-accepted', function( payload ) {
    var boyAndVendorIds = Object.values( payload.boy_and_vendor_ids );

    if( $.inArray( userId, boyAndVendorIds ) != -1 ) {

      // Refresh pending list
      data.status = 'pending';
      gron_get_delivery_notifications( $, data );

    }

  });

  // Accept Delivery request
  $('body').on( 'click', "#accept-btn, #reject-btn", function( event ) {

    event.preventDefault();

    var parentTRElm = $(this).closest( 'tr' );

    parentTRElm.addClass( 'processing' );

    var data = {
      dn_id: parentTRElm.attr('data-dn-id'),
      boy_id: userId
    }

    if( $(this).attr('id') === 'accept-btn' ) {

      parentTRElm.addClass( 'accept' );

      gron_accept_delivery_notifications( $, data, parentTRElm );

    }else if( $(this).attr('id') === 'reject-btn' ) {

      if(!confirm("Are you sure to reject the delivery?")) return;

      parentTRElm.addClass( 'reject' );

      data.reject_type = $(this).attr('data-reject-type');

      gron_reject_delivery_notifications( $, data, parentTRElm );

    }

  })


} );


function gron_get_delivery_notifications( $, data, render = 'all' ) {

  var tableElm = $('#gron-dr-' + data.status + '-table');
  var rowElm = $( '#gron-dr-' + data.status + '-row-template' );

  var tableWrapperElm = $('#gron-dr-' + data.status + ' .gron_table_wrapper');
  var tablePreloaderElm = $('#gron-dr-' + data.status + ' .gron_table_preloader');

  var tabWrapperElm = $( '.gron_tab_wrap' );

  var animSpeed = 300;


  $.ajax({
    url: gron.siteURL + '/wp-json/gron/v1/delivery_notifications',
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

        // Set the entry ID
        rowClonedElm.attr( 'data-dn-id', item.dn_id );

        // Set order ID
        rowClonedElm.attr( 'data-order-id', item.order_id );

        // If the delivery accepted
        if( item.is_accepted ) {

          // Make sure we are in pending list
          if( $('#gron-dr-pending').hasClass('collapse-open') ) {
            rowClonedElm.addClass('accepted');
          }

          rowClonedElm.find( '#accept-btn').remove();
          rowClonedElm.find( '#reject-btn')
          .attr( 'data-reject-type', 'after-accept' );

        }

        if( item.status === 'accepted' ) {
          rowClonedElm.find( '#reject-btn').remove();
        }

        // set order ID
        rowClonedElm.find( '.order' )
        .find('a')
        .text( "#" + item.order_id );

        // set order link
        rowClonedElm.find( '.order' )
        .find('a')
        .attr( 'href', item.order_link );

        // set store name
        rowClonedElm.find( '.store' )
        .text( item.store_name );

        // set delivery day
        rowClonedElm.find( '.delivery_day' )
        .text( item.delivery_day );

        // set delivery time
        rowClonedElm.find( '.delivery_time' )
        .text( item.delivery_time );

        // set status
        var status = item.status;

        if( item.is_accepted ) {
          if( item.accepted_by.id != item.boy_id ) {
            status = item.status_msg + ' <a href="' + item.accepted_by.link + '">' + item.accepted_by.name + '</a>';
          }else {
            status = item.status_msg + ' You';
          }
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
    tablePreloaderElm.fadeOut( animSpeed );

  });
}

function gron_accept_delivery_notifications( $, data, parentTRElm ) {

  var animSpeed = 300;

  $.ajax({
    url: gron.siteURL + '/wp-json/gron/v1/delivery_notifications',
    type: 'PUT',
    beforeSend: function( xhr ) {
      xhr.setRequestHeader( 'X-WP-Nonce', gron.nonce );
    },
    data: data
  })
  .done( function( res ) {

    if( res ) {
      // Hide accept button
      parentTRElm.find('#accept-btn').fadeOut( animSpeed );

      // Change the parent tr element class
      parentTRElm
      .removeClass( ['processing', 'accept'] )
      .addClass( 'accepted' );

      //
      parentTRElm
      .find('#reject-btn')
      .attr('data-reject-type', 'after-accept')

    }

  } )
  .fail( function( err ) {
   console.log( err.responseText );
  } )
  .always( function() {
   //console.log("complete");
  } );

}


function gron_reject_delivery_notifications( $, data, parentTRElm ) {

  var animSpeed = 300;

  $.ajax({
    url: gron.siteURL + '/wp-json/gron/v1/delivery_notifications',
    type: 'DELETE',
    beforeSend: function( xhr ) {
      xhr.setRequestHeader( 'X-WP-Nonce', gron.nonce );
    },
    data: data
  })
  .done( function( res ) {

   if( res ) {
     parentTRElm.fadeOut( animSpeed ).promise().done( function() {
      parentTRElm.remove();
     });
   }
  } )
  .fail( function( err ) {
   console.log( err.responseText );
  } )

}
