<?php

namespace App\Db;

require_once __DIR__ . '/../../vendor/autoload.php';

use Dotenv\Dotenv;

class Mysql
{

  // Constructor
  private function __construct()
  {
    $dotenv = new Dotenv(__DIR__ . '/../..');
    $dotenv->load();
  }

  // Database configuration
  private ?\PDO $pdo = null;
  private static ?self $_instance = null;

  //singleton
  public static function getInstance(): self
  {
    if (is_null(self::$_instance)) {
      self::$_instance = new Mysql();
    }
    return self::$_instance;
  }

  public function getPDO(): \PDO
  {
    if (is_null($this->pdo)) {
      try {
        $this->pdo = new \PDO("mysql:host=".$_ENV['DB_HOST'].";dbname=".$_ENV['DB_NAME'],$_ENV['DB_USER'], $_ENV['DB_PASSWORD']);
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
      } catch (\PDOException $e) {
        echo _ERORR_MESSAGE_ . "(Erreur : " . $e->getCode() . ")";
        die();
      }
    }
    return $this->pdo;
  }
  
}
