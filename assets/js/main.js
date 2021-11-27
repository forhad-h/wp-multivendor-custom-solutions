!( function( $ ) {

  $(document).ready( function() {

    var noticeElm = $('#gron-map-settings-notice');

    if( !mainObj.hasMapApiKey ) {
      noticeElm.show();
    }

  });// Document ready close

})(jQuery)
