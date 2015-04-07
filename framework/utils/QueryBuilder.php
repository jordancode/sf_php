<?php

class QueryBuilder {
	
	public static function insertOrUpdate($table, $insert, $set) {
		$ret = self::insert($table, $insert);
		
		$updateClause = array();
		foreach($set as $col => $value) {
			$updateClause[] = $col . "=VALUES(" . $col . ")";
		}
		
		$ret .= " ON DUPLICATE KEY UPDATE " . join(", " , $updateClause);
		
		return $ret;
	}
	
	public static function insert($table, $insert) {
		
		return "INSERT INTO " . $table . "(" . join(", ", array_keys($insert)) . ") VALUES (" . self::qs($insert) . ")";
	}
	
	public static function update($table, $set, $where) {
		return "UPDATE " . $table . " " . self::setClause($set) . " " . self::whereClause($where); 
	}
	
	public static function select($table, $where) {
		return "SELECT * FROM  " . $table . " " . self::whereClause($where);
	}
	
	private static function whereClause($where) {
		$whereVals = array();
		foreach($where as $key => $value) {
			$whereVals[] = $key ."=?";
		}
		
		return "WHERE " . join(" AND ", $whereVals);
	}
	
	private static function setClause($set) {
		$setVals = array();
		
		foreach($set as $key => $value) {
			$setVals[] = $key . "=?";
		}
		
		return "SET " . join(", ", $setVals);
	}
	
	private static function qs($cols) {
		$qs = array();
		for($i = 0; $i < count($cols); $i++) {
			$qs[] = "?";
		}
		
		return join(", ", $qs);
	}
}


