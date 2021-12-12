!( function( $ ) {

  $(document).ready( function() {

    var noticeElm = $('#gron-map-settings-notice');

    if( !mainObj.hasMapApiKey ) {
      noticeElm.show();
    }

    /* Vendor notifications to set up shop timing and delivery slot */
    var shopTimingNotice = $('#gron-shop-timing-notice');
    var deliverySlotNotice = $('#gron-delivery-slot-notice');

    var shopTimingsCount = +$('#gron-count-shop-timings').val();
    var deliverySlotsCount = +$('#gron-count-delivery-slots').val();

    var animSpeed = 300;

    if( !shopTimingsCount ) {
      shopTimingNotice.fadeIn( animSpeed )
    }

    if( !deliverySlotsCount ) {
      deliverySlotNotice.fadeIn( animSpeed );
    }

  });// Document ready close

})(jQuery)
