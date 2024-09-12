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

  public function hydrate(array $data)
  {
    if (count($data) > 0) {
      // On parcourt le tableau de données
      foreach ($data as $key => $value) {
        // Pour chaque donnée, on appel le setter
        $methodName = 'set' . StringTools::toPascalCase($key);
        if (method_exists($this, $methodName)) {
          if ($key == 'created_at') {
            $value = new \DateTime($value);
          } else if ($key == 'release_date') {
            $value = new \DateTime($value);
          } else if ($key == 'duration') {
            $value = new \DateTime($value);
          }
          $this->{$methodName}($value);
        }
      }
    }
  }
}
