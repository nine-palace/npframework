<?php
class IndexController extends AppController{
	public $autoLayout = false;
	public function index(){
	}
	
	private function defaultPage(){
	}
	
	private function userPage(){
	}
	
	public function info(){
		echo phpinfo();
		$this->autoView = false;
	}
	
	public function ticket(){
		$service = S('test');
		$t = $service->queryTicket();
		if($t['status']){
			$s = simplexml_load_string($t['data']);
			var_dump($s);
		}
		$this->autoView = false;
	}
}
?>