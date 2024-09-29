<?php

namespace App\Repository;

class GamesRepository extends MainRepository
{

  // Réccupération des détails d'un jeu en fonction de son identifiant unique pour hydrater le header de la page de détails d'un jeu
  public function getGameById(int $id): array
  {
    $query = "SELECT
    g.id AS game_id,
    g.name AS game_name,
    g.description AS game_description,
    GROUP_CONCAT(DISTINCT i.name) AS images,
    GROUP_CONCAT(DISTINCT ge.name SEPARATOR ', ') AS genre_name
    FROM game AS g
    INNER JOIN image AS i ON g.id = i.fk_game_id
    INNER JOIN game_genre AS gg ON g.id = gg.fk_game_id
    INNER JOIN genre AS ge ON gg.fk_genre_id = ge.id
    WHERE g.id = :id";

    $stmt = $this->pdo->prepare($query);
    $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
    $stmt->execute();
    $game = $stmt->fetch();

    $game['images'] = explode(',', $game['images']);

    return $game;
  }

}