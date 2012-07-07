<?php

namespace Application\Models;

use Bloom\Database\Model;
use Bloom\Database\Query;
use Bloom\Database\Database;
use Bloom\Utils\Upload;

use Application\Models\Category;
use Application\Models\Image;

class Article extends Model
{
	protected static $_name = "Article";
	protected static $_table = "articles";

	public $categories = [];
	public $images = [];

	public function hasCategories()
	{
		return (count($this->categories)) ? true : false;
	}

	public function hasImages()
	{
		return (count($this->images)) ? true : false;
	}

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
        $article = $query->insert(["status" ,"title", "text", "created_at", "updated_at"], 
								  [$this->status, $this->title, $this->text, $this->created_at, $this->updated_at])
						 ->exec();

		$articleId = Database::$pdo->lastInsertId();

		$categories = new Query("articles_categories");

		foreach($this->categories as $category) {
			$categories->insert(["article_id" ,"category_id"], [$articleId, $category])->exec();
		}

		if(count($this->hasImages())) {
			$imageName = uniqid() ."_". $this->images[0]['name'];
			$imagePath = "medias/images/". $imageName;

			Upload::file(
				$this->images[0]['tmp_name'], 
				"./". $imagePath,
				function() use ($articleId, $imageName, $imagePath) {
					$image = new Image([
						"article_id" => $articleId,
						"name" => $imageName,
						"path" => $imagePath,
						"mime_type" => $this->images[0]['type']
					]);

					$image->save();
				}
			);
		}

		return $this;
	}

	public function update()
	{
		$query = new Query(static::$_table);
        $article = $query->update("status = '{$this->status}', title = '{$this->title}', text = '{$this->text}'")
						 ->where("id", "=", $this->id)
						 ->exec();

		$articleId = $this->id;

		if(count($this->hasImages())) {
			$imageName = uniqid() ."_". $this->images[0]['name'];
			$imagePath = "medias/images/". $imageName;

			Upload::file(
				$this->images[0]['tmp_name'], 
				"./". $imagePath,
				function() use ($articleId, $imageName, $imagePath) {
					$image = new Image([
						"article_id" => $articleId,
						"name" => $imageName,
						"path" => $imagePath,
						"mime_type" => $this->images[0]['type']
					]);

					$image->save();
				}
			);
		}

		return $this;
	}

	public function delete()
	{
		$query = new Query(static::$_table);
        $article = $query->delete()->where("id", "=", $this->id)->exec();

        $query = new Query("articles_categories");
        $categories = $query->delete()->where("article_id", "=", $this->id)->exec();

        $query = new Query("images");
        $images = $query->delete()->where("article_id", "=", $this->id)->exec();

        return $article;
	}

	public static function findAll()
	{
        $query = new Query(static::$_table);
        $articles = $query->select("*")->exec();

        static::$_stack["findAll"] = [];

        while($article = $articles->fetchObject("\\Application\\Models\\Article")) {
            $article->categories = Category::findByArticle($article->id);
            static::$_stack["findAll"][] = $article;
        }
        
        return static::$_stack["findAll"];
	}

	public static function findOne($articleId)
	{
		$query = new Query(static::$_table);
		$article = $query->select(["id", "status", "title", "text", "created_at", "updated_at"])
						  ->where("id", "=", $articleId)
						  ->exec();
		$article = $article->fetchObject("\\Application\\Models\\Article");

        $article->categories = Category::findByArticle($articleId);
        $article->images = Image::findByArticle($articleId);

        static::$_stack["findOne"] = $article;
        
        return static::$_stack["findOne"];
	}

	public static function findByCategory($categoryId)
	{
        $query = new Query("articles_categories");
        $articles = $query->select(["id", "status", "title", "text", "created_at", "updated_at"])
                          ->join("articles", "article_id", "id", "LEFT")
                          ->where("category_id", "=", $categoryId)
                          ->exec();

        static::$_stack["findByCategory"] = $articles->fetchAll(\PDO::FETCH_CLASS, "\\Application\\Models\\Article");
        
        return static::$_stack["findByCategory"];
	}
}