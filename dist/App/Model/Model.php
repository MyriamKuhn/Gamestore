<?php

namespace App\Model;

use App\Tools\StringTools;

class Model
{

  public static function createAndHydrate(array $data): static
  {
    // Ici static fait référence à la classe de l'enfant, alors que self fait référence à la classe courante
    $model = new static();
    $model->hydrate($data);
    return $model;
  }

  public function hydrate(array $datas): void
  {
    if (count($datas) > 0) {
      // On parcourt le tableau de données
      foreach ($datas as $key => $value) {
        // On récupère le nom du setter correspondant à l'attribut
        $method = 'set'.ucfirst($key);
              
        // Si le setter correspondant existe
        if (method_exists($this, $method)) {
          // On appelle le setter
          $this->$method($value);
        }
      }
    }
  }
  
}
