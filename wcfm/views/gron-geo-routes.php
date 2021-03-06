<?php
use GRON\Notice;
global $wpdb, $WCFM, $WCFMmp;

$wcfm_marketplace_options = $WCFMmp->wcfmmp_marketplace_options;
$wcfm_google_map_api = isset( $wcfm_marketplace_options['wcfm_google_map_api'] ) ? $wcfm_marketplace_options['wcfm_google_map_api'] : '';

?>

<div class="collapse wcfm-collapse" id="gron-geo-routes">

  <div class="wcfm-page-headig">
		<span class="wcfmfa fa-street-view"></span>
		<span class="wcfm-page-heading-text"><?php _e( 'GEO Routes', 'gron-custom' ); ?></span>
		<?php do_action( 'wcfm_page_heading' ); ?>
	</div>

	<div class="wcfm-collapse-content">

	  <div id="wcfm_page_load"></div>

      <?php
        $notice = new Notice();
        echo $notice->vendor_geo_routes();
      ?>

		<?php do_action( 'before_gron_geo_routes' ); ?>

		<div class="wcfm-container wcfm-top-element-container">
			<h2><?php _e('GEO Routes', 'gron-custom' ); ?></h2>
			<div class="wcfm-clearfix"></div>
		</div>

		<div class="wcfm-container">
				<!--<div id="delay"></div>-->
				<div id="map"></div>
	  </div>

    <div class="gron_route_details_wrapper">
      <div class="gron_route_filter_options">
        <label for="gron-route-travel-mode">Travel Mode: </label>
        <select id="gron-route-travel-mode">
          <option value="DRIVING">Driving</option>
          <option value="WALKING">Walking</option>
          <option value="BICYCLING">Bicycling</option>
          <option value="TRANSIT">Transit</option>
        </select>

      </div>

      <div id="gron-route-details-panel">
        <ul></ul>
      </div>

      <!-- <div id="messages"></div> -->
    </div>

	</div>
</div>
