<?php
global $WCFM;

$user_id = $WCFM->wcfm_marketplace ? $WCFM->wcfm_marketplace->vendor_id : get_current_user_id();

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


		<div class="wcfm_clearfix"></div><br />

			<div class="wcfm-tabWrap">

				<!-- collapsible -->
			<!--	<div class="page_collapsible" id="gronc-geo-routes-details-head">
					<label class="wcfmfa fa-map"></label>
					<?php _e('Details', 'gron-custom'); ?><span></span>
				</div>

				<div class="wcfm-container">
					<div id="gronc-geo-routes-details-expander" class="wcfm-content">

					  <h2><?php _e('Customer Routes', 'gron-custom'); ?></h2>
						<div class="wcfm_clearfix"></div>

						<div id="messages"></div>
						<div id="directions-panel"></div>

					</div>
				</div>
				<div class="wcfm_clearfix"></div>-->
				<!-- end collapsible -->

					<!-- collapsible -->
					<div class="page_collapsible" id="gronc-geo-routes-settings-head">
						<label class="wcfmfa fa-cogs"></label>
						<?php _e('Settings', 'gron-custom'); ?><span></span>
					</div>

					<div class="wcfm-container">


							<div id="gronc-geo-routes-settings-head" class="wcfm-content">

							  <h2><?php _e('Map Settings', 'gron-custom'); ?></h2>

								<div class="wcfm_clearfix"></div>

    	              <form id="gronc-geo-routes-admin-settings-form" class="wcfm">

                      <?php if( current_user_can('manage_options') ):?>
      									<p class="wcfm_title"><strong>Google Map API Key</strong></p>
      									<label class="screen-reader-text">Google Map API Key</label>
      									<input type="text" class="wcfm-text" id="gron-google-map-api-key" placeholder="Google Map API Key goes here...">
                      <?php endif;?>


                      <p class="wcfm_title"><strong>Address line 1</strong></p>
                      <label class="screen-reader-text">Address line 1</label>
                      <input type="text" class="wcfm-text" id="gron-address-line-1" placeholder="Northern Corridor Economic Region (NCER) No.82">

                      <p class="wcfm_title"><strong>Address line 2</strong></p>
                      <label class="screen-reader-text">Address line 2</label>
                      <input type="text" class="wcfm-text" id="gron-address-line-2" placeholder="Jalan Villa Mutiara, Pearl City Centre">

                      <p class="wcfm_title"><strong>Post Code</strong></p>
                      <label class="screen-reader-text">Post Code</label>
                      <input type="text" class="wcfm-text" id="gron-post-code" placeholder="14100">

                      <p class="wcfm_title"><strong>City</strong></p>
                      <label class="screen-reader-text">City</label>
                      <input type="text" class="wcfm-text" id="gron-city" placeholder="Simpang Ampat">

                      <p class="wcfm_title"><strong>State</strong></p>
                      <label class="screen-reader-text">State</label>
                      <input type="text" class="wcfm-text" id="gron-state" placeholder="Penang">



    									<div class="wcfm_form_simple_submit_wrapper">
    									  <div class="wcfm-message" tabindex="-1"></div>

    										<input type="submit" value="<?php _e( 'Save', 'gron-custom' ); ?>" class="wcfm_submit_button" />
    									</div>

    									<input type="hidden" value="<?php echo wp_create_nonce( 'gronc_geo_routes' ); ?>" />

    	              </form>

							</div>

					</div>
					<div class="wcfm_clearfix"></div>
					<!-- end collapsible -->

				</div>

	</div>
</div>
