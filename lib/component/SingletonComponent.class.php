<?php
class SingletonComponent{
	static protected $singleton = null;
	
	protected function __construct(){
		
	}
	
	static public function instance(){
		if(is_null(self::$singleton)){
			self::$singleton = new SingletonComponent();
		}
		return self::$singleton;
	}
}