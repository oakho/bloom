<?php

namespace Application\Controllers;

use Application\Models\Category;

class CategoriesController extends ActionController
{
  public function viewAction()
  { 
    $categoryId = $this->params['id'];
    $category = Category::findOne($categoryId);

    $this->layout->title = "Category \ ". $category->name;

    $this->view->setPath("categories/view");
    $this->view->category = $category;
  }
}