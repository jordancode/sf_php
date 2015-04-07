<?php

class View {
	
	private $_pageName;
	private $_params;
	private $_partial;	
	
	public function __construct($pageName, $params, $partial = false) {
		$this->_pageName = $pageName;
		$this->_params = $params;
		$this->_partial = $partial;
	}
	
	public function fetchHtml() {
		
		ob_start();
		
		$params = $this->_params;
		$pageName = $this->_pageName;
		
		if($this->_partial) {
			$f = function() use ($params, $pageName) {
				require('/app/views/' . $pageName . '.php');
			};
		}else {
			$f = function() use ($params, $pageName) {
				require('app/views/partial/app.php');
			};
		}
		$f();
		
		$html = ob_get_contents();
		ob_end_clean();
		
		return $html;
	} 
	
	public function printHtml() {
		$html = $this->fetchHtml();
		echo($html);
	}
}
