<?php

namespace Bloom\Database;

use Bloom\Config;

class Database
{
  public static $pdo = null;

  public static function connect($config)
  {
    try {
      static::$pdo = new \PDO(
        "mysql:host=". $config['host'] .";port=". $config['port'] .";dbname=". $config['name'],
        $config['user'],
        $config['pass'],
        array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
      );
    } catch(\PDOException $e) {
      throw $e;
    }
  }
}