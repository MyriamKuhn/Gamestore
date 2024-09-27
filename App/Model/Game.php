<?php

namespace App\Model;

class Game extends Model
{

  protected int $id;
  protected string $name;
  protected string $description;
  protected int $fk_pegi_id;

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

  public function getDescription(): string
  {
    return $this->description;
  }

  public function setDescription(string $description): self
  {
    $this->description = $description;

    return $this;
  }

  public function getFk_pegi_id(): int
  {
    return $this->fk_pegi_id;
  }

  public function setFk_pegi_id(int $fk_pegi_id): self
  {
    $this->fk_pegi_id = $fk_pegi_id;

    return $this;
  }
  
}