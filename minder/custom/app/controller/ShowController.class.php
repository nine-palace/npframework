<?php
class ShowController extends AppController{
	protected $id = '';
	protected $left_index_id = '';
	
	public function initialize(){
		parent::initialize();
		if(isset($this->request->params['url'][0]) && is_numeric($this->request->params['url'][0])){
			$this->id = $this->request->params['url'][0];
		}
		if(isset($this->request->params['post']['id']) && !empty($this->request->params['post']['id'])){
			$this->id = $this->request->params['post']['id'];
		}
		if(isset($this->request->params['get']['id']) && !empty($this->request->params['get']['id'])){
			$this->id = $this->request->params['get']['id'];
		}
		
		if(empty($this->id)){
			$this->redirectIndex();
		}
	}
	public function index(){
		$this->left_index_id = $this->id;
		$articles = S('article')->getArticles($this->id, 4);
		if(count($articles) == 1 && isset($articles[0]['id'])){
			$this->redirect(__DOMAIN__.'show/detail.html?id='.$articles[0]['id']);
		}
		$this->assign('articles', $articles);
		$info = D('category')->findById($this->id, 'name');
		if($info == false){
			$this->redirectIndex();
		}
		$this->current['left_index_name'] = $info['name'];
	}
	
	public function detail(){
		$info = D('article')->findById($this->id);
		if($info == false){
			$this->redirectIndex();
		}
		$this->left_index_id = $info['cate_id'];
		$this->assign('article', $info);
	}
	
	public function beforeRender(){
		parent::beforeRender();
		$service = S('category');
		$main_menus = $service->getParts(true, 0, 1);
		$this->assign('main_menus', $main_menus);
		$tmp = S('category')->getParentDirectPart($this->left_index_id);
		if($tmp == false){
			$this->redirectIndex();
		}
		$this->current['main_index_id'] = $tmp['parent_id'];
		$this->current['left_index_id'] = $tmp['cate_id'];
		$left_menus = $service->getParts(false, $tmp['parent_id'], 1);
		$this->assign('left_menus', $left_menus);
		
		$info = D('category')->findById($this->current['main_index_id'], 'name');
		$this->current['main_index_name'] = $info['name'];
		
		if(!isset($this->current['left_index_name'])){
			if($this->current['left_index_id'] == -1){
				$this->current['left_index_name'] = $this->current['main_index_name'];
			}else{
				$tmpInfo = D('category')->findById($this->current['left_index_id'], 'name');
				$this->current['left_index_name'] = $tmpInfo['name'];
			}
		}
		$this->assign('current', $this->current);
		$this->view = 'minder';
	}
	
	protected function redirectIndex(){
		$this->redirect(__DOMAIN__);
	}
}