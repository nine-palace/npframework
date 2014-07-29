<?php
class SUtilComponent extends Component{
	
	public static function substr($str, $length = 20, $start = 0, $encoding = 'utf-8'){
		return mb_substr($str, $start, $length, $encoding);
	}
	
	public static function upload($_files, $key, $prefix = ''){
		if(!isset($_files[$key])){
			return false;
		}
		$dir = CUSTOM_PATH.'public/';
		
		if(!file_exists($dir) || !is_dir($dir)){
			mkdir($dir, 0777);
		}
		$dir .= 'upload/';
		if(!file_exists($dir) || !is_dir($dir)){
			mkdir($dir, 0777);
		}
		$currentDay = date('Ym', time());
		$dir .= $currentDay.'/';
		if(!file_exists($dir) || !is_dir($dir)){
			mkdir($dir, 0777);
		}
		$url = __DOMAIN__.CUSTOM.'public/upload/'.$currentDay.'/';
		$tmp = explode('.', $_files[$key]['name']);
		$suffix = end($tmp);
		$file = md5($prefix.time()).'.'.$suffix;
		$res = move_uploaded_file($_files[$key]['tmp_name'], $dir.$file);
		if($res == false){
			return false;
		}else{
			return $url.$file;
		}
	}
}