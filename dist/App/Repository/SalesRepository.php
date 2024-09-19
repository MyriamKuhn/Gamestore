<?php

namespace App\Repository;

use App\Model\Sale;

class SalesRepository extends MongoRepository
{

  public function setOneSale(Sale $sale): void
  {
    $result = $this->collection->insertOne([
      'id' => $sale->getId(),
      'name' => $sale->getName(),
      'price' => $sale->getPrice(),
      'date' => $sale->getDate()
    ]);
  }

  public function getOneSale(int $id): Sale
  {
    $result = $this->collection->findOne(['id' => $id]);
    $sale = new Sale();
    $sale->setId($result['id']);
    $sale->setName($result['name']);
    $sale->setPrice($result['price']);
    $sale->setDate($result['date']);

    return $sale;
  }

  public function getAllSales(): array
  {
    $result = $this->collection->find();
    $sales = [];
    foreach ($result as $sale) {
      $sale = new Sale();
      $sale->setId($sale['id']);
      $sale->setName($sale['name']);
      $sale->setPrice($sale['price']);
      $sale->setDate($sale['date']);
      $sales[] = $sale;
    }

    return $sales;
  }
  
}