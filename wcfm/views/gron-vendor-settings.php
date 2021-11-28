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
        <div class="page_collapsible" id="gron-vendor-settings-general">
          <label class="wcfmfa fa-business-time"></label>
          <?php _e('General', 'gron-custom'); ?><span></span>
        </div>

        <div class="wcfm-container">
          <div id="gron-vendor-settings-general" class="wcfm-content">
            <h2><?php _e('General Settings', 'gron-custom'); ?></h2>
            <div class="wcfm_clearfix"></div>
            <form
              id="gron-general-settings-form"
              class="wcfm"
            >

              <div class="each_field">

                <p class="wcfm_capability_optionssubmit_products-submit_products wcfm_title checkbox_title"><strong>Order Deliveries will be managed by me</strong></p>
                <label class="screen-reader-text" for="wcfm_capability_optionssubmit_products-submit_products">Order Deliveries will be managed by me</label>
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
        <div class="page_collapsible" id="gron-vendor-settings-shop-timing">
          <label class="wcfmfa fa-business-time"></label>
          <?php _e('Shop Timings', 'gron-custom'); ?><span></span>
        </div>

        <div class="wcfm-container">
          <div id="gron-vendor-settings-shop-timing" class="wcfm-content">
            <h2><?php _e('Shop Timings', 'gron-custom'); ?></h2>
            <div class="wcfm_clearfix"></div>
            <form
              id="gron-shop-timing-form"
              class="wcfm"
            >
              <table class="gron_table">
                <thead>
                  <tr>
                    <th></th>
                    <th>Day</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                  </tr>
                </thead>
                <tbody>
                <?php

                  $shop_timings = $mysql->get_shop_timings();

                ?>

                <?php
                  foreach( $shop_timings as $timing ):
                    $day = esc_html($timing->day_name);
                    $start_time =  esc_attr($timing->start_time);
                    $end_time =  esc_attr($timing->end_time);
                    $is_active =  esc_attr($timing->is_active);
                ?>
                  <tr class="gron_single_titming">
                    <td><input type="checkbox" class="wcfm-checkbox is_active" <?php echo $is_active ? 'checked' : ''; ?>></td>
                    <td class="day_name"><?php echo ucfirst( $day ); ?></td>
                    <td><input type="time" class="start_time" value="<?php echo $start_time; ?>" /></td>
                    <td><input type="time" class="end_time" value="<?php echo $end_time; ?>" /></td>
                  </tr>
                <?php endforeach; ?>
                <tbody>

              </table>
              <input type="button" name="save-data" value="Save" id="gron-shop-timings-save-button" class="wcfm_submit_button">
            </form>

          </div>
        </div>
        <div class="wcfm_clearfix"></div>
        <!-- end collapsible -->

        <!-- collapsible -->
        <div class="page_collapsible" id="gron-vendor-settings-delivery-slots">
          <label class="wcfmfa fa-clock"></label>
          <?php _e('Delivery Slots', 'gron-custom'); ?><span></span>
        </div>

        <div class="wcfm-container">
          <div id="gron-vendor-settings-delivery-slots" class="wcfm-content">
            <h2><?php _e('Delivery Slots', 'gron-custom'); ?></h2>
            <div class="wcfm_clearfix"></div>

            <div class="gron_add_slot_wrapper">
              <a
                class="add_new_wcfm_ele_dashboard text_tip gron_modal_trigger_button gron_delivery_slot_add_new_button"
                data-target="#gron-modal"
                href="#"
                data-tip="Add New"
              >
                <span class="wcfmfa fa-cube"></span>
                <span class="text">Add New</span>
              </a>
            </div>
            <div class="gron_backdrop"></div>
            <div class="gron_modal" id="gron-modal">
              <h2 class="ci_modal_title">Add new delivery slot</h2>
              <form id="gron-delivery-slots-form">

                <div class="gron_each_field">
                  <label>Time ( From )</label>
                  <input type="time" class="time_from" />
                </div>

                <div class="gron_each_field">
                  <label>Time ( To )</label>
                  <input type="time" class="time_to" />
                </div>

                <input type="button" name="save-data" value="Save" id="gron-delivery-slot-save-button" class="wcfm_submit_button" >

              </form>
            </div>
            <div class="wcfm_clearfix"></div>

            <table class="gron_table">
              <thead>
                <tr>
                  <th>Sr.</th>
                  <th>Time (From)</th>
                  <th>Time (To)</th>
                  <th>Action</th>
                </tr>
              </thead>

              <tbody id="gron-delivery-slot-table">
                <tr class="gron_each_slot" id="gron-delivery-slot-template">

                  <td class="slot_sl_no"></td>
                  <td class="slot_time_form"></td>
                  <td class="slot_time_to"></td>
                  <td>

                    <a
                      class="wcfm-action-icon gron_modal_trigger_button gron_delivery_slot_edit_button"
                      href="#"
                      data-target="#gron-modal"
                      data-slot-id=""
                      data-time-from=""
                      data-time-to=""
                    >
                      <span class="wcfmfa fa-edit text_tip" data-tip="Edit"></span>
                    </a>

                    <a
                      class="wcfm-action-icon gron_delivery_slot_delete_button"
                      href="#"
                      data-slot-id=""
                    >
                      <span class="wcfmfa fa-trash-alt text_tip" data-tip="Delete"></span>
                    </a>

                  </td>
                </tr>

                <?php
                  $delivery_slots = $mysql->get_delivery_slots();
                  $i = 0;
                  foreach( $delivery_slots as $slot ) {
                    $i++;
                    $slot_id = esc_attr( $slot->slot_id );
                    $time_from = esc_html( $slot->time_from );
                    $time_to = esc_html( $slot->time_to );
                ?>
                  <tr class="gron_each_slot">

                    <td class="slot_sl_no"><?php echo $i; ?></td>
                    <td class="slot_time_form"><?php echo Utils::time_format( $time_from ); ?></td>
                    <td class="slot_time_to"><?php echo Utils::time_format( $time_to ); ?></td>
                    <td>

                      <a
                        class="wcfm-action-icon icon-info gron_modal_trigger_button gron_delivery_slot_edit_button"
                        href="#"
                        data-target="#gron-modal"
                        data-slot-id="<?php echo $slot_id; ?>"
                        data-time-from="<?php echo $time_from; ?>"
                        data-time-to="<?php echo $time_to; ?>"
                      >
                        <span class="wcfmfa fa-edit text_tip" data-tip="Edit"></span>
                      </a>

                      <a
                        class="wcfm-action-icon icon-danger gron_delivery_slot_delete_button"
                        href="#"
                        data-slot-id="<?php echo $slot_id; ?>"
                      >
                        <span class="wcfmfa fa-trash-alt text_tip" data-tip="Delete"></span>
                      </a>

                    </td>
                  </tr>
                <?php } ?>
              </tbody>

            </table>

          </div>
        </div>
        <div class="wcfm_clearfix"></div>
        <!-- end collapsible -->


      </div>




	</div>
</div>
