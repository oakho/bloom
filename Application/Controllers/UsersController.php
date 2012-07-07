<?php

namespace Application\Controllers;

use Bloom\Auth\Auth;
use Bloom\Auth\SimpleAuthComponent;

class UsersController extends ActionController
{
  use SimpleAuthComponent;

  protected function isAuthorized()
  {
    if($this->action !== "signinAction" && !\Bloom\Auth\Auth::isLogged()) {
      $this->flashMessages->setMessage("Gosh, I can't authorize you to view this page", 1);
      $this->redirect("/");

      return false;
    }

    return true;
  }

  public function signinAction()
  {
    $this->_autoRender = false;

    if($this->request->isPost()) {
      $email = $_POST['email'];
      $password = addslashes($_POST['password']);

      if(preg_match("#^([a-zA-Z0-9\-\._]*)@([a-z0-9\-]*)\.[a-z]{2,3}$#", $email)
        && Auth::login($email, $password)) {
        $this->flashMessages->setMessage("You successfully signed in, clapclap bro' !", 2);
      } else {
        $this->flashMessages->setMessage("Sorry bro', it seems that the system don't know you :( !", 1);
      }
    }

    $this->redirect("/");
  }

  public function signoutAction()
  {
    $this->_autoRender = false;

    if(Auth::logout()) {
      $this->flashMessages->setMessage("You've successfully signed out, see you around !", 2);
    }

    $this->redirect("/");
  }
}