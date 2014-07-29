<?php
class IndexController extends AppController{
	
	public function index(){
		$this->redirect(__DOMAIN__.'admin/article.html');
	}
	
}