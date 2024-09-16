<?php

namespace App\Repository;

class GenreRepository extends MainRepository
{
  public function getAllGenres(): array
  {
    $query = "SELECT * FROM genre";
    $stmt = $this->pdo->query($query);
    return $stmt->fetchAll();
  }
}