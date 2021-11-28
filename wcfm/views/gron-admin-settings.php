<?php
use GRON\MySQL;
use GRON\Utils;

global $wpdb, $WCFM, $WCFMmp;

$wcfm_marketplace_options = $WCFMmp->wcfmmp_marketplace_options;

$mysql = new MySQL();

// Load stylesheet for checkbox off-on style
wp_enqueue_style( 'gron-wcfm-checkbox-offon-style' );

?>
<input type="hidden" id="gron-count-shop-timings" value="<?php echo $mysql->count_shop_timings(); ?>" />
<input type="hidden" id="gron-count-delivery-slots" value="<?php echo $mysql->count_delivery_slots(); ?>" />

<div class="collapse wcfm-collapse" id="gron-vendor-settings">

  <div class="wcfm-page-headig">
		<span class="wcfmfa fa-street-view"></span>
		<span class="wcfm-page-heading-text"><?php _e( 'GRON Settings', 'gron-custom' ); ?></span>
		<?php do_action( 'wcfm_page_heading' ); ?>
	</div>

	<div class="wcfm-collapse-content">

	  <div id="wcfm_page_load"></div>

		<?php do_action( 'before_gron_geo_routes' ); ?>

		<div class="wcfm-container wcfm-top-element-container">
			<h2><?php _e('GRON Settings', 'gron-custom' ); ?></h2>
			<div class="wcfm-clearfix"></div>

		</div>
    <div class="wcfm_clearfix"></div>
    <br>


      <div class="wcfm-tabWrap gron_tab_wrap">

        <!-- collapsible -->
        <div class="page_collapsible" id="gron-admin-settings-general">
          <label class="wcfmfa fa-business-time"></label>
          <?php _e('General', 'gron-custom'); ?><span></span>
        </div>

        <div class="wcfm-container">
          <div id="gron-admin-settings-general" class="wcfm-content">
            <h2><?php _e('General Settings', 'gron-custom'); ?></h2>
            <div class="wcfm_clearfix"></div>
            <form
              id="gron-general-settings-from"
              class="wcfm"
            >

              <div class="each_field">

                <p class="wcfm_capability_optionssubmit_products-submit_products wcfm_title checkbox_title"><strong>Delivery By Seller</strong></p>
                <label class="screen-reader-text" for="wcfm_capability_optionssubmit_products-submit_products">Delivery By Seller</label>
                <div class="onoffswitch  wcfm_capability_optionssubmit_products-submit_products_wrapper">
                   <input type="checkbox" id="wcfm_capability_optionssubmit_products-submit_products" name="wcfm_capability_options[submit_products]" class="wcfm-checkbox wcfm_ele onoffswitch-checkbox" value="yes">
                   <label class="onoffswitch-label" for="wcfm_capability_optionssubmit_products-submit_products">
        					   <span class="onoffswitch-inner"></span>
        						 <span class="onoffswitch-switch"></span>
        					</label>
                </div>

              </div>


              <input type="button" name="save-data" value="Save" id="gron-general-settings-save-button" class="wcfm_submit_button">
            </form>

          </div>
        </div>
        <div class="wcfm_clearfix"></div>
        <!-- end collapsible -->

        <!-- collapsible -->
        <div class="page_collapsible" id="gron-admin-settings-shop">
          <label class="wcfmfa fa-clock"></label>
          <?php _e('Shop', 'gron-custom'); ?><span></span>
        </div>

        <div class="wcfm-container">
          <div id="gron-admin-settings-shop" class="wcfm-content">
            <h2><?php _e('Shop Settings', 'gron-custom'); ?></h2>
            <div class="wcfm_clearfix"></div>

          </div>
        </div>
        <div class="wcfm_clearfix"></div>
        <!-- end collapsible -->


      </div>




	</div>
</div>
