<?php
class MemoryController extends AppController{
	
	public function index(){
		$a = 'sj;ga';
		$b = &$a;
		unset($a);//
		$a = null;
		echo $a;
		echo $b;
	}
}