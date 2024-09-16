<?php

namespace App\Tools;

class Security
{
  // Sécurisation des inputs
  public static function secureInput(string $input): string
  {
    return htmlspecialchars(trim($input));
  }

  // Sécurisation des emails
  public static function secureEmail(string $email): string
  {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
  }

  // Sécurisation des mots de passe
  public static function securePassword(string $password): string
  {
    return password_hash($password, PASSWORD_DEFAULT);
  }

  // Vérification des mots de passe
  public static function verifyPassword(string $password, string $hash): bool
  {
    return password_verify($password, $hash);
  }

  // Vérification de la session
  public static function isLogged(): bool
  {
      return isset($_SESSION['user']);
  }

  // Vérification du rôle utilisateur
  public static function isUser(): bool
  {
      return isset($_SESSION['user']) && $_SESSION['user']['role'] === 'user';
  }

  // Vérification du rôle administrateur
  public static function isAdmin(): bool
  {
      return isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin';
  }

  // Vérification du rôle employé
  public static function isEmploye(): bool
  {
      return isset($_SESSION['user']) && $_SESSION['user']['role'] === 'employe';
  }

  // Récupération de l'ID de l'utilisateur connecté
  public static function getCurrentUserId(): int|bool
  {
      return (isset($_SESSION['user']) && isset($_SESSION['user']['id'])) ? $_SESSION['user']['id']: false;
  }

}
