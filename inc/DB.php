<?php
namespace GRON;
defined('ABSPATH') or exit;


class DB {

  private $db;
  private $shop_timings_tb_name;
  private $delivery_slots_tb_name;

  public function __construct() {
    global $wpdb;
    $this->db = $wpdb;
    $this->shop_timings_tb_name = $wpdb->prefix . 'gron_shop_timings';
    $this->delivery_slots_tb_name = $wpdb->prefix . 'gron_delivery_slots';
  }

  /**
   * Create necessary tables into Database
  */
  public function create_tables() {

    if( get_option( 'gron_version' ) === GRON_VERSION ) return;

    try {

      $charset_collate = $this->db->get_charset_collate();

      $query_shop_timings = "CREATE TABLE $this->shop_timings_tb_name (
        id INT NOT NULL AUTO_INCREMENT,
        day_name VARCHAR(11) NOT NULL,
        start_time TIME,
        end_time TIME,
        is_active BOOLEAN DEFAULT 0,
        PRIMARY KEY (id)
      ) $charset_collate;";

      $query_delivery_slots = "CREATE TABLE $this->delivery_slots_tb_name (
        id INT NOT NULL AUTO_INCREMENT,
        time_from TIME NOT NULL,
        time_to TIME NOT NULL,
        PRIMARY KEY (id)
      ) $charset_collate;";

      require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
      dbDelta( $query_shop_timings );
      dbDelta( $query_delivery_slots );

      // insert initial values for shop timings
      if( !$this->has_shop_timings_data( false ) ) {
        $this->insert_shop_timings();
      }

      //TODO: what happend if one or more row has been deleted
      if( get_option( 'gron_version' ) ) {
        update_option( 'gron_version', GRON_VERSION );
      }else {
        add_option( 'gron_version', GRON_VERSION );
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
   * @return NULL
   * @version 2.0.1
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
   * @return NULL|Boolean
   * @version 2.0.1
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
   * @return NULL|Array
   * @version 2.0.1
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
    * Has data in Shop Timings
    * @return NULL|Boolean
  */
  public function has_shop_timings_data( $active_only = true ) {

    $sql = "SELECT COUNT(*) FROM {$this->shop_timings_tb_name}";

    if( $active_only ) {
      $sql .= " WHERE is_active=1";
    }

    $result = $this->db->get_var( $sql );

    return (bool) $result;
  }

  /**
    * Has data in Delivery Slots
    * @return NULL|Boolean
  */
  public function has_delivery_slots_data() {

    $sql = "SELECT COUNT(*) FROM {$this->delivery_slots_tb_name}";
    $result = $this->db->get_var( $sql );

    return (bool) $result;
  }

  /**
   * insert delivery slot
   * @return NULL|Bollean
   * @version 2.0.3
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
     * @return NULL|Bollean
     * @version 2.0.3
    */
    public function update_delivery_slot( $data ) {

      $id = esc_sql( $data['id'] );
      $time_from = esc_sql( $data['time_from'] );
      $time_to = esc_sql( $data['time_to'] );

      $update = $this->db->update(
          $this->delivery_slots_tb_name,
          array(
            'time_from' => $time_from,
            'time_to' => $time_to
          ),
          array(
            'id' => $id
          )
      );

      return $update;

    }

    /**
     * delete delivery slot
     * @return NULL|Bollean
     * @version 2.0.3
    */
    public function delete_delivery_slot( $data ) {

      $id = esc_sql( $data['id'] );

      $delete = $this->db->delete(
        $this->delivery_slots_tb_name,
        array(
          'id' => $id
        )
      );

      return $delete;

    }


    /**
     * get delivery slots
     * @return NULL|Array
     * @version 2.0.3
    */
    public function get_delivery_slots() {

      $sql = "SELECT * FROM {$this->delivery_slots_tb_name}";
      $results = $this->db->get_results( $sql );

      return $results;

    }


}
