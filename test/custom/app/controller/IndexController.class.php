<?php
class IndexController extends AppController{
	
	public function index(){
		$string = '123456';
		
		var_dump(md5($string));
	}
}