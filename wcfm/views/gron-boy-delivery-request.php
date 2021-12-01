<?php

use GRON\MySQL;
use GRON\Utils;

global $wpdb, $WCFM, $WCFMmp;

$wcfm_marketplace_options = $WCFMmp->wcfmmp_marketplace_options;

$crud_opearation = new MySQL();

$current_user_id = get_current_user_id();

?>

<input type="hidden" value="<?php echo $current_user_id; ?>" id="current-user-id" />

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
        <div class="page_collapsible" id="gron-dr-pending">
          <label class="wcfmfa fa-ellipsis-h"></label>
          <?php _e('Pending', 'gron-custom'); ?><span></span>
        </div>

        <div class="wcfm-container">
          <div id="gron-dr-pending" class="wcfm-content">
            <h2><?php _e('Pending Delivery Requests', 'gron-custom'); ?></h2>
            <div class="wcfm_clearfix"></div>

              <table style="display:none;visibility:hidden;">
                <tr id="gron-dr-pending-row-template">

                  <td class="store"></td>
                  <td class="order"><a href="#"></a></td>
                  <td class="delivery_day"></td>
                  <td class="delivery_time"></td>
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
              </table>

              <div class="gron_table_wrapper">
                <div class="gron_table_preloader">
                  <img src="<?php echo home_url( '/wp-includes/images/spinner.gif' ); ?>"/>
                </div>
                <table id="gron-dr-pending-table" class="gron_table">

                  <thead>
                    <tr>
                      <th>Store</th>
                      <th>Order</th>
                      <th>Delivery Day</th>
                      <th>Delivery Time</th>
                      <th>Action</th>
                    </tr>
                  </thead>

                  <tbody></tbody>

                  <tfoot>
                    <tr>
                      <th>Store</th>
                      <th>Order</th>
                      <th>Delivery Day</th>
                      <th>Delivery Time</th>
                      <th>Action</th>
                    </tr>
                  </tfoot>

                </table>
              </div>

          </div>
        </div>
        <div class="wcfm_clearfix"></div>
        <!-- end collapsible -->

        <!-- collapsible -->
        <div class="page_collapsible" id="gron-dr-accepted">
          <label class="wcfmfa fa-ellipsis-h"></label>
          <?php _e('Accepted', 'gron-custom'); ?><span></span>
        </div>

        <div class="wcfm-container">
          <div id="gron-dr-accepted" class="wcfm-content">
            <h2><?php _e('Accepted Delivery Requests', 'gron-custom'); ?></h2>
            <div class="wcfm_clearfix"></div>

              <table style="display:none;visibility:hidden;">
                <tr id="gron-dr-accepted-row-template">

                  <td class="store"></td>
                  <td class="order"><a href="#"></a></td>
                  <td class="delivery_day"></td>
                  <td class="delivery_time"></td>
                  <td>

                    <a
                      class="wcfm-action-icon icon-danger"
                      href="#"
                    >
                      <span class="wcfmfa fa-times text_tip" data-tip="Reject"></span>
                    </a>

                  </td>

                </tr>
              </table>

              <div class="gron_table_wrapper">
                <div class="gron_table_preloader">
                  <img src="<?php echo home_url( '/wp-includes/images/spinner.gif' ); ?>"/>
                </div>
                <table id="gron-dr-accepted-table" class="gron_table">

                  <thead>
                    <tr>
                      <th>Store</th>
                      <th>Order</th>
                      <th>Delivery Day</th>
                      <th>Delivery Time</th>
                      <th>Action</th>
                    </tr>
                  </thead>

                  <tbody></tbody>

                  <tfoot>
                    <tr>
                      <th>Store</th>
                      <th>Order</th>
                      <th>Delivery Day</th>
                      <th>Delivery Time</th>
                      <th>Action</th>
                    </tr>
                  </tfoot>

                </table>
              </div>

          </div>
        </div>
        <div class="wcfm_clearfix"></div>
        <!-- end collapsible -->

      </div>

	</div>
</div>
