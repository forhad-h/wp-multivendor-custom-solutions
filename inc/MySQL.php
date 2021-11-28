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

  public function __construct() {
    global $wpdb;
    $this->db = $wpdb;
    $this->shop_timings_tb_name    = $wpdb->prefix . 'gron_shop_timings';
    $this->delivery_slots_tb_name  = $wpdb->prefix . 'gron_delivery_slots';
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
          day_name VARCHAR(11) NOT NULL,
          start_time TIME,
          end_time TIME,
          is_active BOOLEAN DEFAULT 0,
          PRIMARY KEY (timing_id)
        ) $charset_collate;",
        "CREATE TABLE $this->delivery_slots_tb_name (
          slot_id BIGINT NOT NULL AUTO_INCREMENT,
          time_from TIME NOT NULL,
          time_to TIME NOT NULL,
          PRIMARY KEY (slot_id)
        ) $charset_collate;",
      );
      require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

      foreach( $queries as $query ) {
        Utils::log($query);
        dbDelta( $query );
      }

      // insert initial values for shop timings
      if( !$this->count_shop_timings( false ) ) {
        $this->insert_shop_timings();
      }

    }catch( Exception $e) {

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

    }catch( Execption $e ) {
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

    }catch( Exception $e) {
      $this->print_error( 'db_get_failed', 'Error: during try to get user info.', $e );
    }

  }

  public function save_option( $key ) {

    if( get_option( $key ) ) update_option( $key, $value );
    else add_option( $key, $value );

  }

  /**
   * Print error
   * @param $code error code
   * @param $message error message
   * @param $data error data
  */
  private function print_error( $code, $message, $data = '' ) {
    $error = new WP_Error( $code, $message, $data );
    print $error;
  }

  /**
   * Insert default value to shop timings
   *
   * @version 2.0.1
   * @return NULL
  */
  private function insert_shop_timings() {
    $days = array( 'sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday' );

    foreach( $days as $day ) {

      $this->db->insert( $this->shop_timings_tb_name, array(
        'day_name' => $day
      ) );

    }

  }

  /**
   * update shop timings
   *
   * @version 2.0.1
   * @return NULL|Boolean
  */
  public function update_shop_timing( $data ) {

    $start_time = esc_sql( $data['start_time'] );
    $end_time = esc_sql( $data['end_time'] );
    $is_active = esc_sql( $data['is_active'] );
    $day_name = esc_sql( $data['day_name'] );

    try {
      $update = $this->db->update(
        $this->shop_timings_tb_name,
        array(
          'start_time' => $start_time,
          'end_time' => $end_time,
          'is_active' => $is_active == 'true' ? true : false
        ),
        array(
          'day_name' => $day_name
        )
      );

      return $update;

    }catch( Exception $e ) {
      $this->print_error( 'not-updated', 'shop timing not updated!' );
    }

  }

  /**
   * get shop timings
   *
   * @version 2.0.1
   * @return NULL|Array
  */
  public function get_shop_timings( $active_only = false ) {

    $sql = "SELECT * FROM {$this->shop_timings_tb_name}";

    if( $active_only ) {
      $sql .= " WHERE is_active=1";
    }

    $result = $this->db->get_results( $sql );
    return $result;
  }

  /**
   * count shop timings
   *
   * @return NULL|Int
  */
  public function count_shop_timings( $active_only = true ) {

    $sql = "SELECT COUNT(*) FROM {$this->shop_timings_tb_name}";

    if( $active_only ) {
      $sql .= " WHERE is_active=1";
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

    $sql = "SELECT COUNT(*) FROM {$this->delivery_slots_tb_name}";
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

    $time_from = esc_sql( $data['time_from'] );
    $time_to = esc_sql( $data['time_to'] );

    $insert = $this->db->insert(
        $this->delivery_slots_tb_name,
        array(
          'time_from' => $time_from,
          'time_to' => $time_to
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

      $slot_id = esc_sql( $data['slot_id'] );
      $time_from = esc_sql( $data['time_from'] );
      $time_to = esc_sql( $data['time_to'] );

      $update = $this->db->update(
          $this->delivery_slots_tb_name,
          array(
            'time_from' => $time_from,
            'time_to' => $time_to
          ),
          array(
            'slot_id' => $slot_id
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

      $delete = $this->db->delete(
        $this->delivery_slots_tb_name,
        array(
          'slot_id' => $slot_id
        )
      );

      return $delete;

    }


    /**
     * get delivery slots
     *
     * @version 2.0.3
     * @return NULL|Array
    */
    public function get_delivery_slots() {

      $sql = "SELECT * FROM {$this->delivery_slots_tb_name}";
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

      $sql = "SELECT * FROM {$this->delivery_slots_tb_name} WHERE slot_id=$slot_id";

      $result = $this->db->get_row( $sql );

      return $result;

    }

    /**
     * Save vendor general settings
     *
     * @param Array $settings
     *  ['name'] => 'Value'
     * @return Array $settings return same settings array
    */
    public function save_vendor_general_settings( $settings ) {

      foreach( $settings as $name => $value ) {

        Utils::save_option( $name, $value );

      }

      return $settings;

    }


}
