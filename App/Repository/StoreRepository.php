<?php

namespace App\Repository;

use App\Model\Store;

class StoreRepository extends MainRepository
{

  // Récupération de tous les magasins
  public function getAllStores(): array
  {
    $query = "SELECT * FROM store";
    $stmt = $this->pdo->query($query);
    
    return $stmt->fetchAll();
  }

  // Récupération de l'ID d'un magasin par son nom
  public function getStoreIdByName(string $storeName): int
  {
    $query = "SELECT id FROM store WHERE location = :storeName";
    $stmt = $this->pdo->prepare($query);
    $stmt->bindValue(':storeName', $storeName, $this->pdo::PARAM_STR);
    $stmt->execute();
    return $stmt->fetchColumn();
  }
  
  // Récupération d'un magasin par son ID
  public function getStoreById(int $storeId): Store|null
  {
    $query = "SELECT location FROM store WHERE id = :storeId";
    $stmt = $this->pdo->prepare($query);
    $stmt->bindValue(':storeId', $storeId, $this->pdo::PARAM_INT);
    $stmt->execute();
    $store = $stmt->fetch();
    if ($store) {
      return Store::createAndHydrate($store);
    } else {
      return null;
    }
  }

  
}