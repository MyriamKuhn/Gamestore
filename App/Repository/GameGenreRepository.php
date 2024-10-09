<?php

namespace App\Repository;

class GameGenreRepository extends MainRepository
{

  // Récupération de tous les genres d'un jeu
  public function findAllGenresByGame(int $gameId): string
  {
    $query = 'SELECT
      g.name AS genre_name
      FROM game_genre AS gg
      INNER JOIN genre AS g ON gg.fk_genre_id = g.id
      WHERE gg.fk_game_id = :gameId';

    $stmt = $this->pdo->prepare($query);
    $stmt->bindValue(':gameId', $gameId, $this->pdo::PARAM_INT);
    $stmt->execute();

    $genres = $stmt->fetchAll($this->pdo::FETCH_COLUMN, 0);

    $genresList = implode(', ', $genres);

    return $genresList;
  }

  // Suppression des genres d'un jeu
  public function deleteGameGenres(int $gameId): bool
  {
    $query = 'DELETE FROM game_genre WHERE fk_game_id = :gameId';

    $stmt = $this->pdo->prepare($query);
    $stmt->bindValue(':gameId', $gameId, $this->pdo::PARAM_INT);

    return $stmt->execute();
  }

  // Ajout des genres d'un jeu
  public function addGameGenre(int $gameId, int $genreId): bool
  {
    $query = 'INSERT INTO game_genre (fk_game_id, fk_genre_id) VALUE (:gameId, :genreId)';

    $stmt = $this->pdo->prepare($query);
    
    $stmt->bindValue(':gameId', $gameId, $this->pdo::PARAM_INT);
    $stmt->bindValue(':genreId', $genreId, $this->pdo::PARAM_INT);

    return $stmt->execute();
  }

}