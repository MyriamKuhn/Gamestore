<?php

namespace App\Repository; 

use App\Model\Pegi;

class PegiRepository extends MainRepository
{

  // Récupération d'un Pegi par son id
  public function getPegiById(int $id): Pegi|bool
  {
    $query = 'SELECT * FROM pegi WHERE id = :id';
    $stmt = $this->pdo->prepare($query);
    $stmt->bindValue(':id', $id, $this->pdo::PARAM_INT);
    $stmt->execute();
    $pegi = $stmt->fetch();

    if ($pegi) {
      return Pegi::createAndHydrate($pegi);
    } else {
      return false;
    }
  }

  // Récupération de tous les Pegi
  public function getAllPegi(): array
  {
    $query = 'SELECT * FROM pegi';
    $stmt = $this->pdo->query($query);
    
    return $stmt->fetchAll();
  }

}