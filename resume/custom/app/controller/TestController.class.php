<?php
class TestController extends AppController{
	public function index(){
		$url = 'http://www.86yoyo.com/do.php?ac=reg&&ref';
		echo date('Y-m-d H:i:s', time());exit;
	}
}