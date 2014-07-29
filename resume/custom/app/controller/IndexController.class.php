<?php
class IndexController extends AppController{
	public function index(){
		$menu = array(
				'index' => 'Home',
				'business' => 'Business Summit',
				'room' => 'Rooms Tariff'
		);
		$this->assign('menu', $menu);
	}
	
	public function test(){
		var_dump(date('Y-m-d H:i:s', 1370769041));
	}
}
?>