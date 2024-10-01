<?php

namespace App\Tools;  

use App\Model\User;

class UserValidator
{

  public static function validateLastName(string $last_name): bool
  {
    $lastNameRegex = '/^[a-zA-ZÀ-ÿœŒæÆ\-\s\'\’]{3,}$/';
    if(preg_match($lastNameRegex, $last_name)) {
      return true;
    } else {
      return false;
    }
  }

  public static function validateFirstName(string $first_name): bool
  {
    $firstNameRegex = '/^[a-zA-ZÀ-ÿœŒæÆ\-\s\'\’]{3,}$/';
    if(preg_match($firstNameRegex, $first_name)) {
      return true;
    } else {
      return false;
    }
  }

  public static function validateAddress(string $address): bool
  {
    $addressRegex = '/^[a-zA-Z0-9À-ÿœŒæÆ\-\s\(\)\'\’]{3,}$/';
    if(preg_match($addressRegex, $address)) {
      return true;
    } else {
      return false;
    }
  }

  public static function validatePostcode (int $postcode): bool
  {
    $postcodeRegex = '/^[0-9]{5}$/';
    if(preg_match($postcodeRegex, $postcode)) {
      return true;
    } else {
      return false;
    }
  }

  public static function validateCity(string $city): bool
  {
    $cityRegex = '/^[a-zA-ZÀ-ÿœŒæÆ\-\s\(\)\'\’\.\,]{3,}$/';
    if(preg_match($cityRegex, $city)) {
      return true;
    } else {
      return false;
    }
  }

  public static function validateEmail(string $email): bool
  {
    $emailRegex = '/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/';
    if (preg_match($emailRegex, $email)) {
      return true;
    } else {
      return false;
    }
  }

  public static function validatePassword(string $password): bool
  {
    $passwordRegex = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_])[A-Za-z\d\W_]{15,}$/';
    if (preg_match($passwordRegex, $password)) {
      return true;
    } else {
      return false;
    }
  }

  public static function validateRole(string $role): bool
  {
    return in_array($role, ['user', 'admin', 'employe']);
  }
  
  public static function validate(User $data): array
  {
    $errors = [];

    if (!self::validateFirstName($data->getFirst_name())) {
      $errors['first_name'] = 'Le champ prénom n\'est pas valide';
    }

    if (!self::validateLastName($data->getLast_name())) {
      $errors['last_name'] = 'Le champ nom n\'est pas valide';
    }

    if (!self::validateAddress($data->getAddress())) {
      $errors['address'] = 'Le champ adresse n\'est pas valide';
    }

    if (!self::validatePostcode($data->getPostcode())) {
      $errors['postcode'] = 'Le champ code postal n\'est pas valide';
    }

    if (!self::validateCity($data->getCity())) {
      $errors['city'] = 'Le champ ville n\'est pas valide';
    }

    if (!self::validateEmail($data->getEmail())) {
      $errors['email'] = 'Le champ email n\'est pas valide';
    }

    if (!self::validatePassword($data->getPassword())) {
      $errors['password'] = 'Le champ mot de passe n\'est pas valide';
    }
    return $errors;
  }

}