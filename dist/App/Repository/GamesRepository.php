<?php

namespace App\Repository;

use App\Model\Game;

class GamesRepository extends MainRepository
{

  function getGamesList(int $limit = null): array
  {
    $query = "SELECT
    g.id AS game_id,
    g.name AS game_name,
    g.description AS game_description,
    p.name AS pegi_name,
    GROUP_CONCAT(DISTINCT pl.name) AS platforms,
    GROUP_CONCAT(DISTINCT i.name) AS images,
    GROUP_CONCAT(DISTINCT ge.name) AS genre_name,
    GROUP_CONCAT(DISTINCT s.location,',',pl.name,',',gp.price,',',gp.is_reduced,',',gp.discount_rate,',',su.quantity) AS game_prices
    FROM game AS g
    INNER JOIN pegi AS p ON g.fk_pegi_id = p.id
    INNER JOIN game_plattform AS gp ON g.id = gp.fk_game_id
    INNER JOIN plattform AS pl ON gp.fk_plattform_id = pl.id
    INNER JOIN supply AS su ON g.id = su.fk_game_id AND gp.fk_plattform_id = su.fk_plattform_id
    INNER JOIN store AS s ON su.fk_store_id = s.id
    INNER JOIN image AS i ON g.id = i.fk_game_id
    INNER JOIN game_genre AS gg ON g.id = gg.fk_game_id
    INNER JOIN genre AS ge ON gg.fk_genre_id = ge.id
    WHERE su.quantity > 0
    GROUP BY g.id, g.name, g.description, p.name";

    if ($limit) $query .= ' ORDER BY g.id DESC LIMIT :limit';

    $stmt = $this->pdo->prepare($query);
    if ($limit) $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
    $stmt->execute();
    $games = $stmt->fetchAll();

    $gamesList = [];

    if ($games) {
      foreach ($games as $game) {
        $game['platforms'] = explode(',', $game['platforms']);
        $game['images'] = explode(',', $game['images']);
        $game['genre_name'] = explode(',', $game['genre_name']);
        $game['game_prices'] = explode(',', $game['game_prices']);
        $game['game_prices'] = array_chunk($game['game_prices'], 6);
        $game['game_prices'] = array_map(function ($price) {
          return [
            'location' => $price[0],
            'platform' => $price[1],
            'price' => $price[2],
            'is_reduced' => $price[3],
            'discount_rate' => $price[4],
            'stock' => $price[5]
          ];
        }, $game['game_prices']);

        $gamesList[] = $game;
        
      }
    }
    return $gamesList;
  }

  public function getAllReducedGames(): array
  {
    $query = 
    'SELECT
    g.id AS game_id,
    g.name AS game_name,
    g.description AS game_description,
    p.name AS pegi_name,
    pl.name AS platform_name,
    GROUP_CONCAT(DISTINCT i.name) AS images,
    GROUP_CONCAT(DISTINCT ge.name) AS genre_name,
    gp.price AS platform_price,
    gp.discount_rate AS discount_rate,
    s.location AS store_location,
    su.quantity AS stock_quantity
    FROM game AS g
    INNER JOIN pegi AS p ON g.fk_pegi_id = p.id
    INNER JOIN game_plattform AS gp ON g.id = gp.fk_game_id
    INNER JOIN plattform AS pl ON gp.fk_plattform_id = pl.id
    INNER JOIN supply AS su ON g.id = su.fk_game_id AND gp.fk_plattform_id = su.fk_plattform_id
    INNER JOIN store AS s ON su.fk_store_id = s.id
    INNER JOIN image AS i ON g.id = i.fk_game_id
    INNER JOIN game_genre AS gg ON g.id = gg.fk_game_id
    INNER JOIN genre AS ge ON gg.fk_genre_id = ge.id
    WHERE gp.is_reduced = 1 AND su.quantity > 0
    GROUP BY g.id, g.name, pl.name, s.location';

    $stmt = $this->pdo->prepare($query);
    $stmt->execute();
    $games = $stmt->fetchAll();

    $reducedGames = [];

    if ($games) {
      foreach ($games as $game) {
        $game['images'] = explode(',', $game['images']);
        $game['genre_name'] = explode(',', $game['genre_name']);

        $reducedGames[] = $game;
      }
    }
    return $reducedGames;
  }

  public function getGameById(int $id): array
  {
    $query = "SELECT
    g.id AS game_id,
    g.name AS game_name,
    g.description AS game_description,
    GROUP_CONCAT(DISTINCT i.name) AS images,
    GROUP_CONCAT(DISTINCT ge.name) AS genre_name
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