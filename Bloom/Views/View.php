<?php

namespace Bloom\Views;

class View
{
  protected $_path = "";

  public function __contruct() {  }

  public function setPath($path)
  {
    $this->_path = $path;

    return $this;
  }

  public function getPath()
  {
    return $this->_path;
  }

  public function render()
  {
    ob_start();
    require VIEWS_PATH . DS . $this->_path .".phtml";

    return ob_get_clean();
  }
}