<?php

namespace App\Tools;

class NavigationTools
{

  public static function addActiveClass($controller, $action)
  {
    if (isset($_GET['controller']) && $_GET['controller'] === $controller && isset($_GET['action']) && $_GET['action'] === $action) {
      return 'active';
    } else if (!isset($_GET['controller']) && $controller === 'page' && $action === 'home') {
      return 'active';
    }
    return '';
  }

  public static function addMetas()
  {
  
    switch (isset($_GET['controller']) ? $_GET['controller'] : '') {
      case 'page':
        $metaDatas = [
          'title' => "Gamestore : Vos jeux préférés, à portée de main",
          'description' => "Gamestore : votre expert en jeux vidéo pour toutes les plateformes existantes. Explorez notre vaste catalogue et retirez vos achats directement en magasin. Profitez des dernières sorties et de nos offres exclusives dès aujourd'hui !",
          'keywords' => "jeux vidéo, achat jeux vidéo, jeux pour toutes les plateformes, retrait en magasin, nouveautés jeux vidéo, promotions jeux vidéo, Gamestore",
          'image' => _ASSETS_IMAGES_FOLDER_."logo_small.svg"
        ];
        return $metaDatas;
        break;
      case 'auth':
        $metaDatas = [
          'title' => "Gamestore : Connexion à votre compte",
          'description' => "Connectez-vous à votre compte Gamestore pour accéder à vos informations personnelles, vos commandes et vos préférences. Retrouvez également vos jeux favoris et vos listes de souhaits.",
          'keywords' => "connexion, compte, informations personnelles, commandes, préférences, jeux favoris, listes de souhaits, Gamestore",
          'image' => _ASSETS_IMAGES_FOLDER_."logo_small.svg"
        ];
        return $metaDatas;
        break;
      case 'user':
        $metaDatas = [
          'title' => "Gamestore : Votre compte",
          'description' => "Consultez et modifiez vos informations personnelles, vos commandes et vos préférences sur votre compte Gamestore. Retrouvez également vos jeux favoris et vos listes de souhaits.",
          'keywords' => "informations personnelles, commandes, préférences, jeux favoris, listes de souhaits, Gamestore",
          'image' => _ASSETS_IMAGES_FOLDER_."logo_small.svg"
        ];
        return $metaDatas;
        break;
      case 'game':
        $metaDatas = [
          'title' => "Gamestore : Détails du jeu",
          'description' => "Découvrez les détails du jeu sélectionné : description, caractéristiques, avis des joueurs, captures d'écran, vidéos et bien plus encore. Ajoutez-le à votre panier et retirez-le en magasin dès aujourd'hui !",
          'keywords' => "détails, jeu, description, caractéristiques, avis, joueurs, captures d'écran, vidéos, Gamestore",
          'image' => _ASSETS_IMAGES_FOLDER_."logo_small.svg"
        ];
          return $metaDatas;
          break;
        default:
          $metaDatas = [
            'title' => "Gamestore : Vos jeux préférés, à portée de main",
            'description' => "Gamestore : votre expert en jeux vidéo pour toutes les plateformes existantes. Explorez notre vaste catalogue et retirez vos achats directement en magasin. Profitez des dernières sorties et de nos offres exclusives dès aujourd'hui !",
            'keywords' => "jeux vidéo, achat jeux vidéo, jeux pour toutes les plateformes, retrait en magasin, nouveautés jeux vidéo, promotions jeux vidéo, Gamestore",
            'image' => _ASSETS_IMAGES_FOLDER_."logo_small.svg"
          ];
          return $metaDatas;
    } 
  }

}