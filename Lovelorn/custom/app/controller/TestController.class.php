<?php
class TestController extends AppController{
	public $autoLayout = false;
	public $autoView = false;
	public function index(){
		Includes::useComponent('PinyinMulti');
		$t = '重庆';
		PinyinMultiComponent::$isFirstUpper = true;
		PinyinMultiComponent::$prefix = '_';
		PinyinMultiComponent::$returnArray = false;
		$s = PinyinMultiComponent::getPinyin($t);
		var_dump($s);
	}
	public function out(){
		$t = 'false';
		var_dump($t);
		$s = (boolean)$t;
		var_dump($s);
	}
	
	public function file(){
		$this->autoView = true;
		var_dump($_FILES);
	}
	
	public function beforeRender(){
		
	}
	public function test(){
		$t = '123123123';
		$tt = '123abs';
		var_dump(is_numeric($t), is_numeric($tt));
	}
	
	public function ruidu(){
		$a = '莽荒纪';
		$b = '﻿莽荒纪';
		var_dump($a, $b, substr($b, 3));
		var_dump(ctype_cntrl($a), ctype_cntrl($b));
		var_dump($this->trimstr($a), $this->trimstr($b));
		if($a === $b){
			var_dump(1);
		}else{
			var_dump(2);
		}
		
	}
	
	public function sms(){
		$content = '专业专业专业专业专业专业专业专业专业专业专业专业专业专业专业专业专业专业专业专业';
		$content .= '专业专业专业专业专业专业专业专业专业专业专业专业专业专业专业专业专业专业专业专业';
		$content .= '专业专业专业专业专业专业专业专业专业专业专业专业专业专业专业专业专业专业专业专业';
		$content .= '专业专业专业专业专业专业专业专业专业专业专业专业专业专业专业专业专业专业专业专业';
// 		$content = '专';
		var_dump(strlen($content));
		$cont = $this->usrp_socket_encode($content);
		var_dump($cont);
	}
	
	protected function trimstr($str){
		$str = trim($str);
		$arr = array("\r\n" => "", "\n" => '', "\r" => '', "\t" => '', chr(9) => '', "\\n" => "");
	}
	
	private function usrp_socket_encode($s)
	{
		$s=$this->str_base_convert(bin2hex(mb_convert_encoding($s,'UCS-2', 'UTF-8')),16,2);
		$l=strlen($s);
		for($i=0;$l%16&&$i<16-$l%16;$i++)
		{
		$s="0".$s;
		}
				return $s;
	}
	private function str_base_convert($str, $frombase=10, $tobase=36) {
	$str = trim($str);
	if (intval($frombase) != 10) {
	$len = strlen($str);
	$q = 0;
	for ($i=0; $i<$len; $i++) {
		$r = base_convert($str[$i], $frombase, 10);
		$q = bcadd(bcmul($q, $frombase), $r);
		}
		}
			else $q = $str;
	
			if (intval($tobase) != 10) {
			$s = '';
			while (bccomp($q, '0', 0) > 0) {
			$r = intval(bcmod($q, $tobase));
			$s = base_convert($r, 10, $tobase) . $s;
			$q = bcdiv($q, $tobase, 0);
			}
			}
				else $s = $q;
	
				return $s;
			}
	
					private function encode_wappush($url,$content){
					$data[0] ="0605040b8423f0120601ae02056a0045c6080c03";
					$data[1] = bin2hex($url);//url here
					$data[2] = "000103";
				$data[3] = bin2hex($content);//content here
				$data[4] = "000101";
				return $this->str_base_convert(join('',$data),16,2);
	}
}