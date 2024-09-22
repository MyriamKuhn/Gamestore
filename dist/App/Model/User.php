<?php

namespace App\Model;

class User extends Model
{

  protected int $id;
  protected string $first_name;
  protected string $last_name;
  protected string $address;
  protected int $postcode;
  protected string $city;
  protected string $email;
  protected string $password;
  protected string $role;
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

  public function getFirst_name(): string
  {
    return $this->first_name;
  }

  public function setFirst_name(string $first_name): self
  {
    $this->first_name = $first_name;

    return $this;
  }

  public function getLast_name(): string
  {
    return $this->last_name;
  }

  public function setLast_name(string $last_name): self
  {
    $this->last_name = $last_name;

    return $this;
  }

  public function getAddress(): string
  {
    return $this->address;
  }

  public function setAddress(string $address): self
  {
    $this->address = $address;

    return $this;
  }

  public function getPostcode(): int
  {
    return $this->postcode;
  }

  public function setPostcode(int $postcode): self
  {
    $this->postcode = $postcode;

    return $this;
  }

  public function getCity(): string
  {
    return $this->city;
  }

  public function setCity(string $city): self
  {
    $this->city = $city;

    return $this;
  }

  public function getEmail(): string
  {
    return $this->email;
  }

  public function setEmail(string $email): self
  {
    $this->email = $email;

    return $this;
  }

  public function getPassword(): string
  {
    return $this->password;
  }

  public function setPassword(string $password): self
  {
    $this->password = $password;

    return $this;
  }

  public function getRole(): string
  {
    return $this->role;
  }

  public function setRole(string $role): self
  {
    $this->role = $role;

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