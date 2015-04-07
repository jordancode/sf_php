<?php

class Redis {
	
	
	public static function getInstance() {
		return new self();
	}
	
	public function incr() {
		//placeholder
		return mt_rand(0,PHP_INT_MAX);
	}
}
