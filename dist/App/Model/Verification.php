<?php

namespace App\Model;

class Verification extends Model
{

  private int $id;
  private int $verification_code;
  private string $created_at;
  private int $fk_app_user_id;

  public function getId(): int
  {
    return $this->id;
  }

  public function setId(int $id): self
  {
    $this->id = $id;

    return $this;
  }

  public function getVerification_code(): int
  {
    return $this->verification_code;
  }

  public function setVerification_code(int $verification_code): self
  {
    $this->verification_code = $verification_code;

    return $this;
  }

  public function getCreated_at(): string
  {
    return $this->created_at;
  }

  public function setCreated_at(string $created_at): self
  {
    $this->created_at = $created_at;

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
  
}