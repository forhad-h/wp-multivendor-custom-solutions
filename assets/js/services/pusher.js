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

        // Subscribe on 'admin' channel
        var channel = gronPusher.subscribe('admin');

        channel.bind('new-order', function( data ) {

          // Format toast message
          var deliveriesUrl = pusherObj.siteUrl + '/store-manager/gron-admin-delivery-notifications/'
          toastOptions.heading = "New Order!";
          toastOptions.text = "You received a new order. Please check the <a href='" + deliveriesUrl + "'>GRON - Deliveries</a>!";

          $.toast( toastOptions );

        });

        break;

      case 'wcfm_vendor':

        // Subscribe on 'vendor' channel
        var channel = gronPusher.subscribe('vendor');

        channel.bind('new-order', function( data ) {

          if( data.vendorId === pusherObj.userInfo.id ) {

            // Format toast message
            var deliveriesUrl = pusherObj.siteUrl + '/store-manager/gron-vendor-delivery-notifications/'
            toastOptions.heading = "New Order!";
            toastOptions.text = "You received a new order. Please check the <a href='" + deliveriesUrl + "'>GRON - Deliveries</a>!";

            $.toast( toastOptions );

          }

        });

        break;

      case 'wcfm_delivery_boy':

        // Subscribe on 'vendor' channel
        var channel = gronPusher.subscribe('delivery-boy');

        channel.bind('new-order', function( data ) {

          if( data.boyId === pusherObj.userInfo.id ) {

            // Format toast message
            var deliveriesUrl = pusherObj.siteUrl + '/store-manager/gron-boy-delivery-requests/'
            toastOptions.heading = "New Delivery Request!";
            toastOptions.text = "You received a new delivery request. Please check the <a href='" + deliveriesUrl + "'>GRON - Deliveries</a>!";

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
