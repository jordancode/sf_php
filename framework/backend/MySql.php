<?php

class MySql {
	
	private static $_instances = array();
	
	private static $_connectionPool = array();
	
	public static function getFromId($id = null) {
		$t = new Ticket($id);
		return self::get($t->getPoolId(), $t->getShardId());
	}
	
	public static function get($shardId = 0, $pool = "main") {
		if(empty(self::$_instances)) {
			self::$_instance = new self();
		}
		
		return self::$_instance;
	}
	
	private $_config;
	
	public function __construct() {
		$this->_config = MySqlConfig::get();
	}
	
	protected function _getPoolId($poolName) {
		foreach($this->_config['pools'] as $id => $pool) {
			if($pool['name'] === $name) {
				return $id;
			}
		}
		
		return null;
	}
	
	protected function _getConnection($poolNameOrId, $shardId) {
		$hostname = null;
		
		if(!is_int($poolNameOrId)) {
			$poolId = $this->_getPoolId($poolNameOrId);
		}else {
			$poolId = $poolNameOrId;
		}
		
		if(array_key_exists("pools",$poolId)
			&& array_key_exists($pool, $this->_config["pools"])
			&& $shardId < $this->_config["pools"][$pool]["num_shards"]) {
				$index = ($shardId % count($this->_config["pools"][$pool]["ips"]));
				$hostname = $this->_config["pools"][$pool]["ips"][$index];
		}else {
			return null;
		}
			
		if(array_key_exists($hostname,$this->_connectionPool)) {
			$connection = new mysqli(
				$hostname,
				$this->_config['user'],
				$this->_config['pass'],
				$pool . "_" . $shardId
			);
			
			if (mysqli_connect_errno()) {
			    printf("Connect failed: %s\n", mysqli_connect_error());
			    return null;
			}
			
			$this->_connectionPool[$hostname] = $connection;
		}
		
		return $this->_connectionPool[$hostname];
	}
	
	public function query($sql, $params = array()) {
		$rows = array();
		$connection = $this->_getConnection();
		
		$statement = $connection->prepare($sql);
		if(!$statement) {
			echo("Bad SQL: " . $sql);
			return false;
		}
		foreach ($params as $p) {
			$str = "s";
			if(is_int($p)) {
				$str = "i";	
			}else if(is_numeric($p)) {
				$str = "d";
			}
			
			$statement->bind_param($str, $p);
		}
		
		$statement->execute();
    	$result = $statement->get_result();
		
		if(empty($result)) {
			return $statement->affected_rows;
		}

    	while ($row = $result->fetch_assoc()) {
    		$rows[]= $row;
		}
		
      	$statement->fetch();
      	$statement->close(); 
		
		return $rows;
	}
	
	
	
}
