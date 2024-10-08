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
      case 'games':
        switch (isset($_GET['action']) ? $_GET['action'] : '') {
          case 'list':
            $scripts = [
              'games/list.js',
            ];
            return $scripts;
            break;
          case 'show':
            $scripts = [
              'games/show.js',
            ];
            return $scripts;
            break;
          case 'promo':
            $scripts = [
              'games/promo.js',
            ];
            return $scripts;
            break;
        }
        break;

      case 'auth':
        switch (isset($_GET['action']) ? $_GET['action'] : '') {
          case 'login':
            $scripts = [
              'auth/login.js',
            ];
            return $scripts;
            break;
          case 'password':
            $scripts = [
              'auth/password.js',
            ];
            return $scripts;
            break;
          case 'reset':
            $scripts = [
              'auth/reset.js',
            ];
            return $scripts;
            break;
        }
        break;

      case 'user':
        switch (isset($_GET['action']) ? $_GET['action'] : '') {
          case 'register':
            $scripts = [
              'user/register.js',
            ];
            return $scripts;
            break;
        }
        break;

      case 'dashboard':
        switch (isset($_GET['action']) ? $_GET['action'] : '') {
          case 'modify':
            $scripts = [
              'dashboard/modifyUser.js',
            ];
            return $scripts;
            break;
          case 'order':
            $scripts = [
              'order.js',
            ];
            return $scripts;
            break;
        }
        break;

      case 'employe':
        switch (isset($_GET['action']) ? $_GET['action'] : '') {
          case 'password':
            $scripts = [
              'passwordChange.js',
            ];
            return $scripts;
            break;
          case 'orders':
            $scripts = [
              'employe/orders.js',
            ];
            return $scripts;
            break;
          case 'buying':
            $scripts = [
              'employe/buying.js',
            ];
            return $scripts;
            break;
          case 'order':
            $scripts = [
              'order.js',
            ];
            return $scripts;
            break;
          case 'sales':
            $scripts = [
              'employe/sales.js',
            ];
            return $scripts;
            break;
          case 'details':
            $scripts = [
              'employe/details.js',
            ];
            return $scripts;
            break;
        }
        break;

        case 'admin':
          switch (isset($_GET['action']) ? $_GET['action'] : '') {
            case 'password':
              $scripts = [
                'passwordChange.js',
              ];
              return $scripts;
              break;
            case 'order':
              $scripts = [
                'order.js',
              ];
              return $scripts;
              break;
            case 'orders':
              $scripts = [
                'admin/orders.js',
              ];
              return $scripts;
              break;
            case 'buying':
              $scripts = [
                'admin/buying.js',
              ];
              return $scripts;
              break;
            case 'employes':
              $scripts = [
                'admin/employes.js',
              ];
              return $scripts;
              break;
            case 'employe':
              $scripts = [
                'admin/employe.js',
              ];
              return $scripts;
              break;
            case 'users':
              $scripts = [
                'admin/users.js',
              ];
              return $scripts;
              break;
            case 'sales':
              $scripts = [
                'admin/sales.js',
              ];
              return $scripts;
              break;
            case 'details':
              $scripts = [
                'admin/details.js',
              ];
              return $scripts;
              break;
            case 'products':
              $scripts = [
                'admin/products.js',
              ];
              return $scripts;
              break;
            case 'product':
              $scripts = [
                'admin/product.js',
              ];
              return $scripts;
              break;
          }
          break;
    }
  }

}