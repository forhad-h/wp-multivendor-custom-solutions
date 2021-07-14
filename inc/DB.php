<?php
namespace GRONC;
defined('ABSPATH') or exit;


class DB {

  private $db;
  private $table_name;

  public function __construct() {
    global $wpdb;
    $this->db = $wpdb;
    $this->table_name = $wpdb->prefix . "gron_vendors";
  }

  public function create_table() {

    $charset_collate = $this->db->get_charset_collate();

    $query = "CREATE TABLE $this->table_name (
      id mediumint(9) NOT NULL AUTO_INCREMENT,
      vendor_id mediumint(9) NOT NULL,
      address varchar(300) NOT NULL,
      PRIMARY KEY  (id)
    ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $query );

    add_option( 'gron_version', GRONC_VERSION );

  }

  public function insert_vendor_info() {
    $this->db->insert($this->table_name, [
      'vendor_id' => 9,
      'address' => 'nothing, USA'
    ]);
  }

  public function get_vendor_info( $id ) {
    $query = "SELECT * FROM {$this->table_name} WHERE vendor_id={$id}";
    $res = $this->db->get_row( $query );
    return $res;
  }



}
