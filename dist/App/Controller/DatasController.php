<?php

namespace App\Controller;

use App\Repository\GamePlatformRepository;

class DatasController extends RoutingController
{

  protected function __construct()
  {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      // Lire les données JSON envoyées
      $data = json_decode(file_get_contents('php://input'), true);
  
      // Vérifier l'action demandée
      if (isset($data['action'])) {
          // Appeler une fonction spécifique en fonction de l'action
          if ($data['action'] === 'getListDatas') {
              // Appeler la fonction getData()
              $this->getListDatas();
          } elseif ($data['action'] === 'getPromoDatas') {
              // Appeler la fonction getPromoDatas()
              $this->getPromoDatas();
          } elseif ($data['action'] === 'getGameDatas') {
              // Appeler la fonction getGameDatas()
              $this->getGameDatas($data['gameId']);
          } else {
              // Si l'action n'est pas reconnue
              $this->sendResponse(false, "Action inconnue");
          }
      } else {
        $this->sendResponse(false, "Aucune action spécifiée");
      }
    }
  }

  protected function getPromoDatas()
  {
    $gpRepository = new GamePlatformRepository();
    $reducedGames = $gpRepository->getAllReducedGames();
    
    $this->sendResponse(true, $reducedGames);
  }

  protected function getListDatas()
  {
    $gpRepository = new GamePlatformRepository();
    $gamesNantes = $gpRepository->getAllGamesByStore(1);
    $gamesLille = $gpRepository->getAllGamesByStore(2);
    $gamesBordeaux = $gpRepository->getAllGamesByStore(3);
    $gamesParis = $gpRepository->getAllGamesByStore(4);
    $gamesToulouse = $gpRepository->getAllGamesByStore(5);

    $this->sendResponse(true, [
      'datasNantes' => $gamesNantes,
      'datasLille' => $gamesLille,
      'datasBordeaux' => $gamesBordeaux,
      'datasParis' => $gamesParis,
      'datasToulouse' => $gamesToulouse
    ]);
  }

  protected function getGameDatas($gameId)
  {
    $gpRepository = new GamePlatformRepository();
    $game = $gpRepository->getGameById($gameId);

    $this->sendResponse(true, $game);
  }

  // Fonction pour envoyer une réponse JSON
  protected function sendResponse($success, $datas) {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => $success,
        'datas' => $datas
    ]);
  }
  
}