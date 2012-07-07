<?php

namespace Bloom\Routing;

use Bloom\Routing\Router;
use Bloom\Http\Request;
use Bloom\Http\Response;

/**
 * Dispatcher class which do all the hard stuff to match current request to a route,
 * get params, load the controller, call the action then send a response to the client.
 *
 * Usage : 
 *		Dispatcher::dispatch(new \Bloom\Http\Request(), new \Bloom\Http\Response());
 *		
 */
class Dispatcher
{
	protected static $_currentRequest = null;
	protected static $_response = null;

	public static function dispatch(Request $request, Response $response)
	{	
	 	static::$_response = &$response;
	 	
	 	$request = Router::parseRequest($request);
	 	static::$_currentRequest = &$request;

		$routings   = $request->getRoutings();
		$controller = static::_loadController($routings['controller']);
		$controller->callAction();

		$response->send();
	}

	protected static function _loadController($controllerName)
	{
		$controller = "\\Application\\Controllers\\". ucfirst($controllerName) ."Controller";
		$controller = new \ReflectionClass($controller);
		
		return $controller->newInstance(static::$_currentRequest, static::$_response);
	}
}