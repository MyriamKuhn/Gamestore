<?php

namespace App\Repository;

use App\Model\Verification;

class VerificationRepository extends MainRepository
{

  // Création d'un code de vérification
  public function createVerification(int $verification_code, int $fk_app_user_id): bool
  {
    $query = "INSERT INTO email_verification (verification_code, created_at, fk_app_user_id) VALUES (:verification_code, NOW(), :fk_app_user_id)";
    $stmt = $this->pdo->prepare($query);
    $stmt->bindValue(':verification_code', $verification_code, \PDO::PARAM_INT);
    $stmt->bindValue(':fk_app_user_id', $fk_app_user_id, \PDO::PARAM_INT);
    
    return $stmt->execute();
  }

  // Suppression d'un code de vérification
  public function deleteAllCodesFromUser(int $userId): bool
  {
    $query = "DELETE FROM email_verification WHERE fk_app_user_id = :fk_app_user_id";
    $stmt = $this->pdo->prepare($query);
    $stmt->bindValue(':fk_app_user_id', $userId, \PDO::PARAM_INT);

    return $stmt->execute();
  }

  // Suppression de tous les codes expirés
  public function deleteAllExpiredCodes(): bool
  {
    $query = "DELETE FROM email_verification WHERE created_at < DATE_SUB(NOW(), INTERVAL 1 HOUR)";
    $stmt = $this->pdo->prepare($query);

    return $stmt->execute();
  }

  // Récupération du dernier code de vérification par l'id de l'utilisateur
  public function getLastVerificationByUserId(int $userId): Verification|null
  {
    $query = "SELECT * FROM email_verification WHERE fk_app_user_id = :fk_app_user_id ORDER BY created_at DESC LIMIT 1";
    $stmt = $this->pdo->prepare($query);
    $stmt->bindValue(':fk_app_user_id', $userId, \PDO::PARAM_INT);
    $stmt->execute();
    $verification = $stmt->fetch();
    if ($verification) {
      return Verification::createAndHydrate($verification);
    } else {
      return null;
    }
  }

}