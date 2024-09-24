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
              'listPage.js',
            ];
            return $scripts;
            break;
          case 'show':
            $scripts = [
              'showPage.js',
            ];
            return $scripts;
            break;
          case 'promo':
            $scripts = [
              'promoPage.js',
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
      case 'auth':
        switch (isset($_GET['action']) ? $_GET['action'] : '') {
          case 'login':
            $scripts = [
              'loginPage.js',
            ];
            return $scripts;
            break;
          case 'password':
            $scripts = [
              'passwordPage.js',
            ];
            return $scripts;
            break;
          default:
            $scripts = [
              'utils.js',
            ];
            return $scripts;
            break;
        }
      case 'user':
        switch (isset($_GET['action']) ? $_GET['action'] : '') {
          case 'register':
            $scripts = [
              'registerPage.js',
            ];
            return $scripts;
            break;
          default:
            $scripts = [
              'utils.js',
            ];
            return $scripts;
            break;
        }
        return $scripts;
        break;
      default:
        $scripts = [
          'utils.js',
        ];
        return $scripts;
    }
  }

}