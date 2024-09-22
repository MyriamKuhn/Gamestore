<?php

namespace App\Repository;

use App\Model\User;

class UserRepository extends MainRepository
{

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
    $stmt->bindValue(':password', password_hash($user->getPassword(), PASSWORD_DEFAULT), $this->pdo::PARAM_STR);
    $stmt->bindValue(':role', $user->getRole(), $this->pdo::PARAM_STR);
    $stmt->bindValue(':fk_store_id', $user->getFk_store_id(), $this->pdo::PARAM_INT);
    
    return $stmt->execute();
  }

}