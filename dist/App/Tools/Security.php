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
    return isset($_SESSION['user']) && $_SESSION['user']['role'] === _ROLE_USER_;
  }

  // Vérification du rôle administrateur
  public static function isAdmin(): bool
  {
    return isset($_SESSION['user']) && $_SESSION['user']['role'] === _ROLE_ADMIN_;
  }

  // Vérification du rôle employé
  public static function isEmploye(): bool
  {
    return isset($_SESSION['user']) && $_SESSION['user']['role'] === _ROLE_EMPLOYE_;
  }

  // Récupération de l'ID de l'utilisateur connecté
  public static function getCurrentUserId(): int|bool
  {
    return (isset($_SESSION['user']) && isset($_SESSION['user']['id'])) ? $_SESSION['user']['id']: false;
  }

  // Récupération du nom et prénom de l'utilisateur connecté
  public static function getCurrentUserFullName(): string|bool
  {
    if (isset($_SESSION['user'])) {
      return $_SESSION['user']['first_name'] . ' ' . $_SESSION['user']['last_name'];
    } else {
      return false;
    }
  }

  // Ne laisser entrer que les utilisateurs connectés
  public static function userOnly(): void
  {
    if (!self::isUser()) {
      header('Location: index.php');
      exit;
    }
  }

  // Ne laisser entrer que les administrateurs
  public static function adminOnly(): void
  {
    if (!self::isAdmin()) {
      header('Location: index.php');
      exit;
    }
  }

  // Ne laisser entrer que les employés
  public static function employeOnly(): void
  {
    if (!self::isEmploye()) {
      header('Location: index.php');
      exit;
    }
  }

  // Vérification du token CSRF
  public static function checkCSRF(string $token): void
  {
    if ($token !== $_SESSION['csrf_token']) {
      die('Invalid CSRF token');
    }
  }

}
