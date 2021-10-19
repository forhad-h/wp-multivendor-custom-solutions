<?php

/**
 * Add date and time slot field in woocommerce
 * @version 2.0.0
*/
namespace GRON;

defined('ABSPATH') or exit;

use GRON\DB;
use GRON\Utils;

/**
* GRON_WooCommerce - Woocommerce Implementation in GRON
* @access public
*/

class GRON_WooCommerce {

  /** @var DB $db instance of GRON\DB */
  private $db;

  /**
  * consturct function of GRON_WooCommerce
  * Initialize Database connection
  * Use woocommerce and WCFM hooks
  ** 'woocommerce_billing_fields' - filter hook to add extra fields
  ** 'woocommerce_checkout_update_order_meta' (action hook) - update custom fields value
  ** 'wcfm_is_allow_order_data_after_billing_address' (filter hook) - allow custom data in WCFM order details page
  ** 'woocommerce_admin_order_data_after_billing_address' (action hook) - show custom fields data in order details in admin panel
  ** 'woocommerce_checkout_create_order' (action hook) - Provide notification to delivery guys after order completed
  * @access public
  * @return void
  */
  public function __construct() {

    $this->db = new DB();
    add_filter( 'woocommerce_billing_fields', array( $this, 'gron_billing_fileds' ) );

    add_action( 'woocommerce_checkout_update_order_meta', array( $this, 'gron_custom_checkout_field_update_order_meta' ) );

    // allow custom woocommerce checkouut data in WCFM order details page
    add_filter( 'wcfm_is_allow_order_data_after_billing_address', function( $allow ) {
      $allow = true;
      return $allow;
    } );

    // Show custom fields data in order details in admin panel
    add_action( 'woocommerce_admin_order_data_after_billing_address', array( $this, 'gron_custom_checkout_field_display_admin_order_meta' ), 10, 1 );

    // Provide notification to delivery guys after order processed
    // Hooks file - wc-multivendor-marketplace/core/class-wcfmmp-commission.php
    // WooCommerce Hook - woocommerce_checkout_order_processed
    add_action( 'woocommerce_checkout_order_processed', function( $order_id, $order_posted, $order ) {
      //wcfm_get_vendor_id_by_post( $product_id );

      // Get delivery Boys
/*      $args = array(
                    'role__in'     => array( $delivery_boy_role ),
                    'orderby'      => 'ID',
                    'order'        => 'ASC',
                    'offset'       => $offset,
                    'number'       => $length,
                    'count_total'  => false,
                    'meta_key'     => '_wcfm_vendor'
                    'meta_value'   => $_POST['delivery_boy_vendor']
                   );

      $wcfm_delivery_boys_array = get_users( $args );*/

    }, 101 );
  }

  /**
   * Checkout field for date
   * @param Array $fields array of billing fields
   * @return Array modified array of billilng fields
  */
  public function gron_billing_fileds( $fields ) {

    // load custom styles
    wp_enqueue_style( 'gron-woocommerce-style' );
    // Load script
    wp_enqueue_script( 'gron-woocommerce-js' );

    $collection_type_field = array(
        'type'        => 'radio',
        'label'       => __( 'Collection Type', 'gron-custom' ),
        'required'    => true,
        'class'       => array( 'gron_collection_type' ),
        'clear'       => true,
        'options'     => array(
          'self_collection'     => 'Self Collection',
          'deliver_to_home'     => 'Deliver to Home'
        ),
        'priority' => 201
    );
    $fields['gron_collection_type'] = $collection_type_field;

    $date_filed = array(
        'type'        => 'select',
        'label'       => __( 'Deliver Date', 'gron-custom' ),
        'required'    => true,
        'class'       => array( 'select2', 'gron_deliver_date' ),
        'clear'       => true,
        'options'     => $this->get_delivery_dates(),
        'priority' => 202
    );
    $fields['gron_deliver_date'] = $date_filed;

    $time_filed = array(
        'type'        => 'select',
        'label'       => __( 'Deliver Time', 'gron-custom' ),
        'required'    => true,
        'class'       => array( 'gron_deliver_time' ),
        'clear'       => true,
        'options'     => $this->get_delivery_times(),
        'priority' => 203
    );
    $fields['gron_deliver_time'] = $time_filed;

    return $fields;

  }

  /**
   * Get delivery dates
   * @return Array options of delivery date filed
  */
  private function get_delivery_dates() {

    $options = array();
    $shop_timings = $this->db->get_shop_timings( true );

    foreach( $shop_timings as $timing ) {

      $key = $timing->day_name;
      $value = ucfirst( $timing->day_name );

      $options[$key] = $value;

    }

    return $options;

  }

  /**
   * Get delivery times
   * @return Array options of delivery time filed
  */
  private function get_delivery_times() {

    $options = array();
    $slots = $this->db->get_delivery_slots();

    foreach( $slots as $slot ) {
      $time_from = Utils::time_format( $slot->time_from );
      $time_to = Utils::time_format( $slot->time_to );
      $key = $value = $time_from . '-' . $time_to;

      $options[$key] = $value;

    }

    return $options;

  }

  /**
   * Update the order meta with field value
   */

  function gron_custom_checkout_field_update_order_meta( $order_id ) {

      $collection_type = esc_sql( $_POST['gron_collection_type'] );
      $deliver_date = esc_sql( $_POST['gron_deliver_date'] );
      $deliver_time = esc_sql( $_POST['gron_deliver_time'] );

      if ( ! empty( $collection_type ) ) {
          update_post_meta( $order_id, 'gron_collection_type', sanitize_text_field( $collection_type ) );
      }

      if ( ! empty( $deliver_date ) ) {
          update_post_meta( $order_id, 'gron_deliver_date', sanitize_text_field( $deliver_date ) );
      }

      if ( ! empty( $deliver_time ) ) {
          update_post_meta( $order_id, 'gron_deliver_time', sanitize_text_field( $deliver_time ) );
      }

  }

  /**
   * Display field value on the order edit page
   */

  function gron_custom_checkout_field_display_admin_order_meta($order) {

    $collection_type = get_post_meta( $order->get_id(), 'gron_collection_type', true );
    $deliver_date = get_post_meta( $order->get_id(), 'gron_deliver_date', true );
    $deliver_time = get_post_meta( $order->get_id(), 'gron_deliver_time', true );

    echo '<h3 style="color: #17a2b8;border-bottom: 1px solid #ccc;font-weight: 500;font-size: 13px;padding-bottom: 11px;">Delivery Details:</h3>';
    echo '<p><strong>'.__('Collection Type').':</strong> ' . Utils::underscore_to_capitalize( $collection_type ) . '</p>';
    echo '<p><strong>'.__('Deliver Date').':</strong> ' . ucfirst( $deliver_date ) . '</p>';
    echo '<p><strong>'.__('Deliver Time').':</strong> ' . $deliver_time . '</p>';
  }


}
