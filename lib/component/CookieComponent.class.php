<?php
class CookieComponent extends Component{
	public static function write($name, $value){
		$_COOKIE[$name] = $value;
	}
	
	public static function read($name){
		return isset($_COOKIE[$name]) ? $_COOKIE[$name] : null;
	}
	
	public static function delete($name){
		if(isset($_COOKIE[$name])){
			unset($_COOKIE[$name]);
		}
	}
	
	public static function have($name){
		return isset($_COOKIE[$name]) ? true : false;
	}
}