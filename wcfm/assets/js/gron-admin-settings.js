jQuery(document).ready(function($) {

   var submitBtnElm = $('#gron-general-settings-save-button');
   var formElm = $('#gron-general-settings-form');

   submitBtnElm.on( 'click', function() {

     var deliveryBySellerValue = $('input[name=delivery_by_seller]:checked').val();
     var broadcastTimeLimitValue = $('#time-limit').val();

     // Only accept 'yes' or 'no' for delivery by seller value
     if( $.inArray( deliveryBySellerValue, [ 'yes', 'no' ] ) == -1 ) return;

     // Only accept number for broadcast time limit
     if( !$.isNumeric( broadcastTimeLimitValue ) ) return;

     $(this).val('SAVING...');
     _this = $(this);

     var storeListElm = $("#gron-store-list");

     var allowedVendorIds = [];
     storeListElm.find('input[type=checkbox]:checked').each(function(i){
       allowedVendorIds[i] = +$(this).val();
     });

     var data = {
       _gron_delivery_by_seller: deliveryBySellerValue,
       _gron_dn_broadcast_time_limit: broadcastTimeLimitValue,
       _gron_delivery_allowed_vendors: allowedVendorIds.length ? allowedVendorIds : [0]
     }

     $.ajax({
       type: "POST",
       url: wcfm_params.ajax_url,
       data: {
         action: 'wcfm_ajax_controller',
         controller: 'gron-admin-settings',
         task: 'update-delivery-settings',
         data: data
       }
     })
     .done( function (res) {
       //console.log( res );
     })
     .fail( function ( err ) {
       console.log( err );
     })
     .always( function() {
       _this.val('SAVE');
     });

   });

} );
