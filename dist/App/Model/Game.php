<?php

namespace App\Model;

class Game extends Model
{

  protected int $id;
  protected string $name;
  protected string $description;
  protected int $fk_pegi_id;


  /**
   * Get the value of id
   */ 
  public function getId()
  {
    return $this->id;
  }

  /**
   * Set the value of id
   *
   * @return  self
   */ 
  public function setId($id)
  {
    $this->id = $id;

    return $this;
  }

  /**
   * Get the value of name
   */ 
  public function getName()
  {
    return $this->name;
  }

  /**
   * Set the value of name
   *
   * @return  self
   */ 
  public function setName($name)
  {
    $this->name = $name;

    return $this;
  }

  /**
   * Get the value of description
   */ 
  public function getDescription()
  {
    return $this->description;
  }

  /**
   * Set the value of description
   *
   * @return  self
   */ 
  public function setDescription($description)
  {
    $this->description = $description;

    return $this;
  }

  /**
   * Get the value of fk_pegi_id
   */ 
  public function getFk_pegi_id()
  {
    return $this->fk_pegi_id;
  }

  /**
   * Set the value of fk_pegi_id
   *
   * @return  self
   */ 
  public function setFk_pegi_id($fk_pegi_id)
  {
    $this->fk_pegi_id = $fk_pegi_id;

    return $this;
  }
}