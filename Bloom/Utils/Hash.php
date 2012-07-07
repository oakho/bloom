<?php

namespace Bloom\Utils;

class Hash
{
  /**
   * Crypt a password with given salt to md5
   *
   * @param  string $password The password
   * @param  string $salt     The salt
   * @return string           Crypted password
   */
  public static function password($password, $salt)
  {
    return crypt($password, "$1$". $salt ."$");
  }
}