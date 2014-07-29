<?php
class StringController extends AppController{
	
	public function index(){
		echo str_repeat('ab', 10);
	}
	
	public function cookie(){
		setcookie('a', 1);
		echo $_COOKIE['a'];
	}
}