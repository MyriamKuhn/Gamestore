<?php

namespace App\Model;

class Image extends Model
{
  protected int $id;
  protected string $name;
  protected int $fk_game_id;

  public function getId(): int
  {
    return $this->id;
  }

  public function setId(int $id): self
  {
    $this->id = $id;

    return $this;
  }

  public function getName(): string
  {
    return $this->name;
  }

  public function setName(string $name): self
  {
    $this->name = $name;

    return $this;
  }

  public function getFk_game_id(): int
  {
    return $this->fk_game_id;
  }

  public function setFk_game_id(int $fk_game_id): self
  {
    $this->fk_game_id = $fk_game_id;

    return $this;
  }
  
}