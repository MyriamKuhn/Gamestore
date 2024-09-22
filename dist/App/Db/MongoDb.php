<?php

namespace App\Db;

require_once __DIR__ . '/../../vendor/autoload.php';

use Dotenv\Dotenv;
use MongoDB\Client;
use MongoDB\Exception\Exception;

class MongoDb
{

  private ?Client $client = null;
  private static ?self $_instance = null;
  private string $uri;

  // Constructor
  private function __construct()
  {
    $dotenv = new Dotenv(__DIR__ . '/../..');
    $dotenv->load();
    $this->uri = $_ENV['MONGODB_URI'];
  }

  public static function getInstance(): self
  {
    if (is_null(self::$_instance)) {
      self::$_instance = new MongoDb();
    }
    return self::$_instance;
  }

  public function getClient(): Client
  {
    if (is_null($this->client)) {
      try {
        $this->client = new Client($this->uri);

      } catch (Exception $e) {
        echo _ERORR_MESSAGE_ . "(Erreur : " . $e->getCode() . ")";
        die();
      }
    }
    return $this->client;
  }

}