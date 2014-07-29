<?php
class Mysql extends Database{
	public $type = 'mysql';
	
	public function afterAdd($res){
		if($res){
			return mysql_insert_id($this->link);
		}else{
			return false;
		}
	}
	
	public function query($sql){
		$res = mysql_query($sql, $this->link);
		if($res === false){
			throw new DBQueryErrorException($sql);
		}
		if(is_resource($res)){
			$result = array();
			while(@$row = mysql_fetch_array($res)){
				$result[] = $row;
			}
			return $result;
		}else{
			return $res;
		}
	}
	
	public function create(){
		$link = @mysql_connect($this->host.':'.$this->port, $this->user, $this->pwd);
		if($link === false){
			throw new DBConnectErrorException(array());
		}
		$res = mysql_select_db($this->prefix.$this->dbName, $link);
		$sql = 'set names utf8';
		$res = mysql_query($sql, $link);
		if($res === false){
			throw new DBQueryErrorException($sql);
		}
		$this->link = $link;
	}
}