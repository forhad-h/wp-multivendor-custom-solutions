
!(function( $ ) {

  $(document).ready( function() {

    var pusher = new Pusher( GRON.pusherKey, {
      cluster: GRON.pusherCluster
    });

    var channel = pusher.subscribe('my-channel');

    channel.bind('my-event', function(data) {
      alert(JSON.stringify(data));
    });

  });

})(jQuery)
