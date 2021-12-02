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

  /** @var $delivery_notifications_table_name Table name of Order Notifications */
  private $delivery_notifications_table_name;


  public function __construct() {

    if( $this->pdo == null ) {
      // Initialize PDO with SQLite
      $this->pdo = new \PDO( SQLite_FILE_PATH );

    }

    $this->delivery_notifications_table_name = 'delivery_notifications';

  }

  /**
   * Create Tables
  */
  public function create_tables() {

    $queries = array(
      "CREATE TABLE {$this->delivery_notifications_table_name} (
      	dn_id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
        manage_by TEXT NOT NULL,
      	vendor_id INTEGER NOT NULL,
      	order_id INTEGER NOT NULL,
      	boy_id INTEGER,
        status TEXT NOT NULL,
        accepted_by INTEGER,
        status_msg TEXT,
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

    $sql  = "INSERT INTO {$this->delivery_notifications_table_name}(manage_by,vendor_id,order_id,boy_id,status,created_at)";
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

  /**
  * Get delivery notifications
  * @param Int $user_id [Optional] ID of the user
  * @param Int $order_id [Optional] ID of the order
  * @param String $get_for Notifications for admin, vendor or delivery boy
  * @param String $status status of the notification pending, accepted etc.
  * @version 2.1.3
  * @return NULL|Array
  */
  public function get_delivery_notifications( $user_id = null, $order_id = null, $get_for, $status ){

    $sql = "SELECT * FROM {$this->delivery_notifications_table_name} WHERE status='{$status}'";

    // query for admin
    if( $get_for === 'admin' ) $sql .= " AND manage_by='admin'";

    // query for vendor
    elseif( $get_for === 'vendor' ) $sql .= " AND manage_by='vendor' AND vendor_id={$user_id}";

    // query for delivery boy
    elseif( $get_for === 'delivery_boy' ) $sql .= " AND boy_id={$user_id}";

    // if order_id provided
    if( $order_id ) $sql .= " AND order_id={$order_id}";

    $stmt = $this->pdo->query( $sql );

    $notifications = array();
    $order_ids = array();
    while( $row = $stmt->fetch( \PDO::FETCH_ASSOC ) ) {

      $order_id = $row['order_id'];

      // prevent duplication based on order ID
      if( in_array( $order_id, $order_ids ) ) continue;

      $order_ids[] = $order_id;

      $notifications[] = array(
        'manage_by'  => $row['manage_by'],
        'vendor_id'  => $row['vendor_id'],
        'order_id'  => $order_id,
        'boy_id'  => $row['boy_id'],
        'status_msg'  => $row['status_msg'],
        'status'  => $row['status'],
        'created_at'  => $row['created_at'],
      );
    }

    return $notifications;

  }

  /**
  * Update delivery notification
  * @param Int $boy_id ID of the user
  * @param Int $order_id ID of the order
  * @version 2.1.4
  * @return NULL|Array
  */
  function update_delivery_notification( $boy_id, $order_id ) {

    // SQL statement to update status of a task to completed
    $sql = "UPDATE  {$this->delivery_notifications_table_name} SET accepted_by=:boy_id WHERE boy_id=:boy_id AND order_id=:order_id";

    $stmt = $this->pdo->prepare($sql);

    // passing values to the parameters
    $stmt->bindValue(':boy_id', $boy_id);
    $stmt->bindValue(':order_id', $order_id);

    // execute the update statement
    $stmt->execute();
    return $stmt->rowCount();

  }

  /**
  * Delete delivery notification
  * @param Int $boy_id ID of the user
  * @param Int $order_id ID of the order
  * @version 2.1.4
  * @return NULL|Array
  */
  function delete_delivery_notification( $boy_id, $order_id ) {

    $sql = "DELETE FROM {$this->delivery_notifications_table_name} WHERE boy_id=:boy_id AND order_id=:order_id";

    $stmt = $this->pdo->prepare( $sql );

    $stmt->bindValue(':boy_id', $boy_id);
    $stmt->bindValue(':order_id', $order_id);

    $stmt->execute();

    return $stmt->rowCount();

  }


}
