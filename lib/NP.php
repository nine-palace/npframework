<?php
if(!defined('DS')){	
	define('DS', DIRECTORY_SEPARATOR);
}
if(!defined('ROOT')){
	define('ROOT', dirname(__FILE__).DS.'..'.DS);
}
if(!defined('CUSTOM')){
	define('CUSTOM', 'custom'.DS);
}
if(!defined('CUSTOM_PATH')){
	define('CUSTOM_PATH', ROOT.CUSTOM);
}
if(!defined('NP_PATH')){
	define('NP_PATH', dirname(__FILE__).DS);
}
session_start();
require_once NP_PATH.'common'.DS.'common.function.php';
require_once NP_PATH.'core'.DS.'Includes.class.php';
require_once NP_PATH.'core'.DS.'Configure.class.php';
require_once NP_PATH.'router'.DS.'Dispatcher.class.php';
require_once NP_PATH.'router'.DS.'HttpRequest.class.php';
spl_autoload_register(array('Includes', 'autoload'));
Includes::init();
?>