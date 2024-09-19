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

  public static function addScripts()
  {
    switch (isset($_GET['controller']) ? $_GET['controller'] : '') {
      case 'page':
        $scripts = [
          'utils.js',
        ];
        return $scripts;
        break;
      case 'games':
        switch (isset($_GET['action']) ? $_GET['action'] : '') {
          case 'list':
            $scripts = [
              'utils.js',
              'variables.js',
              'listFilters.js',
              'listPage.js',
              'listCardsCreate.js',
            ];
            return $scripts;
            break;
          case 'show':
            $scripts = [
              'utils.js',
              'showPage.js',
              'carouselGamestore.js',
            ];
            return $scripts;
            break;
          case 'promo':
            $scripts = [
              'utils.js',
              'variables.js',
              'promoPage.js',
              'promoCardsCreate.js',
              'promoFilters.js',
            ];
            return $scripts;
            break;
          default:
            $scripts = [
              'utils.js',
            ];
            return $scripts;
        }
        break;
      default:
        $scripts = [
          'utils.js',
          'filters.js',
          'listPage.js',
          'promoPage.js',
          'variables.js'
        ];
        return $scripts;
    }
  }

}