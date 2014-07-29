<?php
class Connect extends Object{
	public $host = null;
	public $port = null;
	public $prefix = null;
	public $dbName = null;
	public $user = null;
	public $pwd = null;
	public $charset = null;
	public $type = 'default';
	public static $link = null;
	
	public function __construct(){
		$this->init();
		$this->create();
	}
	
	private function init(){
		$this->host = Configure::getCustomDbConfig($this->type, 'host');
		$this->port = Configure::getCustomDbConfig($this->type, 'port');
		$this->user = Configure::getCustomDbConfig($this->type, 'user');
		$this->pwd = Configure::getCustomDbConfig($this->type, 'pwd');
		$this->prefix = Configure::getCustomDbConfig($this->type, 'prefix');
		$this->dbName = Configure::getCustomDbConfig($this->type, 'dbName');
		$this->charset = Configure::getCustomDbConfig($this->type, 'charset');
	}
	
	public function create(){
	}
}
?>