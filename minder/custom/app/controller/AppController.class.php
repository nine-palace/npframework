<?php
Includes::useComponent('SUtil');
class AppController extends Controller{
	public $layout = 'minder';
	public function beforeRender(){
		parent::beforeRender();
		$this->assign('default_recom_image', __PUBLIC__.'images/default_recom.jpg');
		$this->assign('default_article_image', __PUBLIC__.'images/default_article.jpg');
	}
}