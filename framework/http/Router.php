<?php
require_once('framework/http/Request.php');
require_once('framework/http/Response.php');


class Router {
	
	private static $_routes;
	private static $_instance;
	
	private $_response;
	private $_request;
	
	
	public static function getInstance() {
		if(empty(self::$_instance)) {
			self::$_instance = new self();
		}
		
		return self::$_instance;
 	}
	
	private static function getRoutes() {
		if(empty(self::$_routes)) {
			self::$_routes = Config::get("route","routes");
		}
		
		return self::$_routes;
	}
	
	private function __construct() {
		$this->_request = Request::getInstance();
		$this->_response = Response::getInstance();
	}
	
		
	public function route() {
		$path = Request::getInstance()->getPath();
		$pathStack = explode("/",$path);
		$filteredPathStack = array();
		foreach($pathStack as $str) {
			if(!empty($str)) {
				$filteredPathStack[]= $str;
			}
		}
		
		$routeData = $this->recursiveRoute($filteredPathStack, self::getRoutes());
		if(!empty($routeData)) {
			$this->callController($routeData);
		}
		
		$this->_response->build();
	}
	
	private function callController($routeData) {
		$class = $routeData['class'];
		$method = $routeData['method'];
		
		require_once("app/controllers/" . $class . ".php");
		
		$obj = new $class();
		$obj->$method();
	}
	
	private function recursiveRoute($pathStack, $routes) {
		if(!empty($routes)) {
			if(empty($pathStack)) {
				if(array_key_exists("?" . $this->_request->getMethod(), $routes)) {
					$routes = $routes["?" . $this->_request->getMethod()];
					//we found an alias to anouter route, start over
					if(array_key_exists("alias", $routes)) {
						return $this->recursiveRoute(array($routes["alias"]),self::getRoutes());
					}else {
						return $routes;
					}
				}else if(array_key_exists("*", $routes)) {
					return $this->recursiveRoute($pathStack,$routes["*"]);
				}
			}else {
				$nextPath = array_pop($pathStack);
				if(array_key_exists($nextPath, $routes)) {
					return $this->recursiveRoute($pathStack, $routes[$nextPath]);
				}else if(array_key_exists("*", $routes)) {
					return $this->recursiveRoute($pathStack,$routes["*"]);
				}
			}
		}
		//$this->_response->error(404);
	}
}
