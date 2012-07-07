<?php

use Bloom\Routing\Router;

return call_user_func(function() {
	Router::addRoute("/", ["controller" => "index", "action" => "index"]);
	Router::addRoute("/users/signin", ["controller" => "users", "action" => "signin"]);
	Router::addRoute("/users/signout", ["controller" => "users", "action" => "signout"]);
	Router::addRoute("/users", ["controller" => "users", "action" => "index"]);

	Router::mapResources("articles");
	Router::addRoute("/admin/articles", ["controller" => "articles", "action" => "adminIndex"]);
	Router::addRoute("/admin/articles/add", ["controller" => "articles", "action" => "adminAdd"]);
	Router::addRoute("/admin/articles/edit/:id", ["controller" => "articles", "action" => "adminEdit"]);
	Router::addRoute("/admin/articles/delete/:id", ["controller" => "articles", "action" => "adminDelete"]);

	Router::mapResources("categories");
});