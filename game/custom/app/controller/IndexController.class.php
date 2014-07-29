<?php
Includes::useService('Spoker');
class IndexController extends AppController{
	public $autoLayout = false;
	public function index(){
		$service = new SpokerService();
		$tmp = $service->shuffle();
		$this->assign('hands', $tmp['hands']);
		$this->assign('rest', $tmp['rest']);
// 		$this->view = 'test';
	}
	
	public function test(){
		$name = 'aaa';
		$list="<input name=\"user\" size=\"6\" class=\"input\" type=\"text\" value=\"$name\">";
		echo $list;
		$this->autoView = false;
	}
}
?>