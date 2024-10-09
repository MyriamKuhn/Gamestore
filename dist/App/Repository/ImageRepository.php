<?php

namespace App\Repository;

class ImageRepository extends MainRepository
{

  // Récupération d'une image par son id
  public function getImagesByGameId(int $id): array
  {
    $query = 'SELECT image.name FROM image WHERE fk_game_id = :id';
    $stmt = $this->pdo->prepare($query);
    $stmt->bindValue(':id', $id, $this->pdo::PARAM_INT);
    $stmt->execute();
    $gameImages = $stmt->fetchAll();

    return $gameImages;
  }

  // Suppression des images d'un jeu
  public function deleteGameImage(string $imageName): bool
  {
    $query = 'DELETE FROM image WHERE name = :imageName';

    $stmt = $this->pdo->prepare($query);
    $stmt->bindValue(':imageName', $imageName, $this->pdo::PARAM_STR);

    return $stmt->execute();
  }

  // Ajout des images d'un jeu
  public function addGameImage(int $gameId, string $imageName): bool
  {
    $query = 'INSERT INTO image (name, fk_game_id) VALUE (:imageName, :gameId)';

    $stmt = $this->pdo->prepare($query);
    
    $stmt->bindValue(':gameId', $gameId, $this->pdo::PARAM_INT);
    $stmt->bindValue(':imageName', $imageName, $this->pdo::PARAM_STR);

    return $stmt->execute();
  }
}