<?php

namespace App\Repository;

use DateTime;

class UserOrderRepository extends MainRepository
{

  // Création d'un panier vide
  public function createEmptyCart(int $userId, int $storeId): bool
  {
    $query = 'INSERT INTO user_order (status, fk_app_user_id, fk_store_id) VALUE (:status, :fk_app_user_id, :fk_store_id)';

    $stmt = $this->pdo->prepare($query);
    $stmt->bindValue(':status', 'En attente', $this->pdo::PARAM_STR);
    $stmt->bindValue(':fk_app_user_id', $userId, $this->pdo::PARAM_INT);
    $stmt->bindValue(':fk_store_id', $storeId, $this->pdo::PARAM_INT);

    return $stmt->execute();
  }

  // Ajout d'une commande magasin vide et retour de son ID
  public function createEmptyOrder(int $userId, int $storeId): int
  {
    $query = 'INSERT INTO user_order (status, fk_app_user_id, fk_store_id, order_date) VALUE (:status, :fk_app_user_id, :fk_store_id, NOW())';

    $stmt = $this->pdo->prepare($query);
    $stmt->bindValue(':status', 'Magasin', $this->pdo::PARAM_STR);
    $stmt->bindValue(':fk_app_user_id', $userId, $this->pdo::PARAM_INT);
    $stmt->bindValue(':fk_store_id', $storeId, $this->pdo::PARAM_INT);
    $stmt->execute();

    return $this->pdo->lastInsertId();
  }

  // Récupération de tous les jeux d'une commande d'un utilisateur d'après son statut
  public function findAllOrdersByStatus(int $userId, string $status): array
  {
    $query = 'SELECT
      uo.id AS order_id,
      uo.order_date AS order_date,
      s.location AS store_location,
      GROUP_CONCAT(CONCAT(g.name, "," ,pl.name, "," ,guo.quantity, "," ,guo.price_at_order)) AS games
      FROM user_order AS uo
      INNER JOIN game_user_order AS guo ON guo.fk_user_order_id = uo.id
      INNER JOIN game AS g ON guo.fk_game_id = g.id
      INNER JOIN platform AS pl ON guo.fk_platform_id = pl.id
      INNER JOIN store AS s ON uo.fk_store_id = s.id
      WHERE uo.fk_app_user_id = :userId AND uo.status = :status
      GROUP BY uo.id, uo.order_date, s.location
      ORDER BY uo.order_date DESC';

    $stmt = $this->pdo->prepare($query);
    $stmt->bindValue(':userId', $userId, $this->pdo::PARAM_INT);
    $stmt->bindValue(':status', $status, $this->pdo::PARAM_STR);
    $stmt->execute();

    $orders = $stmt->fetchAll();

    $userOrders = [];

    if ($orders) {
      foreach ($orders as $order) {
        $order['games'] = explode(',', $order['games']);
        $order['games'] = array_chunk($order['games'], 4);
        $order['games'] = array_map(function ($game) {
          return [
            'name' => $game[0],
            'platform' => $game[1],
            'quantity' => $game[2],
            'price' => $game[3]
          ];
        }, $order['games']);

        $userOrders[] = $order;
      }
    }
    return $userOrders;
  }

  // Récupération d'une commande d'un utilisateur d'après son ID
  public function findOrderById(int $orderId): array
  {
    $query = 'SELECT
      uo.id AS order_id,
      uo.order_date AS order_date,
      s.id AS store_id,
      s.location AS store_location,
      GROUP_CONCAT(CONCAT(g.id, "," ,g.name, "," ,pl.id, "," ,pl.name, "," ,guo.quantity, "," ,guo.price_at_order)) AS games,
      CONCAT(au.first_name, " ", au.last_name) AS order_user,
      CONCAT(au.address, " ", au.postcode, " ", au.city) AS order_address
      FROM user_order AS uo
      INNER JOIN game_user_order AS guo ON guo.fk_user_order_id = uo.id
      INNER JOIN game AS g ON guo.fk_game_id = g.id
      INNER JOIN platform AS pl ON guo.fk_platform_id = pl.id
      INNER JOIN store AS s ON uo.fk_store_id = s.id
      INNER JOIN app_user AS au ON uo.fk_app_user_id = au.id
      WHERE uo.id = :orderId';

    $stmt = $this->pdo->prepare($query);
    $stmt->bindValue(':orderId', $orderId, $this->pdo::PARAM_INT);
    $stmt->execute();

    $order = $stmt->fetch();

    if ($order) {
      $order['games'] = explode(',', $order['games']);
      $order['games'] = array_chunk($order['games'], 6);
      $order['games'] = array_map(function ($game) {
        return [
          'game_id' => $game[0],
          'name' => $game[1],
          'platform_id' => $game[2],
          'platform' => $game[3],
          'quantity' => $game[4],
          'price' => $game[5]
        ];
      }, $order['games']);
    }
    return $order;
  }

  // Récupération de l'ID de la commande qui correspond au panier en cours de l'utilisateur
  public function findCartId(int $userId): int
  {
    $query = 'SELECT id FROM user_order WHERE fk_app_user_id = :userId AND status = "En attente"';

    $stmt = $this->pdo->prepare($query);
    $stmt->bindValue(':userId', $userId, $this->pdo::PARAM_INT);
    $stmt->execute();

    $cartId = $stmt->fetchColumn();
    
    if ($cartId) {
      return $cartId;
    } else {
      return 0;
    }
  }

  // Suppression du panier de l'utilisateur
  public function deleteCart(int $cartId): bool
  {
    $query = 'DELETE FROM user_order WHERE id = :cartId';

    $stmt = $this->pdo->prepare($query);
    $stmt->bindValue(':cartId', $cartId, $this->pdo::PARAM_INT);

    return $stmt->execute();
  }

  // Validation de la commande de l'utilisateur avec ajout de date de retrait
  public function validateOrder(int $cartId, DateTime $pickupDate): bool
  {
    $query = 'UPDATE user_order SET status = "Validée", order_date = :pickupDate  WHERE id = :cartId';

    $stmt = $this->pdo->prepare($query);
    $stmt->bindValue(':cartId', $cartId, $this->pdo::PARAM_INT);
    $stmt->bindValue(':pickupDate', $pickupDate->format('Y-m-d'), $this->pdo::PARAM_STR);

    return $stmt->execute();
  }

  // Validation de la commande de l'employé avec ajout de date de retrait
  public function validateOrderByEmployee(int $orderId, string $status): bool
  {
    $query = 'UPDATE user_order SET status = :status, order_date = NOW()  WHERE id = :orderId';

    $stmt = $this->pdo->prepare($query);
    $stmt->bindValue(':orderId', $orderId, $this->pdo::PARAM_INT);
    $stmt->bindValue(':status', $status, $this->pdo::PARAM_STR);

    return $stmt->execute();
  }

  // Récupération de toutes les commandes par magasin
  public function findAllOrdersByStore(int $storeId): array|bool
  {
    $query = 'SELECT
      uo.id AS order_id,
      uo.order_date AS order_date,
      uo.status AS order_status,
      uo.fk_app_user_id AS user_id,
      CONCAT(au.first_name, " ", au.last_name) AS user_name,
      au.email AS user_address
      FROM user_order AS uo
      INNER JOIN game_user_order AS guo ON guo.fk_user_order_id = uo.id
      INNER JOIN app_user AS au ON uo.fk_app_user_id = au.id
      WHERE uo.fk_store_id = :storeId AND uo.status != "En attente"
      GROUP BY uo.id
      ORDER BY uo.order_date DESC';

    $stmt = $this->pdo->prepare($query);
    $stmt->bindValue(':storeId', $storeId, $this->pdo::PARAM_INT);
    $stmt->execute();

    $orders = $stmt->fetchAll();

    if ($orders) {
      return $orders;
    } else {
      return false;
    }
  }

}