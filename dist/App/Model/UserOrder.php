<?php

namespace App\Model;

class UserOrder extends Model
{

  protected int $id;
  protected string $order_date_time;
  protected string $status;
  protected int $fk_app_user_id;
  protected int $fk_store_id;

  public function getId(): int
  {
    return $this->id;
  }

  public function setId(int $id): self
  {
    $this->id = $id;

    return $this;
  }

  public function getOrder_date_time(): string
  {
    return $this->order_date_time;
  }

  public function setOrder_date_time(string $order_date_time): self
  {
    $this->order_date_time = $order_date_time;

    return $this;
  }

  public function getStatus(): string
  {
    return $this->status;
  }

  public function setStatus(string $status): self
  {
    $this->status = $status;

    return $this;
  }

  public function getFk_app_user_id(): int
  {
    return $this->fk_app_user_id;
  }

  public function setFk_app_user_id(int $fk_app_user_id): self
  {
    $this->fk_app_user_id = $fk_app_user_id;

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

}