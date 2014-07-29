<?php
class Database extends Connect{
	protected $moduleSql = array(
			'order' => ' order by ',
			'group' => ' group by ',
			'limit' => ' limit '
			);
	
	protected $length = 18446744012;
	
	public function query($sql){
		
	}
	/**
	 * add a record
	 * @param mixed $param
	 * @return mixed true for success or false for failed
	 */
	public function add($param, $table){
		$data = $this->getCURDData($param, $table);
		$sql = 'insert into '.$table.'(';
		foreach ($data as $key => $value){
			$sql .= "`$key`,";
		}
		$sql = substr($sql, 0, -1);
		$sql .= ') values(';
		foreach ($data as $k => $v){
			$sql .= "'$v',";
		}
		$sql = substr($sql, 0, -1);
		$sql .= ');';
		$res = $this->query($sql);
		return $this->afterAdd($res);
	}
	
	public function afterAdd($res){
		return $res;
	}
	/**
	 * delete records
	 * @param mixed $param
	 * @return mixed true for success or false for failed
	 */
	public function delete($param, $table = null){
		$conditions = $this->getCURDData($param, $table);
		$sql = 'delete from '.$table;
		$sql .= $this->createWhereSql($conditions);
		$count = $this->count($param, $table);
		if($count == 0){
			return 0;
		}else{
			$res = $this->query($sql);
			if($res === false){
				return false;
			}else{
				return $count;
			}
		}
	}
	/**
	 * update records
	 * @param mixed $param
	 * @return mixed true for success or false for failed
	 */
	public function update($param, $table = null){
		$conditions = isset($param['conditions']) ? $param['conditions'] : array();
		$conditions = $this->getCURDData($conditions, $table);
		$data = isset($param['data']) ? $param['data'] : array();
		$data = $this->getCURDData($data, $table);
		$sql = 'update '.$table.' set ';
		$tmp = array();
		foreach ($data as $key => $value){
			$tmp[] = "`{$key}` = '{$value}'";
		}
		$sql .= implode(',', $tmp);
		$sql .= $this->createWhereSql($conditions);
		$res = $this->query($sql);
		return $res;
	}
	/**
	 * find some data
	 * @param mixed $param
	 * @return mixed array of data for success or false for failed
	 */
	public function find($param = null, $table = null){
		$conditions = isset($param['conditions']) ? $param['conditions'] : array();
		$fields = isset($param['fields']) ? $param['fields'] : '';
		$sort = isset($param['order']) ? $param['order'] : '';
		$group = isset($param['group']) ? $param['group'] : '';
		$limit = isset($param['limit']) ? $param['limit'] : '';
		if(is_array($limit) && count($limit) == 1){
			$limit[] = $this->length;
		}
		$conditions = $this->getCURDData($conditions, $table);
		if(empty($fields)){
			$sql = 'select * from ';
		}else if(is_array($fields)){
			$sql = 'select ';
			$tmp = '';
			foreach ($fields as $v){
				$tmp .= ','.$v;
			}
			$tmp = substr($tmp, 1);
			$sql .= $tmp.' from ';
		}else{
			$sql = "select $fields from ";
		}
		$sql .= $table;
		$sql .= $this->createWhereSql($conditions);
		$sql .= $this->createSql($group, 'group');
		$sql .= $this->createSql($sort, 'order');
		$sql .= $this->createSql($limit, 'limit');
		$res = $this->query($sql);
		return $res;
	}
	
	/**
	 * 
	 */
	public function count($param = null, $table = null){
		$conditions = $param;
		$conditions = $this->getCURDData($param, $table);
		$sql = 'select count(*) from '.$table;
		$sql .= $this->createWhereSql($conditions);
		$res = $this->query($sql);
		return intval($res[0][0]);
	}
	/**
	 * 
	 */
	public function showColumns($all = false, $table = null){
		$table = empty($table) ? $this->table : $table;
		if(empty($table)){
			$this->errorCode = 101;
			$this->errorMsg = '';
			return false;
		}
		$table = classtotable($table);
		$sql = 'show columns from '.$table;
		$res = $this->query($sql);
		$result = array();
		if($all !== true){
			foreach ($res as $r){
				$result[] = $r['Field'];
			}
		}else{
			$result = $res;
		}
		return $result;
	}
	/**
	 * excute the method to access data source by different format param
	 * @param mixed $param access parameter
	 * @param string $method access type
	 * @return mixed the results for success or false for failed
	 */
	protected function getCURDData($rawData, $table = null){
		return is_object($rawData) ? $this->getEffectiveDataByObject($rawData, $table) : $rawData;
// 		if(is_array($rawData)){
// 			$data = $this->getEffectiveDataByArray($rawData, $table);
// 		}else if(is_object($rawData)){
// 			$data = $this->getEffectiveDataByObject($rawData, $table);
// 		}else{
// 			$data = $rawData;
// 		}
// 		return $data;
	}
	
	protected function createSql($params, $module){
		$res = '';
		if(isset($this->moduleSql[$module])){
			if(!empty($params)){
				$res .= $this->moduleSql[$module];
				$tmp = '';
				if(is_array($params)){
					$tmp = implode(',', $params);
				}else{
					$tmp = $params;
				}
				$res .= $tmp;
			}
		}
		return $res;
	}
	
	protected function createWhereSql($params){
		$sql = '';
		if(!empty($params)){
			if(is_array($params)){
				$sql .= ' where ';
				$tmp = '';
				foreach ($params as $key => $value) {
					$tmp .= is_numeric($key) ? " and {$value}" : " and `{$key}`='{$value}'";
				}
				$tmp = substr($tmp, 5);
				$sql .= $tmp;
			}else{
				$sql .= $params;
			}
		}
		return $sql;
	}
	
	/**
	 * get effective values from an array for access data source
	 * @param array $param
	 * @param string $table
	 * @return array
	 */
	protected function getEffectiveDataByArray(Array $param, $table = null){
		$columns = $this->showColumns(false, $table);
		$values = array();
		foreach ($param as $key => $value){
			if(is_numeric($key)){
				$values[] = $value;
			}else{
				if(in_array($key, $columns)){
					$values[$key] = $value;
				}
			}
		}
		return $values;
	}
	/**
	 * get effective values from an object for access data source
	 * @param Object $object
	 * @param string $table
	 * @return array
	 */
	protected function getEffectiveDataByObject(Object $object, $table = null){
// 		$columns = $this->showColumns(false, $table);
		$attrs = get_class_vars(get_class($object));
		$values = array();
		foreach ($attrs as $attr => $value) {
			$values[$attr] = $obejct->$attr;
// 			if(in_array($attr, $columns)){
// 				$values[$attr] = $object->$attr;
// 			}
		}
		return $values;
	}

	protected function isTableExisted($table = null){
		$table = empty($table) ? $this->table : $table;
		$sql = 'show tables';
		$res = $this->query($sql);
		foreach ($res as $r){
			if($r[0] == $table){
				return true;
			}
		}
		return false;
	}
}
?>