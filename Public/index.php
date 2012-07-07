<?php

use Bloom\Autoloader;
use Bloom\Config;
use Bloom\Database\Database;
use Bloom\Routing\Dispatcher;
use Bloom\Http\Request;
use Bloom\Http\Response;

session_start();

define("DS", DIRECTORY_SEPARATOR);
define("PS", PATH_SEPARATOR);

define("BASE_PATH", dirname(dirname(__FILE__)));

define("LIBRARY_PATH", BASE_PATH ."/Bloom");

define("APPLICATION_PATH", BASE_PATH ."/Application");
define("CONFIGS_PATH", APPLICATION_PATH ."/Configs");
define("CONTROLLERS_PATH", APPLICATION_PATH ."/Controllers");
define("MODELS_PATH", APPLICATION_PATH ."/Models");
define("VIEWS_PATH", APPLICATION_PATH ."/Views");

require(LIBRARY_PATH . DS ."Autoloader.php");
require(LIBRARY_PATH . DS ."Utils". DS . "Common.php");

// Start autoloading classes
Autoloader::register([BASE_PATH]);

// Initialize application's config files
Config::loadConfigs([CONFIGS_PATH]);

// Initialize database
Database::connect(Config::getConfigs("database"));

// Dispatch the request and the response
Dispatcher::dispatch(new Request(), new Response());