<?php

namespace App\Model;

use DateTime;

class Sale extends Model
{

  protected int $id;
  protected string $name;
  protected string $genre;
  protected string $platform;
  protected string $store;
  protected float $price;
  protected int $quantity;
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

  public function getGenre(): string
  {
    return $this->genre;
  }

  public function setGenre(string $genre): self
  {
    $this->genre = $genre;

    return $this;
  }

  public function getPlatform(): string
  {
    return $this->platform;
  }

  public function setPlatform(string $platform): self
  {
    $this->platform = $platform;

    return $this;
  }

  public function getStore(): string
  {
    return $this->store;
  }

  public function setStore(string $store): self
  {
    $this->store = $store;

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

  public function getQuantity(): int
  {
    return $this->quantity;
  }

  public function setQuantity(int $quantity): self
  {
    $this->quantity = $quantity;

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