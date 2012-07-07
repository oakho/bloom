<?php

namespace Bloom\Routing;

/**
 * @todo - Rework this trait to be more consistent with the way
 *     Router works
 */
trait Resources
{
  protected static $_defaultResources = [
    ["action" => "",       "params" => []],
    ["action" => "index",  "params" => []],
    ["action" => "view",   "params" => ["id"]],
    ["action" => "create", "params" => []],
    ["action" => "read",   "params" => ["id"]],
    ["action" => "update", "params" => ["id"]],
    ["action" => "delete", "params" => ["id"]]
  ];

  public static function addResource($action, Array $params = [])
  {
    array_merge(static::$_defaultResources, ["action" => $action, "params" => $params]);
  }

  public static function mapResources($controller)
  {
    foreach(static::$_defaultResources as $resource)
    {
      $action = $resource["action"];

      // If there're at least one parameter, prefix it with "/:"
      $params  = (count($resource["params"]) >= 1) ? "/:" : "";
      $params .= implode("/:", $resource["params"]);

      // Build the route
      $route  = "/". $controller;
      $route .= (empty($action)) ? "" : "/". $action;
      $route .= $params;

      // If the action is empty we need to set it to index anyway
      $action = (empty($action)) ? "index" : $action;

      static::addRoute(
        $route,
        ["controller" => $controller, "action" => $action]
      );
    }
  }
}