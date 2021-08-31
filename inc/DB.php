<?php
namespace GRON;
defined('ABSPATH') or exit;


class DB {

  private $db;
  private $shop_timings_tb_name;
  private $delivery_slots_tb_name;
  private $delivery_schedule_tb_name;

  public function __construct() {
    global $wpdb;
    $this->db = $wpdb;
    $this->shop_timings_tb_name = $wpdb->prefix . 'gron_shop_timings';
    $this->delivery_slots_tb_name = $wpdb->prefix . 'gron_delivery_slots';
    $this->delivery_schedule_tb_name = $wpdb->prefix . 'gron_delivery_schedule';
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
        start_time TIME DEFAULT '00:00:00',
        end_time TIME DEFAULT '00:00:00',
        PRIMARY KEY (id)
      ) $charset_collate;";

      $query_delivery_slots = "CREATE TABLE $this->delivery_slots_tb_name (
        id INT NOT NULL AUTO_INCREMENT,
        time_form TIME NOT NULL,
        time_to TIME NOT NULL,
        PRIMARY KEY (id)
      ) $charset_collate;";

      $query_delivery_schedule = "CREATE TABLE $this->delivery_schedule_tb_name (
        id INT NOT NULL AUTO_INCREMENT,
        order_id INT NOT NULL,
        schedule_date INT NOT NULL,
        schedule_time INT NOT NULL,
        collection_type INT NOT NULL,
        PRIMARY KEY (id)
      ) $charset_collate;";

      require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
      dbDelta( $query_shop_timings );
      dbDelta( $query_delivery_slots );
      dbDelta( $query_delivery_schedule );

      // insert initial values for shop timings
      if( !$this->has_shop_timings_data() ) {
        $this->insert_shop_timings();
      }

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
  private function print_error( $code, $message, $data ) {
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
   * @return NULL
   * @version 2.0.1
  */
  private function update_shop_timings( $data ) {

    $update = $this->db->update(
      array(
        'start_time' => $data['start_time'],
        'end_time' => $data['end_time']
      ),
      array(
        'day_name' => $data['day_name']
      )
    );

    return $update;

  }

  /**
   * get shop timings
   * @return NULL
   * @version 2.0.1
  */
  public function get_shop_timings() {
    $sql = "SELECT * FROM {$this->shop_timings_tb_name}";
    $result = $this->db->get_results( $sql );
    return $result;
  }

  /**
    * Has data in Shop Timings
  */
  private function has_shop_timings_data() {

    $sql = "SELECT COUNT(*) FROM {$this->shop_timings_tb_name}";
    $result = $this->db->get_var( $sql );

    return (bool) $result;
  }

}
