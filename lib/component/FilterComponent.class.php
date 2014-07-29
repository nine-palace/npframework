<?php
class FilterComponent extends Component{
	static $keyword = array();
	
	static function getBadWords($filename){
		$handle = fopen($filename, 'r');
		while(!feof($handle)){
			$line = trim(fgets($handle));
			array_push(self::$keyword, $line);
		}
		fclose($handle);
		return self::$keyword;
	}
	
	static function filter($content, $target, $filename, $memconfig){
		$keyword = self::getBadWords($filename);
		
		return strtr($content, array_combine($keyword, array_fill(0, count($keyword), $target)));
	}
}

class BadWordsMemcache{
	
	
}