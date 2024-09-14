<?php

namespace App\Tools;

class FileTools
{
  
  public static function getImagesAsCategory(string $needle, array $images): array
  {
    $imagesDatas = array_filter($images, function($image) use ($needle) {
      return strpos($image, $needle) !== false; });
    
      return $imagesDatas;
  }

}