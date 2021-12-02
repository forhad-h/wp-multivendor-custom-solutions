jQuery(document).ready( function($) {

  var userId = $( '#current-user-id' ).val();

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

  // Subscribe on delivery-boy channel
  var channel = gronPusher.subscribe('delivery-boy');

  channel.bind( 'new-order', function( payload ) {

    if( payload.boyId === userId ) {

      data.order_id = payload.orderId;
      data.status = 'pending';

      gron_get_delivery_notifications( $, data, 'partial' );

    }

  } );

  gron_reject_delivery_notifications( $, {
    boy_id: 7,
    dn_id: 50
  } )

} );


function gron_get_delivery_notifications( $, data, render = 'all' ) {

  var tableElm = $('#gron-dr-' + data.status + '-table');
  var rowElm = $( '#gron-dr-' + data.status + '-row-template' );

  var tableWrapperElm = $('#gron-dr-' + data.status + ' .gron_table_wrapper');
  var tablePreloaderElm = $('#gron-dr-' + data.status + ' .gron_table_preloader');

  var tabWrapperElm = $( '.gron_tab_wrap' );


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

      $.each(res, function(index, data) {

        // clone the row
        var rowClonedElm = rowElm.clone();

        // Fill with response data

        // set order ID
        rowClonedElm.find( '.order' )
        .find('a')
        .text( "#" + data.order_id );

        // set order link
        rowClonedElm.find( '.order' )
        .find('a')
        .attr( 'href', data.order_link );

        // set store name
        rowClonedElm.find( '.store' )
        .text( data.store_name );

        // set delivery day
        rowClonedElm.find( '.delivery_day' )
        .text( data.delivery_day );

        // set delivery day
        rowClonedElm.find( '.delivery_time' )
        .text( data.delivery_time );

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

function gron_accept_delivery_notifications( $, data ) {

  $.ajax({
    url: gron.siteURL + '/wp-json/gron/v1/delivery_notifications',
    type: 'PUT',
    beforeSend: function( xhr ) {
      xhr.setRequestHeader( 'X-WP-Nonce', gron.nonce );
    },
    data: data
  })
  .done( function( res ) {
   console.log( res );
  } )
  .fail( function( err ) {
   console.log( err.responseText );
  } )
  .always( function() {
   //console.log("complete");
  } );

}


function gron_reject_delivery_notifications( $, data ) {

  $.ajax({
    url: gron.siteURL + '/wp-json/gron/v1/delivery_notifications',
    type: 'DELETE',
    beforeSend: function( xhr ) {
      xhr.setRequestHeader( 'X-WP-Nonce', gron.nonce );
    },
    data: data
  })
  .done( function( res ) {
   console.log( res );
  } )
  .fail( function( err ) {
   console.log( err.responseText );
  } )
  .always( function() {
   //console.log("complete");
  } );

}
