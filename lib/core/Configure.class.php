<?php
class Configure{
	/**
	 * auto assign the language variable to template
	 */
	public static $autoAssignLanguage = true;
	/**
	 * auto cache the compile file
	 */
	public static $autoCacheCompileFile = true;
	/**
	 * auto change the current work directory
	 */
	public static $autoChangeWorkPath = true;
	/**
	 * auto create directory when it doesnot exists
	 */
	static public $autoCreateDirectory = true;
	/**
	 * auto distinguish a directory
	 */
	public static $autoDistinguishDir = true;
	/**
	 * auto render the layout file
	 */
	public static $autoLayout = true;
	/**
	 * auto choose layout file from a style template
	 */
	public static $autoLayoutFromStyleTemplate = false;
	/**
	 * auto lower directory
	 */
	public static $autoLowerDirectory = true;
	/**
	 * auto supplement for a class by configured class suffix
	 */
	public static $autoSupplementClassname = true;
	/**
	 * auto supplement for directory by configured default module directory
	 */
	public static $autoSupplementDirectory = true;
	/**
	 * auto supplement for script label or css label in template
	 */
	public static $autoSupplementLabel = false;
	/**
	 * auto transform to controller class name from a separated class name
	 */
	public static $autoTransformController = true;
	/**
	 * auto transform to table name from a separated table name
	 */
	public static $autoTransformTablename = true;
	/**
	 * auto update compile file if the template file had modified
	 */
	public static $autoUpdateCompileFile = true;
	/**
	 * auto render view template file
	 */
	public static $autoView = true;
	/**
	 * the key for display language in session
	 */
	public static $sessionKeyForLanguage = 'np_language';
	/**
	 * is support multi-language
	 */
	public static $multiLanguage = true;
	/**
	 * supported languages list
	 */
	public static $availableLanguages = array(
			'chinese' => '简体中文',
			'english' => 'English'
	);
	/**
	 * define the directory name for modules
	 */
	public static $customDirectory = array(
			'app' => 'app',
			'controller' => 'controller',
			'component' => 'component',
			'view' => 'view',
			'config' => 'config',
			'compile' => '.compile',
			'layout' => 'layout',
			'model' => 'model',
			'service' => 'service',
			'orm' => 'orm',
			'lang' => 'lang',
			'static' => 'static'
			);
	/**
	 * the default language for display
	 */
	public static $defaultLanguage = 'Chinese';
	/**
	 * the database config
	 */
	public static $dbConfig = array(
			'default' => array(
					'host' => 'localhost',
					'port' => '3306',
					'user' => 'root',
					'pwd' => '',
					'prefix' => 'np_',
					'dbName' => 'music',
					'charset' => 'utf8'
			),
			'mysql' => array(
			)
			);
	/**
	 * define the variable name for modules if use the parameters for MVC
	 */
	static public $urlKey = array(
			'app' => 'app',
			'controller' => 'model',
			'action' => 'action'
			);
	/**
	 * the default value for modules
	 */
	public static $router = array('app' => '', 'controller' => 'Index', 'action' => 'index');
	/**
	 * 
	 * @var unknown
	 */
	public static $scriptFilePath = '';
	/**
	 * defined file suffix for different types
	 */
	public static $suffix = array(
			'file' => '.php',
			'class' => '.class',
			'layout' => '.html',
			'compile' => '.php',
			'template' => '.html',
			'view' => '.html'
			);
	/**
	 * defined extension name for different types
	 */
	public static $extension = array(
			'controller' => 'Controller',
			'component' => 'Component',
			'exception' => 'Exception',
			'model' => 'Model',
			'service' => 'Service',
			'view' => 'View',
			'orm' => 'Orm',
			'lang' => 'Pack'
			);
	/**
	 * defined the public file directory name
	 */
	public static $publicFileDirectory = 'public';
	/**
	 * defined separator for different types
	 */
	public static $separator = array(
			'controller' => '_',
			'table' => '_',
			'dataType' => '/'
			);
	/**
	 * custom defined data
	 */
	static public $custom = array();
	/**
	 * defined user permissions
	 */
	static public $purview = array();
	
	/**
	 * if value is null excute get operating else excute set operating
	 * @param string $key
	 * @param mixed $value
	 */
	static public function custom($key, $value = NULL){
		return $value === NULL ? self::getCustom($key) : self::setCustom($key, $value);
	}
	/**
	 * get custom config data
	 * @param string $key
	 * @return return data if exists the key or null for else
	 */
	static public function getCustom($key){
		return isset(self::$custom[$key]) ? self::$custom[$key] : NULL;
	}
	/**
	 * set custom config data
	 * @param string $key
	 * @param mixed $value
	 * @return boolean
	 */
	static public function setCustom($key, $value){
		self::$custom[$key] = $value;
		return true;
	}
	/**
	 * get a property's value
	 * @param string $property
	 * @param string $module
	 * @return mixed
	 */
	static public function get($property, $module = ''){
		$tmp = self::$$property;
		if(!is_array($tmp)){
			return $tmp;
		}else{
			return empty($module) ? $tmp : (isset($tmp[$module]) ? $tmp[$module] : $module);
		}
	}
	/**
	 * get the custom defined directory name
	 * @param string $module
	 */
	static public function getCustomDirectory($module){
		return self::get('customDirectory', $module);
	}
	
	static public function getCustomExtension($module){
		return self::get('extension', $module);
	}
	
	static public function getCustomSuffix($module){
		return self::get('suffix', $module);
	}
	
	static public function getCustomRouter($module){
		if(isset(self::$router[$module])){
			return self::$router[$module];
		}else{
			switch($module){
				case 'app' : return '';
				case 'controller' : return 'Index';
				case 'action' : return 'index';
				default: return $module;
			}
		}
	}
	
	static public function getCustomUrlKey($module){
		return self::get('urlKey', $module);
	}
	
	static public function getCustomDbConfig($type, $key = ''){
		$default = self::$dbConfig['default'];
		$db = isset(self::$dbConfig[$type]) ? array_merge($default, self::$dbConfig[$type]) : $default;
		return empty($key) ? $db : (isset($db[$key]) ? $db[$key] : null);
	}
	
	static public function getCustomSeparator($module){
		$se = self::get('separator', $module);
		return $se === $module ? '_' : $se;
	}
	
	static public function set($key, $pro, $value = ''){
		$tmp = self::$$pro;
		if(is_array($key)){
			foreach ($key as $k => $v){
				$tmp[$k] = $v;
			}
		}else{
			$tmp[$key] = $value;
		}
		self::$$pro = $tmp;
	}
	
	public static function setRouter($key, $value = ''){
		self::set($key, 'router', $value);
	}
	
	public static function setDatasource($key, $value = '', $type = 'mysql'){
		if(isset(self::$dbConfig[$type])){
			self::$dbConfig[$type][$key] = $value;
		}else{
			self::$dbConfig[$type] = array($key => $value);
		}
	}
	public static function setExtension($key, $value = ''){
		self::set($key, 'extension', $value);
	}
	public static function setSeparator($key, $value = ''){
		self::set($key, 'separator', $value);
	}
	public static function setSuffix($key, $value = ''){
		self::set($key, 'suffix', $value);
	}
	public static function setLanguages($key, $value = ''){
		self::set($key, 'languages', $value);
	}
	public static function setCustomDirectory($key, $value = ''){
		self::set($key, 'customDirectory', $value);
	}
	public static function setUserPurview($key, $value = false){
		self::set($key, 'purview', $value);
	}
	public static function getUserPurview($key, $default = false){
		$pu = self::get('purview', $key);
		return $pu === $key ? $default : $pu;
	}
}