!(function ($) {
  /* Default collection type selection */
  var deliverToHomeRadioElm = $("input[value=deliver_to_home]");

  deliverToHomeRadioElm.prop("checked", true);
  deliverToHomeRadioElm.next("label").addClass("active");

  /* Collection Type */
  var collectionTypeLabelElms = $(
    ".gron_radio_select .woocommerce-input-wrapper label"
  );

  collectionTypeLabelElms.on("click", function () {
    $(this).siblings().removeClass("active");
    $(this).addClass("active");
  });

  //

  var locationFieldElm = $("#wcfmmp_user_location");

  locationFieldElm.on("blur", gronWC_tjH2Bn_getLatLng.bind($(this), 0));

})(jQuery);

function gronWC_tjH2Bn_getLatLng(attempt) {
  var maxAttempt = 20;
  attempt++;

  $ = jQuery;

  setTimeout(function () {
    var inputLatVal = $("#wcfmmp_user_location_lat").val();
    var inputLngVal = $("#wcfmmp_user_location_lng").val();

    if (attempt > maxAttempt) return;

    if (!inputLatVal || !inputLngVal) {
      gronWC_tjH2Bn_getLatLng(attempt);
    } else {
      var vendorsInfo = gronWC.vendors_info;
      var directionsService = new google.maps.DirectionsService();
      var start = new google.maps.LatLng(inputLatVal, inputLngVal);

      // Get elements - added in woocommerce checkout page
      var vendorListElm = $(".gron_vendor_list");
      var inputWrapperElm = vendorListElm.find(".woocommerce-input-wrapper");

      // empty the input wrapper element, which has a placeholder radio field
      inputWrapperElm.empty();

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

        directionsService.route(request, function (result, status) {
          if (status === "OK") {
            var route = result.routes[0];
            var distance = route.legs[0].distance.text;

            if (distance) {
              var fieldMarkup = "";

              // Field input
              fieldMarkup += '<input type="radio" class="input-radio " value="';
              fieldMarkup += vendorInfo.vendor_id;
              fieldMarkup += '" name="gron_vendor_list" id="gron_vendor_list_';
              fieldMarkup += vendorInfo.vendor_id;
              fieldMarkup += '" checked="checked">';

              // Field label
              fieldMarkup += '<label for="gron_vendor_list_';
              fieldMarkup += vendorInfo.vendor_id;
              fieldMarkup += '" class="radio ">';
              fieldMarkup += vendorInfo.store_name 
              fieldMarkup += '<span class="gron_v_addr">' 
              fieldMarkup += vendorInfo.store_address + "</span>" 
              fieldMarkup += '<span class="gron_v_distance">' + distance + '</span>';
              fieldMarkup += "</label>";

              inputWrapperElm.append(fieldMarkup);
            }
          }
        });
      }

      // Display the vendor selection options
      vendorListElm
        .css("background-color", "rgba( 58, 181, 123, 0.5 )")
        .fadeIn(300)
        .promise()
        .done(function () {
          $(this).css("background-color", "initial");
        });
    }
  }, 101);
}
