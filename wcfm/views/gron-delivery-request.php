<?php

use GRON\MySQL;
use GRON\Utils;

global $wpdb, $WCFM, $WCFMmp;

$wcfm_marketplace_options = $WCFMmp->wcfmmp_marketplace_options;

$crud_opearation = new MySQL();

?>

<div class="collapse wcfm-collapse" id="gron-delivery-requests">

  <div class="wcfm-page-headig">
		<span class="wcfmfa fa-street-view"></span>
		<span class="wcfm-page-heading-text"><?php _e( 'GRON Delivery Requests', 'gron-custom' ); ?></span>
		<?php do_action( 'wcfm_page_heading' ); ?>
	</div>

	<div class="wcfm-collapse-content">

	  <div id="wcfm_page_load"></div>

		<?php do_action( 'before_gron_geo_routes' ); ?>

		<div class="wcfm-container wcfm-top-element-container">
			<h2><?php _e('GRON Delivery Requests', 'gron-custom' ); ?></h2>
			<div class="wcfm-clearfix"></div>

		</div>
    <div class="wcfm_clearfix"></div>
    <br>


      <div class="wcfm-tabWrap gron_tab_wrap">

        <!-- collapsible -->
        <div class="page_collapsible" id="gron-delivery-requests">
          <label class="wcfmfa fa-ellipsis-h"></label>
          <?php _e('Pending', 'gron-custom'); ?><span></span>
        </div>

        <div class="wcfm-container">
          <div id="gron-delivery-requests" class="wcfm-content">
            <h2><?php _e('Pending Requests', 'gron-custom'); ?></h2>
            <div class="wcfm_clearfix"></div>

              <table class="gron_table">
                <thead>
                  <tr>
                    <th></th>
                    <th>Vendor</th>
                    <th>Vendor Address</th>
                    <th>Delivery Address</th>
                    <th>Delivery Time</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>

                  <tr>

                    <td><input type="checkbox" class="wcfm-checkbox is_active" /></td>
                    <td class="vendor_name"><?php echo 'vendor one'; ?></td>
                    <td class="vendor_address"><?php echo 'something at, Malaysia'; ?></td>
                    <td class="delivery_address"><?php echo 'something at, Malaysia'; ?></td>
                    <td class="delivery_time"><?php echo '9:00am-12:00am'; ?></td>
                    <td>

                      <a
                        class="wcfm-action-icon icon-success"
                        href="#"
                      >
                        <span class="wcfmfa fa-check text_tip" data-tip="Accept"></span>
                      </a>

                      <a
                        class="wcfm-action-icon icon-danger"
                        href="#"
                      >
                        <span class="wcfmfa fa-times text_tip" data-tip="Reject"></span>
                      </a>

                    </td>

                  </tr>

                <tbody>

              </table>

          </div>
        </div>
        <div class="wcfm_clearfix"></div>
        <!-- end collapsible -->

        <!-- collapsible -->
        <div class="page_collapsible" id="gron-delivery-slots">
          <label class="wcfmfa fa-user-check"></label>
          <?php _e('Accepted', 'gron-custom'); ?><span></span>
        </div>

        <div class="wcfm-container">
          <div id="gron-delivery-slots" class="wcfm-content">
            <h2><?php _e('Delivery Slots', 'gron-custom'); ?></h2>
            <div class="wcfm_clearfix"></div>

              <table class="gron_table">
                <thead>
                  <tr>
                    <th>Vendor</th>
                    <th>Vendor Address</th>
                    <th>Delivery Address</th>
                    <th>Delivery Time</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>

                  <tr>

                    <td class="vendor_name"><?php echo 'vendor one'; ?></td>
                    <td class="vendor_address"><?php echo 'something at, Malaysia'; ?></td>
                    <td class="delivery_address"><?php echo 'something at, Malaysia'; ?></td>
                    <td class="delivery_time"><?php echo '9:00am-12:00am'; ?></td>
                    <td>

                      <a
                        class="wcfm-action-icon icon-danger"
                        href="#"
                      >
                        <span class="wcfmfa fa-times text_tip" data-tip="Cancel"></span>
                      </a>

                    </td>

                  </tr>

                <tbody>

              </table>

          </div>
        </div>
        <div class="wcfm_clearfix"></div>
        <!-- end collapsible -->


      </div>




	</div>
</div>
