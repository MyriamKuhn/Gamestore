<?php

namespace App\Repository;

class ImageRepository extends MainRepository
{

  // Récupération d'une image par son id
  public function getImagesByGameId(int $id): array
  {
    $query = 'SELECT image.name FROM image WHERE fk_game_id = :id';
    $stmt = $this->pdo->prepare($query);
    $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
    $stmt->execute();
    $gameImages = $stmt->fetchAll();

    return $gameImages;
  }

}