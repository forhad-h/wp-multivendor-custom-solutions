<?php
namespace GRON;
// prevent direct access
defined( 'ABSPATH' ) or exit;

/**
* All CRUD operations for SQLite
*/

class CRUD_SQLite {

  /** @var $sqlite instance of SQLite */
  private $sqlite;

  /** @var $available_delivery_boys_table_name Table name of Delivery boy notofication queue */
  private $available_delivery_boys_table_name;


  public function __construct() {

    if( $this->sqlite == null ) {

        $this->sqlite = new \PDO( "sqlite:" . GRON_DIR_PATH . "db/delivery_notifications.db" );

    }

    $this->available = 'available_delivery_boys';

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

    $sql  = "INSERT INTO {$this->available_delivery_boys_table_name}(vendor_id, order_id, boy_id)";
    $sql .= "VALUES(:vendor_id, :order_id, :boy_id)";

    $statement = $this->sqlite->prepare( $sql );

    $statement->bindValue( ':vendor_id', $vendor_id );
    $statement->bindValue( ':order_id', $order_id );
    $statement->bindValue( ':boy_id', $boy_id );

    $statement->execute();

    return $this->sqlite->lastInsertId();

  }


}
