<?php
/**
 * A component contains some sort method
 * @author np
 *
 */
class SortComponent extends Component{
	/**
	 * is parameter one less than parameter two
	 * @param mixed $arg1 numeric or string
	 * @param mixed $arg2 numeric or string
	 * @return bool true if parameter one less than parameter two, false otherwise
	 */
	public static function lt($arg1, $arg2){
		return $arg1 < $arg2 ? true : false;
	}
	/**
	 * is paramter one greater than parameter two
	 * @param mixed $arg1 numeric or string
	 * @param mixed $arg2 numeric or string
	 * @return boolean true if parameter one greater than parameter two, false otherwise
	 */
	public static function gt($arg1, $arg2){
		return $arg1 > $arg2 ? true : false;
	}
	/**
	 * bubble sort.(only for numeric or string)
	 * @param array $args the array to sort
	 * @param string $sort the sort mode.<br />
	 * 'asc' or 'ASC' or 0 means sort by ascending.<br />
	 * 'desc' or 'DESC' or 1 means sort by descending.
	 * @param boolean $sort_by_value is sort by value. if false,it will sort by the array's keys.
	 * @return array after sort
	 */
	public static function bubble(array $args, $asc = true, $sort_by_value = true){
		return self::_sort($args, '_bubble', $asc, $sort_by_value);
	}
	public static function dichotomy(array $args, $asc = true, $sort_by_value = true){
		return self::_sort($args, '_dichotomy', $asc, $sort_by_value);
	}
	/**
	 * quick sort.(only for numeric or string)
	 * @param array $args the array to sort
	 * @param string $sort the sort mode.<br />
	 * 'asc' or 'ASC' or 0 means sort by ascending.<br />
	 * 'desc' or 'DESC' or 1 means sort by descending.
	 * @param boolean $sort_by_value is sort by value. if false,it will sort by the array's keys.
	 * @return array after sort
	 */
	public static function quickSort(array $args, $asc = true, $sort_by_value = true){
		return self::_sort($args, '_quickSort', $asc, $sort_by_value);
	}
	/**
	 * sort an array.(only for numeric or string)
	 * @param array $args the array to sort
	 * @param string $sort the sort mode.<br />
	 * 'asc' or 'ASC' or 0 means sort by ascending.<br />
	 * 'desc' or 'DESC' or 1 means sort by descending.
	 * @param boolean $sort_by_value is sort by value. if false,it will sort by the array's keys.
	 * @return array after sort.
	 */
	public static function sort(array $args, $asc = true, $sort_by_value = true){
		return self::_sort($args, '_quickSort', $asc, $sort_by_value);
	}
	/**
	 * bubble sort
	 * @param array $args
	 * @param string $obj
	 * @param string $asc
	 * @return unknown
	 */
	private static function _bubble(array $args, $obj = 'value', $asc = true){
		$all = count($args);
		for ($i = 0; $i < $all - 1; $i++){
			for ($j = 0; $j < $all; $j++){
				$res = $asc  ? self::gt($args[$i][$obj], $args[$j][$obj]) : self::lt($args[$i][$obj], $args[$j][$obj]);
				if($res){
					$t = $args[$i];
					$args[$i] = $args[$j];
					$args[$j] = $t;
				}
			}
		}
		return $args;
	}
	private static function _dichotomy(array $args, $obj = 'value', $asc = true){
		return $args;
	}
	private static function _quickSort(array $args, $obj = 'value', $asc = true){
		$_size = count($args);
		if($_size > 1){
			$k = $args[0];
			$x = array();
			$y = array();
			for ($i = 1; $i < $_size; $i++){
				$res = $asc ? self::lt($args[$i][$obj], $k[$obj]) : self::gt($args[$i][$obj], $k[$obj]);
				if($res){
					$x[] = $args[$i];
				}else{
					$y[] = $args[$i];
				}
			}
			
			$x = self::_quickSort($x, $obj, $asc);
			$y = self::_quickSort($y, $obj, $asc);
			$args = array_merge($x, array($k), $y);
		}
		return $args;
	}
	private static function _sort(array $args, $module = '_bubble', $asc = true, $sort_by_value = true){
		$tmp = array();
		foreach ($args as $key => $value){
			if(!is_numeric($key) && !is_numeric($value) && is_string($key) && !is_string($value)){
				return $args;
			}
			$t = array('key' => $key, 'value' => $value);
			$tmp[] = $t;
		}
		$res = array();
		$obj = $sort_by_value ? 'value' : 'key';
		$tmp = self::$module($tmp, $obj, $asc);
		foreach ($tmp as $key => $value){
			if(is_numeric($value['key'])){
				$res[] = $value['value'];
			}else{
				$res[$value['key']] = $value['value'];
			}
		}
		return $res;
	}
}