<?php

namespace App\Repository;

require_once __DIR__ . '/../../vendor/autoload.php';

use Dotenv\Dotenv;
use App\Db\MongoDb;
use MongoDB\Client;

class MongoRepository
{

  protected Client $client;
  protected $collection;

  public function __construct()
  {
    $dotenv = new Dotenv(__DIR__ . '/../..');
    $dotenv->load();

    $mongo = MongoDb::getInstance();
    $this->client = $mongo->getClient();
    $database = $this->client->selectDatabase($_ENV['MONGODB_DATABASE']);
    $this->collection = $database->selectCollection($_ENV['MONGODB_COLLECTION']);
  }

}
