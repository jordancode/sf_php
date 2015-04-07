<?php
require_once('framework/backend/MySql.php');
require_once('framework/utils/QueryBuilder.php');


abstract class BaseModel {
	
	private $_origRow;
	private $_currentRow;
	
	private $_id;
	
	public function __construct($id = null, $row = null) {
		$this->_id = $row;	
		$this->_origRow = null;
		$this->_currentRow = $row;
	}
	
	public abstract function save();
	
	public abstract function fetch();
	
	public function isDirty() {
		if(count($this->_origRow) !== count($this->_currentRow)) {
			return true;
		}
		
		foreach($this->_currentRow as $key => $value) {
			if(!array_key_exists($key, $this->_origRow) || $this->_currentRow[$key] !== $this->_origRow[$key]) {
				return true;
			}
		}
		
		return false;
	}
	
	protected function _getDirtyCols() {
		$dirtyRows = array();
		
		if(is_null($this->_origRow) || is_null($this->_currentRow)) {
			return $this->_currentRow;
		}

		foreach($this->_currentRow as $key => $value) {
			if(!array_key_exists($key, $this->_origRow) || $this->_currentRow[$key] !== $this->_origRow[$key]) {
				$dirtyRows[$key] = $this->_currentRow[$key];
			}
		}
		
		return $dirtyRows;
	}
	
	protected function _fetchFromTable($tableName, $where = null) {
		$mysql = MySql::getInstance();
		
		if(is_null($where)) {
			$where = array( 'id' => $this->_id);
		}
		
		$sql = QueryBuilder::select($tableName, $where);
		$result = $mysql->query($sql, array_values($where));
		
		if(count($result)) {
			$this->_origRow = $result[0];
			$this->_currentRow = $this->_origRow;
		}
		
		return $result;
	}
	
	protected function _saveToTable($tableName, $insert = null, $set = null) {
		
		$mysql = MySql::getInstance();
		
		if(is_null($insert)) {
			$insert = $this->_currentRow;
		}
		
		if(is_null($set)) {
			$set = $this->_getDirtyCols();
		}
		
		$sql = QueryBuilder::insertOrUpdate($tableName, $insert, $set);
		$result = $mysql->query($sql, array_values(array_merge($insert)));
		
		if($result) {
			$this->_origRow = $this->_currentRow;
		}
		
		return $result;
	}
	
	
	
}
