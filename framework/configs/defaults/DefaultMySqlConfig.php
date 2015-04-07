<?php
require_once('framework/config/BaseConfig.php');

class DefaultMySqlConfig extends BaseConfig {
	
	public function getConfig() {
		return array(
			"user" => "mysql",
			"pass" => "",
			"pools" => array(
				1 => array(
					"id" => 1,
					"name" => "main",
					"num_shards" => 256,
					"ips" => array(
						"localhost"
					)
				)
			)
		);
	}
	
	
}
