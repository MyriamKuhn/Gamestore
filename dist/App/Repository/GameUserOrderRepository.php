<?php

namespace App\Repository;

class GameUserOrderRepository extends MainRepository
{

  // Ajout d'un jeu dans le panier
  public function addGameInCart(int $gameId, int $orderId, int $quantity, float $price_at_order): void
  {
    $query = 'INSERT INTO game_user_order (fk_game_id, fk_user_order_id, quantity, price_at_order) VALUE (:fk_game_id, :fk_user_order_id, :quantity, :price_at_order)';

    $stmt = $this->pdo->prepare($query);
    $stmt->bindValue(':fk_game_id', $gameId, $this->pdo::PARAM_INT);
    $stmt->bindValue(':fk_user_order_id', $orderId, $this->pdo::PARAM_INT);
    $stmt->bindValue(':quantity', $quantity, $this->pdo::PARAM_INT);
    $stmt->bindValue(':price_at_order', $price_at_order, $this->pdo::PARAM_STR);
    $stmt->execute();

  }

}