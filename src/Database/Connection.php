<?php

namespace App\Database;

use PDO;
use PDOException;
use App\Logging\LoggerManage;

class Connection
{

  public static function getConnection()
  {

    $log = new LoggerManage();

    $usr = $_ENV['USR'];
    $passw = $_ENV['PASSW'];
    $db = $_ENV['DB_NAME'];
    $host = $_ENV['HOST'];

    try {
      $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";

      return new PDO($dsn, $usr, $passw, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
      ]);
    } catch (PDOException $e) {
      $log->Logger('error', 'PDOException - Connection DB: ' . $e->getMessage() . "\n StackTrace: " . $e->getTraceAsString());
    }
  }
}

