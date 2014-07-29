<?php
class SessionComponent extends Component{
	/**
	 * write a value to session
	 * @param mixed $key string or array
	 * if array,each value of the order on behalf of a multidimensional array array of keys for each level
	 * @param mixed $value
	 */
	public static function write($key, $value){
		$key = self::filter($key);
		$_SESSION = self::_write($_SESSION, $key, $value);
	}
	/**
	 * read a value from session
	 * @param mixed $key string or array
	 * if array, each value of the order on behalf of a multidimensional array array of keys for each level
	 */
	public static function read($key){
		$key = self::filter($key);
		return self::_read($_SESSION, $key);
	}
	/**
	 * delete a value from session
	 * @param mixed $key string or array
	 * if array, each value of the order on behalf of a multidimensional array array of keys for each level
	 */
	public static function delete($key){
		$key = self::filter($key);
		$_SESSION = self::_delete($_SESSION, $key);
	}
	/**
	 * if a key in session
	 * @param mixed $key string or array<br />
	 * if array, <br />
	 * when flag is true,each value of the order on behalf of a multidimensional array array of keys for each level;<br />
	 * when flag is false, key means multiple values must exist 
	 * @param bool $flag whether the parameter key 
	 * @return boolean
	 */
	public static function have($key, $flag = true){
		$key = self::filter($key);
		if($flag){
			return self::_have($_SESSION, $key);
		}else{
			$res = true;
			foreach ($key as $k){
				if(!isset($_SESSION[$k])){
					$res = false;break;
				}
			}
			return $res;
		}
	}
	
	private static function _write($arr, $key, $value){
		$count = count($key);
		$t = $key;
		foreach ($key as $k => $v){
			if($count > 1){
				unset($t[$k]);
				$arr[$v] = isset($arr[$v]) && is_array($arr[$v]) ? $arr[$v] : array();
				$arr[$v] = self::_write($arr[$v], $t, $value);
			}else{
				$arr[$v] = $value;
			}
			break;
		}
		return $arr;
	}
	private static function _read($arr, $key){
		$count = count($key);
		$t = $key;
		$result = null;
		foreach ($key as $k => $v){
			if(isset($arr[$v])){
				if($count > 1){
					unset($t[$k]);
					$result = self::_read($arr[$v], $t);
				}else{
					$result = $arr[$v];
				}
			}
			break;
		}
		return $result;
	}
	private static function _have($arr, $key){
		$count = count($key);
		$t = $key;
		$result = false;
		foreach ($key as $k => $v){
			if(isset($arr[$v])){
				if($count > 1){
					unset($t[$k]);
					$result = self::_have($arr[$v], $t);
				}else{
					$result = true;
				}
			}
			break;
		}
		return $result;
	}
	private static function _delete($arr, $key){
		$count = count($key);
		$t = $key;
		foreach ($key as $k => $v){
			if(isset($arr[$v])){
				if($count > 1){
					unset($t[$k]);
					$arr[$v] = self::_delete($arr[$v], $t);
				}else{
					unset($arr[$v]);
				}
			}
			break;
		}
		return $arr;
	}
	/**
	 * if a value not an array, puts it into an array, then return the array
	 * @param mixed $key
	 * @return array
	 */
	private static function filter($key){
		return is_array($key) ? $key : array($key);
	}
}