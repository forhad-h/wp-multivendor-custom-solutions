<?php
global $wpdb, $WCFM, $WCFMmp;

$wcfm_marketplace_options = $WCFMmp->wcfmmp_marketplace_options;

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
        <!--<div class="page_collapsible" id="gron-delivery-shop-timing">
          <label class="wcfmfa fa-chalkboard"></label>
          <?php _e('Shop Timings', 'wc-frontend-manager'); ?><span></span>
        </div>

        <div class="wcfm-container">
          <div id="gron-delivery-shop-timing" class="wcfm-content">
            <h2><?php _e('Shop Timings', 'wc-frontend-manager'); ?></h2>
            <div class="wcfm_clearfix"></div>
            <form id="gron-shop-timing-form" class="wcfm">
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
                  $week_info = array(
                    array( 'name' => 'sunday', 'start_time' => '', 'end_time' => '' ),
                    array( 'name' => 'monday', 'start_time' => '', 'end_time' => '' ),
                    array( 'name' => 'tuesday', 'start_time' => '', 'end_time' => '' ),
                    array( 'name' => 'wednesday', 'start_time' => '', 'end_time' => '' ),
                    array( 'name' => 'thursday', 'start_time' => '', 'end_time' => '' ),
                    array( 'name' => 'friday', 'start_time' => '', 'end_time' => '' ),
                    array( 'name' => 'saturday', 'start_time' => '', 'end_time' => '' ),
                  );

                ?>

                <?php foreach( $week_info as $info ): ?>
                  <tr>
                    <td><input type="checkbox" class="wcfm-checkbox" value="sunday"></td>
                    <td><?php echo ucfirst( $info['name'] ); ?></td>
                    <td><input type="time" value="<?php echo $info['start_time']; ?>" /></td>
                    <td><input type="time" valude="<?php echo $info['end_time']; ?>" /></td>
                  </tr>
                <?php endforeach; ?>
                <tbody>

              </table>
              <input type="submit" name="save-data" value="Save" id="gron-shop-timings-save-button" class="wcfm_submit_button">
            </form>

          </div>
        </div>
        <div class="wcfm_clearfix"></div>-->
        <!-- end collapsible -->

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
                class="add_new_wcfm_ele_dashboard text_tip gron_modal_trigger_button"
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
                  <input type="time" />
                </div>

                <div class="gron_each_field">
                  <label>Time ( To )</label>
                  <input type="time" />
                </div>

                <input type="submit" name="save-data" value="Save" id="gron-delivery-slots-save-button" class="wcfm_submit_button">

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
                <tr>
                  <td>1</td>
                  <td>16:30</td>
                  <td>20:00</td>
                  <td>
                    <a class="wcfm-action-icon" href="#">
                      <span class="wcfmfa fa-edit text_tip" data-tip="Edit"></span>
                    </a>
                    <a class="wcfm-action-icon" href="#">
                      <span class="wcfmfa fa-trash-alt text_tip" data-tip="Delete" data-hasqtip="102" aria-describedby="qtip-102"></span>
                    </a>
                  </td>
                </tr>
              </tbody>

            </table>

          </div>
        </div>
        <div class="wcfm_clearfix"></div>
        <!-- end collapsible -->

      </div>




	</div>
</div>
