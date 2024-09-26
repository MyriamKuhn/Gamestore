<?php

namespace App\Model;

class GamePlatform extends Model
{

  protected int $fk_game_id;
  protected int $fk_store_id;
  protected int $fk_plattform_id;
  protected float $price;
  protected bool $is_new;
  protected bool $is_reduced;
  protected float $discount_rate;
    
  public function getFk_game_id(): int
  {
    return $this->fk_game_id;
  }

  public function setFk_game_id(int $fk_game_id): self
  {
    $this->fk_game_id = $fk_game_id;

    return $this;
  }

  public function getFk_store_id(): int
  {
    return $this->fk_store_id;
  }

  public function setFk_store_id(int $fk_store_id): self
  {
    $this->fk_store_id = $fk_store_id;

    return $this;
  }

  public function getFk_plattform_id(): int
  {
    return $this->fk_plattform_id;
  }

  public function setFk_plattform_id(int $fk_plattform_id): self
  {
    $this->fk_plattform_id = $fk_plattform_id;

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

  public function getIs_new(): bool
  {
    return $this->is_new;
  }

  public function setIs_new(bool $is_new): self
  {
    $this->is_new = $is_new;

    return $this;
  }

  public function getIs_reduced(): bool
  {
    return $this->is_reduced;
  }

  public function setIs_reduced(bool $is_reduced): self
  {
    $this->is_reduced = $is_reduced;

    return $this;
  }

  public function getDiscount_rate(): float
  {
    return $this->discount_rate;
  }

  public function setDiscount_rate(float $discount_rate): self
  {
    $this->discount_rate = $discount_rate;

    return $this;
  }

}