jQuery(document).ready(function($) {

   var submitBtnElm = $('#gron-general-settings-save-button');
   var formElm = $('#gron-general-settings-form');

   submitBtnElm.on( 'click', function() {

     var deliveryBySellerValue = $('input[name=delivery_by_seller]:checked').val();

     // Only accept 'yes' or 'no'
     if( $.inArray( deliveryBySellerValue, [ 'yes', 'no' ] ) == -1 ) return;

     $(this).val('SAVING...');
     _this = $(this);

     var data = {
       delivery_by_seller: deliveryBySellerValue
     }
     $.ajax({
       type: "POST",
       url: wcfm_params.ajax_url,
       data: {
         action: 'wcfm_ajax_controller',
         controller: 'gron-admin-settings',
         task: 'update-general-settings',
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
