<?php

namespace App\Repository;

use App\Model\Game;

class GamesRepository extends MainRepository
{

  public function getGames(int $limit = null): array
  {
    $query = 'SELECT game.id, game.name AS game_name, game.description, pegi.name AS pegi_name FROM game JOIN pegi ON game.fk_pegi_id = pegi.id ORDER BY game.id DESC';
    if ($limit) {
      $query .= ' LIMIT :limit';
    }
    $stmt = $this->pdo->prepare($query);
    if ($limit) $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
    $stmt->execute();
    $games = $stmt->fetchAll();

    return $games;
  }

}