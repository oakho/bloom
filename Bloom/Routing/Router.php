<?php

namespace Bloom\Routing;

use BLoom\Http\Request;
use Bloom\Routing\Resources;
use Bloom\Routing\Routes\Route;

class Router
{
  use Resources;

  protected static $_routes = [];
  protected static $_request = null;

  public static function addRoute($route, $segments = [], $opts = [])
  {
    static::$_routes[] = new Route($route, $segments);
  }

  public static function parseRequest(Request $request)
  {
    foreach(static::$_routes as $route) {
      if($route->match($request)) {
        static::$_request = $request;
        break;
      }
    }

    // Set a default controller and action if no route match current request
    if(static::$_request === null) {
      $request->setRoutings(["controller" => "errors", "action" => "error404"]);
      static::$_request = $request;
    }

    return $request;
  }
}