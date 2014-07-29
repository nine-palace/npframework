<?php
Includes::useComponent('SUtil');
class AppController extends Controller{
	public $layout = 'default';
	public function beforeRender(){
		parent::beforeRender();
		$level = Configure::getCustom('func_admin_category_two') == true ? 2 : 1;
		$main_menus = S('category')->getParts(true, 0, $level);
		$main_menus = $this->singleLanguageFilter($main_menus, 'name');
		foreach ($main_menus as $k => $v){
			if(isset($v['direct_cates'])){
				$main_menus[$k]['direct_cates'] = $this->singleLanguageFilter($v['direct_cates'], 'name');
			}
		}
		$this->assign('main_menus', $main_menus);
		$service = S('article');
		$latest = $service->getCategoryList(32, '', 10);
		$this->assign('latest', $latest);
		$tmp = $service->getArticles(12, '', 1);
		$address = isset($tmp['list'][0], $tmp['list'][0]['content']) ? $tmp['list'][0]['content'] : '';
		$this->assign('address', $address);
		
	}
}