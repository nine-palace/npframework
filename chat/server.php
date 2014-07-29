<?php
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(__FILE__).DS);
define('NP_PATH', ROOT . '..'.DS.'lib'.DS);
include_once NP_PATH.'NP.php';
Includes::usePlugin('Websocket');
Includes::usePlugin('AppWebsocket', 'plugin/websocket');
$dispatcher = new Dispatcher();
$dispatcher->initialize();
$web = new AppWebsocket(Configure::custom('websocket_host'), Configure::custom('websocket_port'));