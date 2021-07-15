<?php
global $wpdb, $WCFM, $WCFMmp;

?>

<div class="collapse wcfm-collapse" id="gronc-geo-routes">

  <div class="wcfm-page-headig">
		<span class="wcfmfa fa-street-view"></span>
		<span class="wcfm-page-heading-text"><?php _e( 'GEO Routes', 'gron-custom' ); ?></span>
		<?php do_action( 'wcfm_page_heading' ); ?>
	</div>

	<div class="wcfm-collapse-content">

	  <div id="wcfm_page_load"></div>

		<?php do_action( 'before_gronc_geo_routes' ); ?>

		<div class="wcfm-container wcfm-top-element-container">
			<h2><?php _e('GEO Routes', 'gron-custom' ); ?></h2>
			<div class="wcfm-clearfix"></div>
		</div>

		<div class="wcfm-container">
				<div id="delay"></div>
				<div id="map"></div>
	  </div>

    <div class="gron_route_details_wrapper">

      <div class="gronc_help_text">
        <?php
          if( !IS_GRON_VENDOR ) :
        ?>
          <p>Make sure you have set your Map Default Location in
            <strong>
              <span class="wcfmfa fa-cogs"></span> Settings >
              <span class="wcfmfa fa-street-view"></span> GEO Location > Map Default Location </strong>
          </p>
        <?php
          else:
        ?>
          <p>Make sure you have set your Map Default Location in
            <strong>
              <span class="wcfmfa fa-cogs"></span> Settings >
              <span class="wcfmfa fa-globe"></span> Location > Store Location</strong>
          </p>
        <?php endif; ?>
      </div>


      <div id="gron-route-details-panel">
        <ul></ul>
      </div>

      <div id="messages"></div>
    </div>

	</div>
</div>
