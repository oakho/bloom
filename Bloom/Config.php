<?php

namespace Bloom;

use Bloom\Utils\File;
use Bloom\Utils\Folder;

class Config
{
  protected static $_configs = [];

  public static function load(array $paths)
  {
    foreach ($paths as $path) {
      $callback = function($entry) use ($path) {
        $name = File::getName($entry);
        $content = File::load($path . DS . $entry);

        if(is_array($content)) {
          static::$_configs[$name] = $content;
        }
      };

      Folder::iterateFiles($path, $callback);
    }
  }

  public static function getAll()
  {
    return static::$_configs;
  }

  public static function get($file)
  {
    if (is_array(static::$_configs[$file])) {
      return static::$_configs[$file];
    } else {
      throw new \Exception("Can't find {$file}'s configurations");
    }
  }
}