<?php

abstract class BaseConfig {
	
	
	private $_config;
	
	public function get($path) {
		
		if(empty($this->_config)) {
			$this->_config = $this->getConfig();
		}
		
		$pathArr = explode(".", $path);
		
		return $this->recursiveGet($pathArr, $this->_config);
	}
	
	private function recursiveGet($pathArr, $obj) {
		if(empty($obj)) {
			return array(); //obj nested less than path, return empty array
		}
		
		if(empty($pathArr)) {
			return $obj; //success! return the object
		}
		
		if(!array_key_exists($pathArr[0], $obj)) {
			return array(); //bad path, return empty array
		}
		
		return $this->recursiveGet(array_slice($pathArr, 1), $obj[$pathArr[0]]);
	}
	
	
	
	abstract protected function getConfig();	
}
