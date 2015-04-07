<?php

require_once('framework/backend/Redis.php');

class Ticket {
	
	protected $_ticket;
	
	protected $_shardId;
	protected $_poolId;
	protected $_autoIncrement;
	
	protected static $i = 0;
	
	public static function create($poolId, $shardId) {
		$ins = new Ticket();
		$ins->setPoolId($poolId);
		$ins->setShardId($shardId);
		
		return $ins;
	}
	
	public function __construct($value = null) {
		if(!is_null($value)) {
			$this->_ticket = $value;
		}
	}
	
	public function setPoolId($poolId) {
		$this->_poolId = $poolId;
	}
	
	public function setShardId($shardId) {
		$this->_shardId = $shardId;
	}
	
	public function setValue($val) {
		$this->_ticket = $val;
	}
	
	public function getValue() {
		if(empty($this->_ticket)) {
			$this->_ticket = $this->_generateTicket();
		}
		
		return $this->_ticket;
	}
	
	public function getPoolId() {
		if(empty($this->_poolId) && !empty($this->_ticket)) {
			$this->_deconstructTicket();
		}
		
		return $this->_poolId;
	}
	
	public function getShardId() {
		if(empty($this->_shardId) && !empty($this->_ticket)) {
			$this->_deconstructTicket();
		}
		
		return $this->_poolId;
	}
	
	protected function _deconstructTicket() {
		if(empty($this->_ticket)) {
			return;
		}
		$ticket = gmp_init($this->_ticket);
		
		$payload = gmp_div($ticket, gmp_pow(2,50));
		
		$this->_shardId = gmp_and($payload, 0xFF);
		$this->_poolId = gmp_div($payload, gmp_pow(2,8));
	}
	
	
	protected function _generateTicket() {
		$poolId = $this->_poolId ? $this->_poolId :  0;
		$shardId = $this->_shardId ? $this->_shardId : 0;
		if(!$this->_autoIncrement) {
			$this->_autoIncrement = $this->_getAutoIncrement();
		}
		
		$largePrime = gmp_pow("3", "34"); 
		$maxIncrement = gmp_pow("2","48");
		
		$id = gmp_mul($poolId, gmp_pow(2,58));
		$id = gmp_or($id, gmp_mul($shardId, gmp_pow(2,50)));
		$id = gmp_or($id, gmp_mod(gmp_mul($this->_autoIncrement, $largePrime),$maxIncrement));
		
		return gmp_strval($id);
	}
	
	
	protected function _getAutoIncrement() {
		return ++self::$i;
		
		$pieces = array();
		if($this->_poolId) {
			$pieces[]=$this->_poolId;
		}
		if($this->_shardId) {
			$pieces[]=$this->_shardId;
		}
		
		$scope = join(":",$pieces);
		
		$key = self::_getKey($scope);
		$val = Redis::getInstance()->incr($key);
		
		return $val;
		
	}
	
	protected function _getKey($scope = null) {
		return "TICKET:" . ($scope ? $scope . ":" : "") . "autoIncrement";
	} 
	
	
}

