// Make pusher available for all scripts
var gronPusher = new Pusher( pusherObj.key, {
  cluster: pusherObj.cluster
});

!(function( $ ) {

  $(document).ready( function() {

    var pusher = new Pusher( pusherObj.key, {
      cluster: pusherObj.cluster
    });

    var channel = pusher.subscribe('my-channel');

    channel.bind('my-event', function(data) {
      alert(JSON.stringify(data));
    });

    // Global Options for toast notification
    var toastOptions = {
      loader   : false,
      bgColor  : '#313030',
      hideAfter: 5000
    }

    switch( pusherObj.userRole ) {

      case 'administrator':

        // Subscribe on 'admin' channel
        var channel = gronPusher.subscribe('admin');

        channel.bind('new-order', function( data ) {
          console.log( "Data: inside amdin:", data );
          toastOptions.text = "New order created!",
          $.toast( toastOptions );
        });

        break;

      case 'wcfm_vendor':

        // Subscribe on 'vendor' channel
        var channel = gronPusher.subscribe('vendor');

        channel.bind('new-order', function( data ) {
          console.log(JSON.stringify(data));
        });

        break;

      case 'wcfm_delivery_boy':

        // Subscribe on 'vendor' channel
        var channel = gronPusher.subscribe('delivery-boy');

        channel.bind('new-order', function( data ) {
          console.log( "Data inside boy:", data );
          toastOptions.text = "New order created!",
          $.toast( toastOptions );
        });

        break;

      default:
        // do nothing for others
        return;
    }

  });

})(jQuery)
