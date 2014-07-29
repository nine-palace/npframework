<?php
class HttpRequest{
	public $app = null;
	public $action = null;
	public $controller = null;
	public $controllerClass = '';
	public $controllerName = '';
	public $http_host = '';
	public $http_user_agent = '';
	public $name = '';
	public $params = array();
	public $request_method = '';
	public $request_uri = '';
	public $query_string = '';
	public $data = '';
	public $http_protol = 'http://';
	public function __construct(){
		$this->http_host = $_SERVER['HTTP_HOST'];
		$this->http_user_agent = $_SERVER['HTTP_USER_AGENT'];
		$this->request_method = $_SERVER['REQUEST_METHOD'];
		$this->request_uri = $_SERVER['REQUEST_URI'];
		$this->query_string = $_SERVER['QUERY_STRING'];
		$this->init();
	}
	
	public function is($mode){
		
	}
	private function init(){
		$url = $_SERVER['REQUEST_URI'];
		$tmp = explode('?', $url);
		$t = explode('.', $tmp[0]);
		$url = $t[0];
		$tmp = explode('/', $url);
		array_shift($tmp);
		$this->params['url'] = $tmp;
		$this->params['get'] = $_GET;
		$this->params['post'] = $_POST;
		$this->params['file'] = $_FILES;
		$loop = array('app', 'controller', 'action');
		foreach ($loop as $v){
			$key = Configure::getCustomUrlKey($v);
			if(isset($_GET[$key])){
				$this->$v = $_GET[$key];
			}
		}
		define('__DOMAIN__', $this->http_protol.$this->http_host.DS);
	}
}
?>