<?php
class Model extends Object{
	public static $dataSource = null;
	public $id = null;
	public $errorCode = 0;
	public $errorMsg = 'Default';
	public $table = null;
	public $type = 'mysql';
	
	public function __construct($table = null){
		if(!empty($table)){
			$this->table = $table;
		}else{
			$class = get_class($this);
			$suffix = isset(Configure::$extension['model']) ? Configure::$extension['model'] : 'model';
			if($class != $suffix && $class != 'Model'){
				$len = strlen($class) - strlen($suffix);
				$class = substr($class, 0, $len);
				$this->table = $class;
			}
		}
		$this->initialize($table);
	}
	public function initialize($param = null){
		
	}
	/**
	 * query by a custom sql
	 * @param string $sql
	 * @return mixed result
	 */
	public function query($sql, $table = null){
		return $this->excuteCURD($sql, 'query', $table);
	}
	/**
	 * find a record by id
	 * @param mixed $id
	 */
	public function findById($id, $field = '*', $table = null){
		$params = array('conditions' => array('id' => $id), 'fields' => $field);
		$res = $this->excuteCURD($params, 'find', $table);
		if(!empty($res) && is_array($res)){
			return $res[0];
		}else{
			return false;
		}
	}
	/**
	 * add a record
	 * @param mixed $param
	 * @return mixed true for success or false for failed
	 */
	public function add($data, $table = null){
		return $this->excuteCURD($data, 'add', $table);
	}
	/**
	 * delete records
	 * @param mixed $param
	 * @return mixed true for success or false for failed
	 */
	public function delete($conditions, $table = null){
		return $this->excuteCURD($conditions, 'delete', $table);
	}
	/**
	 * update records
	 * @param mixed $param
	 * @return mixed true for success or false for failed
	 */
	public function update($conditions, $data, $table = null){
		$params = array('conditions' => $conditions, 'data' => $data);
		return $this->excuteCURD($params, 'update', $table);
	}
	/**
	 * find some data
	 * @param mixed $param
	 * @return mixed array of data for success or false for failed
	 */
	public function find($conditions = array(), $fields = '*', $order = '', $group = '', $limit = 0, $page = 0, $table = null){
		$fields = empty($fields) ? '*' : $fields;
		$params = array(
				'conditions' => $conditions,
				'fields' => $fields,
				'order' => $order,
				'group' => $group,
				'limit' => array()
		);
		if(is_numeric($limit) && is_numeric($page) && $limit > 0 && $page > 0){
			$start = ($page - 1) * $limit;
			$params['limit'] = array($start, $limit);
		}
		return $this->excuteCURD($params, 'find', $table);
	}
	
	/**
	 * 
	 */
	public function count($conditions = array(), $table = null){
		return $this->excuteCURD($conditions, 'count', $table);
	}
	
	/**
	 * get some information about the error for access data source
	 * @return array
	 */
	public function getError(){
		return array(
				'code' => $this->errorCode,
				'msg' => $this->errorMsg
				);
	}
	/**
	 * excute a sql
	 * @param string $sql
	 * @return array the results
	 */
	private function excuteCURD($param, $method, $table = null){
		if(is_null(self::$dataSource)){
			$dataClass = strtoupper(substr($this->type, 0, 1)).substr($this->type, 1);
			Includes::uses($dataClass, 'driver');
			Includes::uses('Database', 'driver');
			Includes::uses('Connect', 'driver');
			self::$dataSource = new $dataClass;
		}
		$table = empty($table) ? $this->table : $table;
		return self::$dataSource->$method($param, $table);
	}
}
?>
