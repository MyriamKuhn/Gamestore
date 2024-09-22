<?php

namespace App\Repository;

use App\Model\User;
use App\Tools\Security;

class UserRepository extends MainRepository
{

  // Ajout d'un utilisateur
  public function addUser(User $user)
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
  public function updateUserStatus(User $user)
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

}