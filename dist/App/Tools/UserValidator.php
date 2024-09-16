<?php

namespace App\Tools;  

class UserValidator
{

  public static function validateEmail(string $email): bool
  {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
  }

  public static function validatePassword(string $password): bool
  {
    return strlen($password) >= 8;
  }

  public static function validateUsername(string $username): bool
  {
    return strlen($username) >= 4;
  }

  public static function validateRole(string $role): bool
  {
    return in_array($role, ['user', 'admin', 'employe']);
  }
  
}