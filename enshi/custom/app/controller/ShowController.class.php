<?php
class ShowController extends AppController{
	protected $id = '';
	protected $cate_id = '';
	
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
	}
	public function index(){
		$this->current['cate_id'] = $this->id;
		$this->current['keywords'] = isset($this->request->params['get']['keywords']) ? $this->request->params['get']['keywords'] : '';
		if(!empty($this->id)){
			$cate = D('category')->findById($this->id, 'content_type');
			$this->current['content_type'] = $cate === false ? 'news' : $cate['content_type'];
		}else{
			$this->current['content_type'] = 'news';
		}
		$limit = 10;
		$page = 1;
		if(isset($this->request->params['get']['page']) && is_numeric($this->request->params['get']['page'])){
			$page = $this->request->params['get']['page'] > 1 ? $this->request->params['get']['page'] : 1;
		}
		$model = in_array($this->current['content_type'], array('product', 'photo')) ? $this->current['content_type'] : 'article';
		$limit = $this->current['content_type'] == 'photo' ? -1 : $limit;
		$list = S($model)->getArticles($this->id, $this->current['keywords'], $limit, $page);
		$count = $list['count'];
		$list = $list['list'];
		if($this->current['content_type'] == 'introduction' && isset($list[0]['id'])){
			$this->redirect(__DOMAIN__.'show/detail.html?id='.$list[0]['id']);
		}
		if($count > 0){
			$pages = $this->pages($count, $limit, $page);
			$this->assign('pages', $pages);
		}
		$this->assign('list', $list);
	}
	
	public function detail(){
		$this->single('article');
	}
	
	public function product(){
		$this->single('product');
	}
	
	public function beforeRender(){
		parent::beforeRender();
		$model = $this->current['content_type'] == 'product' ? 'product' : 'article';
		$limit = $this->current['content_type'] == 'product' ? 6 : 5;
		$hots = S($model)->getList('', '', array('views DESC,updated DESC'), $limit);
		$hots = $hots['list'];
		$this->assign('hots', $hots);
		$directs = S('category')->getDirectParts($this->current['cate_id']);
		$this->assign('directs', $directs);
		$keywords = isset($this->current['keywords']) ? $this->current['keywords'] : '';
		$direct_menus = S('category')->getDirects($this->current['cate_id'], $keywords);
		$this->assign('direct_menus', $direct_menus);
		$this->assign('current', $this->current);
		$this->view = 'enshi';
	}
	
	protected function redirectIndex(){
		$this->redirect(__DOMAIN__);
	}
	
	private function single($model){
		$info = D($model)->findById($this->id);
		if($info == false){
			$this->redirectIndex();
		}
		$views = $info['views'] + 1;
		$this->current['cate_id'] = $info['cate_id'];
		$res = D($model)->update(array('id' => $this->id), array('views' => $views));
		$this->current['content_type'] = $model == 'product' ? 'product' : 'news';
		$this->assign('detail', $info);
	}
}