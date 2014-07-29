<?php
function trim_directory_separator($dir){
	$dss = array('/', '\\');
	foreach ($dss as $ds){
		$tmp = explode($ds, $dir);
		$dir = implode(DS, $tmp);
	}
	return $dir;
}

/**
 * 
 * @param array $array1
 * @param array $array2
 * @param boolean $replace
 * @return multitype:
 */
function array_merge_depth(array $array1, array $array2, $replace = false){
	$res = $array1;
	foreach ($array2 as $key => $value){
		if(!isset($res[$key])){
			$res[$key] = $value;
		}else{
			if(is_array($value) && is_array($res[$key])){
				$res[$key] = array_merge_depth($res[$key], $value, $replace);
			}else{
				if($replace){
					$res[$key] = $value;
				}else{
					$tmp1 = is_array($res[$key]) ? $res[$key] : array($res[$key]);
					$tmp2 = is_array($value) ? $value : array($value);
					$res[$key] = array_merge($tmp1, $tmp2);
				}
			}
		}
	}
	return $res;
}
/**
 * whether a letter is upper
 * @param string $letter
 * @return boolean
 */
function is_upper_letter($letter){
	if(in_array($letter, range('A', 'Z'), true)){
		return true;
	}else{
		return false;
	}
}
/**
 * create a model class
 * @param string $table
 * @return object
 */
function D($table){
	$class = tabletoclass($table);
	if(Includes::isCustomClassExisted($class, 'model')){
		$class = Includes::useModel($class);
		return new $class($table);
	}else{
		return M($table);
	}
}
/**
 * create a model class
 * @param string $table
 * @return object
 */
function M($table){
	$table = classtotable($table);
	return new AppModel($table);
}
/**
 * create an orm class
 * @param string $class
 * @return object
 */
function O($class){
	if(Includes::isCustomClassExisted($class, 'orm')){
		$class = Includes::useOrm($class);
		return new $class();
	}
}
/**
 * create a service class
 * @param string $name
 * @return object
 */
function S($name){
	$class = tabletoclass($name);
	if(Includes::isCustomClassExisted($class, 'service')){
		$class = Includes::useService($class);
		return new $class($name);
	}else{
		return new AppService($name);
	}
}
/**
 * create a url
 * @param string|array $params
 */
function U($params){
	
}
/**
 * translate keyword when multiple languages 
 * @param array|string $keys key in the language pack
 * @param string $value the default value when all the keys are empty
 * @return 
 */
function L($keys, $value = false){
	if(defined('CURRENT_APP') && Configure::$autoAssignLanguage === true){
		if(!isset($GLOBALS["current_assign_lang"]) || !is_array($GLOBALS["current_assign_lang"])){
			$lang = empty($_SESSION[Configure::$sessionKeyForLanguage]) ? Configure::$defaultLanguage : $_SESSION[Configure::$sessionKeyForLanguage];
			Includes::useLang('');
			Includes::useLang('App');
			$langClass = Includes::useLang($lang);
			$tmpL = T($lang, 'lang');
			$langs = array();
			if(Includes::isCustomClassExisted($tmpL, 'lang', CURRENT_APP) === true){
				$object = new $langClass;
				$langs = $object->getLangs();
			}
			$GLOBALS['current_assign_lang'] = $langs;
		}
	}
	$res = '';
	$keys = is_array($keys) ? $keys : array($keys);
	$langsArr = isset($GLOBALS["current_assign_lang"]) && is_array($GLOBALS["current_assign_lang"]) ? $GLOBALS["current_assign_lang"] : array();
	foreach ($keys as $key){
		$res .= empty($res) ? '' : (isset($langsArr['ds']) ? $langsArr['ds'] : '');
		$res .= isset($langsArr[$key]) ? $langsArr[$key] : ($value === false ? $key : '');
	}
	return empty($res) ? $value : $res;
}
/**
 * transform a class name
 * @param string $name
 * @param string $module
 * @return string
 */
function T($name, $module = 'controller'){
	$class = '';
	if(Configure::$autoTransformController === false){
		$class = $name;
	}else{
		$ds = Configure::getCustomSeparator($module);
		$tmp = explode($ds, $name);
		foreach ($tmp as $t){
			$class .= strtoupper(substr($t, 0, 1)).strtolower(substr($t, 1));
		}
	}
	return $class;
}
function tabletoclass($table){
	$table = strtolower($table);
	$table = strtoupper(substr($table, 0, 1)).substr($table, 1);
	$tmp = str_split($table);
	$new = '';
	$flag = false;
	$ds = Configure::getCustomSeparator('table');
	foreach ($tmp as $t){
		if($t == $ds){
			$flag = true;
		}else{
			if($flag){
				$new .= strtoupper($t);
			}else{
				$new .= $t;
			}
		}
	}
	return $new;
}

function classtotable($class){
	if(Configure::$autoTransformTablename !== false){
		$new = '';
		$tableDS = Configure::getCustomSeparator('table');
		$tmp = str_split($class);
		foreach ($tmp as $t){
			if(is_upper_letter($t)){
				$new .= $tableDS.strtolower($t);
			}else{
				$new .= $t;
			}
		}
		$f = substr($new, 0, 1);
		if($f === $tableDS){
			$new = substr($new, 1);
		}
		$class = $new;
	}
	return $class;
}

?>