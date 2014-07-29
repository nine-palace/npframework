<?php
class EncrptComponent extends Component{
	
	public static function aesEncrpt($txt, $key){
		$rawKey = NULL;
		if(strlen($key) >= 16){
			$rawKey = self::getBytes(substr($txt, 0, 16));
		}else{
			$rawKey = self::getBytes($txt);
		}
	}
	
	public static function md5Normal($str){
		$hexDigits = array('0','1','2','3','4','5','6','7','8','9','a','b','c','d','e','f');
		$strTmp = md5($str);
		return $strTmp;
	}
	
	public static function getBytes($str){
		$len = strlen($str);
		$bytes = array();
		for ($i = 0; $i < $len; $i++){
			$bytes[] = ord($str[$i]) >= 128 ? ord($str[$i]) - 256 : ord($str[$i]);
		}
		return $bytes;
	}
	
	public static function toString($bytes){
		$str = '';
		foreach ($bytes as $ch){
			$str .= chr($ch);
		}
		return $str;
	}
	
}