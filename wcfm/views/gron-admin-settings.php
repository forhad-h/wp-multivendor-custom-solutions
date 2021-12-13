<?php
use GRON\MySQL;
use GRON\Utils;

$mysql = new MySQL();

?>

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
        <div class="page_collapsible" id="gron-admin-settings-delivery">
          <label class="wcfmfa fa-business-time"></label>
          <?php _e('Delivery', 'gron-custom'); ?><span></span>
        </div>

        <div class="wcfm-container">
          <div id="gron-admin-settings-delivery" class="wcfm-content">
            <h2><?php _e('Delivery Settings', 'gron-custom'); ?></h2>
            <div class="wcfm_clearfix"></div>
            <form
              id="gron-general-settings-form"
              class="wcfm"
            >

              <!-- each field -->
              <div class="each_field gron_checkbox_field">

                <?php
                  $is_delivery_by_seller = Utils::is_delivery_by_seller();
                ?>
                <p class="gron_field_title"><strong>Delivery By Seller</strong></p>
                <label class="screen-reader-text">Delivery By Seller</label>

                <div class="field">
                  <label>
                    <input
                      type="radio"
                      name="delivery_by_seller"
                      class="wcfm-radio"
                      value="yes"
                      <?php echo $is_delivery_by_seller ? 'checked' : ''; ?>
                    /> <span class="radio_text">Yes</span>
                  </label>

                  <label>
                    <input
                      type="radio"
                      name="delivery_by_seller"
                      class="wcfm-radio"
                      value="no"
                      <?php echo !$is_delivery_by_seller ? 'checked' : ''; ?>
                    /> <span class="radio_text">No</span>
                  </label>
                </div>

              </div>
              <!-- each field -->

              <!-- each field -->
              <div class="each_field gron_text_field">

                <?php
                  $time_limit = Utils::get_dn_boradcast_time_limit();
                ?>

                <p class="gron_field_title"><strong>Delivery notification broadcast time limit</strong></p>
                <label class="screen-reader-text">Delivery notification broadcast time limit</label>

                <div class="field">
                  <input type="number" id="time-limit" class="wcfm-text" value="<?php echo $time_limit;?>">
                  <p class="field_desc">Provide the time limit as <strong>Minute</strong>.</p>
                </div>

              </div>
              <!-- each field -->

              <!-- each field -->
              <div class="each_field gron_multiple_checkbox_fields">

                <?php
                  $vendor_role = 'wcfm_vendor';
                  $args = array(
                    'role__in'     => array( $vendor_role ),
                    'orderby'      => 'ID',
                    'order'        => 'ASC',
                    'fields'       => "ID"
                   );

                  $vendor_ids = get_users( $args );

                  $store_names = array();

                  foreach( $vendor_ids as $vendor_id ) {
                    $store_name = get_user_meta( $vendor_id, 'store_name', true );
                    $store_names[$vendor_id] = $store_name;
                  }

                ?>

                <p class="gron_field_title"><strong>Vendor Deliveries Allowed by Admin</strong></p>
                <label class="screen-reader-text">Vendor Deliveries Allowed by Admin</label>

                <div class="fields">

                  <?php foreach( $store_names as $vendor_id => $store_name): ?>

                    <div class="each_field">



                        <input type="checkbox" id="gron-store-<?php echo $vendor_id; ?>" class="wcfm-checkbox" value="<?php echo $vendor_id;?>" />

                        <label for="gron-store-<?php echo $vendor_id; ?>"><?php echo $store_name; ?></label>



                    </div>

                  <?php endforeach;?>

                </div>

              </div>
              <!-- each field -->


              <input type="button" name="save-data" value="Save" id="gron-general-settings-save-button" class="wcfm_submit_button">
            </form>

          </div>
        </div>
        <div class="wcfm_clearfix"></div>
        <!-- end collapsible -->

        <!-- collapsible -->
        <!--<div class="page_collapsible" id="gron-admin-settings-shop">
          <label class="wcfmfa fa-clock"></label>
          <?php //_e('Shop', 'gron-custom'); ?><span></span>
        </div>

        <div class="wcfm-container">
          <div id="gron-admin-settings-shop" class="wcfm-content">
            <h2><?php //_e('Shop Settings', 'gron-custom'); ?></h2>
            <div class="wcfm_clearfix"></div>

          </div>
        </div>
        <div class="wcfm_clearfix"></div>-->
        <!-- end collapsible -->

      </div>




	</div>
</div>
