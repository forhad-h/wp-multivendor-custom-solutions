!( function($) {

  /* Default collection type selection */
  var deliverToHomeRadioElm = $('input[value=deliver_to_home]');

  deliverToHomeRadioElm.prop( 'checked', true );
  deliverToHomeRadioElm.next( 'label' ).addClass( 'active' );

  /* Collection Type */
  var collectionTypeLabelElms = $('.gron_collection_type .woocommerce-input-wrapper label');

  collectionTypeLabelElms.on( 'click', function() {
    $(this).siblings().removeClass( 'active' );
    $(this).addClass( 'active' );
  });

} )(jQuery)
