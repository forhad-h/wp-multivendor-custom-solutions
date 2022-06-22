<?php
namespace GRON;
defined('ABSPATH') or exit;

use GRON\Utils;

class MySQL {

  /** @var wpdb $db instance of wpdb */
  private $db;

  /** @var String $shop_timings_tb_name Name of the table, which store shop timings data*/
  private $shop_timings_tb_name;

  /** @var String $delivery_slots_tb_name Name of the table, which stores delivery slot data*/
  private $delivery_slots_tb_name;

  /** @var Int $current_user_id ID of the current user */
  private $current_user_id;

  public function __construct() {
    global $wpdb;
    $this->db = $wpdb;
    $this->current_user_id = get_current_user_id();
    $this->shop_timings_tb_name    = $wpdb->prefix . 'gron_shop_timings';
    $this->delivery_slots_tb_name  = $wpdb->prefix . 'gron_delivery_slots';
    $this->wcfm_delivery_orders_tb_name = $wpdb->prefix . 'wcfm_delivery_orders';
    $this->gron_assigned_vendor_orders_tb_name = $wpdb->prefix . 'gron_assigned_vendor_orders';
  }

  /**
   * Create necessary tables into Database
  */
  public function create_tables() {

    try {

      $charset_collate = $this->db->get_charset_collate();

      $queries = array(
        "CREATE TABLE $this->shop_timings_tb_name (
          timing_id BIGINT NOT NULL AUTO_INCREMENT,
          user_id BIGINT NOT NULL,
          day_name VARCHAR(11) NOT NULL,
          start_time TIME,
          end_time TIME,
          is_active BOOLEAN DEFAULT 0,
          PRIMARY KEY (timing_id)
        ) $charset_collate;",
        "CREATE TABLE $this->delivery_slots_tb_name (
          slot_id BIGINT NOT NULL AUTO_INCREMENT,
          user_id BIGINT NOT NULL,
          time_from TIME NOT NULL,
          time_to TIME NOT NULL,
          PRIMARY KEY (slot_id)
        ) $charset_collate;",
        "CREATE TABLE $this->gron_assigned_vendor_orders_tb_name (
          assign_id BIGINT NOT NULL AUTO_INCREMENT,
          vendor_id BIGINT NOT NULL,
          order_id BIGINT NOT NULL,
          status VARCHAR(21),
          PRIMARY KEY (assign_id)
        ) $charset_collate;",
      );
      require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

      foreach( $queries as $query ) {
        dbDelta( $query );
      }

    }catch( \Exception $e) {

      $this->print_error( 'db_table_creation_failed', sprintf('Error: during create %s table.', $this->table_name ), $e );

    }

  }

  /**
   * Insert user information
  */
  public function insert_user_info( $data ) {

    try {

      $this->db->insert($this->table_name, [
        'user_id' => $data->user_id,
        'address' => $data->address
      ]);

    }catch( \Exception $e ) {
      $this->print_error( 'db_insert_failed', 'Error: during try to insert user info.', $e );
    }

  }

  /**
   * Get user information
  */
  public function get_user_info( $id ) {

    try {

      $query = "SELECT * FROM {$this->table_name} WHERE user_id={$id}";
      $res = $this->db->get_row( $query );
      return $res;

    }catch( \Exception $e) {
      $this->print_error( 'db_get_failed', 'Error: during try to get user info.', $e );
    }

  }
  /**
   * Print error
   * @param $code error code
   * @param $message error message
   * @param $data error data
  */
  private function print_error( $code, $message, $data = '' ) {
    $error = new \WP_Error( $code, $message, $data );
    print $error;
  }

  /**
   * update shop timings
   *
   * @param Array $data
   * @version 2.0.1
   * @return NULL|Boolean
  */
  public function insert_shop_timing( $data ) {

    $user_id = $this->current_user_id;
    $day_name = esc_sql( $data['day_name'] );
    $start_time = esc_sql( $data['start_time'] );
    $end_time = esc_sql( $data['end_time'] );
    $is_active = $data['is_active'] !== 'true' ? false : true;

    try {
      $insert = $this->db->insert(
        $this->shop_timings_tb_name,
        array(
          'user_id' => $user_id,
          'day_name' => $day_name,
          'start_time' => $start_time,
          'end_time' => $end_time,
          'is_active' => $is_active
        )
      );

      return $insert;

    }catch( \Exception $e ) {
      $this->print_error( 'not-inserted', 'shop timing not inserted!' );
    }

  }

  /**
   * update shop timings
   *
   * @param Array $data
   * @version 2.0.1
   * @return NULL|Boolean
  */
  public function update_shop_timing( $data ) {

    $user_id = $this->current_user_id;
    $day_name = esc_sql( $data['day_name'] );
    $start_time = esc_sql( $data['start_time'] );
    $end_time = esc_sql( $data['end_time'] );
    $is_active = $data['is_active'] !== 'true' ? false : true;

    try {
      $update = $this->db->update(
        $this->shop_timings_tb_name,
        array(
          'start_time' => $start_time,
          'end_time' => $end_time,
          'is_active' => $is_active
        ),
        array(
          'user_id' => $user_id,
          'day_name' => $day_name
        )
      );

      return $update;

    }catch( \Exception $e ) {
      $this->print_error( 'not-updated', 'shop timing not updated!' );
    }

  }

  /**
   * get shop timings
   *
   * @param Boolean $active_only
   * @param Int $user_id ID of the user
   * @version 2.0.1
   * @return NULL|Array
  */
  public function get_shop_timings( $active_only = false, $user_id ) {

    $sql = "SELECT * FROM {$this->shop_timings_tb_name} WHERE user_id='{$user_id}'";
    $data = array();

    if( $active_only ) {
      $sql .= " AND is_active=1";
    }

    $results = $this->db->get_results( $sql );

    foreach( $results as $result ) {
      $data[$result->day_name] = $result;
    }

    return $data;
  }

  /**
   * Check if shop timing exists
   *
   * @version 2.1.2
   * @return Boolean
  */
  public function has_shop_timing( $day_name ) {

    $sql = "SELECT timing_id FROM {$this->shop_timings_tb_name} WHERE user_id={$this->current_user_id} AND day_name='{$day_name}'";

    $result = $this->db->get_var( $sql );

    return !$result ? false : true;
  }

  /**
   * count shop timings
   *
   * @return NULL|Int
  */
  public function count_shop_timings( $active_only = true ) {

    $sql = "SELECT COUNT(*) FROM {$this->shop_timings_tb_name} WHERE user_id={$this->current_user_id}";

    if( $active_only ) {
      $sql .= " AND is_active=1";
    }

    $result = $this->db->get_var( $sql );

    return $result;

  }


  /**
    * Has data in Delivery Slots
    *
    * @return NULL|Boolean
  */
  public function count_delivery_slots() {

    $sql = "SELECT COUNT(*) FROM {$this->delivery_slots_tb_name} WHERE user_id={$this->current_user_id}";
    $result = $this->db->get_var( $sql );

    return $result;
  }

  /**
   * insert delivery slot
   *
   * @version 2.0.3
   * @return NULL|Bollean
  */
  public function insert_delivery_slot( $data ) {

    $user_id   = $this->current_user_id;
    $time_from = esc_sql( $data['time_from'] );
    $time_to = esc_sql( $data['time_to'] );

    $insert = $this->db->insert(
        $this->delivery_slots_tb_name,
        array(
          'user_id'   => $user_id,
          'time_from' => $time_from,
          'time_to'   => $time_to
        )
    );

    return $insert;

  }

    /**
     * update delivery slot
     *
     * @version 2.0.3
     * @return NULL|Bollean
    */
    public function update_delivery_slot( $data ) {

      $user_id   = $this->current_user_id;
      $slot_id   = esc_sql( $data['slot_id'] );
      $time_from = esc_sql( $data['time_from'] );
      $time_to   = esc_sql( $data['time_to'] );

      $update = $this->db->update(
          $this->delivery_slots_tb_name,
          array(
            'time_from' => $time_from,
            'time_to' => $time_to
          ),
          array(
            'slot_id' => $slot_id,
            'user_id' => $user_id
          )
      );

      return $update;

    }

    /**
     * delete delivery slot
     *
     * @param Int $id
     * @version 2.0.3
     * @return NULL|Bollean
    */
    public function delete_delivery_slot( $slot_id ) {

      $user_id = $this->current_user_id;

      $delete = $this->db->delete(
        $this->delivery_slots_tb_name,
        array(
          'slot_id' => $slot_id,
          'user_id' => $user_id
        )
      );

      return $delete;

    }


    /**
     * get delivery slots
     *
     * @param Int $user_id ID of the user
     * @version 2.0.3
     * @return NULL|Array
    */
    public function get_delivery_slots( $user_id ) {

      $sql = "SELECT * FROM {$this->delivery_slots_tb_name} WHERE user_id={$user_id}";
      $results = $this->db->get_results( $sql );

      return $results;

    }

    /**
     * get delivery slot by id
     *
     * @param Int $id
     * @return NULL|Array
    */
    public function get_delivery_slot_by_id( $slot_id ) {

      $user_id = $this->current_user_id;

      $sql = "SELECT * FROM {$this->delivery_slots_tb_name} WHERE slot_id={$slot_id} AND user_id={$user_id}";

      $result = $this->db->get_row( $sql );

      return $result;

    }

    /**
    * Insert Order Delivery in WCFM's Table
    * So that we have a synchronization between
    * WCFM delivery and GRON delivery management
    *
    * @param Array $data array of data
    * ['vendor_id'] => [Required] (Int) ID of the vendor
    * ['boy_id'] => [Required] (Int) ID of the delivery boy
    * ['order_id'] => [Required] (Int) ID of the Order
    * ['item_id'] => [Required] (Int) ID of the Item
    *
    * @return Int|Null $delivery_id ID of the delivery entry
    */

    public function insert_wcfm_delivery_order( $data ) {

      $vendor_id     = esc_sql( $data['vendor_id'] );
      $boy_id        = esc_sql( $data['boy_id'] );
      $order_id      = esc_sql( $data['order_id'] );
      $order_item_id = esc_sql( $data['item_id'] );

      $order = wc_get_order( $order_id );

      $customer_id = 0;
      if ( $order->get_user_id() )
        $customer_id = $order->get_user_id();

      $payment_method = ! empty( $order->get_payment_method() ) ? $order->get_payment_method() : '';

      $line_item    = new \WC_Order_Item_Product( $order_item_id );
			$product      = $line_item->get_product();
			$product_id   = $line_item->get_product_id();
			$variation_id = $line_item->get_variation_id();

      $data_to_insert = array(
        'vendor_id' => $vendor_id,
        'order_id' => $order_id,
        'customer_id' => $customer_id,
        'payment_method' => $payment_method,
        'product_id' => $product_id,
        'variation_id' => $variation_id,
        'quantity' => $line_item->get_quantity(),
        'product_price' => $product->get_price(),
        'item_id' => $order_item_id,
        'item_sub_total' => $line_item->get_subtotal(),
        'item_total' => $line_item->get_total(),
        'delivery_boy' => $boy_id
      );

      $insert = $this->db->insert( $this->wcfm_delivery_orders_tb_name, $data_to_insert);

      $delivery_id = $this->db->insert_id;

      return $delivery_id;
    }

    public function update_wcfm_delivery_order( $delivery_id ) {

      // Update Delivery Order Status Update
      $data_to_update = array(
        'delivery_status' => 'delivered',
        'delivery_date'   => current_time('Y-m-d H:i:s'),
      );

      $update = $this->db->update(
        $this->wcfm_delivery_orders_tb_name,
        $data_to_update,
        array( 'ID' => $delivery_id ) );

      return $update;

    }

    public function insert_assigned_vendor_orders( $data ) {

      $vendor_id     = esc_sql( $data['vendor_id'] );
      $order_id      = esc_sql( $data['order_id'] );

      $data_to_insert = array(
        'vendor_id' => $vendor_id,
        'order_id' => $order_id,
        'status' => 'processing',
      );

      $this->db->insert( $this->gron_assigned_vendor_orders_tb_name, $data_to_insert);

      $assign_id = $this->db->insert_id;

      return $assign_id;

    }


}
