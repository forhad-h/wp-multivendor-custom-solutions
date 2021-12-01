<?php

use GRON\MySQL;
use GRON\Utils;

?>
<div class="collapse wcfm-collapse" id="gron-delivery-requests">

  <div class="wcfm-page-headig">
		<span class="wcfmfa fa-street-view"></span>
		<span class="wcfm-page-heading-text"><?php _e( 'GRON Delivery Requests', 'gron-custom' ); ?></span>
		<?php do_action( 'wcfm_page_heading' ); ?>
	</div>

	<div class="wcfm-collapse-content">

	  <div id="wcfm_page_load"></div>

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

                  <td class="order"><a href="#"></a></td>
                  <td class="delivery_day"></td>
                  <td class="delivery_time"></td>
                  <td class="status"></td>

                </tr>
              </table>

              <div class="gron_table_wrapper">
                <div class="gron_table_preloader">
                  <img src="<?php echo home_url( '/wp-includes/images/spinner.gif' ); ?>"/>
                </div>
                <table id="gron-dr-pending-table" class="gron_table">

                  <thead>
                    <tr>
                      <th>Order</th>
                      <th>Delivery Day</th>
                      <th>Delivery Time</th>
                      <th>Status</th>
                    </tr>
                  </thead>

                  <tbody></tbody>

                  <tfoot>
                    <tr>
                      <th>Order</th>
                      <th>Delivery Day</th>
                      <th>Delivery Time</th>
                      <th>Status</th>
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
          <label class="wcfmfa fa-user-check"></label>
          <?php _e('Accepted', 'gron-custom'); ?><span></span>
        </div>

        <div class="wcfm-container">
          <div id="gron-dr-accepted" class="wcfm-content">
            <h2><?php _e('Accepted Delivery Requests', 'gron-custom'); ?></h2>
            <div class="wcfm_clearfix"></div>

              <table style="display:none;visibility:hidden;">
                <tr id="gron-dr-accepted-row-template">

                  <td class="order"><a href="#"></a></td>
                  <td class="delivery_day"></td>
                  <td class="delivery_time"></td>
                  <td class="accepted_by"><a href="#"></a></td>

                </tr>
              </table>

              <div class="gron_table_wrapper">
                <div class="gron_table_preloader">
                  <img src="<?php echo home_url( '/wp-includes/images/spinner.gif' ); ?>"/>
                </div>
                <table id="gron-dr-accepted-table" class="gron_table">

                  <thead>
                    <tr>
                      <th>Order</th>
                      <th>Delivery Day</th>
                      <th>Delivery Time</th>
                      <th>Accepted By</th>
                    </tr>
                  </thead>

                  <tbody></tbody>

                  <tfoot>
                    <tr>
                      <th>Order</th>
                      <th>Delivery Day</th>
                      <th>Delivery Time</th>
                      <th>Accepted By</th>
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
