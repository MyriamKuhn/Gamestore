<?php

namespace App\Model;

class GameUserOrder extends Model
{

  protected int $fk_game_id;
  protected int $fk_platform_id;
  protected int $fk_user_order_id;
  protected int $quantity;
  protected float $price_at_order;

  public function getFk_game_id(): int
  {
    return $this->fk_game_id;
  }

  public function setFk_game_id(int $fk_game_id): self
  {
    $this->fk_game_id = $fk_game_id;

    return $this;
  }

  public function getFk_platform_id(): int
  {
    return $this->fk_platform_id;
  }

  public function setFk_platform_id(int $fk_platform_id): self
  {
    $this->fk_platform_id = $fk_platform_id;

    return $this;
  }

  public function getFk_user_order_id(): int
  {
    return $this->fk_user_order_id;
  }

  public function setFk_user_order_id(int $fk_user_order_id): self
  {
    $this->fk_user_order_id = $fk_user_order_id;

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

  public function getPrice_at_order(): float
  {
    return $this->price_at_order;
  }

  public function setPrice_at_order(float $price_at_order): self
  {
    $this->price_at_order = $price_at_order;

    return $this;
  }

}