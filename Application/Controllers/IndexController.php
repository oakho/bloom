<?php

namespace Application\Controllers;

class IndexController extends ActionController
{
    public function indexAction()
    {
        $this->layout->title = "Index";

        $this->view->setPath('index/index');
		$this->view->articles = \Application\Models\Article::findAll();
    }
}