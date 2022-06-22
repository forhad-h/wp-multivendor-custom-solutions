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
use GRON\Services;

/**
 * GRON_WooCommerce - Woocommerce Implementation in GRON
 * @access public
 */

class GRON_WooCommerce
{

  /** @var MySQL $mysql instance of MySQL */
  private $mysql;

  /** @var SQLite $sqlite instance of SQLite */
  private $sqlite;

  /** @var WC $wc WooCommerce instance */
  private $wc;

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
  public function __construct()
  {

    $this->mysql = new MySQL();
    $this->sqlite = new SQLite();
    $this->wc = WC();

    // ?[TEMP] add_filter( 'woocommerce_billing_fields', array( $this, 'gron_billing_fields' ) );

    add_filter('woocommerce_billing_fields', array($this, 'gron_billing_fields_v2'));

    add_action('woocommerce_checkout_update_order_meta', array($this, 'gron_custom_checkout_field_update_order_meta'));

    // allow custom woocommerce checkouut data in WCFM order details page
    add_filter('wcfm_is_allow_order_data_after_billing_address', function ($allow) {
      $allow = true;
      return $allow;
    });

    // Show custom fields data in order details in admin panel
    add_action('woocommerce_admin_order_data_after_billing_address', array($this, 'gron_custom_checkout_field_display_admin_order_meta'), 10, 1);

    // Provide notification to delivery guys after order processed
    // WooCommerce Hook - woocommerce_checkout_order_processed
    add_action('woocommerce_checkout_order_processed', array($this, 'order_processed'), 101, 3);
  }

  public function gron_billing_fields_v2($fields)
  {

    // load custom styles
    wp_enqueue_style('gron-woocommerce-style');
    // Load script
    wp_enqueue_script('gron-woocommerce-script');

    $vendor_field = array(
      'type'        => 'radio',
      'label'       =>  __('Select Store:', 'gron-custom'),
      'required'    => true,
      'class'       => array('gron_field_wrapper', 'gron_vendor_list', 'gron_radio_select'),
      'clear'       => true,
      'options'     => array(
        ''     => 'Placeholder'
      ),
      'priority'    => 991
    );
    $fields['gron_vendor'] = $vendor_field;


    $collection_type_field = array(
      'type'        => 'radio',
      'label'       => __('Collection Type:', 'gron-custom'),
      'required'    => true,
      'class'       => array('gron_field_wrapper', 'gron_collection_type', 'gron_radio_select'),
      'clear'       => true,
      'options'     => array(
        'self_collection'     => 'Self Collection',
        'deliver_to_home'     => 'Deliver to Home'
      ),
      'priority'    => 993
    );
    $fields['gron_collection_type'] = $collection_type_field;

    $date_field = array(
      'type'        => 'select',
      'label'       => __('Deliver Date:', 'gron-custom'),
      'required'    => false,
      'class'       => array('gron_field_wrapper', 'select2', 'gron_deliver_day'),
      'clear'       => true,
      'options'     => array(
        '' => 'Placeholder'
      ),
      'priority'    => 995
    );
    $fields['gron_deliver_day'] = $date_field;

    $time_field = array(
      'type'        => 'select',
      'label'       => __('Deliver Time:', 'gron-custom'),
      'required'    => false,
      'class'       => array('gron_field_wrapper', 'gron_deliver_time'),
      'clear'       => true,
      'options'     =>  array(
        '' => 'Placeholder'
      ),
      'priority'    => 997
    );
    $fields['gron_deliver_time'] = $time_field;

    return $fields;
  }

  /**
   * Checkout field for date
   * @param Array $fields array of billing fields
   * @return Array modified array of billilng fields
   */
  public function gron_billing_fields($fields)
  {

    // load custom styles
    wp_enqueue_style('gron-woocommerce-style');
    // Load script
    wp_enqueue_script('gron-woocommerce-script');

    $get_carts = $this->wc->cart->get_cart();
    $priority = 200;
    $vendor_ids = array();

    foreach ($get_carts as $cart_item_key => $cart_item) {
      $product_id = $cart_item['product_id'];
      $author_id = get_post_field('post_author', $product_id);
      array_push($vendor_ids, $author_id);
    }

    // remove duplicates
    $vendor_ids = array_unique($vendor_ids);

    foreach ($vendor_ids as $vendor_id) {

      $store_name = get_user_meta($vendor_id, 'store_name', true);

      $collection_type_field = array(
        'type'        => 'radio',
        'label'       => sprintf('<span class="gron_cp_store_name">%1$s</span> %2$s', $store_name, __('Collection Type:', 'gron-custom')),
        'required'    => true,
        'class'       => array('gron_collection_type', 'gron_radio_select'),
        'clear'       => true,
        'options'     => array(
          'self_collection'     => 'Self Collection',
          'deliver_to_home'     => 'Deliver to Home'
        ),
        'priority'    => ++$priority
      );
      $fields['gron_collection_type_' . $vendor_id] = $collection_type_field;

      $date_field = array(
        'type'        => 'select',
        'label'       => __('Deliver Date:', 'gron-custom'),
        'required'    => true,
        'class'       => array('select2', 'gron_deliver_day'),
        'clear'       => true,
        'options'     => array_merge(array("" => "Select Delivery Day"), Utils::get_delivery_days($vendor_id)),
        'priority'    => ++$priority
      );
      $fields['gron_deliver_day_' . $vendor_id] = $date_field;

      $time_field = array(
        'type'        => 'select',
        'label'       => __('Deliver Time:', 'gron-custom'),
        'required'    => true,
        'class'       => array('gron_deliver_time'),
        'clear'       => true,
        'options'     => array_merge( array("" => "Select Delivery Times"), Utils::get_delivery_times($vendor_id)),
        'priority'    => ++$priority
      );
      $fields['gron_deliver_time_' . $vendor_id] = $time_field;
    }

    return $fields;
  }

  /**
   * Update the order meta with field value
   */

  function gron_custom_checkout_field_update_order_meta($order_id)
  {

    $order = wc_get_order($order_id);

    $vendor_ids = $this->get_vendor_ids($order);

    foreach ($vendor_ids as $item_id => $vendor_id) {

      $collection_type = esc_sql($_POST['gron_collection_type_' . $vendor_id]);
      $deliver_day = esc_sql($_POST['gron_deliver_day_' . $vendor_id]);
      $deliver_time = esc_sql($_POST['gron_deliver_time_' . $vendor_id]);

      if (!empty($collection_type)) {
        update_post_meta($order_id, 'gron_collection_type_' . $vendor_id, sanitize_text_field($collection_type));
      }

      if (!empty($deliver_day)) {
        update_post_meta($order_id, 'gron_deliver_day_' . $vendor_id, sanitize_text_field($deliver_day));
      }

      if (!empty($deliver_time)) {
        update_post_meta($order_id, 'gron_deliver_time_' . $vendor_id, sanitize_text_field($deliver_time));
      }
    }
  }

  /**
   * Display field value on the order edit page
   * @param Object $order Order object
   */

  function gron_custom_checkout_field_display_admin_order_meta($order)
  {

    $vendor_ids = $this->get_vendor_ids($order);

    echo '<h3 style="color:#17a2b8;font-weight: 500;font-size: 13px;border-bottom: 1px solid #ccc;padding-bottom: 11px;">Delivery Details:</h3>';

    foreach ($vendor_ids as $item_id => $vendor_id) {

      $collection_type = get_post_meta($order->get_id(), 'gron_collection_type_' . $vendor_id, true);
      $deliver_day = get_post_meta($order->get_id(), 'gron_deliver_day_' . $vendor_id, true);
      $deliver_time = get_post_meta($order->get_id(), 'gron_deliver_time_' . $vendor_id, true);

      $store_name = get_user_meta($vendor_id, 'store_name', true);

      echo '<h3 style="color:#333;font-weight: 600;font-size: 15px;margin-bottom: 3px;">' . $store_name . '</h3>';
      echo '<p><strong>' . __('Collection Type') . ':</strong> ' . Utils::underscore_to_capitalize($collection_type) . '</p>';
      echo '<p><strong>' . __('Deliver Date') . ':</strong> ' . ucfirst($deliver_day) . '</p>';
      echo '<p><strong>' . __('Deliver Time') . ':</strong> ' . $deliver_time . '</p>';
    }
  }

  /**
   * Mange notification after order processing
   * @param Int $order_id Order ID
   * @param Object $posted_data
   * @param Object $order The Order Object
   */
  public function order_processed($order_id, $posted_data, $order)
  {

    /*
      TODO:
      figure out how to redirect to thank you page, if any error occured here.
      Like pusher can not connect etc.
    */

    // Get vendor IDs from the $order
    $vendor_ids = $this->get_vendor_ids($order);

    // Get devlivery boy IDs
    if (!empty($vendor_ids)) {

      /**
       * Nested loop is acceptable here
       * As vendor numbers will be very limited
       */
      foreach ($vendor_ids as $item_id => $vendor_id) {

        $collection_type = get_post_meta($order_id, 'gron_collection_type_' . $vendor_id, true);

        // No delivery notification for self collection type
        if ($collection_type === 'self_collection') return;

        // Delivery notification process
        if ($this->is_delivery_manage_by_vendor($vendor_id)) {

          // Delivery manage by vendor
          $this->deliveriy_notification_process($vendor_id, $order_id, $item_id, 'vendor');
        } else {

          // Delivery manage by admin
          $this->deliveriy_notification_process($vendor_id, $order_id, $item_id, 'admin');
        }
      }
    }
  }

  /**
   * Get vendor IDs from $order Object
   * @param Object $order Order object
   * @return NULL|Array Arrays of vendor ids
   */
  private function get_vendor_ids($order)
  {

    $vendor_ids = array();
    $line_items = $order->get_items('line_item');

    if (!empty($line_items)) {

      foreach ($line_items as $item_id => $item) {

        $product = new \WC_Order_Item_Product($item_id);

        $product_id = $product->get_product_id();

        if ($product_id) {

          $vendor_id = wcfm_get_vendor_id_by_post($product_id);

          if (!in_array($vendor_id, $vendor_ids)) {
            $vendor_ids[$item_id] = $vendor_id;
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
  private function get_delivery_boy_ids_of_vendor($vendor_id)
  {

    $delivery_boy_role = 'wcfm_delivery_boy';

    $args = array(
      'role__in'     => array($delivery_boy_role),
      'orderby'      => 'ID',
      'order'        => 'ASC',
      'meta_key'     => '_wcfm_vendor',
      'meta_value'   => $vendor_id,
      'fields'       => "ID"
    );

    $delivery_boy_ids = get_users($args);

    return $delivery_boy_ids;
  }

  /**
   * Get vendor's devlivery boy IDs
   * @return NULL|Array Arrays of vendor ids
   */
  private function get_delivery_boy_ids_of_admin()
  {

    $delivery_boy_role = 'wcfm_delivery_boy';

    $args = array(
      'role__in'     => array($delivery_boy_role),
      'orderby'      => 'ID',
      'order'        => 'ASC',
      'meta_key'     => '_gron_admin',
      'meta_value'   => 'yes',
      'fields'       => "ID"
    );

    $delivery_boy_ids = get_users($args);

    return $delivery_boy_ids;
  }

  /**
   * Delivery Notification Process
   *
   * @param Int $vendor_id ID of the vendor
   * @param Int $order_id ID of the order
   * @param String $manage_by delivery manage by 'admin' or 'vendor'
   */
  private function deliveriy_notification_process($vendor_id, $order_id, $item_id, $manage_by)
  {

    $delivery_boy_ids = array();

    if ($manage_by === 'vendor') {

      $delivery_boy_ids = $this->get_delivery_boy_ids_of_vendor($vendor_id);
    } elseif ($manage_by === 'admin') {

      $delivery_boy_ids = $this->get_delivery_boy_ids_of_admin();
    }

    $data = array(
      'manage_by' => $manage_by,
      'vendor_id' => $vendor_id,
      'order_id'  => $order_id,
      'item_id'   => $item_id,
      'status'    => 'pending'
    );

    if (!empty($delivery_boy_ids)) {

      // If found delivery boys
      foreach ($delivery_boy_ids as $boy_id) {

        $data['boy_id'] = $boy_id;

        // save notification info
        $this->sqlite->insert_delivery_notification($data);

        // Notify delivery boy
        Services::pusher()->trigger('delivery-boy', 'new-order', array(
          'order_id'  => $order_id,
          'boy_id' => $boy_id
        ));
      }

      // Notify admin or vendor
      Services::pusher()->trigger($manage_by, 'new-order', array(
        'order_id'  => $order_id,
        'vendor_id' => $vendor_id
      ));
    } else {

      // If delivery boys not found
      $data['boy_id'] = null;
      $data['status_msg'] = 'No delivery boy!';

      // save notification info
      $this->sqlite->insert_delivery_notification($data);

      // Notify admin or vendor
      Services::pusher()->trigger($manage_by, 'new-order', array(
        'order_id'  => $order_id,
        'vendor_id' => $vendor_id
      ));
    }
  }

  /**
   * Check if delivery manage by vendor
   * @param Int $vendor_id ID of vendor
   */
  private function is_delivery_manage_by_vendor($vendor_id)
  {

    return Utils::is_delivery_by_seller() &&
      Utils::is_delivery_by_me($vendor_id) &&
      Utils::is_allowed_the_vendor_for_dm($vendor_id);
  }
}
