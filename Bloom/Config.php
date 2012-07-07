<?php

namespace Bloom;

class Config
{
  protected static $_configs = [];

  public static function loadConfigs(array $paths)
  {
    foreach ($paths as $path) {
      Utils\Folder::iterateFilesOfFolder(
        $path,
        function($entry) use ($path) {
          $entryName = Utils\File::getFileName($entry);
          $entryContent = Utils\File::loadFile($path . DS . $entry);

          if(is_array($entryContent)) {
            static::$_configs[$entryName] = $entryContent;
          }
        }
      );
    }
  }

  public static function getAllConfigs()
  {
    return static::$_configs;
  }

  public static function getConfigs($file)
  {
    if (is_array(static::$_configs[$file])) {
      return static::$_configs[$file];
    }
    else {
      throw new \Exception("Can't find {$file}'s configurations");
    }
  }
}