<?php
global $WCFM;

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


		<div class="wcfm_clearfix"></div><br />

			<div class="wcfm-tabWrap">

				<!-- collapsible -->
				<div class="page_collapsible" id="gronc-geo-routes-map-head">
					<label class="wcfmfa fa-map"></label>
					<?php _e('Map', 'gron-custom'); ?><span></span>
				</div>

				<div class="wcfm-container">
					<div id="gronc-geo-routes-map-expander" class="wcfm-content">

					  <h2><?php _e('Customer Routes', 'gron-custom'); ?></h2>

						<div class="wcfm_clearfix"></div>

						Map goes here...

					</div>
				</div>
				<div class="wcfm_clearfix"></div>
				<!-- end collapsible -->

					<!-- collapsible -->
					<div class="page_collapsible" id="gronc-geo-routes-settings-head">
						<label class="wcfmfa fa-chalkboard"></label>
						<?php _e('Settings', 'gron-custom'); ?><span></span>
					</div>

					<div class="wcfm-container">


							<div id="gronc-geo-routes-settings-head" class="wcfm-content">

							  <h2><?php _e('Map Settings', 'gron-custom'); ?></h2>

								<div class="wcfm_clearfix"></div>

	              <form id="gronc-geo-routes-form" class="wcfm">

									Map Settings content goes here...

									<div class="wcfm_form_simple_submit_wrapper">
									  <div class="wcfm-message" tabindex="-1"></div>

										<input type="submit" name="save-data" value="<?php _e( 'Save', 'gron-custom' ); ?>" class="wcfm_submit_button" />
									</div>

									<input type="hidden" name="wcfm_nonce" value="<?php echo wp_create_nonce( 'gronc_geo_routes' ); ?>" />

	              </form>

							</div>

					</div>
					<div class="wcfm_clearfix"></div>
					<!-- end collapsible -->

				</div>

	</div>
</div>
