<?php

namespace App\Repository;

class GameUserOrderRepository extends MainRepository
{

  // Ajout ou retrait d'une quantité d'un jeu
  public function addGameInCart(int $gameId, int $platformId, int $orderId, int $quantity, float $price_at_order, string $operation = 'add'): bool
  {
    $increment = ($operation === 'add') ? $quantity : -$quantity;

    $query = 'INSERT INTO game_user_order (fk_game_id, fk_platform_id, fk_user_order_id, quantity, price_at_order) 
      VALUE (:fk_game_id, :fk_platform_id, :fk_user_order_id, :quantity, :price_at_order)
      ON DUPLICATE KEY UPDATE quantity = GREATEST(0, quantity + :increment)';

    $stmt = $this->pdo->prepare($query);
    $stmt->bindValue(':fk_game_id', $gameId, $this->pdo::PARAM_INT);
    $stmt->bindValue(':fk_platform_id', $platformId, $this->pdo::PARAM_INT);
    $stmt->bindValue(':fk_user_order_id', $orderId, $this->pdo::PARAM_INT);
    $stmt->bindValue(':quantity', $quantity, $this->pdo::PARAM_INT);
    $stmt->bindValue(':price_at_order', $price_at_order, $this->pdo::PARAM_STR);
    $stmt->bindValue(':increment', $increment, $this->pdo::PARAM_INT);
    
    return $stmt->execute();
  }

  // Suppression d'un jeu du panier
  public function removeGameFromCart(int $gameId, int $platformId, int $orderId): bool
  {
    $query = 'DELETE FROM game_user_order WHERE fk_game_id = :fk_game_id AND fk_platform_id = :fk_platform_id AND fk_user_order_id = :fk_user_order_id';

    $stmt = $this->pdo->prepare($query);
    $stmt->bindValue(':fk_game_id', $gameId, $this->pdo::PARAM_INT);
    $stmt->bindValue(':fk_platform_id', $platformId, $this->pdo::PARAM_INT);
    $stmt->bindValue(':fk_user_order_id', $orderId, $this->pdo::PARAM_INT);

    return $stmt->execute();
  }

  // Récupération du contenu du panier de l'utilisateur
  public function findCartContent(int $cartId): array
  {
    $query = 'SELECT
      g.id AS game_id,
      g.name AS game_name,
      pl.id AS platform_id,
      pl.name AS platform_name,
      guo.quantity AS quantity,
      guo.price_at_order AS price
      FROM game_user_order AS guo
      INNER JOIN game AS g ON guo.fk_game_id = g.id
      INNER JOIN platform AS pl ON guo.fk_platform_id = pl.id
      INNER JOIN user_order AS uo ON guo.fk_user_order_id = uo.id
      WHERE guo.fk_user_order_id = :cartId';

    $stmt = $this->pdo->prepare($query);
    $stmt->bindValue(':cartId', $cartId, $this->pdo::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll();
  }

}