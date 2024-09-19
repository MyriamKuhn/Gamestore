<?php

namespace App\Model;

use DateTime;

class Sale extends Model
{

  protected int $id;
  protected string $name;
  protected float $price;
  protected DateTime $date;

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

  public function getPrice(): float
  {
    return $this->price;
  }

  public function setPrice(float $price): self
  {
    $this->price = $price;

    return $this;
  }

  public function getDate(): DateTime
  {
    return $this->date;
  }

  public function setDate(DateTime $date): self
  {
    $this->date = $date;

    return $this;
  }
  
}