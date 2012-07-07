<?php

namespace Bloom\Routing\Routes;

use Bloom\Http\Request;

/**
 * Route Class
 *
 * @todo Implement the possiblity to specify a custom pattern for each parameters
 *     so that we can set a parameter to only match integers for example
 * @todo Implement the possibility add custom options to the route
 */
class Route
{
  /**
   * Hold route's path
   *
   * @var string
   */
  protected $_route = null;

  /**
   * Hold route's pattern
   *
   * @var string
   */
  protected $_pattern = null;

  /**
   * List of acceptable routings' parameters this let us split
   * routings parameters and pure parameters (eg. an id)
   *
   * @var array
   */
  protected static $_defaultRoutings = [
    "module",
    "controller",
    "action"
  ];

  /**
   * List of routings which have been set while adding the route.
   *
   * @var array
   */
  protected $_routings = [];

  /**
   * Initialize a route
   *
   * @param string $route    The route's path
   * @param array $routings  Any default routings' parameters
   * @param array $opts      Some options for the route (not implemented yet)
   */
  public function __construct($route, $routings, $opts = [])
  {
    $this->_route = (string) $route;
    $this->_routings = array_merge($this->_routings, $routings);
  }

  /**
   * Check if passed request match this route and set it's routings
   * and params
   *
   * @param  Request &$request The request
   * @return mixed             Default to false, return updated request if it match the route
   */
  public function match(Request &$request)
  {
    if(preg_match((string) $this, $request->getURI(), $matches)) {
      // Add any already set routings to the request
      if(count($this->_routings)) {
        $request->setRoutings($this->_routings);
      }

      foreach($matches as $key => $value) {
        if(in_array($key, static::$_defaultRoutings, true)) {
          $request->setRoutings([$key => $value]);
        } elseif(is_string($key)) {
          $request->setParams([$key => $value]);
        }
      }

      return true;
    }

    return false;
  }

  /**
   * Check if the route is greedy or not
   *
   * @return boolean
   */
  protected function _isGreedy()
  {
    return $this->_route[strlen($this->_route) - 1] !== "*";
  }

  /**
   * Compile the route to it's pattern
   *
   * @return string The route's pattern
   */
  public function __toString()
  {
    if(is_null($this->_pattern)) {
      $pattern = preg_replace_callback(
        "#(?:\:(\w+))#",
        function($match) {
          return "(?<". $match[1] .">\w+)";
        },
        $this->_route
      );

      if($this->_isGreedy()) $pattern .= "$";

      $this->_pattern = "#^". $pattern ."#";
    }

    return $this->_pattern;
  }
}