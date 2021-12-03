jQuery(document).ready( function($) {

  var data = {
    get_for: 'admin',
  }
  // Render pending list
  data.status = 'pending';
  gron_delivery_notifications_ajax_request( $, data );

  // Render accepted list
  data.status = 'accepted';
  gron_delivery_notifications_ajax_request( $, data );

  // Subscribe on delivery-boy channel
  var channel = gronPusher.subscribe('admin');

  channel.bind( 'new-order', function( payload ) {

      data.order_id = payload.orderId;
      data.status = 'pending';

      gron_delivery_notifications_ajax_request( $, data, 'partial' );

  } );

  // On new-order event
  channel.bind( 'delivery-accepted', function( payload ) {

    var tableElm = $('#gron-dr-pending-table');

    var userProfileUrl = gron.siteURL +
    '/store-manager/delivery-boys-stats/' +
     payload.accepted_by_id + '/';

    var status = 'Accepted By ' + '<a href="' + userProfileUrl + '">' + payload.accepted_by_name + '</a>'

    tableElm
    .find('tr[data-dn-id=' + payload.dn_id + ']')
    .find('.status')
    .html( status );

  });

} );


function gron_delivery_notifications_ajax_request( $, data, render = 'all' ) {

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

        // set delivery day
        rowClonedElm.find( '.delivery_day' )
        .text( data.delivery_day );

        // set delivery day
        rowClonedElm.find( '.delivery_time' )
        .text( data.delivery_time );

        // set status
        rowClonedElm.find( '.status' )
        .text( data.status_msg ? data.status_msg : data.status );

        // set accepted by name
        rowClonedElm.find( '.accepted_by' )
        .find('a')
        .text( data.accepted_by_name );

        // set accepted by link
        rowClonedElm.find( '.accepted_by' )
        .find('a')
        .attr( 'href', data.accepted_by_link );


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
