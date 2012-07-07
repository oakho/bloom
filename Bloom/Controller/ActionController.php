<?php

namespace Bloom\Controller;

use Bloom\Http\Request;
use Bloom\Http\Response;

use Bloom\Views\Layout;
use Bloom\Views\View;
use Bloom\Views\Helpers\FlashMessages;

abstract class ActionController
{   
  /**
   * Hold the request which called current controller
   * 
   * @var Request
   */
  public $request = null;

  /**
   * Hold the application response
   *
   * @var Response
   */
  public $response = null;

  /**
   * Hold called action name
   * @var string
   */
  public $action = null;

  /**
   * Hold the request's params
   * 
   * @var array
   */
  public $params = [];
  
  /**
   * Hold a View object
   * 
   * @var View
   */
  public $view = null;

  /**
   * Hold a Layout object
   * 
   * @var Layout
   */
  public $layout = null;

  /**
   * Hold the FlashMessages helper
   * 
   * @var FlashMessages
   */
  public $flashMessages = null;

  /**
   * Name of the controller
   * 
   * @var string
   */
  protected $_name = "";

  /**
   * Tell if the controller auto render it's views after action is called
   *
   * @var boolean 
   */
  protected $_autoRender = true;

  /**
   * Initialize the controller
   */
  public function __construct(Request &$request, Response &$response)
  {
    $this->request       = $request;
    $this->response      = $response;
    $this->params        = $request->getParams();
    $this->action        = $this->request->getRoutings()['action'] ."Action";
    $this->view          = new View();
    $this->layout        = new Layout();
    $this->flashMessages = new FlashMessages();

    $this->_name  = get_class($this);
  }

  /**
   * Method called before each action method, useful when you want
   * to do some stuff before an action is called
   * 
   * @return mixed
   */
  public function beforeAction()
  {

  }

  /**
   * Method called after each action method, useful when you want
   * to do some stuff after an action is called
   * 
   * @return mixed
   */
  public function afterAction()
  {
    if($this->_autoRender) {
      $this->layout->content       = $this->view->render();
      $this->layout->stack         = \Bloom\Database\Query::getQueriesStack();
      $this->layout->flashMessages = $this->flashMessages->render();

      $this->response->appendBody($this->layout->render());
    }
  }

  public function callAction()
  {
    $this->beforeAction();
    $this->{$this->action}();
    $this->afterAction();
  }

  /**
   * Let you redirect current request to another path
   * 
   * @param  string $path Path to redirect to
   * @return void
   */
  public function redirect($path)
  {
    $this->response->setHeader("Location", $path);
  }

  /**
   * Throw an exception if called action doesn't exist
   * 
   * @param  string $name The method name
   * @param  array $args  The method arguments
   * @throw  \Exception
   * @return  void
   */
  public function __call($name, $args)
  {
    if(preg_match("#^(?P<action>\w+)Action$#", $name, $matches)) {
      throw new \Exception("Action ". $matches['action'] ." in ". $this->name ." doesn't exist.");
    }
  }
}