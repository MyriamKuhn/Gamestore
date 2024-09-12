<?php

namespace App\Repository;

use App\Model\Game;

class GamesRepository extends MainRepository
{

  public function getGames()
  {
    $query = 'SELECT * FROM games';
    $stmt = $this->pdo->prepare('SELECT * FROM game');
    $stmt->execute();
    $games = $stmt->fetchAll();

    $gamesArray = [];

    if ($games) {
      foreach ($games as $game) {
        $gamesArray[] = Game::createAndHydrate($game);
      }
    }
    return $gamesArray;
  }

}