<?php
Includes::useComponent('SUtil');
class AppController extends Controller{
	public $layout = 'flower';
	public function beforeRender(){
		parent::beforeRender();
		$this->assign('system_site_name', Configure::getCustom('system_site_name'));
		$main_menus = S('category')->getParts(true, 0, 2);
		$this->assign('main_menus', $main_menus);
		$service = S('article');
		$latest = $service->getArticles(4, '', 4);
		$this->assign('latest', $latest['list']);
		
		$tmp = $service->getArticles(5, '', 1);
		$address = isset($tmp['list'][0], $tmp['list'][0]['content']) ? $tmp['list'][0]['content'] : '';
		$this->assign('address', $address);
		
		$this->assign('default_recom_image', __PUBLIC__.'images/default_recom.jpg');
		$this->assign('default_article_image', __PUBLIC__.'images/default_article.jpg');
	}
}