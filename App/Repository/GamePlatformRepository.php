<?php

namespace App\Repository;

class GamePlatformRepository extends MainRepository
{

  // Récupération de la liste des X nouveaux jeux ajoutés à la base de données
  public function getAllNewGames(int $limit): array
  {
    $query = 'SELECT
    g.id AS game_id,
    g.name AS game_name,
    p.name AS pegi_name,
    GROUP_CONCAT(DISTINCT pl.name) AS platforms,
    GROUP_CONCAT(DISTINCT i.name) AS images
    FROM game_platform AS gp
    INNER JOIN game AS g ON gp.fk_game_id = g.id
    INNER JOIN pegi AS p ON g.fk_pegi_id = p.id
    INNER JOIN platform AS pl ON gp.fk_platform_id = pl.id
    INNER JOIN image AS i ON g.id = i.fk_game_id
    INNER JOIN store AS s ON gp.fk_store_id = s.id
    WHERE gp.quantity > 0 AND gp.is_new = 1
    GROUP BY g.id, g.name, p.name
    ORDER BY g.id DESC LIMIT :limit';

    $stmt = $this->pdo->prepare($query);
    $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
    $stmt->execute();
    $games = $stmt->fetchAll();

    $allGames = [];

    if ($games) {
      foreach ($games as $game) {
        $game['images'] = explode(',', $game['images']);
        $game['platforms'] = explode(',', $game['platforms']);
        $allGames[] = $game;
      }
    }
    return $allGames;
  }

    // Récupération de 8 jeux au hasard qui sont en promotions pour la page d'accueil
    public function getReducedGamesListShort(int $limit): array
    {
      $query = 'SELECT
      g.id AS game_id,
      g.name AS game_name,
      p.name AS pegi_name,
      pl.name AS platform_name,
      GROUP_CONCAT(DISTINCT i.name) AS images,
      gp.price AS platform_price,
      gp.discount_rate AS discount_rate,
      s.location AS store_location,
      gp.is_new AS is_new
      FROM game_platform AS gp
      INNER JOIN game AS g ON gp.fk_game_id = g.id
      INNER JOIN pegi AS p ON g.fk_pegi_id = p.id
      INNER JOIN platform AS pl ON gp.fk_platform_id = pl.id
      INNER JOIN image AS i ON g.id = i.fk_game_id
      INNER JOIN store AS s ON gp.fk_store_id = s.id
      WHERE gp.is_reduced = 1 AND gp.quantity > 0
      GROUP BY gp.price, gp.discount_rate, s.location
      ORDER BY RAND()
      LIMIT :limit';
  
      $stmt = $this->pdo->prepare($query);
      $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
      $stmt->execute();
      $games = $stmt->fetchAll();
  
      $reducedGames = [];
  
      if ($games) {
        foreach ($games as $game) {
          $game['images'] = explode(',', $game['images']);
  
          $reducedGames[] = $game;
        }
      }
      return $reducedGames;
    }

  // Récupération de la liste des jeux par magasin
  public function getAllGamesByStore(int $storeId): array
  {
    $query = 'SELECT
    g.id AS game_id,
    g.name AS game_name,
    p.name AS pegi_name,
    pl.name AS platform_name,
    GROUP_CONCAT(DISTINCT ge.name) AS genre,
    GROUP_CONCAT(DISTINCT i.name) AS images,
    gp.price AS platform_price,
    gp.is_reduced AS is_reduced,
    gp.is_new AS is_new,
    gp.discount_rate AS discount_rate
    FROM game_platform AS gp
    INNER JOIN game AS g ON gp.fk_game_id = g.id
    INNER JOIN pegi AS p ON g.fk_pegi_id = p.id
    INNER JOIN platform AS pl ON gp.fk_platform_id = pl.id
    INNER JOIN game_genre AS gg ON g.id = gg.fk_game_id
    INNER JOIN genre AS ge ON gg.fk_genre_id = ge.id
    INNER JOIN image AS i ON g.id = i.fk_game_id
    WHERE gp.fk_store_id = :storeId AND gp.quantity > 0
    GROUP BY g.id, gp.price, gp.discount_rate, gp.is_reduced
    ORDER BY g.id DESC';

    $stmt = $this->pdo->prepare($query);
    $stmt->bindValue(':storeId', $storeId, \PDO::PARAM_INT);
    $stmt->execute();
    $games = $stmt->fetchAll();

    $storeGames = [];

    if ($games) {
      foreach ($games as $game) {
        $game['images'] = explode(',', $game['images']);
        $game['genre'] = explode(',', $game['genre']);
        $storeGames[] = $game;
      }
    }
    return $storeGames;
  }

  // Récupération de la liste des jeux par magasin incluant les jeux qui ne sont pas disponibles pour les employés
  public function getAllGamesByStoreForEmployes(int $storeId): array
  {
    $query = 'SELECT
    g.id AS game_id,
    g.name AS game_name,
    pl.id AS platform_id,
    pl.name AS platform_name,
    GROUP_CONCAT(DISTINCT ge.name SEPARATOR ", ") AS genres,
    gp.price AS platform_price,
    gp.is_reduced AS is_reduced,
    gp.discount_rate AS discount_rate,
    gp.quantity AS quantity
    FROM game_platform AS gp
    INNER JOIN game AS g ON gp.fk_game_id = g.id
    INNER JOIN platform AS pl ON gp.fk_platform_id = pl.id
    INNER JOIN game_genre AS gg ON g.id = gg.fk_game_id
    INNER JOIN genre AS ge ON gg.fk_genre_id = ge.id
    WHERE gp.fk_store_id = :storeId
    GROUP BY g.id, gp.price, gp.discount_rate, gp.is_reduced
    ORDER BY g.id DESC';

    $stmt = $this->pdo->prepare($query);
    $stmt->bindValue(':storeId', $storeId, \PDO::PARAM_INT);
    $stmt->execute();
    $games = $stmt->fetchAll();

    return $games;
  }

  // Récupération de la liste des jeux en promotion disponibles
  public function getAllReducedGames(): array
  {
    $query = 'SELECT
    g.id AS game_id,
    g.name AS game_name,
    g.description AS game_description,
    p.name AS pegi_name,
    pl.name AS platform_name,
    GROUP_CONCAT(DISTINCT ge.name) AS genre,
    GROUP_CONCAT(DISTINCT i.name) AS images,
    gp.price AS platform_price,
    gp.is_reduced AS is_reduced,
    gp.is_new AS is_new,
    gp.discount_rate AS discount_rate,
    gp.quantity AS quantity,
    s.location AS store_location
    FROM game_platform AS gp
    INNER JOIN game AS g ON gp.fk_game_id = g.id
    INNER JOIN pegi AS p ON g.fk_pegi_id = p.id
    INNER JOIN platform AS pl ON gp.fk_platform_id = pl.id
    INNER JOIN game_genre AS gg ON g.id = gg.fk_game_id
    INNER JOIN genre AS ge ON gg.fk_genre_id = ge.id
    INNER JOIN image AS i ON g.id = i.fk_game_id
    INNER JOIN store AS s ON gp.fk_store_id = s.id
    WHERE is_reduced = 1 AND gp.quantity > 0
    GROUP BY g.id, gp.price, gp.discount_rate, s.location';

    $stmt = $this->pdo->prepare($query);
    $stmt->execute();
    $games = $stmt->fetchAll();

    $reducedGames = [];

    if ($games) {
      foreach ($games as $game) {
        $game['genre'] = explode(',', $game['genre']);
        $game['images'] = explode(',', $game['images']);
        $reducedGames[] = $game;
      }
    }
    return $reducedGames;
  }

  // Récupération de toutes les données de prix
  public function getAllPrices(): array
  {
    $query = 'SELECT gp.price FROM game_platform AS gp';
    $stmt = $this->pdo->query($query);
    
    return $stmt->fetchAll();
  }

  // Récupération d'un jeu en fonction de son ID
  public function getGameById(int $gameId): array
  {
    $query = 'SELECT
    g.id AS game_id,
    g.name AS game_name,
    g.description AS game_description,
    p.name AS pegi_name,
    GROUP_CONCAT(DISTINCT i.name) AS images,
    GROUP_CONCAT(DISTINCT ge.name SEPARATOR ", ") AS genres,
    GROUP_CONCAT(DISTINCT CONCAT(s.location, ",", pl.name, ",", gp.price, ",", gp.is_new, ",", gp.is_reduced, ",", gp.discount_rate, ",", gp.quantity)) AS game_prices
    FROM game_platform AS gp
    INNER JOIN game AS g ON gp.fk_game_id = g.id
    INNER JOIN pegi AS p ON g.fk_pegi_id = p.id
    INNER JOIN image AS i ON g.id = i.fk_game_id
    INNER JOIN platform AS pl ON gp.fk_platform_id = pl.id
    INNER JOIN game_genre AS gg ON g.id = gg.fk_game_id
    INNER JOIN genre AS ge ON gg.fk_genre_id = ge.id
    INNER JOIN store AS s ON gp.fk_store_id = s.id
    WHERE g.id = :gameId
    GROUP BY g.id';

    $stmt = $this->pdo->prepare($query);
    $stmt->bindValue(':gameId', $gameId, \PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch();

    $game = [];

    if ($result) {
      $result['images'] = explode(',', $result['images']);
      $result['game_prices'] = explode(',', $result['game_prices']);
      $result['game_prices'] = array_chunk($result['game_prices'], 7);
      $result['game_prices'] = array_map(function ($price) {
        return [
          'location' => $price[0],
          'platform' => $price[1],
          'price' => $price[2],
          'is_new' => $price[3],
          'is_reduced' => $price[4],
          'discount_rate' => $price[5],
          'stock' => $price[6]
        ];
        }, $result['game_prices']);
        
      $game = $result;
    }

    return $game;
  }

  // Vérifier si les données d'un jeu sont correctes
  public function checkGameDatas(int $gameId, int $platformId, float $price, float $discountRate, int $location): bool
  {
    $query = 'SELECT
    gp.fk_game_id AS game_platform_id
    FROM game_platform AS gp
    WHERE gp.fk_game_id = :gameId AND gp.fk_platform_id = :platformId AND gp.price = :price AND gp.discount_rate = :discountRate AND gp.fk_store_id = :location AND gp.quantity > 0';

    $stmt = $this->pdo->prepare($query);
    $stmt->bindValue(':gameId', $gameId, \PDO::PARAM_INT);
    $stmt->bindValue(':platformId', $platformId, \PDO::PARAM_INT);
    $stmt->bindValue(':price', $price, \PDO::PARAM_STR);
    $stmt->bindValue(':discountRate', $discountRate, \PDO::PARAM_STR);
    $stmt->bindValue(':location', $location, \PDO::PARAM_INT);
    $stmt->execute();
    $game = $stmt->fetch();

    if ($game) {
      return true;
    } else {
      return false;
    }
  }

    // Vérification du stock d'un jeu
    public function checkGameStock(int $gameId, int $platformId, int $storeId): int
    {
      $query = 'SELECT quantity FROM game_platform WHERE fk_game_id = :gameId AND fk_platform_id = :platformId AND fk_store_id = :storeId';
  
      $stmt = $this->pdo->prepare($query);
      $stmt->bindValue(':gameId', $gameId, $this->pdo::PARAM_INT);
      $stmt->bindValue(':platformId', $platformId, $this->pdo::PARAM_INT);
      $stmt->bindValue(':storeId', $storeId, $this->pdo::PARAM_INT);
      $stmt->execute();
  
      $stock = $stmt->fetchColumn();
  
      return $stock;
    }

    // Ajout ou suppression du stock d'un jeu
    public function updateGameStock(int $gameId, int $platformId, int $storeId, int $quantity, string $operation = 'add'): bool
    {
      $increment = ($operation === 'add') ? $quantity : -$quantity;
  
      $query = 'UPDATE game_platform SET quantity = GREATEST(0, quantity + :increment) WHERE fk_game_id = :gameId AND fk_platform_id = :platformId AND fk_store_id = :storeId';
  
      $stmt = $this->pdo->prepare($query);
      $stmt->bindValue(':gameId', $gameId, $this->pdo::PARAM_INT);
      $stmt->bindValue(':platformId', $platformId, $this->pdo::PARAM_INT);
      $stmt->bindValue(':storeId', $storeId, $this->pdo::PARAM_INT);
      $stmt->bindValue(':increment', $increment, $this->pdo::PARAM_INT);
  
      return $stmt->execute();
    }
}

