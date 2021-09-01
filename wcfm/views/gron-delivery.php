<?php
use GRON\DB;
include_once GRON_DIR_PATH . 'utils.php';

global $wpdb, $WCFM, $WCFMmp;

$wcfm_marketplace_options = $WCFMmp->wcfmmp_marketplace_options;

$grondb = new DB();


?>

<div class="collapse wcfm-collapse" id="gron-geo-routes">

  <div class="wcfm-page-headig">
		<span class="wcfmfa fa-street-view"></span>
		<span class="wcfm-page-heading-text"><?php _e( 'Delivery Options', 'gron-custom' ); ?></span>
		<?php do_action( 'wcfm_page_heading' ); ?>
	</div>

	<div class="wcfm-collapse-content">

	  <div id="wcfm_page_load"></div>

		<?php do_action( 'before_gron_geo_routes' ); ?>

		<div class="wcfm-container wcfm-top-element-container">
			<h2><?php _e('Delivery Options', 'gron-custom' ); ?></h2>
			<div class="wcfm-clearfix"></div>

		</div>
    <div class="wcfm_clearfix"></div>
    <br>


      <div class="wcfm-tabWrap">

        <!-- collapsible -->
        <div class="page_collapsible" id="gron-delivery-slots">
          <label class="wcfmfa fa-chalkboard"></label>
          <?php _e('Delivery Slots', 'wc-frontend-manager'); ?><span></span>
        </div>

        <div class="wcfm-container">
          <div id="gron-delivery-slots" class="wcfm-content">
            <h2><?php _e('Delivery Slots', 'wc-frontend-manager'); ?></h2>
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

              <tbody>
                <?php
                  $delivery_slots = $grondb->get_delivery_slots();
                  $i = 0;
                  foreach( $delivery_slots as $slot ) {
                    $i++;
                    $id = esc_attr( $slot->id );
                    $time_from = esc_html( $slot->time_from );
                    $time_to = esc_html( $slot->time_to );
                ?>
                  <tr>

                    <td><?php echo $i; ?></td>
                    <td><?php echo gron_time_format( $time_from ); ?></td>
                    <td><?php echo gron_time_format( $time_to ); ?></td>
                    <td>

                      <a
                        class="wcfm-action-icon gron_modal_trigger_button gron_delivery_slot_edit_button"
                        href="#"
                        data-target="#gron-modal"
                        data-slot-id="<?php echo $id; ?>"
                        data-time-from="<?php echo $time_from; ?>"
                        data-time-to="<?php echo $time_to; ?>"
                      >
                        <span class="wcfmfa fa-edit text_tip" data-tip="Edit"></span>
                      </a>

                      <a
                        class="wcfm-action-icon gron_delivery_slot_delete_button"
                        href="#"
                        data-slot-id="<?php echo $id; ?>"
                      >
                        <span class="wcfmfa fa-trash-alt text_tip" data-tip="Delete" data-hasqtip="102" aria-describedby="qtip-102"></span>
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

        <!-- collapsible -->
        <div class="page_collapsible" id="gron-delivery-shop-timing">
          <label class="wcfmfa fa-chalkboard"></label>
          <?php _e('Shop Timings', 'wc-frontend-manager'); ?><span></span>
        </div>

        <div class="wcfm-container">
          <div id="gron-delivery-shop-timing" class="wcfm-content">
            <h2><?php _e('Shop Timings', 'wc-frontend-manager'); ?></h2>
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

                  $shop_timings = $grondb->get_shop_timings();

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

      </div>




	</div>
</div>
