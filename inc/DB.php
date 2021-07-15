<?php
namespace GRONC;
defined('ABSPATH') or exit;


class DB {

  private $db;
  private $table_name;

  public function __construct() {
    global $wpdb;
    $this->db = $wpdb;
    $this->table_name = $wpdb->prefix . "gron_customs";
  }

  public function create_table() {

    try {
      $charset_collate = $this->db->get_charset_collate();

      $query = "CREATE TABLE $this->table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        user_id mediumint(9) NOT NULL,
        PRIMARY KEY (id)
      ) $charset_collate;";

      require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
      dbDelta( $query );

      add_option( 'gron_version', GRONC_VERSION );
    }catch( Exception $e) {
      $this->print_error( 'db_table_creation_failed', sprintf('Error: during create %s table.', $this->table_name ), $e );
    }

  }

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

  private function print_error( $code, $message, $data ) {
    $error = new WP_Error( $code, $message, $data );
    print $error;
  }

}
