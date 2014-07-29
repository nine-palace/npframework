<?php
class FileController extends AppController{
	
	public function index(){
		$t = new Test();
		$t->index();$t->test1();var_dump($t);exit;
		$path = 'F:/wamp/www';
		$dir = opendir($path);
		while(($file = readdir($dir)) !== false){
			echo "filename:$file<br />";
			var_dump(pathinfo($path.'/'.$file));
		}
		closedir($dir);
	}
}
class Test{
	private function __construct(){
		
	}
	public function index(){
		echo '111111';
	}
	protected function test1(){
		echo '22222';
	}
}
class Test123 extends Test{
	public function __construct(){
		echo 'jjj';
	}
	
	public function test1(){
		echo '3333';
	}
}