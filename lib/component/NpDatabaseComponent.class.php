<?php
class NpDatabaseComponent extends Component{
	static private $default = array(
			'type' => 'int',
			'length' => '11',
			'comment' => '',
			'value' => false,
			'null' => true,
			'auto' => false
			);
	static public function dropDatabase($dbName){
		$sql = "drop database if exists {$dbName};";
		return $sql;
	}
	
	static public function createDatabase($dbName){
		$sql = "create database {$dbName};";
		return $sql;
	}
	
	static public function alterCode($dbName, $charset){
		$sql = "alter database {$dbName} default character set = {$charset};";
		return $sql;
	}
	
	static public function useDatabase($dbName){
		$sql = "use $dbName";
		return $sql;
	}
	
	static public function createTable($tableName, Array $params, $primary_key = '', $default = array(), $code = 'utf8', $engine = 'MyISAM'){
		$sql = "create table `{$tableName}`(";
		$defaultValue = self::$default;
		$tmp = $defaultValue;
		foreach ($tmp as $k => $v){
			if(isset($default[$k])){
				$defaultValue[$k] = $default[$k];
			}
		}
		foreach ($params as $key => $value){
			if(empty($primary_key)){
				$primary_key = $key;
			}
			$type = isset($value['type']) ? $value['type'] : $defaultValue['type']; 
			$sql .= "`$key` $type";
			$length = isset($value['length']) ? $value['length'] : $defaultValue['length'];
			if($length !== false){
				$sql .= "({$length})";
			}
			$null = isset($value['null']) ? $value['null'] : $defaultValue['null'];
			if($null == false){
				$sql .= " not null";
			}
			$auto = isset($value['auto']) ? $value['auto'] : $defaultValue['auto'];
			if($auto === true){
				$sql .= " AUTO_INCREMENT";
			}
			$dv = isset($value['value']) ? $value['value'] : $defaultValue['value'];
			if($dv !== false){
				$sql .= " default '{$dv}'";
			}
			$comment = isset($value['comment']) ? $value['comment'] : $defaultValue['comment'];
			if($comment !== false){
				$sql .= " comment '{$comment}'";
			}
			$sql .= ',';
		}
		$engine = empty($engine) ? 'MyISAM' : $engine;
		$code = empty($code) ? 'utf8' : $code;
		$sql .= "primary key(`{$primary_key}`)) ENGINE={$engine} DEFAULT CHARSET={$code};";
		return $sql;
	}
}
?>