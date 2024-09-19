<?php

namespace App\Repository;

class StoreRepository extends MainRepository
{

  public function getAllStores(): array
  {
    $query = "SELECT * FROM store";
    $stmt = $this->pdo->query($query);
    return $stmt->fetchAll();
  }

}