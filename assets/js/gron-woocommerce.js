!(function ($) {

  /* 
    Normally it's not possible to append gron fields at the end of billing fields.
    Because the google map player is not following the woocommerce priority rule.
    So move all gron fields with following code.
  */
  $('.woocommerce-billing-fields').append( '<div class="gron_fields_box"></div>');
  $('.gron_field_wrapper').each( function() {
    $('.woocommerce-billing-fields .gron_fields_box').append( $(this) );
  })

  /* Default collection type selection */
  var deliverToHomeRadioElm = $("input[value=self_collection]");

  deliverToHomeRadioElm.prop("checked", true);
  deliverToHomeRadioElm.next("label").addClass("active");

  /* Radio Type Selection */

  $('.gron_radio_select .woocommerce-input-wrapper').on("click", 'label', function () {
    $(this).siblings().removeClass("active");
    $(this).addClass("active");
  });

  // show collection type based on vendor
  $('.gron_vendor_list').on('change', '.input-radio', function() {

    var vendorId = $(this).val();

    
  });

  // show delivery day and time based on collection type
  $('.gron_collection_type').on('change', '.input-radio', function() {

    var deliveryDayElm = $('.gron_deliver_day');
    var deliveryTimeElm = $('.gron_deliver_time');

    if( $(this).val() === 'self_collection' ) {

      deliveryDayElm.fadeIn(300).css('display', 'inline-block');
      deliveryTimeElm.fadeIn(300).css('display', 'inline-block');

    }else {

      deliveryDayElm.fadeOut(150);
      deliveryTimeElm.fadeOut(150);

    }

  })

  // show vendor list based on location
  var locationFieldElm = $("#wcfmmp_user_location");
  locationFieldElm.on("blur", gronWC_tjH2Bn_getLatLng.bind($(this), 0));

})(jQuery);

$ = jQuery;

function gronWC_tjH2Bn_getLatLng(attempt) {
  var maxAttempt = 20;
  attempt++;

  setTimeout(function () {
    if (attempt > maxAttempt) return;

    // check if the location updated completely
    if( !gronWC_tjH2Bn_location_updated() ) {
      gronWC_tjH2Bn_getLatLng(attempt);
    } else {
      var inputLocationField = $('#wcfmmp_user_location');
      var inputLatField = $("#wcfmmp_user_location_lat");
      var inputLngField = $("#wcfmmp_user_location_lng");

      // Set location value into a text field
      gronWC_tjH2Bn_set_location_data( {
        location: inputLocationField.val(),
        lat: inputLatField.val(),
        lng: inputLngField.val()
      } );


      // empty the input wrapper element, which has a placeholder radio field
      var vendorListElm = $(".gron_vendor_list");
      var inputWrapperElm = vendorListElm.find(".woocommerce-input-wrapper");
      inputWrapperElm.empty();


      var vendorsInfo = gronWC.vendors_info;
      var start = new google.maps.LatLng(inputLatField.val(), inputLngField.val());
      var vendorInfoWithDistance = [];

      for (var i = 0; i < vendorsInfo.length; i++) {
        var vendorInfo = vendorsInfo[i];

        var storeLat = vendorInfo.store_latitude;
        var storeLng = vendorInfo.store_longitude;

        if (storeLat === null || storeLng === null) continue;

        var end = new google.maps.LatLng(storeLat, storeLng);

        var request = {
          origin: start,
          destination: end,
          travelMode: "DRIVING",
        };

        vendorInfoWithDistance.push(
          gronWC_tjH2Bn_vendor_info_with_distance({
            vendorInfo: vendorInfo,
            request: request,
          })
        );
        
      }

      Promise.all( vendorInfoWithDistance ).then( function(vendorsInfo) {

        vendorsInfo.sort( function( a, b) {
          var firstItem = a.distance.replace(/\D/g, '');
          var secondItem = b.distance.replace(/\D/g, '');
          return firstItem - secondItem;
        });

        for (var i = 0; i < vendorsInfo.length; i++) {

          var vendorInfo = vendorsInfo[i];

          gronWC_tjH2Bn_render_vendor_list({

            vendorInfo: vendorInfo,
            isActive: i === 0 ? true : false

          });

        }

        gronWC_tjH2Bn_render_gron_fields_box();
        vendorListElm.fadeIn(300)

      });



    }
  }, 301);
}

function gronWC_tjH2Bn_vendor_info_with_distance(params) {

  var vendorInfo = params.vendorInfo;
  var request = params.request;
  var directionsService = new google.maps.DirectionsService();

  return new Promise( function(resolve, reject) {

    directionsService.route(request, function (result, status) {

      if (status === "OK") {
  
        var route = result.routes[0];
  
        var distance = route.legs[0].distance.text;
  
        if(distance) {
          vendorInfo.distance = distance;
        }
  
        resolve(vendorInfo);
  
      }else {
        reject('Something went wrong during get distance.')
      }
    });

  });
  
}

function gronWC_tjH2Bn_render_vendor_list(params) {

  var vendorInfo = params.vendorInfo;
  var isActive = params.isActive;
  var inputWrapperElm = $(".gron_vendor_list .woocommerce-input-wrapper");

  var fieldMarkup = "";

  // Field input
  fieldMarkup += '<input type="radio" class="input-radio " value="';
  fieldMarkup += vendorInfo.vendor_id;
  fieldMarkup += '" name="gron_vendor" id="gron_vendor_list_' + vendorInfo.vendor_id + '"';
  fieldMarkup += isActive ? ' checked="checked"' : '';
  fieldMarkup += ">"

  // Field label
  fieldMarkup += '<label for="gron_vendor_list_';
  fieldMarkup += vendorInfo.vendor_id;
  fieldMarkup += '" class="radio';
  fieldMarkup += isActive ? ' active' : '';
  fieldMarkup += '">';
  fieldMarkup += '<span class="gron_v_name">';
  fieldMarkup += vendorInfo.store_name;
  fieldMarkup += "</span> ";
  fieldMarkup += ' <span class="gron_v_addr">';
  fieldMarkup += vendorInfo.store_address + "</span> ";
  fieldMarkup += ' <span class="gron_v_distance">' + vendorInfo.distance + "</span>";
  fieldMarkup += "</label>";

  inputWrapperElm.append(fieldMarkup);

}

function gronWC_tjH2Bn_render_gron_fields_box() {
  
  // Display the vendor selection options
  $('.gron_fields_box')
  .css("background-color", "rgba( 58, 181, 123, 0.5 )")
  .fadeIn(300)
  .promise()
  .done(function () {

    $(this).css("background-color", "initial");

    var firstVendorElm = $('.gron_vendor_list .input-radio:first');
    
    firstVendorElm.prop("checked", true);
    firstVendorElm.next('label').addClass('active');

    var collectionTypeElm = $('.gron_collection_type');

    collectionTypeElm.fadeIn(300)

    var deliveryDayElm = $('.gron_deliver_day');
    var deliveryTimeElm = $('.gron_deliver_time');

    deliveryDayElm.fadeIn(300).css('display', 'inline-block');
    deliveryTimeElm.fadeIn(300).css('display', 'inline-block');
    
  });
}

function gronWC_tjH2Bn_set_location_data( params ) {

  

  // set location into a text field to compare for multiple input
  var gronLocationField = $('#gron_delivery_location_data');
  gronLocationField.val( JSON.stringify( params ) );

}

function gronWC_tjH2Bn_location_updated() {
  var locationValue = $('#gron_delivery_location_data').val();
  var locationData = JSON.parse( locationValue ? locationValue : '{}' );

  var savedLocation = locationData.location;
  var inputLocation = $('#wcfmmp_user_location').val();

  var savedLat = locationData.lat ? locationData.lat : "";
  var inputLat = $("#wcfmmp_user_location_lat").val();


  var savedLng = locationData.lng ? locationData.lng : "";
  var inputLng = $("#wcfmmp_user_location_lng").val();

  // check if location changed
  if( savedLocation !== inputLocation ) {
    // check if latitude and longitude updated
    // as it's an asynchronous operation
    return savedLat !== inputLat || savedLng !== inputLng;
  };

  return true;
}

