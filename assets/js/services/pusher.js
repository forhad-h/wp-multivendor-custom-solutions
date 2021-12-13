// Make pusher available for all scripts
var gronPusher = new Pusher( pusherObj.key, {
  cluster: pusherObj.cluster
});

!(function( $ ) {

  $(document).ready( function() {

    // Global Options for toast notification
    // Docs - https://www.jqueryscript.net/other/jQuery-Plugin-For-Animated-Stackable-Toast-Messages-Toast.html
    var toastOptions = {
      loader   : true,
      loaderBg:'#212020',
      bgColor  : '#313030',
      hideAfter: 60000
    }

    switch( pusherObj.userInfo.role ) {

      case 'administrator':

        // Subscribe to 'admin' channel
        var channel = gronPusher.subscribe('admin');

        // On new-order event
        channel.bind('new-order', function( data ) {

          // Format toast message
          var deliveriesUrl = pusherObj.siteUrl + '/store-manager/gron-admin-delivery-notifications/'
          toastOptions.heading = "New Order!";
          toastOptions.text = "You received a new order. Please check the <a href='" + deliveriesUrl + "'>GRON - Deliveries</a>!";

          $.toast( toastOptions );

        });

        // On delivery-accepted event
        channel.bind( 'delivery-accepted', function( data ) {

          // Toast heading
          toastOptions.heading = "Delivery Accepted!";

          // Toast message
          toastOptions.text = data.status_msg + ' <a href="' + data.accepted_by.link + '">' + data.accepted_by.name + '</a>';

          $.toast( toastOptions );

        });

        break;

      case 'wcfm_vendor':

        // Subscribe to 'vendor' channel
        var channel = gronPusher.subscribe('vendor');

        // On new-order event
        channel.bind('new-order', function( data ) {

          if( data.vendor_id == pusherObj.userInfo.id ) {

            // Format toast message
            var deliveriesUrl = pusherObj.siteUrl + '/store-manager/gron-vendor-delivery-notifications/'
            toastOptions.heading = "New Order!";
            toastOptions.text = "You received a new order. Please check the <a href='" + deliveriesUrl + "'>GRON - Deliveries</a>!";

            $.toast( toastOptions );

          }

        });

        // On delivery-accepted event
        channel.bind( 'delivery-accepted', function( data ) {

          if( data.vendor_id == pusherObj.userInfo.id ) {
            // Toast heading
            toastOptions.heading = "Delivery Accepted!";

            // Toast message
            toastOptions.text = data.status_msg + ' <a href="' + data.accepted_by.link + '">' + data.accepted_by.name + '</a>';

            $.toast( toastOptions );

          }

        });

        break;

      case 'wcfm_delivery_boy':

        // Subscribe to 'vendor' channel
        var channel = gronPusher.subscribe('delivery-boy');

        // On new-order event
        channel.bind('new-order', function( data ) {

          if( data.boy_id == pusherObj.userInfo.id ) {

            // Format toast message
            var deliveriesUrl = pusherObj.siteUrl + '/store-manager/gron-boy-delivery-requests/'
            toastOptions.heading = "New Delivery Request!";
            toastOptions.text = "You received a new delivery request. Please check the <a href='" + deliveriesUrl + "'>GRON - Deliveries</a>!";

            $.toast( toastOptions );

          }

        });

        // On delivery-accepted event
        channel.bind( 'delivery-accepted', function( data ) {

          var associatedBoyIds = Object.values( data.associated_boy_ids );

          if( $.inArray( parseInt(pusherObj.userInfo.id), associatedBoyIds ) != -1 ) {

            toastOptions.heading = "Delivery Accepted!";
            toastOptions.text = data.status_msg + ' <a href="' + data.accepted_by.link + '">' + data.accepted_by.name + '</a>';

            $.toast( toastOptions );

          }

        });

        break;

      default:
        // do nothing for others
        return;
    }

  });

})(jQuery)
