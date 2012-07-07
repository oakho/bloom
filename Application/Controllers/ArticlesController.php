<?php

namespace Application\Controllers;

use Bloom\Auth\Auth;
use Bloom\Auth\SimpleAuthComponent;

use Application\Models\Article;
use Application\Models\Category;

class ArticlesController extends ActionController
{
  use SimpleAuthComponent;

  protected function isAuthorized()
  {
    if($this->action !== "viewAction" && !Auth::isLogged()) {
      $this->flashMessages->setMessage("Gosh, I can't authorize you to view this page", 1);
      $this->redirect("/");

      return false;
    }

    return true;
  }

  public function viewAction()
  { 
    $articleId = $this->params['id'];
    $article = Article::findOne($articleId);

    $this->view->setPath("articles/view");
    $this->view->article = $article;

    $this->layout->title = $article->title;
  }

  public function adminIndexAction()
  {
    $this->layout->title = "Admin \ Articles";

    $this->view->setPath("articles/admin_index");
    $this->view->articles = Article::findAll();
  }

  public function adminAddAction()
  { 
    $this->layout->title = "Admin \ Articles \ Add an article";

    $this->view->setPath("articles/admin_addedit");
    $this->view->isEdit = false;
    
    if($this->request->isPost()) {
      $this->_autoRender = false;

      $article = new Article([
        "status" => $_POST['status'],
        "title" => trim(addslashes($_POST['title'])),
        "text" => trim(addslashes($_POST['text'])),
        "categories" => array_key_exists("categories", $_POST) ? $_POST["categories"] : [],
        "images" => array_key_exists("image", $_FILES) ? [$_FILES['image']] : []
      ]);

      if($article->save()) {
        $this->flashMessages->setMessage("Article added, yala !", 2);
      } else {
        $this->flashMessages->setMessage("Whoops, can't add that article :(", 1);
      }

      $this->redirect("/admin/articles");
    } else {
      $this->view->categories = Category::findAll();
    }
  }

  public function adminEditAction()
  { 
    $this->layout->title = "Admin \ Articles \ Edit an article";

    $articleId = $this->params['id'];

    $this->view->setPath("articles/admin_addedit");
    $this->view->isEdit = true;
    
    if($this->request->isPost()) {
      $this->_autoRender = false;

      $article = new Article([
        "id" => $articleId, 
        "status" => $_POST['status'],
        "title" => trim(addslashes($_POST['title'])),
        "text" => trim(addslashes($_POST['text'])),
        "categories" => array_key_exists("categories", $_POST) ? $_POST["categories"] : [],
        "images" => array_key_exists("image", $_FILES) ? [$_FILES['image']] : []
      ]);

      if($article->save()) {
        $this->flashMessages->setMessage("Article updated, yala !", 2);
      } else {
        $this->flashMessages->setMessage("Whoops, can't update that article :(", 1);
      }

      $this->redirect("/admin/articles");
    } else {
      $this->view->article = Article::findOne($articleId);
      $this->view->categories = Category::findAll();
    }

  }

  public function adminDeleteAction()
  {
    $articleId = $this->params['id'];

    $this->_autoRender = false;

    $this->view->setPath("articles/admin_addedit");

    $article = Article::findOne($articleId);

    if($article->delete()) {
      $this->flashMessages->setMessage("Wonderful, the article was delete", 2);
    } else {
      $this->flashMessages->setMessage("Erm, don't why but the article wasn't delete sorry", 1);
    }

    $this->redirect("/admin/articles");
  }
}