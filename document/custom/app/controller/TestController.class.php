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
}