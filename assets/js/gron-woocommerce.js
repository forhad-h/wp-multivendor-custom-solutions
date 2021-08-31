!( function($) {

  /* Collection Type */
  var collectionTypeLabelElms = $('#gron_collection_type_field .woocommerce-input-wrapper label');

  var deliverToHomeRadioElm = $('#gron_collection_type_deliver_to_home');

  deliverToHomeRadioElm.prop( 'checked', true );
  deliverToHomeRadioElm.next( 'label' ).addClass( 'active' );

  collectionTypeLabelElms.on( 'click', function() {
    collectionTypeLabelElms.removeClass( 'active' );
    $(this).addClass( 'active' );
  });

} )(jQuery)
