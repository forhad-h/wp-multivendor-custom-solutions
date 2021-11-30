<?php

/**
 * Add date and time slot field in woocommerce
 * @version 2.0.0
*/
namespace GRON;

defined('ABSPATH') or exit;

use GRON\MySQL;
use GRON\SQLite;
use GRON\Utils;

/**
* GRON_WooCommerce - Woocommerce Implementation in GRON
* @access public
*/

class GRON_WooCommerce {

  /** @var MySQL $mysql_crud_operation instance of MySQL */
  private $mysql_crud_operation;

  /** @var SQLite $sqlite instance of SQLite */
  private $sqlite;

  /**
  * consturct function of GRON_WooCommerce
  * Initialize Database connection
  * Used woocommerce and WCFM hooks
  ** 'woocommerce_billing_fields' - filter hook to add extra fields
  ** 'woocommerce_checkout_update_order_meta' (action hook) - update custom fields value
  ** 'wcfm_is_allow_order_data_after_billing_address' (filter hook) - allow custom data in WCFM order details page
  ** 'woocommerce_admin_order_data_after_billing_address' (action hook) - show custom fields data in order details in admin panel
  ** 'woocommerce_checkout_create_order' (action hook) - Provide notification to delivery guys after order completed
  * @access public
  * @return void
  */
  public function __construct() {

    $this->mysql_crud_operation = new MySQL();
    $this->sqlite = new SQLite();

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
    // WooCommerce Hook - woocommerce_checkout_order_processed
    add_action( 'woocommerce_checkout_order_processed', array( $this, 'order_processed' ), 101, 3 );

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
    wp_enqueue_script( 'gron-woocommerce-script' );

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

    $options = array( "" => "Select Delivery Date" );
    $shop_timings = $this->mysql_crud_operation->get_shop_timings( true );

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

    $options = array( "" => "Select Delivery Times" );
    $slots = $this->mysql_crud_operation->get_delivery_slots();

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
   * @param Object $order Order object
   */

  function gron_custom_checkout_field_display_admin_order_meta( $order ) {

    $collection_type = get_post_meta( $order->get_id(), 'gron_collection_type', true );
    $deliver_date = get_post_meta( $order->get_id(), 'gron_deliver_date', true );
    $deliver_time = get_post_meta( $order->get_id(), 'gron_deliver_time', true );

    echo '<h3 style="color: #17a2b8;border-bottom: 1px solid #ccc;font-weight: 500;font-size: 13px;padding-bottom: 11px;">Delivery Details:</h3>';
    echo '<p><strong>'.__('Collection Type').':</strong> ' . Utils::underscore_to_capitalize( $collection_type ) . '</p>';
    echo '<p><strong>'.__('Deliver Date').':</strong> ' . ucfirst( $deliver_date ) . '</p>';
    echo '<p><strong>'.__('Deliver Time').':</strong> ' . $deliver_time . '</p>';
  }

  /**
   * Mange notification after order processing
   * @param Int $order_id Order ID
   * @param Object $posted_data
   * @param Object $order The Order Object
   */
  public function order_processed( $order_id, $posted_data, $order ) {

    // Get vendor IDs from the $order
    $vendor_ids = $this->get_vendor_ids( $order );

    // Get devlivery boy IDs
    if( !empty( $vendor_ids ) ) {

     /**
     * Nested loop is acceptable here
     * As vendor numbers will be very limited
     */
     foreach( $vendor_ids as $vendor_id ) {

       // Check if delivery manage by vendor

       if( $this->is_delivery_manage_by_vendor() ) {

         // Delivery manage by vendor



       }else {

         // Delivery manage by admin

         $delivery_boy_ids = $this->get_delivery_boy_ids_of_admin();


       }

     }

    }

  }

 /**
  * Get vendor IDs from $order Object
  * @param Object $order Order object
  * @return NULL|Array Arrays of vendor ids
  */
  private function get_vendor_ids( $order ) {

    $vendor_ids = [];
    $line_items = $order->get_items( 'line_item' );

    if( !empty( $line_items ) ) {

      foreach( $line_items as $item_id => $item ) {

        $product = new \WC_Order_Item_Product( $item_id );

        $product_id = $product->get_product_id();

        if( $product_id ) {

          $vendor_id = wcfm_get_vendor_id_by_post( $product_id );

          if( !in_array( $vendor_id, $vendor_ids ) ) {
            array_push( $vendor_ids, $vendor_id );
          }

        }

      }

    }

    return $vendor_ids;

  }

  /**
   * Get vendor's devlivery boy IDs
   * @param Int $vendor_id ID of the vendor
   * @return NULL|Array Arrays of vendor ids
   */
   private function get_delivery_boy_ids_of_vendor( $vendor_id ) {

     $delivery_boy_role = 'wcfm_delivery_boy';

     $args = array(
       'role__in'     => array( $delivery_boy_role ),
       'orderby'      => 'ID',
       'order'        => 'ASC',
       'meta_key'     => '_wcfm_vendor',
       'meta_value'   => $vendor_id,
       'fields'       => "ID"
      );

     $delivery_boy_ids = get_users( $args );

     return $delivery_boy_ids;

   }

   /**
    * Get vendor's devlivery boy IDs
    * @return NULL|Array Arrays of vendor ids
    */
    private function get_delivery_boy_ids_of_admin() {

      $delivery_boy_role = 'wcfm_delivery_boy';

      $args = array(
        'role__in'     => array( $delivery_boy_role ),
        'orderby'      => 'ID',
        'order'        => 'ASC',
        'meta_key'     => '_gron_admin',
        'meta_value'   => 'yes',
        'fields'       => "ID"
       );

      $delivery_boy_ids = get_users( $args );

      return $delivery_boy_ids;

    }

    /**
    * Save Deliveries
    */
    private function save_deliveries( $vendor_id, $order_id, $manage_by ) {

      $delivery_boy_ids = array();

      if( $manage_by === 'vendor' ) {

        $delivery_boy_ids = $this->get_delivery_boy_ids_of_vendor( $vendor_id );

      }elseif( $manage_by === 'admin' ) {

        $delivery_boy_ids = $this->get_delivery_boy_ids_of_admin();

      }

      if( !empty( $delivery_boy_ids ) ) {
        // If found delivery boys
        foreach( $delivery_boy_ids as $boy_id ) {

            $data = array(
              'manage_by' => $manage_by,
              'vendor_id' => $vendor_id,
              'order_id'  => $order_id,
              'boy_id'    => $boy_id,
              'status'    => 'pending'
            );

            $this->sqlite->insert_order_deliveries( $data );

        }
      }else {
        // If delivery boys not found
        $data = array(
          'manage_by' => $manage_by,
          'vendor_id' => $vendor_id,
          'order_id'  => $order_id,
          'boy_id'    => null,
          'status'    => 'No delivery boy!'
        );

        $this->sqlite->insert_order_deliveries( $data );
      }

    }

    /**
    * Check if delivery manage by vendor
    */
    private function is_delivery_manage_by_vendor() {

      return Utils::is_delivery_by_seller() &&
             Utils::is_delivery_by_me( $vendor_id );

    }

}
