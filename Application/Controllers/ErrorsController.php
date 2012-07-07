<?php

namespace Application\Controllers;

class ErrorsController extends ActionController
{
    public function error404Action()
    {
        $this->layout->title = "Error 404";
        
        $this->view->setPath('errors/404');
    }
}