<?php
class Pack extends Object{
	public $lang = array();
	public $common = array();
	public function getLangs(){
		return array_merge($this->common, $this->lang);
	}
}