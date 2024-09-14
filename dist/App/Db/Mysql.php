<?php

namespace App\Db;

use config\Dotenv;

class Mysql
{
  // Database configuration
  private ?\PDO $pdo = null;
  private static ?self $_instance = null;

  // Constructor
  private function __construct()
  {
    $dotenv = new DotEnv(_ROOTPATH_.'/.env');
    $dotenv->load();
  }
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
        echo "Erreur de connexion à la base de données : " . $e->getMessage();
        die();
      }
    }
    return $this->pdo;
  }
}
