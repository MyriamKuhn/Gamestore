<?php

namespace App\Repository;

class GamePlatformRepository extends MainRepository
{

  public function getAllPlatformsByGameId(int $id): array|bool
  {
    $query = 'SELECT plattform.name FROM game_plattform JOIN plattform ON game_plattform.fk_plattform_id = plattform.id WHERE game_plattform.fk_game_id = :id';
    $stmt = $this->pdo->prepare($query);
    $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
    $stmt->execute();
    $gamePlatforms = $stmt->fetchAll();

    if ($gamePlatforms) {
      $platform_names = array_column($gamePlatforms, 'name');
      $unique_platform_names = array_unique($platform_names);
      return $unique_platform_names;
    } else {
      return false;
    }
  }

}

