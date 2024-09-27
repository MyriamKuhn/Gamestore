<?php

namespace App\Repository;

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
    $stmt->bindValue(':storeName', $storeName, \PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetchColumn();
  }
  
}