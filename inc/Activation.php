<?php
namespace GRON;
defined('ABSPATH') or exit;

use GRON\MySQL;
use GRON\SQLite;
use GRON\Utils;

class Activation {

  public function __construct() {

    // Prevent to create table if the version is not changed
    if( get_option( 'gron_version' ) === GRON_VERSION ) return;

    // Create Table in MySQL Database
    $mysql = new MySQL();
    $mysql->create_tables();

    // Create Table in SQLite Database
    $sqlite = new SQLite();
    $sqlite->create_tables();

    // Update version
    update_option( 'gron_version', GRON_VERSION );


  }

}
