<?php
namespace GRON;
defined('ABSPATH') or exit;

use GRON\MySQL;
use GRON\SQLite;

class Activation {

  public function __construct() {

    // Create Table in MySQL Database
    $mysql = new MySQL();
    $mysql->create_tables();

    // Create Table in SQLite Database
    $sqlite = new SQLite();
    $sqlite->create_tables();

  }

}
