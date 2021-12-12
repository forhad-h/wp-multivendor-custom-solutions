!( function( $ ) {

  $(document).ready( function() {

    // Admin notice for set up map API
    var noticeElm = $('#gron-map-settings-notice');

    var animSpeed = 300;

    if( !mainObj.hasMapApiKey ) {
      noticeElm.fadeIn( animSpeed );
    }

    /* Vendor notifications to set up shop timing and delivery slot */
    var shopTimingNotice = $('#gron-shop-timing-notice');
    var deliverySlotNotice = $('#gron-delivery-slot-notice');

    var shopTimingsCount = +$('#gron-count-shop-timings').val();
    var deliverySlotsCount = +$('#gron-count-delivery-slots').val();

    if( !shopTimingsCount ) {
      shopTimingNotice.fadeIn( animSpeed )
    }

    if( !deliverySlotsCount ) {
      deliverySlotNotice.fadeIn( animSpeed );
    }

  });// Document ready close

})(jQuery)
