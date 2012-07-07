<?php

namespace Bloom;

require "Utils/Klass.php";

class Autoloader
{
  /**
   * Hold the paths
   *
   * @var array
   */
  protected static $_paths = [];

  /**
   * Let's you register an array of paths to the autoload system.
   * This will convert any called class namespace to it's own path
   *
   * eg. \Bloom\Autoloader would be converted to /Bloom/Autoloader
   *
   * @param  array  $paths Paths to register
   * @return void
   */
  public static function register(array $paths)
  {
    array_merge(static::$_paths, $paths);

    foreach ($paths as $path) {
      spl_autoload_register(function($class) use ($path) {
        set_include_path($path);
        spl_autoload(\Bloom\Utils\Klass::getClassPath($class));
      });
    }
  }
}