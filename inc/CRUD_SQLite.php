<?php
namespace GRON;
// prevent direct access
defined( 'ABSPATH' ) or exit;

class CRUD_SQLite {

  private $sqlite;

  public function __construct() {

    if( $this->sqlite == null ) {

      var_dump( GRON_DIR_PATH . "db/gronsqlite.db" );

        $this->sqlite = new \PDO( "sqlite:" . GRON_DIR_PATH . "db/gronsqlite.db" );

    }

  }


}
