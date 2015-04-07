<?php


/**
 * Entry point for any site configs.
 * 
 */
class Config {
	
	
	private static $_configs = array();
	
	/**
	 * @param $configName: the name of the config file to grab from
	 * @param $path: a period delimited path for the config value
	 * 
	 * @return value of config requested or null if no config path exists
	 * 
	 * ex: 
	 * 	Config::get("backend", "mysql.port")
	 * 
	 */
	public static function get($configName, $path) {
		if(empty($configName)) {
			return null; 
		}
		
		$className = strtoupper(substr($configName,0,1)) . substr($configName, 1) . "Config";
		
		if(!array_key_exists($className, self::$_configs)) {
			if(file_exists("app/configs/" . $className . ".php")) {
				require_once("app/configs/" . $className . ".php");
				$obj = new $className();
			}elseif(file_exists("framework/configs/defaults/Default" . $className . ".php")) {
				$defaultClassName = "Default" . $className;
				require_once("framework/configs/defaults/" . $defaultClassName . ".php");
				$obj = new $defaultClassName();
			}else {
				throw new Exception("No config found");
			}
			
			self::$_configs[$className] = $obj;
		}
		
		return self::$_configs[$className]->get($path);
	}
		
}
