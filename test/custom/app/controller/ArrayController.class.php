<?php
class ArrayController extends AppController{
	
	public function index(){
		$arr1 = array(3 => 'aaa', 5 => 'bbb');
		$arr2 = array(3 => 'ddd', 'kkk' => 'kjljlj');
		$arr = array(1, 2, 3, 4, 5, 6);
		$t = array_chunk($arr, 2);
		
		$t1 = array_combine($arr1, $arr2);
		$t2 = array_diff_assoc($arr1, $arr2);
		$t3 = array_diff_key($arr1, $arr2);
		$t4 = array_diff($arr1, $arr2);
		var_dump($t4);
	}
}