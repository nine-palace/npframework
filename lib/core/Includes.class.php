<?php
class Includes{
	public static $includeClasses = array();
	public static function init(){
		Includes::useComponent('NpCustom');
		$default = NP_PATH.'config'.DS.'config.php';
		if(file_exists($default)){
			include $default;
		}
		$config = CUSTOM_PATH.'config.php';
		if(file_exists($config) && is_file($config)){
			include $config;
		}
		$config = CUSTOM_PATH.Configure::getCustomDirectory('config').DS.'config.php';
		if(file_exists($config)){
			include $config;
		}
// 		$language = Configure::$defaultLanguage;
// 		if(isset(Configure::$languages[$language])){
// 			$_SESSION['language'] = $language;
// 		}
	}
	/**
	 * method for auto load a class file when a class not found
	 * @param string $className
	 * @return boolean
	 */
	public static function autoload($className){
		$params = array();
		if(isset(self::$includeClasses[$className])){
			$params = self::$includeClasses[$className];
		}
		$res = self::loadFile($params, $className);
		if($res === false){
			var_dump($className);
// 			var_dump($className);
// 			var_dump(self::$includeClasses);
			throw new FileNotFoundException();
		}
	}
	/**
	 * load a class
	 * @param string $class
	 * @param string $dir
	 * @param boolean $isCore whether the class is from core
	 * @param string/boolean $extension
	 */
	public static function uses($class, $dir = '', $module = false){
		if(!empty($dir)){
			$dir = trim_directory_separator($dir);
		}
		
		if($module !== false){
			$extensions = Configure::$extension;
			$coreExtensions = strtoupper(substr($module, 0, 1)).substr($module, 1);
			$appExtensions = isset($extensions[$module]) ? $extensions[$module] : $coreExtensions;
			$coreClass = $class.$coreExtensions;
			$appClass = Configure::$autoSupplementClassname === false ? $class : $class.$appExtensions;
			
			self::$includeClasses[$coreClass] = array('module' => $module, 'dir' => $dir);
			self::$includeClasses[$appClass] = array('module' => $module, 'dir' => $dir);
			return $appClass;
		}else{
			self::$includeClasses[$class] = array('module' => $module, 'dir' => $dir);
			return $class;
		}
	}
	
	public static function usePlugin($plugin, $dir = ''){
		$dir = $dir == '' ? 'plugin'.DS.strtolower($plugin) : $dir;
		return self::uses($plugin, $dir);
	}
	/**
	 * load a component class
	 * @param string $class
	 */
	public static function useComponent($component = ''){
		return self::uses($component, '', 'component');
	}
	/**
	 * load a service class
	 * @param string $class
	 */
	public static function useService($service = ''){
		return self::uses($service, '', 'service');
	}
	/**
	 * load a model class
	 * @param string $class
	 */
	public static function useModel($model = ''){
		return self::uses($model, '', 'model');
	}
	/**
	 * load a controller class
	 * @param string $class
	 */
	public static function useController($controller = ''){
		return self::uses($controller, '', 'controller');
	}
	
	public static function useView($view = ''){
		return self::uses($view, '', 'view');
	}
	
	static public function useOrm($orm = ''){
		return self::uses($orm, '', 'orm');
	}
	
	static public function useLang($lang){
		return self::uses($lang, '', 'lang');
	}
	
	static public function isCustomAppExisted($app){
		$file = CUSTOM_PATH.Configure::getCustomDirectory('app').DS;
		if(!empty($app)){
			$file .= $app.DS;
		}
		if(is_dir($file)){
			return true;
		}else{
			return false;
		}
	}
	
	static public function isCustomClassExisted($class, $module = 'controller', $app = ''){
		if(Configure::$autoSupplementClassname !== false){
			$class .= Configure::getCustomExtension($module);
		}
		$flag = false;
		if(defined('APP_PATH')){
			$path = APP_PATH;
		}else{
			$path = CUSTOM_PATH.Configure::getCustomDirectory('app').DS;
			$path .= empty($app) ? '' : $app.DS;
		}
		$file = Configure::getCustomDirectory($module).DS.$class;
		if(Configure::$autoSupplementClassname !== false){
			$file .= Configure::getCustomSuffix('class');
		}
		$file .= Configure::getCustomSuffix('file');
		$flag = file_exists($path.$file) ? true : false;
		if(!$flag){
			$path = CUSTOM_PATH;
			$flag = file_exists($path.$file) ? true : false;
		}
		return $flag;
	}
	
	protected static function loadFile($params, $class){
		$dir = isset($params['dir']) ? $params['dir'] : '';
		$module = isset($params['module']) ? $params['module'] : false;
		$coreFile = '';
		$appFile = '';
		if(!empty($dir)){
			$coreFile .= $dir.DS;
			$appFile .= $dir.DS;
		}else{
			if($module !== false){
				$coreFile .= $module.DS;
				$appFile .= Configure::getCustomDirectory($module).DS;
			}
		}
		$coreFile .= $class.'.class.php';
		$appFile .= $class.Configure::getCustomSuffix('class').Configure::getCustomSuffix('file');
		$flag = false;
		$file = APP_PATH.$appFile;
		$flag = self::_loadFile($file);
		if(!$flag){
			$file = CUSTOM_PATH.$appFile;
			$flag = self::_loadFile($file);
		}
		if(!$flag){
			$file = NP_PATH.$coreFile;
			$flag = self::_loadFile($file);
		}
		if(!$flag){
			return false;
		}
		return true;
	}
	/**
	 * load a file
	 * @param string $class
	 * @param string $dir
	 */
	protected static function _loadFile($file){
		if(file_exists($file)){
			$res = include_once $file;
			if(!$res){
				return false;
			}else{
				return true;
			}
		}else{
			return false;
		}
	}
	/**
	 * 
	 * @param string $class
	 * @return string
	 */
	protected static function getDirectoryName($class){
		$flag = Configure::$autoLowerDirectory;
		$extension = Configure::$extension;
		if(!is_array($extension)){
			return $flag === false ? $class : strtolower($class);
		}
		$len = strlen($class);
		foreach ($extension as $ext) {
			$length = strlen($ext);
			$tmp = substr($class, $len - $length);
			if($ext == $tmp){
				return $flag === false ? $ext : strtolower($ext);
			}
		}
		return $flag === false ? $class : strtolower($class);
	}
}