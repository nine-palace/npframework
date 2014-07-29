<?php

/**
 *  Get NP's root directory
 */
define('DS', '/');
define('ROOT', dirname(__FILE__).DS);
define('NP_PATH', ROOT.'..'.DS.'lib'.DS);
include_once NP_PATH.'NP.php';
date_default_timezone_set('Asia/Shanghai');
$dispatcher = new Dispatcher();
$dispatcher->dispatch(new HttpRequest());
?>