<?php

namespace App\Repository;

use App\Model\Sale;
use DateTime;
use MongoDB\BSON\UTCDateTime;


class SalesRepository extends MongoRepository
{

  public function setOneSale(Sale $sale): string
  {
    $date = new UTCDateTime((new DateTime())->getTimestamp() * 1000);

    $result = $this->collection->insertOne([
      'id' => $sale->getId(),
      'name' => $sale->getName(),
      'genre' => $sale->getGenre(),
      'platform' => $sale->getPlatform(),
      'store' => $sale->getStore(),
      'price' => $sale->getPrice(),
      'quantity' => $sale->getQuantity(),
      'date' => $date
    ]);

    return $result->getInsertedId();
  }

  public function getOneSale(int $id): Sale
  {
    $result = $this->collection->findOne(['id' => $id]);
    $date = $result['date']->toDateTime();

    $sale = new Sale();
    $sale->setId($result['id']);
    $sale->setName($result['name']);
    $sale->setGenre($result['genre']);
    $sale->setPlatform($result['platform']);
    $sale->setStore($result['store']);
    $sale->setPrice($result['price']);
    $sale->setQuantity($result['quantity']);
    $sale->setDate($date);

    return $sale;
  }

  public function getAllSales(): array
  {
    $result = $this->collection->find();
    $sales = [];
    foreach ($result as $sale) {
      $date = $sale['date']->toDateTime();

      $sale = new Sale();
      $sale->setId($sale['id']);
      $sale->setName($sale['name']);
      $sale->setGenre($sale['genre']);
      $sale->setPlatform($sale['platform']);
      $sale->setStore($sale['store']);
      $sale->setPrice($sale['price']);
      $sale->setQuantity($sale['quantity']);
      $sale->setDate($date);
      $sales[] = $sale;
    }

    return $sales;
  }
  
}