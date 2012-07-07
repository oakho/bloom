<?php

namespace Application\Models;

use Bloom\Database\Model;
use Bloom\Database\Query;

class Image extends Model
{
  protected static $_name = "Image";
  protected static $_table = "images";

  public function save()
  {
    if(isset($this->id)) {
      return $this->update();
    } else {
      return $this->create();
    }
  }

  public function create()
  {
    $query = new Query(static::$_table);
    $image = $query->insert(["article_id", "name" ,"path", "mime_type", "created_at", "updated_at"], 
                            [$this->article_id, $this->name, $this->path, $this->mime_type, $this->created_at, $this->updated_at])
                   ->exec();

    return $this;
  }

  public function update()
  {

  }

  public static function findByArticle($articleId)
  {
    $query = new Query(static::$_table);

    $images = $query->select(["id", "article_id","name", "path", "mime_type", "created_at", "updated_at"])
                    ->where("article_id", "=", $articleId)
                    ->exec();

    static::$_stack["findByArticle"] = $images->fetchAll(\PDO::FETCH_CLASS, "\\Application\\Models\\Image");

    return static::$_stack["findByArticle"];
  }
}