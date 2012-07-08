<?php

namespace Bloom\Views;

class Layout extends View
{
  /**
   * Default title of the layout
   * @var string
   */
  public $title = "";

  /**
   * Path to the default layout
   * @var string
   */
  protected $_path = "layouts/default";
}