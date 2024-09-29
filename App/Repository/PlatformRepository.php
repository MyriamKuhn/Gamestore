<?php

namespace App\Repository;

class PlatformRepository extends MainRepository
{

  // Récupération de tous les plateformes
  public function getAllPlatforms(): array
  {
    $query = "SELECT * FROM platform";
    $stmt = $this->pdo->query($query);
    return $stmt->fetchAll();
  }
  
  // Récupération de l'ID d'une plateforme par son nom
  public function getPlatformIdByName(string $platformName): int
  {
    $query = "SELECT id FROM platform WHERE name = :name";

    $stmt = $this->pdo->prepare($query);
    $stmt->bindParam(':name', $platformName, $this->pdo::PARAM_STR);
    $stmt->execute();

    return $stmt->fetchColumn();
  }
}