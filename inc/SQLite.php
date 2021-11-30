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

  /** @var $delivery_notifications Table name of Order Notifications */
  private $delivery_notifications;


  public function __construct() {

    if( $this->pdo == null ) {
      // Initialize PDO with SQLite
      $this->pdo = new \PDO( SQLite_FILE_PATH );

    }

    $this->delivery_notifications = 'delivery_notifications';

  }

  /**
   * Create Tables
  */
  public function create_tables() {

    $queries = array(
      "CREATE TABLE {$this->delivery_notifications} (
      	od_id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
        manage_by TEXT NOT NULL,
      	vendor_id INTEGER NOT NULL,
      	order_id INTEGER NOT NULL,
      	boy_id INTEGER,
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
  *    ['manage_by'] => (String) ID of the vendor
  *    ['vendor_id'] => (Int) ID of the vendor
  *    ['order_id'}  => (Int) ID of the order
  *    ['boy_id'}  => (Int) ID of the boy
  *    ['status'] => (String) ID of the vendor
  *    ['created_at'] => (DateTime) ID of the vendor
  *
  * @return Null|Int insert ID
  */
  public function insert_delivery_notification( $data ) {

    $manage_by  = $data['manage_by'];
    $vendor_id  = $data['vendor_id'];
    $order_id   = $data['order_id'];
    $boy_id     = $data['boy_id'];
    $status     = $data['status'];
    $created_at = date('Y-m-d H:i:s');

    $sql  = "INSERT INTO {$this->delivery_notifications}(manage_by,vendor_id,order_id,boy_id,status,created_at)";
    $sql .= " VALUES(:manage_by,:vendor_id,:order_id,:boy_id,:status,:created_at)";

    $statement = $this->pdo->prepare( $sql );

    $statement->execute(
      array(
        ':manage_by'  => $manage_by,
        ':vendor_id'  => $vendor_id,
        ':order_id'   => $order_id,
        ':boy_id'     => $boy_id,
        ':status'     => $status,
        ':created_at' => $created_at
      )
    );

    return $this->pdo->lastInsertId();

  }


}
