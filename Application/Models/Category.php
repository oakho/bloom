<?php

namespace Application\Models;

use Bloom\Database\Model;
use Bloom\Database\Query;

class Category extends Model
{
  protected static $_name = "Category";
  protected static $_table = "categories";

  public $articles = [];

  public function hasArticles()
  {
    return (count($this->articles)) ? true : false;
  }

  public static function findAll()
  {
    $query = new Query(static::$_table);
    $categories = $query->select("*")->exec();

    static::$_stack["findAll"] = [];

    while($category = $categories->fetchObject("\\Application\\Models\\Category")) {
      $category->articles = Article::findByCategory($category->id);
      static::$_stack["findAll"][] = $category;
    }
    
    return static::$_stack["findAll"];
  }

  public static function findOne($categoryId)
  {
    $query = new Query(static::$_table);
    $category = $query->select(["name", "description", "created_at", "updated_at"])
                      ->where("id", "=", $categoryId)
                      ->exec();
    $category = $category->fetchObject("\\Application\\Models\\Category");

    $articles = Article::findByCategory($categoryId);

    $category->articles = $articles;

    static::$_stack["findOne"] = $category;
    
    return static::$_stack["findOne"];
  }

  public static function findByArticle($articleId)
  {
    $query = new Query("articles_categories");

    $categories = $query->select(["id", "name", "description", "created_at", "updated_at"])
                        ->join("categories", "category_id", "id", "LEFT")
                        ->where("article_id", "=", $articleId)
                        ->exec();

    static::$_stack["findByCategory"] = $categories->fetchAll(\PDO::FETCH_CLASS, "\\Application\\Models\\Category");

    return static::$_stack["findByCategory"];
  }
}