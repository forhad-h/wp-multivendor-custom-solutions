
!(function( $ ) {

  $(document).ready( function() {

    var toastOptions = {
      loader   : false,
      bgColor  : '#313030',
      hideAfter: 5000
    }

    toastOptions.text = "Your toast message goes here",
    $.toast( toastOptions );


    // Initialize pusher
    var pusher = new Pusher( pusherObj.key, {
      cluster: pusherObj.cluster
    });

    switch( pusherObj.userRole ) {

      case 'administrator':

        // initialize channel for 'admin'
/*        var channel = pusher.subscribe('admin');

        channel.bind('new-order', function( data ) {
          console.log(JSON.stringify(data));
        });*/

        break;

      case 'wcfm_vendor':

        // initialize channel for 'vendor'
/*        var channel = pusher.subscribe('vendor');

        channel.bind('new-order', function( data ) {
          console.log(JSON.stringify(data));
        });*/

        break;

      case 'wcfm_delivery_boy':

        // initialize channel for 'vendor'
/*        var channel = pusher.subscribe('delivery_boy');

        channel.bind('new-order', function( data ) {
          console.log(JSON.stringify(data));
        });*/

        break;

      default:
        // do nothing for others
        return;
    }

  });

})(jQuery)
