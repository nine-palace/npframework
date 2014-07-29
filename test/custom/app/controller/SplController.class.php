<?php
class SplController extends AppController{
	
	public function index(){
		
		$spl = new SplDoublyLinkedList();
		$spl->unshift(array(123, 456, 222));
		$spl->unshift(array('aaa', 'bbb', 'ccc'));
		$key = $spl->key();
		$value = $spl->next();
		$now = $spl->shift();
		$value = $spl->offsetGet(0);
		var_dump($now);
		var_dump($value);
		var_dump($key);
// 		$spl->bottom();
// 		$spl->prev();
		$value = $spl->current();
		var_dump($value);
	}
	
	public function test(){
		$array = array(1, 2, 3, 4);
		$it = new MyIterator($array);
		foreach ($it as $key => $value){
			echo $key.':'.$value.'<br />';
		}
	}
}

class MyIterator implements Iterator{
	protected $position;
	protected $arr;
	
	public function __construct($array){
		$this->arr = $array;
		$this->position = 0;
	}
	
	public function rewind(){
		var_dump(__METHOD__);
		$this->position = 0;
	}
	
	public function valid(){
		var_dump(__METHOD__);
		return isset($this->arr[$this->position]);
	}
	
	public function key(){
		var_dump(__METHOD__);
		return $this->position;
	}
	
	public function current(){
		var_dump(__METHOD__);
		return $this->arr[$this->position];
	}
	
	public function next(){
		var_dump(__METHOD__);
		return ++$this->position;
	}
}