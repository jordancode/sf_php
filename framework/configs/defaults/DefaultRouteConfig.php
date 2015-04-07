<?php
require_once('framework/config/BaseConfig.php');

class DefaultRouteConfig extends BaseConfig {
	
	
	/**
	 * Routes
	 * 
	 * syntax: 
	 * [PATH]
	 * *
	 * {INT} : any integer
	 * {[INTEGER]|[INTEGER]}
	 * {STRING} : takes any string
	 * {'[STRING]'|'[STRING]'|...} : list of valid strings
	 * 
	 * ?[HTTP METHOD],?[HTTP METHOD],... : HTTP methods to route, will return 404 if not listed
	 * 
	 * 
	 */
	public function getConfig() {
		return array(
			"routes" => array(		
				"*" => array(
					"?GET" => array(
						"alias" => "index"
					)
				),
				"about" => array(
					"?GET" => array(
						"class" => "PageController",
						"method" => "about"
					)
				),
				"terms" => array(
					"?GET" => array(
						"class" => "PageController",
						"method" => "terms"
					)
				),
				"contact" => array(
					"?GET" => array(
						"class" => "PageController",
						"method" => "contact"
					)
				),
				"index" => array(
					"?GET" => array(
						"class" => "PageController",
						"method" => "index"
					)
				)
			)
		);
	}
	
	
}
