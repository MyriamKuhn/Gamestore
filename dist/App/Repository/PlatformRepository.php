<?php

namespace App\Repository;

class PlatformRepository extends MainRepository
{

  public function getAllPlatforms(): array
  {
    $query = "SELECT * FROM platform";
    $stmt = $this->pdo->query($query);
    return $stmt->fetchAll();
  }
  
}