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
			<h2><?php _e('GRON Delivery Status', 'gron-custom' ); ?></h2>
			<div class="wcfm-clearfix"></div>

		</div>
    <div class="wcfm_clearfix"></div>
    <br>


      <div class="wcfm-tabWrap gron_tab_wrap">

        <!-- collapsible -->
        <div class="page_collapsible" id="gron-delivery-request-not-accepted">
          <label class="wcfmfa fa-user-times"></label>
          <?php _e('Not Accepted', 'gron-custom'); ?><span></span>
        </div>

        <div class="wcfm-container">
          <div id="gron-delivery-request-not-accepted" class="wcfm-content">
            <h2><?php _e('Not Accepted Delivery Requests', 'gron-custom'); ?></h2>
            <div class="wcfm_clearfix"></div>

              <table class="gron_table">
                <thead>
                  <tr>
                    <th>Vendor</th>
                    <th>Order</th>
                    <th>Delivery Day</th>
                    <th>Delivery Time</th>
                  </tr>
                </thead>
                <tbody>

                  <tr>
                    <td class="vendor_name"><?php echo 'vendor one'; ?></td>
                    <td class="order_id"><a href="#">#32</a></td>
                    <td class="delivery_day">Tuesday</td>
                    <td class="delivery_time">10:00-12:00</td>
                  </tr>

                <tbody>

              </table>

          </div>
        </div>
        <div class="wcfm_clearfix"></div>
        <!-- end collapsible -->

        <!-- collapsible -->
        <div class="page_collapsible" id="gron-delivery-requests-pending">
          <label class="wcfmfa fa-ellipsis-h"></label>
          <?php _e('Pending', 'gron-custom'); ?><span></span>
        </div>

        <div class="wcfm-container">
          <div id="gron-delivery-requests-pending" class="wcfm-content">
            <h2><?php _e('Pending Delivery Requests', 'gron-custom'); ?></h2>
            <div class="wcfm_clearfix"></div>

            <table class="gron_table">
              <thead>
                <tr>
                  <th>Vendor</th>
                  <th>Order</th>
                  <th>Delivery Day</th>
                  <th>Delivery Time</th>
                </tr>
              </thead>
              <tbody>

                <tr>
                  <td class="vendor_name"><?php echo 'vendor one'; ?></td>
                  <td class="order_id"><a href="#">#32</a></td>
                  <td class="delivery_day">Tuesday</td>
                  <td class="delivery_time">10:00-12:00</td>
                </tr>

              <tbody>

            </table>

          </div>
        </div>
        <div class="wcfm_clearfix"></div>
        <!-- end collapsible -->

        <!-- collapsible -->
        <div class="page_collapsible" id="gron-delivery-requests-accepted">
          <label class="wcfmfa fa-user-check"></label>
          <?php _e('Accepted', 'gron-custom'); ?><span></span>
        </div>

        <div class="wcfm-container">
          <div id="gron-delivery-requests-accepted" class="wcfm-content">
            <h2><?php _e('Accepted Delivery Requests', 'gron-custom'); ?></h2>
            <div class="wcfm_clearfix"></div>

            <table class="gron_table">
              <thead>
                <tr>
                  <th>Vendor</th>
                  <th>Order</th>
                  <th>Delivery Day</th>
                  <th>Delivery Time</th>
                  <th>Accepted By</th>
                </tr>
              </thead>
              <tbody>

                <tr>
                  <td class="vendor_name"><?php echo 'vendor one'; ?></td>
                  <td class="order_id"><a href="#">#32</a></td>
                  <td class="delivery_day">Tuesday</td>
                  <td class="delivery_time">10:00-12:00</td>
                  <td class="delivery_time"><a href="#">delivery_boy_one</a></td>
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
