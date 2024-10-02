<?php

namespace App\Model;

class GameGenre extends Model
{

  protected int $fk_game_id;
  protected int $fk_genre_id;

  public function getFk_game_id(): int
  {
    return $this->fk_game_id;
  }

  public function setFk_game_id(int $fk_game_id): self
  {
    $this->fk_game_id = $fk_game_id;

    return $this;
  }

  public function getFk_genre_id(): int
  {
    return $this->fk_genre_id;
  }

  public function setFk_genre_id(int $fk_genre_id): self
  {
    $this->fk_genre_id = $fk_genre_id;

    return $this;
  }

}