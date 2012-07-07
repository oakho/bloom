<?php

namespace Bloom\Utils;

class File
{
  public static function load($path)
  {
    return include($path);
  }

  public static function getName($file)
  {
    return explode(".", $file)[0];
  }

  public static function getExtention($file)
  {
    return explode(".", $file)[1];
  }
}