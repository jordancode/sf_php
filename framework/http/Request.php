<?php

class Request {
	
	private static $_instance;
	
	public static function getInstance() {
		if(empty(self::$_instance)) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	
	private function __construct() {
	}
	
	public function isJSON() {
		return $this->getMethod() === "POST" || (substr($this->getUri(), -5) === ".json");
	}
	
	public function getProtocol() {
		return "HTTP";
	}
	
	public function getHost() {
		return $_SERVER['HTTP_HOST'];
	}

	public function getUri() {
		return $this->getProtocol() . "://" . $this->getHost() . $_SERVER['REQUEST_URI']; //<- This is not right
	}
	
	public function getPath() {
		$uri = $_SERVER['REQUEST_URI'];
		
		$uriSplit = explode("?", $uri);
		if(count($uriSplit) > 0) {
			return $uriSplit[0];
		}else {
			return "";
		}	
	}
	
	public function getMethod() {
		return $_SERVER['REQUEST_METHOD'];
	}
	
	
	
}
