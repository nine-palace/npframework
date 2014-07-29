<?php
Includes::useComponent('SUtil');
class IndexController extends AppController{
	public function index(){
		$parts = S('category')->getList(array('is_index_show' => 1), '*', 'order_asc ASC,created DESC', '', 2);
		$parts = $parts['list'];
		$articleService = S('article');
		foreach ($parts as $key => $value){
			$tmp = $articleService->getList(array('cate_id' => $value['id']), '*', 'order_asc ASC,created DESC', '', 4);
			$parts[$key]['articles'] = $tmp['list'];
		}
		$this->assign('parts', $parts);
		$main_menus = S('category')->getParts(true, 0, 2);
		$this->assign('main_menus', $main_menus);
		$recoms = S('category')->getList(array('is_index_recom' => 1), '*', 'order_asc ASC,created DESC', '', 1);
		$recoms = $recoms['list'];
		foreach ($recoms as $key => $value){
			$tmp = $articleService->getList(array('cate_id' => $value['id']), '*', 'order_asc ASC,created DESC', '', 4);
			$recoms[$key]['articles'] = $tmp['list'];
		}
		$this->assign('recoms', $recoms);
		$this->current['module'] = 'index';
		$this->assign('current', $this->current);
	}
}