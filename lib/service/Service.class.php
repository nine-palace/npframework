<?php
class Service extends Object{
	public $limit = 0;
	public $page = 0;
	public function __construct(){
		$this->initialize();
	}
	
	public function initialize(){
		
	}
	
	public function getList($conditions = array(), $fields = array(), $order = array(), $group = array()){
		$result = array();
		$result['list'] = $this->model->find($conditions, $fields, $order, $group, $this->limit, $this->page);
		$result['count'] = $this->model->count($conditions);
		return $result;
	}
	
	public function setLimit($limit, $page){
		$this->limit = $limit;
		$this->page = $page;
	}
}
?>