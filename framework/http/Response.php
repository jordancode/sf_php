<?php

require_once('framework/View.php');

class Response {
	
	private static $_instance;

	private $_data;
	private $_template;
	private $_isPartial;

	public static function getInstance() {
		if(empty(self::$_instance)) {
			self::$_instance = new self();
		}
		
		return self::$_instance;
	}
	
	public function __construct() {
		$this->_data = array();
		$this->add("success",0);
	}
	
	public function success() {
		return $this->add("success",1);
	}
	
	public function add($key, $value) {
		$this->_data[$key] = $value;
		return $this;
	}
	
	public function addX($key, $value) {
		if(!array_key_exists($key, $this->_data)) {
			$this->add($key, $value);
		}
		return $this;
	}
	
	public function setTemplate($template, $isPartial = false) {
		$this->_template = $template;
		$this->_isPartial = $isPartial;
		
		return $this;
	}
	
	public function build() {
		if(Request::getInstance()->isJSON()) {
			$jsonData = json_encode($this->_data);
			
			header('Content-Type: application/json');
			echo($jsonData);
		} elseif(!empty($this->_template)) {
			
			$view = new View($this->_template, $this->_data, $this->_isPartial);
			$view->printHtml();
		} else {
			$this->error();
		}
		
		die();
	}
	
	public function error($errorCode = 404) {
		header("HTTP/1.0 404 Not Found");
		die();
	}
	
	public function redirect($location = "/") {
		$url = $this->_getFullRedirectUrl($location);
		
		header("location: $url");
		die();
	}
	
	private function _getFullRedirectUrl($url) {
		
		return $url;
	}
	
}
