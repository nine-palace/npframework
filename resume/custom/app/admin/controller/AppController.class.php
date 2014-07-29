<?php
Class AppController extends Controller{
	public $layout = 'admin';
	
	public function initialize(){
		$menus = array('dashboard');
		$this->assign('menus', $menus);
	}
	public function beforeRender(){
		if(!isset($this->current['module'])){
			$this->current['module'] = $this->view;
		}
		if(!isset($this->current['title'])){
			$this->current['title'] = $this->view;
		}
		$this->assign('current', $this->current);
	}
}