<?php
namespace GRON;
// prevent direct access
defined( 'ABSPATH' ) or exit;

/**
* All CRUD operations for SQLite
*/

class SQLite {

  /** @var $pdo instance of SQLite */
  private $pdo;

  /** @var $available_delivery_boys_table_name Table name of Delivery boy notofication queue */
  private $available_delivery_boys_table_name;

  /** @var $order_notifications_table_name Table name of Order Notifications */
  private $order_notifications_table_name;


  public function __construct() {

    if( $this->pdo == null ) {
      // Initialize PDO with SQLite
      $this->pdo = new \PDO( SQLite_FILE_PATH );

    }

    $this->available_delivery_boys_table_name = 'available_delivery_boys';
    $this->order_notifications_table_name     = 'order_notifications';

  }

  /**
   * Create Tables
  */
  public function create_tables() {

    $queries = array(
      "CREATE TABLE {$this->available_delivery_boys_table_name} (
      	id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
      	vendor_id INTEGER NOT NULL,
      	order_id INTEGER NOT NULL,
      	boy_id INTEGER NOT NULL
      )",
      "CREATE TABLE {$this->order_notifications_table_name} (
      	id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
      	vendor_id INTEGER NOT NULL,
      	order_id INTEGER NOT NULL,
      	boy_id INTEGER NOT NULL,
        status TEXT NOT NULL,
        created_at TEXT NOT NULL
      )",
    );

    // Excecute all Queries
    foreach( $queries as $query ) {
      $this->pdo->exec( $query );
    }

  }

  /**
  * Insert Available Delivery boy
  * @param Array $data data to insert
  *
  *    ['vendor_id'] => (Int) ID of the vendor
  *    ['order_id'}  => (Int) ID of the order
  *    ['boy_id'}  => (Int) ID of the boy
  *
  * @return Null|Int insert ID
  */
  public function insert_available_delivery_boy( $data ) {

    $vendor_id = $data['vendor_id'];
    $order_id  = $data['order_id'];
    $boy_id  = $data['boy_id'];

    $sql  = "INSERT INTO {$this->available_delivery_boys_table_name}(vendor_id,order_id,boy_id)";
    $sql .= " VALUES(:vendor_id,:order_id,:boy_id)";

    $statement = $this->pdo->prepare( $sql );

    $statement->execute(
      array(
        ':vendor_id' => $vendor_id,
        ':order_id' => $order_id,
        ':boy_id' => $boy_id,
      )
    );

    return $this->pdo->lastInsertId();

  }


}
