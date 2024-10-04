<?php

namespace App\Repository;

use App\Model\User;
use App\Tools\Security;

class UserRepository extends MainRepository
{

  // Ajout d'un utilisateur
  public function addUser(User $user): bool
  {
    $query = 'INSERT INTO app_user (first_name, last_name, address, postcode, city, email, password, role, fk_store_id) 
    VALUE (:first_name, :last_name, :address, :postcode, :city, :email, :password, :role, :fk_store_id)';

    $stmt = $this->pdo->prepare($query);
    $stmt->bindValue(':first_name', $user->getFirst_name(), $this->pdo::PARAM_STR);
    $stmt->bindValue(':last_name', $user->getLast_name(), $this->pdo::PARAM_STR);
    $stmt->bindValue(':address', $user->getAddress(), $this->pdo::PARAM_STR);
    $stmt->bindValue(':postcode', $user->getPostcode(), $this->pdo::PARAM_INT);
    $stmt->bindValue(':city', $user->getCity(), $this->pdo::PARAM_STR);
    $stmt->bindValue(':email', $user->getEmail(), $this->pdo::PARAM_STR);
    $stmt->bindValue(':password', Security::securePassword($user->getPassword()), $this->pdo::PARAM_STR);
    $stmt->bindValue(':role', $user->getRole(), $this->pdo::PARAM_STR);
    $stmt->bindValue(':fk_store_id', $user->getFk_store_id(), $this->pdo::PARAM_INT);
    
    return $stmt->execute();
  }

  // Mise à jour du statut vérifié d'un utilisateur
  public function updateUserStatus(User $user): bool
  {
    $query = 'UPDATE app_user SET is_verified = 1 WHERE id = :id';

    $stmt = $this->pdo->prepare($query);
    $stmt->bindValue(':id', $user->getId(), $this->pdo::PARAM_INT);
    
    return $stmt->execute();
  }

  // Récupération d'un utilisateur par son id
  public function getUserById(int $id): User|null
  {
    $query = 'SELECT * FROM app_user WHERE id = :id';

    $stmt = $this->pdo->prepare($query);
    $stmt->bindValue(':id', $id, $this->pdo::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch();
    if ($user) {
      return User::createAndHydrate($user);
    } else {
      return null;
    }
  }

  // Récupération d'un utilisateur par son email
  public function getUserByEmail(string $email): User|null
  {
    $query = 'SELECT * FROM app_user WHERE email = :email';

    $stmt = $this->pdo->prepare($query);
    $stmt->bindValue(':email', $email, $this->pdo::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch();
    if ($user) {
      return User::createAndHydrate($user);
    } else {
      return null;
    }
  }

  // Mise ne place d'un token de vérification
  public function setToken(string $token, int $userId): bool
  {
    $query = 'UPDATE app_user SET token = :token, expires_at = DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE id = :id';

    $stmt = $this->pdo->prepare($query);
    $stmt->bindValue(':token', $token, $this->pdo::PARAM_STR);
    $stmt->bindValue(':id', $userId, $this->pdo::PARAM_INT);
    
    return $stmt->execute();
  }

  // Vérification de l'expiration du token
  public function checkToken(string $token): bool
  {
    $query = 'SELECT expires_at FROM app_user WHERE token = :token';

    $stmt = $this->pdo->prepare($query);
    $stmt->bindValue(':token', $token, $this->pdo::PARAM_STR);
    $stmt->execute();

    if ($stmt->rowCount() === 0) {
      return false; 
    }

    $expiresAt = $stmt->fetchColumn();

    $currentDate = new \DateTime();
    $expiresAt = new \DateTime($expiresAt);

    return $currentDate < $expiresAt;
  }

  // Mise à jour du mot de passe et suppression du token
  public function resetPassword(string $password, string $token): bool
  {
    $query = 'UPDATE app_user SET password = :password, token = NULL, expires_at = NULL WHERE token = :token';

    $stmt = $this->pdo->prepare($query);
    $stmt->bindValue(':password', Security::securePassword($password), $this->pdo::PARAM_STR);
    $stmt->bindValue(':token', $token, $this->pdo::PARAM_STR);
    
    return $stmt->execute();
  }

  // Mise à jour des informations de l'utilisateur
  public function updateUser(int $userId, string $firstname, string $lastname, string $address, int $postcode, string $city): User|bool
  {
    $query = 'UPDATE app_user SET first_name = :first_name, last_name = :last_name, address = :address, postcode = :postcode, city = :city WHERE id = :id';

    $stmt = $this->pdo->prepare($query);
    $stmt->bindValue(':first_name', $firstname, $this->pdo::PARAM_STR);
    $stmt->bindValue(':last_name', $lastname, $this->pdo::PARAM_STR);
    $stmt->bindValue(':address', $address, $this->pdo::PARAM_STR);
    $stmt->bindValue(':postcode', $postcode, $this->pdo::PARAM_INT);
    $stmt->bindValue(':city', $city, $this->pdo::PARAM_STR);
    $stmt->bindValue(':id', $userId, $this->pdo::PARAM_INT);

    if ($stmt->execute()) {
      $user = $this->getUserById($userId);
      return $user;
    } else {
      return false;
    }
  }

  // Mise à jour du gamestore le plus proche
  public function updateUserStore(int $userId, int $storeId): User|bool
  {
    $query = 'UPDATE app_user SET fk_store_id = :fk_store_id WHERE id = :id';

    $stmt = $this->pdo->prepare($query);
    $stmt->bindValue(':fk_store_id', $storeId, $this->pdo::PARAM_INT);
    $stmt->bindValue(':id', $userId, $this->pdo::PARAM_INT);
    
    if ($stmt->execute()) {
      $user = $this->getUserById($userId);
      return $user;
    } else {
      return false;
    }
  }

  // Mise à jour du mot de passe
  public function updateUserPassword(int $userId, string $password): User|bool
  {
    $query = 'UPDATE app_user SET password = :password WHERE id = :id';

    $stmt = $this->pdo->prepare($query);
    $stmt->bindValue(':password', Security::securePassword($password), $this->pdo::PARAM_STR);
    $stmt->bindValue(':id', $userId, $this->pdo::PARAM_INT);
    
    if ($stmt->execute()) {
      $user = $this->getUserById($userId);
      return $user;
    } else {
      return false;
    }
  }

  // Récupération de tous les utilisateurs d'un magasin
  public function findAllUsersByStore(int $storeId): array
  {
    $query = 'SELECT 
      au.id AS user_id,
      CONCAT(au.first_name, " ", last_name) AS user_name,
      au.email AS user_address
      FROM app_user AS au 
      WHERE fk_store_id = :fk_store_id AND role = "user"';

    $stmt = $this->pdo->prepare($query);
    $stmt->bindValue(':fk_store_id', $storeId, $this->pdo::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll();
  }

    // Récupération de tous les utilisateurs en général
    public function findAllUsers(): array
    {
      $query = 'SELECT 
        au.id AS user_id,
        CONCAT(au.first_name, " ", last_name) AS user_name,
        au.email AS user_address,
        s.location AS store_location,
        s.id AS store_id,
        au.role AS user_role
        FROM app_user AS au
        INNER JOIN store AS s ON au.fk_store_id = s.id';
  
      $stmt = $this->pdo->query($query);
      $stmt->execute();
  
      return $stmt->fetchAll();
    }

}