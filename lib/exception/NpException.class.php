<?php
class NpException extends Exception{
	protected $key = '';
	protected $param = array();
	public function __construct($mesKey, $param1 = '', $param2 = '', $param3 = ''){
		$this->key = $mesKey;
		if(!empty($param1)){
			$this->param[] = $param1;
		}
		if(!empty($param2)){
			$this->param[] = $param2;
		}
		if(!empty($param3)){
			$this->param[] = $param3;
		}
	}
	
	public function getMsg($key = 0){
		return isset($this->param[$key]) ? $this->param[$key] : '';
	}
}

/**
 * Exception for database connection
 * @author np
 *
 */
class DBConnectErrorException extends NpException{
	/**
	 * 
	 * @param array $params the config of database
	 */
	public function __construct($params){
		parent::__construct('db_config_error', $params);
	}
}
/**
 * Exception for query error
 * @author np
 *
 */
class DBQueryErrorException extends NpException{
	/**
	 * 
	 * @param string $sql the query sql
	 */
	public function __construct($sql){
		parent::__construct('sql_error', $sql);
	}
}
/**
 * Exception for params error
 * @author np
 */
class ParamsErrorException extends NpException{
	public function __construct($mesKey){
		parent::__construct('', $mesKey);
	}
}
/**
 * Exception for missing file
 * maybe template file or layout file
 * @author np
 *
 */
class FileNotFoundException extends NpException{
	/**
	 * Exception for missing file
	 * maybe template file or layout file
	 * @param string $module the module of file
	 * @param string $filename the file name
	 */
	public function __construct($module, $filename){
		parent::__construct($module.'_not_found', $filename);
	}
}
/**
 * Exception for access a action that can't access for public
 * @author np
 *
 */
class IllegalAccessException extends NpException{
	/**
	 * 
	 * @param string $action the name of action
	 * @param string $controller the name of controller
	 * @param string $app the name of app
	 */
	public function __construct($action, $controller, $app){
		parent::__construct('action_ncot_access', $app, $controller, $app);
	}
}
/**
 * Exception for missing action
 * @author np
 *
 */
class MissActionException extends NpException{
	/**
	 * 
	 * @param string $action the name of action
	 * @param string $controller the name of controller
	 * @param string $app the name of app
	 */
	public function __construct($action, $controller, $app){
		parent::__construct('action_not_found', $app, $controller, $action);
	}
}
/**
 * Exception for missing app
 * @author np
 *
 */
class MissAppException extends NpException{
	/**
	 * @param string $app the name of app
	 */
	public function __construct($app){
		parent::__construct('app_not_found', $app);
	}
}
/**
 * Exception for missing controller
 * @author np
 */
class MissControllerException extends NpException{
	/**
	 * @param string $controller the name of controller
	 * @param string $app the name of app
	 */
	public function __construct($controller, $app){
		parent::__construct('controller_not_found', $app, $controller);
	}
}
?>